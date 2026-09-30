# ============================================================
#  GAREH - Backup Script
#  backup.ps1
# ============================================================
#  يقوم بـ:
#   1. نسخ database/database.sqlite (الأهم)
#   2. نسخ .env (الإعدادات)
#   3. نسخ storage/app (الملفات المرفوعة، إن وُجدت)
#   4. إنشاء backup_info.txt
#   5. الاحتفاظ بآخر 30 نسخة فقط
# ============================================================
#  التشغيل:
#   powershell -ExecutionPolicy Bypass -File backup.ps1
# ============================================================

# ═══════════════════════════════════════════════════════════
#  ⚙️  الإعدادات — ✏️ عدّل هنا فقط
# ═══════════════════════════════════════════════════════════

# 🔴 مسار الفلاشة (USB) — عدّله حسب حاسوبك
#    أمثلة:
#      "F:\"   → إذا كانت الفلاشة على F
#      "G:\"   → إذا كانت الفلاشة على G
#      "E:\"   → إذا كانت الفلاشة على E
$USBPath = "G:\"

# مجلد النسخ على الفلاشة
$BackupFolderName = "GAREH_Backups"

# عدد النسخ التي يتم الاحتفاظ بها
$MaxBackups = 100

# هل تريد نسخ .env أيضاً؟
$CopyEnvFile = $true

# هل تريد نسخ storage/app؟
$CopyStorageFolder = $true

# ═══════════════════════════════════════════════════════════
#  ⛔ لا تعدّل ما بعد هذا السطر إلا إذا كنت تعرف ما تفعل
# ═══════════════════════════════════════════════════════════

$ErrorActionPreference = "Stop"

# ------------------------------------------------------------
# 1. تحديد مسارات المشروع
# ------------------------------------------------------------

$BackupScriptPath = Split-Path -Parent $MyInvocation.MyCommand.Path
$ProjectPath      = Split-Path -Parent $BackupScriptPath

# ------------------------------------------------------------
# 2. التحقق من وجود المشروع
# ------------------------------------------------------------

if (-not (Test-Path (Join-Path $ProjectPath "artisan"))) {
    Write-Host ""
    Write-Host "❌ خطأ: مشروع Laravel غير موجود في:" -ForegroundColor Red
    Write-Host "   $ProjectPath"
    Write-Host ""
    exit 1
}

$DatabasePath = Join-Path $ProjectPath "database\database.sqlite"
$EnvPath      = Join-Path $ProjectPath ".env"
$StoragePath  = Join-Path $ProjectPath "storage\app"

# ------------------------------------------------------------
# 3. التحقق من وجود قاعدة البيانات
# ------------------------------------------------------------

if (-not (Test-Path $DatabasePath)) {
    Write-Host ""
    Write-Host "❌ خطأ: ملف قاعدة البيانات غير موجود في:" -ForegroundColor Red
    Write-Host "   $DatabasePath"
    Write-Host ""
    Write-Host "💡 تأكد من أن النظام يستخدم SQLite:"
    Write-Host "   DB_CONNECTION=sqlite في ملف .env"
    Write-Host ""
    exit 1
}

$DatabaseSizeKB = [math]::Round((Get-Item $DatabasePath).Length / 1KB, 2)

# ------------------------------------------------------------
# 4. التحقق من وجود الفلاشة
# ------------------------------------------------------------

if (-not (Test-Path $USBPath)) {
    Write-Host ""
    Write-Host "❌ خطأ: الفلاشة غير موجودة على المسار: $USBPath" -ForegroundColor Red
    Write-Host ""
    Write-Host "💡 تحقق من:"
    Write-Host "   1. إدخال الفلاشة في المنفذ"
    Write-Host "   2. المسار الصحيح في إعدادات السكربت ($USBPath)"
    Write-Host "   3. افتح File Explorer وتأكد من حرف الفلاشة"
    Write-Host ""
    exit 1
}

# ------------------------------------------------------------
# 5. إنشاء مجلد النسخ على الفلاشة
# ------------------------------------------------------------

$USBBackupPath = Join-Path $USBPath $BackupFolderName

if (-not (Test-Path $USBBackupPath)) {
    New-Item -ItemType Directory -Path $USBBackupPath -Force | Out-Null
}

# ------------------------------------------------------------
# 6. إنشاء مجلد نسخة جديد بالتاريخ والوقت
# ------------------------------------------------------------

$Timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"
$CurrentBackup = Join-Path $USBBackupPath "Backup_$Timestamp"

New-Item -ItemType Directory -Path $CurrentBackup -Force | Out-Null

# ------------------------------------------------------------
# 7. نسخ قاعدة البيانات
# ------------------------------------------------------------

$DatabaseDestination = Join-Path $CurrentBackup "database.sqlite"
Copy-Item -Path $DatabasePath -Destination $DatabaseDestination -Force

# ------------------------------------------------------------
# 8. نسخ .env (إذا كان مطلوباً)
# ------------------------------------------------------------

if ($CopyEnvFile -and (Test-Path $EnvPath)) {
    $EnvDestination = Join-Path $CurrentBackup ".env"
    Copy-Item -Path $EnvPath -Destination $EnvDestination -Force
}

# ------------------------------------------------------------
# 9. نسخ storage/app (إذا كان مطلوباً)
# ------------------------------------------------------------

$StorageFileCount = 0

if ($CopyStorageFolder -and (Test-Path $StoragePath)) {
    $StorageFiles = Get-ChildItem -Path $StoragePath -File -Recurse -ErrorAction SilentlyContinue

    if ($StorageFiles.Count -gt 0) {
        $StorageDestination = Join-Path $CurrentBackup "storage_app"
        New-Item -ItemType Directory -Path $StorageDestination -Force | Out-Null

        Copy-Item -Path "$StoragePath\*" -Destination $StorageDestination -Recurse -Force
        $StorageFileCount = $StorageFiles.Count
    }
}

# ------------------------------------------------------------
# 10. إنشاء backup_info.txt
# ------------------------------------------------------------

$Info = @"
============================================================
  GAREH - Backup Information
============================================================

📅 التاريخ:          $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")
💻 الكمبيوتر:        $env:COMPUTERNAME
👤 المستخدم:         $env:USERNAME

📁 المشروع الأصلي:   $ProjectPath
💾 قاعدة البيانات:   $DatabasePath
🔌 الفلاشة:          $USBPath
📂 مجلد النسخة:      $CurrentBackup

------------------------------------------------------------
  محتويات النسخة
------------------------------------------------------------

✅ database.sqlite       (قاعدة البيانات - $DatabaseSizeKB KB)
$(if ($CopyEnvFile -and (Test-Path $EnvPath)) { "✅ .env                  (الإعدادات)" })
$(if ($StorageFileCount -gt 0) { "✅ storage_app/          ($StorageFileCount ملف)" })

------------------------------------------------------------
  كيفية الاستعادة على حاسوب جديد
------------------------------------------------------------

1. ثبّت المتطلبات:
   - PHP 8.2+
   - Composer
   - Node.js 18+
   - Git

2. استنسخ المشروع من GitHub:
   git clone https://github.com/Abderrahim-WebDev7/-.git
   cd -

3. ثبّت مكتبات PHP:
   composer install

4. ثبّت مكتبات Frontend:
   npm install
   npm run build

5. انسخ database.sqlite من هذه النسخة:
   copy "database.sqlite" "database\"

6. انسخ .env (إذا كنت نسخته):
   copy ".env" ".env"

7. (اختياري) انسخ الملفات المرفوعة:
   xcopy "storage_app\*" "storage\app\" /E /I

8. اربط التخزين:
   php artisan storage:link

9. شغّل الخادم:
   php artisan serve

============================================================
"@

$Info | Out-File -FilePath (Join-Path $CurrentBackup "backup_info.txt") -Encoding UTF8

# ------------------------------------------------------------
# 11. الاحتفاظ بآخر 30 نسخة فقط
# ------------------------------------------------------------

$Backups = Get-ChildItem -Path $USBBackupPath -Directory -ErrorAction SilentlyContinue |
    Sort-Object CreationTime -Descending

$TotalBackups = $Backups.Count
$DeletedCount = 0

if ($TotalBackups -gt $MaxBackups) {
    $ToDelete = $Backups | Select-Object -Skip $MaxBackups
    $DeletedCount = $ToDelete.Count

    foreach ($Backup in $ToDelete) {
        Remove-Item -Path $Backup.FullName -Recurse -Force -ErrorAction SilentlyContinue
    }
}

# ------------------------------------------------------------
# 12. حساب حجم النسخة
# ------------------------------------------------------------

$BackupSizeKB = 0
if (Test-Path $CurrentBackup) {
    $BackupSizeKB = [math]::Round((Get-ChildItem -Path $CurrentBackup -Recurse -ErrorAction SilentlyContinue |
        Measure-Object -Property Length -Sum).Sum / 1KB, 2)
}

# ------------------------------------------------------------
# 13. عرض النتيجة النهائية
# ------------------------------------------------------------

Write-Host ""
Write-Host "============================================================" -ForegroundColor Green
Write-Host "  ✅ اكتمل النسخ الاحتياطي بنجاح" -ForegroundColor Green
Write-Host "============================================================" -ForegroundColor Green
Write-Host ""
Write-Host "📁 المسار:      $CurrentBackup"
Write-Host "📊 الحجم:       $BackupSizeKB KB"
Write-Host "🕐 الوقت:       $(Get-Date -Format 'HH:mm:ss')"
Write-Host "💾 قاعدة البيانات: $DatabaseSizeKB KB"
Write-Host ""

if ($StorageFileCount -gt 0) {
    Write-Host "📂 الملفات المرفوعة: $StorageFileCount ملف"
}

Write-Host ""
Write-Host "------------------------------------------------------------"
Write-Host "  📋 آخر 5 نسخ احتياطية:"
Write-Host "------------------------------------------------------------"
Write-Host ""

$Backups | Select-Object -First 5 | ForEach-Object {
    $Size = [math]::Round((Get-ChildItem -Path $_.FullName -Recurse -ErrorAction SilentlyContinue |
        Measure-Object -Property Length -Sum).Sum / 1KB, 2)

    $DateStr = $_.CreationTime.ToString("yyyy-MM-dd HH:mm")

    Write-Host "   📦 $($_.Name)"
    Write-Host "      📅 $DateStr    📊 $Size KB"
    Write-Host ""
}

if ($DeletedCount -gt 0) {
    Write-Host "🗑️  تم حذف $DeletedCount نسخة قديمة (تم الاحتفاظ بـ $MaxBackups نسخة)" -ForegroundColor Yellow
    Write-Host ""
}

Write-Host "------------------------------------------------------------"
Write-Host "  📊 الإحصائيات:"
Write-Host "------------------------------------------------------------"
Write-Host "   إجمالي النسخ المحفوظة: $([math]::Min($TotalBackups, $MaxBackups)) / $MaxBackups"
Write-Host ""
Write-Host "============================================================" -ForegroundColor Green
Write-Host ""

exit 0
