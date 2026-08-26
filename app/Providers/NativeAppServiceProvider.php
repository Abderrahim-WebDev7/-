<?php

namespace App\Providers;

use Native\Laravel\Contracts\ProvidesPhpIni;
use Native\Laravel\Facades\ContextMenu;
use Native\Laravel\Facades\MenuBar;
use Native\Laravel\Facades\Window;
use Native\Laravel\Menu\Menu;

/**
 * NativeAppServiceProvider
 * -------------------------
 * هذا الملف هو نقطة الدخول الرئيسية لإعدادات NativePHP.
 * يُحدد شكل النافذة، القوائم، وسلوك التطبيق عند الفتح.
 *
 * يُستدعى تلقائيًا عند إقلاع التطبيق كبرنامج سطح مكتب.
 */
class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * يُنفَّذ عند إقلاع NativePHP (قبل فتح أي نافذة).
     * ضع هنا الإعدادات العامة للتطبيق.
     */
    public function boot(): void
    {
        // -------------------------------------------------------
        // إنشاء النافذة الرئيسية للتطبيق
        // -------------------------------------------------------
        Window::open()
            ->title('GAREH — نظام إدارة البيض')   // عنوان النافذة
            ->width(1280)                            // العرض (بكسل)
            ->height(820)                            // الارتفاع (بكسل)
            ->minWidth(900)                          // الحد الأدنى للعرض
            ->minHeight(600)                         // الحد الأدنى للارتفاع
            ->route('home')                          // الصفحة الأولى عند الفتح
            ->titleBarHidden(false)                  // إظهار شريط العنوان
            ->resizable(true)                        // السماح بتغيير الحجم
            ->rememberState(true);                   // تذكّر موضع وحجم النافذة
    }

    /**
     * إعدادات php.ini الخاصة بالتطبيق.
     * تُطبَّق فقط على بيئة NativePHP (لا تؤثر على خادم الويب).
     */
    public function phpIni(): array
    {
        return [
            'memory_limit'      => '512M',   // حد الذاكرة المسموح به
            'max_execution_time'=> '0',      // بلا حد زمني للتنفيذ
        ];
    }
}

