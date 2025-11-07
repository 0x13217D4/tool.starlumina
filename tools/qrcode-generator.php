<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二维码生成器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        /* 二维码生成器特定样式 */
        .qrcode-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .control-row {
            display: flex;
            gap: 1.5rem;
        }
        
        .control-row .control-group {
            flex: 1;
        }
        
        /* 全局表单元素样式 */
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            width: 100%;
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
            margin: 0;
        }
        
        /* 滑块样式 */
        input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: #e0e0e0;
            outline: none;
            margin: 1rem 0;
        }
        
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #3498db;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.2);
            background: #2980b9;
        }
        
        /* 颜色选择器样式 */
        input[type="color"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 50px;
            height: 50px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            padding: 0;
            background: none;
        }
        
        input[type="color"]::-webkit-color-swatch {
            border: 2px solid #fff;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        input[type="color"]::-webkit-color-swatch-wrapper {
            padding: 0;
        }
        
        /* 下拉框样式 */
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 12px;
            padding-right: 2rem;
            cursor: pointer;
        }
        
        /* 标签样式 */
        label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            display: block;
            font-size: 0.95rem;
        }
        
        /* 表单组间距 */
        .control-group {
            margin-bottom: 1.25rem;
            padding: 0 0.75rem;
        }
        
        .qrcode-preview {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 300px;
        }
        
        #qrcode {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* 按钮样式 */
        .use-btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            margin-right: 0.5rem;
        }
        
        .use-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>二维码生成器</h1>
            <div class="info-card">
                <div class="qrcode-controls">
                    <div class="control-group">
                        <label for="qr-content">内容类型：</label>
                        <select id="qr-type" class="form-control">
                            <option value="text">文本</option>
                            <option value="url">网址</option>
                            <option value="wifi">WiFi配置</option>
                            <option value="geo">地理位置</option>
                        </select>
                    </div>
                    
                    <div id="text-input-group" class="control-group" style="display:none;">
                        <label for="qr-content">内容：</label>
                        <textarea id="qr-content" rows="3" placeholder="输入要编码的内容"></textarea>
                    </div>
                    
                    <div id="wifi-input-group" class="control-group" style="display:none;">
                        <label for="wifi-ssid">WiFi名称(SSID)：</label>
                        <input type="text" id="wifi-ssid" placeholder="输入WiFi名称">
                        
                        <label for="wifi-password" style="margin-top:0.5rem;">WiFi密码：</label>
                        <input type="text" id="wifi-password" placeholder="输入WiFi密码(可选)">
                    </div>
                    
                    <div id="geo-input-group" class="control-group" style="display:none;">
                        <label for="geo-lat">纬度：</label>
                        <input type="number" id="geo-lat" min="-90" max="90" step="0.000001" placeholder="输入纬度(-90到90)">
                        
                        <label for="geo-lng" style="margin-top:0.5rem;">经度：</label>
                        <input type="number" id="geo-lng" min="-180" max="180" step="0.000001" placeholder="输入经度(-180到180)">
                    </div>
                    
                    <div class="control-row">
                        <div class="control-group">
                            <label for="qr-size">尺寸：</label>
                            <input type="range" id="qr-size" min="100" max="500" value="200">
                            <span id="qr-size-value">200px</span>
                        </div>
                        
                        <div class="control-group">
                            <label for="qr-color">颜色：</label>
                            <input type="color" id="qr-color" value="#000000">
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="qr-ecc">纠错等级：</label>
                        <select id="qr-ecc" class="form-control">
                            <option value="L">低 (7%)</option>
                            <option value="M">中 (15%)</option>
                            <option value="Q">高 (25%)</option>
                            <option value="H">最高 (30%)</option>
                        </select>
                    </div>
                    
                    <div class="control-group">
                        <button id="generate-btn" class="use-btn">生成二维码</button>
                        <button id="download-btn" class="use-btn">下载二维码</button>
                    </div>
                </div>
                
                <div class="qrcode-preview">
                    <div id="qrcode"></div>
                </div>
            </div>
        </div>
    </main>
    
    <div id="footer-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
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
            
        // 二维码生成逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const qrType = document.getElementById('qr-type');
            const qrContent = document.getElementById('qr-content');
            const qrSize = document.getElementById('qr-size');
            const qrSizeValue = document.getElementById('qr-size-value');
            const qrColor = document.getElementById('qr-color');
            const qrEcc = document.getElementById('qr-ecc');
            const generateBtn = document.getElementById('generate-btn');
            const downloadBtn = document.getElementById('download-btn');
            const qrcodeDiv = document.getElementById('qrcode');
            
            // 更新尺寸显示
            qrSize.addEventListener('input', () => {
                qrSizeValue.textContent = `${qrSize.value}px`;
            });
            
            // 生成二维码
            generateBtn.addEventListener('click', generateQRCode);
            
            // 下载二维码
            downloadBtn.addEventListener('click', downloadQRCode);
            
            // 根据类型切换输入格式
            qrType.addEventListener('change', updateInputFields);
            
            function updateInputFields() {
                const type = qrType.value;
                const textGroup = document.getElementById('text-input-group');
                const wifiGroup = document.getElementById('wifi-input-group');
                const geoGroup = document.getElementById('geo-input-group');
                
                // 隐藏所有输入组
                textGroup.style.display = 'none';
                wifiGroup.style.display = 'none';
                geoGroup.style.display = 'none';
                
                switch(type) {
                    case 'text':
                    case 'url':
                        textGroup.style.display = 'flex';
                        const label = textGroup.querySelector('label');
                        const textarea = textGroup.querySelector('textarea');
                        
                        label.textContent = type === 'text' ? '文本内容：' : '网址：';
                        textarea.placeholder = type === 'text' 
                            ? '输入要编码的文本内容' 
                            : '输入网址 (可包含或不包含http://)';
                        textarea.value = '';
                        break;
                        
                    case 'wifi':
                        wifiGroup.style.display = 'flex';
                        document.getElementById('wifi-ssid').value = '';
                        document.getElementById('wifi-password').value = '';
                        break;
                        
                    case 'geo':
                        geoGroup.style.display = 'flex';
                        document.getElementById('geo-lat').value = '';
                        document.getElementById('geo-lng').value = '';
                        break;
                }
            }
            
            function generateQRCode() {
                const content = getFormattedContent();
                const size = parseInt(qrSize.value);
                const color = qrColor.value;
                const eccLevel = qrEcc.value;
                
                qrcodeDiv.innerHTML = '';
                const canvas = document.createElement('canvas');
                qrcodeDiv.appendChild(canvas);
                
                QRCode.toCanvas(canvas, content, {
                    width: size,
                    color: {
                        dark: color,
                        light: '#ffffff'
                    },
                    errorCorrectionLevel: eccLevel
                }, function(error) {
                    if (error) {
                        alert('生成二维码失败: ' + error);
                    }
                });
            }
            
            function downloadQRCode() {
                const canvas = qrcodeDiv.querySelector('canvas');
                if (!canvas) {
                    alert('请先生成二维码');
                    return;
                }
                
                const link = document.createElement('a');
                link.download = 'qrcode.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            }
            
            function getFormattedContent() {
                const type = qrType.value;
                
                switch(type) {
                    case 'text':
                        return document.getElementById('qr-content').value.trim();
                        
                    case 'url':
                        const url = document.getElementById('qr-content').value.trim();
                        return url.startsWith('http') ? url : `https://${url}`;
                        
                    case 'wifi':
                        const ssid = document.getElementById('wifi-ssid').value.trim();
                        const password = document.getElementById('wifi-password').value.trim();
                        if (!ssid) {
                            alert('请输入WiFi名称');
                            throw new Error('Missing WiFi SSID');
                        }
                        return `WIFI:T:WPA;S:${ssid};P:${password};H:;`;
                        
                    case 'geo':
                        const lat = parseFloat(document.getElementById('geo-lat').value);
                        const lng = parseFloat(document.getElementById('geo-lng').value);
                        
                        if (isNaN(lat) || isNaN(lng)) {
                            alert('请输入有效的经纬度');
                            throw new Error('Invalid geo coordinates');
                        }
                        
                        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                            alert('经纬度超出有效范围\n纬度:-90到90\n经度:-180到180');
                            throw new Error('Geo coordinates out of range');
                        }
                        
                        return `geo:${lat},${lng}`;
                        
                    default:
                        return '';
                }
            }
            
            // 初始化输入字段
            updateInputFields();
        });
    </script>
</body>
</html>

