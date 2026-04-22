{{--
    ============================================================
    Komponen: upload-image.blade.php
    ============================================================
    Dipakai di semua form admin yang membutuhkan upload gambar
    dengan kompresi otomatis + preview + progress bar.

    Props:
      $name        string   - nama input (misal: 'image', 'foto')
      $multiple    bool     - allow multiple (default: false)
      $required    bool     - required (default: false)
      $accept      string   - mime types (default: image/*)
      $label       string   - label teks (default: 'Upload Gambar')
      $hint        string   - teks hint tambahan (opsional)
      $currentImg  string   - path gambar saat ini (untuk edit form)
      $maxWidth    int      - maxWidth compress (default: 1280)
      $quality     float    - quality 0-1 (default: 0.82)
      $id          string   - custom ID (default: random)

    Usage di blade:
      <x-upload-image
          name="image"
          label="Foto Utama"
          :required="true"
          :current-img="$gallery->image ?? null"
      />
    ============================================================
--}}

@php
    $inputName   = $name ?? 'image';
    $inputId     = $id ?? 'upload_' . $inputName . '_' . uniqid();
    $isMultiple  = $multiple ?? false;
    $isRequired  = $required ?? false;
    $acceptTypes = $accept ?? 'image/jpeg,image/png,image/jpg,image/webp';
    $inputLabel  = $label ?? 'Upload Gambar';
    $inputHint   = $hint ?? null;
    $currentImg  = $currentImg ?? null;
    $compMaxW    = $maxWidth ?? 1280;
    $compQuality = $quality ?? 0.82;
    $areaId      = $inputId . '_area';
    $progressId  = $inputId . '_progress';
    $previewId   = $inputId . '_preview';
@endphp

<div class="upload-image-wrapper" id="{{ $inputId }}_wrapper">

    {{-- ── Label ── --}}
    <div style="font-family:'Work Sans',sans-serif;font-weight:600;color:#333;margin-bottom:0.6rem;font-size:0.9rem;">
        {{ $inputLabel }}
        @if($isRequired) <span style="color:#e74c3c;margin-left:3px;">*</span> @endif
    </div>

    {{-- ── Gambar saat ini (untuk edit form) ── --}}
    @if($currentImg)
    <div id="{{ $inputId }}_current" style="margin-bottom:0.875rem;padding:0.875rem;background:#f8f9fa;border-radius:10px;border:1px solid #e8e8e8;">
        <div style="font-size:0.78rem;color:#888;margin-bottom:0.5rem;text-transform:uppercase;letter-spacing:0.5px;font-family:'Work Sans',sans-serif;">
            Gambar saat ini
        </div>
        <img
            src="{{ asset('storage/' . $currentImg) }}"
            alt="Current image"
            style="max-width:280px;max-height:200px;object-fit:cover;border-radius:8px;display:block;box-shadow:0 2px 8px rgba(0,0,0,.1);"
        >
    </div>
    @endif

    {{-- ── Upload area ── --}}
    <div
        id="{{ $areaId }}"
        class="upload-drop-area"
        onclick="document.getElementById('{{ $inputId }}').click()"
        ondragover="event.preventDefault();this.classList.add('drag-over')"
        ondragleave="this.classList.remove('drag-over')"
        ondrop="handleDrop_{{ $inputId }}(event)"
        style="
            border:2px dashed #d0c8b8;
            border-radius:12px;
            padding:2rem;
            text-align:center;
            cursor:pointer;
            background:#fafaf8;
            transition:all 0.3s ease;
            user-select:none;
        "
    >
        <input
            type="file"
            id="{{ $inputId }}"
            name="{{ $inputName }}{{ $isMultiple ? '[]' : '' }}"
            accept="{{ $acceptTypes }}"
            @if($isMultiple) multiple @endif
            @if($isRequired && !$currentImg) required @endif
            style="display:none;"
        >

        <div style="font-size:2.5rem;color:#c8b89a;margin-bottom:0.6rem;line-height:1;">
            <i class="fas fa-cloud-upload-alt"></i>
        </div>
        <div style="font-family:'Work Sans',sans-serif;color:#666;font-size:0.9rem;margin-bottom:0.25rem;">
            <strong>Klik atau drag & drop</strong> gambar di sini
        </div>
        <div style="font-family:'Work Sans',sans-serif;color:#aaa;font-size:0.78rem;">
            JPG, PNG, WEBP · Max 30MB per file · Dikompres otomatis ke WebP
        </div>
        @if($inputHint)
        <div style="font-family:'Work Sans',sans-serif;color:#c8b89a;font-size:0.75rem;margin-top:0.35rem;font-style:italic;">
            {{ $inputHint }}
        </div>
        @endif
    </div>

    {{-- ── Progress bar ── --}}
    <div id="{{ $progressId }}" style="display:none;margin-top:0.75rem;">
        <div style="
            padding:10px 14px;
            background:#f0f7ff;
            border:1px solid #90caf9;
            border-radius:10px;
            font-family:'Work Sans',sans-serif;
            font-size:12px;
            color:#1565c0;
        " id="{{ $progressId }}_box">
            <div id="{{ $progressId }}_label" style="margin-bottom:6px;">Mempersiapkan…</div>
            <div style="height:5px;background:#d0e8ff;border-radius:4px;overflow:hidden;">
                <div
                    id="{{ $progressId }}_bar"
                    style="height:100%;width:0%;background:linear-gradient(90deg,#1976d2,#42a5f5);border-radius:4px;transition:width 0.25s ease;"
                ></div>
            </div>
        </div>
    </div>

    {{-- ── Preview setelah compress ── --}}
    <div id="{{ $previewId }}" style="display:none;margin-top:0.875rem;"></div>

</div>

{{-- ── Styles inline (sekali aja) ── --}}
<style>
.upload-drop-area:hover,
.upload-drop-area.drag-over {
    border-color: #8B7355 !important;
    background: #faf5ed !important;
}
</style>

{{-- ── Script untuk input ini ── --}}
<script>
(function () {
    var inputEl    = document.getElementById('{{ $inputId }}');
    var areaEl     = document.getElementById('{{ $areaId }}');
    var progEl     = document.getElementById('{{ $progressId }}');
    var progBox    = document.getElementById('{{ $progressId }}_box');
    var progBar    = document.getElementById('{{ $progressId }}_bar');
    var progLabel  = document.getElementById('{{ $progressId }}_label');
    var previewEl  = document.getElementById('{{ $previewId }}');

    var opts = {
        maxWidth      : {{ $compMaxW }},
        maxHeight     : {{ $compMaxW }},
        quality       : {{ $compQuality }},
        outputFormat  : 'image/webp',
        skipIfSmall   : true,
        skipThresholdKB: 200,
    };

    function showProgress(pct, text, state) {
        progEl.style.display = 'block';
        progBar.style.width  = pct + '%';
        progLabel.textContent = text;

        if (state === 'done') {
            progBar.style.background   = '#27ae60';
            progBox.style.background   = '#f0fdf4';
            progBox.style.borderColor  = '#81c784';
            progBox.style.color        = '#155724';
            setTimeout(function () { progEl.style.display = 'none'; resetProgress(); }, 3000);
        } else if (state === 'error') {
            progBar.style.background   = '#e74c3c';
            progBox.style.background   = '#fff5f5';
            progBox.style.borderColor  = '#f5c6cb';
            progBox.style.color        = '#721c24';
            setTimeout(function () { progEl.style.display = 'none'; resetProgress(); }, 4000);
        } else {
            progBar.style.background  = 'linear-gradient(90deg,#1976d2,#42a5f5)';
            progBox.style.background  = '#f0f7ff';
            progBox.style.borderColor = '#90caf9';
            progBox.style.color       = '#1565c0';
        }
    }

    function resetProgress() {
        progBar.style.width      = '0%';
        progBar.style.background = 'linear-gradient(90deg,#1976d2,#42a5f5)';
        progBox.style.background = '#f0f7ff';
        progBox.style.borderColor = '#90caf9';
        progBox.style.color       = '#1565c0';
    }

    function renderPreviews(files) {
        previewEl.innerHTML = '';
        if (!files.length) { previewEl.style.display = 'none'; return; }

        previewEl.style.display = 'block';

        var isMultiple = {{ $isMultiple ? 'true' : 'false' }};

        var grid = document.createElement('div');
        grid.style.cssText = 'display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:8px;';

        files.forEach(function (file, idx) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var wrap = document.createElement('div');
                wrap.style.cssText = 'position:relative;border-radius:8px;overflow:hidden;aspect-ratio:1;background:#f0f0f0;';

                var img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';

                var badge = document.createElement('div');
                badge.style.cssText = 'position:absolute;bottom:4px;left:4px;right:4px;background:rgba(0,0,0,.6);color:white;font-size:10px;padding:2px 5px;border-radius:4px;font-family:sans-serif;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';
                badge.textContent = ImageCompressor.formatBytes(file.size);

                wrap.appendChild(img);
                wrap.appendChild(badge);
                grid.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });

        previewEl.appendChild(grid);
    }

    // Sembunyikan upload area setelah gambar dipilih (single upload)
    function toggleArea(hasFiles) {
        var isMultiple = {{ $isMultiple ? 'true' : 'false' }};
        if (!isMultiple && hasFiles) {
            areaEl.style.display = 'none';
        } else {
            areaEl.style.display = 'block';
        }
    }

    inputEl.addEventListener('change', async function () {
        var rawFiles = Array.from(this.files);
        if (!rawFiles.length) return;

        showProgress(5, 'Mempersiapkan kompres…', 'processing');

        var totalOrig       = 0;
        var totalCompressed = 0;
        var compressed      = [];

        for (var i = 0; i < rawFiles.length; i++) {
            var f = rawFiles[i];
            totalOrig += f.size;

            try {
                var result = await ImageCompressor.compress(f, opts);
                compressed.push(result);
                totalCompressed += result.size;
            } catch (err) {
                console.warn('[UploadImage] Gagal compress file:', f.name, err);
                compressed.push(f);
                totalCompressed += f.size;
            }

            var pct  = Math.round(((i + 1) / rawFiles.length) * 95) + 5;
            showProgress(pct, 'Mengompres ' + (i + 1) + ' / ' + rawFiles.length + '…', 'processing');
        }

        ImageCompressor.replaceFiles(inputEl, compressed);

        var saved   = Math.max(0, totalOrig - totalCompressed);
        var doneMsg = '✓ ' + compressed.length + ' file siap';
        if (saved > 0) doneMsg += ' · hemat ' + ImageCompressor.formatBytes(saved);
        if (totalCompressed > 0) doneMsg += ' · total ' + ImageCompressor.formatBytes(totalCompressed);

        showProgress(100, doneMsg, 'done');
        renderPreviews(compressed);
        toggleArea(compressed.length > 0);
    });

    // Drag & drop
    window['handleDrop_{{ $inputId }}'] = function (e) {
        e.preventDefault();
        areaEl.classList.remove('drag-over');
        var dt = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(function (f) { dt.items.add(f); });
        inputEl.files = dt.files;
        inputEl.dispatchEvent(new Event('change'));
    };
})();
</script>