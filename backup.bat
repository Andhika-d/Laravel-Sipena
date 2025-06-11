@echo off
setlocal enabledelayedexpansion

:: === Konfigurasi ===
set DB_USER=root
set DB_PASS=
set DB_NAME=sipena
set BACKUP_FOLDER=database_backup

:: === Buat nama file backup pake timestamp ===
for /f %%i in ('powershell -command "Get-Date -Format yyyy-MM-dd_HH-mm-ss"') do set DATETIME=%%i
set BACKUP_FILE=%BACKUP_FOLDER%\backup_%DATETIME%.sql

:: === Path ke mysqldump versi lo ===
set MYSQLDUMP_PATH="C:\laragon\bin\mysql\mysql-8.0.40-winx64\bin\mysqldump.exe"

:: === Jalankan backup ===
echo Membackup database ke: %BACKUP_FILE%
%MYSQLDUMP_PATH% -u %DB_USER% -p%DB_PASS% %DB_NAME% > %BACKUP_FILE%

echo.
echo Backup selesai! File: %BACKUP_FILE%
pause
