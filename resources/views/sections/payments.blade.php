<section class="panel" id="panel-payments">
    <div class="card" style="margin-bottom:22px;">
        <h3><span class="badge-dot"></span> المدفوعات غير المسددة</h3>
        <p style="font-size:0.76rem; color:var(--text-dim); margin-top:-8px; margin-bottom:14px;">
            عرض جميع الأشخاص الذين لم يسددوا دفعاتهم
        </p>
        
        <!-- إحصائيات سريعة -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:15px;">
            <div class="mini-stat" style="border-color:var(--danger); background:var(--danger-soft);">
                <span style="color:var(--text-dim);">👤 المشترين غير المدفوعين</span>
                <b id="totalBuyersUnpaid" style="color:var(--danger); font-size:1.3rem;">0</b>
                <span style="color:var(--text-dim); font-size:0.7rem;">شخص</span>
            </div>
            <div class="mini-stat" style="border-color:var(--gold); background:var(--gold-soft);">
                <span style="color:var(--text-dim);">🏷️ الموردين غير المدفوعين</span>
                <b id="totalSuppliersUnpaid" style="color:var(--gold); font-size:1.3rem;">0</b>
                <span style="color:var(--text-dim); font-size:0.7rem;">شخص</span>
            </div>
        </div>
    </div>
    
    <!-- المشترين غير المدفوعين -->
    <div class="card" style="margin-bottom:18px; border-color:var(--danger);">
        <h3 style="color:var(--danger);">👤 المشترين الذين لم يدفعوا</h3>
        <p style="font-size:0.76rem; color:var(--text-dim); margin-top:-8px; margin-bottom:14px;">
            الأشخاص الذين اشتروا البيض ولم يسددوا المبلغ للمتجر
        </p>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المشتري</th>
                        <th>رقم الهاتف</th>
                        <th>عدد المعاملات</th>
                        <th>إجمالي المبلغ</th>
                        <th>المبلغ المتبقي</th>
                        <th>آخر معاملة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="buyersUnpaidBody">
                    <tr class="empty-row"><td colspan="8">لا يوجد مشترين غير مدفوعين</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- الموردين غير المدفوعين -->
    <div class="card" style="border-color:var(--gold);">
        <h3 style="color:var(--gold);">🏷️ الموردين الذين لم يدفع لهم</h3>
        <p style="font-size:0.76rem; color:var(--text-dim); margin-top:-8px; margin-bottom:14px;">
            الأشخاص الذين اشترى منهم المتجر البيض ولم يسدد لهم المبلغ
        </p>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المورد</th>
                        <th>رقم الهاتف</th>
                        <th>عدد المعاملات</th>
                        <th>إجمالي المبلغ</th>
                        <th>المبلغ المتبقي</th>
                        <th>آخر معاملة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="suppliersUnpaidBody">
                    <tr class="empty-row"><td colspan="8">لا يوجد موردين غير مدفوعين</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- نافذة تسديد جزئي -->
<div id="partialPaymentModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:var(--panel); border:1px solid var(--border); border-radius:var(--radius); padding:30px; max-width:400px; width:90%;">
        <h3 style="margin-top:0; color:var(--cream);">💰 تسديد جزئي</h3>
        <div style="margin:15px 0;">
            <p style="color:var(--text-dim);" id="paymentPersonName">الشخص: </p>
            <p style="color:var(--text-dim);" id="paymentTotalAmount">المبلغ الإجمالي: </p>
            <p style="color:var(--text-dim);" id="paymentRemainingAmount">المبلغ المتبقي: </p>
        </div>
        <div class="form-row">
            <label>المبلغ المراد تسديده (دج)</label>
            <input type="number" id="paymentAmountInput" min="0" step="100" placeholder="أدخل المبلغ" style="width:100%;">
        </div>
        <div style="display:flex; gap:10px; margin-top:15px;">
            <button class="btn" id="confirmPartialPaymentBtn">💾 تأكيد</button>
            <button class="btn ghost" id="closePaymentModalBtn">إلغاء</button>
        </div>
    </div>
</div>