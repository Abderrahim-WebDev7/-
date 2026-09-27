' stop_server.vbs
' إيقاف تشغيل نظام GARE7

Set WshShell = CreateObject("WScript.Shell")

' إيقاف عملية php
WshShell.Run "taskkill /F /IM php.exe", 0, True

' إيقاف عملية cmd
WshShell.Run "taskkill /F /IM cmd.exe", 0, True

' إغلاق المتصفحات
WshShell.Run "taskkill /F /IM chrome.exe", 0, True
WshShell.Run "taskkill /F /IM firefox.exe", 0, True
WshShell.Run "taskkill /F /IM msedge.exe", 0, True

MsgBox "✅ تم إيقاف نظام GARE7 بنجاح", vbInformation, "GARE7"