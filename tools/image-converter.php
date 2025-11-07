<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>图片格式转换器 | 星芒工具箱</title>
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
        .upload-area {
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
        .upload-area input {
            display: none;
        }
        .format-selector {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .format-selector label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .format-selector select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .use-btn {
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
        .use-btn:hover {
            background: #3a79cc;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(74, 137, 220, 0.3);
        }
        .use-btn:active {
            transform: translateY(0);
        }
        .result-area {
            margin-top: 30px;
            text-align: center;
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
        .preview-container img {
            max-width: 100%;
            max-height: 100%;
        }
        @media (max-width: 768px) {
            .preview-container {
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>图片格式转换器</h1>
            <div class="info-card">
                <p>上传图片并选择输出格式，支持PNG、JPG、ICO、SVG和WebP格式转换</p>
            </div>

            <div class="converter-container">
                <div class="upload-area">
                    <label class="upload-label" for="image-upload">
                        <span class="upload-icon">📁</span>
                        <span class="upload-text">选择图片</span>
                        <input type="file" id="image-upload" accept="image/*">
                    </label>
                </div>

                <div class="format-selector">
                    <label for="output-format">输出格式：</label>
                    <select id="output-format">
                        <option value="png">PNG</option>
                        <option value="jpeg">JPG</option>
                        <option value="webp">WebP</option>
                        <option value="ico">ICO</option>
                        <option value="svg">SVG</option>
                    </select>
                </div>

                <button id="convert-btn" class="use-btn">转换图片</button>
                <button id="download-btn" class="use-btn" disabled>下载图片</button>

                <div class="result-area" id="result-area">
                    <!-- 转换结果将在这里显示 -->
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

        // 图片格式转换功能
        const imageUpload = document.getElementById('image-upload');
        const outputFormat = document.getElementById('output-format');
        const convertBtn = document.getElementById('convert-btn');
        const resultArea = document.getElementById('result-area');

        if (imageUpload && convertBtn) {
            convertBtn.addEventListener('click', convertImage);
        }

        function convertImage() {
            const file = imageUpload.files[0];
            if (!file) {
                alert('请先选择图片文件');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);

                    let mimeType = 'image/png';
                    switch(outputFormat.value) {
                        case 'jpeg': mimeType = 'image/jpeg'; break;
                        case 'webp': mimeType = 'image/webp'; break;
                        case 'ico': mimeType = 'image/x-icon'; break;
                        case 'svg': 
                            convertToSvg(img, file.name);
                            return;
                    }

                    canvas.toBlob(function(blob) {
                        displayResult(blob, file.name.split('.')[0] + '.' + outputFormat.value);
                    }, mimeType, 0.92);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function convertToSvg(img, originalName) {
            const svgContent = `
                <svg xmlns="http://www.w3.org/2000/svg" width="${img.width}" height="${img.height}">
                    <image href="${img.src}" width="${img.width}" height="${img.height}"/>
                </svg>
            `;
            const blob = new Blob([svgContent], {type: 'image/svg+xml'});
            displayResult(blob, originalName.split('.')[0] + '.svg');
        }

        let convertedBlob = null;
        
        function displayResult(blob, fileName) {
            resultArea.innerHTML = '';
            convertedBlob = blob;
            
            const previewContainer = document.createElement('div');
            previewContainer.className = 'preview-container';
            
            const preview = document.createElement('img');
            preview.src = URL.createObjectURL(blob);
            
            previewContainer.appendChild(preview);
            resultArea.appendChild(previewContainer);
            
            document.getElementById('download-btn').disabled = false;
            document.getElementById('download-btn').onclick = function() {
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.download = fileName;
                link.href = url;
                link.click();
                URL.revokeObjectURL(url);
            };
        }
    </script>
</body>
</html>

