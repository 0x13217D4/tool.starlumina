<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>进制转换器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .base-converter {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .converter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .converter-row {
            display: flex;
            gap: 1.5rem;
        }
        
        .converter-row .converter-group {
            flex: 1;
        }
        
        input[type="text"],
        select {
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
        
        input[type="text"]:focus,
        select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 12px;
            padding-right: 2rem;
            cursor: pointer;
        }
        
        label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            display: block;
            font-size: 0.9rem;
        }
        
        .error {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: none;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="base-converter">
        <a href="../index.php" class="back-btn">返回首页</a>
        <h1>进制转换器</h1>
        <p>支持二进制、八进制、十进制和十六进制之间的相互转换</p>
        
        <div class="converter-row">
            <div class="converter-group">
                <label for="from-base">从</label>
                <select id="from-base">
                    <option value="2">二进制 (2)</option>
                    <option value="8">八进制 (8)</option>
                    <option value="10" selected>十进制 (10)</option>
                    <option value="16">十六进制 (16)</option>
                </select>
            </div>
            
            <div class="converter-group">
                <label for="to-base">到</label>
                <select id="to-base">
                    <option value="2">二进制 (2)</option>
                    <option value="8">八进制 (8)</option>
                    <option value="10">十进制 (10)</option>
                    <option value="16" selected>十六进制 (16)</option>
                </select>
            </div>
        </div>
        
        <div class="converter-group">
            <label for="input-value">输入值</label>
            <input type="text" id="input-value" placeholder="输入要转换的数字">
            <div id="input-error" class="error"></div>
        </div>
        
        <button id="convert-btn" class="btn">转换</button>
        
        <div class="converter-group">
            <label for="result">结果</label>
            <input type="text" id="result" readonly>
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
            
        // 进制转换功能
        document.getElementById('convert-btn').addEventListener('click', convertBase);
        document.getElementById('input-value').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                convertBase();
            }
        });
        
        function convertBase() {
            const fromBase = parseInt(document.getElementById('from-base').value);
            const toBase = parseInt(document.getElementById('to-base').value);
            const inputValue = document.getElementById('input-value').value.trim();
            const resultInput = document.getElementById('result');
            const errorElement = document.getElementById('input-error');
            
            // 重置错误状态
            errorElement.style.display = 'none';
            resultInput.value = '';
            
            // 验证输入
            if (!inputValue) {
                showError('请输入要转换的数字');
                return;
            }
            
            try {
                // 将输入值转换为十进制
                let decimalValue;
                if (fromBase === 10) {
                    decimalValue = parseInt(inputValue);
                } else {
                    decimalValue = parseInt(inputValue, fromBase);
                }
                
                if (isNaN(decimalValue)) {
                    throw new Error('无效的输入值');
                }
                
                // 将十进制转换为目标进制
                let convertedValue;
                if (toBase === 10) {
                    convertedValue = decimalValue.toString();
                } else if (toBase === 16) {
                    convertedValue = decimalValue.toString(16).toUpperCase();
                } else {
                    convertedValue = decimalValue.toString(toBase);
                }
                
                // 显示结果
                resultInput.value = convertedValue;
            } catch (error) {
                showError('无效的输入值，请检查格式是否正确');
                console.error(error);
            }
        }
        
        function showError(message) {
            const errorElement = document.getElementById('input-error');
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
    </script>
</body>
</html>
