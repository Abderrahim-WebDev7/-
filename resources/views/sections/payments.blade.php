<section class="panel" id="panel-payments">
    <div class="card">
        <h3><span class="badge-dot"></span> المدفوعات غير المسددة</h3>
        <p class="card-kicker">عرض جميع الأشخاص الذين لم تُسدَّد معاملاتهم بعد</p>
        <div class="grid cols-2">
            <div class="mini-stat" style="background:var(--danger-soft); border-color:rgba(210,102,92,.4);">
                <span>المشترين غير المدفوعين</span>
                <b id="totalBuyersUnpaid" style="color:var(--danger);">0</b>
                <span>شخص</span>
            </div>
            <div class="mini-stat" style="border-color:rgba(224,179,90,.4);">
                <span>الموردين غير المدفوعين</span>
                <b id="totalSuppliersUnpaid" style="color:var(--gold);">0</b>
                <span>شخص</span>
            </div>
        </div>
    </div>

    <div class="card">
        <h3>المشترين الذين لم يدفعوا</h3>
        <p class="card-kicker">الأشخاص الذين اشتروا البيض ولم يسددوا المبلغ للمتجر</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المشتري</th>
                        <th>رقم الهاتف</th>
                        <th>عدد المعاملات</th>
                        <th>إجمالي المبلغ</th>
                        <th>المبلغ المتبقي</th>
                        <th>المبلغ بالسانتيم</th>
                        <th>آخر معاملة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="buyersUnpaidBody">
                    <tr class="empty-row"><td colspan="9">لا يوجد مشترين غير مدفوعين</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3>الموردين الذين لم يدفع لهم</h3>
        <p class="card-kicker">الأشخاص الذين اشترى منهم المتجر البيض ولم يُسدَّد لهم المبلغ</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المورد</th>
                        <th>رقم الهاتف</th>
                        <th>عدد المعاملات</th>
                        <th>إجمالي المبلغ</th>
                        <th>المبلغ المتبقي</th>
                        <th>المبلغ بالسانتيم</th>
                        <th>آخر معاملة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="suppliersUnpaidBody">
                    <tr class="empty-row"><td colspan="9">لا يوجد موردين غير مدفوعين</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<div id="partialPaymentModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:100000; align-items:center; justify-content:center;">
    <div class="card" style="max-width:450px; width:90%; padding:28px;">
        <h3 style="margin-top:0;">تسديد جزئي</h3>
        <div style="margin:8px 0 16px;">
            <p class="card-kicker" style="margin:8px 0;" id="paymentPersonName">الشخص: </p>
            <p class="card-kicker" style="margin:8px 0;">
                المبلغ الإجمالي:
                <strong id="paymentTotalAmount" style="color:var(--cream);">0 دج</strong>
                <br>
                <small id="paymentTotalAmountWords">(صفر دينار)</small>
            </p>
            <p class="card-kicker" style="margin:8px 0;">
                المبلغ المتبقي:
                <strong id="paymentRemainingAmount" style="color:var(--gold);">0 دج</strong>
                <br>
                <small id="paymentRemainingAmountWords">(صفر دينار)</small>
            </p>
        </div>
        <div class="form-row">
            <label>المبلغ المراد تسديده (دج)</label>
            <input type="number" id="paymentAmountInput" min="0" step="100" placeholder="أدخل المبلغ"
                   oninput="updatePaymentAmountWords(this.value)">
            <small id="paymentAmountWords" class="card-kicker" style="margin:6px 0 0;">صفر دينار</small>
        </div>
        <div style="display:flex; gap:10px; margin-top:8px;">
            <button type="button" class="btn" id="confirmPartialPaymentBtn" style="flex:1;">تأكيد</button>
            <button type="button" class="btn ghost" id="closePaymentModalBtn" style="flex:1;">إلغاء</button>
        </div>
    </div>
</div>

