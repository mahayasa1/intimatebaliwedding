{{--
    ============================================================
    Komponen: upload-image.blade.php  (v3 — Server-Side Compress)
    ============================================================
    Upload gambar dengan preview instan.
    Kompresi sepenuhnya di server (ImageHelper::storeAndCompress).
    TIDAK ada client-side compression.

    Props:
      $name        string   - nama input  (misal: 'image', 'foto')
      $multiple    bool     - allow multiple files (default: false)
      $required    bool     - required     (default: false)
      $accept      string   - mime types   (default: image/*)
      $label       string   - label teks   (default: 'Upload Gambar')
      $hint        string   - teks hint tambahan (opsional)
      $currentImg  string   - path gambar saat ini (edit form)
      $id          string   - custom ID   (default: random)

    Usage:
      <x-upload-image
          name="image"
          label="Foto Utama"
          :required="true"
          :current-img="$gallery->image ?? null"
      />
    ============================================================
--}}

@php
    $inputName   = $name      ?? 'image';
    $inputId     = $id        ?? 'upload_' . $inputName . '_' . uniqid();
    $isMultiple  = $multiple  ?? false;
    $isRequired  = $required  ?? false;
    $acceptTypes = $accept    ?? 'image/jpeg,image/png,image/jpg,image/webp';
    $inputLabel  = $label     ?? 'Upload Gambar';
    $inputHint   = $hint      ?? null;
    $currentImg  = $currentImg ?? null;
    $areaId      = $inputId . '_area';
    $previewId   = $inputId . '_preview';
@endphp

<div class="upload-image-wrapper" id="{{ $inputId }}_wrapper">

    {{-- ── Label ── --}}
    <div style="font-family:'Work Sans',sans-serif;font-weight:600;color:#333;margin-bottom:0.6rem;font-size:0.9rem;">
        {{ $inputLabel }}
        @if($isRequired) <span style="color:#e74c3c;margin-left:3px;">*</span> @endif
    </div>

    {{-- ── Gambar saat ini (edit form) ── --}}
    @if($currentImg)
    <div id="{{ $inputId }}_current"
         style="margin-bottom:0.875rem;padding:0.875rem;background:#f8f9fa;border-radius:10px;border:1px solid #e8e8e8;">
        <div style="font-size:0.78rem;color:#888;margin-bottom:0.5rem;text-transform:uppercase;letter-spacing:0.5px;font-family:'Work Sans',sans-serif;">
            Gambar saat ini
        </div>
        <img src="{{ asset('storage/' . $currentImg) }}"
             alt="Current image"
             style="max-width:280px;max-height:200px;object-fit:cover;border-radius:8px;display:block;box-shadow:0 2px 8px rgba(0,0,0,.1);">
        <div style="font-size:0.75rem;color:#aaa;margin-top:0.5rem;font-family:'Work Sans',sans-serif;">
            Pilih file baru untuk mengganti gambar ini
        </div>
    </div>
    @endif

    {{-- ── Upload area ── --}}
    <div id="{{ $areaId }}"
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
         ">

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
            <strong>Klik atau drag &amp; drop</strong> gambar di sini
        </div>
        <div style="font-family:'Work Sans',sans-serif;color:#aaa;font-size:0.78rem;">
            JPG, PNG, WEBP · Max 30MB per file
        </div>
        <div style="font-family:'Work Sans',sans-serif;color:#c8b89a;font-size:0.72rem;margin-top:0.4rem;">
            ✨ Gambar akan dikompres &amp; dikonversi ke WebP otomatis oleh server
        </div>
        @if($inputHint)
        <div style="font-family:'Work Sans',sans-serif;color:#c8b89a;font-size:0.75rem;margin-top:0.35rem;font-style:italic;">
            {{ $inputHint }}
        </div>
        @endif
    </div>

    {{-- ── Preview grid (muncul setelah file dipilih) ── --}}
    <div id="{{ $previewId }}"
         style="display:none;margin-top:0.875rem;">
    </div>

</div>

{{-- ── Shared CSS (hanya sekali per page, idempotent) ── --}}
@once
<style>
    .upload-drop-area:hover,
    .upload-drop-area.drag-over {
        border-color: #8B7355 !important;
        background: #faf5ed !important;
    }
</style>
@endonce

{{-- ── Script: preview saja, tanpa compression ── --}}
<script>
(function () {
    var inputEl  = document.getElementById('{{ $inputId }}');
    var areaEl   = document.getElementById('{{ $areaId }}');
    var previewEl = document.getElementById('{{ $previewId }}');

    if (!inputEl) return;

    /* ── Render previews dari FileList ── */
    function renderPreviews(files) {
        previewEl.innerHTML = '';

        if (!files.length) {
            previewEl.style.display = 'none';
            areaEl.style.display = '';
            return;
        }

        previewEl.style.display = 'block';

        var grid = document.createElement('div');
        grid.style.cssText = 'display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:8px;';

        Array.from(files).forEach(function (file) {
            if (!file.type.startsWith('image/')) return;

            var reader = new FileReader();
            reader.onload = function (e) {
                var wrap = document.createElement('div');
                wrap.style.cssText = [
                    'position:relative',
                    'border-radius:8px',
                    'overflow:hidden',
                    'aspect-ratio:1',
                    'background:#f0f0f0',
                    'border:2px solid #e8e8e8',
                ].join(';');

                var img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';

                /* filename badge */
                var badge = document.createElement('div');
                badge.style.cssText = [
                    'position:absolute',
                    'bottom:0',
                    'left:0',
                    'right:0',
                    'background:rgba(0,0,0,.55)',
                    'color:white',
                    'font-size:9px',
                    'padding:3px 5px',
                    'font-family:sans-serif',
                    'white-space:nowrap',
                    'overflow:hidden',
                    'text-overflow:ellipsis',
                ].join(';');
                badge.textContent = file.name;

                wrap.appendChild(img);
                wrap.appendChild(badge);
                grid.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });

        previewEl.appendChild(grid);

        /* Sembunyikan upload area untuk single upload */
        if (!{{ $isMultiple ? 'true' : 'false' }}) {
            areaEl.style.display = 'none';
        }
    }

    /* ── change event ── */
    inputEl.addEventListener('change', function () {
        renderPreviews(Array.from(this.files));
    });

    /* ── Drag & drop ── */
    window['handleDrop_{{ $inputId }}'] = function (e) {
        e.preventDefault();
        areaEl.classList.remove('drag-over');

        var dt = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(function (f) {
            dt.items.add(f);
        });
        inputEl.files = dt.files;
        inputEl.dispatchEvent(new Event('change'));
    };
})();
</script>