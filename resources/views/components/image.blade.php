{{--
    Komponen gambar teroptimasi dengan lazy loading + WebP fallback.

    Usage:
        <x-image :src="$gallery->image" alt="Wedding photo" class="my-class" />
        <x-image :src="$package->image" :thumb="true" width="640" height="480" eager />

    Props:
        src    (string)  — path relatif di disk 'public'  ATAU URL absolut
        alt    (string)  — teks alt
        class  (string)  — CSS class tambahan
        thumb  (bool)    — pakai thumbnail (default true)
        eager  (bool)    — eager load (hero images); default false = lazy
        width  (int)     — attribute width
        height (int)     — attribute height
        style  (string)  — inline style
--}}

@php
    use App\Helpers\ImageHelper;

    $src   = $src   ?? '';
    $alt   = $alt   ?? '';
    $thumb = $thumb ?? true;
    $eager = $eager ?? false;

    // Tentukan URL
    if ($src === '') {
        $url = asset('assets/placeholder.jpg');
    } elseif (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
        // URL absolut (Google Photos, CDN, dll)
        $url = $src;
    } else {
        // Path relatif di disk 'public'
        $displayPath = $thumb ? ImageHelper::thumb($src) : $src;
        $url = asset('storage/' . $displayPath);
    }

    $loading  = $eager ? 'eager'  : 'lazy';
    $decoding = $eager ? 'sync'   : 'async';
    $priority = $eager ? 'high'   : 'low';
@endphp

<img
    src="{{ $url }}"
    alt="{{ $alt }}"
    loading="{{ $loading }}"
    decoding="{{ $decoding }}"
    fetchpriority="{{ $priority }}"
    @if(!empty($width))  width="{{ $width }}"   @endif
    @if(!empty($height)) height="{{ $height }}"  @endif
    @if(!empty($style))  style="{{ $style }}"    @endif
    {{ $attributes->merge(['class' => $class ?? '']) }}
    onerror="this.onerror=null; this.src='{{ asset('assets/placeholder.jpg') }}';"
>