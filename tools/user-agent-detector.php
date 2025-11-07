<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户代理检测器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .ua-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
            margin: 0;
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
        
        textarea:focus {
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
        
        .ua-buttons {
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
        
        .ua-results {
            margin-top: 1.5rem;
        }
        
        .ua-result-item {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 6px;
            background-color: #f8f9fa;
        }
        
        .ua-result-item h3 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: #2c3e50;
        }
        
        .ua-result-item p {
            margin: 0;
            word-break: break-all;
        }
        
        .ua-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .ua-table th, .ua-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .ua-table th {
            background-color: #f8f9fa;
            font-weight: 500;
            color: #2c3e50;
            width: 30%;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>用户代理检测器</h1>
            <div class="info-card">
                <div class="ua-controls">
                    <div class="control-group">
                        <label for="user-agent">用户代理字符串：</label>
                        <textarea id="user-agent" placeholder="在此粘贴User-Agent字符串"></textarea>
                    </div>
                    
                    <div class="ua-buttons">
                        <button id="detect-btn" class="use-btn">检测</button>
                        <button id="my-ua-btn" class="use-btn secondary">我的UA</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                    </div>
                    
                    <div id="error-message" class="error-message" style="display:none;"></div>
                    
                    <div class="ua-results" id="ua-results" style="display:none;">
                        <div class="ua-result-item">
                            <h3>原始User-Agent：</h3>
                            <p id="original-ua"></p>
                        </div>
                        
                        <table class="ua-table">
                            <tr>
                                <th>浏览器</th>
                                <td id="browser"></td>
                            </tr>
                            <tr>
                                <th>浏览器版本</th>
                                <td id="browser-version"></td>
                            </tr>
                            <tr>
                                <th>操作系统</th>
                                <td id="os"></td>
                            </tr>
                            <tr>
                                <th>设备类型</th>
                                <td id="device-type"></td>
                            </tr>
                            <tr>
                                <th>设备型号</th>
                                <td id="device-model"></td>
                            </tr>
                            <tr>
                                <th>引擎</th>
                                <td id="engine"></td>
                            </tr>
                            <tr>
                                <th>CPU架构</th>
                                <td id="cpu-arch"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div id="footer-container"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/UAParser.js/1.0.2/ua-parser.min.js"></script>
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
            
        // 用户代理检测逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const userAgentInput = document.getElementById('user-agent');
            const detectBtn = document.getElementById('detect-btn');
            const myUaBtn = document.getElementById('my-ua-btn');
            const clearBtn = document.getElementById('clear-btn');
            const errorMessage = document.getElementById('error-message');
            const uaResults = document.getElementById('ua-results');
            const originalUa = document.getElementById('original-ua');
            const browser = document.getElementById('browser');
            const browserVersion = document.getElementById('browser-version');
            const os = document.getElementById('os');
            const deviceType = document.getElementById('device-type');
            const deviceModel = document.getElementById('device-model');
            const engine = document.getElementById('engine');
            const cpuArch = document.getElementById('cpu-arch');
            
            // 检测按钮
            detectBtn.addEventListener('click', detectUserAgent);
            
            // 我的UA按钮
            myUaBtn.addEventListener('click', function() {
                userAgentInput.value = navigator.userAgent;
                detectUserAgent();
            });
            
            // 清空按钮
            clearBtn.addEventListener('click', clearData);
            
            // 检测用户代理
            function detectUserAgent() {
                const uaString = userAgentInput.value.trim();
                if (!uaString) {
                    showError('请输入User-Agent字符串');
                    return;
                }
                
                errorMessage.style.display = 'none';
                
                try {
                    // 使用UAParser.js解析User-Agent
                    const parser = new UAParser();
                    parser.setUA(uaString);
                    const result = parser.getResult();
                    
                    // 显示原始UA
                    originalUa.textContent = uaString;
                    
                    // 显示解析结果
                    browser.textContent = result.browser.name || '未知';
                    browserVersion.textContent = result.browser.version || '未知';
                    os.textContent = result.os.name ? `${result.os.name} ${result.os.version || ''}`.trim() : '未知';
                    deviceType.textContent = result.device.type || '桌面设备';
                    deviceModel.textContent = result.device.model || '未知';
                    engine.textContent = result.engine.name || '未知';
                    cpuArch.textContent = result.cpu.architecture || '未知';
                    
                    uaResults.style.display = 'block';
                } catch (e) {
                    showError('解析User-Agent时出错: ' + e.message);
                }
            }
            
            // 清空数据
            function clearData() {
                userAgentInput.value = '';
                errorMessage.style.display = 'none';
                uaResults.style.display = 'none';
            }
            
            // 显示错误
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
            
            // 初始加载时自动检测当前UA
            myUaBtn.click();
        });
    </script>
</body>
</html>
