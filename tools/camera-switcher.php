<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>设备摄像头 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .camera-switcher {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .camera-container {
            width: 100%;
            height: 400px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
            background-color: #f8f9fa;
        }
        
        #camera-video {
            width: 100%;
            height: 100%;
        }
        
        .camera-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #666;
            font-size: 1.2rem;
        }
        
        .control-panel {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
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
        
        select {
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            width: 100%;
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
            <h1>设备摄像头</h1>
            <div class="info-card">
                <div class="camera-switcher">
                    <div class="camera-container">
                        <video id="camera-video" playsinline autoplay></video>
                        <div class="camera-overlay" id="camera-overlay">
                            摄像头未启动
                        </div>
                    </div>
                    
                    <div class="control-panel">
                        <select id="camera-select" disabled>
                            <option value="">选择摄像头...</option>
                        </select>
                        
                        <div class="btn-group">
                            <button id="start-btn">启动摄像头</button>
                            <button id="stop-btn" disabled>停止摄像头</button>
                            <button id="capture-btn" disabled>拍摄照片</button>
                        </div>
                    </div>
                    
                    <div class="info-text">
                        提示：请允许摄像头访问权限，选择不同摄像头可切换视角
                    </div>
                    <div class="info-text" id="resolution-info">
                        当前分辨率: 未启动
                    </div>
                    <div class="info-text">
                        本工具完全在浏览器本地运行，不会上传任何数据到服务器
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
            
        // 摄像头切换逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('camera-video');
            const overlay = document.getElementById('camera-overlay');
            const cameraSelect = document.getElementById('camera-select');
            const startBtn = document.getElementById('start-btn');
            const stopBtn = document.getElementById('stop-btn');
            
            let stream = null;
            let cameras = [];
            
            // 启动摄像头
            startBtn.addEventListener('click', async () => {
                try {
                    // 检查浏览器支持情况
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        throw new Error('您的浏览器不支持摄像头访问功能');
                    }
                    
                    // 检查是否在安全上下文中
                    if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                        throw new Error('摄像头访问需要HTTPS安全连接或本地开发环境');
                    }
                    
                    // 获取摄像头列表
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    cameras = devices.filter(device => device.kind === 'videoinput');
                    
                    if (cameras.length === 0) {
                        throw new Error('未检测到可用摄像头');
                    }
                    
                    // 填充摄像头选择列表
                    cameraSelect.innerHTML = '';
                    cameras.forEach((camera, index) => {
                        const label = camera.label || `摄像头 ${index + 1}`;
                        cameraSelect.add(new Option(label, camera.deviceId));
                    });
                    
                    // 默认选择第一个摄像头
                    await switchCamera(cameras[0].deviceId);
                    
                    cameraSelect.disabled = false;
                    startBtn.disabled = true;
                    stopBtn.disabled = false;
                    document.getElementById('capture-btn').disabled = false;
                    overlay.style.display = 'none';
                    
                } catch (err) {
                    console.error('摄像头访问失败:', err);
                    overlay.textContent = `摄像头访问失败: ${err.message}`;
                    overlay.style.display = 'flex';
                }
            });
            
            // 停止摄像头
            stopBtn.addEventListener('click', () => {
                stopCamera();
                overlay.textContent = '摄像头已停止';
                overlay.style.display = 'flex';
                cameraSelect.innerHTML = '<option value="">选择摄像头...</option>';
                cameraSelect.disabled = true;
                startBtn.disabled = false;
                stopBtn.disabled = true;
            });
            
            // 切换摄像头
            cameraSelect.addEventListener('change', async () => {
                const deviceId = cameraSelect.value;
                if (!deviceId) return;
                
                try {
                    await switchCamera(deviceId);
                } catch (err) {
                    console.error('摄像头切换失败:', err);
                    overlay.textContent = `摄像头切换失败: ${err.message}`;
                    overlay.style.display = 'flex';
                }
            });
            
            // 拍摄按钮事件
            document.getElementById('capture-btn').addEventListener('click', () => {
                capturePhoto();
            });
            
            // 切换摄像头函数
            async function switchCamera(deviceId) {
                stopCamera();
                
                // 获取摄像头支持的能力
                const device = cameras.find(cam => cam.deviceId === deviceId);
                const capabilities = device ? await navigator.mediaDevices.getSupportedConstraints() : {};
                
                // 使用最高分辨率
                const constraints = {
                    video: {
                        deviceId: { exact: deviceId },
                        width: { ideal: capabilities.width?.max || 4096 },
                        height: { ideal: capabilities.height?.max || 2160 }
                    }
                };
                
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;
                
                // 更新分辨率信息
                updateResolutionInfo();
                
                // 监听分辨率变化
                video.onresize = updateResolutionInfo;
                
                // 确保拍摄按钮可用
                document.getElementById('capture-btn').disabled = false;
            }
            
            // 更新分辨率信息
            function updateResolutionInfo() {
                const resolutionInfo = document.getElementById('resolution-info');
                if (video.videoWidth && video.videoHeight) {
                    resolutionInfo.textContent = `当前分辨率: ${video.videoWidth} × ${video.videoHeight}`;
                } else {
                    resolutionInfo.textContent = '当前分辨率: 未启动';
                }
            }
            
            // 停止摄像头函数
            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                }
                document.getElementById('capture-btn').disabled = true;
            }
            
            // 拍摄照片并下载
            function capturePhoto() {
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // 创建下载链接
                const link = document.createElement('a');
                link.download = `photo_${new Date().getTime()}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
            }
        });
    </script>
</body>
</html>

