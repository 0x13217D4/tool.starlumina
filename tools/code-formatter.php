<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>代码格式化 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        /* 代码格式化特定样式 */
        .tool-content {
            padding: 1rem;
        }
        
        textarea {
            width: 100%;
            min-height: 200px;
            font-family: monospace;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        
        .formatter-actions {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
            justify-content: center;
        }
        
        .formatter-actions button {
            padding: 0.5rem 1rem;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .formatter-actions button:hover {
            background: #2980b9;
        }
        
        .formatter-actions button:active {
            transform: translateY(1px);
        }
        
        .formatter-actions button#clear-btn {
            background: #e74c3c;
        }
        
        .formatter-actions button#clear-btn:hover {
            background: #c0392b;
        }
        
        /* 下拉框样式 */
        select {
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            width: 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 12px;
            padding-right: 2rem;
            cursor: pointer;
        }
        
        select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        
        /* 标签样式 */
        label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            display: block;
            font-size: 0.95rem;
        }
        
        /* 表单组间距 */
        .control-group {
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>代码格式化工具</h1>
            <div class="control-group">
                <label for="language-select">选择语言：</label>
                <select id="language-select" class="form-control">
                    <option value="javascript">JavaScript</option>
                    <option value="html">HTML</option>
                    <option value="css">CSS</option>
                    <option value="json">JSON</option>
                    <option value="python">Python</option>
                    <option value="java">Java</option>
                    <option value="cpp">C++</option>
                </select>
            </div>
            <textarea id="input-code" placeholder="粘贴需要格式化的代码..."></textarea>
            <div class="formatter-actions">
                <button id="format-btn">格式化代码</button>
                <button id="clear-btn">清空</button>
            </div>
            <textarea id="output-code" readonly placeholder="格式化后的代码将显示在这里..."></textarea>
        </div>
    </main>
    
    <div id="footer-container"></div>

    <!-- 引入Prettier代码格式化库 -->
    <script src="https://cdn.jsdelivr.net/npm/prettier@2.7.1/standalone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prettier@2.7.1/parser-babel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prettier@2.7.1/parser-html.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prettier@2.7.1/parser-postcss.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prettier@2.7.1/parser-graphql.min.js"></script>
    
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

        class CodeFormatter {
            constructor() {
                console.log('CodeFormatter初始化开始');
                this.initElements();
                this.initConfig();
                this.bindEvents();
                console.log('CodeFormatter初始化完成');
            }

            initElements() {
                this.inputCode = document.getElementById('input-code');
                this.outputCode = document.getElementById('output-code');
                this.formatBtn = document.getElementById('format-btn');
                this.clearBtn = document.getElementById('clear-btn');
                this.languageSelect = document.getElementById('language-select');
                
                console.log('DOM元素获取结果:', {
                    inputCode: !!this.inputCode,
                    outputCode: !!this.outputCode,
                    formatBtn: !!this.formatBtn,
                    clearBtn: !!this.clearBtn,
                    languageSelect: !!this.languageSelect
                });
            }

            initConfig() {
                this.formatOptions = {
                    tabWidth: 4,
                    semi: true,
                    singleQuote: true,
                    printWidth: 80
                };

                this.languageParsers = {
                    javascript: 'babel',
                    html: 'html',
                    css: 'css',
                    json: 'json',
                    python: 'babel',
                    java: 'babel',
                    cpp: 'babel'
                };

                this.plugins = {
                    babel: prettierPlugins.babel,
                    html: prettierPlugins.html,
                    css: prettierPlugins.postcss,
                    json: prettierPlugins.babel
                };
            }

            bindEvents() {
                this.formatBtn.addEventListener('click', () => this.formatCode());
                this.clearBtn.addEventListener('click', () => this.clearCode());
            }

            validateInput(code) {
                if (!code || code.trim() === '') {
                    throw new Error('请输入需要格式化的代码');
                }
                return code.trim();
            }

            getParser(language) {
                const parser = this.languageParsers[language];
                if (!parser) {
                    throw new Error(`不支持的语言: ${language}`);
                }
                return parser;
            }

            formatCode() {
                try {
                    const code = this.validateInput(this.inputCode.value);
                    const parser = this.getParser(this.languageSelect.value);
                    
                    const formatted = prettier.format(code, {
                        ...this.formatOptions,
                        parser: parser,
                        plugins: this.plugins
                    });
                    
                    this.outputCode.value = formatted;
                } catch (error) {
                    this.outputCode.value = `格式化错误: ${error.message}`;
                    console.error('格式化错误:', error);
                }
            }

            clearCode() {
                this.inputCode.value = '';
                this.outputCode.value = '';
            }
        }

        // 初始化格式化工具
        document.addEventListener('DOMContentLoaded', () => {
            new CodeFormatter();
        });
    </script>
</body>
</html>

