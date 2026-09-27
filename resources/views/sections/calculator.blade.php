<section class="panel" id="panel-calculator">
    <div class="card" style="max-width: 450px; margin: 0 auto;">
        <h3 style="text-align:center; margin-bottom:20px;">
            <span class="badge-dot"></span> 🧮 آلة حاسبة
        </h3>
        
        <!-- شاشة العرض -->
        <div style="background:var(--panel-2); border:1px solid var(--border); border-radius:10px; padding:15px 20px; margin-bottom:15px; min-height:90px; display:flex; flex-direction:column; justify-content:flex-end; direction:ltr; overflow-x:auto;">
            <!-- المعادلة -->
            <div id="calcExpression" style="color:var(--text-dim); font-size:1rem; font-family:monospace; text-align:left; min-height:25px; direction:ltr; word-break:break-all;">
                &nbsp;
            </div>
            <!-- النتيجة -->
            <div id="calcDisplay" style="color:var(--cream); font-size:2.2rem; font-family:monospace; text-align:left; direction:ltr; font-weight:bold; min-height:45px; display:flex; align-items:center;">
                0
            </div>
        </div>
        
        <!-- أزرار الآلة الحاسبة -->
        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:8px;">
            <!-- الصف الأول -->
            <button class="calc-btn" data-value="C" style="background:var(--danger-soft); color:#f0a49c; border:1px solid #4a2a26; padding:18px 12px; border-radius:10px; font-size:1.1rem; font-weight:600; cursor:pointer; transition:all 0.2s;">C</button>
            <button class="calc-btn" data-value="⌫" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.1rem; font-weight:600; cursor:pointer; transition:all 0.2s;">⌫</button>
            <button class="calc-btn" data-value="%" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.1rem; font-weight:600; cursor:pointer; transition:all 0.2s;">%</button>
            <button class="calc-btn" data-value="/" style="background:var(--gold-soft); color:var(--cream); border:1px solid var(--gold-soft); padding:18px 12px; border-radius:10px; font-size:1.3rem; font-weight:700; cursor:pointer; transition:all 0.2s;">÷</button>
            
            <!-- الصف الثاني -->
            <button class="calc-btn" data-value="7" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s;">7</button>
            <button class="calc-btn" data-value="8" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s;">8</button>
            <button class="calc-btn" data-value="9" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s;">9</button>
            <button class="calc-btn" data-value="*" style="background:var(--gold-soft); color:var(--cream); border:1px solid var(--gold-soft); padding:18px 12px; border-radius:10px; font-size:1.3rem; font-weight:700; cursor:pointer; transition:all 0.2s;">×</button>
            
            <!-- الصف الثالث -->
            <button class="calc-btn" data-value="4" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s;">4</button>
            <button class="calc-btn" data-value="5" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s;">5</button>
            <button class="calc-btn" data-value="6" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s;">6</button>
            <button class="calc-btn" data-value="-" style="background:var(--gold-soft); color:var(--cream); border:1px solid var(--gold-soft); padding:18px 12px; border-radius:10px; font-size:1.3rem; font-weight:700; cursor:pointer; transition:all 0.2s;">−</button>
            
            <!-- الصف الرابع -->
            <button class="calc-btn" data-value="1" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s;">1</button>
            <button class="calc-btn" data-value="2" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s;">2</button>
            <button class="calc-btn" data-value="3" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s;">3</button>
            <button class="calc-btn" data-value="+" style="background:var(--gold-soft); color:var(--cream); border:1px solid var(--gold-soft); padding:18px 12px; border-radius:10px; font-size:1.3rem; font-weight:700; cursor:pointer; transition:all 0.2s;">+</button>
            
            <!-- الصف الخامس -->
            <button class="calc-btn" data-value="00" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.1rem; font-weight:600; cursor:pointer; transition:all 0.2s; grid-column:span 1;">00</button>
            <button class="calc-btn" data-value="0" style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s; grid-column:span 1;">0</button>
            <button class="calc-btn" data-value="." style="background:var(--panel-2); color:var(--cream); border:1px solid var(--border); padding:18px 12px; border-radius:10px; font-size:1.2rem; font-weight:600; cursor:pointer; transition:all 0.2s;">.</button>
            <button class="calc-btn" data-value="=" style="background:var(--gold); color:#1b1608; border:1px solid var(--gold); padding:18px 12px; border-radius:10px; font-size:1.5rem; font-weight:700; cursor:pointer; transition:all 0.2s; grid-column:span 1;">=</button>
        </div>
        
        <!-- زر مسح الكل -->
        <div style="margin-top:12px;">
            <button id="calcClearAll" style="width:100%; background:var(--danger-soft); color:#f0a49c; border:1px solid #4a2a26; padding:14px; border-radius:10px; font-size:1rem; font-weight:600; cursor:pointer; transition:all 0.2s;">
                🗑️ مسح الكل
            </button>
        </div>
        
        <!-- معلومات -->
        <div style="margin-top:15px; padding:10px; background:var(--panel-2); border-radius:8px; border:1px solid var(--border); text-align:center; font-size:0.75rem; color:var(--text-dim);">
            💡 يمكنك استخدام لوحة المفاتيح أيضاً
        </div>
    </div>
</section>