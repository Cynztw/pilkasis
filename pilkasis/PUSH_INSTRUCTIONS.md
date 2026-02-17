# Instruksi Push ke GitHub

1) Jika belum punya repository di GitHub:
   - Buat repository baru di https://github.com/new (beri nama mis. `pilkasis`).

2) Dari mesin lokal (di dalam folder `pilkasis`) jalankan:

   ```bash
   git init
   git add .
   git commit -m "Initial import of pilkasis"
   git branch -M main
   git remote add origin https://github.com/USERNAME/REPO.git
   git push -u origin main
   ```

   Ganti URL remote dengan URL repository Anda.

3) Jika menggunakan GitHub CLI (`gh`) dan sudah login, Anda bisa membuat repo dan push otomatis:

   ```bash
   gh repo create USERNAME/REPO --public --source=. --remote=origin --push
   ```

4) Jika Anda perlu bantuan menjalankan perintah di mesin ini, beri tahu saya `Yes, push now` dan berikan URL remote.
