<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP地址查询 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .ip-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .ip-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .input-area {
            flex: 1;
            min-width: 300px;
        }
        
        .result-area {
            flex: 1;
            min-width: 300px;
            padding: 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background-color: #f8f9fa;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        
        .ip-buttons {
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
        
        .info-item {
            margin-bottom: 0.75rem;
        }
        
        .info-label {
            font-weight: 500;
            color: #2c3e50;
        }
        
        .info-value {
            color: #3498db;
        }
        
        /* 地图相关样式已移除 */
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>IP地址查询</h1>
            <div class="info-card">
                <div class="ip-controls">
                    <div class="ip-container">
                        <div class="input-area">
                            <label for="ip-input">IP地址:</label>
                            <input type="text" id="ip-input" placeholder="输入要查询的IP地址 (留空查询本机IP)">
                            
                            <div class="ip-buttons">
                                <button id="lookup-btn" class="use-btn">查询</button>
                                <button id="clear-btn" class="use-btn secondary">清空</button>
                            </div>
                        </div>
                        
                        <div class="result-area" id="result-area">
                            <p>查询结果将显示在这里...</p>
                        </div>
                    </div>
                    
                    <div id="error-message" class="error-message" style="display:none;"></div>
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
            
        // IP查询逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const ipInput = document.getElementById('ip-input');
            const lookupBtn = document.getElementById('lookup-btn');
            const clearBtn = document.getElementById('clear-btn');
            const errorMessage = document.getElementById('error-message');
            const resultArea = document.getElementById('result-area');
            
            // 按钮事件
            lookupBtn.addEventListener('click', lookupIP);
            clearBtn.addEventListener('click', clearResults);
            
            // 查询IP
            function lookupIP() {
                try {
                    const ip = ipInput.value.trim();
                    const apiUrl = ip ? 
                        `https://ipapi.co/${ip}/json/` : 
                        'https://ipapi.co/json/';
                    
                    resultArea.innerHTML = '<p>查询中，请稍候...</p>';
                    hideError();
                    
                    axios.get(apiUrl)
                        .then(response => {
                            displayResults(response.data);
                        })
                        .catch(error => {
                            showError('查询失败: ' + (error.response?.data?.error || error.message));
                            resultArea.innerHTML = '<p>查询结果将显示在这里...</p>';
                        });
                } catch (error) {
                    showError('查询过程中发生错误: ' + error.message);
                }
            }
            
            // 显示查询结果
            function displayResults(data) {
                let html = `
                    <div class="info-item">
                        <span class="info-label">IP地址:</span>
                        <span class="info-value">${data.ip || '未知'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">国家:</span>
                        <span class="info-value">${data.country_name || '未知'} (${data.country || '未知'})</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">地区:</span>
                        <span class="info-value">${data.region || '未知'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">城市:</span>
                        <span class="info-value">${data.city || '未知'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">邮政编码:</span>
                        <span class="info-value">${data.postal || '未知'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">经纬度:</span>
                        <span class="info-value">${data.latitude || '未知'}, ${data.longitude || '未知'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">时区:</span>
                        <span class="info-value">${data.timezone || '未知'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">运营商:</span>
                        <span class="info-value">${data.org || '未知'}</span>
                    </div>
                    <!-- 地图预览功能已移除 -->
                    <p class="info-note" style="margin-top:1rem;font-size:0.8rem;color:#6c757d;">
                        数据来自 ipapi.co API，仅供参考
                    </p>
                `;
                
                resultArea.innerHTML = html;
                
                // 地图预览功能已移除
            }
            
            // 清空结果
            function clearResults() {
                ipInput.value = '';
                resultArea.innerHTML = '<p>查询结果将显示在这里...</p>';
                hideError();
            }
            
            // 显示错误信息
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
            
            // 隐藏错误信息
            function hideError() {
                errorMessage.style.display = 'none';
            }
        });
    </script>
</body>
</html>
