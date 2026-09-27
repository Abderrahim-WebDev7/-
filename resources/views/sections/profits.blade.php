<section class="panel" id="panel-profits">
    <div class="card">
        <h3><span class="badge-dot"></span> فلترة الفترة</h3>
        <div class="form-grid g3">
            <div class="form-row">
                <label>اختر السنة</label>
                <select id="profitYear"></select>
            </div>
            <div class="form-row">
                <label>اختر الشهر</label>
                <select id="profitMonth">
                    <option value="all">كل الأشهر</option>
                    <option value="01">جانفي</option>
                    <option value="02">فيفري</option>
                    <option value="03">مارس</option>
                    <option value="04">أفريل</option>
                    <option value="05">ماي</option>
                    <option value="06">جوان</option>
                    <option value="07">جويلية</option>
                    <option value="08">أوت</option>
                    <option value="09">سبتمبر</option>
                    <option value="10">أكتوبر</option>
                    <option value="11">نوفمبر</option>
                    <option value="12">ديسمبر</option>
                </select>
            </div>
            <div class="form-row" style="justify-content:flex-end;">
                <label>&nbsp;</label>
                <div style="display:flex; gap:8px;">
                    <button class="btn" id="refreshProfitsBtn" type="button">تحديث</button>
                    <button class="btn ghost" id="resetProfitsBtn" type="button">إعادة تعيين</button>
                </div>
            </div>
        </div>

        <div class="grid cols-4">
            <div class="mini-stat">
                <span>إجمالي المشتريات</span>
                <b id="pfTotalPurchases" style="color:var(--gold);">0 دج</b>
                <span id="pfTotalPurchasesCentime">صفر دينار</span>
            </div>
            <div class="mini-stat">
                <span>إجمالي المبيعات</span>
                <b id="pfTotalSales" style="color:var(--success);">0 دج</b>
                <span id="pfTotalSalesCentime">صفر دينار</span>
            </div>
            <div class="mini-stat">
                <span>الربح من المعاملات</span>
                <b id="pfNetProfit" style="color:var(--gold);">0 دج</b>
                <span id="pfNetProfitCentime">صفر دينار</span>
            </div>
            <div class="mini-stat">
                <span>عدد المعاملات</span>
                <b id="pfTransactionsCount" style="color:var(--blue);">0</b>
            </div>
        </div>
    </div>

    <div class="grid cols-3">
        <div class="mini-stat" style="border-color:rgba(210,102,92,.45); background:var(--danger-soft);">
            <span>المشترين غير المدفوعين</span>
            <b id="pfBuyersUnpaid" style="color:var(--danger);">0 دج</b>
            <span id="pfBuyersUnpaidCentime">صفر دينار</span>
        </div>
        <div class="mini-stat" style="border-color:rgba(224,179,90,.4);">
            <span>الموردين غير المدفوعين</span>
            <b id="pfSuppliersUnpaid" style="color:var(--gold);">0 دج</b>
            <span id="pfSuppliersUnpaidCentime">صفر دينار</span>
        </div>
        <div class="mini-stat" style="background:var(--blue-soft);">
            <span>أجور العمال المدفوعة</span>
            <b id="pfWagesPaid" style="color:var(--blue);">0 دج</b>
            <span id="pfWagesPaidCentime">صفر دينار</span>
        </div>
    </div>

    <div class="grid cols-2">
        <div class="mini-stat">
            <span>التكاليف الأخرى</span>
            <b id="pfOtherCosts">0 دج</b>
            <span id="pfOtherCostsCentime">صفر دينار</span>
        </div>
        <div class="mini-stat" style="border-color:rgba(224,179,90,.45);">
            <span>إجمالي الأرباح</span>
            <b id="pfTotalProfitWithLoss" style="color:var(--gold);">0 دج</b>
            <span id="pfTotalProfitWithLossCentime">صفر دينار</span>
            <span class="profit-note">بعد خصم التكاليف وأجور العمال</span>
        </div>
    </div>

    <div class="card">
        <p class="card-kicker" style="margin:0;">الربح الصافي = إجمالي المبيعات − إجمالي المشتريات − أجور العمال − التكاليف الأخرى</p>
    </div>

    <div class="card">
        <div class="section-actions">
            <h3><span class="badge-dot"></span> مقارنة الأرباح والمبيعات والمشتريات</h3>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button class="btn sm chart-type-btn" id="chartTypeBar" type="button">أشرطة</button>
                <button class="btn sm ghost chart-type-btn" id="chartTypeLine" type="button">خطوط</button>
            </div>
        </div>
        <div class="chart-container" style="margin-top:16px; height:400px;">
            <canvas id="profitChartCanvas"></canvas>
        </div>
        <p class="card-kicker" style="margin:12px 0 0; text-align:center;">يعرض الرسم البياني الأشهر (جانفي - ديسمبر) للسنة المحددة بالدينار الجزائري</p>
    </div>
</section>
