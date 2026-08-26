<section class="panel" id="panel-profits">
    <div class="card" style="margin-bottom:20px;">
        <div class="month-bar">
            <label style="font-size:0.82rem; color:var(--cream-dim);">اختر السنة:</label>
            <select id="profitYear" style="max-width:150px; padding:8px 12px; background:var(--panel-2); border:1px solid var(--border); border-radius:9px; color:var(--text);">
                <!-- سيتم تعبئتها بالجافاسكريبت -->
            </select>
            <button class="btn sm" id="refreshProfitsBtn" style="background:var(--gold); color:#1b1608;">🔄 تحديث</button>
        </div>
        
        <!-- الإحصائيات الرئيسية -->
        <div class="grid cols-4" style="margin-bottom:15px;">
            <div class="mini-stat" style="border-color:var(--gold);">
                <span style="color:var(--text-dim);">💰 إجمالي المشتريات</span>
                <b id="pfTotalPurchases" style="color:var(--gold);">0 دج</b>
            </div>
            <div class="mini-stat" style="border-color:var(--success);">
                <span style="color:var(--text-dim);">📈 إجمالي المبيعات</span>
                <b id="pfTotalSales" style="color:var(--success);">0 دج</b>
            </div>
            <div class="mini-stat" style="border-color:var(--gold);">
                <span style="color:var(--text-dim);">💵 الربح الصافي</span>
                <b id="pfNetProfit" style="color:var(--gold);">0 دج</b>
            </div>
            <div class="mini-stat" style="border-color:var(--blue);">
                <span style="color:var(--text-dim);">📊 عدد المعاملات</span>
                <b id="pfTransactionsCount" style="color:var(--blue);">0</b>
            </div>
            <div class="mini-stat" style="border-color:var(--gold);">
    <span style="color:var(--text-dim);">📊 إجمالي الأرباح</span>
    <b id="pfTotalProfitWithLoss" style="color:var(--gold);">0 دج</b>
    <span style="color:var(--text-dim); font-size:0.6rem;" class="profit-note">(0 دج أجور العمال)</span>
</div>
        </div>
        
        <!-- التفاصيل -->
        <div class="grid cols-3" style="margin-bottom:15px;">
            <div class="mini-stat" style="border-color:var(--danger); background:var(--danger-soft);">
                <span style="color:var(--text-dim);">👤 المشترين غير المدفوعين</span>
                <b id="pfBuyersUnpaid" style="color:var(--danger);">0 دج</b>
            </div>
            <div class="mini-stat" style="border-color:var(--gold); background:var(--gold-soft);">
                <span style="color:var(--text-dim);">🏷️ الموردين غير المدفوعين</span>
                <b id="pfSuppliersUnpaid" style="color:var(--gold);">0 دج</b>
            </div>
            <div class="mini-stat" style="border-color:var(--blue); background:var(--blue-soft);">
                <span style="color:var(--text-dim);">👷 أجور العمال المدفوعة</span>
                <b id="pfWagesPaid" style="color:var(--blue);">0 دج</b>
            </div>
        </div>
        
        <div class="grid cols-2">
            <div class="mini-stat" style="border-color:var(--text-dim);">
                <span style="color:var(--text-dim);">📋 التكاليف الأخرى</span>
                <b id="pfOtherCosts" style="color:var(--cream-dim);">0 دج</b>
            </div>
            <div class="mini-stat" style="border-color:var(--gold);">
                <span style="color:var(--text-dim);">📊 إجمالي الأرباح</span>
                <b id="pfTotalProfitWithLoss" style="color:var(--gold);">0 دج</b>
            </div>
        </div>
        
        <div style="font-size:0.72rem; color:var(--text-dim); margin-top:12px; padding:10px; background:var(--panel-2); border-radius:9px; border:1px solid var(--border);">
            <strong>📌 ملاحظة:</strong> 
            الربح الصافي = إجمالي المبيعات − إجمالي المشتريات − أجور العمال − التكاليف الأخرى
        </div>
    </div>

    <!-- الرسم البياني -->
    <div class="card" style="margin-top:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; flex-wrap:wrap; gap:10px;">
            <h3 style="margin:0; color:var(--cream);">
                <span class="badge-dot"></span> مقارنة الأرباح والمبيعات والمشتريات
            </h3>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button class="btn sm chart-type-btn" id="chartTypeBar" style="background:var(--gold); color:#1b1608;">📊 أشرطة</button>
                <button class="btn sm chart-type-btn" id="chartTypeLine" style="background:var(--panel-2); color:var(--text-dim);">📈 خطوط</button>
            </div>
        </div>
        
        <div style="position:relative; height:400px; width:100%;">
            <canvas id="profitChartCanvas" style="width:100% !important; height:100% !important;"></canvas>
        </div> 
        <div style="font-size:0.7rem; color:var(--text-dim); margin-top:10px; text-align:center;">
            * يعرض الرسم البياني الأشهر (جانفي - ديسمبر) للسنة المحددة
        </div>
    </div>
</section>