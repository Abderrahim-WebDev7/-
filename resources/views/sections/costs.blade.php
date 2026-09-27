<section class="panel" id="panel-costs">
    <div class="card">
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
        <h3>سجل التكاليف</h3>
        <div class="total-pill">إجمالي التكاليف: <b id="costsTotal">0</b> دج</div>
        <h3 id="costsTotalCentime"></h3>
    </div>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>التاريخ</th><th>السبب</th><th>المبلغ</th><th>المبلغ بالسانتيم</th><th></th></tr></thead>
                <tbody id="costsBody"><tr class="empty-row"><td colspan="5">لا توجد تكاليف مسجّلة</td></tr></tbody>
            </table>
        </div>
    </div>
</section>
