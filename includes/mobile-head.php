<?php
/**
 * Mobile App Header Meta Tags
 * Include ini di setiap halaman dalam <head> tag
 */
?>
<!-- Mobile App Meta Tags -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=yes">
<meta name="description" content="Pilkasis - Sistem Pemilihan Ketua OSIS berbasis web dengan interface yang user-friendly">
<meta name="keywords" content="pilkasis, osis, voting, pemilihan, sistem">
<meta name="author" content="Pilkasis Team">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Pilkasis">
<meta name="mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#764ba2">
<meta name="msapplication-TileColor" content="#764ba2">
<meta name="msapplication-TileImage" content="/webprosm2/pilkasis/public/icons/icon-192.png">

<!-- PWA Manifest -->
<link rel="manifest" href="/webprosm2/pilkasis/public/manifest.json">
<link rel="icon" type="image/png" sizes="192x192" href="/webprosm2/pilkasis/public/icons/icon-192.png">
<link rel="icon" type="image/png" sizes="512x512" href="/webprosm2/pilkasis/public/icons/icon-512.png">
<link rel="apple-touch-icon" href="/webprosm2/pilkasis/public/icons/icon-192.png">

<!-- iOS Splash Screens -->
<link rel="apple-touch-startup-image" href="/webprosm2/pilkasis/public/icons/splash-640x1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="/webprosm2/pilkasis/public/icons/splash-750x1334.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="/webprosm2/pilkasis/public/icons/splash-1242x2208.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)">

<!-- Preconnect to external resources -->
<link rel="preconnect" href="https://cdn.jsdelivr.net">
<link rel="preconnect" href="https://fonts.googleapis.com">

<!-- Mobile optimizations -->
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
<meta name="color-scheme" content="light dark">

<!-- Additional iOS settings -->
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-capable" content="yes">

<!-- Disable zoom on input touch -->
<style>
  @supports (padding: max(0px)) {
    body {
      padding-left: max(0px, env(safe-area-inset-left));
      padding-right: max(0px, env(safe-area-inset-right));
      padding-top: max(0px, env(safe-area-inset-top));
      padding-bottom: max(0px, env(safe-area-inset-bottom));
    }
  }

  input,
  select,
  textarea {
    font-size: 16px;
  }

  body.is-mobile {
    -webkit-user-select: none;
    user-select: none;
  }

  body.is-mobile input,
  body.is-mobile textarea {
    -webkit-user-select: text;
    user-select: text;
  }
</style>
