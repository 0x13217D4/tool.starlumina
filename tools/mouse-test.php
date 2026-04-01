<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>鼠标测试 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .mouse-test-area {
            position: relative;
            background: linear-gradient(145deg, #f8f9fa, #e9ecef);
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            height: 400px;
            overflow: hidden;
            cursor: crosshair;
            margin-bottom: 2rem;
        }
        
        .mouse-visualization {
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(52, 152, 219, 0.8);
            pointer-events: none;
            transform: translate(-50%, -50%);
            transition: all 0.1s ease;
            box-shadow: 0 0 10px rgba(52, 152, 219, 0.5);
        }
        
        .click-point {
            position: absolute;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            pointer-events: none;
            transform: translate(-50%, -50%);
            animation: clickRipple 0.6s ease-out;
        }
        
        @keyframes clickRipple {
            0% {
                width: 30px;
                height: 30px;
                opacity: 1;
            }
            100% {
                width: 80px;
                height: 80px;
                opacity: 0;
            }
        }
        
        .mouse-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .mouse-button {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .mouse-button.active {
            background: #007bff;
            color: white;
            border-color: #0056b3;
            transform: scale(0.95);
            box-shadow: 0 0 0 3px rgba(0,123,255,0.25);
        }
        
        .mouse-button.left { border-left: 4px solid #28a745; }
        .mouse-button.right { border-right: 4px solid #dc3545; }
        .mouse-button.middle { border-top: 4px solid #ffc107; border-bottom: 4px solid #ffc107; }
        .mouse-button.extra { border: 4px solid #6f42c1; }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
        }
        
        .stat-label {
            color: #6c757d;
            margin-top: 0.5rem;
        }
        
        .wheel-indicator {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .wheel-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }
        
        .trajectory-canvas {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: white;
            margin-bottom: 2rem;
        }
        
        .coordinates {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .coordinate-item {
            text-align: center;
        }
        
        .coordinate-value {
            font-weight: bold;
            color: #007bff;
        }
        
        .instructions {
            background: #e9ecef;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        
        .instructions h3 {
            margin-top: 0;
            color: #495057;
        }
        
        .instructions ul {
            margin-bottom: 0;
        }
        
        .controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        
        .btn:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }
        
        .btn.secondary {
            background: #6c757d;
        }
        
        .btn.secondary:hover {
            background: #545b62;
        }
        
        @media (max-width: 768px) {
            .mouse-buttons {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .coordinates {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .mouse-buttons {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include '../templates/header.php'; ?>
    
    <div class="tool-container">
        <a href="../index.php" class="back-btn">返回首页</a>
        <h1>🖱️ 鼠标测试工具</h1>
        
        <div class="instructions">
            <h3>📖 使用说明</h3>
            <ul>
                <li>在测试区域<strong>移动鼠标</strong>查看实时坐标和轨迹</li>
                <li><strong>点击鼠标按键</strong>（左键、右键、中键、侧键）测试响应状态</li>
                <li><strong>滚动鼠标滚轮</strong>测试滚轮功能和方向检测</li>
                <li><strong>双击测试</strong>检测双击间隔时间</li>
                <li>统计面板显示<strong>点击次数</strong>、<strong>移动距离</strong>、<strong>滚轮操作</strong>等数据</li>
                <li>轨迹记录功能显示<strong>鼠标移动路径</strong>，支持清除和重置</li>
            </ul>
        </div>
        
        <div class="mouse-buttons">
            <div class="mouse-button left" data-button="0">
                <div class="button-icon">🖱️</div>
                <div class="button-name">左键</div>
                <div class="button-status">待测试</div>
            </div>
            <div class="mouse-button right" data-button="2">
                <div class="button-icon">🖱️</div>
                <div class="button-name">右键</div>
                <div class="button-status">待测试</div>
            </div>
            <div class="mouse-button middle" data-button="1">
                <div class="button-icon">🖱️</div>
                <div class="button-name">中键</div>
                <div class="button-status">待测试</div>
            </div>
            <div class="mouse-button extra" data-button="3">
                <div class="button-icon">🖱️</div>
                <div class="button-name">侧键1</div>
                <div class="button-status">待测试</div>
            </div>
            <div class="mouse-button extra" data-button="4">
                <div class="button-icon">🖱️</div>
                <div class="button-name">侧键2</div>
                <div class="button-status">待测试</div>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value" id="leftClicks">0</div>
                <div class="stat-label">左键点击</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="rightClicks">0</div>
                <div class="stat-label">右键点击</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="middleClicks">0</div>
                <div class="stat-label">中键点击</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="doubleClicks">0</div>
                <div class="stat-label">双击次数</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="wheelScrolls">0</div>
                <div class="stat-label">滚轮滚动</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="totalDistance">0</div>
                <div class="stat-label">移动距离(px)</div>
            </div>
        </div>
        
        <div class="wheel-indicator">
            <div class="wheel-label">滚轮方向</div>
            <div class="wheel-value" id="wheelDirection">-</div>
            <div class="wheel-delta" id="wheelDelta">Delta: 0</div>
        </div>
        
        <div class="controls">
            <button class="btn" onclick="resetStats()">🔄 重置数据</button>
            <button class="btn secondary" onclick="clearTrajectory()">🗑️ 清除轨迹</button>
            <button class="btn" onclick="toggleSound()">🔊 音效: <span id="soundStatus">开启</span></button>
        </div>
        
        <div class="mouse-test-area" id="testArea">
            <div class="mouse-visualization" id="mouseVisual"></div>
            <canvas class="trajectory-canvas" id="trajectoryCanvas"></canvas>
        </div>
        
        <div class="coordinates">
            <div class="coordinate-item">
                <div>当前位置</div>
                <div class="coordinate-value">X: <span id="currentX">0</span>, Y: <span id="currentY">0</span></div>
            </div>
            <div class="coordinate-item">
                <div>相对位置</div>
                <div class="coordinate-value">X: <span id="relativeX">0</span>, Y: <span id="relativeY">0</span></div>
            </div>
            <div class="coordinate-item">
                <div>移动速度</div>
                <div class="coordinate-value"><span id="moveSpeed">0</span> px/s</div>
            </div>
        </div>
    </div>
    
    <?php include '../templates/footer.php'; ?>
    
    <script>
        const mouseTest = {
            stats: {
                leftClicks: 0,
                rightClicks: 0,
                middleClicks: 0,
                doubleClicks: 0,
                wheelScrolls: 0,
                totalDistance: 0,
                lastPosition: { x: 0, y: 0 },
                lastMoveTime: Date.now()
            },
            soundEnabled: true,
            trajectoryPoints: [],
            audioContext: null,
            isMouseDown: false,
            lastClickTime: 0,
            lastClickButton: -1,
            
            init() {
                this.setupEventListeners();
                this.initAudioContext();
                this.setupCanvas();
                this.updateDisplay();
            },
            
            setupEventListeners() {
                const testArea = document.getElementById('testArea');
                
                testArea.addEventListener('mousedown', this.handleMouseDown.bind(this));
                testArea.addEventListener('mouseup', this.handleMouseUp.bind(this));
                testArea.addEventListener('mousemove', this.handleMouseMove.bind(this));
                testArea.addEventListener('wheel', this.handleWheel.bind(this));
                testArea.addEventListener('dblclick', this.handleDoubleClick.bind(this));
                testArea.addEventListener('contextmenu', this.handleContextMenu.bind(this));
                
                // 失去焦点时重置状态
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        this.resetAllButtonStates();
                    }
                });
            },
            
            initAudioContext() {
                try {
                    this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                } catch (error) {
                    console.warn('音频上下文初始化失败:', error);
                }
            },
            
            setupCanvas() {
                const canvas = document.getElementById('trajectoryCanvas');
                const testArea = document.getElementById('testArea');
                
                canvas.width = testArea.offsetWidth;
                canvas.height = testArea.offsetHeight;
                
                // 响应窗口大小变化
                window.addEventListener('resize', () => {
                    canvas.width = testArea.offsetWidth;
                    canvas.height = testArea.offsetHeight;
                    this.drawTrajectory();
                });
            },
            
            handleMouseDown(event) {
                event.preventDefault();
                const button = event.button;
                const buttonElement = document.querySelector(`[data-button="${button}"]`);
                
                if (buttonElement) {
                    buttonElement.classList.add('active');
                    this.isMouseDown = true;
                    this.updateButtonClick(button, true);
                    this.playClickSound();
                    this.createClickEffect(event.offsetX, event.offsetY, button);
                    
                    // 检测双击
                    const currentTime = Date.now();
                    if (button === this.lastClickButton && currentTime - this.lastClickTime < 500) {
                        this.stats.doubleClicks++;
                        document.getElementById('doubleClicks').textContent = this.stats.doubleClicks;
                    }
                    this.lastClickTime = currentTime;
                    this.lastClickButton = button;
                }
            },
            
            handleMouseUp(event) {
                event.preventDefault();
                const button = event.button;
                const buttonElement = document.querySelector(`[data-button="${button}"]`);
                
                if (buttonElement) {
                    buttonElement.classList.remove('active');
                    this.isMouseDown = false;
                    this.updateButtonClick(button, false);
                }
            },
            
            handleMouseMove(event) {
                const rect = event.currentTarget.getBoundingClientRect();
                const x = event.clientX - rect.left;
                const y = event.clientY - rect.top;
                
                this.updateMousePosition(x, y);
                this.updateTrajectory(x, y);
                this.calculateSpeed(x, y);
            },
            
            handleWheel(event) {
                event.preventDefault();
                const delta = event.deltaY;
                const direction = delta > 0 ? '向下' : '向上';
                
                this.stats.wheelScrolls++;
                document.getElementById('wheelDirection').textContent = direction;
                document.getElementById('wheelDelta').textContent = `Delta: ${Math.abs(delta.toFixed(0))}`;
                document.getElementById('wheelScrolls').textContent = this.stats.wheelScrolls;
                
                this.playScrollSound();
            },
            
            handleDoubleClick(event) {
                event.preventDefault();
                // 双击事件已由mousedown处理
            },
            
            handleContextMenu(event) {
                event.preventDefault();
                // 阻止右键菜单
            },
            
            updateButtonClick(button, isPressed) {
                const statusElement = document.querySelector(`[data-button="${button}"] .button-status`);
                if (statusElement) {
                    statusElement.textContent = isPressed ? '按下' : '抬起';
                }
                
                // 更新统计
                if (isPressed) {
                    switch(button) {
                        case 0: // 左键
                            this.stats.leftClicks++;
                            document.getElementById('leftClicks').textContent = this.stats.leftClicks;
                            break;
                        case 1: // 中键
                            this.stats.middleClicks++;
                            document.getElementById('middleClicks').textContent = this.stats.middleClicks;
                            break;
                        case 2: // 右键
                            this.stats.rightClicks++;
                            document.getElementById('rightClicks').textContent = this.stats.rightClicks;
                            break;
                    }
                }
            },
            
            updateMousePosition(x, y) {
                // 更新鼠标视觉指示器
                const mouseVisual = document.getElementById('mouseVisual');
                mouseVisual.style.left = x + 'px';
                mouseVisual.style.top = y + 'px';
                
                // 更新坐标显示
                document.getElementById('currentX').textContent = Math.round(x);
                document.getElementById('currentY').textContent = Math.round(y);
                
                // 更新相对坐标（相对于测试区域）
                const testArea = document.getElementById('testArea');
                document.getElementById('relativeX').textContent = Math.round(x - testArea.offsetWidth / 2);
                document.getElementById('relativeY').textContent = Math.round(y - testArea.offsetHeight / 2);
            },
            
            updateTrajectory(x, y) {
                // 计算移动距离
                if (this.stats.lastPosition.x !== 0 || this.stats.lastPosition.y !== 0) {
                    const distance = Math.sqrt(
                        Math.pow(x - this.stats.lastPosition.x, 2) + 
                        Math.pow(y - this.stats.lastPosition.y, 2)
                    );
                    this.stats.totalDistance += distance;
                    document.getElementById('totalDistance').textContent = Math.round(this.stats.totalDistance);
                }
                
                this.stats.lastPosition = { x, y };
                
                // 记录轨迹点
                this.trajectoryPoints.push({ x, y, time: Date.now() });
                
                // 限制轨迹点数量
                if (this.trajectoryPoints.length > 100) {
                    this.trajectoryPoints.shift();
                }
                
                this.drawTrajectory();
            },
            
            calculateSpeed(x, y) {
                const currentTime = Date.now();
                const timeDiff = currentTime - this.stats.lastMoveTime;
                
                if (timeDiff > 0) {
                    const distance = Math.sqrt(
                        Math.pow(x - this.stats.lastPosition.x, 2) + 
                        Math.pow(y - this.stats.lastPosition.y, 2)
                    );
                    const speed = (distance / timeDiff) * 1000; // px/s
                    document.getElementById('moveSpeed').textContent = Math.round(speed);
                }
                
                this.stats.lastMoveTime = currentTime;
            },
            
            drawTrajectory() {
                const canvas = document.getElementById('trajectoryCanvas');
                const ctx = canvas.getContext('2d');
                
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                if (this.trajectoryPoints.length < 2) return;
                
                ctx.strokeStyle = 'rgba(52, 152, 219, 0.5)';
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                
                ctx.beginPath();
                ctx.moveTo(this.trajectoryPoints[0].x, this.trajectoryPoints[0].y);
                
                for (let i = 1; i < this.trajectoryPoints.length; i++) {
                    const point = this.trajectoryPoints[i];
                    ctx.lineTo(point.x, point.y);
                }
                
                ctx.stroke();
                
                // 绘制轨迹点
                ctx.fillStyle = 'rgba(52, 152, 219, 0.8)';
                for (let i = 0; i < this.trajectoryPoints.length; i += 5) {
                    const point = this.trajectoryPoints[i];
                    ctx.beginPath();
                    ctx.arc(point.x, point.y, 2, 0, Math.PI * 2);
                    ctx.fill();
                }
            },
            
            createClickEffect(x, y, button) {
                const colors = {
                    0: '#28a745', // 左键 - 绿色
                    1: '#ffc107', // 中键 - 黄色
                    2: '#dc3545', // 右键 - 红色
                    3: '#6f42c1', // 侧键1 - 紫色
                    4: '#6f42c1'  // 侧键2 - 紫色
                };
                
                const color = colors[button] || '#007bff';
                
                const clickPoint = document.createElement('div');
                clickPoint.className = 'click-point';
                clickPoint.style.left = x + 'px';
                clickPoint.style.top = y + 'px';
                clickPoint.style.border = `3px solid ${color}`;
                
                document.getElementById('testArea').appendChild(clickPoint);
                
                setTimeout(() => clickPoint.remove(), 600);
            },
            
            playClickSound() {
                if (!this.soundEnabled || !this.audioContext) return;
                
                try {
                    const oscillator = this.audioContext.createOscillator();
                    const gainNode = this.audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(this.audioContext.destination);
                    
                    oscillator.frequency.value = 1000;
                    oscillator.type = 'sine';
                    
                    gainNode.gain.setValueAtTime(0.2, this.audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + 0.1);
                    
                    oscillator.start(this.audioContext.currentTime);
                    oscillator.stop(this.audioContext.currentTime + 0.1);
                } catch (error) {
                    // 忽略音频播放错误
                }
            },
            
            playScrollSound() {
                if (!this.soundEnabled || !this.audioContext) return;
                
                try {
                    const oscillator = this.audioContext.createOscillator();
                    const gainNode = this.audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(this.audioContext.destination);
                    
                    oscillator.frequency.value = 600;
                    oscillator.type = 'sine';
                    
                    gainNode.gain.setValueAtTime(0.1, this.audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + 0.05);
                    
                    oscillator.start(this.audioContext.currentTime);
                    oscillator.stop(this.audioContext.currentTime + 0.05);
                } catch (error) {
                    // 忽略音频播放错误
                }
            },
            
            resetAllButtonStates() {
                document.querySelectorAll('.mouse-button').forEach(button => {
                    button.classList.remove('active');
                    const statusElement = button.querySelector('.button-status');
                    if (statusElement) {
                        statusElement.textContent = '待测试';
                    }
                });
                this.isMouseDown = false;
            },
            
            updateDisplay() {
                // 初始化显示
                document.getElementById('leftClicks').textContent = this.stats.leftClicks;
                document.getElementById('rightClicks').textContent = this.stats.rightClicks;
                document.getElementById('middleClicks').textContent = this.stats.middleClicks;
                document.getElementById('doubleClicks').textContent = this.stats.doubleClicks;
                document.getElementById('wheelScrolls').textContent = this.stats.wheelScrolls;
                document.getElementById('totalDistance').textContent = Math.round(this.stats.totalDistance);
                document.getElementById('currentX').textContent = '0';
                document.getElementById('currentY').textContent = '0';
                document.getElementById('relativeX').textContent = '0';
                document.getElementById('relativeY').textContent = '0';
                document.getElementById('moveSpeed').textContent = '0';
            }
        };
        
        // 全局函数
        function resetStats() {
            mouseTest.stats = {
                leftClicks: 0,
                rightClicks: 0,
                middleClicks: 0,
                doubleClicks: 0,
                wheelScrolls: 0,
                totalDistance: 0,
                lastPosition: { x: 0, y: 0 },
                lastMoveTime: Date.now()
            };
            mouseTest.trajectoryPoints = [];
            mouseTest.resetAllButtonStates();
            mouseTest.updateDisplay();
            
            // 清空轨迹
            const canvas = document.getElementById('trajectoryCanvas');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        
        function clearTrajectory() {
            mouseTest.trajectoryPoints = [];
            const canvas = document.getElementById('trajectoryCanvas');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        
        function toggleSound() {
            mouseTest.soundEnabled = !mouseTest.soundEnabled;
            document.getElementById('soundStatus').textContent = mouseTest.soundEnabled ? '开启' : '关闭';
            
            // 如果启用音效且音频上下文被暂停，尝试恢复
            if (mouseTest.soundEnabled && mouseTest.audioContext && mouseTest.audioContext.state === 'suspended') {
                mouseTest.audioContext.resume().catch(() => {
                    console.warn('音频上下文恢复失败');
                });
            }
        }
        
        // 初始化
        document.addEventListener('DOMContentLoaded', function() {
            mouseTest.init();
        });
    </script>
</body>
</html>