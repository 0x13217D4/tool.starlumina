<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JPG无损压缩 - 星芒工具箱</title>
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
        .upload-container {
            margin: 20px 0 30px;
        }
        .upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-label:hover {
            border-color: #4a89dc;
            background-color: #f8faff;
        }
        .upload-icon {
            font-size: 24px;
            margin-right: 10px;
        }
        .upload-text {
            font-size: 16px;
        }
        .upload-label input {
            display: none;
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
            display: flex;
            align-items: center;
            font-size: 14px;
        }
        #quality-range {
            width: 100%;
            margin: 8px 0;
        }
        #quality-value {
            display: inline-block;
            width: 30px;
            text-align: center;
            font-weight: bold;
        }
        .compression-stats {
            display: flex;
            gap: 15px;
            margin: 30px 0 15px;
        }
        .stat-item {
            flex: 1;
            padding: 12px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .stat-item h4 {
            margin: 0 0 8px 0;
            font-size: 15px;
            color: #555;
        }
        .stat-item p {
            margin: 4px 0;
            font-size: 14px;
        }
        .original-size {
            color: #4a89dc;
            font-weight: bold;
        }
        .compressed-size {
            color: #37bc9b;
            font-weight: bold;
        }
        .primary-btn, .secondary-btn {
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }
        
        .primary-btn {
            background: #4a89dc;
            color: white;
            box-shadow: 0 2px 5px rgba(74, 137, 220, 0.3);
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
            background: white;
            color: #4a89dc;
            border: 1px solid #4a89dc;
            box-shadow: 0 2px 5px rgba(74, 137, 220, 0.1);
        }
        
        .secondary-btn:hover {
            background: #f8faff;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(74, 137, 220, 0.2);
        }
        
        .secondary-btn:active {
            transform: translateY(0);
        }
        
        .preview-container {
            width: 100%;
            height: 300px;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 30px auto 20px;
            overflow: hidden;
            background: #f9f9f9;
            border-radius: 8px;
        }
        
        @media (max-width: 768px) {
            .preview-container {
                height: 250px;
            }
            
            .compression-stats {
                flex-direction: column;
                gap: 10px;
            }
            
            .stat-item {
                width: 100%;
            }
            
            .actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .primary-btn, .secondary-btn {
                width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .preview-container {
                height: 200px;
            }
            
            .upload-label {
                padding: 10px;
                flex-direction: column;
            }
            
            .upload-icon {
                margin-right: 0;
                margin-bottom: 8px;
            }

            .actions {
                margin-top: 40px;
                margin-bottom: 40px;
                gap: 25px;
            }
            
            .primary-btn, .secondary-btn {
                margin: 12px 0;
                padding: 14px 28px;
                font-size: 18px;
                min-height: 50px;
            }
        }
        
        .preview-container canvas {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <div class="tool-content">
        <a href="../index.php" class="back-btn">返回首页</a>
        <h1>JPG无损压缩</h1>
        <p style="color:#666; margin-bottom:20px; font-size:14px;">
            你上传的图片不会上传到服务器中<br>
            图片中透明度部分将会丢失
        </p>
        
        <div class="input-group upload-container">
            <label class="upload-label">
                <span class="upload-icon">📁</span>
                <span class="upload-text">选择图片</span>
                <input type="file" id="uploader" accept="image/*">
            </label>
        </div>
        
        <div class="options-panel">
            <div class="option-item">
                <label>压缩质量: <output id="quality-value">85</output>%</label>
                <input type="range" id="quality-range" min="50" max="95" value="85">
            </div>
            <div class="option-item">
                <label>
                    <input type="checkbox" id="resize-checkbox" checked>
                    自动调整大尺寸图片(最大宽度1920px)
                </label>
            </div>
        </div>
        
        <div class="preview-container">
            <canvas id="preview"></canvas>
        </div>
        
        <div id="stats" class="compression-stats">
            <div class="stat-item">
                <h4>原始文件</h4>
                <p class="original-size">-</p>
                <p class="dimensions">-</p>
            </div>
            <div class="stat-item">
                <h4>压缩结果</h4>
                <p class="compressed-size">-</p>
                <p class="saving">-</p>
            </div>
        </div>
        
        <div class="actions">
            <button id="compress-btn" class="primary-btn">压缩图片</button>
            <button id="download-btn" class="secondary-btn" disabled>下载压缩图</button>
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

        // 图片压缩功能
        let compressedBlob = null;
        
        document.getElementById('uploader').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // 检测是否为图片
            if (!file.type.startsWith('image/')) {
                alert('请上传图片文件');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.getElementById('preview');
                    const ctx = canvas.getContext('2d');
                    
                    // 智能调整尺寸以适应预览容器
                    const previewWidth = 400;
                    const previewHeight = 300;
                    const imgRatio = img.width / img.height;
                    const previewRatio = previewWidth / previewHeight;
                    
                    let drawWidth, drawHeight;
                    
                    if (imgRatio > previewRatio) {
                        drawWidth = previewWidth;
                        drawHeight = previewWidth / imgRatio;
                    } else {
                        drawHeight = previewHeight;
                        drawWidth = previewHeight * imgRatio;
                    }
                    
                    // 限制最大尺寸防止内存问题
                    const MAX_DIMENSION = 5000;
                    if (img.width > MAX_DIMENSION || img.height > MAX_DIMENSION) {
                        alert('图片尺寸过大，请选择较小尺寸的图片');
                        return;
                    }
                    
                    canvas.width = img.width;
                    canvas.height = img.height;
                    
                    // 临时绘制完整尺寸图像用于压缩
                    ctx.drawImage(img, 0, 0);
                    
                    // 缩放显示在预览容器中
                    canvas.style.width = `${drawWidth}px`;
                    canvas.style.height = `${drawHeight}px`;
                    
                    // 显示原始文件信息
                    document.querySelector('.original-size').textContent = 
                        `大小: ${(file.size / 1024).toFixed(2)} KB`;
                    document.querySelector('.dimensions').textContent = 
                        `分辨率: ${img.width} × ${img.height}`;
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        // 质量滑块交互
        document.getElementById('quality-range').addEventListener('input', function() {
            document.getElementById('quality-value').textContent = this.value;
        });

        document.getElementById('compress-btn').addEventListener('click', function() {
            const file = document.getElementById('uploader').files[0];
            if (!file) {
                alert('请先选择图片');
                return;
            }
            
            const quality = document.getElementById('quality-range').value / 100;
            const shouldResize = document.getElementById('resize-checkbox').checked;
            const canvas = document.getElementById('preview');
            const ctx = canvas.getContext('2d');
            
            try {
                // 确保canvas有内容
                if (canvas.width === 0 || canvas.height === 0) {
                    throw new Error('请先上传有效的图片');
                }
                
                // 直接使用预览canvas进行压缩
                const tempCanvas = document.createElement('canvas');
                const tempCtx = tempCanvas.getContext('2d');
                
                if (shouldResize) {
                    // 调整尺寸
                    const MAX_WIDTH = 1920;
                    const scale = MAX_WIDTH / canvas.width;
                    tempCanvas.width = MAX_WIDTH;
                    tempCanvas.height = canvas.height * scale;
                } else {
                    // 保持原始尺寸
                    tempCanvas.width = canvas.width;
                    tempCanvas.height = canvas.height;
                }
                
                // 绘制图像
                tempCtx.drawImage(canvas, 0, 0, tempCanvas.width, tempCanvas.height);
                
                // 导出为JPEG
                tempCanvas.toBlob(blob => {
                    if (!blob) {
                        throw new Error('图片压缩失败');
                    }
                    
                    compressedBlob = blob;
                    
                    // 更新统计信息
                    document.querySelector('.original-size').textContent = 
                        `大小: ${(file.size / 1024).toFixed(2)} KB`;
                    document.querySelector('.dimensions').textContent = 
                        `分辨率: ${canvas.width} × ${canvas.height}`;
                    document.querySelector('.compressed-size').textContent = 
                        `大小: ${(blob.size / 1024).toFixed(2)} KB`;
                    document.querySelector('.saving').textContent = 
                        `节省: ${(100 - (blob.size / file.size * 100)).toFixed(1)}%`;
                    
                    document.getElementById('download-btn').disabled = false;
                    alert('图片压缩成功！');
                }, 'image/jpeg', quality);
            } catch (error) {
                alert('压缩出错: ' + error.message);
                console.error(error);
            }
        });

        document.getElementById('download-btn').addEventListener('click', function() {
            if (!compressedBlob) {
                alert('请先压缩图片');
                return;
            }
            
            const url = URL.createObjectURL(compressedBlob);
            const link = document.createElement('a');
            link.download = 'compressed.jpg';
            link.href = url;
            link.click();
            URL.revokeObjectURL(url);
        });
    </script>
</body>
</html>

