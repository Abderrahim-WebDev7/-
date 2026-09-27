{{-- ============================================ --}}
{{--  🧮 ساكشن الآلة الحاسبة --}}
{{-- ============================================ --}}
<section class="panel" id="panel-calculator">
    <div class="card" style="max-width: 480px; margin: 0 auto;">
        
        <h3 style="text-align:center; margin-bottom:20px;">
            <span class="badge-dot"></span> 🧮 آلة حاسبة
        </h3>

        {{-- شاشة العرض --}}
        <div style="
            background:var(--panel-2);
            border:1px solid var(--border);
            border-radius:10px;
            padding:15px 20px;
            margin-bottom:15px;
            min-height:100px;
            display:flex;
            flex-direction:column;
            justify-content:flex-end;
            direction:ltr;
            overflow-x:auto;
        ">
            {{-- المعادلة --}}
            <div id="calcExpression" style="
                color:var(--text-dim);
                font-size:1rem;
                font-family:monospace;
                text-align:left;
                min-height:25px;
                direction:ltr;
                word-break:break-all;
            ">&nbsp;</div>
            
            {{-- النتيجة --}}
            <div id="calcDisplay" style="
                color:var(--cream);
                font-size:2.4rem;
                font-family:monospace;
                text-align:left;
                direction:ltr;
                font-weight:bold;
                min-height:50px;
                display:flex;
                align-items:center;
            ">0</div>
        </div>

        {{-- شبكة الأزرار --}}
        <div id="calcGrid" style="
            display:grid;
            grid-template-columns:repeat(4, 1fr);
            gap:8px;
        ">
            {{-- الصف 1 --}}
            <button type="button" class="calc-btn" data-value="C" data-type="clear">C</button>
            <button type="button" class="calc-btn" data-value="⌫" data-type="back">⌫</button>
            <button type="button" class="calc-btn" data-value="%" data-type="percent">%</button>
            <button type="button" class="calc-btn" data-value="/" data-type="op">÷</button>

            {{-- الصف 2 --}}
            <button type="button" class="calc-btn" data-value="7" data-type="num">7</button>
            <button type="button" class="calc-btn" data-value="8" data-type="num">8</button>
            <button type="button" class="calc-btn" data-value="9" data-type="num">9</button>
            <button type="button" class="calc-btn" data-value="*" data-type="op">×</button>

            {{-- الصف 3 --}}
            <button type="button" class="calc-btn" data-value="4" data-type="num">4</button>
            <button type="button" class="calc-btn" data-value="5" data-type="num">5</button>
            <button type="button" class="calc-btn" data-value="6" data-type="num">6</button>
            <button type="button" class="calc-btn" data-value="-" data-type="op">−</button>

            {{-- الصف 4 --}}
            <button type="button" class="calc-btn" data-value="1" data-type="num">1</button>
            <button type="button" class="calc-btn" data-value="2" data-type="num">2</button>
            <button type="button" class="calc-btn" data-value="3" data-type="num">3</button>
            <button type="button" class="calc-btn" data-value="+" data-type="op">+</button>

            {{-- الصف 5 --}}
            <button type="button" class="calc-btn" data-value="00" data-type="num">00</button>
            <button type="button" class="calc-btn" data-value="0" data-type="num">0</button>
            <button type="button" class="calc-btn" data-value="." data-type="dot">.</button>
            <button type="button" class="calc-btn" data-value="=" data-type="eq">=</button>
        </div>

        {{-- زر مسح الكل --}}
        <div style="margin-top:12px;">
            <button type="button" id="calcClearAll" style="
                width:100%;
                background:var(--danger-soft);
                color:#f0a49c;
                border:1px solid #4a2a26;
                padding:14px;
                border-radius:10px;
                font-size:1rem;
                font-weight:600;
                cursor:pointer;
                transition:all 0.2s;
            ">🗑️ مسح الكل</button>
        </div>

        {{-- معلومات --}}
        <div style="
            margin-top:15px;
            padding:10px;
            background:var(--panel-2);
            border-radius:8px;
            border:1px solid var(--border);
            text-align:center;
            font-size:0.75rem;
            color:var(--text-dim);
        ">
            💡 يمكنك استخدام لوحة المفاتيح أيضاً
        </div>
    </div>
</section>

{{-- ============================================ --}}
{{--  🎨 أنماط الأزرار --}}
{{-- ============================================ --}}
<style>
    .calc-btn {
        background: var(--panel-2);
        color: var(--cream);
        border: 1px solid var(--border);
        padding: 18px 12px;
        border-radius: 10px;
        font-size: 1.2rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        font-family: inherit;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }
    
    .calc-btn:hover {
        background: #232227;
        transform: translateY(-1px);
    }
    
    .calc-btn:active {
        transform: scale(0.96);
    }
    
    .calc-btn[data-type="op"] {
        background: var(--gold-soft);
        color: var(--cream);
        border-color: var(--gold-soft);
        font-size: 1.3rem;
        font-weight: 700;
    }
    
    .calc-btn[data-type="op"]:hover {
        background: var(--gold);
        color: #1b1608;
    }
    
    .calc-btn[data-type="eq"] {
        background: var(--gold);
        color: #1b1608;
        border-color: var(--gold);
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .calc-btn[data-type="eq"]:hover {
        filter: brightness(1.1);
    }
    
    .calc-btn[data-type="clear"] {
        background: var(--danger-soft);
        color: #f0a49c;
        border-color: #4a2a26;
    }
    
    .calc-btn[data-type="clear"]:hover {
        background: #4a2a26;
    }
    
    .calc-btn[data-type="back"] {
        background: var(--panel-2);
        color: var(--cream);
    }
    
    .calc-btn[data-type="percent"] {
        background: var(--blue-soft);
        color: #a9c8e0;
        border-color: #2c435a;
    }
    
    .calc-btn[data-type="percent"]:hover {
        background: #2c435a;
    }
    
    #calcClearAll:hover {
        background: #4a2a26;
    }
</style>