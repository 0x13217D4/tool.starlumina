<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>中文域名转换工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .idn-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .idn-options {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .idn-option {
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
        
        .idn-buttons {
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
        
        .idn-examples {
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
            <h1>中文域名转换工具</h1>
            <div class="info-card">
                <div class="idn-controls">
                    <div class="idn-options">
                        <div class="idn-option">
                            <input type="radio" id="to-punycode" name="idn-action" value="to-punycode" checked>
                            <label for="to-punycode">中文转Punycode</label>
                        </div>
                        <div class="idn-option">
                            <input type="radio" id="from-punycode" name="idn-action" value="from-punycode">
                            <label for="from-punycode">Punycode转中文</label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="input-idn">输入域名：</label>
                        <textarea id="input-idn" placeholder="在此输入中文域名或Punycode编码"></textarea>
                    </div>
                    
                    <div class="idn-buttons">
                        <button id="convert-btn" class="use-btn">转换</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                        <button id="copy-btn" class="use-btn secondary">复制结果</button>
                    </div>
                    
                    <div class="control-group">
                        <label for="output-idn">转换结果：</label>
                        <textarea id="output-idn" placeholder="转换结果将显示在这里" readonly></textarea>
                        <div id="error-message" class="error-message" style="display:none;"></div>
                    </div>
                    
                    <div class="idn-examples">
                        <p><strong>示例：</strong></p>
                        <p>中文转Punycode: "中文.com" → "xn--fiq228c.com"</p>
                        <p>Punycode转中文: "xn--fiq228c.com" → "中文.com"</p>
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
            
        // IDN转换逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const inputIdn = document.getElementById('input-idn');
            const outputIdn = document.getElementById('output-idn');
            const convertBtn = document.getElementById('convert-btn');
            const clearBtn = document.getElementById('clear-btn');
            const copyBtn = document.getElementById('copy-btn');
            const errorMessage = document.getElementById('error-message');
            const actionRadios = document.querySelectorAll('input[name="idn-action"]');
            
            // 转换按钮点击事件
            convertBtn.addEventListener('click', convertIdn);
            
            // 清空按钮点击事件
            clearBtn.addEventListener('click', clearFields);
            
            // 复制按钮点击事件
            copyBtn.addEventListener('click', copyResult);
            
            // 输入框回车键触发转换
            inputIdn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    convertIdn();
                }
            });
            
            function convertIdn() {
                const inputText = inputIdn.value.trim();
                const action = document.querySelector('input[name="idn-action"]:checked').value;
                
                if (!inputText) {
                    showError('请输入要转换的内容');
                    return;
                }
                
                try {
                    let result = '';
                    if (action === 'to-punycode') {
                        // 中文转Punycode
                        result = toPunycode(inputText);
                    } else {
                        // Punycode转中文
                        result = fromPunycode(inputText);
                    }
                    
                    outputIdn.value = result;
                    errorMessage.style.display = 'none';
                } catch (error) {
                    showError('转换失败: ' + error.message);
                }
            }
            
            function toPunycode(text) {
                // 处理完整URL
                if (/^https?:\/\//i.test(text)) {
                    const url = new URL(text);
                    return url.protocol + '//' + punycode.toASCII(url.hostname) + url.pathname + url.search + url.hash;
                }
                
                // 处理域名部分
                if (/\./.test(text)) {
                    return text.split('.').map(part => {
                        return /[^\x00-\x7F]/.test(part) ? 'xn--' + punycode.encode(part) : part;
                    }).join('.');
                }
                
                // 处理纯中文
                return 'xn--' + punycode.encode(text);
            }
            
            function fromPunycode(text) {
                // 处理完整URL
                if (/^https?:\/\//i.test(text)) {
                    const url = new URL(text);
                    return url.protocol + '//' + punycode.toUnicode(url.hostname) + url.pathname + url.search + url.hash;
                }
                
                // 处理域名部分
                if (/\./.test(text)) {
                    return text.split('.').map(part => {
                        return part.startsWith('xn--') ? punycode.decode(part.substring(4)) : part;
                    }).join('.');
                }
                
                // 处理纯Punycode
                if (text.startsWith('xn--')) {
                    return punycode.decode(text.substring(4));
                }
                
                throw new Error('无效的Punycode编码');
            }
            
            function clearFields() {
                inputIdn.value = '';
                outputIdn.value = '';
                errorMessage.style.display = 'none';
            }
            
            function copyResult() {
                if (!outputIdn.value) {
                    showError('没有可复制的内容');
                    return;
                }
                
                outputIdn.select();
                document.execCommand('copy');
                
                // 显示复制成功提示
                const originalText = copyBtn.textContent;
                copyBtn.textContent = '已复制!';
                setTimeout(() => {
                    copyBtn.textContent = originalText;
                }, 2000);
            }
            
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
                outputIdn.value = '';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/punycode@2.1.1/punycode.js"></script>
</body>
</html>
