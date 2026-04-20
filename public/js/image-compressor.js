/**
 * ImageCompressor — client-side image compression before upload.
 *
 * Usage (attach to a file input):
 *   ImageCompressor.attachTo(document.getElementById('image'), {
 *       maxWidth:  1920,
 *       maxHeight: 1920,
 *       quality:   0.82,
 *   });
 *
 * Usage (compress a File manually):
 *   const compressed = await ImageCompressor.compress(file, { maxWidth: 1280, quality: 0.80 });
 *
 * Usage (compress many files):
 *   const files = await ImageCompressor.compressAll(fileList, { maxWidth: 1280 });
 */

(function (global) {
    'use strict';

    /* ------------------------------------------------------------------ */
    /*  Default options                                                     */
    /* ------------------------------------------------------------------ */
    const DEFAULTS = {
        maxWidth:     1920,
        maxHeight:    1920,
        quality:      0.82,       // JPEG/WebP quality 0–1
        outputType:   'image/webp', // preferred output format
        fallbackType: 'image/jpeg', // fallback if WebP not supported
        skipIfSmaller: true,        // skip compression if result is larger
        skipBelowKB:   50,          // skip if original < 50 KB (already tiny)
        showProgress: false,        // log progress to console
    };

    /* ------------------------------------------------------------------ */
    /*  Feature detection                                                   */
    /* ------------------------------------------------------------------ */
    function supportsWebP () {
        const c = document.createElement('canvas');
        c.width = c.height = 1;
        return c.toDataURL('image/webp').startsWith('data:image/webp');
    }

    const WEBP_OK = supportsWebP();

    /* ------------------------------------------------------------------ */
    /*  Core compression                                                    */
    /* ------------------------------------------------------------------ */

    /**
     * Compress a single File.
     * Returns a Promise<File>.
     */
    function compress (file, opts) {
        opts = Object.assign({}, DEFAULTS, opts || {});

        return new Promise(function (resolve) {

            /* Skip non-image types */
            if (!file.type.startsWith('image/')) {
                resolve(file);
                return;
            }

            /* Skip tiny files — already small enough */
            if (file.size < opts.skipBelowKB * 1024) {
                resolve(file);
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = new Image();
                img.onload = function () {
                    try {
                        const result = _draw(img, file, opts);
                        resolve(result);
                    } catch (err) {
                        console.warn('[ImageCompressor] draw failed, returning original:', err);
                        resolve(file);
                    }
                };
                img.onerror = function () { resolve(file); };
                img.src = e.target.result;
            };
            reader.onerror = function () { resolve(file); };
            reader.readAsDataURL(file);
        });
    }

    /** Internal: draw image onto canvas and return compressed File. */
    function _draw (img, originalFile, opts) {
        let w = img.naturalWidth  || img.width;
        let h = img.naturalHeight || img.height;

        /* Resize if too large */
        const maxW = opts.maxWidth  || DEFAULTS.maxWidth;
        const maxH = opts.maxHeight || DEFAULTS.maxHeight;

        if (w > maxW) {
            h = Math.round(h * maxW / w);
            w = maxW;
        }
        if (h > maxH) {
            w = Math.round(w * maxH / h);
            h = maxH;
        }

        const canvas = document.createElement('canvas');
        canvas.width  = w;
        canvas.height = h;

        const ctx = canvas.getContext('2d');

        /* White background for JPEG (avoid black on transparency) */
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, w, h);
        ctx.drawImage(img, 0, 0, w, h);

        const mime    = (WEBP_OK && opts.outputType === 'image/webp')
                          ? 'image/webp'
                          : opts.fallbackType;
        const quality = opts.quality;
        const dataURL = canvas.toDataURL(mime, quality);

        /* Convert dataURL → Blob → File */
        const byteString = atob(dataURL.split(',')[1]);
        const ab  = new ArrayBuffer(byteString.length);
        const ia  = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) ia[i] = byteString.charCodeAt(i);

        const blob = new Blob([ab], { type: mime });

        /* Skip if compressed result is actually larger */
        if (opts.skipIfSmaller && blob.size >= originalFile.size) {
            return originalFile;
        }

        /* Build a new File with clean extension */
        const ext      = mime === 'image/webp' ? 'webp' : 'jpg';
        const baseName = originalFile.name.replace(/\.[^.]+$/, '');
        const newName  = baseName + '.' + ext;

        if (opts.showProgress) {
            const saved = (((originalFile.size - blob.size) / originalFile.size) * 100).toFixed(1);
            console.log(
                '[ImageCompressor]',
                originalFile.name,
                '→', newName,
                '|', _humanSize(originalFile.size),
                '→', _humanSize(blob.size),
                '(' + saved + '% saved)',
            );
        }

        return new File([blob], newName, {
            type:         mime,
            lastModified: Date.now(),
        });
    }

    /** Compress an array / FileList of files, returns Promise<File[]>. */
    function compressAll (files, opts, onProgress) {
        const arr = Array.from(files);
        let done = 0;

        return Promise.all(arr.map(function (file) {
            return compress(file, opts).then(function (result) {
                done++;
                if (typeof onProgress === 'function') {
                    onProgress(done, arr.length, result);
                }
                return result;
            });
        }));
    }

    /* ------------------------------------------------------------------ */
    /*  attachTo — convenience wrapper for a <input type="file">           */
    /* ------------------------------------------------------------------ */

    /**
     * Attach compression logic to a file input.
     *
     * After the user picks files, they are silently compressed and
     * the input's FileList is replaced with the compressed versions.
     *
     * @param {HTMLInputElement} inputEl
     * @param {object}           opts
     */
    function attachTo (inputEl, opts) {
        if (!inputEl || inputEl.tagName !== 'INPUT' || inputEl.type !== 'file') {
            console.warn('[ImageCompressor] attachTo: expected a file input element');
            return;
        }

        /* Avoid double-binding */
        if (inputEl._icAttached) return;
        inputEl._icAttached = true;

        opts = Object.assign({}, DEFAULTS, opts || {});

        inputEl.addEventListener('change', function () {
            const files = Array.from(inputEl.files || []);
            if (!files.length) return;

            /* Show a subtle loading indicator if the form has a submit button */
            const form  = inputEl.closest('form');
            const btn   = form ? form.querySelector('[type=submit]') : null;
            let prevTxt = '';
            if (btn && opts.showProgress) {
                prevTxt     = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '⏳ Compressing…';
            }

            let done = 0;
            compressAll(files, opts, function (d, total) {
                done = d;
                if (btn && opts.showProgress) {
                    btn.innerHTML = '⏳ Compressing ' + d + '/' + total + '…';
                }
            }).then(function (compressed) {
                /* Replace the FileList on the input */
                _replaceFiles(inputEl, compressed);

                if (btn && opts.showProgress) {
                    btn.disabled  = false;
                    btn.innerHTML = prevTxt;
                }

                /* Dispatch a synthetic 'compressed' event so external code can react */
                inputEl.dispatchEvent(new CustomEvent('compressed', {
                    detail: { files: compressed },
                    bubbles: true,
                }));
            });
        });
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                             */
    /* ------------------------------------------------------------------ */

    function _replaceFiles (inputEl, files) {
        try {
            const dt = new DataTransfer();
            files.forEach(function (f) { dt.items.add(f); });
            inputEl.files = dt.files;
        } catch (e) {
            /* DataTransfer not supported in very old browsers — silently skip */
            console.warn('[ImageCompressor] Could not replace FileList:', e);
        }
    }

    function _humanSize (bytes) {
        if (bytes < 1024)        return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    /* ------------------------------------------------------------------ */
    /*  Public API                                                          */
    /* ------------------------------------------------------------------ */
    global.ImageCompressor = {
        compress:    compress,
        compressAll: compressAll,
        attachTo:    attachTo,
    };

}(window));