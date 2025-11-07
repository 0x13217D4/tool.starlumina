<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JSON格式化工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .json-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .json-options {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .json-option {
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
            min-height: 200px;
            resize: vertical;
            margin: 0;
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
        
        .json-buttons {
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
        
        .json-result {
            margin-top: 1.5rem;
        }
        
        .result-tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 0.5rem;
        }
        
        .result-tab {
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        
        .result-tab.active {
            border-bottom-color: #3498db;
            color: #3498db;
            font-weight: 500;
        }
        
        .result-content {
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
        
        .json-stats {
            margin-top: 0.5rem;
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
            <h1>JSON格式化工具</h1>
            <div class="info-card">
                <div class="json-controls">
                    <div class="json-options">
                        <div class="json-option">
                            <input type="radio" id="format-json" name="json-action" value="format" checked>
                            <label for="format-json">格式化</label>
                        </div>
                        <div class="json-option">
                            <input type="radio" id="minify-json" name="json-action" value="minify">
                            <label for="minify-json">压缩</label>
                        </div>
                        <div class="json-option">
                            <input type="radio" id="validate-json" name="json-action" value="validate">
                            <label for="validate-json">验证</label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="input-json">输入JSON：</label>
                        <textarea id="input-json" placeholder="在此粘贴JSON内容"></textarea>
                    </div>
                    
                    <div class="json-buttons">
                        <button id="process-btn" class="use-btn">处理JSON</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                        <button id="copy-btn" class="use-btn secondary">复制结果</button>
                    </div>
                    
                    <div id="error-message" class="error-message" style="display:none;"></div>
                    
                    <div class="json-result" id="json-result" style="display:none;">
                        <div class="result-tabs">
                            <div class="result-tab active" data-tab="formatted">格式化</div>
                            <div class="result-tab" data-tab="minified">压缩</div>
                        </div>
                        
                        <div class="result-content" id="result-content"></div>
                        <div class="json-stats" id="json-stats"></div>
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
            
        // JSON格式化逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const formatRadio = document.getElementById('format-json');
            const minifyRadio = document.getElementById('minify-json');
            const validateRadio = document.getElementById('validate-json');
            const inputJson = document.getElementById('input-json');
            const processBtn = document.getElementById('process-btn');
            const clearBtn = document.getElementById('clear-btn');
            const copyBtn = document.getElementById('copy-btn');
            const errorMessage = document.getElementById('error-message');
            const jsonResult = document.getElementById('json-result');
            const resultTabs = document.querySelectorAll('.result-tab');
            const resultContent = document.getElementById('result-content');
            const jsonStats = document.getElementById('json-stats');
            
            let currentTab = 'formatted';
            let formattedJson = '';
            let minifiedJson = '';
            
            // 切换标签页
            resultTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    resultTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentTab = this.dataset.tab;
                    updateResultDisplay();
                });
            });
            
            // 处理JSON
            processBtn.addEventListener('click', processJson);
            
            // 清空
            clearBtn.addEventListener('click', clearData);
            
            // 复制结果
            copyBtn.addEventListener('click', copyResult);
            
            // 处理JSON
            function processJson() {
                const jsonString = inputJson.value.trim();
                if (!jsonString) {
                    showError('请输入JSON内容');
                    return;
                }
                
                errorMessage.style.display = 'none';
                jsonResult.style.display = 'none';
                
                try {
                    // 解析JSON
                    const jsonObj = JSON.parse(jsonString);
                    
                    // 格式化JSON
                    formattedJson = JSON.stringify(jsonObj, null, 4);
                    
                    // 压缩JSON
                    minifiedJson = JSON.stringify(jsonObj);
                    
                    // 显示结果
                    if (validateRadio.checked) {
                        resultContent.textContent = 'JSON有效';
                        jsonStats.textContent = `对象类型: ${getType(jsonObj)}, 大小: ${minifiedJson.length} 字节`;
                    } else if (formatRadio.checked) {
                        resultContent.textContent = formattedJson;
                        jsonStats.textContent = `大小: ${minifiedJson.length} 字节 → ${formattedJson.length} 字节`;
                    } else if (minifyRadio.checked) {
                        resultContent.textContent = minifiedJson;
                        jsonStats.textContent = `大小: ${formattedJson.length} 字节 → ${minifiedJson.length} 字节`;
                    }
                    
                    jsonResult.style.display = 'block';
                } catch (e) {
                    showError('JSON解析错误: ' + e.message);
                }
            }
            
            // 更新结果显示
            function updateResultDisplay() {
                if (currentTab === 'formatted') {
                    resultContent.textContent = formattedJson;
                } else {
                    resultContent.textContent = minifiedJson;
                }
            }
            
            // 获取对象类型
            function getType(obj) {
                if (Array.isArray(obj)) {
                    return '数组';
                } else if (obj === null) {
                    return 'null';
                } else {
                    return typeof obj === 'object' ? '对象' : typeof obj;
                }
            }
            
            // 清空数据
            function clearData() {
                inputJson.value = '';
                errorMessage.style.display = 'none';
                jsonResult.style.display = 'none';
            }
            
            // 复制结果
            function copyResult() {
                if (!resultContent.textContent) {
                    showError('没有可复制的内容');
                    return;
                }
                
                navigator.clipboard.writeText(resultContent.textContent).then(function() {
                    const originalText = copyBtn.textContent;
                    copyBtn.textContent = '已复制!';
                    setTimeout(function() {
                        copyBtn.textContent = originalText;
                    }, 2000);
                });
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
