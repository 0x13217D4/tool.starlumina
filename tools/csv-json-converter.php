<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV/JSON转换器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .converter-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .converter-options {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .converter-option {
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
        
        .converter-buttons {
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
            <h1>CSV/JSON转换器</h1>
            <div class="info-card">
                <div class="converter-controls">
                    <div class="converter-options">
                        <div class="converter-option">
                            <input type="radio" id="csv-to-json" name="conversion-type" value="csv-to-json" checked>
                            <label for="csv-to-json">CSV转JSON（上传文件）</label>
                        </div>
                        <div class="converter-option">
                            <input type="radio" id="json-to-csv" name="conversion-type" value="json-to-csv">
                            <label for="json-to-csv">JSON转CSV（输入数据）</label>
                        </div>
                    </div>
                    
                    <div class="control-group" id="input-container">
                        <label id="input-label">输入JSON数据：</label>
                        <textarea id="input-data" placeholder="在此粘贴JSON数据（仅用于JSON转CSV）" style="display:none;"></textarea>
                        <button id="upload-btn" class="use-btn secondary" style="display:none;">上传CSV文件</button>
                    </div>
                    
                    <div class="converter-buttons">
                        <button id="convert-btn" class="use-btn">转换</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                        <button id="download-btn" class="use-btn secondary">下载</button>
                    </div>
                    
                    <div class="control-group">
                        <label for="output-data">输出结果：</label>
                        <textarea id="output-data" placeholder="转换结果将显示在这里" readonly></textarea>
                        <div id="error-message" class="error-message" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div id="footer-container"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
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

        // CSV/JSON转换逻辑
        document.addEventListener('DOMContentLoaded', function() {
                const convertBtn = document.getElementById('convert-btn');
                const clearBtn = document.getElementById('clear-btn');
                const downloadBtn = document.getElementById('download-btn');
                const uploadBtn = document.getElementById('upload-btn');
                const inputData = document.getElementById('input-data');
                const inputLabel = document.getElementById('input-label');
                const errorMessage = document.getElementById('error-message');
                const conversionType = document.getElementsByName('conversion-type');
                const outputData = document.getElementById('output-data');
                let convertedData = null;
                let currentFile = null;
                
                // 切换转换类型时更新UI
                conversionType.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'csv-to-json') {
                            inputLabel.textContent = '上传CSV文件：';
                            inputData.style.display = 'none';
                            uploadBtn.style.display = 'block';
                        } else {
                            inputLabel.textContent = '输入JSON数据：';
                            inputData.style.display = 'block';
                            uploadBtn.style.display = 'none';
                        }
                    });
                });
                
                // 初始化UI状态
                if (document.querySelector('input[name="conversion-type"]:checked').value === 'csv-to-json') {
                    inputLabel.textContent = '上传CSV文件：';
                    inputData.style.display = 'none';
                    uploadBtn.style.display = 'block';
                }
                
                convertBtn.addEventListener('click', convertData);
                clearBtn.addEventListener('click', clearData);
                downloadBtn.addEventListener('click', downloadResult);
                
                function convertData() {
                errorMessage.style.display = 'none';
                
                try {
                    const selectedType = Array.from(conversionType).find(radio => radio.checked).value;
                    
                    if (selectedType === 'csv-to-json') {
                        if (!currentFile) {
                            showError('请先上传CSV文件');
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            try {
                                const csvData = e.target.result;
                                const parsed = Papa.parse(csvData, {
                                    header: true,
                                    skipEmptyLines: true
                                });
                                
                                if (parsed.errors.length > 0) {
                                    throw new Error('CSV解析错误: ' + parsed.errors[0].message);
                                }
                                
                                convertedData = JSON.stringify(parsed.data, null, 2);
                                outputData.value = convertedData;
                                showSuccess('CSV已成功转换为JSON');
                            } catch (e) {
                                showError('转换失败: ' + e.message);
                            }
                        };
                        reader.readAsText(currentFile, 'UTF-8');
                    } else {
                        // JSON转CSV
                        const input = inputData.value.trim();
                        if (!input) {
                            showError('请输入JSON数据');
                            return;
                        }
                        
                        const jsonData = JSON.parse(input);
                        convertedData = Papa.unparse(jsonData, {
                            quotes: true,
                            header: true
                        });
                        outputData.value = convertedData;
                        showSuccess('JSON已成功转换为CSV');
                    }
                } catch (e) {
                    showError('转换失败: ' + e.message);
                }
            }
            
            function clearData() {
                inputData.value = '';
                outputData.value = '';
                convertedData = null;
                errorMessage.style.display = 'none';
            }
            
            function downloadResult() {
                if (!convertedData) {
                    showError('没有可下载的内容');
                    return;
                }
                
                const selectedType = Array.from(conversionType).find(radio => radio.checked).value;
                const extension = selectedType === 'csv-to-json' ? 'json' : 'csv';
                const mimeType = selectedType === 'csv-to-json' ? 'application/json' : 'text/csv';
                
                // 添加BOM头解决中文乱码问题
                let dataToDownload = convertedData;
                if (selectedType === 'json-to-csv') {
                    dataToDownload = '\uFEFF' + dataToDownload; // UTF-8 BOM
                }
                
                const blob = new Blob([dataToDownload], { type: mimeType });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `converted.${extension}`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
            
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
            
            function showSuccess(message) {
                errorMessage.textContent = message;
                errorMessage.style.color = '#28a745';
                errorMessage.style.display = 'block';
            }
            
            // 文件上传处理
            uploadBtn.addEventListener('click', function() {
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = '.csv';
                fileInput.onchange = e => {
                    currentFile = e.target.files[0];
                    if (currentFile) {
                        showSuccess(`已选择文件: ${currentFile.name}`);
                    }
                };
                fileInput.click();
            });
        });
    </script>
</body>
</html>
