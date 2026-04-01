<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>在线Markdown编辑器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <!-- Font Awesome -->
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Marked.js for Markdown parsing -->
    <script src="https://cdn.bootcdn.net/ajax/libs/marked/11.1.1/marked.min.js"></script>
    <!-- Fallback Marked.js from unpkg -->
    <!-- Highlight.js for code syntax highlighting -->
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/highlight.js/11.11.1/styles/github.min.css" id="light-theme">
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/highlight.js/11.11.1/styles/github-dark.min.css" id="dark-theme" disabled>
    <script src="https://cdn.bootcdn.net/ajax/libs/highlight.js/11.11.1/highlight.min.js"></script>
    <!-- DOMPurify for sanitizing HTML output -->
    <script src="https://cdn.bootcdn.net/ajax/libs/dompurify/3.0.6/purify.min.js"></script>
    
    <style>
        /* Markdown编辑器专用样式 */
        .markdown-editor {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .editor-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
        }

        .editor-container {
            display: flex;
            gap: 1.5rem;
            min-height: 500px;
        }

        .editor-panel, .preview-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background-color: white;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 500;
            color: #2c3e50;
            border-radius: 6px 6px 0 0;
        }

        .panel-content {
            flex: 1;
            min-height: 400px;
        }

        #editor {
            width: 100%;
            height: 100%;
            min-height: 400px;
            padding: 1rem;
            border: none;
            outline: none;
            resize: none;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.875rem;
            line-height: 1.5;
            background-color: white;
            color: #2c3e50;
        }

        #preview {
            padding: 1rem;
            overflow-y: auto;
            height: 100%;
            min-height: 400px;
            background-color: white;
            color: #2c3e50;
        }

        .toolbar-section {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin-right: 1rem;
            padding-right: 1rem;
            border-right: 1px solid #dee2e6;
        }

        .toolbar-section:last-child {
            border-right: none;
            margin-right: 0;
            padding-right: 0;
        }

        .toolbar-btn {
            padding: 0.5rem 0.75rem;
            border: 1px solid #dee2e6;
            background-color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.875rem;
            color: #64748b;
        }

        .toolbar-btn:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
            color: #2c3e50;
        }

        .toolbar-btn.active {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }

        .editor-actions {
            display: flex;
            gap: 0.5rem;
            margin-left: auto;
        }

        .word-count {
            padding: 0.25rem 0.5rem;
            background-color: #e9ecef;
            border-radius: 4px;
            font-size: 0.8rem;
            color: #64748b;
            margin-left: auto;
        }
        
        .file-name-section {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .file-name-display {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.875rem;
            color: #2c3e50;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            transition: all 0.3s;
            min-width: 120px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        
        .file-name-display:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #1a73e8;
        }
        
        .file-name-input {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.875rem;
            color: #2c3e50;
            padding: 0.25rem 0.5rem;
            border: 1px solid #3498db;
            border-radius: 4px;
            background-color: white;
            min-width: 120px;
            text-align: center;
            outline: none;
        }
        
        .dark-mode .file-name-display {
            background-color: #334155;
            border-color: #475569;
            color: #f8fafc;
        }
        
        .dark-mode .file-name-display:hover {
            background-color: #475569;
            border-color: #64748b;
            color: #60a5fa;
        }
        
        .dark-mode .file-name-input {
            background-color: #0f172a;
            border-color: #3498db;
            color: #f8fafc;
        }

        /* 帮助模态框样式 */
        .help-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            overflow-y: auto;
        }

        .help-content {
            background-color: white;
            margin: 2rem auto;
            padding: 2rem;
            max-width: 800px;
            border-radius: 8px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .help-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            color: #dc3545;
        }

        .help-section {
            margin-bottom: 2rem;
        }

        .help-section h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .help-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .help-table th,
        .help-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .help-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }

        .help-table td {
            color: #495057;
        }

        .code-example {
            background-color: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.875rem;
            color: #e83e8c;
        }

        /* 暗色模式支持 */
        .dark-mode {
            background-color: #1e293b;
            color: #f8fafc;
        }

        .dark-mode .editor-toolbar,
        .dark-mode .panel-header {
            background-color: #334155;
            border-color: #475569;
        }

        .dark-mode .editor-panel,
        .dark-mode .preview-panel {
            border-color: #475569;
            background-color: #1e293b;
        }

        .dark-mode #editor,
        .dark-mode #preview {
            background-color: #0f172a;
            color: #f8fafc;
        }

        .dark-mode .toolbar-btn {
            background-color: #475569;
            border-color: #64748b;
            color: #f8fafc;
        }

        .dark-mode .toolbar-btn:hover {
            background-color: #64748b;
        }

        .dark-mode .help-content {
            background-color: #1e293b;
            color: #f8fafc;
        }

        .dark-mode .help-header {
            border-color: #475569;
        }

        .dark-mode .help-table th {
            background-color: #334155;
            color: #f8fafc;
        }

        .dark-mode .help-table td {
            border-color: #475569;
            color: #e2e8f0;
        }

        .dark-mode .code-example {
            background-color: #334155;
        }

        /* 预览区编辑模式样式 */
        .edit-mode-toggle {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            transition: all 0.3s;
        }

        .edit-mode-toggle:hover {
            background-color: #e9ecef;
            color: #2c3e50;
        }

        .edit-mode-toggle.active {
            background-color: #3498db;
            color: white;
        }

        .edit-mode-toggle {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            transition: all 0.3s;
        }

        .edit-mode-toggle:hover {
            background-color: #e9ecef;
            color: #2c3e50;
        }

        .edit-mode-toggle.active {
            background-color: #3498db;
            color: white;
        }

        .preview-content {
            min-height: 400px;
            overflow-y: auto;
        }

        .preview-editor {
            min-height: 400px;
            padding: 1rem;
            border: none;
            outline: none;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.875rem;
            line-height: 1.6;
            background-color: white;
            color: #2c3e50;
            overflow-y: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .preview-editor:focus {
            background-color: #f8f9fa;
        }

        .dark-mode .preview-editor {
            background-color: #0f172a;
            color: #f8fafc;
        }

        .dark-mode .preview-editor:focus {
            background-color: #1e293b;
        }

        .edit-mode-indicator {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background-color: #3498db;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .edit-mode-indicator.show {
            opacity: 1;
        }

        /* 响应式设计 */
        @media (max-width: 768px) {
            .editor-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .editor-toolbar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .toolbar-section {
                margin-right: 0;
                padding-right: 0;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
                padding-bottom: 0.5rem;
                margin-bottom: 0.5rem;
            }
            
            .toolbar-section:last-child {
                border-bottom: none;
                padding-bottom: 0;
                margin-bottom: 0;
            }
            
            .editor-actions {
                margin-left: 0;
                margin-bottom: 0.5rem;
            }
            
            .word-count {
                margin-left: 0;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- File input (hidden) -->
    <input type="file" id="file-input" accept=".md,.markdown,.txt" style="display: none;">
    
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>在线Markdown编辑器</h1>
            <div class="info-card">
                <div class="markdown-editor">
                    <!-- Editor Toolbar -->
                    <div class="editor-toolbar">
                        <!-- File Operations -->
                        <div class="editor-actions">
                            <button id="new-file" class="use-btn" title="新建文件 (Ctrl+N)">
                                <i class="fa fa-file-o"></i> 新建
                            </button>
                            <button id="open-file" class="use-btn" title="打开文件 (Ctrl+O)">
                                <i class="fa fa-folder-open-o"></i> 打开
                            </button>
                            <button id="save-file" class="use-btn" title="保存文件 (Ctrl+S)">
                                <i class="fa fa-save"></i> 保存
                            </button>
                            <button id="upload-file" class="use-btn" title="上传文件">
                                <i class="fa fa-upload"></i> 上传
                            </button>
                        </div>
                        
                        <!-- Formatting Tools -->
                        <div class="toolbar-section">
                            <button data-command="heading" data-value="1" class="toolbar-btn" title="标题 1">H1</button>
                            <button data-command="heading" data-value="2" class="toolbar-btn" title="标题 2">H2</button>
                            <button data-command="heading" data-value="3" class="toolbar-btn" title="标题 3">H3</button>
                        </div>
                        
                        <div class="toolbar-section">
                            <button data-command="bold" class="toolbar-btn" title="粗体 (Ctrl+B)">
                                <i class="fa fa-bold"></i>
                            </button>
                            <button data-command="italic" class="toolbar-btn" title="斜体 (Ctrl+I)">
                                <i class="fa fa-italic"></i>
                            </button>
                            <button data-command="strikethrough" class="toolbar-btn" title="删除线">
                                <i class="fa fa-strikethrough"></i>
                            </button>
                        </div>
                        
                        <div class="toolbar-section">
                            <button data-command="list" data-value="ul" class="toolbar-btn" title="无序列表">
                                <i class="fa fa-list-ul"></i>
                            </button>
                            <button data-command="list" data-value="ol" class="toolbar-btn" title="有序列表">
                                <i class="fa fa-list-ol"></i>
                            </button>
                            <button data-command="task" class="toolbar-btn" title="任务列表">
                                <i class="fa fa-check-square-o"></i>
                            </button>
                        </div>
                        
                        <div class="toolbar-section">
                            <button data-command="link" class="toolbar-btn" title="链接 (Ctrl+L)">
                                <i class="fa fa-link"></i>
                            </button>
                            <button data-command="image" class="toolbar-btn" title="图片 (Ctrl+P)">
                                <i class="fa fa-image"></i>
                            </button>
                            <button data-command="code" class="toolbar-btn" title="代码块 (Ctrl+K)">
                                <i class="fa fa-code"></i>
                            </button>
                        </div>
                        
                        <div class="toolbar-section">
                            <button data-command="quote" class="toolbar-btn" title="引用">
                                <i class="fa fa-quote-right"></i>
                            </button>
                            <button data-command="table" class="toolbar-btn" title="表格">
                                <i class="fa fa-table"></i>
                            </button>
                            <button data-command="hr" class="toolbar-btn" title="分隔线">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        
                        <!-- File Name -->
                        <div class="toolbar-section file-name-section">
                            <span class="file-name-display" id="file-name-display" title="点击修改文件名">document.md</span>
                            <button id="rename-file-btn" class="toolbar-btn" title="修改文件名">
                                <i class="fa fa-edit"></i>
                            </button>
                        </div>
                        
                        <!-- Settings -->
                        <div class="toolbar-section">
                            <button id="theme-toggle" class="toolbar-btn" title="切换主题 (Ctrl+D)">
                                <i class="fa fa-moon-o" id="theme-icon"></i>
                            </button>
                            <button id="help-btn" class="toolbar-btn" title="帮助">
                                <i class="fa fa-question-circle"></i>
                            </button>
                            <span class="word-count" id="word-count">字数: 0</span>
                        </div>
                    </div>
                    
                    <!-- Editor Container -->
                    <div class="editor-container">
                        <!-- Editor Panel -->
                        <div class="editor-panel">
                            <div class="panel-header">编辑区</div>
                            <div class="panel-content">
                                <textarea id="editor" placeholder="在此输入Markdown内容..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Preview Panel -->
                        <div class="preview-panel">
                            <div class="panel-header">
                                <span>预览区</span>
                                <button id="edit-mode-btn" class="edit-mode-toggle" title="切换编辑模式">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </div>
                            <div class="panel-content">
                                <div id="preview" class="preview-content"></div>
                                <div id="preview-editor" class="preview-editor hidden" contenteditable="true" spellcheck="false"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Help Modal -->
    <div id="help-modal" class="help-modal">
        <div class="help-content">
            <div class="help-header">
                <h2>Markdown语法帮助</h2>
                <button id="close-help" class="close-btn">&times;</button>
            </div>
            
            <div class="help-section">
                <h3>基础语法</h3>
                <table class="help-table">
                    <thead>
                        <tr>
                            <th>语法</th>
                            <th>效果</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="code-example"># 标题 1</span></td>
                            <td><h1>标题 1</h1></td>
                        </tr>
                        <tr>
                            <td><span class="code-example">## 标题 2</span></td>
                            <td><h2>标题 2</h2></td>
                        </tr>
                        <tr>
                            <td><span class="code-example">**粗体**</span></td>
                            <td><strong>粗体</strong></td>
                        </tr>
                        <tr>
                            <td><span class="code-example">*斜体*</span></td>
                            <td><em>斜体</em></td>
                        </tr>
                        <tr>
                            <td><span class="code-example">~~删除线~~</span></td>
                            <td><s>删除线</s></td>
                        </tr>
                        <tr>
                            <td><span class="code-example">[链接](url)</span></td>
                            <td><a href="#">链接</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="help-section">
                <h3>高级语法</h3>
                <table class="help-table">
                    <thead>
                        <tr>
                            <th>语法</th>
                            <th>效果</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="code-example">- 列表项</span></td>
                            <td><ul><li>列表项</li></ul></td>
                        </tr>
                        <tr>
                            <td><span class="code-example">1. 有序项</span></td>
                            <td><ol><li>有序项</li></ol></td>
                        </tr>
                        <tr>
                            <td><span class="code-example">> 引用</span></td>
                            <td><blockquote>引用</blockquote></td>
                        </tr>
                        <tr>
                            <td><span class="code-example">```代码块```</span></td>
                            <td><pre><code>代码块</code></pre></td>
                        </tr>
                        <tr>
                            <td><span class="code-example">| 表格 |</span></td>
                            <td><table><tr><td>表格</td></tr></table></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="help-section">
                <h3>快捷键</h3>
                <table class="help-table">
                    <thead>
                        <tr>
                            <th>功能</th>
                            <th>快捷键</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>新建文件</td>
                            <td><span class="code-example">Ctrl+N</span></td>
                        </tr>
                        <tr>
                            <td>打开文件</td>
                            <td><span class="code-example">Ctrl+O</span></td>
                        </tr>
                        <tr>
                            <td>保存文件</td>
                            <td><span class="code-example">Ctrl+S</span></td>
                        </tr>
                        <tr>
                            <td>粗体</td>
                            <td><span class="code-example">Ctrl+B</span></td>
                        </tr>
                        <tr>
                            <td>斜体</td>
                            <td><span class="code-example">Ctrl+I</span></td>
                        </tr>
                        <tr>
                            <td>链接</td>
                            <td><span class="code-example">Ctrl+L</span></td>
                        </tr>
                        <tr>
                            <td>切换主题</td>
                            <td><span class="code-example">Ctrl+D</span></td>
                        </tr>
                        <tr>
                            <td>帮助</td>
                            <td><span class="code-example">F1</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div id="footer-container"></div>
  
  <script>
    // Load common components
    fetch('../templates/header.php')
        .then(response => response.text())
        .then(html => document.getElementById('header-container').innerHTML = html);
        
    fetch('../templates/footer.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('footer-container').innerHTML = html;
            document.getElementById('current-year').textContent = new Date().getFullYear();
        });
    
    // DOM Elements
    const editor = document.getElementById('editor');
    const preview = document.getElementById('preview');
    const previewEditor = document.getElementById('preview-editor');
    const editModeBtn = document.getElementById('edit-mode-btn');
    const themeToggle = document.getElementById('theme-toggle');
    const newFileBtn = document.getElementById('new-file');
    const openFileBtn = document.getElementById('open-file');
    const saveFileBtn = document.getElementById('save-file');
    const uploadFileBtn = document.getElementById('upload-file');
    const renameFileBtn = document.getElementById('rename-file-btn');
    const fileNameDisplay = document.getElementById('file-name-display');
    const fileInput = document.getElementById('file-input');
    const helpBtn = document.getElementById('help-btn');
    const helpModal = document.getElementById('help-modal');
    const closeHelpBtn = document.getElementById('close-help');
    const wordCountBtn = document.getElementById('word-count');
    const lightTheme = document.getElementById('light-theme');
    const darkTheme = document.getElementById('dark-theme');
    
    // 编辑模式状态
    let isEditMode = false;
    let syncTimeout = null;
    let currentFileName = 'document.md';
    
    // Toolbar buttons
    const toolbarButtons = document.querySelectorAll('[data-command]');
    
    // Initialize libraries and start rendering
    function initializeLibraries() {
      let attempts = 0;
      const maxAttempts = 10;
      
      function checkLibraries() {
        attempts++;
        
        if (typeof marked !== 'undefined' && typeof DOMPurify !== 'undefined') {
          // Libraries loaded, perform initial render
          console.log('Libraries loaded successfully');
          initializeEditMode();
          renderMarkdown();
        } else if (attempts < maxAttempts) {
          // Libraries not yet loaded, try again
          console.log(`Waiting for libraries... (${attempts}/${maxAttempts})`);
          setTimeout(checkLibraries, 500);
        } else {
          // Libraries failed to load
          console.error('Failed to load libraries');
          preview.innerHTML = '<div style="color: red; padding: 1rem;">外部库加载失败，请检查网络连接并刷新页面</div>';
        }
      }
      
      checkLibraries();
    }
    
    // 初始化编辑模式
    function initializeEditMode() {
      // 编辑模式切换按钮事件
      editModeBtn.addEventListener('click', toggleEditMode);
      
      // 预览区编辑器事件
      previewEditor.addEventListener('input', handlePreviewEditorInput);
      previewEditor.addEventListener('paste', handlePreviewEditorPaste);
      previewEditor.addEventListener('keydown', handlePreviewEditorKeydown);
      
      // 双击预览区进入编辑模式
      preview.addEventListener('dblclick', () => {
        if (!isEditMode) {
          enterEditMode();
        }
      });
    }
    
    // 切换编辑模式
    function toggleEditMode() {
      if (isEditMode) {
        exitEditMode();
      } else {
        enterEditMode();
      }
    }
    
    // 进入编辑模式
    function enterEditMode() {
      isEditMode = true;
      editModeBtn.classList.add('active');
      editModeBtn.innerHTML = '<i class="fa fa-eye"></i>';
      editModeBtn.title = '退出编辑模式';
      
      // 将渲染的Markdown内容转换为可编辑的文本
      previewEditor.textContent = editor.value;
      preview.classList.add('hidden');
      previewEditor.classList.remove('hidden');
      
      // 添加编辑模式指示器
      addEditModeIndicator();
      
      // 聚焦到编辑器
      previewEditor.focus();
      
      // 设置光标到末尾
      const range = document.createRange();
      const sel = window.getSelection();
      range.selectNodeContents(previewEditor);
      range.collapse(false);
      sel.removeAllRanges();
      sel.addRange(range);
    }
    
    // 退出编辑模式
    function exitEditMode() {
      isEditMode = false;
      editModeBtn.classList.remove('active');
      editModeBtn.innerHTML = '<i class="fa fa-edit"></i>';
      editModeBtn.title = '切换编辑模式';
      
      // 同步内容到主编辑器
      const previewContent = previewEditor.textContent || previewEditor.innerText || '';
      if (previewContent !== editor.value) {
        editor.value = previewContent;
        renderMarkdown();
      }
      
      preview.classList.remove('hidden');
      previewEditor.classList.add('hidden');
      
      // 移除编辑模式指示器
      removeEditModeIndicator();
    }
    
    // 处理预览区编辑器输入
    function handlePreviewEditorInput() {
      // 使用防抖机制，避免频繁同步
      clearTimeout(syncTimeout);
      syncTimeout = setTimeout(() => {
        syncPreviewToEditor();
      }, 300);
    }
    
    // 处理预览区编辑器粘贴
    function handlePreviewEditorPaste(e) {
      e.preventDefault();
      const text = e.clipboardData.getData('text/plain');
      const selection = window.getSelection();
      if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        range.deleteContents();
        range.insertNode(document.createTextNode(text));
        range.collapse(false);
        selection.removeAllRanges();
        selection.addRange(range);
      }
    }
    
    // 处理预览区编辑器键盘事件
    function handlePreviewEditorKeydown(e) {
      // Ctrl+Enter 退出编辑模式
      if (e.ctrlKey && e.key === 'Enter') {
        e.preventDefault();
        exitEditMode();
      }
      
      // Esc 退出编辑模式
      if (e.key === 'Escape') {
        e.preventDefault();
        exitEditMode();
      }
      
      // Tab键处理
      if (e.key === 'Tab') {
        e.preventDefault();
        document.execCommand('insertText', false, '  ');
      }
    }
    
    // 同步预览区内容到主编辑器
    function syncPreviewToEditor() {
      const previewContent = previewEditor.textContent || previewEditor.innerText || '';
      if (previewContent !== editor.value) {
        editor.value = previewContent;
        renderMarkdown();
        updateWordCount();
      }
    }
    
    // 添加编辑模式指示器
    function addEditModeIndicator() {
      const indicator = document.createElement('div');
      indicator.id = 'edit-mode-indicator';
      indicator.className = 'edit-mode-indicator';
      indicator.textContent = '编辑模式 (Ctrl+Enter 保存, Esc 退出)';
      previewEditor.parentElement.style.position = 'relative';
      previewEditor.parentElement.appendChild(indicator);
      
      // 显示指示器
      setTimeout(() => {
        indicator.classList.add('show');
      }, 100);
      
      // 3秒后自动隐藏
      setTimeout(() => {
        indicator.classList.remove('show');
      }, 3000);
    }
    
    // 移除编辑模式指示器
    function removeEditModeIndicator() {
      const indicator = document.getElementById('edit-mode-indicator');
      if (indicator) {
        indicator.remove();
      }
    }

    // Initialize editor
    document.addEventListener('DOMContentLoaded', () => {
      // Set initial theme based on user preference
      if (localStorage.getItem('theme') === 'dark' || 
          (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        lightTheme.disabled = true;
        darkTheme.disabled = false;
      } else {
        document.documentElement.classList.remove('dark');
        lightTheme.disabled = false;
        darkTheme.disabled = true;
      }
      
      // Load sample content if no content is present
      if (!localStorage.getItem('markdown-content')) {
        editor.value = `# 欢迎使用在线Markdown编辑器\n\n这是一个简单易用的在线Markdown编辑器，支持实时预览和多种格式化工具。\n\n## 主要功能\n\n- **实时预览**：编辑内容即时渲染\n- **语法高亮**：支持代码块语法高亮\n- **主题切换**：明/暗两种主题模式\n- **文件操作**：新建、打开、保存Markdown文件\n- **快捷键**：丰富的快捷键支持\n\n## 基础语法示例\n\n### 标题\n\n# 一级标题\n## 二级标题\n### 三级标题\n\n### 文本格式\n\n**粗体文本**\n*斜体文本*\n~~删除线文本~~\n\n### 列表\n\n无序列表：\n- 项目1\n- 项目2\n  - 子项目1\n  - 子项目2\n\n有序列表：\n1. 第一步\n2. 第二步\n3. 第三步\n\n任务列表：\n- [x] 已完成任务\n- [ ] 未完成任务\n\n### 链接和图片\n\n[Markdown官网](https://daringfireball.net/projects/markdown/)\n\n![示例图片](https://picsum.photos/200/150)\n\n### 代码块\n\n\`\`\`javascript\nfunction greeting() {\n  console.log('Hello, Markdown!');\n}\n\`\`\`\n\n### 表格\n\n| 姓名 | 年龄 | 职业 |\n|------|------|------|\n| 张三 | 28   | 工程师 |\n| 李四 | 32   | 设计师 |\n\n### 引用\n\n> 这是一段引用文本\n> 可以包含多行内容\n\n### 分隔线\n\n---\n\n## 开始使用\n\n直接在左侧编辑区输入Markdown内容，右侧会实时显示预览效果。您也可以使用顶部工具栏的按钮来快速插入格式化元素。`;
      } else {
        editor.value = localStorage.getItem('markdown-content');
      }
      
      // Show loading message while waiting for libraries
      preview.innerHTML = '<div style="color: #666; padding: 1rem; text-align: center;">正在加载Markdown解析库...</div>';
      
      // Load file name
      loadFileName();
      
      // Initialize libraries and start rendering
      initializeLibraries();
      
      // Update word count
      updateWordCount();
    });
    
    // Render Markdown content
    function renderMarkdown() {
      const markdownContent = editor.value;
      
      try {
        // Check if marked is available
        if (typeof marked === 'undefined') {
          preview.innerHTML = '<div style="color: red; padding: 1rem;">Markdown解析库未加载，请检查网络连接</div>';
          return;
        }
        
        // Check if DOMPurify is available
        if (typeof DOMPurify === 'undefined') {
          preview.innerHTML = '<div style="color: red; padding: 1rem;">HTML安全库未加载，请检查网络连接</div>';
          return;
        }
        
        // Sanitize and render markdown
        let html;
        try {
          html = DOMPurify.sanitize(marked.parse(markdownContent));
        } catch (err) {
          console.error('Markdown parsing error:', err);
          html = '<div style="color: orange; padding: 1rem;">Markdown解析错误，显示原始内容：<pre>' + 
                 markdownContent.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</pre></div>';
        }
        
        preview.innerHTML = html;
        
        // Apply syntax highlighting to code blocks if hljs is available
        if (typeof hljs !== 'undefined') {
          document.querySelectorAll('pre code').forEach((block) => {
            try {
              hljs.highlightElement(block);
            } catch (err) {
              console.error('Syntax highlighting error:', err);
            }
          });
        }
        
        // Save content to localStorage
        localStorage.setItem('markdown-content', markdownContent);
        
        // Update word count
        updateWordCount();
        
      } catch (err) {
        console.error('Rendering error:', err);
        preview.innerHTML = '<div style="color: red; padding: 1rem;">渲染错误：' + err.message + 
                          '<br>原始内容：<pre>' + 
                          markdownContent.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</pre></div>';
      }
    }
    
    // Update word count
    function updateWordCount() {
      const content = editor.value.trim();
      const wordCount = content === '' ? 0 : content.split(/\s+/).length;
      wordCountBtn.textContent = `字数: ${wordCount}`;
    }
    
    // Theme toggle
    themeToggle.addEventListener('click', () => {
      if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        lightTheme.disabled = false;
        darkTheme.disabled = true;
      } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        lightTheme.disabled = true;
        darkTheme.disabled = false;
      }
      
      // Re-render to apply theme to code blocks
      renderMarkdown();
    });
    
    // File operations
    newFileBtn.addEventListener('click', () => {
      if (confirm('确定要创建新文件吗？当前未保存的内容将会丢失。')) {
        editor.value = '';
        renderMarkdown();
      }
    });
    
    openFileBtn.addEventListener('click', () => {
      fileInput.click();
    });
    
    uploadFileBtn.addEventListener('click', () => {
      fileInput.click();
    });
    
    fileInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
          editor.value = event.target.result;
          renderMarkdown();
        };
        reader.readAsText(file);
      }
    });
    
    saveFileBtn.addEventListener('click', () => {
      const content = editor.value;
      const blob = new Blob([content], { type: 'text/markdown' });
      const url = URL.createObjectURL(blob);
      
      const a = document.createElement('a');
      a.href = url;
      a.download = currentFileName;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    });
    
    // 文件名相关事件
    renameFileBtn.addEventListener('click', () => {
      startRenaming();
    });
    
    fileNameDisplay.addEventListener('click', () => {
      startRenaming();
    });
    
    // Help modal
    helpBtn.addEventListener('click', () => {
      helpModal.classList.remove('hidden');
    });
    
    closeHelpBtn.addEventListener('click', () => {
      helpModal.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    helpModal.addEventListener('click', (e) => {
      if (e.target === helpModal) {
        helpModal.classList.add('hidden');
      }
    });
    
    // Toolbar button commands
    toolbarButtons.forEach(button => {
      button.addEventListener('click', () => {
        const command = button.getAttribute('data-command');
        const value = button.getAttribute('data-value');
        executeCommand(command, value);
      });
    });
    
    // Execute editor command
    function executeCommand(command, value) {
      const selectionStart = editor.selectionStart;
      const selectionEnd = editor.selectionEnd;
      const selectedText = editor.value.substring(selectionStart, selectionEnd);
      
      let result = '';
      
      switch (command) {
        case 'heading':
          const prefix = '#'.repeat(parseInt(value)) + ' ';
          result = wrapSelectedText(selectedText, prefix, '\n\n');
          break;
          
        case 'bold':
          result = wrapSelectedText(selectedText, '**', '**');
          break;
          
        case 'italic':
          result = wrapSelectedText(selectedText, '*', '*');
          break;
          
        case 'strikethrough':
          result = wrapSelectedText(selectedText, '~~', '~~');
          break;
          
        case 'list':
          if (value === 'ul') {
            result = formatList(selectedText, '- ');
          } else if (value === 'ol') {
            result = formatList(selectedText, (index) => `${index + 1}. `);
          }
          break;
          
        case 'task':
          result = formatList(selectedText, (index, line) => {
            const isChecked = line.trim().startsWith('- [x]');
            const isUnchecked = line.trim().startsWith('- [ ]');
            if (isChecked) return '- [ ] ';
            if (isUnchecked) return '- [x] ';
            return '- [ ] ';
          });
          break;
          
        case 'link':
          if (selectedText) {
            result = `[${selectedText}](https://)`;
          } else {
            result = '[链接文本](https://)';
          }
          break;
          
        case 'image':
          if (selectedText) {
            result = `![${selectedText}](https://)`;
          } else {
            result = '![图片描述](https://)';
          }
          break;
          
        case 'code':
          if (selectedText.includes('\n')) {
            result = wrapSelectedText(selectedText, '```\n', '\n```');
          } else {
            result = wrapSelectedText(selectedText, '`', '`');
          }
          break;
          
        case 'quote':
          result = formatList(selectedText, '> ');
          break;
          
        case 'table':
          result = `| 表头1 | 表头2 | 表头3 |\n| --- | --- | --- |\n| 内容1 | 内容2 | 内容3 |\n| 内容4 | 内容5 | 内容6 |`;
          break;
          
        case 'hr':
          result = '\n---\n';
          break;
      }
      
      // Replace selected text with result
      editor.value = editor.value.substring(0, selectionStart) + result + editor.value.substring(selectionEnd);
      
      // Update cursor position
      const newCursorPosition = selectionStart + result.length;
      editor.focus();
      editor.selectionStart = newCursorPosition;
      editor.selectionEnd = newCursorPosition;
      
      // Render markdown
      renderMarkdown();
    }
    
    // Helper function to wrap selected text
    function wrapSelectedText(text, prefix, suffix) {
      if (text) {
        return prefix + text + suffix;
      } else {
        return prefix + suffix;
      }
    }
    
    // Helper function to format lists
    function formatList(text, prefix) {
      if (!text) {
        return typeof prefix === 'function' ? prefix(0) : prefix;
      }
      
      return text.split('\n').map((line, index) => {
        if (typeof prefix === 'function') {
          return prefix(index, line) + line.replace(/^[-\d.]+\s*/, '');
        }
        return prefix + line.replace(/^[-\d.]+\s*/, '');
      }).join('\n');
    }
    
    // Editor input event
    editor.addEventListener('input', () => {
      if (!isEditMode) {
        renderMarkdown();
      }
    });
    
    // 开始重命名文件名
    function startRenaming() {
      const input = document.createElement('input');
      input.type = 'text';
      input.className = 'file-name-input';
      input.value = currentFileName;
      
      // 替换显示元素
      fileNameDisplay.parentNode.replaceChild(input, fileNameDisplay);
      input.focus();
      input.select();
      
      // 处理输入事件
      const finishRenaming = () => {
        const newFileName = input.value.trim();
        if (newFileName && isValidFileName(newFileName)) {
          currentFileName = ensureMdExtension(newFileName);
          localStorage.setItem('markdown-filename', currentFileName);
        }
        
        // 恢复显示元素
        fileNameDisplay.textContent = currentFileName;
        fileNameDisplay.title = currentFileName;
        input.parentNode.replaceChild(fileNameDisplay, input);
      };
      
      input.addEventListener('blur', finishRenaming);
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          finishRenaming();
        } else if (e.key === 'Escape') {
          e.preventDefault();
          input.value = currentFileName;
          finishRenaming();
        }
      });
    }
    
    // 验证文件名有效性
    function isValidFileName(fileName) {
      // 检查是否包含无效字符
      const invalidChars = /[<>:"/\\|?*]/;
      if (invalidChars.test(fileName)) {
        showError('文件名包含无效字符：<>:"/\\|?*');
        return false;
      }
      
      // 检查是否为空
      if (fileName.trim() === '') {
        showError('文件名不能为空');
        return false;
      }
      
      return true;
    }
    
    // 确保文件名有.md扩展名
    function ensureMdExtension(fileName) {
      // 移除现有扩展名
      const nameWithoutExt = fileName.replace(/\.[^/.]+$/, '');
      
      // 确保.md扩展名
      return nameWithoutExt + '.md';
    }
    
    // 显示错误消息
    function showError(message) {
      const errorDiv = document.createElement('div');
      errorDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #e74c3c;
        color: white;
        padding: 12px 20px;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        font-size: 14px;
        max-width: 300px;
      `;
      errorDiv.textContent = message;
      
      document.body.appendChild(errorDiv);
      
      // 3秒后自动移除
      setTimeout(() => {
        if (errorDiv.parentNode) {
          errorDiv.parentNode.removeChild(errorDiv);
        }
      }, 3000);
    }
    
    // 加载保存的文件名
    function loadFileName() {
      const savedFileName = localStorage.getItem('markdown-filename');
      if (savedFileName) {
        currentFileName = savedFileName;
      }
      fileNameDisplay.textContent = currentFileName;
      fileNameDisplay.title = currentFileName;
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
      // Ctrl+S to save
      if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        saveFileBtn.click();
      }
      
      // Ctrl+N to new file
      if (e.ctrlKey && e.key === 'n') {
        e.preventDefault();
        newFileBtn.click();
      }
      
      // Ctrl+O to open file
      if (e.ctrlKey && e.key === 'o') {
        e.preventDefault();
        openFileBtn.click();
      }
      
      // Ctrl+B for bold
      if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        executeCommand('bold');
      }
      
      // Ctrl+I for italic
      if (e.ctrlKey && e.key === 'i') {
        e.preventDefault();
        executeCommand('italic');
      }
      
      // Ctrl+L for link
      if (e.ctrlKey && e.key === 'l') {
        e.preventDefault();
        executeCommand('link');
      }
      
      // Ctrl+P for image
      if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        executeCommand('image');
      }
      
      // Ctrl+K for code
      if (e.ctrlKey && e.key === 'k') {
        e.preventDefault();
        executeCommand('code');
      }
      
      // Ctrl+D to toggle theme
      if (e.ctrlKey && e.key === 'd') {
        e.preventDefault();
        themeToggle.click();
      }
      
      // F1 for help
      if (e.key === 'F1') {
        e.preventDefault();
        helpBtn.click();
      }
    });
  </script>
</body>
</html>