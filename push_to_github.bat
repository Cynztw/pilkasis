@echo off
REM Ganti REMOTE_URL dan BRANCH sesuai kebutuhan
SET REMOTE_URL=https://github.com/USERNAME/REPO.git
SET BRANCH=main

git init
git add .
git commit -m "Initial import of pilkasis"
git branch -M %BRANCH%
git remote add origin %REMOTE_URL%
git push -u origin %BRANCH%

echo Done. Jika ada error, periksa kredensial Git dan URL remote.
