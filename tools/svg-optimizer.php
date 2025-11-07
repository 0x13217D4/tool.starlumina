<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVG优化工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .svg-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .svg-options {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .svg-option {
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
        
        .svg-buttons {
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
        
        .svg-preview {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
            border: 1px dashed #e0e0e0;
            border-radius: 6px;
            background-color: #f8f9fa;
        }
        
        .svg-preview svg {
            max-width: 100%;
            max-height: 200px;
        }
        
        .stats {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>SVG优化工具</h1>
            <div class="info-card">
                <div class="svg-controls">
                    <div class="svg-options">
                        <div class="svg-option">
                            <input type="checkbox" id="remove-metadata" checked>
                            <label for="remove-metadata">移除元数据</label>
                        </div>
                        <div class="svg-option">
                            <input type="checkbox" id="remove-comments" checked>
                            <label for="remove-comments">移除注释</label>
                        </div>
                        <div class="svg-option">
                            <input type="checkbox" id="remove-empty-text" checked>
                            <label for="remove-empty-text">移除空文本</label>
                        </div>
                        <div class="svg-option">
                            <input type="checkbox" id="remove-hidden-elements" checked>
                            <label for="remove-hidden-elements">移除隐藏元素</label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="input-svg">输入SVG代码：</label>
                        <textarea id="input-svg" placeholder="在此粘贴SVG代码"></textarea>
                    </div>
                    
                    <div class="svg-buttons">
                        <button id="optimize-btn" class="use-btn">优化SVG</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                        <button id="copy-btn" class="use-btn secondary">复制结果</button>
                        <button id="download-btn" class="use-btn secondary">下载SVG</button>
                    </div>
                    
                    <div class="control-group">
                        <label for="output-svg">优化结果：</label>
                        <textarea id="output-svg" placeholder="优化后的SVG代码将显示在这里" readonly></textarea>
                        <div id="error-message" class="error-message" style="display:none;"></div>
                    </div>
                    
                    <div class="svg-preview" id="svg-preview">
                        <p>SVG预览将显示在这里</p>
                    </div>
                    
                    <div class="stats" id="stats"></div>
                </div>
            </div>
        </div>
    </main>
    
    <div id="footer-container"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/3.1.2/svg.min.js"></script>
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
            
        // SVG优化逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const optimizeBtn = document.getElementById('optimize-btn');
            const clearBtn = document.getElementById('clear-btn');
            const copyBtn = document.getElementById('copy-btn');
            const downloadBtn = document.getElementById('download-btn');
            const inputSvg = document.getElementById('input-svg');
            const outputSvg = document.getElementById('output-svg');
            const errorMessage = document.getElementById('error-message');
            const svgPreview = document.getElementById('svg-preview');
            const stats = document.getElementById('stats');
            
            optimizeBtn.addEventListener('click', optimizeSvg);
            clearBtn.addEventListener('click', clearData);
            copyBtn.addEventListener('click', copyResult);
            downloadBtn.addEventListener('click', downloadSvg);
            
            function optimizeSvg() {
                const svgCode = inputSvg.value.trim();
                if (!svgCode) {
                    showError('请输入SVG代码');
                    return;
                }
                
                errorMessage.style.display = 'none';
                
                try {
                    // 简单优化实现 - 实际项目中应使用更专业的SVG优化库
                    let optimized = svgCode;
                    
                    // 移除XML声明
                    optimized = optimized.replace(/<\?xml[^>]+\?>/, '');
                    
                    // 移除DOCTYPE
                    optimized = optimized.replace(/<!DOCTYPE[^>]+>/, '');
                    
                    // 移除注释
                    if (document.getElementById('remove-comments').checked) {
                        optimized = optimized.replace(/<!--[\s\S]*?-->/g, '');
                    }
                    
                    // 移除元数据
                    if (document.getElementById('remove-metadata').checked) {
                        optimized = optimized.replace(/<metadata>[\s\S]*?<\/metadata>/g, '');
                    }
                    
                    // 移除空文本
                    if (document.getElementById('remove-empty-text').checked) {
                        optimized = optimized.replace(/<text[^>]*>\s*<\/text>/g, '');
                    }
                    
                    // 移除隐藏元素
                    if (document.getElementById('remove-hidden-elements').checked) {
                        optimized = optimized.replace(/<[^>]+display=["']none["'][^>]*>[\s\S]*?<\/[^>]+>/g, '');
                        optimized = optimized.replace(/<[^>]+visibility=["']hidden["'][^>]*>[\s\S]*?<\/[^>]+>/g, '');
                    }
                    
                    // 压缩空格
                    optimized = optimized.replace(/\s+/g, ' ');
                    optimized = optimized.replace(/>\s+</g, '><');
                    optimized = optimized.trim();
                    
                    // 更新输出
                    outputSvg.value = optimized;
                    
                    // 显示预览
                    updatePreview(optimized);
                    
                    // 显示统计信息
                    const originalSize = svgCode.length;
                    const optimizedSize = optimized.length;
                    const reduction = Math.round((1 - optimizedSize / originalSize) * 100);
                    
                    stats.innerHTML = `
                        <p>原始大小: ${originalSize} 字节</p>
                        <p>优化后大小: ${optimizedSize} 字节</p>
                        <p>减少了: ${reduction}%</p>
                    `;
                } catch (e) {
                    showError('优化SVG时出错: ' + e.message);
                }
            }
            
            function updatePreview(svgCode) {
                svgPreview.innerHTML = '';
                
                try {
                    // 使用SVG.js创建预览
                    const draw = SVG().addTo('#svg-preview').size('100%', '100%');
                    draw.svg(svgCode);
                    
                    // 调整预览大小
                    const svgElement = svgPreview.querySelector('svg');
                    if (svgElement) {
                        const viewBox = svgElement.getAttribute('viewBox');
                        if (viewBox) {
                            const [,, width, height] = viewBox.split(' ');
                            const aspectRatio = height / width;
                            
                            if (width > 200) {
                                svgElement.style.width = '200px';
                                svgElement.style.height = (200 * aspectRatio) + 'px';
                            }
                        }
                    }
                } catch (e) {
                    svgPreview.innerHTML = '<p>无法渲染SVG预览</p>';
                }
            }
            
            function clearData() {
                inputSvg.value = '';
                outputSvg.value = '';
                svgPreview.innerHTML = '<p>SVG预览将显示在这里</p>';
                stats.innerHTML = '';
                errorMessage.style.display = 'none';
            }
            
            function copyResult() {
                if (!outputSvg.value) {
                    showError('没有可复制的内容');
                    return;
                }
                
                outputSvg.select();
                document.execCommand('copy');
                alert('已复制到剪贴板');
            }
            
            function downloadSvg() {
                if (!outputSvg.value) {
                    showError('没有可下载的内容');
                    return;
                }
                
                const blob = new Blob([outputSvg.value], { type: 'image/svg+xml' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'optimized.svg';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
            
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html>
