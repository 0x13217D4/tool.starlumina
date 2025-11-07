<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>随机数生成器 - 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .back-btn {
            display: inline-block;
            margin: 0 0 20px 0;
            padding: 8px 16px;
            text-align: center;
            width: auto;
        }
        .tool-content {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
            box-sizing: border-box;
        }
        
        .tool-content h1 {
            margin-top: 0;
        }
        .options-panel {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .option-item {
            margin: 10px 0;
        }
        .option-item label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group input[type="number"],
        .input-group input[type="text"],
        .input-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .primary-btn {
            padding: 12px 24px;
            background: #4a89dc;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: block;
            width: 100%;
            margin: 15px 0;
        }
        .primary-btn:hover {
            background: #3a79cc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(74, 137, 220, 0.3);
        }
        .primary-btn:active {
            transform: translateY(0);
        }
        .secondary-btn {
            padding: 12px 24px;
            background: white;
            color: #4a89dc;
            border: 1px solid #4a89dc;
            border-radius: 6px;
            font-weight: 500;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: block;
            width: 100%;
            margin: 15px 0;
        }
        .secondary-btn:hover {
            background: #f8faff;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(74, 137, 220, 0.2);
        }
        .secondary-btn:active {
            transform: translateY(0);
        }
        .result-container {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        .result-text {
            white-space: pre-wrap;
            word-break: break-all;
            font-family: monospace;
        }
        @media (max-width: 768px) {
            .input-group {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <div class="tool-content">
        <a href="../index.php" class="back-btn">返回首页</a>
        <h1>随机数生成器</h1>
        <p style="color:#666; margin-bottom:20px; font-size:14px;">
            生成指定范围内的随机数，支持整数和浮点数
        </p>
        
        <div class="options-panel">
            <div class="option-item">
                <div class="input-group">
                    <label for="min-value">最小值:</label>
                    <input type="number" id="min-value" value="1" step="any">
                </div>
                <div class="input-group">
                    <label for="max-value">最大值:</label>
                    <input type="number" id="max-value" value="100" step="any">
                </div>
                <div class="input-group">
                    <label for="count">生成数量:</label>
                    <input type="number" id="count" value="1" min="1" max="1000">
                </div>
                <div class="input-group">
                    <label for="number-type">数字类型:</label>
                    <select id="number-type">
                        <option value="integer">整数</option>
                        <option value="float">浮点数</option>
                    </select>
                </div>
            </div>
        </div>
        
        <button id="generate-btn" class="primary-btn">生成随机数</button>
        
        <div class="result-container" id="result-container" style="display:none;">
            <div class="result-text" id="result-text"></div>
            <button id="copy-btn" class="secondary-btn">复制结果</button>
        </div>
    </div>
    
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

        // 随机数生成功能
        document.getElementById('generate-btn').addEventListener('click', function() {
            const min = parseFloat(document.getElementById('min-value').value);
            const max = parseFloat(document.getElementById('max-value').value);
            const count = parseInt(document.getElementById('count').value);
            const isInteger = document.getElementById('number-type').value === 'integer';
            
            if (min >= max) {
                alert('最大值必须大于最小值');
                return;
            }
            
            if (count < 1 || count > 1000) {
                alert('生成数量必须在1-1000之间');
                return;
            }
            
            const results = [];
            for (let i = 0; i < count; i++) {
                let randomNum = Math.random() * (max - min) + min;
                if (isInteger) {
                    randomNum = Math.floor(randomNum);
                } else {
                    randomNum = parseFloat(randomNum.toFixed(4));
                }
                results.push(randomNum);
            }
            
            document.getElementById('result-text').textContent = results.join(', ');
            document.getElementById('result-container').style.display = 'block';
        });
        
        document.getElementById('copy-btn').addEventListener('click', function() {
            const resultText = document.getElementById('result-text').textContent;
            navigator.clipboard.writeText(resultText)
                .then(() => alert('已复制到剪贴板'))
                .catch(err => alert('复制失败: ' + err));
        });
    </script>
</body>
</html>

