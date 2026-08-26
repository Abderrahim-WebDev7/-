<section class="panel active" id="panel-home">
    <div class="grid cols-6" style="margin-bottom:18px;">
        <div class="card stat-card">
            <div class="stat-label">عدد العمّال</div>
            <div class="stat-num" id="statWorkers">0</div>
        </div>
        <div class="card stat-card">
            <div class="stat-label">طلبيات اليوم</div>
            <div class="stat-num" id="statOrdersToday">0</div>
        </div>
        <div class="card stat-card">
            <div class="stat-label">مبيعات اليوم <small>(دج)</small></div>
            <div class="stat-num" id="statSalesToday">0</div>
        </div>
        <div class="card stat-card">
            <div class="stat-label">تكاليف اليوم <small>(دج)</small></div>
            <div class="stat-num" id="statCostsToday">0</div>
        </div>
        <div class="card stat-card">
            <div class="stat-label">أرباح اليوم <small>(دج)</small></div>
            <div class="stat-num" id="statProfitToday">0</div>
        </div>
        <div class="card stat-card">
            <div class="stat-label">أرباح الشهر <small>(دج)</small></div>
            <div class="stat-num" id="statProfitMonth">0</div>
        </div>
    </div>
    <div class="grid cols-2" style="margin-bottom:18px;">
        <div class="card">
            <h3><span class="badge-dot"></span> آخر الطلبيات</h3>
            <table>
                <thead><tr><th>التاريخ</th><th>الزبون</th><th>الكمية</th></tr></thead>
                <tbody id="homeOrdersBody"><tr class="empty-row"><td colspan="3">لا توجد طلبيات بعد</td></tr></tbody>
            </table>
        </div>
        <div class="card">
            <h3><span class="badge-dot"></span> آخر عمليات البيع</h3>
            <table>
                <thead><tr><th>التاريخ</th><th>الزبون</th><th>الحالة</th></tr></thead>
                <tbody id="homeSalesBody"><tr class="empty-row"><td colspan="3">لا توجد مبيعات بعد</td></tr></tbody>
            </table>
        </div>
    </div>
    <div class="card" style="margin-bottom:18px;">
        <h3><span class="badge-dot"></span> الكمية المتبقية من البيض غير المباع</h3>
        <p style="font-size:0.76rem; color:var(--text-dim); margin-top:-8px; margin-bottom:14px;">دفعات الطلبيات التي ما زال منها كمية لم تُباع بعد، مرتبة حسب تاريخ الشراء.</p>
        <table>
            <thead><tr><th>تاريخ الشراء</th><th>الزبون / المصدر</th><th>النوع</th><th>الكمية المتبقية (بيضة)</th></tr></thead>
            <tbody id="homeStockBody"><tr class="empty-row"><td colspan="4">لا توجد كمية متبقية</td></tr></tbody>
        </table>
    </div>
    <div class="card">
        <h3><span class="badge-dot"></span> آخر التكاليف</h3>
        <table>
            <thead><tr><th>التاريخ</th><th>السبب</th><th>المبلغ</th></tr></thead>
            <tbody id="homeCostsBody"><tr class="empty-row"><td colspan="3">لا توجد تكاليف بعد</td></tr></tbody>
        </table>
    </div>
</section>