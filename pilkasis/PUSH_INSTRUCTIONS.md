# Instruksi Push & Deploy

Langkah di bawah mencakup cara menjalankan aplikasi ini secara lokal menggunakan Docker, serta opsi deployment ke layanan PaaS.

1) Jika belum punya repository di GitHub:
   - Buat repository baru di https://github.com/new (beri nama mis. `pilkasis`).

2) Push perubahan ke GitHub (jika belum):

   ```bash
   git init
   git add .
   git commit -m "Initial import of pilkasis"
   git branch -M main
   git remote add origin https://github.com/USERNAME/REPO.git
   git push -u origin main
   ```

3) Jalankan dan uji menggunakan Docker (lokal):

   - Build image:

   ```bash
   docker build -t pilkasis:local .
   ```

   - Run container (akses di http://localhost:8080):

   ```bash
   docker run --rm -p 8080:80 pilkasis:local
   ```

4) Deploy ke Render / platform serupa (rekomendasi):

   - Pilihan A (Render) — mudah, mendukung Docker:
     1. Buat Web Service baru di https://dashboard.render.com
     2. Hubungkan GitHub repo Anda dan pilih branch `main`.
     3. Set `Root` ke `/` (repo sudah berisi `pilkasis` di root) atau path yang sesuai.
     4. Pilih `Docker` (Render akan menggunakan `Dockerfile` di repo) dan deploy.

   - Pilihan B (Railway / Fly / DigitalOcean App Platform): juga mendukung Docker atau PHP runtime.

5) Jika ingin tetap menggunakan Vercel untuk static frontend:
   - Host static files di Vercel (`public/`), dan host PHP backend di Render/Railway.
   - Ubah fetch URL di client (mis. `public/js/service-worker.js`) menjadi URL backend publik (mis. `https://api.example.com/api/sync-vote.php`).

6) Jika Anda mau, saya dapat membantu membuat service di Render atau men-deploy otomatis (perlu akses GitHub dan Render login).
