<section class="panel" id="panel-capitals">
    <!-- فورم إضافة رأس مال -->
    <div class="card" style="margin-bottom:22px;">
        <h3><span class="badge-dot"></span> إضافة رأس مال جديد</h3>
        <form id="capitalForm">
            <div class="form-grid">
                <div class="form-row">
                    <label>اسم الشريك</label>
                    <input type="text" id="cPartnerName" placeholder="مثال: أحمد بن علي" required>
                </div>
                <div class="form-row">
                    <label>المبلغ (دج)</label>
                    <input type="number" id="cAmount" placeholder="مثال: 100000" min="0" step="1" required>
                </div>
            </div>
            
            <div class="form-row">
                <label>تاريخ الإدخال</label>
                <input type="date" id="cEntryDate" required>
            </div>
            
            <div class="form-row">
                <label>ملاحظات</label>
                <input type="text" id="cNotes" placeholder="ملاحظات إضافية">
            </div>
            
            <button type="submit" class="btn block">إضافة رأس المال</button>
        </form>
    </div>

    <!-- عرض رؤوس الأموال -->
    <div class="section-actions">
        <h3 style="margin:0; font-size:1.05rem; color:var(--cream);">سجل رأس المال</h3>
        <div class="total-pill">
            إجمالي رأس المال: <b id="totalCapital" style="color:var(--gold);">0</b> دج
            <span style="color:var(--text-dim);font-size:0.7rem;">(<span id="totalCapitalCentime">صفر سنتيم</span>)</span>
        </div>
    </div>
    
    <div class="card">
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الشريك</th>
                        <th>المبلغ</th>
                        <th>النسبة المئوية</th>
                        <th>الأرباح</th>
                        <th>تاريخ الإدخال</th>
                        <th>ملاحظات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="capitalsBody">
                    <tr class="empty-row"><td colspan="8">لا توجد رؤوس أموال مسجّلة</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- عرض أرباح الشركاء -->
    <div class="card" style="margin-top:20px; border-color:var(--gold);">
        <h3 style="color:var(--gold);">
            <span class="badge-dot" style="background:var(--gold);"></span> 
            💰 أرباح الشركاء
        </h3>
        <p style="font-size:0.76rem; color:var(--text-dim); margin-top:-8px; margin-bottom:14px;">
            يتم حساب الأرباح من تاريخ إدخال رأس المال إلى نفس اليوم من الشهر التالي
        </p>
        
        <!-- إجمالي الأرباح -->
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:15px; margin-bottom:15px;">

    <!-- إجمالي المبيعات -->
    <div class="mini-stat" style="border-color:var(--gold);">
        <span style="color:var(--text-dim);">📊 إجمالي المبيعات</span>
        <b id="capitalTotalSales" style="color:var(--gold);">0 دج</b>
    </div>

    <!-- إجمالي الأرباح -->
    <div class="mini-stat" style="border-color:var(--success);">
        <span style="color:var(--text-dim);">📈 إجمالي الأرباح</span>
        <b id="capitalTotalProfit" style="color:var(--success);">0 دج</b>
    </div>

    <!-- فترة الحساب -->
    <div class="mini-stat" style="border-color:var(--blue);">
        <span style="color:var(--text-dim);">📅 فترة الحساب</span>
        <b id="capitalPeriod" style="color:var(--blue); font-size:0.8rem;">—</b>
    </div>

</div>
        
<!-- عناصر مخفية لتخزين القيم من renderProfits -->
<span id="capitalTotalProfit" style="display:none;">0 دج</span>
<span id="capitalTotalSales" style="display:none;">0 دج</span>

        <!-- جدول أرباح الشركاء -->
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الشريك</th>
                        <th>نسبة الربح</th>
                        <th>المبلغ المستحق</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody id="partnerProfitsBody">
                    <tr class="empty-row"><td colspan="5">لا توجد أرباح للمشاركة</td></tr>
                </tbody>
            </table>
        </div>
</div><br><br>
<!-- ============================================ -->
<!--  سحب مبلغ من حصة شريك                       -->
<!-- ============================================ -->
<div class="card" style="margin-bottom:22px; border-color:var(--danger);">
    <h3 style="color:var(--danger);">
        <span class="badge-dot" style="background:var(--danger);"></span>
        💸 سحب مبلغ من حصة شريك
    </h3>
    <p style="font-size:0.76rem; color:var(--text-dim); margin-top:-8px; margin-bottom:14px;">
        عندما يسحب الشريك جزءاً من أمواله، يُخصم المبلغ مباشرة من حصته الإجمالية
    </p>

    <form id="partnerPaymentForm">
        <div class="form-grid">
            <div class="form-row">
                <label>اسم الشريك</label>
                <select id="pPartnerId" required style="width:100%; padding:10px 12px; background:var(--panel-2); border:1px solid var(--border); border-radius:9px; color:var(--text); font-size:0.9rem;">
                    <option value="">-- اختر الشريك --</option>
                    <!-- سيتم تعبئتها بالجافاسكريبت -->
                </select>
            </div>
            <div class="form-row">
                <label>المبلغ المسحوب (دج)</label>
                <input type="number" id="pAmount" placeholder="مثال: 100000" min="0" step="1" required>
            </div>
        </div>

        <div class="form-row">
            <label>تاريخ السحب</label>
            <input type="datetime-local" id="pPaymentDate" required>
        </div>

        <div class="form-row">
            <label>ملاحظات</label>
            <input type="text" id="pNotes" placeholder="ملاحظات إضافية">
        </div>

        <button type="submit" class="btn block" style="background:var(--danger-soft); color:#f0a49c; border:1px solid #4a2a26;">
            💸 تسجيل السحب
        </button>
    </form>

    <!-- معلومات الشريك المحدد -->
    <div id="partnerInfoBox" style="display:none; margin-top:15px; padding:15px; background:var(--panel-2); border-radius:9px; border:1px solid var(--border);">
    <p style="margin:5px 0;">الشريك: <strong id="infoPartnerName" style="color:var(--cream);">—</strong></p>
    <p style="margin:5px 0;">المبلغ المستحق (رأس المال + الأرباح): <strong id="infoOriginalAmount" style="color:var(--gold);">0 دج</strong></p>
    <p style="margin:5px 0;">إجمالي المسحوب: <strong id="infoTotalWithdrawn" style="color:var(--danger);">0 دج</strong></p>
    <p style="margin:5px 0;">المبلغ المتبقي: <strong id="infoRemainingAmount" style="color:var(--success);">0 دج</strong></p>
</div>
</div>

<!-- ============================================ -->
<!--  سجل السحوبات                                -->
<!-- ============================================ -->
<div class="card" style="margin-bottom:22px; border-color:var(--danger);">
    <h3 style="color:var(--danger);">
        <span class="badge-dot" style="background:var(--danger);"></span>
        📋 سجل السحوبات
    </h3>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>#</th>
                    <th>اسم الشريك</th>
                    <th>المبلغ</th>
                    <th>المبلغ بالكلمات</th>
                    <th>تاريخ السحب</th>
                    <th>ملاحظات</th>
                    <th>التواريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="partnerPaymentsBody">
                <tr class="empty-row"><td colspan="9">لا توجد سحوبات مسجّلة</td></tr>
            </tbody>
        </table>
    </div>
</div>
    </div>
</section>