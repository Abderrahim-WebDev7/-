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
                    <input type="number" id="cAmount" placeholder="مثال: 10000" min="0" step="1" required>
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
                        <th>تاريخ الإدخال</th>
                        <th>ملاحظات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="capitalsBody">
                    <tr class="empty-row"><td colspan="7">لا توجد رؤوس أموال مسجّلة</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>