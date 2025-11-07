<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>图片EXIF查看器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .exif-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .file-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            border: 2px dashed #e0e0e0;
            border-radius: 6px;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-upload:hover {
            border-color: #3498db;
            background-color: #f0f7fd;
        }
        
        .file-upload-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        
        .file-upload-text {
            text-align: center;
            color: #6c757d;
        }
        
        .preview-container {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        
        .image-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .exif-data {
            width: 100%;
            margin-top: 1.5rem;
            border-collapse: collapse;
        }
        
        .exif-data th, .exif-data td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .exif-data th {
            background-color: #f8f9fa;
            font-weight: 500;
            color: #2c3e50;
            width: 30%;
        }
        
        .error-message {
            color: #dc3545;
            margin-top: 0.5rem;
            font-size: 0.9rem;
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
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>图片EXIF查看器</h1>
            <div class="info-card">
                <div class="exif-controls">
                    <div class="control-group">
                        <label>上传图片：</label>
                        <div class="file-upload" id="file-upload">
                            <div class="file-upload-icon">📷</div>
                            <div class="file-upload-text">
                                <p>点击或拖拽图片到此处上传</p>
                                <p><small>支持JPEG、PNG等格式</small></p>
                            </div>
                            <input type="file" id="file-input" accept="image/*" style="display:none;">
                        </div>
                        <div id="error-message" class="error-message" style="display:none;"></div>
                    </div>
                    
                    <div class="preview-container" id="preview-container" style="display:none;">
                        <img id="image-preview" class="image-preview">
                        <button id="clear-btn" class="use-btn secondary">清除图片</button>
                    </div>
                    
                    <div class="control-group" id="exif-data-container" style="display:none;">
                        <label>EXIF数据：</label>
                        <table class="exif-data" id="exif-data">
                            <thead>
                                <tr>
                                    <th>属性</th>
                                    <th>值</th>
                                </tr>
                            </thead>
                            <tbody id="exif-data-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div id="footer-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
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
            
        // EXIF查看器逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const fileUpload = document.getElementById('file-upload');
            const fileInput = document.getElementById('file-input');
            const previewContainer = document.getElementById('preview-container');
            const imagePreview = document.getElementById('image-preview');
            const clearBtn = document.getElementById('clear-btn');
            const exifDataContainer = document.getElementById('exif-data-container');
            const exifDataBody = document.getElementById('exif-data-body');
            const errorMessage = document.getElementById('error-message');
            
            // 点击上传区域触发文件选择
            fileUpload.addEventListener('click', () => fileInput.click());
            
            // 拖放功能
            fileUpload.addEventListener('dragover', (e) => {
                e.preventDefault();
                fileUpload.style.borderColor = '#3498db';
                fileUpload.style.backgroundColor = '#f0f7fd';
            });
            
            fileUpload.addEventListener('dragleave', () => {
                fileUpload.style.borderColor = '#e0e0e0';
                fileUpload.style.backgroundColor = '#f8f9fa';
            });
            
            fileUpload.addEventListener('drop', (e) => {
                e.preventDefault();
                fileUpload.style.borderColor = '#e0e0e0';
                fileUpload.style.backgroundColor = '#f8f9fa';
                
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFileSelect();
                }
            });
            
            // 文件选择变化
            fileInput.addEventListener('change', handleFileSelect);
            
            // 清除按钮
            clearBtn.addEventListener('click', clearData);
            
            function handleFileSelect() {
                const file = fileInput.files[0];
                if (!file) return;
                
                // 检查文件类型
                if (!file.type.match('image.*')) {
                    showError('请选择有效的图片文件');
                    return;
                }
                
                errorMessage.style.display = 'none';
                
                // 显示预览
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'flex';
                    
                    // 读取EXIF数据
                    EXIF.getData(imagePreview, function() {
                        console.log('EXIF data loaded for image');
                        const allExifData = EXIF.getAllTags(this);
                        console.log('Raw EXIF data:', allExifData);
                        
                        // 检查是否真的没有EXIF数据
                        if (!allExifData || Object.keys(allExifData).length === 0) {
                            console.log('No EXIF data found in image');
                            // 尝试直接读取EXIF标签
                            const basicExifData = {
                                'Make': EXIF.getTag(this, 'Make'),
                                'Model': EXIF.getTag(this, 'Model'),
                                'DateTime': EXIF.getTag(this, 'DateTime')
                            };
                            console.log('Basic EXIF tags:', basicExifData);
                            
                            if (!basicExifData.Make && !basicExifData.Model && !basicExifData.DateTime) {
                                console.log('Confirmed no EXIF data in image');
                            }
                        }
                        
                        displayExifData(allExifData);
                    }, function(error) {
                        console.error('Error reading EXIF data:', error);
                        showError('读取EXIF数据时出错: ' + error.message);
                    });
                };
                reader.readAsDataURL(file);
            }
            
            function displayExifData(exifData) {
                exifDataBody.innerHTML = '';
                
                if (!exifData || Object.keys(exifData).length === 0) {
                    exifDataBody.innerHTML = '<tr><td colspan="2">未找到EXIF数据</td></tr>';
                    exifDataContainer.style.display = 'block';
                    return;
                }
                
                // 常见EXIF属性映射
                const exifPropertyMap = {
                    'Make': '相机品牌',
                    'Model': '相机型号',
                    'Orientation': '方向',
                    'DateTime': '拍摄时间',
                    'ExposureTime': '曝光时间',
                    'FNumber': '光圈值',
                    'ISOSpeedRatings': 'ISO',
                    'FocalLength': '焦距',
                    'Flash': '闪光灯',
                    'WhiteBalance': '白平衡',
                    'ExposureMode': '曝光模式',
                    'GPSLatitude': '纬度',
                    'GPSLongitude': '经度',
                    'GPSAltitude': '海拔',
                    'Software': '软件'
                };
                
                // 显示EXIF数据
                for (const [key, value] of Object.entries(exifData)) {
                    const displayName = exifPropertyMap[key] || key;
                    
                    // 格式化特殊值
                    let displayValue = value;
                    if (key === 'Orientation') {
                        displayValue = getOrientationDescription(value);
                    } else if (key === 'Flash') {
                        displayValue = getFlashDescription(value);
                    } else if (key === 'ExposureTime') {
                        displayValue = formatExposureTime(value);
                    } else if (key === 'FNumber') {
                        displayValue = `f/${value}`;
                    } else if (key === 'FocalLength') {
                        displayValue = `${value} mm`;
                    } else if (key === 'DateTime') {
                        displayValue = formatExifDate(value);
                    } else if (key === 'GPSLatitude' || key === 'GPSLongitude') {
                        displayValue = formatGpsCoordinate(value);
                    }
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${displayName}</td>
                        <td>${displayValue}</td>
                    `;
                    exifDataBody.appendChild(row);
                }
                
                exifDataContainer.style.display = 'block';
            }
            
            function formatExposureTime(value) {
                if (value < 1) {
                    return `1/${Math.round(1/value)} 秒`;
                }
                return `${value} 秒`;
            }
            
            function getOrientationDescription(value) {
                const orientations = {
                    1: '正常',
                    2: '水平翻转',
                    3: '180度旋转',
                    4: '垂直翻转',
                    5: '顺时针90度+水平翻转',
                    6: '顺时针90度',
                    7: '逆时针90度+水平翻转',
                    8: '逆时针90度'
                };
                return orientations[value] || value;
            }
            
            function getFlashDescription(value) {
                if (value === 0) return '未使用';
                if (value === 1) return '使用';
                return value;
            }
            
            function formatExifDate(dateString) {
                return dateString.replace(/^(\d{4}):(\d{2}):(\d{2}) (\d{2}):(\d{2}):(\d{2})$/, '$1-$2-$3 $4:$5:$6');
            }
            
            function formatGpsCoordinate(coordinate) {
                if (!coordinate) return '无数据';
                return `${coordinate[0]}°${coordinate[1]}'${coordinate[2]}"`;
            }
            
            function clearData() {
                fileInput.value = '';
                previewContainer.style.display = 'none';
                exifDataContainer.style.display = 'none';
                errorMessage.style.display = 'none';
            }
            
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html>
