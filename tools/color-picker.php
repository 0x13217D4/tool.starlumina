<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>颜色选择器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .color-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .color-picker-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .color-preview {
            flex: 1;
            min-width: 200px;
            height: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }
        
        .color-values {
            flex: 1;
            min-width: 250px;
        }
        
        .color-formats {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .color-format {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .color-sliders {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .slider-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .slider-label {
            width: 60px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .slider-input {
            flex: 1;
        }
        
        .slider-value {
            width: 50px;
            text-align: center;
        }
        
        input[type="range"] {
            appearance: none;
            -webkit-appearance: none;
            height: 8px;
            border-radius: 4px;
            background: #e0e0e0;
            outline: none;
        }
        
        input[type="range"]::-webkit-slider-thumb {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #3498db;
            cursor: pointer;
        }
        
        input[type="text"] {
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: monospace;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            width: 100%;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        
        label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            display: block;
            font-size: 0.95rem;
        }
        
        .color-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .use-btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            font-size: 1rem;
        }
        
        .use-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .use-btn.secondary {
            background-color: #6c757d;
        }
        
        .use-btn.secondary:hover {
            background-color: #5a6268;
        }
        
        .error-message {
            color: #dc3545;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }
        
        .color-palette {
            margin-top: 1.5rem;
        }
        
        .palette-title {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .palette-colors {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .palette-color {
            width: 50px;
            height: 50px;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .palette-color:hover::after {
            content: attr(data-value);
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0,0,0,0.7);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem;
            text-align: center;
        }
        
        .contrast-ratio {
            margin-top: 1rem;
            padding: 0.75rem;
            border-radius: 6px;
            background-color: #f8f9fa;
        }
        
        .contrast-value {
            font-weight: bold;
        }
        
        .contrast-rating {
            margin-top: 0.25rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>颜色选择器</h1>
            <div class="info-card">
                <div class="color-controls">
                    <div class="color-picker-container">
                        <div class="color-preview" id="color-preview">
                            <span id="color-value" style="background-color: rgba(0,0,0,0.7); color: white; padding: 0.3rem 0.6rem; border-radius: 4px;"></span>
                        </div>
                        
                        <div class="color-values">
                            <div class="color-formats">
                                <div class="color-format">
                                    <label for="hex-value">HEX:</label>
                                    <input type="text" id="hex-value" value="#3498db">
                                </div>
                                <div class="color-format">
                                    <label for="rgb-value">RGB:</label>
                                    <input type="text" id="rgb-value" value="rgb(52, 152, 219)">
                                </div>
                                <div class="color-format">
                                    <label for="hsl-value">HSL:</label>
                                    <input type="text" id="hsl-value" value="hsl(204, 70%, 53%)">
                                </div>
                            </div>
                            
                            <div class="color-sliders">
                                <div class="slider-group">
                                    <span class="slider-label">Red:</span>
                                    <input type="range" id="red-slider" class="slider-input" min="0" max="255" value="52">
                                    <span class="slider-value" id="red-value">52</span>
                                </div>
                                <div class="slider-group">
                                    <span class="slider-label">Green:</span>
                                    <input type="range" id="green-slider" class="slider-input" min="0" max="255" value="152">
                                    <span class="slider-value" id="green-value">152</span>
                                </div>
                                <div class="slider-group">
                                    <span class="slider-label">Blue:</span>
                                    <input type="range" id="blue-slider" class="slider-input" min="0" max="255" value="219">
                                    <span class="slider-value" id="blue-value">219</span>
                                </div>
                                <div class="slider-group">
                                    <span class="slider-label">Alpha:</span>
                                    <input type="range" id="alpha-slider" class="slider-input" min="0" max="100" value="100">
                                    <span class="slider-value" id="alpha-value">100%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="color-buttons">
                        <button id="random-btn" class="use-btn">随机颜色</button>
                        <button id="copy-hex-btn" class="use-btn secondary">复制HEX</button>
                        <button id="copy-rgb-btn" class="use-btn secondary">复制RGB</button>
                        <button id="copy-hsl-btn" class="use-btn secondary">复制HSL</button>
                    </div>
                    
                    <div id="error-message" class="error-message" style="display:none;"></div>
                    
                    <div class="contrast-ratio">
                        <div>对比度: <span class="contrast-value" id="contrast-value">4.54</span></div>
                        <div class="contrast-rating" id="contrast-rating">AA级 (最小对比度4.5:1)</div>
                    </div>
                    
                    <div class="color-palette">
                        <div class="palette-title">调色板:</div>
                        <div class="palette-colors" id="palette-colors">
                            <!-- 调色板颜色将通过JavaScript动态生成 -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
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
            
        // 颜色选择器逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const colorPreview = document.getElementById('color-preview');
            const colorValue = document.getElementById('color-value');
            const hexInput = document.getElementById('hex-value');
            const rgbInput = document.getElementById('rgb-value');
            const hslInput = document.getElementById('hsl-value');
            const redSlider = document.getElementById('red-slider');
            const greenSlider = document.getElementById('green-slider');
            const blueSlider = document.getElementById('blue-slider');
            const alphaSlider = document.getElementById('alpha-slider');
            const redValue = document.getElementById('red-value');
            const greenValue = document.getElementById('green-value');
            const blueValue = document.getElementById('blue-value');
            const alphaValue = document.getElementById('alpha-value');
            const randomBtn = document.getElementById('random-btn');
            const copyHexBtn = document.getElementById('copy-hex-btn');
            const copyRgbBtn = document.getElementById('copy-rgb-btn');
            const copyHslBtn = document.getElementById('copy-hsl-btn');
            const errorMessage = document.getElementById('error-message');
            const contrastValue = document.getElementById('contrast-value');
            const contrastRating = document.getElementById('contrast-rating');
            const paletteColors = document.getElementById('palette-colors');
            
            let currentColor = {
                r: 52,
                g: 152,
                b: 219,
                a: 1
            };
            
            // 初始化
            updateColor();
            generatePalette();
            
            // RGB滑块事件
            redSlider.addEventListener('input', function() {
                currentColor.r = parseInt(this.value);
                redValue.textContent = currentColor.r;
                updateColor();
            });
            
            greenSlider.addEventListener('input', function() {
                currentColor.g = parseInt(this.value);
                greenValue.textContent = currentColor.g;
                updateColor();
            });
            
            blueSlider.addEventListener('input', function() {
                currentColor.b = parseInt(this.value);
                blueValue.textContent = currentColor.b;
                updateColor();
            });
            
            alphaSlider.addEventListener('input', function() {
                const alpha = parseInt(this.value);
                currentColor.a = alpha / 100;
                alphaValue.textContent = alpha + '%';
                updateColor();
            });
            
            // 颜色格式输入事件
            hexInput.addEventListener('change', function() {
                const hex = this.value.trim();
                if (/^#?([0-9A-F]{3}|[0-9A-F]{6}|[0-9A-F]{8})$/i.test(hex)) {
                    const hexColor = hex.startsWith('#') ? hex : '#' + hex;
                    const rgb = hexToRgb(hexColor);
                    if (rgb) {
                        currentColor.r = rgb.r;
                        currentColor.g = rgb.g;
                        currentColor.b = rgb.b;
                        currentColor.a = rgb.a || 1;
                        updateSliders();
                        updateColor();
                    }
                } else {
                    showError('无效的HEX颜色格式');
                    this.value = rgbToHex(currentColor.r, currentColor.g, currentColor.b, currentColor.a);
                }
            });
            
            rgbInput.addEventListener('change', function() {
                const rgb = this.value.trim();
                const match = rgb.match(/^rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*([01]?\.\d+)\s*)?\)$/i);
                if (match) {
                    currentColor.r = parseInt(match[1]);
                    currentColor.g = parseInt(match[2]);
                    currentColor.b = parseInt(match[3]);
                    currentColor.a = match[4] ? parseFloat(match[4]) : 1;
                    updateSliders();
                    updateColor();
                } else {
                    showError('无效的RGB颜色格式');
                    this.value = rgbToRgbString(currentColor.r, currentColor.g, currentColor.b, currentColor.a);
                }
            });
            
            hslInput.addEventListener('change', function() {
                const hsl = this.value.trim();
                const match = hsl.match(/^hsla?\(\s*(\d{1,3})\s*,\s*(\d{1,3})%\s*,\s*(\d{1,3})%\s*(?:,\s*([01]?\.\d+)\s*)?\)$/i);
                if (match) {
                    const h = parseInt(match[1]);
                    const s = parseInt(match[2]);
                    const l = parseInt(match[3]);
                    const a = match[4] ? parseFloat(match[4]) : 1;
                    const rgb = hslToRgb(h, s, l);
                    currentColor.r = rgb.r;
                    currentColor.g = rgb.g;
                    currentColor.b = rgb.b;
                    currentColor.a = a;
                    updateSliders();
                    updateColor();
                } else {
                    showError('无效的HSL颜色格式');
                    this.value = rgbToHslString(currentColor.r, currentColor.g, currentColor.b, currentColor.a);
                }
            });
            
            // 按钮事件
            randomBtn.addEventListener('click', function() {
                currentColor.r = Math.floor(Math.random() * 256);
                currentColor.g = Math.floor(Math.random() * 256);
                currentColor.b = Math.floor(Math.random() * 256);
                currentColor.a = 1;
                updateSliders();
                updateColor();
                generatePalette();
            });
            
            copyHexBtn.addEventListener('click', function() {
                copyToClipboard(hexInput.value);
                showCopiedFeedback(this);
            });
            
            copyRgbBtn.addEventListener('click', function() {
                copyToClipboard(rgbInput.value);
                showCopiedFeedback(this);
            });
            
            copyHslBtn.addEventListener('click', function() {
                copyToClipboard(hslInput.value);
                showCopiedFeedback(this);
            });
            
            // 更新颜色显示
            function updateColor() {
                const rgbaString = `rgba(${currentColor.r}, ${currentColor.g}, ${currentColor.b}, ${currentColor.a})`;
                colorPreview.style.backgroundColor = rgbaString;
                
                // 根据亮度设置文本颜色
                const brightness = (currentColor.r * 299 + currentColor.g * 587 + currentColor.b * 114) / 1000;
                colorValue.style.color = brightness > 128 ? 'black' : 'white';
                colorValue.style.backgroundColor = brightness > 128 ? 'rgba(255,255,255,0.7)' : 'rgba(0,0,0,0.7)';
                colorValue.textContent = rgbaString;
                
                // 更新输入框
                hexInput.value = rgbToHex(currentColor.r, currentColor.g, currentColor.b, currentColor.a);
                rgbInput.value = rgbToRgbString(currentColor.r, currentColor.g, currentColor.b, currentColor.a);
                hslInput.value = rgbToHslString(currentColor.r, currentColor.g, currentColor.b, currentColor.a);
                
                // 计算对比度
                calculateContrast();
                
                // 生成调色板
                generatePalette();
            }
            
            // 更新滑块位置
            function updateSliders() {
                redSlider.value = currentColor.r;
                greenSlider.value = currentColor.g;
                blueSlider.value = currentColor.b;
                alphaSlider.value = currentColor.a * 100;
                
                redValue.textContent = currentColor.r;
                greenValue.textContent = currentColor.g;
                blueValue.textContent = currentColor.b;
                alphaValue.textContent = Math.round(currentColor.a * 100) + '%';
            }
            
            // 颜色转换函数
            function rgbToHex(r, g, b, a = 1) {
                const toHex = (c) => {
                    const hex = c.toString(16);
                    return hex.length === 1 ? '0' + hex : hex;
                };
                
                const hex = '#' + toHex(r) + toHex(g) + toHex(b);
                return a === 1 ? hex : hex + toHex(Math.round(a * 255));
            }
            
            function hexToRgb(hex) {
                // 移除#号
                hex = hex.replace(/^#/, '');
                
                // 解析RGB值
                let r, g, b, a = 1;
                
                if (hex.length === 3) {
                    r = parseInt(hex[0] + hex[0], 16);
                    g = parseInt(hex[1] + hex[1], 16);
                    b = parseInt(hex[2] + hex[2], 16);
                } else if (hex.length === 6) {
                    r = parseInt(hex.substring(0, 2), 16);
                    g = parseInt(hex.substring(2, 4), 16);
                    b = parseInt(hex.substring(4, 6), 16);
                } else if (hex.length === 8) {
                    r = parseInt(hex.substring(0, 2), 16);
                    g = parseInt(hex.substring(2, 4), 16);
                    b = parseInt(hex.substring(4, 6), 16);
                    a = parseInt(hex.substring(6, 8), 16) / 255;
                } else {
                    return null;
                }
                
                return { r, g, b, a };
            }
            
            function rgbToRgbString(r, g, b, a = 1) {
                return a === 1 
                    ? `rgb(${r}, ${g}, ${b})`
                    : `rgba(${r}, ${g}, ${b}, ${a})`;
            }
            
            function rgbToHslString(r, g, b, a = 1) {
                // 转换RGB到HSL
                r /= 255;
                g /= 255;
                b /= 255;
                
                const max = Math.max(r, g, b);
                const min = Math.min(r, g, b);
                let h, s, l = (max + min) / 2;
                
                if (max === min) {
                    h = s = 0; // 灰度
                } else {
                    const d = max - min;
                    s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                    
                    switch (max) {
                        case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                        case g: h = (b - r) / d + 2; break;
                        case b: h = (r - g) / d + 4; break;
                    }
                    
                    h /= 6;
                }
                
                h = Math.round(h * 360);
                s = Math.round(s * 100);
                l = Math.round(l * 100);
                
                return a === 1 
                    ? `hsl(${h}, ${s}%, ${l}%)`
                    : `hsla(${h}, ${s}%, ${l}%, ${a})`;
            }
            
            function hslToRgb(h, s, l) {
                s /= 100;
                l /= 100;
                
                let r, g, b;
                
                if (s === 0) {
                    r = g = b = l; // 灰度
                } else {
                    const hue2rgb = (p, q, t) => {
                        if (t < 0) t += 1;
                        if (t > 1) t -= 1;
                        if (t < 1/6) return p + (q - p) * 6 * t;
                        if (t < 1/2) return q;
                        if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
                        return p;
                    };
                    
                    const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
                    const p = 2 * l - q;
                    
                    r = hue2rgb(p, q, h / 360 + 1/3);
                    g = hue2rgb(p, q, h / 360);
                    b = hue2rgb(p, q, h / 360 - 1/3);
                }
                
                return {
                    r: Math.round(r * 255),
                    g: Math.round(g * 255),
                    b: Math.round(b * 255)
                };
            }
            
            // 计算对比度
            function calculateContrast() {
                // 计算当前颜色与白色的对比度
                const rgb = [currentColor.r / 255, currentColor.g / 255, currentColor.b / 255];
                const luminance = 0.2126 * rgb[0] + 0.7152 * rgb[1] + 0.0722 * rgb[2];
                const contrast = (luminance + 0.05) / (1 + 0.05);
                
                const contrastRatio = contrast >= 1 ? contrast : 1 / contrast;
                const roundedRatio = Math.round(contrastRatio * 100) / 100;
                
                contrastValue.textContent = roundedRatio;
                
                // 设置对比度评级
                let rating = '';
                if (roundedRatio >= 7) {
                    rating = 'AAA级 (最小对比度7:1)';
                } else if (roundedRatio >= 4.5) {
                    rating = 'AA级 (最小对比度4.5:1)';
                } else if (roundedRatio >= 3) {
                    rating = 'A级 (最小对比度3:1)';
                } else {
                    rating = '不足 (对比度低于3:1)';
                }
                
                contrastRating.textContent = rating;
            }
            
            // 生成调色板
            function generatePalette() {
                paletteColors.innerHTML = '';
                
                // 生成12种相关颜色
                const colors = generateRelatedColors(currentColor.r, currentColor.g, currentColor.b, 12);
                
                colors.forEach(color => {
                    const colorEl = document.createElement('div');
                    colorEl.className = 'palette-color';
                    colorEl.style.backgroundColor = `rgb(${color.r}, ${color.g}, ${color.b})`;
                    colorEl.setAttribute('data-value', rgbToHex(color.r, color.g, color.b));
                    
                    colorEl.addEventListener('click', () => {
                        currentColor.r = color.r;
                        currentColor.g = color.g;
                        currentColor.b = color.b;
                        updateSliders();
                        updateColor();
                    });
                    
                    paletteColors.appendChild(colorEl);
                });
            }
            
            // 生成相关颜色
            function generateRelatedColors(r, g, b, count) {
                const colors = [];
                
                // 添加当前颜色
                colors.push({ r, g, b });
                
                // 生成变化颜色
                const hueStep = 30; // 色相变化步长
                const satLightStep = 0.1; // 饱和度和亮度变化步长
                
                for (let i = 1; i < count; i++) {
                    // 转换为HSL
                    const hsl = rgbToHsl(r, g, b);
                    
                    // 变化色相、饱和度和亮度
                    const newHue = (hsl.h + i * hueStep) % 360;
                    const newSat = Math.min(1, Math.max(0, hsl.s + (i % 2 === 0 ? satLightStep : -satLightStep)));
                    const newLight = Math.min(1, Math.max(0, hsl.l + (i % 3 === 0 ? satLightStep : -satLightStep)));
                    
                    // 转换回RGB
                    const rgb = hslToRgb(newHue, newSat * 100, newLight * 100);
                    colors.push(rgb);
                }
                
                return colors;
            }
            
            // RGB转HSL
            function rgbToHsl(r, g, b) {
                r /= 255;
                g /= 255;
                b /= 255;
                
                const max = Math.max(r, g, b);
                const min = Math.min(r, g, b);
                let h, s, l = (max + min) / 2;
                
                if (max === min) {
                    h = s = 0; // 灰度
                } else {
                    const d = max - min;
                    s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                    
                    switch (max) {
                        case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                        case g: h = (b - r) / d + 2; break;
                        case b: h = (r - g) / d + 4; break;
                    }
                    
                    h /= 6;
                }
                
                return {
                    h: h * 360,
                    s,
                    l
                };
            }
            
            // 复制到剪贴板
            function copyToClipboard(text) {
                const input = document.createElement('textarea');
                input.value = text;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
            }
            
            // 显示复制反馈
            function showCopiedFeedback(button) {
                const originalText = button.textContent;
                button.textContent = '已复制!';
                setTimeout(() => {
                    button.textContent = originalText;
                }, 2000);
            }
            
            // 显示错误信息
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>
