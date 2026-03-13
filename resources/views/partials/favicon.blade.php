@php
    $configuredFaviconPath = config('branding.favicon_path', 'favicon.png');
    $isExternal = str_starts_with($configuredFaviconPath, 'http://') || str_starts_with($configuredFaviconPath, 'https://');
    $normalizedFaviconPath = ltrim($configuredFaviconPath, '/');
    $localConfiguredExists = !$isExternal && is_file(public_path($normalizedFaviconPath));
    $localFallbackPath = 'favicon.png';
    $localFallbackExists = is_file(public_path($localFallbackPath));

    if ($isExternal) {
        $faviconHref = $configuredFaviconPath;
    } else {
        $effectivePath = $localConfiguredExists
            ? $normalizedFaviconPath
            : ($localFallbackExists ? $localFallbackPath : $normalizedFaviconPath);

        $version = is_file(public_path($effectivePath)) ? (string) filemtime(public_path($effectivePath)) : (string) time();
        $faviconHref = asset($effectivePath).'?v='.$version;
    }
@endphp
<link rel="icon" type="image/png" href="{{ $faviconHref }}">
<link rel="shortcut icon" href="{{ $faviconHref }}">
<link rel="apple-touch-icon" href="{{ $faviconHref }}">
