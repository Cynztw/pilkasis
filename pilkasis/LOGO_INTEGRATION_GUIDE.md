# 🎓 Panduan Logo Pramuka - Pilkasis Voting System

## 📍 Lokasi Logo File

**Direktori Lengkap:**
```
c:\xampp\htdocs\webprosm2\pilkasis\public\images\pramuka-logo.png
```

**Struktur Folder:**
```
pilkasis/
├── public/
│   └── images/
│       ├── candidates/        (foto kandidat)
│       ├── pramuka-logo.png  ← LOGO DISINI
│       └── [icons folder]
└── [other files]
```

## 🎯 Dimana Logo Akan Muncul

### 1. **Admin Settings Page** ✅ UTAMA
- **Lokasi**: Navbar (ujung kiri)
- **Ukuran**: 40x40 pixel (circle)
- **Style**: White background dengan shadow
- **File**: `views/admin/settings.php` (baris 772-773)

```html
<img src="../../public/images/pramuka-logo.png" alt="Logo" class="navbar-logo">
```

### 2. Siap untuk Halaman Lain
- Logo dapat ditambahkan ke semua halaman menggunakan templat
- Path yang sama: `../../public/images/pramuka-logo.png`
- Automatic fallback jika file tidak ada (tetap berfungsi)

## 📋 Spesifikasi Logo yang Sempurna

### Ukuran Rekomendasi
- **Minimum**: 256x256 pixel
- **Ideal**: 512x512 pixel
- **Format**: PNG dengan transparency (recommended)
- **Ratio**: Square (1:1) untuk tampilan terbaik

### Kualitas File
- **Format**: PNG, JPG, atau WebP
- **Compression**: Minimal (kualitas tinggi)
- **Background**: Transparent recommended
- **File Size**: < 500 KB

### Display Information
Pada halaman settings:
- Ditampilkan dalam circle frame (40x40px)
- Border radius penuh (rounded)
- Subtle shadow effect
- White background di belakang

## ✅ Checklist Penempatan Logo

- [ ] Logo file ditempatkan di: `public/images/pramuka-logo.png`
- [ ] Nama file tepat: `pramuka-logo.png` (case-sensitive)
- [ ] Format file valid: PNG/JPG/WebP
- [ ] File size < 500 KB
- [ ] Test di admin settings page
- [ ] Logo muncul di navbar dengan baik
- [ ] Logo responsif di mobile device
- [ ] Tunggu cache browser refresh jika perlu (Ctrl+Shift+Delete)

## 🧪 Testing Logo

### Desktop
1. Buka: `http://localhost/webprosm2/pilkasis/views/admin/settings.php`
2. Lihat navbar atas halaman
3. Logo harus muncul di ujung kiri sebelum text "PILKASIS"
4. Hover over logo melihat shadow effect

### Mobile
1. Buka halaman yang sama di smartphone
2. Logo tetap terlihat di navbar
3. Responsive dan tidak bergeser
4. Loading screen juga menampilkan loader icon

### Dark Mode
1. Enable dark mode di system (Windows Settings)
2. Refresh browser
3. Logo tetap visible dan terlihat jelas

## 🎨 Styling Logo (Nanti bisa dikustomisasi)

Current CSS untuk logo:
```css
.navbar-logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;          /* circle shape */
    background: white;            /* white background */
    padding: 3px;                /* inner padding */
    box-shadow: 0 2px 8px 
                rgba(0, 0, 0, 0.2);  /* subtle shadow */
}
```

Jika ingin mengubah:
- Ukuran: Ganti width/height
- Bentuk: Ganti border-radius (0 = square, 50% = circle)
- Shadow: Edit box-shadow values
- Background: Ganti warna background

## 📱 Fallback Behavior

Jika logo tidak ditemukan:
- Sistem tetap berfungsi normal
- Text "PILKASIS" tetap ditampilkan
- Tidak ada error di console
- Halaman fully functional

Ini adalah fitur keamanan intelligent design - tidak crash meski logo hilang.

## 🔄 Update Logo Nanti

Jika ingin mengganti logo di masa depan:
1. Hapus file lama: `public/images/pramuka-logo.png`
2. Tempatkan file baru dengan nama sama
3. Refresh browser cache (Ctrl+Shift+Delete)
4. Reload halaman

## 📞 Troubleshooting

### Logo tidak muncul?
- [ ] Check file exist di path yang tepat
- [ ] Verify nama file: `pramuka-logo.png` (case sensitive)
- [ ] Clear browser cache (Ctrl+Shift+Delete)
- [ ] Refresh halaman (F5)
- [ ] Lihat console browser (F12) untuk error

### Logo blur atau pixelated?
- [ ] Upload file dengan ukuran minimal 256x256
- [ ] Pastikan format PNG untuk transparency
- [ ] Gunakan file quality tinggi

### Logo tidak responsif di mobile?
- [ ] CSS sudah handling responsive
- [ ] Coba di browser lain
- [ ] Clear cache mobile

## 🎯 File Location Summary

| Item | Path | Status |
|------|------|--------|
| Logo File | `public/images/pramuka-logo.png` | ⏳ Menunggu gambar |
| Settings Page | `views/admin/settings.php` | ✅ Siap |
| CSS Styling | `<style>` di settings.php | ✅ Siap |
| Fallback Logic | `file_exists()` check | ✅ Siap |

## ✨ Hasil Akhir

Setelah logo ditempatkan, Anda akan melihat:

**Navbar Settings Page:**
```
┌─────────────────────────────────────────┐
│ [🎓] PILKASIS    Dashboard    Logout   │
└─────────────────────────────────────────┘
   ↑
  Logo muncul di sini!
```

---

**Version**: 2.0.1 Logo Integration
**Date**: 16 February 2026
**Status**: Ready for Logo Placement ✅

Silakan tempatkan file logo dan refresh halaman untuk melihat hasilnya!
