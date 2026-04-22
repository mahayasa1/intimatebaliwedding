/**
 * ImageCompressor v2.0
 * ============================================================
 * Client-side image compression SEBELUM upload ke server.
 *
 * Fitur:
 *  - Resize otomatis (default max 1280px)
 *  - Output WebP dengan kualitas adaptif
 *  - Fallback JPEG jika browser tidak support WebP encode
 *  - Skip kompresi jika file sudah kecil (< 200KB + dimensi kecil)
 *  - Progress bar built-in per file
 *  - Multi-file compression dengan antrian
 *  - Drag & drop ready
 *
 * Usage:
 *   // Attach ke input file - otomatis compress saat user memilih file
 *   ImageCompressor.attachTo(inputElement, options, onDoneCallback);
 *
 *   // Compress satu file manual
 *   const result = await ImageCompressor.compress(file, options);
 *
 *   // Compress banyak file sekaligus
 *   const results = await ImageCompressor.compressAll(filesArray, options);
 *
 *   // Ganti FileList pada input element
 *   ImageCompressor.replaceFiles(inputEl, [file1, file2]);
 *
 * Options (semua opsional):
 *   maxWidth       : number  - lebar maksimum px (default: 1280)
 *   maxHeight      : number  - tinggi maksimum px (default: 1280)
 *   quality        : number  - kualitas 0.0-1.0 (default: 0.82)
 *   outputFormat   : string  - 'image/webp' | 'image/jpeg' (default: 'image/webp')
 *   maxSizeKB      : number  - target max ukuran KB, 0 = tidak dibatasi (default: 0)
 *   showProgress   : boolean - tampilkan UI progress (default: false)
 *   skipIfSmall    : boolean - skip jika sudah kecil (default: true)
 *   skipThresholdKB: number  - threshold skip dalam KB (default: 200)
 * ============================================================
 */

;(function (global) {
    'use strict';

    /* ─────────────────────────────────────────────────────────
       DEFAULT OPTIONS
    ───────────────────────────────────────────────────────── */
    var DEFAULTS = {
        maxWidth        : 1280,
        maxHeight       : 1280,
        quality         : 0.82,
        outputFormat    : 'image/webp',
        maxSizeKB       : 0,       // 0 = tidak ada target ukuran
        showProgress    : false,
        skipIfSmall     : true,
        skipThresholdKB : 200,     // skip jika < 200KB DAN dimensi sudah kecil
    };

    /* ─────────────────────────────────────────────────────────
       DETEKSI SUPPORT WEBP ENCODE
    ───────────────────────────────────────────────────────── */
    var _webpEncodeSupported = null;

    function supportsWebpEncode() {
        if (_webpEncodeSupported !== null) return _webpEncodeSupported;
        try {
            var c = document.createElement('canvas');
            c.width = 1; c.height = 1;
            _webpEncodeSupported = c.toDataURL('image/webp').indexOf('data:image/webp') === 0;
        } catch (e) {
            _webpEncodeSupported = false;
        }
        return _webpEncodeSupported;
    }

    /* ─────────────────────────────────────────────────────────
       HELPER UTILS
    ───────────────────────────────────────────────────────── */
    function mergeOpts(opts) {
        var o = {};
        for (var k in DEFAULTS) o[k] = DEFAULTS[k];
        if (opts) for (var k in opts) o[k] = opts[k];
        return o;
    }

    function formatBytes(bytes) {
        if (bytes < 1024)        return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    function getExt(mimeType) {
        if (mimeType === 'image/webp') return '.webp';
        if (mimeType === 'image/png')  return '.png';
        return '.jpg';
    }

    function calcDimensions(origW, origH, maxW, maxH) {
        if (origW <= maxW && origH <= maxH) return { w: origW, h: origH };
        var ratio = Math.min(maxW / origW, maxH / origH);
        return {
            w: Math.round(origW * ratio),
            h: Math.round(origH * ratio)
        };
    }

    /**
     * Load file sebagai HTMLImageElement
     */
    function loadImage(file) {
        return new Promise(function (resolve, reject) {
            var url = URL.createObjectURL(file);
            var img = new Image();
            img.onload = function () {
                URL.revokeObjectURL(url);
                resolve(img);
            };
            img.onerror = function () {
                URL.revokeObjectURL(url);
                reject(new Error('Gagal memuat gambar: ' + file.name));
            };
            img.src = url;
        });
    }

    /**
     * Canvas → Blob (Promise-based)
     */
    function canvasToBlob(canvas, mimeType, quality) {
        return new Promise(function (resolve, reject) {
            if (canvas.toBlob) {
                canvas.toBlob(function (blob) {
                    if (blob) resolve(blob);
                    else reject(new Error('toBlob gagal'));
                }, mimeType, quality);
            } else {
                // Fallback: toDataURL → Blob
                try {
                    var dataUrl = canvas.toDataURL(mimeType, quality);
                    var parts   = dataUrl.split(',');
                    var mime    = parts[0].match(/:(.*?);/)[1];
                    var bin     = atob(parts[1]);
                    var arr     = new Uint8Array(bin.length);
                    for (var i = 0; i < bin.length; i++) arr[i] = bin.charCodeAt(i);
                    resolve(new Blob([arr], { type: mime }));
                } catch (e) {
                    reject(e);
                }
            }
        });
    }

    /**
     * Kompresi iteratif: turunkan quality sampai maxSizeKB terpenuhi
     */
    async function compressToTarget(canvas, mimeType, initialQuality, maxSizeKB) {
        var quality = initialQuality;
        var blob    = await canvasToBlob(canvas, mimeType, quality);

        // Jika maxSizeKB tidak diset atau sudah di bawah target, langsung return
        if (!maxSizeKB || blob.size <= maxSizeKB * 1024) return blob;

        // Iterasi turunkan quality
        var minQ = 0.40;
        for (var i = 0; i < 6 && quality > minQ; i++) {
            quality = Math.max(minQ, quality - 0.08);
            blob = await canvasToBlob(canvas, mimeType, quality);
            if (blob.size <= maxSizeKB * 1024) break;
        }

        return blob;
    }

    /* ─────────────────────────────────────────────────────────
       CORE: compress(file, opts) → Promise<File>
    ───────────────────────────────────────────────────────── */
    async function compress(file, opts) {
        opts = mergeOpts(opts);

        // Bukan gambar → return as-is
        if (!file || !file.type.startsWith('image/')) return file;

        // GIF tidak dikompresi (animasi akan rusak)
        if (file.type === 'image/gif') return file;

        var img;
        try {
            img = await loadImage(file);
        } catch (e) {
            console.warn('[ImageCompressor] Gagal load gambar, skip:', e.message);
            return file;
        }

        var origW = img.naturalWidth;
        var origH = img.naturalHeight;
        var dim   = calcDimensions(origW, origH, opts.maxWidth, opts.maxHeight);

        // Cek apakah perlu skip (sudah kecil)
        var fileSizeKB = file.size / 1024;
        if (
            opts.skipIfSmall &&
            origW <= opts.maxWidth &&
            origH <= opts.maxHeight &&
            fileSizeKB <= opts.skipThresholdKB
        ) {
            return file;
        }

        // Tentukan output format
        var outputMime = (opts.outputFormat === 'image/webp' && supportsWebpEncode())
            ? 'image/webp'
            : 'image/jpeg';

        // Buat canvas
        var canvas   = document.createElement('canvas');
        canvas.width  = dim.w;
        canvas.height = dim.h;
        var ctx = canvas.getContext('2d');

        // Background putih untuk JPEG (menghindari transparan jadi hitam)
        if (outputMime === 'image/jpeg') {
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, dim.w, dim.h);
        }

        // Gunakan imageSmoothingQuality jika tersedia
        if (ctx.imageSmoothingEnabled !== undefined) {
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
        }

        ctx.drawImage(img, 0, 0, dim.w, dim.h);

        // Compress ke target ukuran
        var blob;
        try {
            blob = await compressToTarget(canvas, outputMime, opts.quality, opts.maxSizeKB);
        } catch (e) {
            console.warn('[ImageCompressor] Gagal compress, pakai asli:', e.message);
            return file;
        }

        // Jika hasil lebih besar dari asli, pakai file asli
        if (blob.size >= file.size) return file;

        // Buat File dari Blob
        var baseName = file.name.replace(/\.[^/.]+$/, '') + getExt(outputMime);
        return new File([blob], baseName, {
            type        : outputMime,
            lastModified: Date.now(),
        });
    }

    /* ─────────────────────────────────────────────────────────
       compressAll(files, opts) → Promise<File[]>
    ───────────────────────────────────────────────────────── */
    async function compressAll(files, opts) {
        var arr     = Array.from(files || []);
        var results = [];
        for (var i = 0; i < arr.length; i++) {
            results.push(await compress(arr[i], opts));
        }
        return results;
    }

    /* ─────────────────────────────────────────────────────────
       replaceFiles(input, filesArray)
    ───────────────────────────────────────────────────────── */
    function replaceFiles(input, files) {
        if (!input || !window.DataTransfer) return;
        var dt = new DataTransfer();
        (files || []).forEach(function (f) { if (f) dt.items.add(f); });
        input.files = dt.files;
    }

    /* ─────────────────────────────────────────────────────────
       PROGRESS UI
    ───────────────────────────────────────────────────────── */
    function createProgressUI(input) {
        var wrap = document.createElement('div');
        wrap.className = 'ic-progress';
        wrap.style.cssText = [
            'display:none',
            'margin-top:8px',
            'padding:10px 14px',
            'background:#f0f7ff',
            'border:1px solid #90caf9',
            'border-radius:10px',
            'font-family:sans-serif',
            'font-size:12px',
            'color:#1565c0',
        ].join(';');

        var label = document.createElement('div');
        label.style.marginBottom = '6px';
        label.textContent = 'Mempersiapkan…';

        var trackEl = document.createElement('div');
        trackEl.style.cssText = 'height:5px;background:#d0e8ff;border-radius:4px;overflow:hidden';

        var barEl = document.createElement('div');
        barEl.style.cssText = [
            'height:100%',
            'width:0%',
            'background:linear-gradient(90deg,#1976d2,#42a5f5)',
            'border-radius:4px',
            'transition:width 0.2s ease',
        ].join(';');

        trackEl.appendChild(barEl);
        wrap.appendChild(label);
        wrap.appendChild(trackEl);

        if (input.parentNode) {
            input.parentNode.insertBefore(wrap, input.nextSibling);
        }

        return {
            show   : function () { wrap.style.display = 'block'; },
            hide   : function () { wrap.style.display = 'none'; },
            update : function (pct, text) {
                barEl.style.width = pct + '%';
                label.textContent = text || ('Mengompres… ' + pct + '%');
            },
            done   : function (savedBytes, total) {
                barEl.style.width      = '100%';
                barEl.style.background = '#27ae60';
                wrap.style.background  = '#f0fdf4';
                wrap.style.borderColor = '#81c784';
                wrap.style.color       = '#155724';
                var saved = savedBytes > 0 ? ' (hemat ' + formatBytes(savedBytes) + ')' : '';
                label.textContent = '✓ ' + total + ' gambar siap' + saved;
                setTimeout(function () { wrap.style.display = 'none'; }, 3000);
            },
            error  : function (msg) {
                barEl.style.background = '#e74c3c';
                wrap.style.background  = '#fff5f5';
                wrap.style.borderColor = '#f5c6cb';
                wrap.style.color       = '#721c24';
                label.textContent = '⚠ ' + (msg || 'Gagal mengompres');
                setTimeout(function () { wrap.style.display = 'none'; }, 4000);
            },
        };
    }

    /* ─────────────────────────────────────────────────────────
       attachTo(input, opts, onChange)
    ───────────────────────────────────────────────────────── */
    function attachTo(input, opts, onChange) {
        if (!input) return;
        opts = mergeOpts(opts);

        var ui = opts.showProgress ? createProgressUI(input) : null;

        input.addEventListener('change', async function () {
            var rawFiles = Array.from(input.files);
            if (!rawFiles.length) return;

            if (ui) { ui.show(); ui.update(0, 'Memulai…'); }

            var totalOrig       = 0;
            var totalCompressed = 0;
            var compressed      = [];

            for (var i = 0; i < rawFiles.length; i++) {
                var f = rawFiles[i];
                totalOrig += f.size;

                var result = await compress(f, opts);
                compressed.push(result);
                totalCompressed += result.size;

                var pct  = Math.round(((i + 1) / rawFiles.length) * 100);
                var text = 'Mengompres ' + (i + 1) + ' / ' + rawFiles.length + '…';
                if (ui) ui.update(pct, text);
            }

            replaceFiles(input, compressed);

            var saved = Math.max(0, totalOrig - totalCompressed);
            if (ui) ui.done(saved, compressed.length);

            input.dispatchEvent(new CustomEvent('compressed', {
                bubbles: true,
                detail : { files: compressed, savedBytes: saved },
            }));

            if (typeof onChange === 'function') onChange(compressed);
        });
    }

    /* ─────────────────────────────────────────────────────────
       PUBLIC API
    ───────────────────────────────────────────────────────── */
    global.ImageCompressor = {
        compress     : compress,
        compressAll  : compressAll,
        attachTo     : attachTo,
        replaceFiles : replaceFiles,
        formatBytes  : formatBytes,
        supportsWebp : supportsWebpEncode,
    };

}(window));