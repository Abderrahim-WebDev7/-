# ============================================================
# GAREH - Automatic Backup
# backup.ps1
# ============================================================

$ErrorActionPreference = "Stop"

# ------------------------------------------------------------
# 1. مسار مجلد backup
# ------------------------------------------------------------

$BackupScriptPath = Split-Path -Parent $MyInvocation.MyCommand.Path

# ------------------------------------------------------------
# 2. مسار المشروع GAREH
# backup.ps1 موجود داخل GAREH\backup
# لذلك نرجعو مجلد واحد للوراء
# ------------------------------------------------------------

$ProjectPath = Split-Path -Parent $BackupScriptPath

# ------------------------------------------------------------
# 3. البحث عن USB
# ------------------------------------------------------------

$USBDrive = Get-CimInstance Win32_LogicalDisk |
    Where-Object {
        $_.DriveType -eq 2
    } |
    Select-Object -First 1

if (-not $USBDrive) {
    Write-Host "لم يتم العثور على USB."
    exit 1
}

$USBPath = $USBDrive.DeviceID + "\"

# ------------------------------------------------------------
# 4. مجلد النسخ الاحتياطية داخل USB
# ------------------------------------------------------------

$USBBackupPath = Join-Path $USBPath "GAREH_Backups"

if (-not (Test-Path $USBBackupPath)) {
    New-Item -ItemType Directory -Path $USBBackupPath -Force | Out-Null
}

# ------------------------------------------------------------
# 5. إنشاء مجلد Backup جديد بالتاريخ والوقت
# ------------------------------------------------------------

$Date = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"

$CurrentBackup = Join-Path $USBBackupPath "Backup_$Date"

New-Item -ItemType Directory -Path $CurrentBackup -Force | Out-Null

# ------------------------------------------------------------
# 6. نسخ مجلد المشروع كامل
# ------------------------------------------------------------

$ProjectBackup = Join-Path $CurrentBackup "GAREH"

New-Item -ItemType Directory -Path $ProjectBackup -Force | Out-Null

Get-ChildItem -Path $ProjectPath -Force |
    Where-Object {
        $_.FullName -ne $BackupScriptPath
    } |
    Copy-Item -Destination $ProjectBackup -Recurse -Force

# ------------------------------------------------------------
# 7. معلومات النسخة
# ------------------------------------------------------------

$Info = @"
GAREH BACKUP
==============================

Date:
$(Get-Date -Format "yyyy-MM-dd HH:mm:ss")

Computer:
$env:COMPUTERNAME

Original Project:
$ProjectPath

USB:
$USBPath

Backup:
$CurrentBackup
"@

$Info | Out-File `
    -FilePath (Join-Path $CurrentBackup "backup_info.txt") `
    -Encoding UTF8

# ------------------------------------------------------------
# 8. الاحتفاظ بآخر 30 نسخة فقط
# ------------------------------------------------------------

$Backups = Get-ChildItem `
    -Path $USBBackupPath `
    -Directory |
    Sort-Object CreationTime -Descending

if ($Backups.Count -gt 30) {

    $Backups |
        Select-Object -Skip 30 |
        Remove-Item -Recurse -Force
}

Write-Host ""
Write-Host "======================================"
Write-Host "GAREH BACKUP COMPLETED"
Write-Host "======================================"
Write-Host ""
Write-Host "Backup saved to:"
Write-Host $CurrentBackup