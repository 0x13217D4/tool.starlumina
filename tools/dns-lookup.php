<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNS查询工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .dns-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .dns-container {
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
        
        input[type="text"], select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        
        .dns-buttons {
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
            cursor: button;
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
        
        .dns-record {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 6px;
            background-color: white;
            border: 1px solid #e0e0e0;
        }
        
        .record-header {
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }
        
        .record-content {
            font-family: monospace;
            font-size: 0.9rem;
            color: #495057;
            word-break: break-all;
        }
        
        .loading {
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>DNS查询工具</h1>
            <div class="info-card">
                <div class="dns-controls">
                    <div class="dns-container">
                        <div class="input-area">
                            <label for="domain-input">域名:</label>
                            <input type="text" id="domain-input" placeholder="输入要查询的域名或URL (如: example.com 或 https://example.com)">
                            
                            <label for="record-type" style="margin-top:1rem;display:block;">记录类型:</label>
                            <select id="record-type">
                                <option value="A">A (IPv4地址)</option>
                                <option value="AAAA">AAAA (IPv6地址)</option>
                                <option value="CNAME">CNAME (别名)</option>
                                <option value="MX">MX (邮件交换)</option>
                                <option value="TXT">TXT (文本记录)</option>
                                <option value="NS">NS (域名服务器)</option>
                                <option value="SOA">SOA (起始授权机构)</option>
                                <option value="PTR">PTR (指针记录)</option>
                                <option value="SRV">SRV (服务定位器)</option>
                                <option value="ANY">ANY (所有记录)</option>
                            </select>
                            
                            <div class="dns-buttons">
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
            
        // DNS查询逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const domainInput = document.getElementById('domain-input');
            const recordType = document.getElementById('record-type');
            const lookupBtn = document.getElementById('lookup-btn');
            const clearBtn = document.getElementById('clear-btn');
            const errorMessage = document.getElementById('error-message');
            const resultArea = document.getElementById('result-area');
            
            // 按钮事件
            lookupBtn.addEventListener('click', lookupDNS);
            clearBtn.addEventListener('click', clearResults);
            
            // 从URL中提取域名
            function extractDomain(url) {
                try {
                    // 如果输入不是URL格式，直接返回原内容
                    if (!/^https?:\/\//i.test(url)) return url;
                    
                    // 创建URL对象解析
                    const urlObj = new URL(url.includes('://') ? url : `http://${url}`);
                    return urlObj.hostname;
                } catch (e) {
                    // 解析失败返回原内容
                    return url;
                }
            }
            
            // 查询DNS记录
            function lookupDNS() {
                try {
                    const input = domainInput.value.trim();
                    const type = recordType.value;
                    
                    if (!input) {
                        throw new Error('请输入要查询的域名或URL');
                    }
                    
                    // 提取域名
                    const domain = extractDomain(input);
                    
                    // 验证域名格式
                    if (!/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/i.test(domain)) {
                        throw new Error('域名格式不正确');
                    }
                    
                    // 在结果中显示原始输入和实际查询的域名
                    if (input !== domain) {
                        resultArea.innerHTML = `
                            <div class="info-item">
                                <span class="info-label">原始输入:</span>
                                <span class="info-value">${input}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">实际查询:</span>
                                <span class="info-value">${domain}</span>
                            </div>
                            <div class="loading">查询中，请稍候...</div>
                        `;
                    } else {
                        resultArea.innerHTML = '<div class="loading">查询中，请稍候...</div>';
                    }
                    hideError();
                    
                    // 使用公共DNS查询API
                    const apiUrl = `https://dns.google/resolve?name=${encodeURIComponent(domain)}&type=${type}`;
                    
                    axios.get(apiUrl)
                        .then(response => {
                            console.log('DNS查询响应:', response.data); // 调试信息
                            // 在结果中保留原始输入信息
                            if (input !== domain) {
                                response.data.originalInput = input;
                                response.data.actualDomain = domain;
                            }
                            displayResults(response.data);
                        })
                        .catch(error => {
                            console.error('DNS查询错误:', error); // 调试信息
                            let errorMsg = '查询失败: ';
                            if (error.response) {
                                errorMsg += `HTTP ${error.response.status}: ${error.response.data?.error || error.response.statusText}`;
                            } else if (error.request) {
                                errorMsg += '网络请求失败，请检查网络连接';
                            } else {
                                errorMsg += error.message;
                            }
                            showError(errorMsg);
                            resultArea.innerHTML = '<p>查询结果将显示在这里...</p>';
                        });
                } catch (error) {
                    showError('查询过程中发生错误: ' + error.message);
                }
            }
            
            // 显示查询结果
            function displayResults(data) {
                let html = '';
                
                // 显示原始输入和实际查询的域名
                if (data.originalInput) {
                    html += `
                        <div class="info-item">
                            <span class="info-label">原始输入:</span>
                            <span class="info-value">${data.originalInput}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">实际查询:</span>
                            <span class="info-value">${data.actualDomain}</span>
                        </div>
                    `;
                }
                
                if (!data || !data.Answer && !data.Authority) {
                    html += '<p>未找到匹配的DNS记录</p>';
                    resultArea.innerHTML = html;
                    return;
                }
                
                // 处理回答部分
                if (data.Answer && data.Answer.length > 0) {
                    html += '<h3>DNS记录结果</h3>';
                    data.Answer.forEach(record => {
                        html += `
                            <div class="dns-record">
                                <div class="record-header">${record.name} (${record.type}) - TTL: ${record.TTL}</div>
                                <div class="record-content">${record.data}</div>
                            </div>
                        `;
                    });
                }
                
                // 处理权威部分
                if (data.Authority && data.Authority.length > 0) {
                    html += '<h3>权威名称服务器</h3>';
                    data.Authority.forEach(record => {
                        html += `
                            <div class="dns-record">
                                <div class="record-header">${record.name} (${record.type}) - TTL: ${record.TTL}</div>
                                <div class="record-content">${record.data}</div>
                            </div>
                        `;
                    });
                }
                
                // 添加数据来源说明
                html += '<p style="margin-top:1rem;font-size:0.8rem;color:#6c757d;">数据来自Google Public DNS API</p>';
                
                resultArea.innerHTML = html;
            }
            
            // 清空结果
            function clearResults() {
                domainInput.value = '';
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
