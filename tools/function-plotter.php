<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>函数图像绘制器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <!-- Font Awesome -->
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <!-- Math.js -->
    <script src="https://cdn.jsdelivr.net/npm/mathjs@13.0.0/lib/browser/math.min.js"></script>
    
    <style>
        .function-plotter {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .plotter-layout {
            display: flex;
            gap: 1.5rem;
            flex: 1;
        }
        
        .control-panel {
            flex: 0 0 380px;
            min-width: 350px;
            max-width: 420px;
        }
        
        .graph-panel {
            flex: 1;
            height: 600px;
            max-height: 600px;
            min-width: 0;
        }
        
        /* 大屏幕优化 (1200px+) */
        @media (min-width: 1200px) {
            .function-plotter {
                max-width: 1400px;
            }
            
            .control-panel {
                flex: 0 0 400px;
                max-width: 450px;
            }
            
            .graph-panel {
                height: 650px;
                max-height: 650px;
            }
        }
        
        /* 超大屏幕优化 (1600px+) */
        @media (min-width: 1600px) {
            .function-plotter {
                max-width: 1600px;
            }
            
            .control-panel {
                flex: 0 0 420px;
                max-width: 480px;
            }
            
            .graph-panel {
                height: 700px;
                max-height: 700px;
            }
            
            .canvas-container {
                min-height: 600px;
            }
        }
        
        /* 极大屏幕优化 (1920px+) */
        @media (min-width: 1920px) {
            .function-plotter {
                max-width: 1800px;
            }
            
            .control-panel {
                flex: 0 0 450px;
                max-width: 500px;
            }
            
            .graph-panel {
                height: 750px;
                max-height: 750px;
            }
            
            .canvas-container {
                min-height: 650px;
            }
        }
        
        .section {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .function-input-group {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .function-input {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.95rem;
            height: 44px !important;
            min-height: 44px !important;
            max-height: 44px !important;
            line-height: 1.4;
            resize: none;
            overflow: hidden;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        .function-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .btn {
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
        
        .btn-outline {
            background-color: transparent;
            color: #666;
            border: 1px solid #e0e0e0;
        }
        
        .btn-outline:hover {
            background-color: #f8f9fa;
            border-color: #bdc3c7;
        }
        
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .function-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
        }
        
        .function-item:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        .function-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }
        
        .function-color {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 1px solid rgba(0,0,0,0.1);
        }
        
        .function-expr {
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.9rem;
            color: #2c3e50;
        }
        
        .function-actions {
            display: flex;
            gap: 0.25rem;
        }
        
        .icon-btn {
            width: 32px;
            height: 32px;
            border: none;
            background-color: transparent;
            color: #666;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .icon-btn:hover {
            background-color: #e9ecef;
            color: #2c3e50;
        }
        
        .icon-btn.active {
            color: #3498db;
        }
        
        .icon-btn.danger:hover {
            color: #e74c3c;
        }
        
        .axis-controls {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .axis-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .axis-group label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }
        
        .axis-input {
            padding: 0.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 0.9rem;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        .axis-input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .parameter-control {
            margin-bottom: 1rem;
        }
        
        .parameter-slider {
            width: 100%;
            margin: 0.5rem 0;
        }
        
        .parameter-value {
            display: inline-block;
            background-color: #e9ecef;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.85rem;
            min-width: 60px;
            text-align: center;
        }
        
        .examples-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 0.5rem;
        }
        
        .example-btn {
            padding: 0.5rem;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Consolas', 'Monaco', monospace;
        }
        
        .example-btn:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        .graph-container {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .graph-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .graph-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .graph-tools {
            display: flex;
            gap: 0.5rem;
        }
        
        .canvas-container {
            flex: 1;
            position: relative;
            min-height: 500px;
        }
        
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            display: none;
        }
        
        .error-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(231, 76, 60, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            display: none;
        }
        
        .error-content {
            text-align: center;
            padding: 2rem;
        }
        
        .error-icon {
            font-size: 3rem;
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        
        .error-message {
            color: #e74c3c;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .error-hint {
            color: #666;
            font-size: 0.9rem;
        }
        
        .info-box {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .info-box h4 {
            color: #1976d2;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .info-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.25rem;
            font-size: 0.85rem;
            color: #666;
        }
        
        .mouse-position {
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.85rem;
            color: #666;
            text-align: right;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .plotter-layout {
                flex-direction: column;
            }
            
            .control-panel {
                max-width: none;
                min-width: auto;
            }
            
            .axis-controls {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>函数图像绘制器</h1>
            <div class="function-plotter">
                <div class="plotter-layout">
                    <!-- 控制面板 -->
                    <div class="control-panel">
                        <!-- 函数输入 -->
                        <div class="section">
                            <div class="section-title">
                                <i class="fa fa-pencil-square-o"></i>
                                函数输入
                            </div>
                            
                            <div class="function-input-group">
                                <input type="text" id="functionInput" class="function-input" 
                                       placeholder="输入函数表达式，如: sin(x)、x^2、e^x" value="sin(x)">
                                <button id="addFunctionBtn" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> 添加
                                </button>
                            </div>
                            <div class="examples-grid">
                                <button class="example-btn" data-func="sin(x)">sin(x)</button>
                                <button class="example-btn" data-func="x^2">x^2</button>
                                <button class="example-btn" data-func="e^x">e^x</button>
                                <button class="example-btn" data-func="log(x)">log(x)</button>
                                <button class="example-btn" data-func="abs(x)">abs(x)</button>
                                <button class="example-btn" data-func="sqrt(x)">√x</button>
                                <button class="example-btn" data-func="x^3 - x">x^3 - x</button>
                                <button class="example-btn" data-func="k * sin(x)">k*sin(x)</button>
                                <button class="example-btn" data-func="a * x^2 + b * x + c">ax²+bx+c</button>
                                <button class="example-btn" data-func="sin(k * x)">sin(kx)</button>
                            </div>
                            <div class="info-box">
                                <h4>支持的函数和运算符</h4>
                                <div class="info-list">
                                    <div><i class="fa fa-check" style="color: #22c55e;"></i> 基本运算：+ - * / ^</div>
                                    <div><i class="fa fa-check" style="color: #22c55e;"></i> 三角函数：sin, cos, tan</div>
                                    <div><i class="fa fa-check" style="color: #22c55e;"></i> 对数函数：log, ln</div>
                                    <div><i class="fa fa-check" style="color: #22c55e;"></i> 指数函数：exp</div>
                                    <div><i class="fa fa-check" style="color: #22c55e;"></i> 根号函数：sqrt</div>
                                    <div><i class="fa fa-check" style="color: #22c55e;"></i> 常量：pi, e</div>
                                    <div><i class="fa fa-check" style="color: #22c55e;"></i> 变量：x</div>
                                    <div><i class="fa fa-check" style="color: #3498db;"></i> 参数：a, b, c, k</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 当前函数列表 -->
                        <div class="section">
                            <div class="section-title">
                                <i class="fa fa-list"></i>
                                当前函数
                            </div>
                            <div id="functionsList">
                                <!-- 函数列表将在这里动态生成 -->
                            </div>
                        </div>
                        
                        <!-- 参数控制 -->
                        <div class="section" id="parameterSection" style="display: none;">
                            <div class="section-title">
                                <i class="fa fa-sliders"></i>
                                参数控制
                            </div>
                            
                            <div id="parameterControls">
                                <!-- 参数控制项将在这里动态生成 -->
                            </div>
                            
                            <div id="animationControls" style="margin-top: 1rem; display: none;">
                                <button id="animateBtn" class="btn btn-secondary" style="width: 100%;">
                                    <i class="fa fa-play"></i> 播放动画
                                </button>
                            </div>
                        </div>
                        

                        
                        <!-- 操作按钮 -->
                        <div style="display: flex; gap: 0.5rem;">
                            <button id="clearAllBtn" class="btn btn-outline" style="flex: 1;">
                                <i class="fa fa-eraser"></i> 清除所有
                            </button>
                            <button id="saveImageBtn" class="btn btn-primary" style="flex: 1;">
                                <i class="fa fa-download"></i> 保存图像
                            </button>
                        </div>
                    </div>
                    
                    <!-- 图像面板 -->
                    <div class="graph-panel">
                        <div class="graph-container">
                            <div class="graph-header">
                                <div class="graph-title">
                                    <i class="fa fa-line-chart"></i> 函数图像
                                </div>
                                <div class="graph-tools">
                                    <button id="zoomInBtn" class="icon-btn" title="放大">
                                        <i class="fa fa-search-plus"></i>
                                    </button>
                                    <button id="zoomOutBtn" class="icon-btn" title="缩小">
                                        <i class="fa fa-search-minus"></i>
                                    </button>
                                    <button id="resetZoomBtn" class="icon-btn" title="重置视图">
                                        <i class="fa fa-arrows-alt"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="canvas-container">
                                <canvas id="graphCanvas"></canvas>
                                <div id="loadingOverlay" class="loading-overlay">
                                    <div style="text-align: center;">
                                        <div class="spinner"></div>
                                        <p style="margin-top: 1rem; color: #666;">绘制中...</p>
                                    </div>
                                </div>
                                <div id="errorOverlay" class="error-overlay">
                                    <div class="error-content">
                                        <div class="error-icon">
                                            <i class="fa fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="error-message" id="errorMessage">函数表达式错误</div>
                                        <div class="error-hint">请检查函数表达式格式是否正确</div>
                                        <button id="closeErrorBtn" class="btn btn-outline" style="margin-top: 1rem;">
                                            关闭
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mouse-position" id="mousePosition">
                                (x: 0, y: 0)
                            </div>
                        </div>
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

        // 全局变量
        let graphChart = null;
        let functions = [];
        let paramK = 1.0;
        let paramA = 1.0;
        let paramB = 1.0;
        let paramC = 1.0;
        let isAnimating = false;
        let animationInterval = null;
        let nextColorIndex = 0;
        let intersections = [];
        let showIntersectionTooltip = true;
        
        // 参数配置
        const parameterConfigs = {
            'a': { min: -5, max: 5, step: 0.1, default: 1.0, value: 1.0 },
            'b': { min: -5, max: 5, step: 0.1, default: 1.0, value: 1.0 },
            'c': { min: -5, max: 5, step: 0.1, default: 1.0, value: 1.0 },
            'k': { min: 0, max: 5, step: 0.1, default: 1.0, value: 1.0 }
        };
        
        // DOM 元素
        const functionInput = document.getElementById('functionInput');
        const addFunctionBtn = document.getElementById('addFunctionBtn');
        const functionsList = document.getElementById('functionsList');
        const animateBtn = document.getElementById('animateBtn');
        const clearAllBtn = document.getElementById('clearAllBtn');
        const saveImageBtn = document.getElementById('saveImageBtn');
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');
        const resetZoomBtn = document.getElementById('resetZoomBtn');
        const graphCanvas = document.getElementById('graphCanvas');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const errorOverlay = document.getElementById('errorOverlay');
        const errorMessage = document.getElementById('errorMessage');
        const closeErrorBtn = document.getElementById('closeErrorBtn');
        const mousePosition = document.getElementById('mousePosition');
        const parameterSection = document.getElementById('parameterSection');
        
        // 检测函数表达式中包含哪些参数 a, b, c, k
        function getUsedParameters(expr) {
            const params = [];
            const paramRegex = /\b([abck])\b/g;
            let match;
            while ((match = paramRegex.exec(expr)) !== null) {
                if (!params.includes(match[1])) {
                    params.push(match[1]);
                }
            }
            return params;
        }
        
        // 获取所有可见函数中使用的参数
        function getAllUsedParameters() {
            const visibleFunctions = functions.filter(f => f.visible);
            const allParams = new Set();
            
            visibleFunctions.forEach(func => {
                const params = getUsedParameters(func.expr);
                params.forEach(param => allParams.add(param));
            });
            
            return Array.from(allParams).sort();
        }
        
        // 生成参数控制项
        function generateParameterControls() {
            const parameterControls = document.getElementById('parameterControls');
            const animationControls = document.getElementById('animationControls');
            const usedParams = getAllUsedParameters();
            
            // 清空现有的控制项
            parameterControls.innerHTML = '';
            
            // 如果没有使用参数，隐藏参数控制部分
            if (usedParams.length === 0) {
                parameterSection.style.display = 'none';
                // 如果没有参数函数，停止动画
                if (isAnimating) {
                    stopAnimation();
                }
                return;
            }
            
            // 显示参数控制部分
            parameterSection.style.display = 'block';
            
            // 为每个使用的参数生成控制项
            usedParams.forEach(param => {
                const config = parameterConfigs[param];
                const controlDiv = document.createElement('div');
                controlDiv.className = 'parameter-control';
                controlDiv.id = `param-${param}-control`;
                
                controlDiv.innerHTML = `
                    <label style="font-size: 0.9rem; color: #666; font-weight: 500;">参数 ${param}</label>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="range" id="param${param.toUpperCase()}Slider" class="parameter-slider" 
                               min="${config.min}" max="${config.max}" step="${config.step}" value="${config.value}">
                        <input type="number" class="parameter-value-input" id="param${param.toUpperCase()}Value" 
                               value="${config.value}" min="0.1" max="20" step="0.1" 
                               style="width: 80px; padding: 0.25rem; border: 1px solid #e0e0e0; border-radius: 4px; text-align: center;">
                    </div>
                `;
                
                parameterControls.appendChild(controlDiv);
                
                // 使用 setTimeout 确保DOM元素已经创建完成
                setTimeout(() => {
                    // 绑定滑块和输入框事件
                    const slider = document.getElementById(`param${param.toUpperCase()}Slider`);
                    const valueInput = document.getElementById(`param${param.toUpperCase()}Value`);
                    
                    if (slider && valueInput) {
                        // 滑动条事件
                        slider.addEventListener('input', () => {
                            const value = parseFloat(slider.value);
                            valueInput.value = value.toFixed(1);
                            // 更新全局参数值
                            window[`param${param.toUpperCase()}`] = value;
                            parameterConfigs[param].value = value;
                            updateChart();
                        });
                        
                        // 数值输入框事件
                        valueInput.addEventListener('input', () => {
                            const newValue = parseFloat(valueInput.value);
                            if (!isNaN(newValue) && newValue > 0 && newValue <= 20) {
                                // 更新滑动条范围：-newValue 到 newValue
                                slider.min = -newValue;
                                slider.max = newValue;
                                parameterConfigs[param].min = -newValue;
                                parameterConfigs[param].max = newValue;
                                
                                // 如果当前值超出新范围，调整到新范围内
                                const currentValue = window[`param${param.toUpperCase()}`];
                                if (currentValue > newValue) {
                                    slider.value = newValue;
                                    window[`param${param.toUpperCase()}`] = newValue;
                                    parameterConfigs[param].value = newValue;
                                    valueInput.value = newValue.toFixed(1);
                                } else if (currentValue < -newValue) {
                                    slider.value = -newValue;
                                    window[`param${param.toUpperCase()}`] = -newValue;
                                    parameterConfigs[param].value = -newValue;
                                    valueInput.value = (-newValue).toFixed(1);
                                }
                                
                                updateChart();
                            } else {
                                // 如果输入无效，恢复为上一个有效值
                                valueInput.value = window[`param${param.toUpperCase()}`].toFixed(1);
                            }
                        });
                        
                        // 添加blur事件，确保输入值在有效范围内
                        valueInput.addEventListener('blur', () => {
                            const inputValue = parseFloat(valueInput.value);
                            if (isNaN(inputValue) || inputValue <= 0 || inputValue > 20) {
                                // 恢复为当前参数值
                                valueInput.value = window[`param${param.toUpperCase()}`].toFixed(1);
                            } else {
                                // 确保显示值是当前参数的绝对值（范围值）
                                const currentValue = window[`param${param.toUpperCase()}`];
                                const absoluteValue = Math.abs(currentValue);
                                if (absoluteValue !== inputValue) {
                                    // 如果用户修改了范围值，重新触发input事件
                                    valueInput.value = inputValue;
                                    valueInput.dispatchEvent(new Event('input'));
                                }
                            }
                        });
                    }
                }, 0);
            });
            
            // 如果只有一个参数，显示动画控制
            if (usedParams.length === 1) {
                animationControls.style.display = 'block';
            } else {
                animationControls.style.display = 'none';
                // 如果有多个参数，停止动画
                if (isAnimating) {
                    stopAnimation();
                }
            }
        }
        
        // 更新参数控制卡片的显示状态
        function updateParameterSectionVisibility() {
            generateParameterControls();
        }
        
        // 颜色列表
        const functionColors = [
            '#3b82f6', // 蓝色
            '#ef4444', // 红色
            '#22c55e', // 绿色
            '#f59e0b', // 橙色
            '#8b5cf6', // 紫色
            '#ec4899', // 粉色
            '#06b6d4', // 青色
            '#84cc16', // 酸橙绿
        ];
        
        // 初始化
        document.addEventListener('DOMContentLoaded', () => {
            // 等待外部库加载
            setTimeout(() => {
                if (typeof Chart !== 'undefined' && typeof math !== 'undefined') {
                    initChart();
                    addExampleFunctions();
                    bindEvents();
                    updateChart();
                } else {
                    showError('外部库加载失败，请检查网络连接');
                }
            }, 1000);
        });
        
        // 初始化图表
        function initChart() {
            const ctx = graphCanvas.getContext('2d');
            
            graphChart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 0
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'center',
                            min: -10,
                            max: 10,
                            title: {
                                display: true,
                                text: 'x',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        y: {
                            type: 'linear',
                            min: -10,
                            max: 10,
                            title: {
                                display: true,
                                text: 'y',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const x = context.parsed.x.toFixed(2);
                                    const y = context.parsed.y.toFixed(2);
                                    return `${label}: (${x}, ${y})`;
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    elements: {
                        line: {
                            tension: 0.1
                        },
                        point: {
                            radius: 0,
                            hoverRadius: 4
                        }
                    }
                }
            });
            
            // 监听鼠标移动事件
            graphCanvas.addEventListener('mousemove', (event) => {
                const rect = graphCanvas.getBoundingClientRect();
                const mouseX = event.clientX - rect.left;
                const mouseY = event.clientY - rect.top;
                const x = (mouseX / rect.width) * (graphChart.options.scales.x.max - graphChart.options.scales.x.min) + graphChart.options.scales.x.min;
                const y = ((rect.height - mouseY) / rect.height) * (graphChart.options.scales.y.max - graphChart.options.scales.y.min) + graphChart.options.scales.y.min;
                
                // 查找最近的交叉点
                const nearestIntersection = findNearestIntersection(mouseX, mouseY);
                
                if (nearestIntersection && showIntersectionTooltip) {
                    // 显示交叉点坐标
                    mousePosition.innerHTML = `<span style="color: #e74c3c; font-weight: bold;">交叉点: (${nearestIntersection.x.toFixed(3)}, ${nearestIntersection.y.toFixed(3)})</span> | ${nearestIntersection.label}`;
                } else {
                    // 显示普通鼠标坐标
                    mousePosition.textContent = `(x: ${x.toFixed(2)}, y: ${y.toFixed(2)})`;
                }
            });
            
            graphCanvas.addEventListener('mouseleave', () => {
                mousePosition.textContent = '(x: 0, y: 0)';
            });
            
            // 坐标轴已固定，禁用鼠标滚轮缩放功能
            graphCanvas.addEventListener('wheel', (event) => {
                event.preventDefault();
                // 坐标轴范围固定，不执行缩放操作
            });
            
            // 坐标轴已固定，禁用触摸缩放功能
            // 触摸屏缩放相关代码已移除
            
            // 拖动功能支持
            let isDragging = false;
            let dragStartX = 0;
            let dragStartY = 0;
            let dragStartXMin, dragStartXMax, dragStartYMin, dragStartYMax;
            
            // 鼠标按下事件
            graphCanvas.addEventListener('mousedown', (event) => {
                // 只在左键点击且没有双指触摸时启用拖动
                if (event.button === 0 && event.touches === undefined) {
                    isDragging = true;
                    dragStartX = event.clientX;
                    dragStartY = event.clientY;
                    
                    // 记录当前坐标轴范围
                    dragStartXMin = graphChart.options.scales.x.min;
                    dragStartXMax = graphChart.options.scales.x.max;
                    dragStartYMin = graphChart.options.scales.y.min;
                    dragStartYMax = graphChart.options.scales.y.max;
                    
                    // 改变鼠标样式
                    graphCanvas.style.cursor = 'grabbing';
                    
                    event.preventDefault();
                }
            });
            
            // 鼠标移动事件（拖动）- 坐标轴已固定，不更新坐标轴
            graphCanvas.addEventListener('mousemove', (event) => {
                if (isDragging) {
                    // 坐标轴已固定，拖动不改变坐标轴范围
                    // 只保持拖动状态用于UI反馈
                }
            });
            
            // 鼠标释放事件
            graphCanvas.addEventListener('mouseup', () => {
                if (isDragging) {
                    isDragging = false;
                    graphCanvas.style.cursor = 'default';
                }
            });
            
            // 鼠标离开画布事件
            graphCanvas.addEventListener('mouseleave', () => {
                if (isDragging) {
                    isDragging = false;
                    graphCanvas.style.cursor = 'default';
                }
                mousePosition.textContent = '(x: 0, y: 0)';
            });
            
            // 触摸拖动支持（单指拖动）- 坐标轴已固定，不更新坐标轴
            let touchIsDragging = false;
            let touchStartX = 0;
            let touchStartY = 0;
            let touchDragStartXMin, touchDragStartXMax, touchDragStartYMin, touchDragStartYMax;
            
            // 触摸开始事件 - 坐标轴已固定，不处理缩放
            graphCanvas.addEventListener('touchstart', (event) => {
                if (event.touches.length === 1) {
                    // 单指触摸 - 启用拖动状态，但不更新坐标轴
                    touchIsDragging = true;
                    touchStartX = event.touches[0].clientX;
                    touchStartY = event.touches[0].clientY;
                    
                    event.preventDefault();
                }
            });
            
            // 触摸移动事件 - 坐标轴已固定，不更新坐标轴
            graphCanvas.addEventListener('touchmove', (event) => {
                if (event.touches.length === 1 && touchIsDragging) {
                    // 单指触摸 - 处理拖动状态，但不更新坐标轴
                    event.preventDefault();
                    // 坐标轴已固定，拖动不改变坐标轴范围
                }
            });
            
            // 触摸结束事件
            graphCanvas.addEventListener('touchend', (event) => {
                touchIsDragging = false;
            });
        }
        
        // 添加示例函数
        function addExampleFunctions() {
            // 清空列表
            functionsList.innerHTML = '';
            functions = [];
            
            // 添加示例函数
            const examples = ['sin(x)', 'x^2', 'e^x', 'log(x)'];
            examples.forEach(expr => {
                addFunction(expr);
            });
            
            // 初始化参数控制卡片显示状态
            updateParameterSectionVisibility();
        }
        
        // 绑定事件
        function bindEvents() {
            // 添加函数按钮
            addFunctionBtn.addEventListener('click', () => {
                const expr = functionInput.value.trim();
                if (expr) {
                    addFunction(expr);
                    functionInput.value = '';
                    functionInput.focus();
                }
            });
            
            // 函数输入框回车事件
            functionInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    addFunctionBtn.click();
                }
            });
            
            // 应用坐标轴范围按钮 - 由于HTML中没有对应的输入框，暂时注释掉
            // applyAxisBtn.addEventListener('click', () => {
            //     const xMin = parseFloat(xMinInput.value);
            //     const xMax = parseFloat(xMaxInput.value);
            //     const yMin = parseFloat(yMinInput.value);
            //     const yMax = parseFloat(yMaxInput.value);
            //     
            //     if (!isNaN(xMin) && !isNaN(xMax) && !isNaN(yMin) && !isNaN(yMax)) {
            //         graphChart.options.scales.x.min = xMin;
            //         graphChart.options.scales.x.max = xMax;
            //         graphChart.options.scales.y.min = yMin;
            //         graphChart.options.scales.y.max = yMax;
            //         graphChart.update();
            //         updateChart();
            //     }
            // });
            
            // 参数滑块（现在已移除，改为动态生成）
            // paramSlider.addEventListener('input', () => {
            //     paramK = parseFloat(paramSlider.value);
            //     paramValue.textContent = paramK.toFixed(1);
            //     updateChart();
            // });
            
            // 播放动画按钮
            animateBtn.addEventListener('click', toggleAnimation);
            
            // 清除所有按钮
            clearAllBtn.addEventListener('click', clearAllFunctions);
            
            // 保存图像按钮
            saveImageBtn.addEventListener('click', saveImage);
            
            // 缩放按钮 - 坐标轴已固定，显示提示信息
            zoomInBtn.addEventListener('click', () => {
                // 坐标轴已固定，不执行缩放操作，显示提示
                showError('坐标轴已固定，无法缩放。请使用重置视图按钮恢复默认范围。');
            });
            
            zoomOutBtn.addEventListener('click', () => {
                // 坐标轴已固定，不执行缩放操作，显示提示
                showError('坐标轴已固定，无法缩放。请使用重置视图按钮恢复默认范围。');
            });
            
            resetZoomBtn.addEventListener('click', () => {
                graphChart.options.scales.x.min = -10;
                graphChart.options.scales.x.max = 10;
                graphChart.options.scales.y.min = -10;
                graphChart.options.scales.y.max = 10;
                
                graphChart.update();
                updateChart();
            });
            
            // 关闭错误按钮
            closeErrorBtn.addEventListener('click', () => {
                errorOverlay.style.display = 'none';
            });
            
            // 示例函数按钮
            document.querySelectorAll('.example-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    functionInput.value = btn.dataset.func;
                    functionInput.focus();
                });
            });
        }
        
        // 添加函数
        function addFunction(expr) {
            try {
                // 检查表达式是否有效
                const compiled = math.compile(expr);
                
                // 获取颜色
                const color = functionColors[nextColorIndex % functionColors.length];
                nextColorIndex++;
                
                // 创建函数对象
                const func = {
                    id: Date.now(),
                    expr: expr,
                    compiled: compiled,
                    color: color,
                    visible: true
                };
                
                // 添加到函数列表
                functions.push(func);
                
                // 更新UI
                updateFunctionsList();
                
                // 更新参数控制卡片显示状态
                updateParameterSectionVisibility();
                
                // 更新图表
                updateChart();
                
                return true;
            } catch (error) {
                showError(`函数表达式错误: ${error.message}`);
                return false;
            }
        }
        
        // 更新函数列表UI
        function updateFunctionsList() {
            functionsList.innerHTML = '';
            
            functions.forEach(func => {
                const item = document.createElement('div');
                item.className = 'function-item';
                item.dataset.id = func.id;
                
                item.innerHTML = `
                    <div class="function-info">
                        <div class="function-color" style="background-color: ${func.color};"></div>
                        <span class="function-expr">${func.expr}</span>
                    </div>
                    <div class="function-actions">
                        <button class="icon-btn ${func.visible ? 'active' : ''}" onclick="toggleFunctionVisibility(${func.id})" title="显示/隐藏">
                            <i class="fa fa-eye${func.visible ? '' : '-slash'}"></i>
                        </button>
                        <button class="icon-btn danger" onclick="removeFunction(${func.id})" title="删除">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                `;
                
                functionsList.appendChild(item);
            });
        }
        
        // 切换函数可见性
        function toggleFunctionVisibility(id) {
            const func = functions.find(f => f.id === id);
            if (func) {
                func.visible = !func.visible;
                updateFunctionsList();
                updateParameterSectionVisibility();
                updateChart();
            }
        }
        
        // 移除函数
        function removeFunction(id) {
            const index = functions.findIndex(f => f.id === id);
            if (index !== -1) {
                functions.splice(index, 1);
                updateFunctionsList();
                updateParameterSectionVisibility();
                updateChart();
            }
        }
        
        // 清除所有函数
        function clearAllFunctions() {
            functions = [];
            updateFunctionsList();
            updateParameterSectionVisibility();
            updateChart();
        }
        
        // 计算函数交叉点
        function calculateIntersections() {
            intersections = [];
            const visibleFunctions = functions.filter(f => f.visible);
            
            if (visibleFunctions.length < 2) return;
            
            const xMin = graphChart.options.scales.x.min;
            const xMax = graphChart.options.scales.x.max;
            const step = (xMax - xMin) / 2000; // 更高精度
            
            for (let i = 0; i < visibleFunctions.length; i++) {
                for (let j = i + 1; j < visibleFunctions.length; j++) {
                    const func1 = visibleFunctions[i];
                    const func2 = visibleFunctions[j];
                    
                    let lastDiff = null;
                    let lastX = null;
                    
                    for (let x = xMin; x <= xMax; x += step) {
                        try {
                            const scope = { 
                                x: x, 
                                k: paramK, 
                                a: paramA, 
                                b: paramB, 
                                c: paramC, 
                                pi: Math.PI, 
                                e: Math.E 
                            };
                            const y1 = func1.compiled.evaluate(scope);
                            const y2 = func2.compiled.evaluate(scope);
                            
                            if (!isNaN(y1) && isFinite(y1) && !isNaN(y2) && isFinite(y2)) {
                                const diff = y1 - y2;
                                
                                // 检测符号变化，表示可能有交叉
                                if (lastDiff !== null && lastDiff * diff < 0) {
                                    // 使用二分法精确定位交叉点
                                    const intersectionX = findIntersectionX(func1, func2, lastX, x);
                                    
                                    if (intersectionX !== null) {
                                        try {
                                            const intersectionScope = { x: intersectionX, k: paramK, pi: Math.PI, e: Math.E };
                                            const intersectionY = func1.compiled.evaluate(intersectionScope);
                                            
                                            if (!isNaN(intersectionY) && isFinite(intersectionY)) {
                                                intersections.push({
                                                    x: intersectionX,
                                                    y: intersectionY,
                                                    func1: func1,
                                                    func2: func2,
                                                    label: `${func1.expr} = ${func2.expr}`
                                                });
                                            }
                                        } catch (error) {
                                            // 忽略计算错误
                                        }
                                    }
                                }
                                
                                lastDiff = diff;
                                lastX = x;
                            }
                        } catch (error) {
                            // 忽略计算错误
                        }
                    }
                }
            }
        }
        
        // 使用二分法找到精确的交叉点
        function findIntersectionX(func1, func2, x1, x2, maxIterations = 10) {
            try {
                for (let i = 0; i < maxIterations; i++) {
                    const midX = (x1 + x2) / 2;
                    const scope = { 
                        x: midX, 
                        k: paramK, 
                        a: paramA, 
                        b: paramB, 
                        c: paramC, 
                        pi: Math.PI, 
                        e: Math.E 
                    };
                    
                    const y1_mid = func1.compiled.evaluate(scope);
                    const y2_mid = func2.compiled.evaluate(scope);
                    const diff_mid = y1_mid - y2_mid;
                    
                    if (Math.abs(diff_mid) < 1e-6 || Math.abs(x2 - x1) < 1e-6) {
                        return midX;
                    }
                    
                    const scope1 = { 
                        x: x1, 
                        k: paramK, 
                        a: paramA, 
                        b: paramB, 
                        c: paramC, 
                        pi: Math.PI, 
                        e: Math.E 
                    };
                    const y1_x1 = func1.compiled.evaluate(scope1);
                    const y2_x1 = func2.compiled.evaluate(scope1);
                    const diff_x1 = y1_x1 - y2_x1;
                    
                    if (diff_x1 * diff_mid <= 0) {
                        x2 = midX;
                    } else {
                        x1 = midX;
                    }
                }
                
                return (x1 + x2) / 2;
            } catch (error) {
                return null;
            }
        }
        
        // 查找最近的交叉点
        function findNearestIntersection(mouseX, mouseY) {
            if (intersections.length === 0) return null;
            
            const rect = graphCanvas.getBoundingClientRect();
            const xMin = graphChart.options.scales.x.min;
            const xMax = graphChart.options.scales.x.max;
            const yMin = graphChart.options.scales.y.min;
            const yMax = graphChart.options.scales.y.max;
            
            let nearestIntersection = null;
            let minDistance = Infinity;
            
            intersections.forEach(intersection => {
                // 将交叉点坐标转换为画布坐标
                const canvasX = ((intersection.x - xMin) / (xMax - xMin)) * rect.width;
                const canvasY = ((yMax - intersection.y) / (yMax - yMin)) * rect.height;
                
                // 计算距离
                const distance = Math.sqrt(
                    Math.pow(canvasX - mouseX, 2) + Math.pow(canvasY - mouseY, 2)
                );
                
                // 设置阈值，只有距离小于30像素才认为是"接近"
                if (distance < 30 && distance < minDistance) {
                    minDistance = distance;
                    nearestIntersection = intersection;
                }
            });
            
            return nearestIntersection;
        }
        
        // 更新图表
        function updateChart() {
            try {
                showLoading();
                
                // 清空数据集
                graphChart.data.datasets = [];
                
                // 为每个可见函数创建数据集
                functions.forEach(func => {
                    if (func.visible) {
                        // 生成数据点
                        const xMin = graphChart.options.scales.x.min;
                        const xMax = graphChart.options.scales.x.max;
                        const points = 1000;
                        const step = (xMax - xMin) / points;
                        
                        const data = [];
                        
                        for (let i = 0; i <= points; i++) {
                            const x = xMin + i * step;
                            
                            try {
                                // 计算函数值，替换所有参数
                                const scope = { 
                                    x: x, 
                                    k: paramK, 
                                    a: paramA, 
                                    b: paramB, 
                                    c: paramC, 
                                    pi: Math.PI, 
                                    e: Math.E 
                                };
                                const y = func.compiled.evaluate(scope);
                                
                                // 检查是否为有效数字
                                if (!isNaN(y) && isFinite(y)) {
                                    data.push({ x: x, y: y });
                                }
                            } catch (error) {
                                // 忽略计算错误的点
                            }
                        }
                        
                        // 创建数据集
                        const dataset = {
                            label: func.expr,
                            data: data,
                            borderColor: func.color,
                            backgroundColor: func.color + '20',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.1,
                            pointRadius: 0,
                            pointHoverRadius: 4,
                            pointBackgroundColor: func.color,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1
                        };
                        
                        graphChart.data.datasets.push(dataset);
                    }
                });
                
                // 计算交叉点
                calculateIntersections();
                
                // 更新图表
                graphChart.update();
                
                hideLoading();
            } catch (error) {
                hideLoading();
                showError(`更新图表时出错: ${error.message}`);
            }
        }
        
        // 切换动画
        function toggleAnimation() {
            if (isAnimating) {
                stopAnimation();
            } else {
                startAnimation();
            }
        }
        
        // 开始动画
        function startAnimation() {
            if (isAnimating) return;
            
            // 获取当前唯一的参数
            const usedParams = getAllUsedParameters();
            if (usedParams.length !== 1) {
                alert('动画功能只支持单个参数的函数');
                return;
            }
            
            const paramName = usedParams[0];
            const config = parameterConfigs[paramName];
            
            // 确保DOM元素存在
            setTimeout(() => {
                const slider = document.getElementById(`param${paramName.toUpperCase()}Slider`);
                const valueSpan = document.getElementById(`param${paramName.toUpperCase()}Value`);
                
                if (!slider || !valueSpan) {
                    console.error('动画控件未找到');
                    return;
                }
                
                isAnimating = true;
                animateBtn.innerHTML = '<i class="fa fa-stop"></i> 停止动画';
                animateBtn.classList.remove('btn-secondary');
                animateBtn.classList.add('btn-danger');
                
                let direction = 1;
                let step = 0.05;
                
                animationInterval = setInterval(() => {
                    // 确保获取到当前正确的参数值
                    const currentValue = parameterConfigs[paramName].value || 
                                       window[`param${paramName.toUpperCase()}`] || 
                                       parameterConfigs[paramName].default;
                    
                    let newValue = currentValue + step * direction;
                    
                    // 边界检测
                    if (newValue >= config.max) {
                        newValue = config.max;
                        direction = -1;
                    } else if (newValue <= config.min) {
                        newValue = config.min;
                        direction = 1;
                    }
                    
                    // 更新全局参数值
                    window[`param${paramName.toUpperCase()}`] = newValue;
                    parameterConfigs[paramName].value = newValue;
                    
                    // 更新UI
                    slider.value = newValue;
                    valueSpan.value = newValue.toFixed(1);
                    
                    // 更新图表
                    updateChart();
                }, 50);
            }, 100);
        }
        
        // 停止动画
        function stopAnimation() {
            if (!isAnimating) return;
            
            isAnimating = false;
            animateBtn.innerHTML = '<i class="fa fa-play"></i> 播放动画';
            animateBtn.classList.remove('btn-danger');
            animateBtn.classList.add('btn-secondary');
            
            clearInterval(animationInterval);
        }
        
        // 保存图像
        function saveImage() {
            try {
                const link = document.createElement('a');
                link.download = '函数图像.png';
                link.href = graphCanvas.toDataURL('image/png');
                link.click();
            } catch (error) {
                showError(`保存图像时出错: ${error.message}`);
            }
        }
        
        // 显示加载中
        function showLoading() {
            loadingOverlay.style.display = 'flex';
        }
        
        // 隐藏加载中
        function hideLoading() {
            loadingOverlay.style.display = 'none';
        }
        
        // 显示错误
        function showError(message) {
            errorMessage.textContent = message;
            errorOverlay.style.display = 'flex';
        }
    </script>
</body>
</html>