# ⚡ Pilkasis PWA - Test Checklist

Gunakan checklist ini untuk memverifikasi bahwa PWA sudah berfungsi dengan benar.

---

## 1️⃣ DESKTOP TEST (Chrome/Edge) - 5 menit

### Setup
- [ ] Buka browser: `http://localhost/webprosm2/pilkasis/`
- [ ] Tekan `F12` untuk buka Developer Tools
- [ ] Pergi ke tab `Application`

### Check Service Worker
- [ ] Lihat bagian `Service Workers` di sidebar kiri
- [ ] Verifikasi status: "Service Worker registered" ✓
- [ ] URL harus: `public/js/service-worker.js`
- [ ] Status harus: "running" (berwarna hijau)

### Check Manifest
- [ ] Klik `Manifest` di tab Application
- [ ] Buka file: `public/manifest.json`
- [ ] Verifikasi ada field:
  - [ ] `"name": "Pilkasis - Sistem Pemilihan..."`
  - [ ] `"short_name": "Pilkasis"`
  - [ ] `"start_url": "/webprosm2/pilkasis/index.php"`
  - [ ] `"display": "standalone"`
  - [ ] `"theme_color": "#764ba2"`

### Check Caching
- [ ] Lihat `Cache Storage` di sidebar kiri
- [ ] Expand cache: `pilkasis-v1`
- [ ] Verifikasi ada cached files:
  - [ ] index.php
  - [ ] bootstrap CSS
  - [ ] bootstrap JS
  - [ ] app.js
  - [ ] style.css

### Test Install Prompt
- [ ] Refresh halaman (F5)
- [ ] Tunggu 2-3 detik
- [ ] Cari ikon "+" di address bar (Chrome) atau "App" button
- [ ] Atau lihat install button di halaman (id="install-btn")

### Test Online/Offline
- [ ] Di Network tab → ubah ke "Offline"
- [ ] Refresh halaman (F5)
- [ ] Halaman harus tetap bisa dibuka (dari cache)
- [ ] Ubah kembali ke "Online"

**Result**: ✅ Desktop test passed

---

## 2️⃣ ANDROID TEST - 10 menit

### Prerequisites
- [ ] Android phone dengan Chrome browser terbaru
- [ ] Phone terhubung dengan internet
- [ ] Same network dengan computer (optional, bisa via hostname)

### Initial Test
- [ ] Buka Chrome di phone
- [ ] Ketik: `http://192.168.1.X:80/webprosm2/pilkasis/` 
  (ganti IP sesuai computer Anda, atau gunakan localhost jika di device)
- [ ] Halaman harus load dengan benar

### Test Install Prompt
- [ ] Di Chrome, klik menu (⋮) (3 garis di kanan atas)
- [ ] Lihat opsi: "Install app" atau "Add to Home Screen"
- [ ] Klik "Install app"
- [ ] Dialog harus muncul
- [ ] Klik "Install"
- [ ] Tunggu proses (2-5 detik)

### Verify Installation
- [ ] Kembali ke home screen
- [ ] Lihat icon baru "Pilkasis" (atau generic icon jika tidak ada custom)
- [ ] Klik icon tersebut
- [ ] App harus launch fullscreen (tanpa browser address bar)
- [ ] Nama di atas harus "Pilkasis"

### Test Functionality
- [ ] Bisa navigate ke yang pages
- [ ] Bisa klik buttons dan links
- [ ] Responsive design terlihat bagus
- [ ] Touch targets (button) mudah diklik

### Test Offline Mode
- [ ] Putus internet (airplane mode atau disable WiFi)
- [ ] Di app yang masih terbuka, refresh (swipe down)
- [ ] Halaman harus tetap muncul dari cache
- [ ] Cek console (F12) tidak ada error (atau swipe up untuk dev menu if available)

### Test Online Detection
- [ ] Nyalakan kembali internet
- [ ] Refresh halaman
- [ ] Harus ada notifikasi "Terhubung ke internet" (jika sudah implement notification)
- [ ] Data tetap terbaca dengan benar

**Result**: ✅ Android test passed

---

## 3️⃣ iOS TEST (iPhone/iPad) - 10 menit

### Initial Test
- [ ] Buka Safari di iPhone/iPad
- [ ] Ketik: `http://192.168.1.X/webprosm2/pilkasis/`
- [ ] Halaman harus load dengan benar
- [ ] Verifikasi responsive design terlihat bagus

### Test Add to Home Screen
- [ ] Di Safari, klik tombol Share (kotak dengan panah) di bawah
- [ ] Scroll ke: "Add to Home Screen"
- [ ] Klik "Add to Home Screen"
- [ ] Edit nama jika diperlukan (atau keep default)
- [ ] Klik "Add"
- [ ] Dialog harus close dan icon ditambah ke home screen

### Verify Installation
- [ ] Keluar dari Safari
- [ ] Lihat home screen
- [ ] Icon "Pilkasis" harus ada (dengan default icon atau custom)
- [ ] Klik icon
- [ ] App harus launch fullscreen
- [ ] iOS status bar berwarna sesuai theme color

### Test Functionality
- [ ] Navigasi antar pages berfungsi
- [ ] Buttons & form terlihat dengan baik
- [ ] Responsive design bagus di narrow screen
- [ ] Keyboard tidak zoom out

### Test Offline Mode
- [ ] Putus Wi-Fi
- [ ] Di app, refresh
- [ ] Halaman harus tetap muncul (iOS punya limited offline support)
- [ ] Nyalakan WiFi kembali

**Result**: ✅ iOS test passed

---

## 4️⃣ RESPONSIVE DESIGN TEST - 5 menit

### Desktop Chrome DevTools
- [ ] Tekan `F12`
- [ ] Klik icon "Toggle Device Toolbar" (Ctrl+Shift+M)
- [ ] Test berbagai ukuran:
  - [ ] iPhone SE (375x667)
  - [ ] iPhone 12 (390x844)
  - [ ] Pixel 4 (393x873)
  - [ ] iPad (768x1024)
  - [ ] Tablet (1024x768)

### Check Responsive Elements
- [ ] Navbar tetap readable
- [ ] Untuk setiap ukuran:
  - [ ] Tidak ada horizontal scroll
  - [ ] Text readable
  - [ ] Buttons ada jarak untuk diklik (44px minimum)
  - [ ] Layout tidak broken
  - [ ] Images responsive

### Test Touch Emulation
- [ ] Di DevTools → three dots → More tools → Sensors
- [ ] Enable "Emulate touch"
- [ ] Klik button harus responsive
- [ ] Check console tidak ada errors

**Result**: ✅ Responsive design test passed

---

## 5️⃣ PERFORMANCE TEST - 5 menit

### Check Load Time
- [ ] Buka DevTools Network tab
- [ ] Hard refresh (Ctrl+Shift+R)
- [ ] Lihat waktu loading:
  - [ ] HTML: < 1 second
  - [ ] CSS: < 500ms
  - [ ] JS: < 1 second
  - [ ] Total: < 3 seconds

### Check Cache Efficiency
- [ ] Refresh halaman 2x (F5)
- [ ] Refresh ke-2 harus lebih cepat
- [ ] Banyak resource harus dari cache (status "disk cache" atau "memory cache")

### Check Bundle Size
- [ ] Di Network tab, lihat total size:
  - [ ] HTML: < 50KB
  - [ ] CSS: < 30KB
  - [ ] JS: < 100KB
  - [ ] Total: < 500KB (reasonable)

**Result**: ✅ Performance test passed

---

## 6️⃣ SECURITY TEST - 5 menit

### Check HTTPS (Production)
- [ ] Manifest requires HTTPS untuk production
- [ ] Localhost OK untuk development
- [ ]Protocol harus "https" atau "http://localhost"

### Check Session Security
- [ ] Login & buat session
- [ ] Check cookies bersifat HttpOnly
- [ ] Logout & session clear
- [ ] Kembali refresh, harus redirect ke login

### Check Manifest Security
- [ ] Manifest.json tidak contain sensitive data
- [ ] Start URL tidak contain tokens
- [ ] Icons dari public folder (tidak sensitive)

**Result**: ✅ Security test passed

---

## 7️⃣ BROWSER COMPATIBILITY TEST - 5 menit

### Chrome/Chromium Based
- [ ] Chrome (Latest) ✅
- [ ] Edge (Latest) ✅
- [ ] Opera (Latest) ✅

### Firefox
- [ ] Firefox (Latest) - PWA installed, mungkin limited offline

### Safari
- [ ] Safari (iOS) - Add to Home Screen works ✅
- [ ] Safari (macOS) - Can add to dock ⚠️

**Result**: ✅ Compatibility verified

---

## 📋 FINAL CHECKLIST

### Code Quality
- [ ] Semua file terformat dengan baik
- [ ] No console errors (F12 > Console)
- [ ] No console warnings
- [ ] Service Worker tidak error

### User Experience
- [ ] Load time cepat (< 3 detik)
- [ ] Responsive di semua ukuran
- [ ] Touch targets cukup besar (44px+)
- [ ] Install prompt jelas dan mudah
- [ ] Offline mode berfungsi
- [ ] Online/offline status terdeteksi

### PWA Compliance
- [ ] Manifest.json valid (F12 > Application)
- [ ] Service Worker registered (F12 > Application)
- [ ] Icons ada di manifest
- [ ] start_url correct
- [ ] display: "standalone" ada
- [ ] theme_color ada

### Documentation
- [ ] README.md exist
- [ ] PWA_SETUP_GUIDE.md exist
- [ ] QUICK_START_PWA.md exist
- [ ] Code comments clear
- [ ] Error messages helpful

---

## 🚨 TROUBLESHOOTING

### Service Worker tidak registered
```
1. Check: F12 > Console untuk error
2. Pastikan: public/js/service-worker.js exist
3. Clear cache: Ctrl+Shift+Del > Service Workers
4. Hard refresh: Ctrl+Shift+R
```

### Install button tidak muncul
```
1. Check: public/js/app.js sudah di-include
2. Check: manifest valid (F12 > Application > Manifest)
3. Check: halaman sudah diakses > 30 detik
4. Try different browser (Chrome/Edge terbaik)
```

### Halaman white/blank
```
1. Check: F12 > Console error
2. Check: path ke resources correct (mobile-head.php, app.js)
3. Check: tidak ada syntax error di PHP
4. Try: clear cache dan hard refresh
```

### Offline tidak bekerja
```
1. Check: Service Worker status "running"
2. Check: halaman sudah di-cache (Network tab lihat status)
3. Update CACHE_NAME untuk force refresh
4. Check: halaman tidak butuh server untuk render
```

---

## ✅ SIGN-OFF

Setelah semua test passed, tandai di sini:

```
Test Checklist Completion Date: _______________

Desktop Test:        ✅ Passed  ☐ Failed  ☐ Skipped
Android Test:        ✅ Passed  ☐ Failed  ☐ Skipped
iOS Test:           ✅ Passed  ☐ Failed  ☐ Skipped
Responsive Test:     ✅ Passed  ☐ Failed  ☐ Skipped
Performance Test:    ✅ Passed  ☐ Failed  ☐ Skipped
Security Test:       ✅ Passed  ☐ Failed  ☐ Skipped
Compatibility Test:  ✅ Passed  ☐ Failed  ☐ Skipped

Overall Status:      ✅ READY FOR PRODUCTION
                     ☐ NEEDS FIXES
                     ☐ UNDER DEVELOPMENT

Notes:
_________________________________________________________________
_________________________________________________________________
```

---

## 🎉 Ready to Deploy!

Jika semua test sudah passed, aplikasi Pilkasis PWA siap untuk:
- ✅ Deploy ke production server
- ✅ Diakses oleh users
- ✅ Install sebagai mobile app
- ✅ Offline usage
- ✅ Data persistence

**Congratulations!** 🚀

---

Generated: February 15, 2026
Pilkasis PWA v1.0
