<section class="panel" id="panel-orders">
    <div class="card" style="margin-bottom:22px;">
        <h3><span class="badge-dot"></span> إضافة طلبية جديدة</h3>
        <form id="orderForm">
            <div class="form-grid">
                <div class="form-row"><label>التاريخ</label><input type="date" id="oDate" required></div>
                <div class="form-row"><label>اسم الزبون</label><input type="text" id="oClient" placeholder="اسم الزبون" required></div>
            </div>
            <div class="form-row">
                <label>نوع البيض</label>
                <select id="oProduct" required>
                    <option value="">اختر نوع البيض</option>
                    <option value="بيض أبيض">بيض أبيض</option>
                    <option value="بيض أحمر">بيض أحمر</option>
                    <option value="بيض عرب">بيض عرب</option>
                    <option value="دوبل جون">دوبل جون</option>
                </select>
            </div>
            <div class="form-grid">
                <div class="form-row">
                    <label>وحدة الكمية</label>
                    <select id="oQtyType" required>
                        <option value="plate">لوح (30 بيضة)</option>
                        <option value="carton12">كرتون (12 لوح)</option>
                    </select>
                </div>
                <div class="form-row"><label>العدد</label><input type="number" id="oQtyCount" placeholder="مثال: 5" min="1" required></div>
            </div>
            <div class="form-row"><label>سعر اللوح الواحد (دج)</label><input type="number" id="oPrice" placeholder="مثال: 300" min="0" required></div>
            <div class="form-row">
                <label>الإجمالي المحسوب تلقائيًا</label>
                <div class="total-display" id="oTotalDisplay">0 دج</div>
            </div>
            <div class="form-row"><label>ملاحظات (اختياري)</label><input type="text" id="oNotes" placeholder="ملاحظات إضافية"></div>
            <button type="submit" class="btn block">تسجيل الطلبية</button>
        </form>
    </div>
    <div class="section-actions">
        <h3 style="margin:0; font-size:1.05rem; color:var(--cream);">سجل الطلبيات</h3>
        <div class="total-pill">عدد الطلبيات: <b id="ordersCount">0</b></div>
    </div>
    <div class="card" style="margin-bottom:22px;">
        <table>
            <thead><tr><th>التاريخ</th><th>الزبون</th><th>النوع</th><th>الكمية</th><th>سعر اللوح</th><th>الإجمالي</th><th></th></tr></thead>
            <tbody id="ordersBody"><tr class="empty-row"><td colspan="7">لا توجد طلبيات مسجّلة</td></tr></tbody>
        </table>
    </div>

    <div class="card">
        <h3><span class="badge-dot"></span> الكمية الباقية يوميًا (بيضة)</h3>
        <p style="font-size:0.76rem; color:var(--text-dim); margin-top:-8px; margin-bottom:14px;">الكمية الواردة من الطلبيات ناقص الكمية المباعة في نفس اليوم.</p>
        <table>
            <thead><tr><th>التاريخ</th><th>الوارد (طلبيات)</th><th>المباع</th><th>الباقي</th></tr></thead>
            <tbody id="stockBody"><tr class="empty-row"><td colspan="4">لا توجد بيانات بعد</td></tr></tbody>
        </table>
    </div>
</section>