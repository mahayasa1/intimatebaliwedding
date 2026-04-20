/**
 * image-compressor.js
 * Kompres gambar di browser sebelum dikirim ke server.
 * Gunakan: <script src="/js/image-compressor.js"></script>
 *
 * API:
 *   ImageCompressor.compress(file, options?)  → Promise<File>
 *   ImageCompressor.compressAll(files, options?) → Promise<File[]>
 *   ImageCompressor.attachTo(inputEl, options?) → void
 */
(function (global) {
    'use strict';

    const DEFAULTS = {
        maxWidth   : 1280,   // px — lebar maksimal
        maxHeight  : 1280,   // px — tinggi maksimal
        quality    : 0.72,   // WebP quality 0–1
        outputType : 'image/webp',
        onProgress : null,   // callback(current, total)
    };

    /* ------------------------------------------------------------------ */
    /* Core compress                                                        */
    /* ------------------------------------------------------------------ */
    function compress(file, opts) {
        opts = Object.assign({}, DEFAULTS, opts || {});

        return new Promise(function (resolve, reject) {
            // Bukan gambar → langsung lanjut
            if (!file.type.startsWith('image/')) {
                resolve(file);
                return;
            }

            var img = new Image();
            var url = URL.createObjectURL(file);

            img.onload = function () {
                URL.revokeObjectURL(url);

                var origW = img.naturalWidth;
                var origH = img.naturalHeight;

                // Hitung dimensi baru dengan menjaga aspect ratio
                var ratio = 1;
                if (origW > opts.maxWidth)  ratio = Math.min(ratio, opts.maxWidth  / origW);
                if (origH > opts.maxHeight) ratio = Math.min(ratio, opts.maxHeight / origH);

                var newW = Math.round(origW * ratio);
                var newH = Math.round(origH * ratio);

                var canvas   = document.createElement('canvas');
                canvas.width  = newW;
                canvas.height = newH;
                var ctx = canvas.getContext('2d');

                // Background putih (untuk JPEG/WebP agar tidak transparan hitam)
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, newW, newH);
                ctx.drawImage(img, 0, 0, newW, newH);

                canvas.toBlob(
                    function (blob) {
                        if (!blob) {
                            // Fallback ke file asli jika gagal
                            resolve(file);
                            return;
                        }

                        // Pakai WebP jika hasilnya lebih kecil, else pakai JPEG
                        var ext  = opts.outputType === 'image/webp' ? '.webp' : '.jpg';
                        var name = file.name.replace(/\.[^.]+$/, '') + ext;
                        var compressed = new File([blob], name, {
                            type         : opts.outputType,
                            lastModified : Date.now(),
                        });

                        // Jika hasil lebih besar dari asli, kembalikan asli
                        resolve(compressed.size < file.size ? compressed : file);
                    },
                    opts.outputType,
                    opts.quality
                );
            };

            img.onerror = function () {
                URL.revokeObjectURL(url);
                resolve(file); // fallback ke file asli
            };

            img.src = url;
        });
    }

    /* ------------------------------------------------------------------ */
    /* Compress array of files                                              */
    /* ------------------------------------------------------------------ */
    function compressAll(files, opts) {
        opts = Object.assign({}, DEFAULTS, opts || {});
        var arr    = Array.from(files);
        var total  = arr.length;
        var results = [];

        return arr.reduce(function (chain, file, i) {
            return chain.then(function () {
                return compress(file, opts).then(function (f) {
                    results.push(f);
                    if (typeof opts.onProgress === 'function') {
                        opts.onProgress(i + 1, total);
                    }
                });
            });
        }, Promise.resolve()).then(function () {
            return results;
        });
    }

    /* ------------------------------------------------------------------ */
    /* Attach to <input type="file">                                        */
    /* ------------------------------------------------------------------ */
    function attachTo(inputEl, opts) {
        if (!inputEl) return;

        opts = Object.assign({
            showProgress : true,
            progressClass: 'img-compress-progress',
        }, DEFAULTS, opts || {});

        inputEl.addEventListener('change', function (e) {
            var files = Array.from(e.target.files);
            if (!files.length) return;

            // Tampilkan progress jika diminta
            var progressEl = null;
            if (opts.showProgress) {
                progressEl = inputEl.parentNode
                    ? inputEl.parentNode.querySelector('.' + opts.progressClass)
                    : null;
            }

            var progressOpts = Object.assign({}, opts, {
                onProgress: function (cur, total) {
                    if (progressEl) {
                        progressEl.textContent = 'Memproses ' + cur + ' / ' + total + ' gambar…';
                        progressEl.style.display = 'block';
                    }
                },
            });

            compressAll(files, progressOpts).then(function (compressed) {
                // Inject kembali ke input menggunakan DataTransfer
                try {
                    var dt = new DataTransfer();
                    compressed.forEach(function (f) { dt.items.add(f); });
                    inputEl.files = dt.files;
                } catch (err) {
                    console.warn('[ImageCompressor] DataTransfer not supported, skipping inject.', err);
                }

                if (progressEl) {
                    progressEl.textContent = 'Selesai — ' + compressed.length + ' gambar diproses';
                    setTimeout(function () { progressEl.style.display = 'none'; }, 2500);
                }

                // Trigger change event supaya preview/listener lain ikut update
                inputEl.dispatchEvent(new Event('compressed', { bubbles: true }));
            });
        });
    }

    /* ------------------------------------------------------------------ */
    /* Expose                                                               */
    /* ------------------------------------------------------------------ */
    global.ImageCompressor = {
        compress    : compress,
        compressAll : compressAll,
        attachTo    : attachTo,
    };

}(typeof window !== 'undefined' ? window : this));