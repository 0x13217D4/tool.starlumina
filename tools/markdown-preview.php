<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markdown预览编辑器 - 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/github.min.css">
    <style>
        .markdown-container {
            display: flex;
            flex: 1;
            overflow: hidden;
            margin-top: 20px;
        }
        .editor, .preview {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
        }
        .editor {
            border-right: 1px solid #ddd;
        }
        #markdown-input {
            width: 100%;
            height: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: none;
            font-family: monospace;
            font-size: 16px;
        }
        .preview {
            background-color: #f9f9f9;
        }
        .preview-content {
            max-width: 800px;
            margin: 0 auto;
        }
        .preview-content h1, .preview-content h2, .preview-content h3 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.3em;
        }
        .preview-content pre {
            background-color: #f6f8fa;
            padding: 16px;
            border-radius: 6px;
            overflow: auto;
        }
        .preview-content code {
            font-family: SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;
            background-color: rgba(27,31,35,0.05);
            border-radius: 3px;
            padding: 0.2em 0.4em;
            font-size: 85%;
        }
        .preview-content blockquote {
            border-left: 4px solid #dfe2e5;
            color: #6a737d;
            padding: 0 1em;
            margin-left: 0;
        }
        .preview-content table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 16px;
        }
        .preview-content table th, .preview-content table td {
            padding: 6px 13px;
            border: 1px solid #dfe2e5;
        }
        .preview-content table tr {
            background-color: #fff;
            border-top: 1px solid #c6cbd1;
        }
        .preview-content table tr:nth-child(2n) {
            background-color: #f6f8fa;
        }
        @media (max-width: 768px) {
            .markdown-container {
                flex-direction: column;
            }
            .editor {
                border-right: none;
                border-bottom: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>Markdown预览编辑器</h1>
            <div class="info-card">
                <div class="markdown-container">
                    <div class="editor">
                        <textarea id="markdown-input" placeholder="在此输入Markdown文本..."></textarea>
                    </div>
                    <div class="preview">
                        <div class="preview-content" id="preview-output"></div>
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

        // 配置marked.js
        marked.setOptions({
            breaks: true,
            highlight: function(code, lang) {
                if (lang && hljs.getLanguage(lang)) {
                    return hljs.highlight(lang, code).value;
                }
                return hljs.highlightAuto(code).value;
            }
        });

        const input = document.getElementById('markdown-input');
        const output = document.getElementById('preview-output');

        // 初始示例内容
        const defaultMarkdown = `# Markdown预览编辑器

## 功能特点
- 实时预览Markdown内容
- 支持代码高亮
- 响应式设计
- 常用Markdown语法支持

## 代码示例
\`\`\`javascript
function helloWorld() {
    console.log("Hello, Markdown!");
}
\`\`\`

## 表格示例
| 语法 | 描述 |
| ---- | ---- |
| 标题 | 使用 # 号 |
| 列表 | 使用 - 或 * |
| 链接 | [文本](URL) |

> 提示：在左侧编辑，右侧实时预览`;

        input.value = defaultMarkdown;
        output.innerHTML = marked.parse(defaultMarkdown);

        // 实时预览
        input.addEventListener('input', function() {
            output.innerHTML = marked.parse(input.value);
        });
    </script>
</body>
</html>
