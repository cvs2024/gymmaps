@php
    $faviconPath = config('branding.favicon_path', 'logo/gymmaps_logo_treatwell_style.png');
    $isExternal = str_starts_with($faviconPath, 'http://') || str_starts_with($faviconPath, 'https://');
    $faviconHref = $isExternal ? $faviconPath : asset(ltrim($faviconPath, '/'));
@endphp
<link rel="icon" type="image/png" href="{{ $faviconHref }}">
<link rel="shortcut icon" href="{{ $faviconHref }}">
<link rel="apple-touch-icon" href="{{ $faviconHref }}">
