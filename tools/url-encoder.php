<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL编码/解码工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .url-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .url-options {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .url-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        textarea {
            min-height: 150px;
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
        
        .url-buttons {
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
        
        .url-examples {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>URL编码/解码工具</h1>
            <div class="info-card">
                <div class="url-controls">
                    <div class="url-options">
                        <div class="url-option">
                            <input type="radio" id="encode-url" name="url-action" value="encode" checked>
                            <label for="encode-url">编码</label>
                        </div>
                        <div class="url-option">
                            <input type="radio" id="decode-url" name="url-action" value="decode">
                            <label for="decode-url">解码</label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="input-url">输入内容：</label>
                        <textarea id="input-url" placeholder="在此输入要编码或解码的内容"></textarea>
                    </div>
                    
                    <div class="url-buttons">
                        <button id="process-btn" class="use-btn">处理</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                        <button id="copy-btn" class="use-btn secondary">复制结果</button>
                    </div>
                    
                    <div class="control-group">
                        <label for="output-url">输出结果：</label>
                        <textarea id="output-url" placeholder="处理结果将显示在这里" readonly></textarea>
                        <div id="error-message" class="error-message" style="display:none;"></div>
                    </div>
                    
                    <div class="url-examples">
                        <p><strong>示例：</strong></p>
                        <p>编码: "hello world" → "hello%20world"</p>
                        <p>解码: "hello%20world" → "hello world"</p>
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
            
        // URL编码/解码逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const processBtn = document.getElementById('process-btn');
            const clearBtn = document.getElementById('clear-btn');
            const copyBtn = document.getElementById('copy-btn');
            const inputUrl = document.getElementById('input-url');
            const outputUrl = document.getElementById('output-url');
            const errorMessage = document.getElementById('error-message');
            const urlAction = document.getElementsByName('url-action');
            
            processBtn.addEventListener('click', processUrl);
            clearBtn.addEventListener('click', clearData);
            copyBtn.addEventListener('click', copyResult);
            
            function processUrl() {
                const input = inputUrl.value.trim();
                if (!input) {
                    showError('请输入要处理的内容');
                    return;
                }
                
                errorMessage.style.display = 'none';
                
                try {
                    const selectedAction = Array.from(urlAction).find(radio => radio.checked).value;
                    let result;
                    
                    if (selectedAction === 'encode') {
                        // URL编码
                        result = encodeURIComponent(input);
                    } else {
                        // URL解码
                        result = decodeURIComponent(input);
                    }
                    
                    outputUrl.value = result;
                } catch (e) {
                    showError('处理失败: ' + e.message);
                }
            }
            
            function clearData() {
                inputUrl.value = '';
                outputUrl.value = '';
                errorMessage.style.display = 'none';
            }
            
            function copyResult() {
                if (!outputUrl.value) {
                    showError('没有可复制的内容');
                    return;
                }
                
                outputUrl.select();
                document.execCommand('copy');
                alert('已复制到剪贴板');
            }
            
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html>
