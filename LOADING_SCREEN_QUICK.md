# ⏳ Loading Screen - Quick Start

## ✅ Status
✓ Loading screen created & optimized
✓ `index.php` updated with loading screen  
✓ `login.php` updated with loading screen
✓ Ready for integration to other pages

---

## 📱 What It Looks Like

```
Saat halaman dibuka:

┌─────────────────────────────┐
│   PILKASIS                  │ ← Big purple gradient
│   Pemilihan Ketua OSIS      │
│   [animated checkmark]      │
│   [sliding progress bar]    │
│   Sedang memuat...          │
│   [bouncing dots: • • •]    │
└─────────────────────────────┘

Setelah page load:
   ↓ fade out smooth (0.5s)
   ↓ page appears
```

---

## 🚀 Next: Update Remaining Pages (11 halaman)

### Halaman yang Perlu Update

**Admin (4):**
- views/admin/dashboard.php
- views/admin/manage_candidates.php
- views/admin/settings.php
- views/admin/attendance.php

**Siswa (2):**
- views/siswa/voting.php
- views/siswa/keluar.php

**Guru (2):**
- views/guru/voting.php
- views/guru/keluar.php

**Root (2):**
- register.php
- logout.php

---

## 📋 Cara Update Setiap Halaman (5 menit per halaman)

Untuk **setiap halaman**, lakukan:

### **Step 1: Add CSS (Copy dari login.php)**

Cari `<style>` tag dalam halaman. Di dalamnya, paste:

```css
/* ===== LOADING SCREEN ===== */
.loading-screen {
    position: fixed;
    top: 0;left: 0;right: 0;bottom: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 99999;
    width: 100%;
    height: 100vh;
    overflow: hidden;
}

.loading-container {
    text-align: center;
    color: white;
}

.loading-icon {
    font-size: 80px;
    margin-bottom: 20px;
    animation: iconPulse 1.5s ease-in-out infinite;
}

@keyframes iconPulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

.loading-title {
    font-size: 32px;
    font-weight: bold;
    margin: 0 0 5px 0;
    letter-spacing: 2px;
    animation: fadeInDown 0.6s ease-out;
}

.loading-subtitle {
    font-size: 14px;
    margin: 0 0 30px 0;
    opacity: 0.9;
    animation: fadeInUp 0.6s ease-out 0.2s;
    animation-fill-mode: both;
}

.loading-bar-container {
    width: 200px;
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    margin: 30px auto;
    overflow: hidden;
}

.loading-bar {
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
    animation: slideBar 1.5s infinite;
    border-radius: 10px;
}

@keyframes slideBar {
    0% { transform: translateX(-200px); }
    100% { transform: translateX(200px); }
}

.loading-text {
    font-size: 16px;
    margin: 20px 0;
    opacity: 0.9;
    animation: fadeInUp 0.6s ease-out 0.4s;
    animation-fill-mode: both;
}

.loading-status {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    gap: 8px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
    opacity: 0.6;
    animation: dotBounce 1.4s infinite;
}

.dot:nth-child(1) { animation-delay: 0s; }
.dot:nth-child(2) { animation-delay: 0.2s; }
.dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes dotBounce {
    0%, 100% { opacity: 0.4; transform: translateY(0); }
    50% { opacity: 1; transform: translateY(-10px); }
}

@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.loading-screen.hidden {
    animation: fadeOut 0.5s ease-out forwards;
}

@keyframes fadeOut {
    to { opacity: 0; visibility: hidden; }
}

@media (max-width: 600px) {
    .loading-icon { font-size: 60px; }
    .loading-title { font-size: 24px; }
    .loading-subtitle { font-size: 12px; }
    .loading-bar-container { width: 150px; }
}
```

### **Step 2: Add HTML (Copy dari login.php)**

Awal `<body>` tag (paling atas, sebelum content apapun), paste:

```html
<!-- Loading Screen -->
<div id="loading-screen" class="loading-screen">
    <div class="loading-container">
        <div class="loading-icon">
            <i class="bi bi-check2-square"></i>
        </div>
        <h2 class="loading-title">PILKASIS</h2>
        <p class="loading-subtitle">Pemilihan Ketua OSIS</p>
        <div class="loading-bar-container">
            <div class="loading-bar"></div>
        </div>
        <p class="loading-text">Sedang memuat...</p>
        <div class="loading-status">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</div>
```

### **Step 3: Add JavaScript (Copy dari login.php)**

Sebelum `</body>` tag (akhir halaman), paste:

```html
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        setTimeout(() => {
            loadingScreen.classList.add('hidden');
        }, 2000);  // Mulai fade out setelah 2 detik
        setTimeout(() => {
            loadingScreen.style.display = 'none';
        }, 2500);  // Total 2.5 detik loading screen
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

## ✅ That's It!

Setiap halaman hanya butuh **3 step**:
1. Add CSS (copy-paste full block)
2. Add HTML (copy-paste div)
3. Add JS (copy-paste script)

**Waktu per halaman: ~2-3 menit**
**Total 11 halaman: ~30 menit**

---

## 🧪 Testing

Setelah update:

1. **Desktop:**
   - Buka halaman
   - Loading screen muncul 1-2 detik
   - Fade out smooth
   - Page appears
   - Klik link → loading muncul lagi

2. **Mobile:**
   - Buka di Chrome Android
   - Same behavior
   - Responsive on narrow screen
   - No white screen or jitter

---

## 📖 Reference

- **Example 1**: `index.php` - sudah updated ✅
- **Example 2**: `login.php` - sudah updated ✅
- **Full Guide**: `LOADING_SCREEN_GUIDE.md`
- **Detailed Info**: `LOADING_SCREEN_SUMMARY.md`

---

## 🎯 Checklist

```
Halaman                          Status
────────────────────────────────────────────
index.php                        ✅ DONE
login.php                        ✅ DONE
────────────────────────────────────────────
views/admin/dashboard.php        [ ] TODO
views/admin/manage_candidates    [ ] TODO
views/admin/settings.php         [ ] TODO
views/admin/attendance.php       [ ] TODO
────────────────────────────────────────────
views/siswa/voting.php           [ ] TODO
views/siswa/keluar.php           [ ] TODO
────────────────────────────────────────────
views/guru/voting.php            [ ] TODO
views/guru/keluar.php            [ ] TODO
────────────────────────────────────────────
register.php                     [ ] TODO
logout.php                       [ ] TODO
────────────────────────────────────────────
TOTAL: 2/13 Updated
```

---

## 🎉 Ready?

Sekarang tinggal:
1. Open halaman pertama
2. Add loading screen (Step 1-3)
3. Save & test
4. Repeat untuk halaman berikutnya

**Estimated total time: 40 minutes untuk semua 11 halaman**

Good luck! 🚀
