<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>键盘按键测试 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .keyboard-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .keyboard {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            max-width: 1200px;
            margin: 0 auto;
            user-select: none;
        }
        
        .keyboard-row {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }
        
        .key {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 500;
            color: #495057;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .key:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .key.active {
            background: #007bff;
            color: white;
            border-color: #0056b3;
            transform: scale(0.95);
            box-shadow: 0 0 0 3px rgba(0,123,255,0.25);
        }
        
        .key.space {
            min-width: 250px;
        }
        
        .key.tab, .key.caps, .key.enter, .key.shift {
            min-width: 80px;
        }
        
        .key.backspace {
            min-width: 100px;
        }
        
        .key.ctrl, .key.alt, .key.win, .key.fn {
            min-width: 60px;
        }
        
        .stats-container {
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
        
        .key-history {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
        }
        
        .history-item {
            padding: 0.5rem;
            border-bottom: 1px solid #f1f3f4;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .history-item:last-child {
            border-bottom: none;
        }
        
        .key-code {
            background: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
        }
        
        .key-time {
            color: #6c757d;
            font-size: 12px;
        }
        
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .keyboard {
                transform: scale(0.8);
                transform-origin: center;
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .keyboard {
                transform: scale(0.6);
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
        
    </style>
</head>
<body>
    <?php include '../templates/header.php'; ?>
    
    <div class="tool-container">
        <a href="../index.php" class="back-btn">返回首页</a>
        <h1>⌨️ 键盘按键测试工具</h1>
        
        <div class="instructions">
            <h3>📖 使用说明</h3>
            <ul>
                <li>在键盘上<strong>按下任意按键</strong>，页面上的虚拟键盘将会<strong>高亮显示</strong>被按下的按键</li>
                <li>如果按键能正常高亮，说明该按键<strong>工作正常</strong></li>
                <li>如果没有任何反应，可能表示该按键<strong>存在故障</strong>或<strong>无响应</strong></li>
                <li>本工具支持<strong>同时检测多个按键</strong>，适合用于测试键盘的<strong>连按支持</strong>和<strong>键位冲突问题</strong></li>
            </ul>
        </div>
        
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-value" id="pressCount">0</div>
                <div class="stat-label">按下次数</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="releaseCount">0</div>
                <div class="stat-label">抬起次数</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="totalPresses">0</div>
                <div class="stat-label">总触发次数</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="uniqueKeys">0</div>
                <div class="stat-label">已测试按键数</div>
            </div>
        </div>
        
        <div class="controls">
            <button class="btn" onclick="resetStats()">🔄 重置数据</button>
            <button class="btn secondary" onclick="clearHistory()">🗑️ 清除记录</button>
            <button class="btn" onclick="toggleSound()">🔊 按键音效: <span id="soundStatus">开启</span></button>
        </div>
        
        <div class="keyboard-container">
            <div class="keyboard" id="keyboard">
                <!-- 键盘布局将通过JavaScript生成 -->
            </div>
        </div>
        
        <div class="key-history" id="keyHistory">
            <div style="text-align: center; color: #6c757d; padding: 2rem;">
                按下任意键开始记录...
            </div>
        </div>
        
        <div class="warning">
            <strong>⚠️ 注意：</strong> 由于浏览器环境限制，某些系统按键（如 Win 键、Fn 键）可能无法被检测到。这是正常现象，不代表键盘故障。
        </div>
    </div>
    
    <?php include '../templates/footer.php'; ?>
    
    <script>
        // 键盘布局定义
        const keyboardLayout = [
            [
                { code: 'Escape', label: 'Esc' },
                { code: 'F1', label: 'F1' },
                { code: 'F2', label: 'F2' },
                { code: 'F3', label: 'F3' },
                { code: 'F4', label: 'F4' },
                { code: 'F5', label: 'F5' },
                { code: 'F6', label: 'F6' },
                { code: 'F7', label: 'F7' },
                { code: 'F8', label: 'F8' },
                { code: 'F9', label: 'F9' },
                { code: 'F10', label: 'F10' },
                { code: 'F11', label: 'F11' },
                { code: 'F12', label: 'F12' }
            ],
            [
                { code: 'Backquote', label: '`' },
                { code: 'Digit1', label: '1' },
                { code: 'Digit2', label: '2' },
                { code: 'Digit3', label: '3' },
                { code: 'Digit4', label: '4' },
                { code: 'Digit5', label: '5' },
                { code: 'Digit6', label: '6' },
                { code: 'Digit7', label: '7' },
                { code: 'Digit8', label: '8' },
                { code: 'Digit9', label: '9' },
                { code: 'Digit0', label: '0' },
                { code: 'Minus', label: '-' },
                { code: 'Equal', label: '=' },
                { code: 'Backspace', label: 'Backspace', class: 'backspace' }
            ],
            [
                { code: 'Tab', label: 'Tab', class: 'tab' },
                { code: 'KeyQ', label: 'Q' },
                { code: 'KeyW', label: 'W' },
                { code: 'KeyE', label: 'E' },
                { code: 'KeyR', label: 'R' },
                { code: 'KeyT', label: 'T' },
                { code: 'KeyY', label: 'Y' },
                { code: 'KeyU', label: 'U' },
                { code: 'KeyI', label: 'I' },
                { code: 'KeyO', label: 'O' },
                { code: 'KeyP', label: 'P' },
                { code: 'BracketLeft', label: '[' },
                { code: 'BracketRight', label: ']' },
                { code: 'Backslash', label: '\\' }
            ],
            [
                { code: 'CapsLock', label: 'Caps Lock', class: 'caps' },
                { code: 'KeyA', label: 'A' },
                { code: 'KeyS', label: 'S' },
                { code: 'KeyD', label: 'D' },
                { code: 'KeyF', label: 'F' },
                { code: 'KeyG', label: 'G' },
                { code: 'KeyH', label: 'H' },
                { code: 'KeyJ', label: 'J' },
                { code: 'KeyK', label: 'K' },
                { code: 'KeyL', label: 'L' },
                { code: 'Semicolon', label: ';' },
                { code: 'Quote', label: '\'' },
                { code: 'Enter', label: 'Enter', class: 'enter' }
            ],
            [
                { code: 'ShiftLeft', label: 'Shift', class: 'shift' },
                { code: 'KeyZ', label: 'Z' },
                { code: 'KeyX', label: 'X' },
                { code: 'KeyC', label: 'C' },
                { code: 'KeyV', label: 'V' },
                { code: 'KeyB', label: 'B' },
                { code: 'KeyN', label: 'N' },
                { code: 'KeyM', label: 'M' },
                { code: 'Comma', label: ',' },
                { code: 'Period', label: '.' },
                { code: 'Slash', label: '/' },
                { code: 'ShiftRight', label: 'Shift', class: 'shift' }
            ],
            [
                { code: 'ControlLeft', label: 'Ctrl', class: 'ctrl' },
                { code: 'MetaLeft', label: 'Win', class: 'win' },
                { code: 'AltLeft', label: 'Alt', class: 'alt' },
                { code: 'Space', label: 'Space', class: 'space' },
                { code: 'AltRight', label: 'Alt', class: 'alt' },
                { code: 'MetaRight', label: 'Win', class: 'win' },
                { code: 'ContextMenu', label: 'Menu' },
                { code: 'ControlRight', label: 'Ctrl', class: 'ctrl' }
            ]
        ];
        
        // 统计数据
        let stats = {
            pressCount: 0,
            releaseCount: 0,
            totalPresses: 0,
            uniqueKeys: new Set(),
            keyHistory: []
        };
        
        let soundEnabled = true;
        let activeKeys = new Set();
        let audioContext = null;
        
        // 初始化键盘
        function initKeyboard() {
            const keyboard = document.getElementById('keyboard');
            keyboard.innerHTML = '';
            
            keyboardLayout.forEach(row => {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'keyboard-row';
                
                row.forEach(key => {
                    const keyDiv = document.createElement('div');
                    keyDiv.className = `key ${key.class || ''}`;
                    keyDiv.textContent = key.label;
                    keyDiv.dataset.keyCode = key.code;
                    rowDiv.appendChild(keyDiv);
                });
                
                keyboard.appendChild(rowDiv);
            });
        }
        
        // 初始化音频上下文
        function initAudioContext() {
            if (!audioContext) {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
            }
        }
        
        // 播放按键音效
        function playKeySound() {
            if (!soundEnabled || !audioContext) return;
            
            try {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = 800;
                oscillator.type = 'sine';
                
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.1);
            } catch (error) {
                // 如果音频上下文被暂停，尝试恢复
                if (audioContext.state === 'suspended') {
                    audioContext.resume().then(() => {
                        playKeySound();
                    }).catch(() => {
                        // 忽略音频播放错误
                    });
                }
            }
        }
        
        // 更新统计显示
        function updateStats() {
            document.getElementById('pressCount').textContent = stats.pressCount;
            document.getElementById('releaseCount').textContent = stats.releaseCount;
            document.getElementById('totalPresses').textContent = stats.totalPresses;
            document.getElementById('uniqueKeys').textContent = stats.uniqueKeys.size;
        }
        
        // 添加到历史记录
        function addToHistory(keyCode, event) {
            const now = new Date();
            const timeStr = now.toLocaleTimeString();
            
            stats.keyHistory.unshift({
                keyCode: keyCode,
                event: event,
                time: timeStr
            });
            
            // 只保留最近20条记录
            if (stats.keyHistory.length > 20) {
                stats.keyHistory = stats.keyHistory.slice(0, 20);
            }
            
            updateHistoryDisplay();
        }
        
        // 更新历史记录显示
        function updateHistoryDisplay() {
            const historyDiv = document.getElementById('keyHistory');
            
            if (stats.keyHistory.length === 0) {
                historyDiv.innerHTML = '<div style="text-align: center; color: #6c757d; padding: 2rem;">按下任意键开始记录...</div>';
                return;
            }
            
            historyDiv.innerHTML = stats.keyHistory.map(item => `
                <div class="history-item">
                    <span>
                        <strong>${item.event === 'down' ? '⬇️ 按下' : '⬆️ 抬起'}</strong>
                        <span class="key-code">${item.keyCode}</span>
                    </span>
                    <span class="key-time">${item.time}</span>
                </div>
            `).join('');
        }
        
        // 处理按键按下
        function handleKeyDown(event) {
            // 阻止所有按键的默认行为，特别是功能键
            event.preventDefault();
            event.stopPropagation();
            
            const keyElement = document.querySelector(`[data-key-code="${event.code}"]`);
            
            if (keyElement && !activeKeys.has(event.code)) {
                keyElement.classList.add('active');
                activeKeys.add(event.code);
                
                stats.pressCount++;
                stats.totalPresses++;
                stats.uniqueKeys.add(event.code);
                
                playKeySound();
                updateStats();
                addToHistory(event.code, 'down');
            }
        }
        
        // 处理按键抬起
        function handleKeyUp(event) {
            // 阻止所有按键的默认行为
            event.preventDefault();
            event.stopPropagation();
            
            const keyElement = document.querySelector(`[data-key-code="${event.code}"]`);
            
            if (keyElement) {
                keyElement.classList.remove('active');
                activeKeys.delete(event.code);
                
                stats.releaseCount++;
                updateStats();
                addToHistory(event.code, 'up');
            }
        }
        
        // 重置统计数据
        function resetStats() {
            stats = {
                pressCount: 0,
                releaseCount: 0,
                totalPresses: 0,
                uniqueKeys: new Set(),
                keyHistory: []
            };
            
            activeKeys.clear();
            document.querySelectorAll('.key.active').forEach(key => {
                key.classList.remove('active');
            });
            
            updateStats();
            updateHistoryDisplay();
        }
        
        // 清除历史记录
        function clearHistory() {
            stats.keyHistory = [];
            updateHistoryDisplay();
        }
        
        // 切换音效
        function toggleSound() {
            soundEnabled = !soundEnabled;
            document.getElementById('soundStatus').textContent = soundEnabled ? '开启' : '关闭';
        }
        
        // 初始化
        document.addEventListener('DOMContentLoaded', function() {
            initKeyboard();
            initAudioContext();
            updateStats();
            
            // 统一的键盘事件监听器，避免重复处理
            document.addEventListener('keydown', function(event) {
                // 阻止所有按键的默认行为，特别是功能键
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
                
                handleKeyDown(event);
            }, true); // 使用捕获阶段确保优先处理
            
            document.addEventListener('keyup', function(event) {
                // 阻止所有按键的默认行为
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
                
                handleKeyUp(event);
            }, true); // 使用捕获阶段
            
            // 失去焦点时清除所有高亮状态
            window.addEventListener('blur', function() {
                document.querySelectorAll('.key.active').forEach(key => {
                    key.classList.remove('active');
                });
                activeKeys.clear();
            });
            
            // 页面可见性变化时，清除可能的状态残留
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    document.querySelectorAll('.key.active').forEach(key => {
                        key.classList.remove('active');
                    });
                    activeKeys.clear();
                }
            });
            
            // 处理页面可见性变化，恢复音频上下文
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden && audioContext && audioContext.state === 'suspended') {
                    audioContext.resume().catch(() => {
                        // 忽略恢复失败
                    });
                }
            });
        });
    </script>
</body>
</html>