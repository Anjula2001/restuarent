@echo off
REM Grand Restaurant Backup Script for Windows
REM Creates a complete backup of the restaurant system

echo 🍽️ Grand Restaurant Backup Tool (Windows)
echo ==========================================

REM Get current timestamp
for /f "tokens=2 delims==" %%a in ('wmic OS Get localdatetime /value') do set "dt=%%a"
set "YY=%dt:~2,2%" & set "YYYY=%dt:~0,4%" & set "MM=%dt:~4,2%" & set "DD=%dt:~6,2%"
set "HH=%dt:~8,2%" & set "Min=%dt:~10,2%" & set "Sec=%dt:~12,2%"
set "timestamp=%YYYY%%MM%%DD%_%HH%%Min%%Sec%"

set "BACKUP_DIR=backup_%timestamp%"

REM Create backup directory
mkdir "%BACKUP_DIR%" 2>nul

echo 📦 Creating backup in: %BACKUP_DIR%

REM Copy essential files
echo 📁 Copying core files...
xcopy /E /I /Q "*.html" "%BACKUP_DIR%\" 2>nul
xcopy /E /I /Q "*.php" "%BACKUP_DIR%\" 2>nul
xcopy /E /I /Q "api" "%BACKUP_DIR%\api\" 2>nul
xcopy /E /I /Q "classes" "%BACKUP_DIR%\classes\" 2>nul
xcopy /E /I /Q "config" "%BACKUP_DIR%\config\" 2>nul
xcopy /E /I /Q "css" "%BACKUP_DIR%\css\" 2>nul
xcopy /E /I /Q "js" "%BACKUP_DIR%\js\" 2>nul
xcopy /E /I /Q "images" "%BACKUP_DIR%\images\" 2>nul
xcopy /E /I /Q "admin" "%BACKUP_DIR%\admin\" 2>nul

REM Copy database and schema
echo 🗄️ Backing up database...
mkdir "%BACKUP_DIR%\database" 2>nul
copy "database\schema.sql" "%BACKUP_DIR%\database\" 2>nul
copy "database\grand_restaurant_complete.sql" "%BACKUP_DIR%\database\" 2>nul
copy "database\complete_database.sql" "%BACKUP_DIR%\database\" 2>nul
copy "database\grand_restaurant.db" "%BACKUP_DIR%\database\" 2>nul

REM Copy uploads if they exist
echo 📷 Backing up uploads...
if exist "uploads" (
    xcopy /E /I /Q "uploads" "%BACKUP_DIR%\uploads\" 2>nul
)

REM Copy documentation
echo 📚 Copying documentation...
copy "README.md" "%BACKUP_DIR%\" 2>nul
copy "DEPLOYMENT.md" "%BACKUP_DIR%\" 2>nul
copy "WINDOWS_XAMPP.md" "%BACKUP_DIR%\" 2>nul

REM Create backup info file
echo 📝 Creating backup info...
(
echo Grand Restaurant System Backup ^(Windows^)
echo ==========================================
echo.
echo Backup Date: %date%
echo Backup Time: %time%
echo System: Windows
echo User: %username%
echo Computer: %computername%
echo.
echo Contents:
echo - Core PHP files
echo - HTML templates
echo - CSS/JS assets
echo - Database and schema
echo - Upload files
echo - Documentation
echo.
echo Windows XAMPP Restore Instructions:
echo 1. Install XAMPP from https://www.apachefriends.org/
echo 2. Copy all files to C:\xampp\htdocs\restaurant\
echo 3. Start Apache in XAMPP Control Panel
echo 4. Run setup: http://localhost/restaurant/setup.php
echo 5. Access: http://localhost/restaurant/
echo.
echo Admin Credentials:
echo Username: admin
echo Password: admin123 ^(change after restore^)
echo.
echo XAMPP Requirements:
echo - Apache Web Server
echo - PHP 7.4+ with SQLite support
echo - No MySQL needed ^(uses SQLite^)
) > "%BACKUP_DIR%\BACKUP_INFO.txt"

REM Create PowerShell script to zip the backup
echo 📦 Creating ZIP archive...
powershell -command "Compress-Archive -Path '%BACKUP_DIR%' -DestinationPath 'grand_restaurant_backup_%timestamp%.zip' -CompressionLevel Optimal"

REM Clean up temporary directory
rmdir /s /q "%BACKUP_DIR%"

echo ✅ Backup completed successfully!
echo 📦 Archive: grand_restaurant_backup_%timestamp%.zip
echo.
echo To restore on Windows XAMPP:
echo 1. Extract ZIP to C:\xampp\htdocs\restaurant\
echo 2. Start Apache in XAMPP Control Panel
echo 3. Visit: http://localhost/restaurant/setup.php
echo 4. Follow the setup instructions
echo.
echo To restore on Mac MAMP:
echo 1. Extract ZIP to /Applications/MAMP/htdocs/restaurant/
echo 2. Start MAMP servers
echo 3. Visit: http://localhost:8888/restaurant/setup.php
echo.
pause
