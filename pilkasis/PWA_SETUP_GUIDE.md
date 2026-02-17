# Panduan PWA - Aplikasi Mobile Pilkasis

## Apa itu PWA?
PWA (Progressive Web App) memungkinkan aplikasi web Anda berfungsi seperti aplikasi native di HP:
- ✅ Bisa diakses offline (dengan cache)
- ✅ "Add to Home Screen" tanpa install dari App Store
- ✅ Notifikasi push
- ✅ Fast loading
- ✅ Storage lokal

## File-File yang Sudah Dibuat

### 1. **manifest.json** 
   - Lokasi: `public/manifest.json`
   - Fungsi: Metadata aplikasi untuk browser
   - Contains: Nama, ikon, shortcuts, warna tema

### 2. **service-worker.js**
   - Lokasi: `public/js/service-worker.js`  
   - Fungsi: Caching & offline support
   - Features: Network first untuk HTML, cache first untuk assets

### 3. **app.js**
   - Lokasi: `public/js/app.js`
   - Fungsi: Service worker registration, install prompt, UI enhancements
   - Includes: Notification system, online/offline detection

### 4. **mobile-head.php**
   - Lokasi: `includes/mobile-head.php`
   - Fungsi: Meta tags untuk mobile optimization
   - Include di: Setiap halaman HTML dalam `<head>` tag

### 5. **mobile.css**
   - Lokasi: `public/css/mobile.css`
   - Fungsi: Responsive design & mobile UX improvements
   - Optimizes: Touch targets, safe areas, dark mode

---

## Integrasi ke Halaman yang Ada

### Step 1: Update `<head>` Tag
Tambahkan di setiap halaman (login.php, index.php, voting pages, dll):

```php
<?php
// Di dalam <head> tag, setelah <meta charset>
include '../includes/mobile-head.php';
?>

<!-- Existing head content... -->
<!-- Links ke Bootstrap CSS dll... -->

<!-- Tambahkan CSS Mobile (optional) -->
<link rel="stylesheet" href="../../public/css/mobile.css">
```

### Step 2: Include app.js Script
Tambahkan di akhir file HTML sebelum `</body>`:

```php
<!-- Sebelum </body> -->
<script src="../../public/js/app.js"></script>
```

### Step 3: Tambahkan Install Button (Optional)
Di navbar atau atas halaman, tambahkan:

```html
<!-- Di navbar atau container utama -->
<button id="install-btn" class="btn btn-primary btn-lg w-100" style="display: none;">
  <i class="bi bi-download"></i> Install as App
</button>
```

### Step 4: Styling Optimization
Pastikan semua halaman:
- Sudah menggunakan Bootstrap 5.3.0
- Memasukkan `mobile.css` (optional tapi recommended)
- Memiliki meta viewport tag (sudah di mobile-head.php)

---

## Checklist Integrasi

### Halaman yang perlu diupdate:
- [ ] login.php
- [ ] register.php
- [ ] index.php
- [ ] views/admin/dashboard.php
- [ ] views/admin/manage_candidates.php
- [ ] views/admin/settings.php
- [ ] views/admin/attendance.php
- [ ] views/siswa/voting.php
- [ ] views/guru/voting.php
- [ ] views/siswa/keluar.php
- [ ] views/guru/keluar.php

### Untuk setiap halaman:
```
1. ✓ Include mobile-head.php di <head>
2. ✓ Tambah mobile.css di <head> (opsional)
3. ✓ Tambah app.js sebelum </body>
4. ✓ Test di browser desktop (F12 > Mobile view)
5. ✓ Test di HP actual
```

---

## Testing PWA

### Desktop Browser (Chrome/Edge)
1. Buka aplikasi di browser: `http://localhost/webprosm2/pilkasis/`
2. Press F12 → Klik tab "Application"
3. Sidebar kiri → "Service Workers"
4. Harus terlihat: "Service Worker registered"
5. Lihat juga: Manifest, Cache Storage

### HP Android
1. Buka aplikasi di browser
2. Klik menu (⋮) → "Install app"
3. Atau tunggu prompt di atas
4. Pilih "Install"
5. App akan muncul di home screen

### iPhone/iPad
1. Buka di Safari
2. Tap share button
3. Pilih "Add to Home Screen"
4. Tap "Add"
5. App akan seperti halaman bookmark tapi berfungsi fullscreen

---

## Fitur-Fitur PWA yang Sudah Aktif

### ✅ Service Worker Caching
```
- HTML: Network first (selalu coba online dulu)
- Assets: Cache first (gunakan cache jika ada)
- Offline: Fallback message
```

### ✅ Background Sync
- Vote dapat disimpan offline
- Otomatis dikirim saat online kembali

### ✅ Install Prompt
- Tombol "Install" muncul otomatis di browser
- User dapat add ke home screen

### ✅ Responsive Design
- Touch-friendly buttons (min 44x44px)
- Mobile-optimized navigation
- Landscape & portrait support
- Safe area handling (notch support)

### ✅ Online/Offline Detection
- Notifikasi otomatis saat online/offline
- Graceful degradation saat offline

### ✅ Dark Mode Support
- Respects system dark mode preference
- Styling automatically adjusted

---

## Ikon Aplikasi (Optional - Untuk Hasil Terbaik)

Untuk setiap ukuran, buat image PNG dengan background solid (warna: #764ba2):

```
public/icons/
├── icon-192.png      (192x192px)
├── icon-512.png      (512x512px)
├── icon-maskable-192.png  (untuk adaptive icons)
├── icon-maskable-512.png
├── splash-640x1136.png    (iPhone 5 splash)
├── splash-750x1334.png    (iPhone 6/7/8 splash)
└── splash-1242x2208.png   (iPhone XR splash)
```

Jika tidak ada, PWA masih berfungsi dengan default icon dari browser.

---

## Troubleshooting

### Service Worker tidak terdaftar
```
1. Check console (F12 > Console)
2. Pastikan manifest.json path benar
3. Clear cache: Ctrl+Shift+Del > Clear service workers
4. Hard refresh: Ctrl+Shift+R
```

### Offline page muncul tapi seharusnya online
```
1. Check internet connection
2. Service worker mungkin cache halaman lama
3. Update cache version di service-worker.js:
   const CACHE_NAME = 'pilkasis-v2'; (ubah dari v1 ke v2)
```

### Install prompt tidak muncul
```
1. Pastikan app.js sudah di-include
2. Browser harus HTTPS (localhost OK)
3. Harus ada manifest.json yang valid
4. Halaman harus diakses minimal 30 detik
5. iPhone: Install via "Add to Home Screen" dari Safari share menu
```

### Halaman tidak responsive
```
1. Pastikan mobile-head.php sudah di-include
2. Checked viewport meta tag ada
3. Pastikan mobile.css di-include (recommended)
```

---

## Performance Tips

1. **Cache Strategy**
   - Gunakan service worker untuk caching assets
   - Update CACHE_NAME untuk force refresh

2. **Loading**
   - Preload fonts dan critical assets
   - Lazy load images yang tidak visible

3. **Storage**
   - Limit cache size (max 50MB recommended)
   - Clear old caches regularly

---

## Keamanan Notes

✅ Sessions masih secure (PHP server-side)
✅ Voting data hanya dikirim via server
✅ Offline voting di-sync saat online
✅ Browser cache hanya untuk static assets

---

## Next Steps

1. [ ] Buat icon dengan design yang bagus
2. [ ] Update manifest.json dengan icon paths
3. [ ] Test di HP Android & iPhone
4. [ ] Setup HTTPS (recommended untuk production)
5. [ ] Monitor cache usage
6. [ ] Gather user feedback

---

## Support

Jika ada pertanyaan, check:
- Browser console (F12 > Console)
- Application tab (F12 > Application)
- Network tab untuk offline simulation
