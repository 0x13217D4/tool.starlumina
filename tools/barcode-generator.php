<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>条码生成器 - 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .back-btn {
            display: inline-block;
            margin: 0 0 20px 0;
            padding: 8px 16px;
            text-align: center;
            width: auto;
        }
        .tool-content h1 {
            margin-top: 0;
        }
        .input-container {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }
        .input-icon {
            margin-right: 8px;
            font-size: 16px;
        }
        #barcode-input {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 0 10px 0 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .option-group {
            display: flex;
            align-items: center;
        }
        .option-group label {
            margin-right: 10px;
            font-size: 14px;
            color: #555;
        }
        #barcode-type {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .barcode-display {
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .barcode-placeholder {
            text-align: center;
            color: #999;
            padding: 20px;
        }
        .primary-btn, .secondary-btn {
            padding: 12px 24px;
            margin: 0 10px 0 0;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .primary-btn {
            background: #4a89dc;
            color: white;
        }
        
        .primary-btn:hover:not(:disabled) {
            background: #3a79cc;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .secondary-btn {
            background: #f5f7fa;
            color: #4a89dc;
            border: 1px solid #4a89dc;
        }
        
        .secondary-btn:hover:not(:disabled) {
            background: #e1e5eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .actions {
            margin: 25px 0;
        }
        .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .download-icon {
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <div class="tool-content">
        <a href="../index.php" class="back-btn">返回首页</a>
        <h1>条码生成器</h1>
        
        <div class="input-container">
            <div class="input-group">
                <label for="barcode-input">
                    <span class="input-icon">✏️</span>
                    <span>输入要编码的内容</span>
                </label>
                <input type="text" id="barcode-input" placeholder="支持字母、数字和符号">
            </div>
            
            <!-- 已移除条码类型选择，固定使用Code128格式 -->
        </div>
        
        <div class="barcode-display">
            <div class="barcode-placeholder" id="barcode-placeholder">
                <span>条码将在此处显示</span>
            </div>
            <canvas id="barcode-canvas" style="display:none;"></canvas>
        </div>
        
        <div class="actions">
            <button id="generate-btn" class="primary-btn">
                <span class="btn-text">生成条码</span>
                <span class="spinner"></span>
            </button>
            <button id="download-btn" class="secondary-btn" disabled>
                <span class="btn-text">下载条码</span>
                <span class="download-icon">⬇️</span>
            </button>
        </div>
    </div>
    
    <div id="footer-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
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

        // 条码生成逻辑
        document.getElementById('generate-btn').addEventListener('click', generateBarcode);
        document.getElementById('barcode-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') generateBarcode();
        });

        function generateBarcode() {
            const input = document.getElementById('barcode-input').value.trim();
            const type = "code128";
            const btn = document.getElementById('generate-btn');
            const btnText = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.spinner');
            
            if (!input) {
                alert('请输入要生成条码的内容');
                return;
            }
            
            btn.disabled = true;
            btnText.textContent = '生成中...';
            spinner.style.display = 'inline-block';
            
            try {
                document.getElementById('barcode-placeholder').style.display = 'none';
                document.getElementById('barcode-canvas').style.display = 'block';
                
                JsBarcode('#barcode-canvas', input, {
                    format: "code128",
                    lineColor: '#000',
                    width: 2,
                    height: 100,
                    displayValue: true
                });
                
                document.getElementById('download-btn').disabled = false;
            } catch (e) {
                alert('生成条码失败: ' + e.message);
                document.getElementById('barcode-placeholder').style.display = 'flex';
                document.getElementById('barcode-canvas').style.display = 'none';
            } finally {
                btn.disabled = false;
                btnText.textContent = '生成条码';
                spinner.style.display = 'none';
            }
        }

        // 下载功能
        document.getElementById('download-btn').addEventListener('click', function() {
            const canvas = document.getElementById('barcode-canvas');
            if (!canvas.toDataURL().includes('data:image/png')) {
                alert('请先生成条码');
                return;
            }
            
            const link = document.createElement('a');
            link.download = 'barcode.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    </script>
</body>
</html>

