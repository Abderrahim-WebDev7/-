import './bootstrap';
import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';
(function() {
    "use strict";

    /* ---------------- storage helpers (real browser localStorage) ---------------- */
    const KEYS = {
        workers: 'gare7_workers', 
        attendance: 'gare7_attendance', 
        payments: 'gare7_payments',
        orders: 'gare7_orders', 
        sales: 'gare7_sales', 
        costs: 'gare7_costs'
    };

    function load(key) {
        try {
            const raw = localStorage.getItem(key);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    function save(key, val) {
        try {
            localStorage.setItem(key, JSON.stringify(val));
        } catch (e) {
            toast('تعذّر حفظ البيانات في هذا المتصفح');
        }
    }

    function uid() {
        return Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
    }

    function pad(n) {
        return String(n).padStart(2, '0');
    }

    function localISODate(d) {
        return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
    }

    function todayStr() {
        return localISODate(new Date());
    }

    function fmtDate(iso) {
        if (!iso) return '—';
        const [y, m, d] = iso.split('-');
        return `${d}/${m}/${y}`;
    }

    function fmtMoney(n) {
        return Number(n || 0).toLocaleString('en-US');
    }

    function monthKey(iso) {
        return iso ? iso.slice(0, 7) : '';
    }

    function monthKeyOf(y, m) {
        return `${y}-${pad(m+1)}`;
    }

    const QTY_TYPES = {
        plate: { unit: 'لوح', plural: 'ألواح', eggsEach: 30, extra: '', plates: 1 },
        carton12: { unit: 'كرتون', plural: 'كراتين', eggsEach: 360, extra: ' (12 لوح/كرتون)', plates: 12 }
    };

    function isFiniteNum(v) {
        return typeof v === 'number' && isFinite(v);
    }

    function eggsOf(item) {
        const type = QTY_TYPES[item.qtyType] ? item.qtyType : 'plate';
        const count = Number(item.qtyCount);
        return (isFiniteNum(count) ? count : 0) * QTY_TYPES[type].eggsEach;
    }

    function platesOf(qtyType, count) {
        const type = QTY_TYPES[qtyType] ? qtyType : 'plate';
        const c = Number(count);
        return (isFiniteNum(c) ? c : 0) * QTY_TYPES[type].plates;
    }

    function orderTotal(o) {
        if (isFiniteNum(o.price)) return platesOf(o.qtyType, o.qtyCount) * o.price;
        if (isFiniteNum(o.total)) return o.total;
        return 0;
    }

    function saleTotal(s) {
        if (isFiniteNum(s.price)) return platesOf(s.qtyType, s.qtyCount) * s.price;
        if (isFiniteNum(s.total)) return s.total;
        if (isFiniteNum(s.salePrice)) return s.salePrice;
        return 0;
    }

    function qtyText(type, count) {
        const m = QTY_TYPES[type] || QTY_TYPES.plate;
        const totalEggs = count * m.eggsEach;
        const unitLabel = count > 1 ? m.plural : m.unit;
        return `${count} ${unitLabel}${m.extra} — ${fmtMoney(totalEggs)} بيضة`;
    }

    let workers = load(KEYS.workers);
    let attendance = load(KEYS.attendance);
    let payments = load(KEYS.payments);
    let orders = load(KEYS.orders);
    let sales = load(KEYS.sales);
    let costs = load(KEYS.costs);

    /* ---------------- toast ---------------- */
    let toastTimer;

    function toast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => t.classList.remove('show'), 2200);
    }

    /* ---------------- navigation ---------------- */
    const titles = {
        home: ['الرئيسية', 'نظرة سريعة على نشاط الشركة اليوم'],
        workers: ['العمّال والأجور', 'إضافة العمّال ومتابعة الحضور والدفع'],
        costs: ['التكاليف', 'تسجيل ومتابعة تكاليف كل يوم'],
        orders: ['الطلبيات', 'تسجيل ومتابعة طلبيات اليوم'],
        sales: ['المبيعات', 'تسجيل كميات وأسعار البيع'],
        profits: ['الأرباح الشهرية', 'حساب الأرباح من المبيعات وأجور العمّال']
    };

    document.getElementById('nav').addEventListener('click', (e) => {
        const item = e.target.closest('.nav-item');
        if (!item) return;
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        item.classList.add('active');
        const target = item.dataset.target;
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
        document.getElementById('panel-' + target).classList.add('active');
        document.getElementById('pageTitle').textContent = titles[target][0];
        document.getElementById('pageDesc').textContent = titles[target][1];
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sideCalc').classList.toggle('show', target === 'orders' || target === 'sales');
        if (target === 'home') renderHome();
        if (target === 'profits') renderProfits();
        if (target === 'orders') renderStock();
        if (target === 'costs') renderCosts();
    });

    document.getElementById('hamburger').addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('open');
    });

    const chip = document.getElementById('todayChip');
    chip.textContent = 'اليوم: ' + fmtDate(todayStr());

    /* ================= WORKERS ================= */
    const workerForm = document.getElementById('workerForm');
    workerForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const w = {
            id: uid(),
            name: document.getElementById('wName').value.trim(),
            phone: document.getElementById('wPhone').value.trim(),
            wage: Number(document.getElementById('wWage').value || 0),
            createdAt: todayStr()
        };
        workers.push(w);
        save(KEYS.workers, workers);
        workerForm.reset();
        toast('تمت إضافة العامل بنجاح');
        renderWorkers();
    });

    function markAttendance(workerId, status) {
        const today = todayStr();
        let rec = attendance.find(a => a.workerId === workerId && a.date === today);
        if (rec) {
            rec.status = status;
        } else {
            attendance.push({ id: uid(), workerId, date: today, status, paid: false });
        }
        save(KEYS.attendance, attendance);
        const labels = { present: 'تم تسجيل الحضور لليوم', absent: 'تم تسجيل الغياب لليوم', rest: 'تم تسجيل يوم راحة مدفوع' };
        toast(labels[status]);
        renderWorkers();
    }

    function confirmPayment(workerId) {
        const worker = workers.find(w => w.id === workerId);
        if (!worker) return;
        const unpaid = attendance.filter(a => a.workerId === workerId && !a.paid);
        if (unpaid.length === 0) {
            toast('لا توجد أيام جديدة للدفع');
            return;
        }
        const presentDays = unpaid.filter(a => a.status === 'present').length;
        const restDays = unpaid.filter(a => a.status === 'rest').length;
        const absentDays = unpaid.filter(a => a.status === 'absent').length;
        const amount = (presentDays + restDays) * worker.wage;
        if (!confirm(`تأكيد دفع أجرة ${worker.name}؟\nأيام الحضور: ${presentDays}\nأيام الراحة المدفوعة: ${restDays}\nأيام الغياب: ${absentDays}\nالمبلغ المستحق: ${fmtMoney(amount)} دج`)) return;
        unpaid.forEach(a => a.paid = true);
        save(KEYS.attendance, attendance);
        payments.push({ id: uid(), workerId, date: todayStr(), presentDays, restDays, absentDays, amount });
        save(KEYS.payments, payments);
        toast('تم تأكيد الدفع بنجاح');
        renderWorkers();
    }

    function deleteWorker(id) {
        if (!confirm('حذف هذا العامل نهائيًا وكل سجلاته؟')) return;
        workers = workers.filter(w => w.id !== id);
        attendance = attendance.filter(a => a.workerId !== id);
        payments = payments.filter(p => p.workerId !== id);
        save(KEYS.workers, workers);
        save(KEYS.attendance, attendance);
        save(KEYS.payments, payments);
        renderWorkers();
    }

    function initials(w) {
        const parts = w.name.trim().split(/\s+/);
        const a = parts[0] ? parts[0][0] : '';
        const b = parts[1] ? parts[1][0] : '';
        return (a + b).toUpperCase();
    }

    function renderWorkers() {
        document.getElementById('workersCount').textContent = workers.length;
        const list = document.getElementById('workersList');
        if (workers.length === 0) {
            list.innerHTML = '<div class="card" style="text-align:center; color:var(--text-dim); padding:34px;">لا يوجد عمّال بعد — أضف أول عامل من النموذج أعلاه</div>';
            return;
        }
        list.innerHTML = workers.map(w => {
            const today = todayStr();
            const todayRec = attendance.find(a => a.workerId === w.id && a.date === today);
            const unpaid = attendance.filter(a => a.workerId === w.id && !a.paid).sort((a, b) => a.date.localeCompare(b.date));
            const presentDays = unpaid.filter(a => a.status === 'present').length;
            const restDays = unpaid.filter(a => a.status === 'rest').length;
            const absentDays = unpaid.filter(a => a.status === 'absent').length;
            const due = (presentDays + restDays) * w.wage;
            const statusLabel = { present: 'حاضر', absent: 'غائب', rest: 'راحة مدفوعة' };
            const rowsHtml = unpaid.length ? unpaid.map(a => `
                        <tr>
                            <td>${fmtDate(a.date)}</td>
                            <td><span class="badge ${a.status}">${statusLabel[a.status]}</span></td>
                            <td><span class="badge pending">بانتظار الدفع</span></td>
                        </tr>`).join('') : '<tr class="empty-row"><td colspan="3">لا توجد أيام غير مدفوعة</td></tr>';

            return `
                    <div class="worker-card">
                        <div class="worker-head">
                            <div class="worker-info">
                                <div class="avatar">${initials(w)}</div>
                                <div>
                                    <div class="worker-name">${w.name}</div>
                                    <div class="worker-phone">${w.phone} · أجرة اليوم: ${fmtMoney(w.wage)} دج</div>
                                </div>
                            </div>
                            <div class="worker-actions">
                                <button class="btn sm success" data-act="present" data-id="${w.id}" ${todayRec && todayRec.status === 'present' ? 'disabled' : ''}>حاضر اليوم</button>
                                <button class="btn sm danger" data-act="absent" data-id="${w.id}" ${todayRec && todayRec.status === 'absent' ? 'disabled' : ''}>غائب اليوم</button>
                                <button class="btn sm info" data-act="rest" data-id="${w.id}" ${todayRec && todayRec.status === 'rest' ? 'disabled' : ''}>راحة</button>
                                <button class="btn sm ghost" data-act="toggle" data-id="${w.id}">التفاصيل</button>
                                <button class="btn sm ghost" data-act="delete" data-id="${w.id}" style="color:#e08a82;">حذف</button>
                            </div>
                        </div>
                        <div class="worker-body" id="body-${w.id}">
                            <div class="summary-strip">
                                <div class="mini-stat">أيام حضور غير مدفوعة<b>${presentDays}</b></div>
                                <div class="mini-stat">أيام راحة مدفوعة<b>${restDays}</b></div>
                                <div class="mini-stat">أيام غياب غير مدفوعة<b>${absentDays}</b></div>
                                <div class="mini-stat">المبلغ المستحق<b>${fmtMoney(due)} دج</b></div>
                            </div>
                            <table style="margin-bottom:14px;">
                                <thead><tr><th>التاريخ</th><th>الحالة</th><th>حالة الدفع</th></tr></thead>
                                <tbody>${rowsHtml}</tbody>
                            </table>
                            <button class="btn" data-act="pay" data-id="${w.id}" ${unpaid.length === 0 ? 'disabled' : ''}>تأكيد الدفع (${fmtMoney(due)} دج)</button>
                        </div>
                    </div>`;
        }).join('');
    }

    document.getElementById('workersList').addEventListener('click', (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;
        const id = btn.dataset.id,
            act = btn.dataset.act;
        if (act === 'present') markAttendance(id, 'present');
        else if (act === 'absent') markAttendance(id, 'absent');
        else if (act === 'rest') markAttendance(id, 'rest');
        else if (act === 'pay') confirmPayment(id);
        else if (act === 'delete') deleteWorker(id);
        else if (act === 'toggle') {
            const body = document.getElementById('body-' + id);
            body.classList.toggle('open');
        }
    });

    /* ================= COSTS ================= */
    document.getElementById('cDate').value = todayStr();
    document.getElementById('costForm').addEventListener('submit', (e) => {
        e.preventDefault();
        costs.push({
            id: uid(),
            date: document.getElementById('cDate').value,
            reason: document.getElementById('cReason').value.trim(),
            amount: Number(document.getElementById('cAmount').value || 0)
        });
        save(KEYS.costs, costs);
        const form = e.target;
        form.reset();
        document.getElementById('cDate').value = todayStr();
        toast('تم تسجيل التكلفة');
        renderCosts();
    });

    function deleteCost(id) {
        costs = costs.filter(c => c.id !== id);
        save(KEYS.costs, costs);
        renderCosts();
    }

    function renderCosts() {
        const total = costs.reduce((s, c) => s + Number(c.amount || 0), 0);
        document.getElementById('costsTotal').textContent = fmtMoney(total);

        const body = document.getElementById('costsBody');
        if (costs.length === 0) {
            body.innerHTML = '<tr class="empty-row"><td colspan="4">لا توجد تكاليف مسجّلة</td></tr>';
        } else {
            const sorted = [...costs].sort((a, b) => b.date.localeCompare(a.date));
            body.innerHTML = sorted.map(c => `
                        <tr>
                            <td>${fmtDate(c.date)}</td><td>${c.reason}</td><td>${fmtMoney(c.amount)} دج</td>
                            <td><button class="btn sm danger" data-id="${c.id}">حذف</button></td>
                        </tr>`).join('');
        }

        const dailyBody = document.getElementById('costsDailyBody');
        const dates = Array.from(new Set(costs.map(c => c.date))).sort((a, b) => b.localeCompare(a));
        if (dates.length === 0) {
            dailyBody.innerHTML = '<tr class="empty-row"><td colspan="3">لا توجد بيانات بعد</td></tr>';
        } else {
            dailyBody.innerHTML = dates.map(date => {
                const dayCosts = costs.filter(c => c.date === date);
                const dayTotal = dayCosts.reduce((s, c) => s + Number(c.amount || 0), 0);
                return `<tr><td>${fmtDate(date)}</td><td>${dayCosts.length}</td><td>${fmtMoney(dayTotal)} دج</td></tr>`;
            }).join('');
        }
    }
    document.getElementById('costsBody').addEventListener('click', (e) => {
        const b = e.target.closest('button');
        if (b) deleteCost(b.dataset.id);
    });

    /* ================= ORDERS ================= */
    document.getElementById('oDate').value = todayStr();

    function updateOrderTotal() {
        const qtyType = document.getElementById('oQtyType').value;
        const qtyCount = Number(document.getElementById('oQtyCount').value || 0);
        const price = Number(document.getElementById('oPrice').value || 0);
        const total = platesOf(qtyType, qtyCount) * price;
        document.getElementById('oTotalDisplay').textContent = fmtMoney(total) + ' دج';
    }
    ['oQtyType', 'oQtyCount', 'oPrice'].forEach(id => {
        document.getElementById(id).addEventListener('input', updateOrderTotal);
        document.getElementById(id).addEventListener('change', updateOrderTotal);
    });

    document.getElementById('orderForm').addEventListener('submit', (e) => {
        e.preventDefault();
        const qtyType = document.getElementById('oQtyType').value;
        const qtyCount = Number(document.getElementById('oQtyCount').value || 0);
        const price = Number(document.getElementById('oPrice').value || 0);
        orders.push({
            id: uid(),
            date: document.getElementById('oDate').value,
            client: document.getElementById('oClient').value.trim(),
            product: document.getElementById('oProduct').value,
            qtyType,
            qtyCount,
            price,
            total: platesOf(qtyType, qtyCount) * price,
            notes: document.getElementById('oNotes').value.trim()
        });
        save(KEYS.orders, orders);
        const form = e.target;
        form.reset();
        document.getElementById('oDate').value = todayStr();
        document.getElementById('oTotalDisplay').textContent = '0 دج';
        toast('تم تسجيل الطلبية');
        renderOrders();
        renderStock();
    });

    function deleteOrder(id) {
        orders = orders.filter(o => o.id !== id);
        save(KEYS.orders, orders);
        renderOrders();
        renderStock();
    }

    function renderOrders() {
        document.getElementById('ordersCount').textContent = orders.length;
        const body = document.getElementById('ordersBody');
        if (orders.length === 0) {
            body.innerHTML = '<tr class="empty-row"><td colspan="7">لا توجد طلبيات مسجّلة</td></tr>';
            return;
        }
        const sorted = [...orders].sort((a, b) => b.date.localeCompare(a.date));
        body.innerHTML = sorted.map(o => `
                    <tr>
                        <td>${fmtDate(o.date)}</td><td>${o.client}</td><td>${o.product}</td><td>${qtyText(o.qtyType, o.qtyCount)}</td>
                        <td>${fmtMoney(o.price)} دج</td><td>${fmtMoney(orderTotal(o))} دج</td>
                        <td><button class="btn sm danger" data-id="${o.id}">حذف</button></td>
                    </tr>`).join('');
    }
    document.getElementById('ordersBody').addEventListener('click', (e) => {
        const b = e.target.closest('button');
        if (b) deleteOrder(b.dataset.id);
    });

    /* ---------- daily remaining stock (orders received vs sold) ---------- */
    function renderStock() {
        const body = document.getElementById('stockBody');
        const dates = Array.from(new Set([...orders.map(o => o.date), ...sales.map(s => s.date)])).filter(Boolean);
        if (dates.length === 0) {
            body.innerHTML = '<tr class="empty-row"><td colspan="4">لا توجد بيانات بعد</td></tr>';
            return;
        }
        dates.sort((a, b) => b.localeCompare(a));
        body.innerHTML = dates.map(date => {
            const orderedEggs = orders.filter(o => o.date === date).reduce((s, o) => s + eggsOf(o), 0);
            const soldEggs = sales.filter(s => s.date === date).reduce((s, x) => s + eggsOf(x), 0);
            const remaining = orderedEggs - soldEggs;
            const cls = remaining < 0 ? 'low' : 'ok';
            return `<tr>
                        <td>${fmtDate(date)}</td>
                        <td>${fmtMoney(orderedEggs)}</td>
                        <td>${fmtMoney(soldEggs)}</td>
                        <td class="stock-remaining ${cls}">${fmtMoney(remaining)}</td>
                    </tr>`;
        }).join('');
    }

    /* ---------- stock batches (FIFO) for home page ---------- */
    function computeStockBatches() {
        const batches = [...orders].sort((a, b) => a.date.localeCompare(b.date)).map(o => ({
            date: o.date,
            client: o.client,
            product: o.product,
            remaining: eggsOf(o)
        }));
        let toConsume = sales.reduce((s, x) => s + eggsOf(x), 0);
        for (const b of batches) {
            if (toConsume <= 0) break;
            const use = Math.min(b.remaining, toConsume);
            b.remaining -= use;
            toConsume -= use;
        }
        return batches.filter(b => b.remaining > 0);
    }

    /* ================= SALES ================= */
    document.getElementById('sDate').value = todayStr();

    function updateSaleTotal() {
        const qtyType = document.getElementById('sQtyType').value;
        const qtyCount = Number(document.getElementById('sQtyCount').value || 0);
        const price = Number(document.getElementById('sPrice').value || 0);
        const total = platesOf(qtyType, qtyCount) * price;
        document.getElementById('sTotalDisplay').textContent = fmtMoney(total) + ' دج';
    }
    ['sQtyType', 'sQtyCount', 'sPrice'].forEach(id => {
        document.getElementById(id).addEventListener('input', updateSaleTotal);
        document.getElementById(id).addEventListener('change', updateSaleTotal);
    });

    document.getElementById('saleForm').addEventListener('submit', (e) => {
        e.preventDefault();
        const qtyType = document.getElementById('sQtyType').value;
        const qtyCount = Number(document.getElementById('sQtyCount').value || 0);
        const price = Number(document.getElementById('sPrice').value || 0);
        sales.push({
            id: uid(),
            date: document.getElementById('sDate').value,
            buyer: document.getElementById('sBuyer').value.trim(),
            qtyType,
            qtyCount,
            price,
            total: platesOf(qtyType, qtyCount) * price,
            exited: false
        });
        save(KEYS.sales, sales);
        const form = e.target;
        form.reset();
        document.getElementById('sDate').value = todayStr();
        document.getElementById('sTotalDisplay').textContent = '0 دج';
        toast('تم تسجيل عملية البيع');
        renderSales();
        renderStock();
    });

    function deleteSale(id) {
        sales = sales.filter(s => s.id !== id);
        save(KEYS.sales, sales);
        renderSales();
        renderStock();
    }

    function exitSale(id) {
        const s = sales.find(x => x.id === id);
        if (!s) return;
        s.exited = true;
        save(KEYS.sales, sales);
        toast('تم تأكيد خروج عملية البيع');
        renderSales();
    }

    function renderSales() {
        const body = document.getElementById('salesBody');
        const total = sales.reduce((s, x) => s + saleTotal(x), 0);
        document.getElementById('salesTotal').textContent = fmtMoney(total);
        if (sales.length === 0) {
            body.innerHTML = '<tr class="empty-row"><td colspan="7">لا توجد مبيعات مسجّلة</td></tr>';
            return;
        }
        const sorted = [...sales].sort((a, b) => b.date.localeCompare(a.date));
        body.innerHTML = sorted.map(s => `
                    <tr>
                        <td>${fmtDate(s.date)}</td><td>${s.buyer}</td><td>${qtyText(s.qtyType, s.qtyCount)}</td>
                        <td>${fmtMoney(s.price)} دج</td><td>${fmtMoney(saleTotal(s))} دج</td>
                        <td>${s.exited ? '<span class="badge exited">تم الخروج</span>' : '<span class="badge pending">قيد الانتظار</span>'}</td>
                        <td style="display:flex; gap:6px;">
                            ${s.exited ? '' : `<button class="btn sm success" data-act="exit" data-id="${s.id}">تأكيد الخروج</button>`}
                            <button class="btn sm danger" data-act="del" data-id="${s.id}">حذف</button>
                        </td>
                    </tr>`).join('');
    }
    document.getElementById('salesBody').addEventListener('click', (e) => {
        const b = e.target.closest('button');
        if (!b) return;
        if (b.dataset.act === 'del') deleteSale(b.dataset.id);
        else if (b.dataset.act === 'exit') exitSale(b.dataset.id);
    });

    /* ================= PROFITS ================= */
    const profitMonthInput = document.getElementById('profitMonth');
    profitMonthInput.value = todayStr().slice(0, 7);
    profitMonthInput.addEventListener('change', renderProfits);

    function monthTotals(mKey) {
        const monthOrders = orders.filter(o => monthKey(o.date) === mKey);
        const monthSales = sales.filter(s => monthKey(s.date) === mKey);
        const cost = monthOrders.reduce((s, o) => s + orderTotal(o), 0);
        const revenue = monthSales.reduce((s, x) => s + saleTotal(x), 0);
        const wages = payments.filter(p => monthKey(p.date) === mKey).reduce((s, p) => s + p.amount, 0);
        const otherCosts = costs.filter(c => monthKey(c.date) === mKey).reduce((s, c) => s + Number(c.amount || 0), 0);
        return { revenue, cost, wages, otherCosts, profit: revenue - cost - wages - otherCosts };
    }

    function dayTotals(dateStr) {
        const dayOrders = orders.filter(o => o.date === dateStr);
        const daySales = sales.filter(s => s.date === dateStr);
        const cost = dayOrders.reduce((s, o) => s + orderTotal(o), 0);
        const revenue = daySales.reduce((s, x) => s + saleTotal(x), 0);
        const wages = payments.filter(p => p.date === dateStr).reduce((s, p) => s + p.amount, 0);
        const otherCosts = costs.filter(c => c.date === dateStr).reduce((s, c) => s + Number(c.amount || 0), 0);
        return { revenue, cost, wages, otherCosts, profit: revenue - cost - wages - otherCosts };
    }

    function monthLabel(mKey) {
        const [y, m] = mKey.split('-');
        const names = ['جانفي', 'فيفري', 'مارس', 'أفريل', 'ماي', 'جوان', 'جويلية', 'أوت', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        return `${names[Number(m) - 1]} ${y}`;
    }

    function renderProfits() {
        const mKey = profitMonthInput.value || todayStr().slice(0, 7);
        const t = monthTotals(mKey);
        document.getElementById('pfPurchases').textContent = fmtMoney(t.cost) + ' دج';
        document.getElementById('pfSales').textContent = fmtMoney(t.revenue) + ' دج';
        document.getElementById('pfWages').textContent = fmtMoney(t.wages) + ' دج';
        document.getElementById('pfCosts').textContent = fmtMoney(t.otherCosts) + ' دج';
        document.getElementById('pfProfit').textContent = fmtMoney(t.profit) + ' دج';
        document.getElementById('pfProfit').style.color = t.profit >= 0 ? 'var(--success)' : 'var(--danger)';

        const [cy, cm] = mKey.split('-').map(Number);
        const baseDate = new Date(cy, cm - 1, 1);
        const months = [];
        for (let i = 11; i >= 0; i--) {
            const d = new Date(baseDate.getFullYear(), baseDate.getMonth() - i, 1);
            const key = monthKeyOf(d.getFullYear(), d.getMonth());
            months.push({ key, label: monthLabel(key), ...monthTotals(key) });
        }

        document.getElementById('profitsBody').innerHTML = [...months].reverse().map(m => `
                    <tr>
                        <td>${m.label}</td>
                        <td>${fmtMoney(m.cost)} دج</td>
                        <td>${fmtMoney(m.revenue)} دج</td>
                        <td>${fmtMoney(m.wages)} دج</td>
                        <td>${fmtMoney(m.otherCosts)} دج</td>
                        <td style="color:${m.profit >= 0 ? 'var(--success)' : 'var(--danger)'}; font-weight:600;">${fmtMoney(m.profit)} دج</td>
                    </tr>`).join('');

        const maxAbs = Math.max(...months.map(m => Math.abs(m.profit)), 1);
        const chart = document.getElementById('profitChart');
        const labels = document.getElementById('profitChartLabels');
        chart.innerHTML = months.map(m => {
            const h = Math.max((Math.abs(m.profit) / maxAbs) * 150, m.profit === 0 ? 2 : 4);
            const cls = m.profit >= 0 ? 'pos' : 'neg';
            return `<div class="chart-col">
                        <div class="chart-val">${fmtMoney(m.profit)}</div>
                        <div class="chart-bar ${cls}" style="height:${h}px;"></div>
                    </div>`;
        }).join('');
        labels.innerHTML = months.map(m => `<div class="chart-label">${m.label.split(' ')[0]}<br>${m.label.split(' ')[1]}</div>`).join('');
    }

    /* ================= HOME ================= */
    function renderHome() {
        document.getElementById('statWorkers').textContent = workers.length;
        const today = todayStr();
        document.getElementById('statOrdersToday').textContent = orders.filter(o => o.date === today).length;
        const salesToday = sales.filter(s => s.date === today).reduce((s, x) => s + saleTotal(x), 0);
        document.getElementById('statSalesToday').textContent = fmtMoney(salesToday);

        const costsToday = costs.filter(c => c.date === today).reduce((s, c) => s + Number(c.amount || 0), 0);
        document.getElementById('statCostsToday').textContent = fmtMoney(costsToday);

        const dToday = dayTotals(today);
        document.getElementById('statProfitToday').textContent = fmtMoney(dToday.profit);

        const mKey = today.slice(0, 7);
        const t = monthTotals(mKey);
        document.getElementById('statProfitMonth').textContent = fmtMoney(t.profit);

        const oBody = document.getElementById('homeOrdersBody');
        const lastOrders = [...orders].sort((a, b) => b.date.localeCompare(a.date)).slice(0, 5);
        oBody.innerHTML = lastOrders.length ? lastOrders.map(o => `<tr><td>${fmtDate(o.date)}</td><td>${o.client}</td><td>${qtyText(o.qtyType, o.qtyCount)}</td></tr>`).join('') :
            '<tr class="empty-row"><td colspan="3">لا توجد طلبيات بعد</td></tr>';

        const sBody = document.getElementById('homeSalesBody');
        const lastSales = [...sales].sort((a, b) => b.date.localeCompare(a.date)).slice(0, 5);
        sBody.innerHTML = lastSales.length ? lastSales.map(s => `<tr><td>${fmtDate(s.date)}</td><td>${s.buyer}</td><td>${s.exited ? '<span class="badge exited">تم الخروج</span>' : '<span class="badge pending">قيد الانتظار</span>'}</td></tr>`).join('') :
            '<tr class="empty-row"><td colspan="3">لا توجد مبيعات بعد</td></tr>';

        const stockBody = document.getElementById('homeStockBody');
        const batches = computeStockBatches();
        stockBody.innerHTML = batches.length ? batches.map(b => `
                    <tr><td>${fmtDate(b.date)}</td><td>${b.client}</td><td>${b.product || '—'}</td><td>${fmtMoney(b.remaining)}</td></tr>
                `).join('') : '<tr class="empty-row"><td colspan="4">لا توجد كمية متبقية</td></tr>';

        const costsBodyHome = document.getElementById('homeCostsBody');
        const lastCosts = [...costs].sort((a, b) => b.date.localeCompare(a.date)).slice(0, 5);
        costsBodyHome.innerHTML = lastCosts.length ? lastCosts.map(c => `<tr><td>${fmtDate(c.date)}</td><td>${c.reason}</td><td>${fmtMoney(c.amount)} دج</td></tr>`).join('') :
            '<tr class="empty-row"><td colspan="3">لا توجد تكاليف بعد</td></tr>';
    }

    /* ================= CALCULATOR ================= */
    (function initCalc() {
        const display = document.getElementById('calcDisplay');
        let expr = '';

        function updateDisplay() {
            display.textContent = expr === '' ? '0' : expr;
        }

        document.getElementById('calcGrid').addEventListener('click', (e) => {
            const btn = e.target.closest('button');
            if (!btn) return;
            const a = btn.dataset.a;
            if (a === 'clear') {
                expr = '';
            } else if (a === 'back') {
                expr = expr.slice(0, -1);
            } else if (a === 'num') {
                expr += btn.textContent.trim();
            } else if (a === 'dot') {
                const parts = expr.split(/[+\-*/]/);
                const last = parts[parts.length - 1];
                if (!last.includes('.')) expr += (last === '' ? '0.' : '.');
            } else if (a === 'op') {
                if (expr === '') return;
                const lastChar = expr[expr.length - 1];
                const operators = ['+', '-', '*', '/'];
                if (operators.includes(lastChar)) expr = expr.slice(0, -1) + btn.dataset.op;
                else expr += btn.dataset.op;
            } else if (a === 'eq') {
                if (expr === '') return;
                try {
                    const cleanExpr = expr.replace(/[^0-9+\-*/.]/g, '');
                    if (!cleanExpr) { return; }
                    const result = Function('"use strict"; return (' + cleanExpr + ')')();
                    expr = (Number.isFinite(result)) ? String(Math.round(result * 1000000) / 1000000) : 'خطأ';
                } catch (err) {
                    expr = 'خطأ';
                }
            }
            updateDisplay();
        });
        updateDisplay();
    })();

    /* ---------------- init ---------------- */
    renderWorkers();
    renderCosts();
    renderOrders();
    renderSales();
    renderStock();
    renderProfits();
    renderHome();
})();