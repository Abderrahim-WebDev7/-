@echo off
title نظام GARE7 - إدارة منتجات البيض
color 0E

:: ========================================
::  نظام GARE7 - تشغيل الخادم المحلي
::  مع فتح PHP Desktop بدون كاش
:: ========================================

:: تعيين المسارات
set PROJECT_PATH=C:\Users\Nx Tech\Desktop\gareh\system
set PHP_DESKTOP_PATH=C:\Users\NxTech\Desktop\phpdesktop-chrome-130.1-php-8.3
set PORT=8000
set URL=http://127.0.0.1:%PORT%

:: الانتقال إلى مجلد المشروع
cd /d "%PROJECT_PATH%"

:: التحقق من وجود ملف artisan
if not exist artisan (
    cls
    echo ========================================
    echo    ❌ خطأ: ملف artisan غير موجود!
    echo ========================================
    echo.
    echo المسار الحالي: %CD%
    echo.
    echo تأكد من أن المسار صحيح:
    echo %PROJECT_PATH%
    echo.
    pause >nul
    exit /b
)

:: التحقق من وجود PHP
where php >nul 2>nul
if errorlevel 1 (
    cls
    echo ========================================
    echo    ❌ خطأ: PHP غير مثبت أو غير موجود!
    echo ========================================
    echo.
    echo تأكد من تثبيت PHP وإضافته إلى PATH.
    echo.
    pause >nul
    exit /b
)

:: التحقق من وجود PHP Desktop
if not exist "%PHP_DESKTOP_PATH%\phpdesktop-chrome.exe" (
    cls
    echo ========================================
    echo    ❌ خطأ: PHP Desktop غير موجود!
    echo ========================================
    echo.
    echo المسار المتوقع:
    echo %PHP_DESKTOP_PATH%\phpdesktop-chrome.exe
    echo.
    pause >nul
    exit /b
)

:: عرض معلومات البدء
cls
echo ========================================
echo    🥚 نظام GARE7 - إدارة منتجات البيض
echo ========================================
echo.
echo 📂 المسار: %CD%
echo 🌐 المنفذ: %PORT%
echo.
echo 🚀 جاري تشغيل الخادم...
echo.

:: تشغيل الخادم في الخلفية
start /B cmd /C "php artisan serve --port=%PORT%" >nul 2>nul

:: انتظار الخادم
timeout /t 3 /nobreak >nul

:: تشغيل PHP Desktop مع تعطيل الكاش
echo 🖥️ جاري فتح التطبيق...
cd /d "%PHP_DESKTOP_PATH%"
start "" phpdesktop-chrome.exe --disable-cache --disable-application-cache --app=%URL%

:: إغلاق نافذة التيرمينال
exit