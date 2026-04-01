<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>图片压缩与格式转换工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .tool-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .section-card {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .section-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .section-title {
            color: #2c3e50;
            margin: 0 0 1.5rem 0;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .section-title i {
            color: #3498db;
        }
        
        .file-drop-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 3rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }
        
        .file-drop-area:hover {
            border-color: #3498db;
            background-color: #f0f8ff;
        }
        
        .file-drop-area.active {
            border-color: #27ae60;
            background-color: #f0fff4;
        }
        
        .upload-icon {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        
        .upload-text {
            color: #666;
            margin-bottom: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .btn-secondary {
            background-color: #27ae60;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #219a52;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 2px solid #3498db;
            color: #3498db;
        }
        
        .btn-outline:hover {
            background-color: #3498db;
            color: white;
        }
        
        .btn:focus {
            outline: 2px solid rgba(52, 152, 219, 0.5);
            outline-offset: 2px;
        }
        
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .info-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .info-card h3 {
            color: #2c3e50;
            margin: 0 0 1rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #666;
            font-weight: 500;
        }
        
        .info-value {
            color: #2c3e50;
            font-weight: 600;
        }
        
        .controls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .control-label {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .slider-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .slider {
            flex: 1;
            height: 6px;
            border-radius: 3px;
            background: linear-gradient(to right, #3498db, #27ae60);
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }
        
        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            border: 2px solid #3498db;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .slider::-moz-range-thumb {
            -moz-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            border: 2px solid #3498db;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .slider::-ms-thumb {
            -ms-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            border: 2px solid #3498db;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .value-display {
            min-width: 60px;
            text-align: center;
            background-color: #3498db;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 500;
        }
        
        .preset-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .preset-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #ddd;
            border-radius: 6px;
            background-color: white;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .preset-btn:hover {
            border-color: #3498db;
            color: #3498db;
        }
        
        .preset-btn:focus {
            outline: 2px solid rgba(52, 152, 219, 0.5);
            outline-offset: 1px;
        }
        
        .preset-btn.active {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }
        
        .format-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }
        
        .format-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .format-option:hover {
            border-color: #3498db;
            background-color: #f0f8ff;
        }
        
        .format-option.selected {
            border-color: #3498db;
            background-color: #3498db;
            color: white;
        }
        
        .format-option input[type="radio"] {
            display: none;
        }
        
        .format-check {
            width: 20px;
            height: 20px;
            border: 2px solid #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .format-option.selected .format-check {
            background-color: white;
            border-color: white;
            color: #3498db;
        }
        
        .input-field {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .status-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .loading-spinner {
            width: 30px;
            height: 30px;
            border: 3px solid #3498db;
            border-top: 3px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .history-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .history-item {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 1rem;
            border-left: 4px solid #3498db;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .modal-title {
            color: #2c3e50;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .modal-close {
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            background: none;
            border: none;
        }
        
        .modal-close:hover {
            color: #333;
        }
        
        .privacy-notice {
            background-color: #e8f4fd;
            border-left: 4px solid #3498db;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 2rem;
        }
        
        .privacy-notice h3 {
            color: #2c3e50;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .privacy-notice p {
            color: #666;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .tool-container {
                padding: 1rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .controls-grid {
                grid-template-columns: 1fr;
            }
            
            .format-options {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    <div class="tool-container">
        <!-- 隐私安全提示 -->
        <div class="privacy-notice">
            <h3><i class="fa fa-shield"></i> 隐私安全保障</h3>
            <p>所有图片处理均在本地完成，不会上传至任何服务器，确保您的文件安全与隐私。</p>
        </div>

        <!-- 上传区域 -->
        <div class="section-card">
            <h2 class="section-title"><i class="fa fa-upload"></i>上传图片</h2>
            <div id="drop-area" class="file-drop-area">
                <input type="file" id="file-input" style="display: none;" accept="image/*">
                <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                <div class="upload-text">拖拽图片到此处，或</div>
                <button id="browse-btn" class="btn btn-primary">
                    <i class="fa fa-folder-open"></i> 选择图片
                </button>
                <div style="margin-top: 1rem; font-size: 0.9rem; color: #999;">支持 JPG、PNG、WEBP、GIF 格式</div>
            </div>
        </div>

        <!-- 图片信息和处理结果区域 -->
        <div id="processing-section" class="section-card" style="display: none;">
            <h2 class="section-title"><i class="fa fa-bar-chart"></i>图片处理信息</h2>
            
            <div class="info-grid">
                <div class="info-card">
                    <h3><i class="fa fa-file-image-o"></i> 原始图片</h3>
                    <div class="info-row">
                        <span class="info-label">文件名:</span>
                        <span id="original-filename" class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">文件大小:</span>
                        <span id="original-size" class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">尺寸:</span>
                        <span id="original-dimensions" class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">格式:</span>
                        <span id="original-format" class="info-value"></span>
                    </div>
                </div>
                
                <div class="info-card">
                    <h3><i class="fa fa-cogs"></i> 处理后图片</h3>
                    <div class="info-row">
                        <span class="info-label">预计文件大小:</span>
                        <span id="processed-size" class="info-value" style="color: #3498db;"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">压缩比:</span>
                        <span id="compression-ratio" class="info-value" style="color: #27ae60;"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">输出尺寸:</span>
                        <span id="output-dimensions" class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">输出格式:</span>
                        <span id="output-format" class="info-value"></span>
                    </div>
                </div>
            </div>
            
            <div class="status-container">
                <div id="processing-spinner" class="loading-spinner" style="display: none;"></div>
                <span id="status-text">处理完成</span>
                <button id="download-btn" class="btn btn-primary" disabled>
                    <i class="fa fa-download"></i> 下载处理后的图片
                </button>
            </div>
        </div>

        <!-- 设置区域 -->
        <div id="settings-section" class="section-card" style="display: none;">
            <h2 class="section-title"><i class="fa fa-sliders"></i>处理设置</h2>

            <!-- 压缩质量设置 -->
            <div class="control-group">
                <div class="control-label">
                    <i class="fa fa-compress"></i> 压缩质量
                    <span id="quality-value" class="value-display">80%</span>
                </div>
                <div class="slider-container">
                    <input type="range" id="quality-slider" class="slider" min="0" max="100" value="80">
                </div>
            </div>

            <!-- 预设模式 -->
            <div class="control-group">
                <div class="control-label">
                    <i class="fa fa-magic"></i> 预设模式
                </div>
                <div class="preset-buttons">
                    <button class="preset-btn active" data-preset="normal">
                        <i class="fa fa-check-circle"></i> 普通
                    </button>
                    <button class="preset-btn" data-preset="high">
                        <i class="fa fa-star"></i> 高质量
                    </button>
                    <button class="preset-btn" data-preset="strong">
                        <i class="fa fa-rocket"></i> 超强压缩
                    </button>
                    <button class="preset-btn" data-preset="webp">
                        <i class="fa fa-leaf"></i> WebP最小体积
                    </button>
                </div>
            </div>

            <!-- 格式转换 -->
            <div class="control-group">
                <div class="control-label">
                    <i class="fa fa-exchange"></i> 格式转换
                </div>
                <div class="format-options">
                    <label class="format-option selected">
                        <input type="radio" name="format" value="jpeg" checked>
                        <span class="format-check"><i class="fa fa-check"></i></span>
                        <span>JPEG</span>
                    </label>
                    <label class="format-option">
                        <input type="radio" name="format" value="png">
                        <span class="format-check"><i class="fa fa-check"></i></span>
                        <span>PNG</span>
                    </label>
                    <label class="format-option">
                        <input type="radio" name="format" value="webp">
                        <span class="format-check"><i class="fa fa-check"></i></span>
                        <span>WebP</span>
                    </label>
                    <label class="format-option">
                        <input type="radio" name="format" value="gif">
                        <span class="format-check"><i class="fa fa-check"></i></span>
                        <span>GIF</span>
                    </label>
                </div>
            </div>

            <!-- 分辨率调整 -->
            <div class="control-group">
                <div class="control-label">
                    <i class="fa fa-arrows-alt"></i> 分辨率调整
                </div>
                <div class="controls-grid">
                    <div>
                        <label for="scale-percent">缩放比例</label>
                        <div class="slider-container">
                            <input type="range" id="scale-percent" class="slider" min="10" max="100" value="100">
                            <span id="scale-value" class="value-display">100%</span>
                        </div>
                    </div>
                    <div>
                        <label for="max-width">最大宽度 (px)</label>
                        <input type="number" id="max-width" class="input-field" placeholder="原始宽度">
                    </div>
                </div>
            </div>
        </div>

        <!-- 操作历史 -->
        <div id="history-section" class="section-card" style="display: none;">
            <h2 class="section-title"><i class="fa fa-history"></i>操作历史</h2>
            <div id="history-list" class="history-list">
                <!-- 历史记录将在这里动态添加 -->
            </div>
            <div style="margin-top: 1rem; text-align: center;">
                <button id="clear-history-btn" class="btn btn-outline">
                    <i class="fa fa-trash"></i> 清除历史
                </button>
            </div>
        </div>
    </div>
    <div id="footer-container"></div>

    <!-- 帮助模态框 -->
    <div id="help-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">使用帮助</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div>
                <h4>如何上传图片？</h4>
                <p>点击"选择图片"按钮或直接拖拽图片到上传区域。</p>
                <h4>如何调整压缩质量？</h4>
                <p>使用压缩质量滑块，向左降低质量（更小文件），向右提高质量（更大文件）。</p>
                <h4>如何选择预设模式？</h4>
                <p>点击预设模式按钮，包括普通、高质量、超强压缩和WebP最小体积模式。</p>
                <h4>如何转换图片格式？</h4>
                <p>在格式转换区域选择您想要的输出格式。</p>
                <h4>如何调整分辨率？</h4>
                <p>使用缩放比例滑块或直接输入最大宽度值。</p>
                <h4>隐私安全说明</h4>
                <p>所有图片处理均在您的浏览器本地完成，不会上传至任何服务器，确保您的文件安全。</p>
            </div>
            <div style="text-align: right; margin-top: 1.5rem;">
                <button class="btn btn-primary" id="got-it-btn">我知道了</button>
            </div>
        </div>
    </div>

    <script>
        // 全局变量
        let originalImage = null;
        let originalFile = null;
        let processedImageUrl = null;
        let processingHistory = JSON.parse(localStorage.getItem('imageProcessingHistory')) || [];

        // DOM元素
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file-input');
        const browseBtn = document.getElementById('browse-btn');
        const processingSection = document.getElementById('processing-section');
        const settingsSection = document.getElementById('settings-section');
        const historySection = document.getElementById('history-section');
        const originalFilename = document.getElementById('original-filename');
        const originalSize = document.getElementById('original-size');
        const originalDimensions = document.getElementById('original-dimensions');
        const originalFormat = document.getElementById('original-format');
        const processedSize = document.getElementById('processed-size');
        const compressionRatio = document.getElementById('compression-ratio');
        const outputDimensions = document.getElementById('output-dimensions');
        const outputFormat = document.getElementById('output-format');
        const qualitySlider = document.getElementById('quality-slider');
        const qualityValue = document.getElementById('quality-value');
        const scalePercent = document.getElementById('scale-percent');
        const scaleValue = document.getElementById('scale-value');
        const maxWidth = document.getElementById('max-width');
        const downloadBtn = document.getElementById('download-btn');
        const processingSpinner = document.getElementById('processing-spinner');
        const statusText = document.getElementById('status-text');
        const presetBtns = document.querySelectorAll('.preset-btn');
        const formatOptions = document.querySelectorAll('.format-option');
        const historyList = document.getElementById('history-list');
        const clearHistoryBtn = document.getElementById('clear-history-btn');
        const helpModal = document.getElementById('help-modal');
        const gotItBtn = document.getElementById('got-it-btn');

        // 加载模板
        fetch('../templates/header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-container').innerHTML = data;
            })
            .catch(error => console.error('Error loading header:', error));

        fetch('../templates/footer.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            })
            .catch(error => console.error('Error loading footer:', error));

        // 初始化
        document.addEventListener('DOMContentLoaded', () => {
            // 检查是否有历史记录
            if (processingHistory.length > 0) {
                historySection.style.display = 'block';
                renderHistory();
            }

            // 事件监听
            browseBtn.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', handleFileSelect);
            dropArea.addEventListener('dragover', handleDragOver);
            dropArea.addEventListener('drop', handleDrop);
            qualitySlider.addEventListener('input', handleQualityChange);
            scalePercent.addEventListener('input', handleScaleChange);
            maxWidth.addEventListener('input', handleMaxWidthChange);
            downloadBtn.addEventListener('click', handleDownload);
            presetBtns.forEach(btn => btn.addEventListener('click', handlePresetClick));
            formatOptions.forEach(option => option.addEventListener('click', handleFormatChange));
            clearHistoryBtn.addEventListener('click', handleClearHistory);
            gotItBtn.addEventListener('click', () => helpModal.style.display = 'none');

            // 为drop area添加动画效果
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });
        });

        // 辅助函数
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            dropArea.classList.add('active');
        }

        function unhighlight() {
            dropArea.classList.remove('active');
        }

        function handleDragOver(e) {
            highlight();
        }

        function handleDrop(e) {
            unhighlight();
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length) {
                handleFiles(files);
            }
        }

        function handleFileSelect(e) {
            const files = e.target.files;
            
            if (files.length) {
                handleFiles(files);
            }
        }

        function handleFiles(files) {
            const file = files[0];
            
            if (!file.type.match('image.*')) {
                alert('请上传图片文件！');
                return;
            }
            
            originalFile = file;
            const reader = new FileReader();
            
            reader.onload = function(e) {
                originalImage = new Image();
                originalImage.onload = function() {
                    // 显示原始图片信息
                    originalFilename.textContent = file.name;
                    originalSize.textContent = formatFileSize(file.size);
                    originalDimensions.textContent = `${originalImage.width} × ${originalImage.height}`;
                    originalFormat.textContent = file.type.split('/')[1].toUpperCase();
                    
                    // 设置最大宽度输入框的默认值
                    maxWidth.value = originalImage.width;
                    
                    // 显示处理区域和设置区域
                    processingSection.style.display = 'block';
                    settingsSection.style.display = 'block';
                    
                    // 处理图片
                    processImage();
                };
                originalImage.src = e.target.result;
            };
            
            reader.readAsDataURL(file);
        }

        function handleQualityChange() {
            const quality = qualitySlider.value;
            qualityValue.textContent = `${quality}%`;
            processImage();
        }

        function handleScaleChange() {
            const scale = scalePercent.value;
            scaleValue.textContent = `${scale}%`;
            
            // 根据缩放比例计算最大宽度
            const width = Math.round(originalImage.width * (scale / 100));
            maxWidth.value = width;
            
            processImage();
        }

        function handleMaxWidthChange() {
            const width = parseInt(maxWidth.value);
            
            if (isNaN(width) || width <= 0) {
                maxWidth.value = originalImage.width;
                return;
            }
            
            // 根据最大宽度计算缩放比例
            const scale = Math.round((width / originalImage.width) * 100);
            scalePercent.value = scale;
            scaleValue.textContent = `${scale}%`;
            
            processImage();
        }

        function handlePresetClick(e) {
            const preset = e.target.closest('.preset-btn').dataset.preset;
            
            // 更新预设按钮样式
            presetBtns.forEach(btn => btn.classList.remove('active'));
            e.target.closest('.preset-btn').classList.add('active');
            
            // 根据预设设置参数
            switch(preset) {
                case 'normal':
                    qualitySlider.value = 80;
                    qualityValue.textContent = '80%';
                    scalePercent.value = 100;
                    scaleValue.textContent = '100%';
                    maxWidth.value = originalImage.width;
                    document.querySelector('input[name="format"][value="jpeg"]').checked = true;
                    break;
                case 'high':
                    qualitySlider.value = 95;
                    qualityValue.textContent = '95%';
                    scalePercent.value = 100;
                    scaleValue.textContent = '100%';
                    maxWidth.value = originalImage.width;
                    document.querySelector('input[name="format"][value="png"]').checked = true;
                    break;
                case 'strong':
                    qualitySlider.value = 50;
                    qualityValue.textContent = '50%';
                    scalePercent.value = 80;
                    scaleValue.textContent = '80%';
                    maxWidth.value = Math.round(originalImage.width * 0.8);
                    document.querySelector('input[name="format"][value="jpeg"]').checked = true;
                    break;
                case 'webp':
                    qualitySlider.value = 70;
                    qualityValue.textContent = '70%';
                    scalePercent.value = 100;
                    scaleValue.textContent = '100%';
                    maxWidth.value = originalImage.width;
                    document.querySelector('input[name="format"][value="webp"]').checked = true;
                    break;
            }
            
            // 更新格式选择样式
            updateFormatSelection();
            processImage();
        }

        function handleFormatChange(e) {
            const option = e.target.closest('.format-option');
            const radio = option.querySelector('input[type="radio"]');
            
            // 更新格式选择样式
            formatOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            
            // 设置选中状态
            radio.checked = true;
            
            processImage();
        }

        function updateFormatSelection() {
            const selectedFormat = document.querySelector('input[name="format"]:checked').value;
            formatOptions.forEach(opt => opt.classList.remove('selected'));
            document.querySelector(`input[name="format"][value="${selectedFormat}"]`).closest('.format-option').classList.add('selected');
        }

        function processImage() {
            if (!originalImage) return;
            
            const quality = qualitySlider.value / 100;
            const format = document.querySelector('input[name="format"]:checked').value;
            const maxWidthValue = parseInt(maxWidth.value);
            
            // 计算输出尺寸
            let outputWidth = originalImage.width;
            let outputHeight = originalImage.height;
            
            if (maxWidthValue && maxWidthValue < originalImage.width) {
                outputWidth = maxWidthValue;
                outputHeight = Math.round(originalImage.height * (maxWidthValue / originalImage.width));
            }
            
            // 显示处理状态
            processingSpinner.style.display = 'block';
            statusText.textContent = '处理中...';
            downloadBtn.disabled = true;
            
            // 使用setTimeout避免阻塞UI
            setTimeout(() => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                
                canvas.width = outputWidth;
                canvas.height = outputHeight;
                
                // 绘制图片
                ctx.drawImage(originalImage, 0, 0, outputWidth, outputHeight);
                
                // 转换为指定格式
                const mimeType = format === 'jpg' ? 'image/jpeg' : `image/${format}`;
                
                canvas.toBlob(function(blob) {
                    // 创建URL
                    processedImageUrl = URL.createObjectURL(blob);
                    
                    // 更新处理后的信息
                    processedSize.textContent = formatFileSize(blob.size);
                    compressionRatio.textContent = `${Math.round((1 - blob.size / originalFile.size) * 100)}%`;
                    outputDimensions.textContent = `${outputWidth} × ${outputHeight}`;
                    outputFormat.textContent = format.toUpperCase();
                    
                    // 更新下载按钮
                    downloadBtn.disabled = false;
                    downloadBtn.onclick = () => {
                        const a = document.createElement('a');
                        a.href = processedImageUrl;
                        const originalName = originalFile.name.split('.')[0];
                        a.download = `${originalName}_processed.${format}`;
                        a.click();
                        
                        // 添加到历史记录
                        addToHistory(originalFile.name, blob.size, format.toUpperCase());
                    };
                    
                    // 更新状态
                    processingSpinner.style.display = 'none';
                    statusText.textContent = '处理完成';
                }, mimeType, quality);
            }, 100);
        }

        function handleDownload() {
            if (!processedImageUrl) return;
            
            const a = document.createElement('a');
            a.href = processedImageUrl;
            const originalName = originalFile.name.split('.')[0];
            const format = document.querySelector('input[name="format"]:checked').value;
            a.download = `${originalName}_processed.${format}`;
            a.click();
            
            // 添加到历史记录
            addToHistory(originalFile.name, '下载完成', format.toUpperCase());
        }

        function addToHistory(filename, action, format) {
            const timestamp = new Date().toLocaleString('zh-CN');
            const historyItem = {
                filename,
                action,
                format,
                timestamp
            };
            
            // 添加到历史记录数组的开头
            processingHistory.unshift(historyItem);
            
            // 限制历史记录数量
            if (processingHistory.length > 10) {
                processingHistory = processingHistory.slice(0, 10);
            }
            
            // 保存到localStorage
            localStorage.setItem('imageProcessingHistory', JSON.stringify(processingHistory));
            
            // 显示历史区域
            historySection.style.display = 'block';
            
            // 重新渲染历史记录
            renderHistory();
        }

        function renderHistory() {
            if (processingHistory.length === 0) {
                historyList.innerHTML = '<p style="color: #999;">暂无历史记录</p>';
                return;
            }
            
            historyList.innerHTML = processingHistory.map(item => `
                <div class="history-item">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>${item.filename}</strong>
                            <div style="font-size: 0.9rem; color: #666;">${item.action} - ${item.format}</div>
                            <div style="font-size: 0.8rem; color: #999;">${item.timestamp}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="background: #3498db; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">${item.format}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function handleClearHistory() {
            if (confirm('确定要清除所有历史记录吗？')) {
                processingHistory = [];
                localStorage.removeItem('imageProcessingHistory');
                historySection.style.display = 'none';
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // 关闭模态框
        helpModal.querySelector('.modal-close').addEventListener('click', () => {
            helpModal.style.display = 'none';
        });

        window.addEventListener('click', function(e) {
            if (e.target === helpModal) {
                helpModal.style.display = 'none';
            }
        });
    </script>
</body>
</html>