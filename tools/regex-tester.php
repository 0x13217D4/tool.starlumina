<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>正则表达式测试器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .regex-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .regex-options {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .regex-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        input[type="text"], textarea {
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
        
        input[type="text"]:focus, textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            display: block;
            font-size: 0.95rem;
        }
        
        .regex-buttons {
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
        
        .regex-results {
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
        
        .match-highlight {
            background-color: #fff3cd;
            padding: 0.1em 0.2em;
            border-radius: 0.2em;
        }
        
        .match-info {
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
            <h1>正则表达式测试器</h1>
            <div class="info-card">
                <div class="regex-controls">
                    <div class="control-group">
                        <label for="regex-pattern">正则表达式：</label>
                        <input type="text" id="regex-pattern" placeholder="输入正则表达式，例如: \d{3}-\d{2}-\d{4}">
                    </div>
                    
                    <div class="regex-options">
                        <div class="regex-option">
                            <input type="checkbox" id="case-insensitive">
                            <label for="case-insensitive">不区分大小写 (i)</label>
                        </div>
                        <div class="regex-option">
                            <input type="checkbox" id="global-match">
                            <label for="global-match">全局匹配 (g)</label>
                        </div>
                        <div class="regex-option">
                            <input type="checkbox" id="multiline">
                            <label for="multiline">多行模式 (m)</label>
                        </div>
                        <div class="regex-option">
                            <input type="checkbox" id="dotall">
                            <label for="dotall">点号匹配换行 (s)</label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="test-string">测试字符串：</label>
                        <textarea id="test-string" placeholder="输入要测试的字符串"></textarea>
                    </div>
                    
                    <div class="regex-buttons">
                        <button id="test-btn" class="use-btn">测试</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                    </div>
                    
                    <div id="error-message" class="error-message" style="display:none;"></div>
                    
                    <div class="regex-results" id="regex-results" style="display:none;">
                        <div class="result-tabs">
                            <div class="result-tab active" data-tab="matches">匹配结果</div>
                            <div class="result-tab" data-tab="replace">替换结果</div>
                        </div>
                        
                        <div class="result-content" id="result-content"></div>
                        <div class="match-info" id="match-info"></div>
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
            
        // 正则表达式测试器逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const regexPattern = document.getElementById('regex-pattern');
            const caseInsensitive = document.getElementById('case-insensitive');
            const globalMatch = document.getElementById('global-match');
            const multiline = document.getElementById('multiline');
            const dotall = document.getElementById('dotall');
            const testString = document.getElementById('test-string');
            const testBtn = document.getElementById('test-btn');
            const clearBtn = document.getElementById('clear-btn');
            const errorMessage = document.getElementById('error-message');
            const regexResults = document.getElementById('regex-results');
            const resultTabs = document.querySelectorAll('.result-tab');
            const resultContent = document.getElementById('result-content');
            const matchInfo = document.getElementById('match-info');
            
            let currentTab = 'matches';
            let matches = [];
            let replaceResult = '';
            
            // 切换标签页
            resultTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    resultTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentTab = this.dataset.tab;
                    updateResultDisplay();
                });
            });
            
            // 测试按钮
            testBtn.addEventListener('click', testRegex);
            
            // 清空按钮
            clearBtn.addEventListener('click', clearData);
            
            // 测试正则表达式
            function testRegex() {
                const pattern = regexPattern.value.trim();
                if (!pattern) {
                    showError('请输入正则表达式');
                    return;
                }
                
                const text = testString.value;
                if (!text) {
                    showError('请输入测试字符串');
                    return;
                }
                
                errorMessage.style.display = 'none';
                regexResults.style.display = 'none';
                
                try {
                    // 构建标志
                    let flags = '';
                    if (caseInsensitive.checked) flags += 'i';
                    if (globalMatch.checked) flags += 'g';
                    if (multiline.checked) flags += 'm';
                    if (dotall.checked) flags += 's';
                    
                    // 创建正则表达式
                    const regex = new RegExp(pattern, flags);
                    
                    // 测试匹配
                    matches = [];
                    const matchIterator = text.matchAll(regex);
                    for (const match of matchIterator) {
                        matches.push({
                            match: match[0],
                            index: match.index,
                            groups: match.slice(1)
                        });
                    }
                    
                    // 生成替换结果
                    replaceResult = text.replace(regex, '<span class="match-highlight">$&</span>');
                    
                    // 显示结果
                    updateResultDisplay();
                    regexResults.style.display = 'block';
                    
                    // 显示匹配信息
                    matchInfo.textContent = `找到 ${matches.length} 个匹配`;
                } catch (e) {
                    showError('正则表达式错误: ' + e.message);
                }
            }
            
            // 更新结果显示
            function updateResultDisplay() {
                if (currentTab === 'matches') {
                    if (matches.length === 0) {
                        resultContent.textContent = '没有找到匹配';
                    } else {
                        let resultText = '';
                        matches.forEach((match, i) => {
                            resultText += `匹配 #${i + 1}:\n`;
                            resultText += `位置: ${match.index}\n`;
                            resultText += `内容: "${match.match}"\n`;
                            if (match.groups && match.groups.length > 0) {
                                resultText += `捕获组: ${JSON.stringify(match.groups)}\n`;
                            }
                            resultText += '\n';
                        });
                        resultContent.textContent = resultText.trim();
                    }
                } else {
                    resultContent.innerHTML = replaceResult || '没有匹配可替换';
                }
            }
            
            // 清空数据
            function clearData() {
                regexPattern.value = '';
                testString.value = '';
                caseInsensitive.checked = false;
                globalMatch.checked = false;
                multiline.checked = false;
                dotall.checked = false;
                errorMessage.style.display = 'none';
                regexResults.style.display = 'none';
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
