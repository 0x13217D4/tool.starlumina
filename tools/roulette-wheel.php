<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>随机转盘 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .tool-content {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .wheel-tool-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .controls {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .controls label {
            margin: 0 10px;
            color: #2c3e50;
            font-weight: 500;
        }
        
        input[type="number"] {
            padding: 10px;
            margin: 5px;
            border: 2px solid #3498db;
            border-radius: 5px;
            width: 80px;
            font-size: 16px;
            text-align: center;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 15px;
        }
        
        button {
            padding: 12px 24px;
            margin: 5px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        button:disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        button#spinBtn {
            background-color: #2ecc71;
        }
        
        button#spinBtn:hover {
            background-color: #27ae60;
        }
        
        .wheel-container {
            position: relative;
            width: 350px;
            height: 350px;
            margin: 20px 0;
        }
        
        #spinner {
            width: 100%;
            height: 100%;
            position: relative;
            transition: transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
            border: 4px solid #3498db;
            border-radius: 50%;
        }
        
        #spinner-inner {
            width: 100%;
            height: 100%;
            transition: transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99);
        }
        
        #ticker {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            pointer-events: none;
        }
        
        #ticker svg {
            height: 40px;
            width: 40px;
            overflow: visible;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .modal-content {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            max-width: 450px;
            width: 100%;
            max-width: 90vw;
            position: relative;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: modalPop 0.3s ease-out;
            margin: 0;
        }
        
        @keyframes modalPop {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 28px;
            cursor: pointer;
            color: #95a5a6;
            transition: color 0.3s;
        }
        
        .close-btn:hover {
            color: #2c3e50;
        }
        
        .winning-number {
            font-size: 80px;
            color: #e74c3c;
            font-weight: bold;
            margin: 20px 0;
            animation: numberPop 0.5s ease-out;
        }
        
        .winning-numbers {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fafafa;
        }
        
        /* 自定义滚动条样式 */
        .winning-numbers::-webkit-scrollbar {
            width: 8px;
        }
        
        .winning-numbers::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .winning-numbers::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        .winning-numbers::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .winning-numbers .number-item {
            font-size: 40px;
            color: #e74c3c;
            font-weight: bold;
            padding: 10px 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #e74c3c;
            animation: numberPop 0.5s ease-out;
            flex-shrink: 0;
        }
        
        @keyframes numberPop {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .timer {
            font-size: 20px;
            color: #7f8c8d;
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .status {
            margin-top: 25px;
            font-size: 18px;
            color: #2c3e50;
            padding: 15px;
            background-color: #e8f4f8;
            border-radius: 8px;
            border-left: 4px solid #3498db;
            text-align: center;
            font-weight: 500;
        }
        
        @media (max-width: 600px) {
            .wheel-container {
                width: 280px;
                height: 280px;
            }
            
            .segment-content {
                font-size: 14px;
            }
            
            .winning-number {
                font-size: 60px;
            }
            
            .winning-numbers .number-item {
                font-size: 28px;
                padding: 8px 12px;
            }
            
            .controls label {
                display: block;
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            
            <div class="wheel-tool-container">
                <h1 style="color: #2c3e50; margin-bottom: 30px;">随机转盘</h1>
                
                <div class="controls">
                    <label>最小值: <input type="number" id="minValue" value="1"></label>
                    <label>最大值: <input type="number" id="maxValue" value="10"></label>
                    <label>每次抽取数: <input type="number" id="drawCount" value="1" min="1" max="50"></label>
                    <div class="btn-group">
                        <button id="initBtn">初始化转盘</button>
                        <button id="spinBtn" disabled>开始抽奖</button>
                    </div>
                </div>
                
                <div class="wheel-container">
                    <div id="spinner"></div>
                </div>
                
                <div class="status" id="status">请先初始化转盘</div>
            </div>
        </div>
    </main>
    
    <div class="modal" id="resultModal">
        <div class="modal-content">
            <span class="close-btn" id="closeModal">&times;</span>
            <h2 style="color: #2c3e50;">🎉 恭喜抽中！</h2>
            <div id="singleResult" class="winning-number" style="display: none;">0</div>
            <div id="multipleResult" class="winning-numbers" style="display: none;"></div>
            <p style="color: #7f8c8d; margin: 15px 0;">数字已从转盘中移除</p>
            <div class="timer">自动关闭倒计时: <span id="timer">10</span>秒</div>
        </div>
    </div>
    
    <div id="footer-container"></div>

    <script>
        // 加载公共组件
        fetch('../templates/header.php')
            .then(response => response.text())
            .then(html => document.getElementById('header-container').innerHTML = html);
            
        fetch('../templates/footer.php')
            .then(response => response.text())
            .then(html => {
                document.getElementById('footer-container').innerHTML = html;
                document.getElementById('current-year').textContent = new Date().getFullYear();
            });

        // 新转盘算法实现
        var spinner = function() {
            "use strict";
            var t = t => 2 * Math.PI / t;
            var e = t => {
                const {
                    originX: e,
                    originY: n,
                    spinnerRadius: i,
                    pinRadius: r
                } = t;
                return `<circle cx=${e} cy=${n} r=${i-2*r} fill=transparent stroke-width=${4*r} stroke=var(--shade) />`
            };
            var n = t => {
                const {
                    viewBoxSize: e,
                    originX: n,
                    originY: i,
                    spinnerRadius: r
                } = t;
                return `<mask id="mask"><rect width="${e}" height="${e}" fill="#000"/><circle cx=${n} cy=${i} r=${r} fill="#fff"/></mask>`
            };
            var i = t =>
                `<path d="M${t-4} 3 h12 l-6 10z" fill=var(--shade) stroke-linejoin="round" stroke-width=2 stroke=var(--text) />`;
            var r = (t, e, n, i) => [n * Math.cos(i) + t, n * Math.sin(i) + e];
            var s = (e, n) => {
                const {
                    originX: i,
                    originY: s,
                    spinnerRadius: a,
                    pinRadius: o
                } = n;
                let l = "",
                    h = Math.min(e, 60);
                const c = t(h);
                for (let t = 0; t < h; t++) {
                    const e = r(i, s, a - 2 * o, c * t);
                    l += `<circle cx=${e[0]} cy=${e[1]} r=${o} />`
                }
                return `<g fill=var(--text)>${l}</g>`
            };
            var a = (t, e, n, i, s, a) => {
                a && (s = 2 * Math.PI - .01);
                const o = r(t, e, n, i),
                    l = r(t, e, n, s),
                    h = s - i <= Math.PI ? 0 : 1,
                    c = s < i ? 0 : 1;
                return `M${o[0]} ${o[1]} A${n} ${n} 0 ${h} ${c} ${l[0]} ${l[1]}`
            };
            var o = (e, n) => {
                const {
                    originX: i,
                    originY: s,
                    spinnerRadius: o,
                    arcTextOffset: l,
                    straightTextOffset: h,
                    straightTextOffsetInner: c,
                    fontSize: d
                } = n, p = e.length, u = t(p), m = o - l;
                return e.map(((t, e) => {
                    const n = p > 20 || ((t, e, n, i) => {
                            const r = e * n;
                            return t.length * i * .6 > r
                        })(t, u, m, d) || u < 1,
                        l = u * e,
                        g = l + u;
                    let f = "";
                    if (n) {
                        f = ((t, e, n, i, s, a, o) => {
                            const l = i + s / 2 - a / 2 / n * .7,
                                h = r(t, e, n, l),
                                c = r(t, e, o, l);
                            return `M${h[0]} ${h[1]}L${c[0]} ${c[1]}`
                        })(i, s, o - h, l, u, d, c)
                    } else {
                        f = a(i, s, m, l, g, p < 2)
                    }
                    return {
                        path: `<path id=t${e} d="${f}" />`,
                        text: `<text fill=var(--text)><textPath ${n?"":"text-anchor=middle startOffset=50%"} href=#t${e}>${t}</textPath></text>`
                    }
                }))
            };
            var l = t => t.map(((t, e) => ((t, e) =>
                `\n    <pattern id=p${t} width=4 height=4 patternUnits=userSpaceOnUse >
                <path d="m0 0h4v4h-4z"/><path d="${e}" fill="#fff" />
                </pattern>
                <mask id=m${t} width="4" height="4"><path d="m0 0h1000v1000h-1000z" fill="url(#p${t})"/></mask>`
            )(e, t))).join("");
            var h = (e, n) => {
                const {
                    originX: i,
                    originY: s,
                    spinnerRadius: a,
                    viewBoxSize: o,
                    colors: l,
                    patterns: h,
                    patternColors: c
                } = n, d = e.length;
                return e.map(((e, n) => ((e, n) => {
                    const d = "m" + n % h.length,
                        p = l.length,
                        u = c.length;
                    let m = n % p;
                    e - n == 1 && 0 === m && p > 2 && (m += 2);
                    let g = l[m],
                        f = c[m % u],
                        v = `M0 0h${o}v${o}h-${o}`;
                    if (e > 2) {
                        const o = t(e),
                            l = o * n,
                            h = l + o,
                            c = r(i, s, 2 * a, l),
                            d = r(i, s, 2 * a, h);
                        v = `M${i} ${s} L${c[0]} ${c[1]} L${d[0]} ${d[1]}z`
                    } else n > 0 && (v = `M0 0 v${s} h${o} v-${i}`);
                    return e > 50 ? `<path d="${v}" fill=${g} />` :
                        `<path d="${v}" fill=${g} /><path d="${v}" fill=${f} mask="url(#${d})" />`
                })(d, n))).join("")
            };
            const c = {
                spinnerRadius: 195,
                originX: 200,
                originY: 200,
                viewBoxSize: 400,
                pinRadius: 2,
                arcTextOffset: 30,
                straightTextOffset: 10,
                straightTextOffsetInner: 20,
                fontSize: 12,
                colors: ["#FF9AA2","#FFB7B2","#FFDAC1","#E2F0CB","#B5EAD7","#C7CEEA","#B8E0D2","#FFD3A5","#FFAAA5","#FFC9A3"].map((t, e) => t),
                patternColors: ["#FF6B6B","#4ECDC4","#45B7D1","#FFBE0B","#FB5607","#8338EC","#3A86FF","#FF006E","#8AC926","#1982C4"].map((t, e) => t),
                patterns: ["m1 2a1 1 0 102 0a1 1 0 10-2 0", "m0 0h1l3 3v1h-1l-3-3z M3 0h1v1z M0 3v1h1z",
                    "m0 4v-1l2-2l2 2v1l-2-2z"
                ],
                animationName: ""
            };
            var d = (r, a = c) => {
                a = { ...c,
                    ...a
                };
                const d = t(r.length);
                a.fontSize = r.length > 100 ? d * a.spinnerRadius * .9 : 12;
                const p = o(r, a),
                    u = l(a.patterns),
                    m = n(a),
                    g = h(r, a),
                    f = s(r.length, a),
                    v = i(a.originX),
                    $ = e(a);
                return `
                    <div id="spinner-inner">
                    <svg viewBox="0 0 ${a.viewBoxSize} ${a.viewBoxSize}" height=100% width=100% font-size="${a.fontSize}">
                        <defs>
                        ${u}
                        ${m}
                        ${p.map((t=>t.path)).join("")}
                        </defs>
                        <g>
                        <circle cx=${a.originX} cy=${a.originY} r=${a.spinnerRadius} fill=var(--base) />
                        <g style="mask:url(#mask);">
                        ${g}
                        </g>
                        ${$}
                        ${f}
                        ${p.map((t=>t.text)).join("")}
                        </g>
                        
                        <circle cx=${a.originX} cy=${a.originY} r=5 fill=var(--base) stroke=var(--shade) stroke-width=2></circle>
                    </svg>
                    </div>
                    <div id="ticker">
                        <svg viewBox="0 0 ${a.viewBoxSize} ${a.viewBoxSize}" height=100% width=100%>
                            ${v}
                        </svg>
                    </div>
                    `
            };
            var p = t => {
                let e = t % (2 * Math.PI);
                return e < 0 && (e += 2 * Math.PI), e
            };
            var u = (t, e, n) => (t - e) * (t - n) <= 0;
            var m = t => t[t.length * Math.random() | 0];
            var g = (t, e) => Math.floor(Math.random() * (e - t + 1)) + t;
            const f = new(window.AudioContext || window.webkitAudioContext);

            function v(t) {
                this.audioContext = t
            }
            v.prototype.setup = function() {
                this.gain = this.audioContext.createGain();
                this.bandpass = this.audioContext.createBiquadFilter(), this.bandpass.type = "bandpass", this.bandpass.frequency
                    .value =
                    9e3, this.highpass = this.audioContext.createBiquadFilter(), this.highpass.type = "highpass", this.highpass.frequency
                    .value = 4500, this.lowpass = this.audioContext.createBiquadFilter(), this.lowpass.type = "lowpass", this.lowpass
                    .frequency.value = 2500, this.oscillators = [], [2, 3, 4.16, 5.43, 6.79, 8.21].forEach((t => {
                        const e = this.audioContext.createOscillator();
                        e.type = "square", e.frequency.value = 40 * t, e.frequency.exponentialRampToValueAtTime(.001, this.audioContext
                            .currentTime + 1), this.oscillators.push(e)
                    })), this.oscillators.forEach((t => t.connect(this.bandpass))), this.bandpass.connect(this.highpass).connect(
                        this.lowpass).connect(this.gain).connect(this.audioContext.destination)
            }, v.prototype.trigger = function() {
                this.setup(), this.gain.gain.setValueAtTime(1, this.audioContext.currentTime), this.gain.gain.exponentialRampToValueAtTime(
                    .01, this.audioContext.currentTime + .06), this.oscillators.forEach((t => {
                    t.start(this.audioContext.currentTime + .01), t.stop(this.audioContext.currentTime + .07)
                }))
            };
            var $ = () => {
                new v(f).trigger()
            };
            var x = () => {
                const t = window.location.search.substring(1);
                if (!t) return;
                let e = {};
                return t.split("&").forEach((t => {
                    const n = t.split("="),
                        i = n[0],
                        r = n[1].split("+").map((t => decodeURIComponent(t))),
                        s = r.length > 1 ? r : r[0];
                    e[i] = s
                })), e
            };
            var w = t => t.replace(/-./g, (t => t.toUpperCase()[1]));
            var y = t => {
                const e = window.getComputedStyle(t, null);
                let n = "transform";
                const i = e.getPropertyValue("-webkit-transform") || e.getPropertyValue("-moz-transform") || e.getPropertyValue(
                    "-ms-transform") || e.getPropertyValue("-o-transform") || e.getPropertyValue(n);
                let r = 0;
                if ("none" !== i) {
                    const t = i.split("(")[1].split(")")[0].split(","),
                        e = t[0],
                        n = t[1];
                    r = Math.atan2(n, e)
                }
                return r < 0 ? r += 2 * Math.PI : r
            };
            var T = t => (t.split(",").length - 1 > 2 && (t = t.split(",").map((t => t.trim())).join("\n")), t);
            var k = (t, e = "copy", n = !0) => {
                document.getElementById(e).value = n ? window.location.href.split("?")[0] + t : t
            };
            window.updateCopyBox = k;
            const M = {};
            ["spinner", "form", "list", "tada", "tada-text", "remove-button", "remove-name", "reset-wheel", "sound"].forEach(
                (t => {
                    M[w(t)] = document.getElementById(t)
                }));
            const I = 2 * Math.PI,
                C = Math.PI / 2,
                b = [
                    [.19, 1, .22, 1],
                    [.19, 1, .22, 1],
                    [.19, 1, .22, 1],
                    [0, 1.3, .3, 1.01],
                    [.02, .28, .31, 1],
                    [.24, .98, .32, 1],
                    [0, 1, 3, 1.001]
                ],
                R = (M.spinner.animate, window.matchMedia("(prefers-reduced-motion: reduce)").matches),
                B = {
                    maxNumberOfPins: 60,
                    spinnerValues: [],
                    sound: false,
                    sectorAngle: 0,
                    pinRadiansWithTickerOffset: [],
                    extraRotations: 3,
                    timingFunction: b[0],
                    spinDuration: 5000,
                    winningRadian: 0,
                    currentAngle: 0,
                    winner: "",
                    isSpinning: false
                },
                z = () => {
                    B.spinnerValues = S(), P(), M.resetWheel.disabled = true, M.removeButton.disabled = true, F(B.spinnerValues)
                },
                P = () => {
                    const t = d(B.spinnerValues);
                    M.spinner.innerHTML = t;
                    const e = B.spinnerValues.length;
                    B.sectorAngle = I / e, B.pinRadiansWithTickerOffset = V(e, B.maxNumberOfPins), M.spinnerInner = document.getElementById(
                        "spinner-inner"), M.ticker = document.getElementById("ticker")
                },
                S = () => M.list.value.split("\n").filter((t => t.trim())).map((t => t.trim())),
                V = (t, e) => {
                    let n = [],
                        i = Math.min(t, e),
                        r = I / i;
                    for (let t = 0; t < i; t++) n.push(r * t);
                    return n.map((t => p(t - C)))
                },
                E = (t, e) => {
                    M.spinnerInner.style.transitionTimingFunction = `cubic-bezier(${t.join(",")})`, M.spinnerInner.style.transitionProperty =
                        "transform", M.spinnerInner.style.transitionDuration = e / 1e3 + "s"
                },
                O = {
                    startTimestamp: null,
                    lastTimestamp: null,
                    down: true
                },
                A = t => {
                    O.startTimestamp || (O.startTimestamp = t), O.lastTimestamp || (O.lastTimestamp = t);
                    const e = t - O.startTimestamp,
                        n = y(M.spinnerInner);
                    q(n) ? (M.ticker.style.transform = "translateY(-3px)", O.down && B.sound && $(), O.down = false) : t - O.lastTimestamp >
                        20 && (M.ticker.style.transform = "translateY(0)", O.down = true), e < B.spinDuration ? window.requestAnimationFrame(
                        A) : O.startTimestamp = null
                },
                q = t => B.pinRadiansWithTickerOffset.some((e => u(t, e - .05, e + .05))),
                L = t => {
                    M.tada.classList.add("highlight"), M.tadaText.textContent = t, M.removeName.textContent = t, M.removeButton.disabled = !
                        1, setTimeout((() => {
                            M.tada.classList.remove("highlight")
                        }), 2e3)
                },
                F = t => {
                    const e = t.map((t => encodeURIComponent(t))).join("+");
                    k("?values=" + e)
                },
                Y = () => {
                    M.tadaText.textContent = "", M.removeButton.disabled = true, M.removeName.textContent = "winner"
                };

            // 转盘逻辑
            let numbers = [];
            let remainingNumbers = [];
            let spinning = false;
            let countdownInterval = null;
            let currentRotation = 0; // 保持当前旋转角度
            
            document.getElementById('initBtn').addEventListener('click', initWheel);
            document.getElementById('spinBtn').addEventListener('click', spinWheel);
            document.getElementById('closeModal').addEventListener('click', closeModal);
            
            // 修改数字后自动初始化转盘
            document.getElementById('minValue').addEventListener('change', autoInitWheel);
            document.getElementById('maxValue').addEventListener('change', autoInitWheel);
            
            function autoInitWheel() {
                const min = parseInt(document.getElementById('minValue').value);
                const max = parseInt(document.getElementById('maxValue').value);
                
                // 只有当输入有效时才自动初始化
                if (!isNaN(min) && !isNaN(max) && min < max) {
                    initWheel();
                }
            }
            
            // 页面加载完成后自动初始化转盘
            window.addEventListener('load', () => {
                autoInitWheel();
            });
            
            function initWheel() {
                const min = parseInt(document.getElementById('minValue').value);
                const max = parseInt(document.getElementById('maxValue').value);
                
                if (isNaN(min) || isNaN(max) || min >= max) {
                    alert('请输入有效的数字范围（最小值应小于最大值）');
                    return;
                }
                
                if (max - min > 999) {
                    alert('数字范围太大，请选择较小的范围（最多1000个数字）');
                    return;
                }
                
                numbers = Array.from({length: max - min + 1}, (_, i) => min + i);
                remainingNumbers = [...numbers];

                renderWheel();
                document.getElementById('spinBtn').disabled = false;
                document.getElementById('status').textContent = `转盘已初始化，剩余 ${remainingNumbers.length} 个数字`;
            }
            
            function renderWheel() {
                if (remainingNumbers.length === 0) {
                    document.getElementById('status').textContent = '🎊 所有数字已抽完！';
                    document.getElementById('spinBtn').disabled = true;
                    return;
                }
                
                // 使用新的转盘渲染算法
                const spinnerHtml = d(remainingNumbers);
                document.getElementById('spinner').innerHTML = spinnerHtml;
                
                // 获取新的spinner元素
                const spinnerInner = document.getElementById('spinner-inner');
                if (spinnerInner) {
                    // 如果存在之前的旋转角度，应用到新渲染的转盘上
                    if (currentRotation !== 0) {
                        spinnerInner.style.transform = `rotate(${currentRotation}rad)`; // 弧度单位
                    }
                    
                    // 设置过渡属性
                    spinnerInner.style.transitionProperty = 'transform';
                    spinnerInner.style.transitionTimingFunction = 'cubic-bezier(0.17, 0.67, 0.12, 0.99)';
                    spinnerInner.style.transitionDuration = '4s';
                }
            }
            
            function spinWheel() {
                if (spinning || remainingNumbers.length === 0) return;
                
                const drawCount = parseInt(document.getElementById('drawCount').value) || 1;
                const actualDrawCount = Math.min(drawCount, remainingNumbers.length);
                
                if (actualDrawCount > 1) {
                    // 多次抽取模式：直接随机抽取，不转盘
                    spinning = true;
                    document.getElementById('spinBtn').disabled = true;
                    document.getElementById('status').textContent = '?? 抽取中...';
                    
                    setTimeout(() => {
                        // 随机抽取多个数字
                        const winners = [];
                        const tempNumbers = [...remainingNumbers];
                        for (let i = 0; i < actualDrawCount; i++) {
                            const randomIndex = Math.floor(Math.random() * tempNumbers.length);
                            winners.push(tempNumbers[randomIndex]);
                            tempNumbers.splice(randomIndex, 1);
                        }
                        
                        showMultipleResults(winners);
                        spinning = false;
                    }, 500);
                } else {
                    // 单次抽取模式：使用转盘
                    spinning = true;
                    document.getElementById('spinBtn').disabled = true;
                    document.getElementById('status').textContent = '🎰 转盘转动中...';
                    
                    // 获取转盘元素
                    const spinnerInner = document.getElementById('spinner-inner');
                    if (!spinnerInner) return;
                    
                    // 计算随机旋转（弧度制）
                    const extraRotation = Math.random() * Math.PI * 2; // 额外随机旋转弧度
                    const totalExtraRotations = Math.PI * 2 * 3; // 最少3圈
                    const totalRotation = totalExtraRotations + extraRotation; // 总旋转弧度
                    
                    // 计算最终旋转角度（包含之前的旋转）
                    const finalRotation = currentRotation + totalRotation;
                    
                    // 应用旋转
                    spinnerInner.style.transform = `rotate(${finalRotation}rad)`;
                    
                    // 在动画结束后处理结果
                    setTimeout(() => {
                        // 计算实际停止位置对应的数字
                        const normalizedRotation = finalRotation % (Math.PI * 2);
                        const segmentAngle = (Math.PI * 2) / remainingNumbers.length;
                        
                        // 计算获胜扇形的索引
                        // 由于转盘是顺时针旋转，且指针在顶部（0弧度），我们需要找到对应扇形
                        const winningIndex = Math.floor(normalizedRotation / segmentAngle) % remainingNumbers.length;
                        const winningNumber = remainingNumbers[winningIndex];
                        
                        // 更新当前旋转角度
                        currentRotation = finalRotation;
                        
                        showResult(winningNumber);
                        spinning = false;
                    }, 4000); // 与CSS过渡时间一致
                }
            }
            
            function showResult(number) {
                // 显示单个结果
                document.getElementById('singleResult').textContent = number;
                document.getElementById('singleResult').style.display = 'block';
                document.getElementById('multipleResult').style.display = 'none';
                document.getElementById('resultModal').style.display = 'flex';
                
                // 从剩余数字中移除
                const index = remainingNumbers.indexOf(number);
                if (index !== -1) {
                    remainingNumbers.splice(index, 1);
                }
                
                renderWheel();
                
                if (remainingNumbers.length === 0) {
                    document.getElementById('status').textContent = '🎊 所有数字已抽完！';
                } else {
                    document.getElementById('status').textContent = `已抽中 ${number}，剩余 ${remainingNumbers.length} 个数字`;
                }
                
                // 开始倒计时
                let timeLeft = 10;
                document.getElementById('timer').textContent = timeLeft;
                
                clearInterval(countdownInterval);
                countdownInterval = setInterval(() => {
                    timeLeft--;
                    document.getElementById('timer').textContent = timeLeft;
                    
                    if (timeLeft <= 0) {
                        closeModal();
                    }
                }, 1000);
            }
            
            function showMultipleResults(numbers) {
                // 显示多个结果
                const multipleResultDiv = document.getElementById('multipleResult');
                multipleResultDiv.innerHTML = '';
                
                // 使用文档片段优化DOM操作
                const fragment = document.createDocumentFragment();
                numbers.forEach(num => {
                    const numDiv = document.createElement('div');
                    numDiv.className = 'number-item';
                    numDiv.textContent = num;
                    fragment.appendChild(numDiv);
                });
                multipleResultDiv.appendChild(fragment);
                
                document.getElementById('singleResult').style.display = 'none';
                document.getElementById('multipleResult').style.display = 'flex';
                document.getElementById('resultModal').style.display = 'flex';
                
                // 从剩余数字中移除
                numbers.forEach(num => {
                    const index = remainingNumbers.indexOf(num);
                    if (index !== -1) {
                        remainingNumbers.splice(index, 1);
                    }
                });
                
                renderWheel();
                
                if (remainingNumbers.length === 0) {
                    document.getElementById('status').textContent = '?? 所有数字已抽完！';
                } else {
                    document.getElementById('status').textContent = `已抽中 ${numbers.join(', ')}，剩余 ${remainingNumbers.length} 个数字`;
                }
                
                // 使用setTimeout确保DOM渲染完成后再启动倒计时
                setTimeout(() => {
                    // 开始倒计时
                    let timeLeft = 10;
                    document.getElementById('timer').textContent = timeLeft;
                    
                    clearInterval(countdownInterval);
                    countdownInterval = setInterval(() => {
                        timeLeft--;
                        document.getElementById('timer').textContent = timeLeft;
                        
                        if (timeLeft <= 0) {
                            closeModal();
                        }
                    }, 1000);
                }, 100);
            }
            
            function closeModal() {
                document.getElementById('resultModal').style.display = 'none';
                clearInterval(countdownInterval);
                document.getElementById('spinBtn').disabled = remainingNumbers.length === 0;
            }
        };

        // 初始化转盘函数
        spinner();
    </script>
</body>
</html>