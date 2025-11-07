<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>时间戳转换工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .timestamp-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .timestamp-options {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .timestamp-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        input[type="text"], input[type="datetime-local"] {
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
        
        input[type="text"]:focus, input[type="datetime-local"]:focus {
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
        
        .timestamp-buttons {
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
        
        .timestamp-results {
            margin-top: 1.5rem;
        }
        
        .result-item {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 6px;
            background-color: #f8f9fa;
        }
        
        .result-item h3 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: #2c3e50;
        }
        
        .result-item p {
            margin: 0;
            font-family: monospace;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>时间戳转换工具</h1>
            <div class="info-card">
                <div class="timestamp-controls">
                    <div class="timestamp-options">
                        <div class="timestamp-option">
                            <input type="radio" id="timestamp-to-date" name="conversion-type" value="timestamp-to-date" checked>
                            <label for="timestamp-to-date">时间戳转日期</label>
                        </div>
                        <div class="timestamp-option">
                            <input type="radio" id="date-to-timestamp" name="conversion-type" value="date-to-timestamp">
                            <label for="date-to-timestamp">日期转时间戳</label>
                        </div>
                    </div>
                    
                    <div class="control-group" id="timestamp-input-group">
                        <label for="timestamp">时间戳：</label>
                        <input type="text" id="timestamp" placeholder="输入秒级或毫秒级时间戳">
                    </div>
                    
                    <div class="control-group" id="date-input-group" style="display:none;">
                        <label for="date">日期时间：</label>
                        <input type="datetime-local" id="date">
                    </div>
                    
                    <div class="timestamp-buttons">
                        <button id="convert-btn" class="use-btn">转换</button>
                        <button id="current-time-btn" class="use-btn secondary">当前时间</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                    </div>
                    
                    <div id="error-message" class="error-message" style="display:none;"></div>
                    
                    <div class="timestamp-results" id="timestamp-results" style="display:none;">
                        <div class="result-item">
                            <h3>本地时间：</h3>
                            <p id="local-time"></p>
                        </div>
                        <div class="result-item">
                            <h3>UTC时间：</h3>
                            <p id="utc-time"></p>
                        </div>
                        <div class="result-item">
                            <h3>时间戳：</h3>
                            <p id="timestamp-result"></p>
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
            
        // 时间戳转换逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const timestampToDateRadio = document.getElementById('timestamp-to-date');
            const dateToTimestampRadio = document.getElementById('date-to-timestamp');
            const timestampInputGroup = document.getElementById('timestamp-input-group');
            const dateInputGroup = document.getElementById('date-input-group');
            const timestampInput = document.getElementById('timestamp');
            const dateInput = document.getElementById('date');
            const convertBtn = document.getElementById('convert-btn');
            const currentTimeBtn = document.getElementById('current-time-btn');
            const clearBtn = document.getElementById('clear-btn');
            const errorMessage = document.getElementById('error-message');
            const timestampResults = document.getElementById('timestamp-results');
            const localTimeResult = document.getElementById('local-time');
            const utcTimeResult = document.getElementById('utc-time');
            const timestampResult = document.getElementById('timestamp-result');
            
            // 切换转换类型
            timestampToDateRadio.addEventListener('change', function() {
                if (this.checked) {
                    timestampInputGroup.style.display = 'flex';
                    dateInputGroup.style.display = 'none';
                }
            });
            
            dateToTimestampRadio.addEventListener('change', function() {
                if (this.checked) {
                    timestampInputGroup.style.display = 'none';
                    dateInputGroup.style.display = 'flex';
                }
            });
            
            // 转换按钮
            convertBtn.addEventListener('click', convertTimestamp);
            
            // 当前时间按钮
            currentTimeBtn.addEventListener('click', setCurrentTime);
            
            // 清空按钮
            clearBtn.addEventListener('click', clearData);
            
            // 转换时间戳
            function convertTimestamp() {
                errorMessage.style.display = 'none';
                timestampResults.style.display = 'none';
                
                try {
                    if (timestampToDateRadio.checked) {
                        // 时间戳转日期
                        const timestamp = timestampInput.value.trim();
                        if (!timestamp) {
                            showError('请输入时间戳');
                            return;
                        }
                        
                        let date;
                        if (timestamp.length === 10) {
                            // 秒级时间戳
                            date = new Date(parseInt(timestamp) * 1000);
                        } else if (timestamp.length === 13) {
                            // 毫秒级时间戳
                            date = new Date(parseInt(timestamp));
                        } else {
                            showError('时间戳应为10位(秒)或13位(毫秒)');
                            return;
                        }
                        
                        if (isNaN(date.getTime())) {
                            showError('无效的时间戳');
                            return;
                        }
                        
                        displayResults(date);
                    } else {
                        // 日期转时间戳
                        const dateString = dateInput.value;
                        if (!dateString) {
                            showError('请选择日期时间');
                            return;
                        }
                        
                        const date = new Date(dateString);
                        if (isNaN(date.getTime())) {
                            showError('无效的日期时间');
                            return;
                        }
                        
                        displayResults(date);
                    }
                } catch (e) {
                    showError('转换失败: ' + e.message);
                }
            }
            
            // 显示结果
            function displayResults(date) {
                const localTime = date.toLocaleString();
                const utcTime = date.toUTCString();
                const timestampInSeconds = Math.floor(date.getTime() / 1000);
                const timestampInMilliseconds = date.getTime();
                
                localTimeResult.textContent = localTime;
                utcTimeResult.textContent = utcTime;
                timestampResult.textContent = `秒: ${timestampInSeconds}\n毫秒: ${timestampInMilliseconds}`;
                
                timestampResults.style.display = 'block';
            }
            
            // 设置当前时间
            function setCurrentTime() {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                
                if (timestampToDateRadio.checked) {
                    timestampInput.value = Math.floor(now.getTime() / 1000);
                } else {
                    dateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
                }
            }
            
            // 清空数据
            function clearData() {
                timestampInput.value = '';
                dateInput.value = '';
                errorMessage.style.display = 'none';
                timestampResults.style.display = 'none';
            }
            
            // 显示错误
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html>
