/**
 * ImageCompressor — Client-side image compression before upload
 * Supports: JPEG, PNG, WEBP → compressed JPEG/WEBP
 * Usage:
 *   ImageCompressor.attachTo(inputEl, options)
 *   ImageCompressor.compressAll(files, options) → Promise<File[]>
 *   ImageCompressor.compress(file, options)     → Promise<File>
 */

;(function (global) {
    'use strict';

    /* ─── Default options ─────────────────────────────────────────── */
    const DEFAULTS = {
        maxWidth:      1920,   // px
        maxHeight:     1920,   // px
        quality:       0.82,   // 0–1
        outputFormat:  'image/jpeg',  // output MIME
        showProgress:  false,  // show inline progress UI
    };

    /* ─── Helpers ─────────────────────────────────────────────────── */
    function mergeOpts(opts) {
        return Object.assign({}, DEFAULTS, opts || {});
    }

    function formatBytes(bytes) {
        if (bytes < 1024)        return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function ext(mimeOrOpts) {
        const mime = typeof mimeOrOpts === 'string'
            ? mimeOrOpts
            : (mimeOrOpts.outputFormat || DEFAULTS.outputFormat);
        return mime === 'image/webp' ? '.webp' : '.jpg';
    }

    /* ─── Core compress (single File → File) ──────────────────────── */
    function compress(file, opts) {
        opts = mergeOpts(opts);

        return new Promise(function (resolve, reject) {
            if (!file || !file.type.startsWith('image/')) {
                return resolve(file);
            }

            const url = URL.createObjectURL(file);
            const img = new Image();

            img.onload = function () {
                URL.revokeObjectURL(url);

                let w = img.naturalWidth;
                let h = img.naturalHeight;

                // Scale down if needed
                const maxW = opts.maxWidth  || DEFAULTS.maxWidth;
                const maxH = opts.maxHeight || DEFAULTS.maxHeight;

                if (w > maxW || h > maxH) {
                    const ratio = Math.min(maxW / w, maxH / h);
                    w = Math.round(w * ratio);
                    h = Math.round(h * ratio);
                }

                // If image is already smaller & quality would be lossless skip heavy recompression
                const skipCompress = (img.naturalWidth <= maxW &&
                                      img.naturalHeight <= maxH &&
                                      file.size < 200 * 1024);

                if (skipCompress) return resolve(file);

                const canvas = document.createElement('canvas');
                canvas.width  = w;
                canvas.height = h;

                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#fff';       // white bg for transparent PNGs
                ctx.fillRect(0, 0, w, h);
                ctx.drawImage(img, 0, 0, w, h);

                canvas.toBlob(function (blob) {
                    if (!blob) return resolve(file);

                    // Only replace if compressed version is actually smaller
                    if (blob.size >= file.size) return resolve(file);

                    const baseName = file.name.replace(/\.[^.]+$/, '') + ext(opts);
                    const compressed = new File([blob], baseName, {
                        type: opts.outputFormat || DEFAULTS.outputFormat,
                        lastModified: Date.now(),
                    });

                    resolve(compressed);
                }, opts.outputFormat || DEFAULTS.outputFormat, opts.quality || DEFAULTS.quality);
            };

            img.onerror = function () {
                URL.revokeObjectURL(url);
                resolve(file);   // fallback: use original
            };

            img.src = url;
        });
    }

    /* ─── Compress array of files ──────────────────────────────────── */
    function compressAll(files, opts) {
        const arr = Array.from(files || []);
        return Promise.all(arr.map(function (f) { return compress(f, opts); }));
    }

    /* ─── Replace FileList on an <input> element ───────────────────── */
    function replaceFiles(input, files) {
        const dt = new DataTransfer();
        files.forEach(function (f) { dt.items.add(f); });
        input.files = dt.files;
    }

    /* ─── Progress UI ──────────────────────────────────────────────── */
    function createProgressUI(input) {
        const wrap = document.createElement('div');
        wrap.className = 'ic-progress-wrap';
        wrap.style.cssText = [
            'display:none',
            'margin-top:8px',
            'padding:8px 12px',
            'background:#f0f7ff',
            'border:1px solid #90caf9',
            'border-radius:8px',
            'font-family:sans-serif',
            'font-size:12px',
            'color:#1565c0',
        ].join(';');

        const bar = document.createElement('div');
        bar.style.cssText = [
            'height:4px',
            'background:#1565c0',
            'border-radius:4px',
            'width:0%',
            'transition:width 0.2s ease',
            'margin-top:6px',
        ].join(';');

        const label = document.createElement('div');
        label.textContent = 'Compressing images…';

        wrap.appendChild(label);
        wrap.appendChild(bar);

        input.parentNode.insertBefore(wrap, input.nextSibling);

        return {
            show: function () { wrap.style.display = 'block'; },
            hide: function () { wrap.style.display = 'none'; },
            update: function (pct, text) {
                bar.style.width = pct + '%';
                label.textContent = text || ('Compressing… ' + pct + '%');
            },
            done: function (savedBytes, totalFiles) {
                bar.style.width = '100%';
                bar.style.background = '#27ae60';
                wrap.style.background = '#f0fdf4';
                wrap.style.borderColor = '#81c784';
                wrap.style.color = '#155724';
                const saved = savedBytes > 0
                    ? ' (saved ' + formatBytes(savedBytes) + ')'
                    : '';
                label.textContent = '✓ ' + totalFiles + ' image' + (totalFiles !== 1 ? 's' : '') + ' ready' + saved;
                setTimeout(function () { wrap.style.display = 'none'; }, 3000);
            },
        };
    }

    /* ─── attachTo — primary public API ───────────────────────────── */
    /**
     * Attach auto-compression to a file input.
     *
     * @param {HTMLInputElement} input
     * @param {object} opts
     * @param {Function} [onChange]  Optional callback(compressedFiles) after done
     */
    function attachTo(input, opts, onChange) {
        if (!input) return;
        opts = mergeOpts(opts);

        let ui = null;
        if (opts.showProgress) {
            ui = createProgressUI(input);
        }

        input.addEventListener('change', async function () {
            const rawFiles = Array.from(input.files);
            if (!rawFiles.length) return;

            if (ui) { ui.show(); ui.update(0, 'Starting…'); }

            let totalOriginal   = 0;
            let totalCompressed = 0;
            const compressed    = [];

            for (let i = 0; i < rawFiles.length; i++) {
                const file   = rawFiles[i];
                totalOriginal += file.size;

                const result = await compress(file, opts);
                compressed.push(result);
                totalCompressed += result.size;

                const pct  = Math.round(((i + 1) / rawFiles.length) * 100);
                const text = 'Compressing ' + (i + 1) + ' / ' + rawFiles.length + '…';
                if (ui) ui.update(pct, text);
            }

            replaceFiles(input, compressed);

            const saved = Math.max(0, totalOriginal - totalCompressed);
            if (ui) ui.done(saved, compressed.length);

            // Fire a custom event so other code can react
            input.dispatchEvent(new CustomEvent('compressed', {
                bubbles: true,
                detail: { files: compressed, savedBytes: saved },
            }));

            if (typeof onChange === 'function') onChange(compressed);
        });
    }

    /* ─── Export ───────────────────────────────────────────────────── */
    global.ImageCompressor = {
        compress:    compress,
        compressAll: compressAll,
        attachTo:    attachTo,
        replaceFiles: replaceFiles,
        formatBytes: formatBytes,
    };

}(window));