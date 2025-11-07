<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTTP请求测试工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .http-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .http-methods {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .http-method {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e0e0e0;
            background-color: #f8f9fa;
        }
        
        .http-method:hover {
            background-color: #e9ecef;
        }
        
        .http-method.active {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        input[type="text"], input[type="number"], select {
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
        
        input[type="text"]:focus, input[type="number"]:focus, select:focus {
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
        
        .http-headers {
            margin-top: 1rem;
        }
        
        .header-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .header-row input {
            flex: 1;
        }
        
        .header-row button {
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .http-buttons {
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
        
        .response-container {
            margin-top: 1.5rem;
        }
        
        .response-tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 0.5rem;
        }
        
        .response-tab {
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        
        .response-tab.active {
            border-bottom-color: #3498db;
            color: #3498db;
            font-weight: 500;
        }
        
        .response-content {
            min-height: 200px;
            max-height: 400px;
            overflow: auto;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background-color: #f8f9fa;
            font-family: monospace;
            white-space: pre-wrap;
        }
        
        .response-info {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .add-header-btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 0.5rem;
        }
        
        .add-header-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>HTTP请求测试工具</h1>
            <div class="info-card">
                <div class="http-controls">
                    <div class="http-methods">
                        <div class="http-method active" data-method="GET">GET</div>
                        <div class="http-method" data-method="POST">POST</div>
                        <div class="http-method" data-method="PUT">PUT</div>
                        <div class="http-method" data-method="DELETE">DELETE</div>
                        <div class="http-method" data-method="PATCH">PATCH</div>
                        <div class="http-method" data-method="HEAD">HEAD</div>
                    </div>
                    
                    <div class="control-group">
                        <label for="url">请求URL：</label>
                        <input type="text" id="url" placeholder="https://api.example.com/endpoint">
                    </div>
                    
                    <div class="control-group" id="request-body-group" style="display:none;">
                        <label for="request-body">请求体：</label>
                        <textarea id="request-body" placeholder="输入请求体内容（JSON/XML/文本）"></textarea>
                    </div>
                    
                    <div class="http-headers">
                        <label>请求头：</label>
                        <div id="headers-container">
                            <div class="header-row">
                                <input type="text" placeholder="Header名称" class="header-name">
                                <input type="text" placeholder="Header值" class="header-value">
                                <button class="remove-header">×</button>
                            </div>
                        </div>
                        <button id="add-header" class="add-header-btn">添加请求头</button>
                    </div>
                    
                    <div class="control-group">
                        <label for="timeout">超时时间（毫秒）：</label>
                        <input type="number" id="timeout" value="5000" min="1000" max="30000">
                    </div>
                    
                    <div class="http-buttons">
                        <button id="send-btn" class="use-btn">发送请求</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                    </div>
                    
                    <div class="response-container">
                        <div class="response-tabs">
                            <div class="response-tab active" data-tab="response">响应</div>
                            <div class="response-tab" data-tab="headers">响应头</div>
                            <div class="response-tab" data-tab="cookies">Cookies</div>
                        </div>
                        
                        <div class="response-content" id="response-content"></div>
                        <div class="response-info" id="response-info"></div>
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
            
        // HTTP请求测试逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const httpMethods = document.querySelectorAll('.http-method');
            const urlInput = document.getElementById('url');
            const requestBodyGroup = document.getElementById('request-body-group');
            const requestBody = document.getElementById('request-body');
            const headersContainer = document.getElementById('headers-container');
            const addHeaderBtn = document.getElementById('add-header');
            const timeoutInput = document.getElementById('timeout');
            const sendBtn = document.getElementById('send-btn');
            const clearBtn = document.getElementById('clear-btn');
            const responseTabs = document.querySelectorAll('.response-tab');
            const responseContent = document.getElementById('response-content');
            const responseInfo = document.getElementById('response-info');
            
            let currentMethod = 'GET';
            let currentTab = 'response';
            
            // 切换HTTP方法
            httpMethods.forEach(method => {
                method.addEventListener('click', function() {
                    httpMethods.forEach(m => m.classList.remove('active'));
                    this.classList.add('active');
                    currentMethod = this.dataset.method;
                    
                    // 显示/隐藏请求体输入框
                    if (currentMethod === 'POST' || currentMethod === 'PUT' || currentMethod === 'PATCH') {
                        requestBodyGroup.style.display = 'flex';
                    } else {
                        requestBodyGroup.style.display = 'none';
                    }
                });
            });
            
            // 添加请求头
            addHeaderBtn.addEventListener('click', function() {
                const headerRow = document.createElement('div');
                headerRow.className = 'header-row';
                headerRow.innerHTML = `
                    <input type="text" placeholder="Header名称" class="header-name">
                    <input type="text" placeholder="Header值" class="header-value">
                    <button class="remove-header">×</button>
                `;
                headersContainer.appendChild(headerRow);
                
                // 绑定删除按钮事件
                headerRow.querySelector('.remove-header').addEventListener('click', function() {
                    headersContainer.removeChild(headerRow);
                });
            });
            
            // 切换响应标签页
            responseTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    responseTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentTab = this.dataset.tab;
                    updateResponseDisplay();
                });
            });
            
            // 发送请求
            sendBtn.addEventListener('click', sendRequest);
            
            // 清空
            clearBtn.addEventListener('click', clearData);
            
            // 发送HTTP请求
            async function sendRequest() {
                const url = urlInput.value.trim();
                if (!url) {
                    showError('请输入请求URL');
                    return;
                }
                
                try {
                    // 收集请求头
                    const headers = {};
                    const headerRows = headersContainer.querySelectorAll('.header-row');
                    headerRows.forEach(row => {
                        const name = row.querySelector('.header-name').value.trim();
                        const value = row.querySelector('.header-value').value.trim();
                        if (name && value) {
                            headers[name] = value;
                        }
                    });
                    
                    // 设置请求选项
                    const options = {
                        method: currentMethod,
                        headers: headers,
                        timeout: parseInt(timeoutInput.value)
                    };
                    
                    // 添加请求体
                    if (currentMethod === 'POST' || currentMethod === 'PUT' || currentMethod === 'PATCH') {
                        options.body = requestBody.value;
                        
                        // 如果没有Content-Type头，自动添加
                        if (!headers['Content-Type'] && !headers['content-type']) {
                            options.headers['Content-Type'] = 'application/json';
                        }
                    }
                    
                    // 显示加载状态
                    responseContent.textContent = '发送请求中...';
                    responseInfo.textContent = '';
                    
                    // 发送请求
                    const startTime = Date.now();
                    let response;
                    try {
                        response = await fetchWithTimeout(url, options);
                        
                        // 检查响应状态
                        if (!response.ok) {
                            throw new Error(`HTTP错误 ${response.status}: ${response.statusText}`);
                        }
                    } catch (error) {
                        const endTime = Date.now();
                        const responseData = {
                            error: error.message,
                            time: endTime - startTime
                        };
                        sessionStorage.setItem('httpResponse', JSON.stringify(responseData));
                        updateResponseDisplay();
                        throw error;
                    }
                    const endTime = Date.now();
                    
                    // 处理响应
                    const responseData = {
                        status: response.status,
                        statusText: response.statusText,
                        headers: {},
                        cookies: [],
                        body: '',
                        time: endTime - startTime
                    };
                    
                    // 收集响应头
                    response.headers.forEach((value, name) => {
                        responseData.headers[name] = value;
                    });
                    
                    // 收集Cookies
                    const cookiesHeader = response.headers.get('set-cookie');
                    if (cookiesHeader) {
                        responseData.cookies = cookiesHeader.split('; ');
                    }
                    
                    // 读取响应体
                    try {
                        responseData.body = await response.text();
                        try {
                            // 尝试解析为JSON
                            responseData.body = JSON.parse(responseData.body);
                            responseData.body = JSON.stringify(responseData.body, null, 2);
                        } catch (e) {
                            // 不是JSON，保持原样
                        }
                    } catch (e) {
                        responseData.body = '无法读取响应体';
                    }
                    
                    // 存储响应数据
                    sessionStorage.setItem('httpResponse', JSON.stringify(responseData));
                    
                    // 更新显示
                    updateResponseDisplay();
                    
                    // 显示响应信息
                    responseInfo.innerHTML = `
                        <p>状态码: ${responseData.status} ${responseData.statusText}</p>
                        <p>耗时: ${responseData.time}ms</p>
                        <p>大小: ${responseData.body.length} 字节</p>
                    `;
                } catch (error) {
                    console.error('HTTP请求错误:', error);
                    responseContent.textContent = `请求失败: ${error.message}\n\n详细错误信息:\n${error.stack || '无堆栈信息'}`;
                    responseInfo.textContent = '';
                    
                    // 如果是网络错误，提供更多帮助信息
                    if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
                        responseContent.textContent += '\n\n可能的解决方案:\n';
                        responseContent.textContent += '1. 检查URL是否正确\n';
                        responseContent.textContent += '2. 检查网络连接\n';
                        responseContent.textContent += '3. 目标服务器可能不支持CORS\n';
                        responseContent.textContent += '4. 尝试增加超时时间\n';
                    }
                }
            }
            
            // 更新响应显示
            function updateResponseDisplay() {
                const responseData = JSON.parse(sessionStorage.getItem('httpResponse') || '{}');
                
                if (responseData.error) {
                    responseContent.textContent = `请求错误: ${responseData.error}\n耗时: ${responseData.time}ms`;
                    return;
                }
                
                switch (currentTab) {
                    case 'response':
                        responseContent.textContent = responseData.body || '无响应体';
                        break;
                    case 'headers':
                        if (responseData.headers) {
                            let headersText = '';
                            for (const [name, value] of Object.entries(responseData.headers)) {
                                headersText += `${name}: ${value}\n`;
                            }
                            responseContent.textContent = headersText || '无响应头';
                        } else {
                            responseContent.textContent = '无响应头';
                        }
                        break;
                    case 'cookies':
                        if (responseData.cookies && responseData.cookies.length > 0) {
                            responseContent.textContent = responseData.cookies.join('\n');
                        } else {
                            responseContent.textContent = '无Cookies';
                        }
                        break;
                }
            }
            
            // 带超时的fetch
            async function fetchWithTimeout(url, options) {
                const controller = new AbortController();
                const timeout = options.timeout || 5000;
                
                const timeoutId = setTimeout(() => {
                    controller.abort();
                }, timeout);
                
                try {
                    const response = await fetch(url, {
                        ...options,
                        signal: controller.signal
                    });
                    
                    clearTimeout(timeoutId);
                    return response;
                } catch (error) {
                    clearTimeout(timeoutId);
                    throw error;
                }
            }
            
            // 清空数据
            function clearData() {
                urlInput.value = '';
                requestBody.value = '';
                
                // 保留第一个请求头行，清空其他
                const headerRows = headersContainer.querySelectorAll('.header-row');
                for (let i = 1; i < headerRows.length; i++) {
                    headersContainer.removeChild(headerRows[i]);
                }
                
                // 清空第一个请求头行
                if (headerRows[0]) {
                    headerRows[0].querySelector('.header-name').value = '';
                    headerRows[0].querySelector('.header-value').value = '';
                }
                
                timeoutInput.value = '5000';
                responseContent.textContent = '';
                responseInfo.textContent = '';
                sessionStorage.removeItem('httpResponse');
            }
            
            function showError(message) {
                responseContent.textContent = message;
                responseInfo.textContent = '';
            }
        });
    </script>
</body>
</html>
