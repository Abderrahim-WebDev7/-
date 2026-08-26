<section class="panel" id="panel-people">
    <!-- فورم إضافة شخص جديد -->
    <div class="card" style="margin-bottom:22px;">
        <h3><span class="badge-dot"></span> إضافة شخص جديد</h3>
        <form id="personForm">
            <div class="form-grid">
                <div class="form-row">
                    <label>الاسم الكامل</label>
                    <input type="text" id="pFullName" placeholder="مثال: أحمد بن محمد" required>
                </div>
                <div class="form-row">
                    <label>رقم الهاتف</label>
                    <input type="tel" id="pPhone" placeholder="0555 00 00 00">
                </div>
            </div>
            
            <div class="form-row">
                <label>مكان التواجد / العنوان</label>
                <input type="text" id="pAddress" placeholder="مثال: الجزائر العاصمة">
            </div>
            
            <div class="form-grid">
                <div class="form-row">
                    <label>نوع الشخص</label>
                    <select id="pType" required>
                        <option value="supplier">🏷️ بائع (مورد)</option>
                        <option value="buyer">🛒 مشتري (زبون)</option>
                        <option value="both">🔄 بائع ومشتري</option>
                    </select>
                </div>
                <div class="form-row">
                    <label>ملاحظات</label>
                    <input type="text" id="pNotes" placeholder="ملاحظات إضافية">
                </div>
            </div>
            
            <button type="submit" class="btn block">إضافة الشخص</button>
        </form>
    </div>

    <!-- عرض الأشخاص -->
    <div class="section-actions">
        <h3 style="margin:0; font-size:1.05rem; color:var(--cream);">قائمة الأشخاص</h3>
        <div class="total-pill">إجمالي الأشخاص: <b id="peopleCount">0</b></div>
    </div>
    
    <div class="card">
        <div style="display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap;">
            <button class="btn sm people-filter-btn" id="showAllPeople" style="background:var(--gold); color:#1b1608;">📋 الكل</button>
            <button class="btn sm people-filter-btn" id="showSuppliers" style="background:var(--panel-2); color:var(--text-dim);">🏷️ الموردين</button>
            <button class="btn sm people-filter-btn" id="showBuyers" style="background:var(--panel-2); color:var(--text-dim);">🛒 المشترين</button>
        </div>
        
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم الكامل</th>
                        <th>رقم الهاتف</th>
                        <th>العنوان</th>
                        <th>النوع</th>
                        <th>ملاحظات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="peopleBody">
                    <tr class="empty-row"><td colspan="7">لا يوجد أشخاص مسجّلين</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>