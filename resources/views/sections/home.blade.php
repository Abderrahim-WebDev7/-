<section class="panel active" id="panel-home">
    <div class="grid cols-6">
        <div class="card stat-card">
            <div class="stat-label">عدد العمّال</div>
            <div class="stat-num" id="statWorkers">0</div>
        </div>
        <div class="card stat-card">
            <div class="stat-label">طلبيات اليوم</div>
            <div class="stat-num" id="statOrdersToday">0</div>
        </div>
        <div class="card stat-card">
            <div class="stat-label">مبيعات اليوم</div>
            <div class="stat-num" id="statSalesToday">0 دج</div>
            <small id="statSalesTodayCentime">صفر سنتيم</small>
        </div>
        <div class="card stat-card">
            <div class="stat-label">تكاليف اليوم</div>
            <div class="stat-num" id="statCostsToday">0 دج</div>
            <small id="statCostsTodayCentime">صفر سنتيم</small>
        </div>
        <div class="card stat-card">
            <div class="stat-label">الربح الصافي اليوم</div>
            <div class="stat-num" id="statProfitToday">0 دج</div>
            <small id="statProfitTodayCentime">صفر سنتيم</small>
        </div>
        <div class="card stat-card">
            <div class="stat-label">أرباح هذا الشهر</div>
            <div class="stat-num" id="statProfitMonth">0 دج</div>
            <small id="statProfitMonthCentime">صفر سنتيم</small>
        </div>
    </div>

    <div class="card">
        <h3><span class="badge-dot"></span> السلع التي لم يتم خروجها</h3>
        <p class="card-kicker">جميع الدفعات المعلّقة عبر الأيام السابقة والحالية</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المورد</th>
                        <th>المشتري</th>
                        <th>النوع</th>
                        <th>الكمية</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody id="homeUndeliveredBody">
                    <tr class="empty-row"><td colspan="6">جميع السلع تم خروجها</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid cols-1">
        <div class="card">
            <h3><span class="badge-dot"></span> آخر الطلبيات (اليوم)</h3>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>التاريخ</th><th>المورد</th><th>الكمية</th><th>المبلغ</th></tr></thead>
                    <tbody id="homeOrdersBody">
                        <tr class="empty-row"><td colspan="4">لا توجد طلبيات اليوم</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <h3><span class="badge-dot"></span> تكاليف اليوم</h3>
        <div class="table-wrap">
            <table>
                <thead><tr><th>التاريخ</th><th>السبب</th><th>المبلغ</th></tr></thead>
                <tbody id="homeCostsBody">
                    <tr class="empty-row"><td colspan="3">لا توجد تكاليف اليوم</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
