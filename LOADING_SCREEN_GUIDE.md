# 🔄 Loading Screen Integration Guide

## ✅ Sudah Diupdate

Halaman berikut sudah include loading screen:
- ✅ `index.php` 
- ✅ `login.php`

## 📋 Halaman yang Perlu Update

Add loading screen ke halaman-halaman ini:

### Admin Pages (4)
- [ ] `views/admin/dashboard.php`
- [ ] `views/admin/manage_candidates.php`
- [ ] `views/admin/settings.php`
- [ ] `views/admin/attendance.php`

### Siswa Pages (2)
- [ ] `views/siswa/voting.php`
- [ ] `views/siswa/keluar.php`

### Guru Pages (2)
- [ ] `views/guru/voting.php`
- [ ] `views/guru/keluar.php`

### Other Pages (2)
- [ ] `register.php`
- [ ] `logout.php`

---

## 🚀 Cara Integrasi (3 Cara)

### **CARA 1: Include Component (EASIEST) ⭐**

Untuk setiap halaman:

**Step 1: Di dalam `<style>` tag (atau sebelum `</head>`)**, tambah:
```php
<?php 
// Include loading screen styles
ob_start();
include '../../includes/loading-screen-component.php';
$loading_screen_code = ob_get_clean();
// Extract styles
preg_match('/<style>(.*?)<\/style>/s', $loading_screen_code, $styles);
if (!empty($styles[1])) {
    echo '<style>' . $styles[1] . '</style>';
}
?>
```

**Step 2: Di awal `<body>` tag:**
```php
<?php 
// Load the HTML and JS part
ob_start();
include '../../includes/loading-screen-component.php';
$content = ob_get_clean();
// Extract and echo only the HTML + JS (not styles)
echo preg_replace('/<style>.*?<\/style>/s', '', $content);
?>
```

### **CARA 2: Manual Copy-Paste**

Lihat file `includes/loading-screen-component.php` dan copy:
1. `<style>` section ke dalam `<head>` tag halaman Anda
2. `<div id="loading-screen">...</div>` ke awal `<body>`
3. `<script>` section sebelum `</body>`

### **CARA 3: Inline Styles (Simplest)**

Untuk halaman yang sudah punya style inline, tambah loading CSS langsung di `<style>` tag.

---

## ✨ Versi Singkat (Recommended)

Jika halaman sudah punya style tag, cukup:

**Di `<style>`:**
```css
/* Tambah loading screen styles dari includes/loading-screen-component.php */
```

**Di awal `<body>`:**
```html
<div id="loading-screen" class="loading-screen">
    <div class="loading-container">
        <div class="loading-icon"><i class="bi bi-check2-square"></i></div>
        <h2 class="loading-title">PILKASIS</h2>
        <p class="loading-subtitle">Pemilihan Ketua OSIS</p>
        <div class="loading-bar-container"><div class="loading-bar"></div></div>
        <p class="loading-text">Sedang memuat...</p>
        <div class="loading-status">
            <span class="dot"></span><span class="dot"></span><span class="dot"></span>
        </div>
    </div>
</div>
```

**Sebelum `</body>`:**
```html
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        setTimeout(() => { loadingScreen.classList.add('hidden'); }, 300);
        setTimeout(() => { loadingScreen.style.display = 'none'; }, 800);
    }
});

document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && !link.target && !link.href.startsWith('#')) {
        const loadingScreen = document.getElementById('loading-screen');
        if (loadingScreen) {
            loadingScreen.style.display = 'flex';
            loadingScreen.classList.remove('hidden');
        }
    }
});
</script>
```

---

## 🎨 Customization

### Change Loading Text
```html
<p class="loading-text">Memproses...</p>  <!-- Ubah text -->
```

### Change Colors
```css
.loading-screen {
    background: linear-gradient(135deg, #YOUR_COLOR1 0%, #YOUR_COLOR2 100%);
}
```

### Change Icon
```html
<i class="bi bi-hourglass-split"></i>  <!-- Ganti icon -->
```

---

## 🔧 JavaScript API

Setelah loading screen, gunakan:

```javascript
// Show loading
window.loadingScreen.show();

// Hide loading
window.loadingScreen.hide();

// Or use the direct hide
document.getElementById('loading-screen').classList.add('hidden');
```

---

## ✅ Testing

### Desktop
1. Buka halaman di browser
2. Loading screen muncul beberapa detik
3. Fade out dan hilang
4. Klik link → loading muncul lagi

### Mobile
1. Buka di Chrome Android
2. Loading smooth dan responsif
3. Tidak lag atau jitter
4. Safe area respected (notch)

---

## 📝 Checklist untuk Setiap Halaman

```
Untuk setiap halaman yang diupdate:

□ Cari <head> tag
□ Cari <style> tag (atau buat baru)
□ Copy loading screen CSS ke dalam style
□ Di <body>, awal content, paste loading screen HTML
□ Sebelum </body>, paste loading screen JS
□ Test di desktop (Inspect > toggle device toolbar)
□ Test di HP Android (klik beberapa link)
□ Verify: loading muncul/hilang smooth
□ No console errors (F12 > Console)
```

---

## 📊 File Reference

| File | Purpose |
|------|---------|
| `includes/loading-screen-component.php` | Complete component (HTML + CSS + JS) |
| `index.php` | Example - fully implemented ✅ |
| `login.php` | Example - fully implemented ✅ |

---

## 🔍 Troubleshooting

### Loading screen doesn't hide
```
Check: setTimeout delays (300ms & 800ms)
Check: 'hidden' class added properly
Check: animation: fadeOut in CSS present
```

### Loading shows when clicking internal anchor
```
Fix: Link has href="#..." → auto-ignored
Fix: Add data-no-loading="true" to link
```

### Multiple loading screens visible
```
Check: Only 1 id="loading-screen" per page
Check: Not included twice
Solution: Find and remove duplicate
```

### Styling looks different
```
Check: All @keyframes are copied
Check: All CSS classes for loading-*
Check: Mobile responsive styles (@media)
```

---

## ⚡ Quick Implementation (5 min)

1. Open file
2. Find `</head>` 
3. Before it, add loading CSS
4. Find `<body>`
5. After it, add loading HTML
6. Before `</body>`, add loading JS
7. Test
8. Done!

---

## 🎯 Success Criteria

✅ Loading screen appears when page opens
✅ Loading screen disappears after page loads
✅ Loading appears when clicking navigation links
✅ No console errors
✅ Responsive on mobile
✅ Animation smooth (60fps)
✅ Works in all browsers

---

Generated: February 16, 2026
Loading Screen Documentation v1.0
