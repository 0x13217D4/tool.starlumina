<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>字数统计工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .counter-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .counter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .text-area {
            flex: 1;
            min-width: 300px;
        }
        
        .stats-area {
            flex: 1;
            min-width: 300px;
            padding: 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background-color: #f8f9fa;
        }
        
        textarea {
            width: 100%;
            height: 300px;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            resize: vertical;
        }
        
        .stat-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .stat-label {
            font-weight: 500;
            color: #2c3e50;
        }
        
        .stat-value {
            font-weight: bold;
            color: #3498db;
        }
        
        .counter-buttons {
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
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>字数统计工具</h1>
            <div class="info-card">
                <div class="counter-controls">
                    <div class="counter-container">
                        <div class="text-area">
                            <label for="text-input">输入文本:</label>
                            <textarea id="text-input" placeholder="在此输入要统计的文本..."></textarea>
                        </div>
                        
                        <div class="stats-area">
                            <h3>统计结果</h3>
                            <div class="stat-item">
                                <span class="stat-label">字符数 (含空格):</span>
                                <span class="stat-value" id="chars-with-spaces">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">字符数 (不含空格):</span>
                                <span class="stat-value" id="chars-no-spaces">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">字数:</span>
                                <span class="stat-value" id="word-count">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">行数:</span>
                                <span class="stat-value" id="line-count">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">段落数:</span>
                                <span class="stat-value" id="paragraph-count">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">字母数:</span>
                                <span class="stat-value" id="letter-count">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">数字数:</span>
                                <span class="stat-value" id="digit-count">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">标点符号数:</span>
                                <span class="stat-value" id="punctuation-count">0</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="counter-buttons">
                        <button id="count-btn" class="use-btn">统计</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
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
            
        // 字数统计逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const textInput = document.getElementById('text-input');
            const countBtn = document.getElementById('count-btn');
            const clearBtn = document.getElementById('clear-btn');
            const exampleBtn = document.getElementById('example-btn');
            const errorMessage = document.getElementById('error-message');
            
            // 统计结果元素
            const charsWithSpaces = document.getElementById('chars-with-spaces');
            const charsNoSpaces = document.getElementById('chars-no-spaces');
            const wordCount = document.getElementById('word-count');
            const lineCount = document.getElementById('line-count');
            const paragraphCount = document.getElementById('paragraph-count');
            const letterCount = document.getElementById('letter-count');
            const digitCount = document.getElementById('digit-count');
            const punctuationCount = document.getElementById('punctuation-count');
            
            // 初始化
            textInput.addEventListener('input', countText);
            
            // 按钮事件
            countBtn.addEventListener('click', countText);
            clearBtn.addEventListener('click', clearText);
            exampleBtn.addEventListener('click', loadExampleText);
            
            // 统计文本
            function countText() {
                try {
                    const text = textInput.value;
                    
                    // 字符数 (含空格)
                    charsWithSpaces.textContent = text.length;
                    
                    // 字符数 (不含空格)
                    charsNoSpaces.textContent = text.replace(/\s+/g, '').length;
                    
                    // 字数 (中文按字算，英文按词算)
                    const words = text.trim() === '' ? [] : 
                        text.match(/[\w\u4e00-\u9fa5]+/g) || [];
                    wordCount.textContent = words.length;
                    
                    // 行数
                    lineCount.textContent = text.trim() === '' ? 0 : 
                        (text.match(/\n/g) || []).length + 1;
                    
                    // 段落数
                    paragraphCount.textContent = text.trim() === '' ? 0 : 
                        text.split(/\n\s*\n/).filter(p => p.trim() !== '').length;
                    
                    // 字母数
                    letterCount.textContent = (text.match(/[a-zA-Z]/g) || []).length;
                    
                    // 数字数
                    digitCount.textContent = (text.match(/[0-9]/g) || []).length;
                    
                    // 标点符号数
                    punctuationCount.textContent = (text.match(/[.,\/#!$%\^&\*;:{}=\-_`~()'"\[\]?！，。、；：""''（）【】？]/g) || []).length;
                    
                    hideError();
                } catch (error) {
                    showError('统计过程中发生错误: ' + error.message);
                }
            }
            
            // 清空文本
            function clearText() {
                textInput.value = '';
                charsWithSpaces.textContent = '0';
                charsNoSpaces.textContent = '0';
                wordCount.textContent = '0';
                lineCount.textContent = '0';
                paragraphCount.textContent = '0';
                letterCount.textContent = '0';
                digitCount.textContent = '0';
                punctuationCount.textContent = '0';
                hideError();
            }
            
            // 加载示例文本
            function loadExampleText() {
                textInput.value = `《静夜思》是唐代诗人李白的诗作。

床前明月光，疑是地上霜。
举头望明月，低头思故乡。

这首诗创作于公元726年（唐玄宗开元十四年）农历九月十五日左右。李白时年26岁，写作地点在当时扬州旅舍。

英文翻译：
Thoughts on a Quiet Night

Before my bed, the moon shines bright,
I wonder if it's frost aground.
Looking up, I find the moon bright;
Bowing, in homesickness I'm drowned.

统计信息：
- 中文部分：56字
- 英文部分：26词
- 总行数：11行
- 段落数：4段`;
                
                countText();
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
