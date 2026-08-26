<section class="panel" id="panel-transactions">
    <!-- فورم موحد للشراء والبيع -->
    <div class="card" style="margin-bottom:22px;">
        <h3><span class="badge-dot"></span> تسجيل معاملة شراء وبيع</h3>
        <form id="transactionForm" style="border:2px solid transparent; padding:10px; border-radius:10px; transition:border-color 0.3s;">
            
            <!-- التاريخ -->
            <div class="form-row">
                <label>التاريخ</label>
                <input type="date" id="tDate" required>
            </div>
            
            <!-- اسم البائع (مع إكمال تلقائي) -->
             <!--
            <div class="form-row" style="position:relative;">
                <label>اسم البائع (المورد)</label>
                <input type="text" id="tSupplierName" placeholder="ابحث عن بائع..." required autocomplete="off">
                <div id="supplierSuggestions" style="background:var(--panel-2); border:1px solid var(--border); border-radius:9px; max-height:150px; overflow-y:auto; display:none; position:absolute; z-index:1000; width:100%;"></div>
            </div>  -->

            <!-- اسم البائع -->
<div class="form-row" style="position:relative;">
    <label>اسم البائع (المورد)</label>
    <input type="text" id="tSupplierName" placeholder="ابحث عن بائع..." required autocomplete="off">
    <div id="supplierSuggestions" style="display:none; position:absolute; z-index:9999; width:100%;"></div>
</div>
            
            <!-- نوع البيض -->
            <div class="form-row">
                <label>نوع البيض</label>
                <select id="tEggType">
                    <option value="">اختر نوع البيض</option>
                    <option value="بيض أبيض">🥚 بيض أبيض</option>
                    <option value="بيض أحمر">🥚 بيض أحمر</option>
                    <option value="بيض عرب">🥚 بيض عرب</option>
                    <option value="دوبل جون">🥚 دوبل جون</option>
                </select>
            </div>
            
            <!-- الكمية والعدد -->
            <div class="form-grid">
                <div class="form-row">
                    <label>وحدة الكمية</label>
                    <select id="tQtyType" required>
                        <option value="plate">لوح (30 بيضة)</option>
                        <option value="carton12">كرتون (12 لوح)</option>
                    </select>
                </div>
                <div class="form-row">
                    <label>العدد</label>
                    <input type="number" id="tQtyCount" placeholder="مثال: 5" min="1" required>
                </div>
            </div>
            
            <!-- سعر الشراء -->
            <div class="form-row">
                <label>سعر شراء اللوح (دج)</label>
                <input type="number" id="tPurchasePrice" placeholder="مثال: 300" min="0" required>
            </div>
            
            <!-- اسم المشتري (مع إكمال تلقائي) -->
             <!--
            <div class="form-row" style="position:relative;">
                <label>اسم المشتري (الزبون)</label>
                <input type="text" id="tBuyerName" placeholder="ابحث عن مشتري..." required autocomplete="off">
                <div id="buyerSuggestions" style="background:var(--panel-2); border:1px solid var(--border); border-radius:9px; max-height:150px; overflow-y:auto; display:none; position:absolute; z-index:1000; width:100%;"></div>
            </div>  -->

            <!-- اسم المشتري -->
<div class="form-row" style="position:relative;">
    <label>اسم المشتري (الزبون)</label>
    <input type="text" id="tBuyerName" placeholder="ابحث عن مشتري..." required autocomplete="off">
    <div id="buyerSuggestions" style="display:none; position:absolute; z-index:9999; width:100%;"></div>
</div>
            
            <!-- سعر البيع -->
            <div class="form-row">
                <label>سعر بيع اللوح (دج)</label>
                <input type="number" id="tSalePrice" placeholder="مثال: 350" min="0" required>
            </div>
            
            <!-- الإجماليات المحسوبة تلقائياً -->
            <div class="form-grid">
                <div class="form-row">
                    <label>إجمالي الشراء</label>
                    <div class="total-display" id="tTotalPurchase">0 دج</div>
                </div>
                <div class="form-row">
                    <label>إجمالي البيع</label>
                    <div class="total-display" id="tTotalSale">0 دج</div>
                </div>
            </div>
            
            <!-- الربح المحسوب تلقائياً -->
            <div class="form-row">
                <label>الربح المتوقع</label>
                <div class="total-display" id="tProfit" style="border-color: var(--success); color: var(--success);">0 دج</div>
            </div>
            
            <!-- حالة الدفع -->
<!-- حالة الدفع -->
<div class="form-grid">
    <div class="form-row">
        <label>حالة دفع البائع</label>
        <div style="display:flex; gap:10px; align-items:center; margin-top:5px;">
            <button type="button" class="btn sm danger" id="supplierPaidBtn" style="min-width:120px;">
                ❌ لم يتم الدفع
            </button>
            <input type="hidden" id="supplierPaid" value="0">
            <span style="color:var(--text-dim); font-size:0.7rem;">(اضغط لتغيير الحالة)</span>
        </div>
    </div>
    <div class="form-row">
        <label>حالة دفع المشتري</label>
        <div style="display:flex; gap:10px; align-items:center; margin-top:5px;">
            <button type="button" class="btn sm danger" id="buyerPaidBtn" style="min-width:120px;">
                ❌ لم يتم الدفع
            </button>
            <input type="hidden" id="buyerPaid" value="0">
            <span style="color:var(--text-dim); font-size:0.7rem;">(اضغط لتغيير الحالة)</span>
        </div>
    </div>
</div>
            
            <!-- ملاحظات -->
            <div class="form-row">
                <label>ملاحظات</label>
                <input type="text" id="tNotes" placeholder="ملاحظات إضافية">
            </div>
            
            <button type="submit" class="btn block">تسجيل المعاملة</button>
        </form>
    </div>

    <!-- عرض المعاملات -->
    <div class="section-actions">
        <div class="total-pill" style="font-size:0.75rem; line-height:1.8;">
            إجمالي المشتريات: <b id="totalPurchases">0</b> دج 
            <span style="color:var(--gold);font-size:0.7rem;">(<span id="totalPurchasesCentime">صفر سنتيم</span>)</span> | 
            إجمالي المبيعات: <b id="totalSales">0</b> دج 
            <span style="color:var(--gold);font-size:0.7rem;">(<span id="totalSalesCentime">صفر سنتيم</span>)</span> | 
            إجمالي الأرباح: <b id="totalProfit" style="color:var(--gold);">0</b> دج 
            <span style="font-size:0.7rem;">(<span id="totalProfitCentime" style="color:var(--gold);">صفر سنتيم</span>)</span>
        </div>
    </div>
    
    <div class="card">
        <div style="display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap;">
            <button class="btn sm filter-btn" id="showAll" style="background:var(--gold); color:#1b1608;">📋 الكل</button>
            <button class="btn sm filter-btn" id="showExited" style="background:var(--success-soft); color:#a8cbad;">✅ تم الخروج</button>
            <button class="btn sm filter-btn" id="showPending" style="background:var(--panel-2); color:var(--text-dim);">⏳ قيد الانتظار</button>
        </div>
        
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المورد</th>
                        <th>المشتري</th>
                        <th>النوع</th>
                        <th>الكمية</th>
                        <th>سعر الشراء</th>
                        <th>سعر البيع</th>
                        <th>الربح</th>
                        <th>الحالة</th>
                        <th>حالة الدفع</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="transactionsBody">
                    <tr class="empty-row"><td colspan="11">لا توجد معاملات مسجّلة</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>