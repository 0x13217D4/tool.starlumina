<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文本差异比较 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/diff@5.0.0/dist/diff.min.js"></script>
    <style>
        .diff-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .diff-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .text-area {
            flex: 1;
            min-width: 300px;
        }
        
        .diff-result {
            flex: 1;
            min-width: 100%;
            padding: 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background-color: #f8f9fa;
            font-family: monospace;
            white-space: pre-wrap;
        }
        
        textarea {
            width: 100%;
            height: 200px;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: monospace;
            font-size: 0.95rem;
            resize: vertical;
        }
        
        .diff-options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .option-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .option-item {
            flex: 1;
            min-width: 150px;
        }
        
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        
        .diff-buttons {
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
        
        /* 差异高亮样式 */
        .diff-added {
            background-color: #d4edda;
            color: #155724;
        }
        
        .diff-removed {
            background-color: #f8d7da;
            color: #721c24;
            text-decoration: line-through;
        }
        
        .diff-unchanged {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>文本差异比较</h1>
            <div class="info-card">
                <div class="diff-controls">
                    <div class="diff-container">
                        <div class="text-area">
                            <label for="text-old">原始文本:</label>
                            <textarea id="text-old" placeholder="输入原始文本内容..."></textarea>
                        </div>
                        
                        <div class="text-area">
                            <label for="text-new">新文本:</label>
                            <textarea id="text-new" placeholder="输入修改后的文本内容..."></textarea>
                        </div>
                        
                        <div class="diff-result" id="diff-result">差异结果将显示在这里...</div>
                    </div>
                    
                    <div class="diff-options">
                        <div class="option-group">
                            <div class="option-item">
                                <label for="diff-method">比较方式:</label>
                                <select id="diff-method">
                                    <option value="chars">字符比较</option>
                                    <option value="words">单词比较</option>
                                    <option value="lines">行比较</option>
                                </select>
                            </div>
                            
                            <div class="option-item">
                                <label for="ignore-case">忽略大小写:</label>
                                <select id="ignore-case">
                                    <option value="false">否</option>
                                    <option value="true">是</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="diff-buttons">
                        <button id="compare-btn" class="use-btn">比较文本</button>
                        <button id="clear-btn" class="use-btn secondary">清空文本</button>
                        <button id="example-btn" class="use-btn secondary">示例文本</button>
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
            
        // 文本差异比较逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const textOld = document.getElementById('text-old');
            const textNew = document.getElementById('text-new');
            const diffResult = document.getElementById('diff-result');
            const diffMethod = document.getElementById('diff-method');
            const ignoreCase = document.getElementById('ignore-case');
            const compareBtn = document.getElementById('compare-btn');
            const clearBtn = document.getElementById('clear-btn');
            const exampleBtn = document.getElementById('example-btn');
            const errorMessage = document.getElementById('error-message');
            
            // 初始化
            loadExampleText();
            
            // 按钮事件
            compareBtn.addEventListener('click', compareText);
            clearBtn.addEventListener('click', clearText);
            exampleBtn.addEventListener('click', loadExampleText);
            
            // 比较文本
            function compareText() {
                try {
                    const oldText = textOld.value;
                    const newText = textNew.value;
                    
                    if (!oldText || !newText) {
                        throw new Error('请填写原始文本和新文本');
                    }
                    
                    const method = diffMethod.value;
                    const ignore = ignoreCase.value === 'true';
                    
                    let diff;
                    
                    if (method === 'chars') {
                        diff = Diff.diffChars(
                            ignore ? oldText.toLowerCase() : oldText, 
                            ignore ? newText.toLowerCase() : newText
                        );
                    } else if (method === 'words') {
                        diff = Diff.diffWords(
                            ignore ? oldText.toLowerCase() : oldText, 
                            ignore ? newText.toLowerCase() : newText
                        );
                    } else {
                        diff = Diff.diffLines(
                            ignore ? oldText.toLowerCase() : oldText, 
                            ignore ? newText.toLowerCase() : newText
                        );
                    }
                    
                    // 显示差异结果
                    displayDiffResult(diff);
                    
                    hideError();
                } catch (error) {
                    showError(error.message);
                }
            }
            
            // 显示差异结果
            function displayDiffResult(diff) {
                const fragment = document.createDocumentFragment();
                
                diff.forEach(part => {
                    const span = document.createElement('span');
                    
                    if (part.added) {
                        span.className = 'diff-added';
                    } else if (part.removed) {
                        span.className = 'diff-removed';
                    } else {
                        span.className = 'diff-unchanged';
                    }
                    
                    span.appendChild(document.createTextNode(part.value));
                    fragment.appendChild(span);
                });
                
                diffResult.innerHTML = '';
                diffResult.appendChild(fragment);
            }
            
            // 清空文本
            function clearText() {
                textOld.value = '';
                textNew.value = '';
                diffResult.textContent = '差异结果将显示在这里...';
                hideError();
            }
            
            // 加载示例文本
            function loadExampleText() {
                textOld.value = `function greet(name) {
    return "Hello, " + name + "!";
}

console.log(greet("World"));`;
                
                textNew.value = `function greet(name, language = 'en') {
    const greetings = {
        en: "Hello",
        es: "Hola",
        fr: "Bonjour"
    };
    
    return greetings[language] + ", " + name + "!";
}

console.log(greet("World"));`;
                
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
