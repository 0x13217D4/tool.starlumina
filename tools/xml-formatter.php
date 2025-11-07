<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XML格式化工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .formatter-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
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
        
        .formatter-buttons {
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
        
        .formatter-options {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .formatter-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>XML格式化工具</h1>
            <div class="info-card">
                <div class="formatter-controls">
                    <div class="formatter-options">
                        <div class="formatter-option">
                            <input type="radio" id="format-xml" name="action-type" value="format" checked>
                            <label for="format-xml">格式化</label>
                        </div>
                        <div class="formatter-option">
                            <input type="radio" id="minify-xml" name="action-type" value="minify">
                            <label for="minify-xml">压缩</label>
                        </div>
                        <div class="formatter-option">
                            <input type="radio" id="validate-xml" name="action-type" value="validate">
                            <label for="validate-xml">验证</label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="input-xml">输入XML：</label>
                        <textarea id="input-xml" placeholder="在此粘贴XML内容"></textarea>
                    </div>
                    
                    <div class="formatter-buttons">
                        <button id="process-btn" class="use-btn">处理XML</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                        <button id="copy-btn" class="use-btn secondary">复制结果</button>
                    </div>
                    
                    <div class="control-group">
                        <label for="output-xml">输出结果：</label>
                        <textarea id="output-xml" placeholder="处理结果将显示在这里" readonly></textarea>
                        <div id="error-message" class="error-message" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div id="footer-container"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fast-xml-parser/4.0.0/fxparser.min.js"></script>
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
            
        // XML格式化逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const processBtn = document.getElementById('process-btn');
            const clearBtn = document.getElementById('clear-btn');
            const copyBtn = document.getElementById('copy-btn');
            const inputXml = document.getElementById('input-xml');
            const outputXml = document.getElementById('output-xml');
            const errorMessage = document.getElementById('error-message');
            const actionType = document.getElementsByName('action-type');
            
            processBtn.addEventListener('click', processXml);
            clearBtn.addEventListener('click', clearData);
            copyBtn.addEventListener('click', copyResult);
            
            function processXml() {
                const xml = inputXml.value.trim();
                if (!xml) {
                    showError('请输入XML内容');
                    return;
                }
                
                errorMessage.style.display = 'none';
                
                try {
                    const selectedAction = Array.from(actionType).find(radio => radio.checked).value;
                    let result;
                    
                    if (selectedAction === 'format') {
                        // 格式化XML
                        result = formatXml(xml);
                        outputXml.value = result;
                    } else if (selectedAction === 'minify') {
                        // 压缩XML
                        result = minifyXml(xml);
                        outputXml.value = result;
                    } else {
                        // 验证XML
                        const isValid = validateXml(xml);
                        outputXml.value = isValid ? 'XML验证通过，格式正确' : 'XML验证失败';
                    }
                } catch (e) {
                    showError('处理XML时出错: ' + e.message);
                }
            }
            
            function formatXml(xml) {
                // 简单格式化实现 - 实际项目中应使用更健壮的XML解析器
                // 这里使用正则表达式进行基本格式化
                let formatted = '';
                let indent = '';
                const tab = '  ';
                
                xml.split(/>\s*</).forEach(node => {
                    if (node.match(/^\/\w/)) {
                        // 关闭标签
                        indent = indent.substring(tab.length);
                    }
                    
                    formatted += indent + '<' + node + '>\n';
                    
                    if (node.match(/^<\w[^>]*[^\/]$/)) {
                        // 打开标签
                        indent += tab;
                    }
                });
                
                return formatted.substring(1, formatted.length - 3);
            }
            
            function minifyXml(xml) {
                // 压缩XML - 移除多余空格和换行
                return xml.replace(/>\s+</g, '><').trim();
            }
            
            function validateXml(xml) {
                // 简单验证 - 检查基本XML结构
                if (!xml.startsWith('<') || !xml.includes('>')) {
                    throw new Error('不是有效的XML文档');
                }
                
                // 检查标签是否匹配
                const tagStack = [];
                const tagRegex = /<\/?([a-z][a-z0-9]*)(?:\s+[^>]*)?>/gi;
                let match;
                
                while ((match = tagRegex.exec(xml)) !== null) {
                    const tag = match[1];
                    if (match[0].startsWith('</')) {
                        // 关闭标签
                        if (tagStack.pop() !== tag) {
                            throw new Error('标签不匹配: ' + tag);
                        }
                    } else {
                        // 打开标签
                        tagStack.push(tag);
                    }
                }
                
                if (tagStack.length > 0) {
                    throw new Error('未关闭的标签: ' + tagStack.join(', '));
                }
                
                return true;
            }
            
            function clearData() {
                inputXml.value = '';
                outputXml.value = '';
                errorMessage.style.display = 'none';
            }
            
            function copyResult() {
                if (!outputXml.value) {
                    showError('没有可复制的内容');
                    return;
                }
                
                outputXml.select();
                document.execCommand('copy');
                alert('已复制到剪贴板');
            }
            
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html>
