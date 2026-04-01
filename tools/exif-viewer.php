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
    <?php include '../templates/header.php'; ?>
    
    <main class="tool-page">
        <div class="tool-container">
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
    
    <?php include '../templates/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
    <script>
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
                    
                    // 等待图片加载完成后再读取EXIF数据
                    imagePreview.onload = function() {
                        readExifData(imagePreview);
                    };
                    
                    // 如果图片已经加载完成（从缓存）
                    if (imagePreview.complete) {
                        readExifData(imagePreview);
                    }
                };
                reader.readAsDataURL(file);
            }
            
            function readExifData(imgElement) {
                console.log('Starting EXIF read...');
                
                // 使用 exif-js 读取 EXIF 数据
                EXIF.getData(imgElement, function() {
                    console.log('EXIF data loaded for image');
                    const allExifData = EXIF.getAllTags(this);
                    console.log('Raw EXIF data:', allExifData);
                    console.log('Number of EXIF tags:', Object.keys(allExifData).length);
                    
                    displayExifData(allExifData);
                });
            }
            
            function displayExifData(exifData) {
                exifDataBody.innerHTML = '';
                
                // 过滤掉空值和内部属性
                const filteredData = {};
                for (const [key, value] of Object.entries(exifData)) {
                    // 跳过空值和 exif-js 内部属性
                    if (value !== undefined && value !== null && value !== '' && 
                        !key.startsWith('exif') && key !== 'thumbnail') {
                        filteredData[key] = value;
                    }
                }
                
                if (!filteredData || Object.keys(filteredData).length === 0) {
                    exifDataBody.innerHTML = '<tr><td colspan="2" style="text-align:center;padding:2rem;color:#6c757d;">此图片不包含EXIF数据<br><small>可能是截图、网络图片或已清除元数据的图片</small></td></tr>';
                    exifDataContainer.style.display = 'block';
                    return;
                }
                
                // 常见EXIF属性映射
                const exifPropertyMap = {
                    'ImageWidth': '图像宽度',
                    'ImageHeight': '图像高度',
                    'BitsPerSample': '每样本位数',
                    'ImageDescription': '图像描述',
                    'Make': '相机品牌',
                    'Model': '相机型号',
                    'Orientation': '方向',
                    'XResolution': 'X分辨率',
                    'YResolution': 'Y分辨率',
                    'ResolutionUnit': '分辨率单位',
                    'Software': '软件',
                    'DateTime': '拍摄时间',
                    'YCbCrPositioning': 'YCbCr定位',
                    'ExifIFDPointer': 'EXIF指针',
                    'ExposureTime': '曝光时间',
                    'FNumber': '光圈值',
                    'ExposureProgram': '曝光程序',
                    'ISOSpeedRatings': 'ISO',
                    'ExifVersion': 'EXIF版本',
                    'DateTimeOriginal': '原始拍摄时间',
                    'DateTimeDigitized': '数字化时间',
                    'ComponentsConfiguration': '组件配置',
                    'CompressedBitsPerPixel': '压缩位每像素',
                    'ShutterSpeedValue': '快门速度值',
                    'ApertureValue': '光圈值',
                    'BrightnessValue': '亮度值',
                    'ExposureBias': '曝光补偿',
                    'MaxApertureValue': '最大光圈值',
                    'MeteringMode': '测光模式',
                    'LightSource': '光源',
                    'Flash': '闪光灯',
                    'FocalLength': '焦距',
                    'MakerNote': '制造商备注',
                    'SubsecTime': '亚秒时间',
                    'SubsecTimeOriginal': '原始亚秒时间',
                    'SubsecTimeDigitized': '数字化亚秒时间',
                    'FlashpixVersion': 'Flashpix版本',
                    'ColorSpace': '色彩空间',
                    'PixelXDimension': '像素X尺寸',
                    'PixelYDimension': '像素Y尺寸',
                    'InteroperabilityIFDPointer': '互操作性指针',
                    'SensingMethod': '感测方法',
                    'FileSource': '文件来源',
                    'SceneType': '场景类型',
                    'CustomRendered': '自定义渲染',
                    'ExposureMode': '曝光模式',
                    'WhiteBalance': '白平衡',
                    'DigitalZoomRation': '数字变焦比',
                    'FocalLengthIn35mmFilm': '35mm等效焦距',
                    'SceneCaptureType': '场景类型',
                    'GainControl': '增益控制',
                    'Contrast': '对比度',
                    'Saturation': '饱和度',
                    'Sharpness': '锐度',
                    'SubjectDistanceRange': '主体距离范围',
                    'GPSLatitude': '纬度',
                    'GPSLongitude': '经度',
                    'GPSAltitude': '海拔'
                };
                
                // 优先显示的属性顺序
                const priorityOrder = [
                    'Make', 'Model', 'DateTime', 'ExposureTime', 'FNumber', 
                    'ISOSpeedRatings', 'FocalLength', 'Flash', 'Orientation',
                    'GPSLatitude', 'GPSLongitude'
                ];
                
                // 排序后的数据
                const sortedEntries = Object.entries(filteredData).sort((a, b) => {
                    const aIndex = priorityOrder.indexOf(a[0]);
                    const bIndex = priorityOrder.indexOf(b[0]);
                    if (aIndex === -1 && bIndex === -1) return 0;
                    if (aIndex === -1) return 1;
                    if (bIndex === -1) return -1;
                    return aIndex - bIndex;
                });
                
                // 显示EXIF数据
                for (const [key, value] of sortedEntries) {
                    const displayName = exifPropertyMap[key] || key;
                    
                    // 格式化特殊值
                    let displayValue = value;
                    
                    // 处理字符串值的乱码问题
                    if (typeof value === 'string') {
                        displayValue = fixEncoding(value);
                    }
                    
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
                    } else if (key === 'DateTime' || key === 'DateTimeOriginal' || key === 'DateTimeDigitized') {
                        displayValue = formatExifDate(value);
                    } else if (key === 'GPSLatitude' || key === 'GPSLongitude') {
                        displayValue = formatGpsCoordinate(value);
                    } else if (key === 'ExposureProgram') {
                        displayValue = getExposureProgramDescription(value);
                    } else if (key === 'MeteringMode') {
                        displayValue = getMeteringModeDescription(value);
                    } else if (key === 'LightSource') {
                        displayValue = getLightSourceDescription(value);
                    } else if (key === 'SensingMethod') {
                        displayValue = getSensingMethodDescription(value);
                    } else if (key === 'SceneCaptureType') {
                        displayValue = getSceneCaptureTypeDescription(value);
                    } else if (key === 'SceneType') {
                        displayValue = getSceneTypeDescription(value);
                    } else if (key === 'FileSource') {
                        displayValue = getFileSourceDescription(value);
                    } else if (key === 'ResolutionUnit') {
                        displayValue = getResolutionUnitDescription(value);
                    } else if (key === 'ExposureMode') {
                        displayValue = getExposureModeDescription(value);
                    } else if (key === 'WhiteBalance') {
                        displayValue = getWhiteBalanceDescription(value);
                    } else if (key === 'GainControl') {
                        displayValue = getGainControlDescription(value);
                    } else if (key === 'Contrast' || key === 'Saturation' || key === 'Sharpness') {
                        displayValue = getAdjustmentDescription(value);
                    } else if (key === 'ColorSpace') {
                        displayValue = getColorSpaceDescription(value);
                    } else if (key === 'CustomRendered') {
                        displayValue = getCustomRenderedDescription(value);
                    } else if (key === 'SubjectDistanceRange') {
                        displayValue = getSubjectDistanceRangeDescription(value);
                    } else if (key === 'MakerNote') {
                        displayValue = '[制造商数据]';
                    } else if (key === 'thumbnail') {
                        displayValue = '[缩略图]';
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
            
            // 修复EXIF字符串编码问题（处理UTF-8被错误解码为ISO-8859-1的情况）
            function fixEncoding(str) {
                if (!str || typeof str !== 'string') return str;
                
                try {
                    // 检测是否有乱码特征（常见的中文字符被错误解码）
                    const hasGarbled = /[\u00c0-\u00ff][\u0080-\u00bf]/.test(str);
                    if (hasGarbled) {
                        // 尝试修复编码：将错误解码的字符串转回正确的UTF-8
                        const bytes = new Uint8Array(str.length);
                        for (let i = 0; i < str.length; i++) {
                            bytes[i] = str.charCodeAt(i);
                        }
                        const decoder = new TextDecoder('utf-8');
                        const fixed = decoder.decode(bytes);
                        return fixed;
                    }
                } catch (e) {
                    console.log('Encoding fix failed:', e);
                }
                return str;
            }
            
            function getExposureProgramDescription(value) {
                const programs = {
                    0: '未定义',
                    1: '手动',
                    2: '正常程序',
                    3: '光圈优先',
                    4: '快门优先',
                    5: '创意程序',
                    6: '动作程序',
                    7: '人像模式',
                    8: '风景模式'
                };
                return programs[value] || value;
            }
            
            function getMeteringModeDescription(value) {
                const modes = {
                    0: '未知',
                    1: '平均测光',
                    2: '中央重点测光',
                    3: '点测光',
                    4: '多点测光',
                    5: '评价测光',
                    6: '局部测光'
                };
                return modes[value] || value;
            }
            
            function getLightSourceDescription(value) {
                const sources = {
                    0: '自动',
                    1: '日光',
                    2: '荧光灯',
                    3: '钨丝灯',
                    4: '闪光灯',
                    9: '晴天',
                    10: '阴天',
                    11: '阴影',
                    12: '日光荧光灯',
                    13: '日光白色荧光灯',
                    14: '冷白色荧光灯',
                    15: '白色荧光灯',
                    17: '标准光A',
                    18: '标准光B',
                    19: '标准光C',
                    20: 'D55',
                    21: 'D65',
                    22: 'D75',
                    23: 'D50',
                    24: 'ISO摄影棚钨灯'
                };
                return sources[value] || value;
            }
            
            function getSensingMethodDescription(value) {
                const methods = {
                    1: '未定义',
                    2: '单芯片彩色区域传感器',
                    3: '双芯片彩色区域传感器',
                    4: '三芯片彩色区域传感器',
                    5: '彩色顺序区域传感器',
                    7: '三线性传感器',
                    8: '彩色顺序线性传感器'
                };
                return methods[value] || value;
            }
            
            function getSceneCaptureTypeDescription(value) {
                const types = {
                    0: '标准',
                    1: '风景',
                    2: '人像',
                    3: '夜景'
                };
                return types[value] || value;
            }
            
            function getSceneTypeDescription(value) {
                if (value === 1) return '直接拍摄';
                return value;
            }
            
            function getFileSourceDescription(value) {
                if (value === 3) return '数码相机';
                return value;
            }
            
            function getResolutionUnitDescription(value) {
                const units = {
                    1: '无单位',
                    2: '英寸',
                    3: '厘米'
                };
                return units[value] || value;
            }
            
            function getExposureModeDescription(value) {
                const modes = {
                    0: '自动曝光',
                    1: '手动曝光',
                    2: '自动包围曝光'
                };
                return modes[value] || value;
            }
            
            function getWhiteBalanceDescription(value) {
                const modes = {
                    0: '自动白平衡',
                    1: '手动白平衡'
                };
                return modes[value] || value;
            }
            
            function getGainControlDescription(value) {
                const controls = {
                    0: '无',
                    1: '低增益',
                    2: '高增益',
                    3: '低减益',
                    4: '高减益'
                };
                return controls[value] || value;
            }
            
            function getAdjustmentDescription(value) {
                const adjustments = {
                    0: '正常',
                    1: '低',
                    2: '高'
                };
                return adjustments[value] || value;
            }
            
            function getColorSpaceDescription(value) {
                if (value === 1) return 'sRGB';
                if (value === 65535) return '未校准';
                return value;
            }
            
            function getCustomRenderedDescription(value) {
                const renders = {
                    0: '正常处理',
                    1: '自定义处理'
                };
                return renders[value] || value;
            }
            
            function getSubjectDistanceRangeDescription(value) {
                const ranges = {
                    0: '未知',
                    1: '微距',
                    2: '近景',
                    3: '远景'
                };
                return ranges[value] || value;
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
