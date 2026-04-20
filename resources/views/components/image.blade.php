{{--
    Komponen gambar teroptimasi.
    Usage:
        <x-image :src="$gallery->image" alt="Wedding photo" />
        <x-image :src="$pkg->image" :thumb="true" :eager="true" class="hero-img" />
    Props:
        src    — path relatif disk 'public' ATAU URL absolut
        alt    — teks alt (wajib untuk aksesibilitas)
        thumb  — gunakan thumbnail (default true)
        eager  — eager load untuk above-fold (default false)
        class  — CSS class tambahan
        width  — attribute width
        height — attribute height
        style  — inline style
--}}

@php
    use App\Helpers\ImageHelper;

    $src    = $src    ?? '';
    $alt    = $alt    ?? '';
    $thumb  = $thumb  ?? true;
    $eager  = $eager  ?? false;

    if ($src === '') {
        $url     = asset('assets/placeholder.jpg');
        $urlFull = $url;
    } elseif (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
        $url     = $src;
        $urlFull = $src;
    } else {
        $thumbPath = $thumb ? ImageHelper::thumb($src) : $src;
        $url       = asset('storage/' . $thumbPath);
        $urlFull   = asset('storage/' . $src);
    }

    $loading  = $eager ? 'eager'  : 'lazy';
    $decoding = $eager ? 'sync'   : 'async';
    $fetchpri = $eager ? 'high'   : 'low';
@endphp

<img
    src="{{ $url }}"
    alt="{{ $alt }}"
    loading="{{ $loading }}"
    decoding="{{ $decoding }}"
    fetchpriority="{{ $fetchpri }}"
    @if(!empty($width))  width="{{ $width }}"   @endif
    @if(!empty($height)) height="{{ $height }}"  @endif
    @if(!empty($style))  style="{{ $style }}"    @endif
    {{ $attributes->merge(['class' => $class ?? '']) }}
    onerror="if(this.src!=='{{ $urlFull }}'){this.src='{{ $urlFull }}';}else{this.src='{{ asset('assets/placeholder.jpg') }}';}this.onerror=null;"
>