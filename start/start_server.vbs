' start_server.vbs
' تشغيل نظام GARE7 عبر PHP Desktop بدون ظهور نافذة التيرمينال

Option Explicit

Dim WshShell, fso
Dim projectPath, phpDesktopPath, port, url

Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

' ============================================
'  المسارات والإعدادات
' ============================================
projectPath = "C:\Users\Nx Tech\Desktop\gareh\system"
phpDesktopPath = "C:\Users\Nx Tech\Desktop\phpdesktop-chrome-130.1-php-8.3"
port = "8000"
url = "http://127.0.0.1:" & port

' ============================================
'  التحقق من وجود الملفات
' ============================================

' التحقق من وجود مشروع Laravel
If Not fso.FileExists(projectPath & "\artisan") Then
    MsgBox "❌ خطأ: ملف artisan غير موجود!" & vbCrLf & vbCrLf & _
           "المسار: " & projectPath, vbCritical, "GARE7"
    WScript.Quit
End If

' التحقق من وجود PHP Desktop
If Not fso.FileExists(phpDesktopPath & "\phpdesktop-chrome.exe") Then
    MsgBox "❌ خطأ: PHP Desktop غير موجود!" & vbCrLf & vbCrLf & _
           "المسار: " & phpDesktopPath & "\phpdesktop-chrome.exe", vbCritical, "GARE7"
    WScript.Quit
End If

' ============================================
'  تشغيل الخادم
' ============================================

' الانتقال إلى مجلد المشروع
WshShell.CurrentDirectory = projectPath

' تشغيل الخادم في الخلفية (بدون نافذة)
WshShell.Run "cmd /c php artisan serve --port=" & port, 0, False

' انتظار الخادم (1.5 ثانية)
WScript.Sleep 1500

' ============================================
'  تشغيل PHP Desktop
' ============================================

' الانتقال إلى مجلد PHP Desktop
WshShell.CurrentDirectory = phpDesktopPath

' تشغيل PHP Desktop مع الرابط
WshShell.Run "phpdesktop-chrome.exe --app=" & url, 1, False

' ============================================
'  إنهاء السكريبت
' ============================================