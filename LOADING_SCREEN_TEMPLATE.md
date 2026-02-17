# Quick Guide: Adding Loading Screen to Remaining Pages

## Pages Completed ✅
1. ✅ views/admin/settings.php (Full upgrade)
2. ✅ views/admin/dashboard.php
3. ✅ views/siswa/voting.php
4. ✅ register.php
5. ✅ index.php (was already done)
6. ✅ login.php (was already done)

## Pages Still Needing Update (7 remaining)
- [ ] views/admin/manage_candidates.php
- [ ] views/admin/attendance.php
- [ ] views/siswa/keluar.php
- [ ] views/guru/voting.php
- [ ] views/guru/keluar.php
- [ ] logout.php
- [ ] views/guru/dashboard.php (if exists)

## Copy-Paste Template

### Step 1: Update `<head>` Meta Tags
Replace the existing `<head>` section with this:

```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=yes">
    <meta name="description" content="[PAGE_TITLE] - Pilkasis Voting System">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Pilkasis">
    <meta name="theme-color" content="#764ba2">
    <meta name="color-scheme" content="light dark">
    
    <title>[PAGE_TITLE] - Pilkasis</title>
    
    <link rel="manifest" href="../../public/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="../../public/icons/icon-192.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/mobile.css">
    
    <style>
        /* ===== LOADING SCREEN ===== */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
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
            animation: fadeInUp 0.6s ease-out 0.2s both;
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
        }

        @keyframes slideBar {
            0% { transform: translateX(-200px); }
            100% { transform: translateX(200px); }
        }

        .loading-screen.hidden {
            animation: fadeOut 0.5s ease-out forwards;
        }

        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== EXISTING STYLES ===== */
        /* Keep all your existing styles here */
    </style>
</head>
```

### Step 2: Add Loading Screen HTML to Body
Add this right after `<body>` tag:

```html
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-container">
            <div class="loading-icon">
                <i class="bi bi-[ICON]"></i>
            </div>
            <div class="loading-title">PILKASIS</div>
            <div class="loading-subtitle">[PAGE_TITLE]</div>
            <div class="loading-bar-container">
                <div class="loading-bar"></div>
            </div>
        </div>
    </div>

    <!-- Rest of your page content here -->
```

### Step 3: Add JavaScript Before Closing Body Tag
Add this before `</body>`:

```html
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Loading Screen Timing
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loadingScreen');
            setTimeout(() => {
                loadingScreen.classList.add('hidden');
            }, 2000);
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 2500);
        });

        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('../../public/js/service-worker.js').catch(() => {});
        }
    </script>
</body>
</html>
```

## Icon Guide for Each Page

Choose the appropriate icon for your page type:

```
Admin Pages:
- dashboard.php → bi-speedometer2 (speedometer icon)
- manage_candidates.php → bi-person-badge (candidates)
- attendance.php → bi-clipboard-check (checklist)
- settings.php → bi-gear-fill (gear icon) ✅ Already set

Student Pages:
- voting.php → bi-hand-thumbs-up-fill (thumbs up) ✅ Already set
- keluar.php → bi-box-arrow-right (exit icon)

Teacher Pages:
- voting.php → bi-hand-index (pointing finger)
- keluar.php → bi-door-open (door icon)
- dashboard.php → bi-graph-up (chart icon)

Root Pages:
- index.php → bi-diagram-3 (diagram) ✅ Already set
- login.php → bi-lock-fill (lock icon) ✅ Already set
- register.php → bi-person-plus-fill (person plus) ✅ Already set
- logout.php → bi-box-arrow-right (exit icon)
```

## Path Fix Guide

### For Files in Root Directory (login.php, register.php, index.php, logout.php)
Use these paths:
```
- public/manifest.json
- public/icons/icon-192.png
- public/css/mobile.css
- public/js/service-worker.js
```

### For Files in views/* Directories
Use these paths:
```
- ../../public/manifest.json
- ../../public/icons/icon-192.png
- ../../public/css/mobile.css
- ../../public/js/service-worker.js
```

### For Files in views/admin/*, views/siswa/*, views/guru/*
Use these paths (same as above):
```
- ../../public/manifest.json
- ../../public/icons/icon-192.png
- ../../public/css/mobile.css
- ../../public/js/service-worker.js
```

## Quick Find & Replace Examples

### Example 1: manage_candidates.php
```
[PAGE_TITLE] = "Kelola Kandidat"
[ICON] = "bi-person-badge"
```

### Example 2: attendance.php
```
[PAGE_TITLE] = "Daftar Kehadiran"
[ICON] = "bi-clipboard-check"
```

### Example 3: guru/voting.php
```
[PAGE_TITLE] = "Voting Guru"
[ICON] = "bi-hand-index"
```

## Testing After Update

For each page updated:
1. Open in browser
2. Loading screen should appear for 2.5 seconds
3. Page should show after fade-out
4. Works in dark mode
5. Responsive on mobile
6. No console errors

## Verification Checklist

- [ ] All paths use correct relative paths
- [ ] Icon from Bootstrap Icons is valid
- [ ] Page title is descriptive
- [ ] Service worker path is correct
- [ ] No duplicate `<head>` or `<body>` tags
- [ ] All styles are preserved from original
- [ ] Closing tags are correct
- [ ] Page loads without errors
- [ ] Loading screen appears
- [ ] Page content visible after

## If Something Breaks

1. Check browser console (F12 → Console tab)
2. Look for path errors in Service Worker registration
3. Verify all brackets and quotes are balanced
4. Check that original styles are still included
5. Inspect element to see if HTML structure is correct

## Need Help?

Reference the completed pages:
- [settings.php](views/admin/settings.php) - Most sophisticated
- [dashboard.php](views/admin/dashboard.php) - Simple example
- [voting.php](views/siswa/voting.php) - Student page example
- [register.php](register.php) - Root directory example

---

**Estimated Time**: ~5-10 minutes per page
**Difficulty**: Low (mainly copy-paste)
**Risk Level**: Very Low (PWA features degrade gracefully)
**Can Revert**: Yes (just replace `<head>` and `<body>` sections)
