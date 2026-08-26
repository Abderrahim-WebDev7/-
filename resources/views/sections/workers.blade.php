<section class="panel" id="panel-workers">
    <div class="card" style="margin-bottom:22px;">
        <h3><span class="badge-dot"></span> إضافة عامل جديد</h3>
        <form id="workerForm">
            <div class="form-grid">
                <div class="form-row"><label>الاسم واللقب</label><input type="text" id="wName" placeholder="مثال: محمد بن علي" required></div>
                <div class="form-row"><label>رقم الهاتف</label><input type="tel" id="wPhone" placeholder="0555 00 00 00" required></div>
            </div>
            <div class="form-row"><label>أجرة اليوم (دج)</label><input type="number" id="wWage" placeholder="1500" min="0" required></div>
            <button type="submit" class="btn block">إضافة العامل</button>
        </form>
    </div>

    <div class="section-actions">
        <h3 style="margin:0; font-size:1.05rem; color:var(--cream);">قائمة العمّال</h3>
        <div class="total-pill">إجمالي العمّال: <b id="workersCount">0</b></div>
    </div>
    <div id="workersList"></div>
</section>