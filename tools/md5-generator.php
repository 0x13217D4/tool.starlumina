<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MD5加密工具 - 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <style>
        .converter-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        textarea {
            width: 100%;
            min-height: 150px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
        <a href="../index.php" class="back-btn">返回首页</a>
        <h1>MD5加密工具</h1>
        
        <div class="input-group">
            <label for="input-text">输入文本：</label>
            <textarea id="input-text" placeholder="请输入要计算MD5哈希值的文本..."></textarea>
        </div>
        
        <div class="button-group">
            <button id="hash-btn">计算MD5值</button>
            <button id="clear-btn">清空</button>
        </div>
        
        <div class="input-group">
            <label for="output-text">MD5结果：</label>
            <textarea id="output-text" placeholder="MD5哈希值将显示在这里..." readonly></textarea>
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

        // MD5哈希计算功能
        document.getElementById('hash-btn').addEventListener('click', function() {
            const input = document.getElementById('input-text').value;
            
            if (input) {
                const hash = CryptoJS.MD5(input).toString(CryptoJS.enc.Hex);
                document.getElementById('output-text').value = hash;
            }
        });

        document.getElementById('clear-btn').addEventListener('click', function() {
            document.getElementById('input-text').value = '';
            document.getElementById('output-text').value = '';
        });
    </script>
</body>
</html>
