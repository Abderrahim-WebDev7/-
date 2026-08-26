<section class="panel" id="panel-sales">
    <div class="card" style="margin-bottom:22px;">
        <h3><span class="badge-dot"></span> تسجيل عملية بيع</h3>
        <form id="saleForm">
            <div class="form-grid">
                <div class="form-row"><label>التاريخ</label><input type="date" id="sDate" required></div>
                <div class="form-row"><label>لمن تم البيع (الزبون)</label><input type="text" id="sBuyer" placeholder="اسم الزبون" required></div>
            </div>
            <div class="form-grid">
                <div class="form-row">
                    <label>وحدة الكمية</label>
                    <select id="sQtyType" required>
                        <option value="plate">لوح (30 بيضة)</option>
                        <option value="carton12">كرتون (12 لوح)</option>
                    </select>
                </div>
                <div class="form-row"><label>العدد</label><input type="number" id="sQtyCount" placeholder="مثال: 5" min="1" required></div>
            </div>
            <div class="form-row"><label>سعر بيع اللوح الواحد (دج)</label><input type="number" id="sPrice" placeholder="مثال: 350" min="0" required></div>
            <div class="form-row">
                <label>الإجمالي المحسوب تلقائيًا</label>
                <div class="total-display" id="sTotalDisplay">0 دج</div>
            </div>
            <button type="submit" class="btn block">تسجيل عملية البيع</button>
        </form>
    </div>
    <div class="section-actions">
        <h3 style="margin:0; font-size:1.05rem; color:var(--cream);">سجل المبيعات</h3>
        <div class="total-pill">إجمالي البيع: <b id="salesTotal">0</b> دج</div>
    </div>
    <div class="card">
        <table>
            <thead><tr><th>التاريخ</th><th>الزبون</th><th>الكمية</th><th>سعر اللوح</th><th>الإجمالي</th><th>الحالة</th><th></th></tr></thead>
            <tbody id="salesBody"><tr class="empty-row"><td colspan="7">لا توجد مبيعات مسجّلة</td></tr></tbody>
        </table>
    </div>
</section>