/**
 * image-compressor.js
 * Kompres gambar di browser SEBELUM upload ke server.
 * Mengurangi ukuran file secara drastis sebelum dikirim.
 */
(function (global) {
    'use strict';

    const DEFAULTS = {
        maxWidth   : 1280,
        maxHeight  : 1280,
        quality    : 0.78,
        outputType : 'image/webp',
        onProgress : null,
    };

    function compress(file, opts) {
        opts = Object.assign({}, DEFAULTS, opts || {});

        return new Promise(function (resolve) {
            if (!file.type.startsWith('image/')) {
                resolve(file);
                return;
            }

            // File sudah sangat kecil (< 100 KB), skip
            if (file.size < 100 * 1024) {
                resolve(file);
                return;
            }

            var img = new Image();
            var url = URL.createObjectURL(file);

            img.onload = function () {
                URL.revokeObjectURL(url);

                var origW = img.naturalWidth;
                var origH = img.naturalHeight;

                var ratio = 1;
                if (origW > opts.maxWidth)  ratio = Math.min(ratio, opts.maxWidth  / origW);
                if (origH > opts.maxHeight) ratio = Math.min(ratio, opts.maxHeight / origH);

                var newW = Math.round(origW * ratio);
                var newH = Math.round(origH * ratio);

                var canvas   = document.createElement('canvas');
                canvas.width  = newW;
                canvas.height = newH;

                var ctx = canvas.getContext('2d');
                ctx.imageSmoothingEnabled  = true;
                ctx.imageSmoothingQuality  = 'high';
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, newW, newH);
                ctx.drawImage(img, 0, 0, newW, newH);

                canvas.toBlob(
                    function (blob) {
                        if (!blob) { resolve(file); return; }

                        // Pakai hasil kompres hanya jika lebih kecil
                        if (blob.size >= file.size) { resolve(file); return; }

                        var ext  = opts.outputType === 'image/webp' ? '.webp' : '.jpg';
                        var name = file.name.replace(/\.[^.]+$/, '') + ext;
                        resolve(new File([blob], name, {
                            type         : opts.outputType,
                            lastModified : Date.now(),
                        }));
                    },
                    opts.outputType,
                    opts.quality
                );
            };

            img.onerror = function () {
                URL.revokeObjectURL(url);
                resolve(file);
            };

            img.src = url;
        });
    }

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
        }, Promise.resolve()).then(function () { return results; });
    }

    function attachTo(inputEl, opts) {
        if (!inputEl) return;

        opts = Object.assign({
            showProgress  : true,
            progressClass : 'img-compress-progress',
        }, DEFAULTS, opts || {});

        inputEl.addEventListener('change', function (e) {
            var files = Array.from(e.target.files);
            if (!files.length) return;

            var progressEl = opts.showProgress && inputEl.parentNode
                ? inputEl.parentNode.querySelector('.' + opts.progressClass)
                : null;

            var progressOpts = Object.assign({}, opts, {
                onProgress: function (cur, total) {
                    if (progressEl) {
                        progressEl.textContent = 'Memproses ' + cur + ' / ' + total + '…';
                        progressEl.style.display = 'block';
                    }
                },
            });

            compressAll(files, progressOpts).then(function (compressed) {
                try {
                    var dt = new DataTransfer();
                    compressed.forEach(function (f) { dt.items.add(f); });
                    inputEl.files = dt.files;
                } catch (err) {
                    console.warn('[ImageCompressor] DataTransfer tidak didukung:', err);
                }

                if (progressEl) {
                    progressEl.textContent = 'Selesai — ' + compressed.length + ' gambar diproses';
                    setTimeout(function () { progressEl.style.display = 'none'; }, 2500);
                }

                inputEl.dispatchEvent(new Event('compressed', { bubbles: true }));
            });
        });
    }

    global.ImageCompressor = { compress: compress, compressAll: compressAll, attachTo: attachTo };

}(typeof window !== 'undefined' ? window : this));