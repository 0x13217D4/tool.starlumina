<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二维码识别 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .qr-scanner {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .scanner-container {
            width: 100%;
            height: 300px;
            border: 2px dashed #e0e0e0;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        #qr-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .scanner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        
        .result-area {
            margin-top: 1rem;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        button:disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }
        
        #result-text {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            min-height: 100px;
            font-family: inherit;
        }
        
        .info-text {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>二维码识别</h1>
            <div class="info-card">
                <div class="qr-scanner">
                    <div class="scanner-container">
                        <video id="qr-video" playsinline></video>
                        <div id="image-preview" style="display:none; width:100%; height:100%; object-fit:contain;"></div>
                        <div class="scanner-overlay"></div>
                    </div>
                    <div class="btn-group">
                        <button id="start-btn">摄像头扫描</button>
                        <button id="gallery-btn">图库扫描</button>
                        <button id="stop-btn" disabled>停止扫描</button>
                        <button id="clear-btn">清除</button>
                        <button id="copy-btn" disabled>复制结果</button>
                    </div>
                    <input type="file" id="file-input" accept=".jpg,.jpeg,.png,.gif,.bmp,.webp" style="display: none;">
                    <div class="result-area">
                        <label for="result-text">扫描结果：</label>
                        <textarea id="result-text" readonly placeholder="扫描结果将显示在这里"></textarea>
                        <div class="info-text">提示：请允许摄像头访问权限，将二维码置于取景框内即可自动识别</div>
                        <div class="info-text">本工具完全在浏览器本地运行，不会上传任何数据到服务器</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div id="footer-container"></div>

    <script src="../js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
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
            
        // 二维码扫描逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('qr-video');
            const startBtn = document.getElementById('start-btn');
            const galleryBtn = document.getElementById('gallery-btn');
            const stopBtn = document.getElementById('stop-btn');
            const clearBtn = document.getElementById('clear-btn');
            const copyBtn = document.getElementById('copy-btn');
            const fileInput = document.getElementById('file-input');
            const resultText = document.getElementById('result-text');
            const imagePreview = document.getElementById('image-preview');
            let scanning = false;
            let stream = null;
            let selectedFile = null;
            
            // 开始扫描
            startBtn.addEventListener('click', async () => {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            facingMode: "environment",
                            width: { ideal: 1280 },
                            height: { ideal: 720 }
                        } 
                    });
                    video.srcObject = stream;
                    video.play();
                    
                    startBtn.disabled = true;
                    stopBtn.disabled = false;
                    scanning = true;
                    scanQRCode();
                } catch (err) {
                    console.error('摄像头访问失败:', err);
                    resultText.value = '摄像头访问失败: ' + err.message;
                }
            });
            
            // 停止扫描
            stopBtn.addEventListener('click', () => {
                stopScanning();
            });
            
            // 复制结果
            copyBtn.addEventListener('click', () => {
                resultText.select();
                document.execCommand('copy');
            });
            
            // 扫描二维码
            function scanQRCode() {
                if (!scanning) return;
                
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });
                    
                    if (code) {
                        resultText.value = code.data;
                        copyBtn.disabled = false;
                    }
                }
                
                requestAnimationFrame(scanQRCode);
            }
            
            // 停止扫描
            function stopScanning() {
                scanning = false;
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                }
                
                startBtn.disabled = false;
                stopBtn.disabled = true;
            }
            
            // 图库扫描
            galleryBtn.addEventListener('click', function() {
                try {
                    // 先停止摄像头扫描
                    stopScanning();
                    // 清除之前的选择和预览
                    fileInput.value = '';
                    imagePreview.style.display = 'none';
                    imagePreview.style.backgroundImage = 'none';
                    video.style.display = 'block';
                    // 触发文件选择
                    fileInput.click();
                } catch (err) {
                    console.error('图库扫描出错:', err);
                    resultText.value = '图库扫描出错: ' + err.message;
                }
            });
            
            // 文件选择处理
            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;
                
                // 验证文件类型
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    resultText.value = '请选择有效的图片格式 (JPEG, PNG, GIF, BMP, WEBP)';
                    return;
                }
                
                selectedFile = file;
                
                const reader = new FileReader();
                reader.onload = (event) => {
                    // 显示预览图片
                    video.style.display = 'none';
                    imagePreview.style.display = 'block';
                    imagePreview.style.backgroundImage = `url(${event.target.result})`;
                    imagePreview.style.backgroundSize = 'contain';
                    imagePreview.style.backgroundRepeat = 'no-repeat';
                    imagePreview.style.backgroundPosition = 'center';
                    
                    const img = new Image();
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        
                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const code = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: "dontInvert",
                        });
                        
                        if (code) {
                            resultText.value = code.data;
                            copyBtn.disabled = false;
                        } else {
                            resultText.value = '未识别到二维码';
                            copyBtn.disabled = true;
                        }
                    };
                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
            
            // 清除功能
            clearBtn.addEventListener('click', () => {
                // 停止摄像头扫描
                stopScanning();
                
                // 清除图库扫描
                if (selectedFile) {
                    fileInput.value = '';
                    imagePreview.style.display = 'none';
                    imagePreview.style.backgroundImage = 'none';
                    selectedFile = null;
                    video.style.display = 'block';
                }
                
                // 清除结果
                resultText.value = '';
                copyBtn.disabled = true;
            });
            
            function clearResults() {
                resultText.value = '';
                copyBtn.disabled = true;
                
                // 清除上传的图片
                if (selectedFile) {
                    fileInput.value = '';
                    previewContainer.style.display = 'none';
                    previewImage.src = '#';
                    selectedFile = null;
                    scanBtn.disabled = true;
                }
            }
            
            // 清空按钮事件
            clearBtn.addEventListener('click', clearResults);
            
            // 复制结果按钮事件
            copyBtn.addEventListener('click', () => {
                resultText.select();
                document.execCommand('copy');
            });
        });
    </script>
</body>
</html>

