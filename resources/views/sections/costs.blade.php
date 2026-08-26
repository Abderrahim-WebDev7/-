<section class="panel" id="panel-costs">
    <div class="card" style="margin-bottom:22px;">
        <h3><span class="badge-dot"></span> إضافة تكلفة جديدة</h3>
        <form id="costForm">
            <div class="form-grid">
                <div class="form-row"><label>التاريخ</label><input type="date" id="cDate" required></div>
                <div class="form-row"><label>السبب</label><input type="text" id="cReason" placeholder="مثال: شراء أعلاف" required></div>
            </div>
            <div class="form-row"><label>المبلغ (دج)</label><input type="number" id="cAmount" placeholder="مثال: 5000" min="0" required></div>
            <button type="submit" class="btn block">تسجيل التكلفة</button>
        </form>
    </div>
    <div class="section-actions">
        <h3 style="margin:0; font-size:1.05rem; color:var(--cream);">سجل التكاليف</h3>
        <div class="total-pill">إجمالي التكاليف: <b id="costsTotal">0</b> دج</div>
    </div>
    <div class="card" style="margin-bottom:22px;">
        <table>
            <thead><tr><th>التاريخ</th><th>السبب</th><th>المبلغ</th><th></th></tr></thead>
            <tbody id="costsBody"><tr class="empty-row"><td colspan="4">لا توجد تكاليف مسجّلة</td></tr></tbody>
        </table>
    </div>
    <div class="card">
        <h3><span class="badge-dot"></span> التكاليف يوميًا</h3>
        <table>
            <thead><tr><th>التاريخ</th><th>عدد التكاليف</th><th>الإجمالي</th></tr></thead>
            <tbody id="costsDailyBody"><tr class="empty-row"><td colspan="3">لا توجد بيانات بعد</td></tr></tbody>
        </table>
    </div>
</section>