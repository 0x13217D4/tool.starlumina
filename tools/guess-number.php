<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>猜数字游戏 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        /* 猜数字游戏特定样式 */
        .guess-game {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .game-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        input[type="number"] {
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            width: 100px;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .message {
            padding: 1rem;
            border-radius: 6px;
            margin-top: 1rem;
            text-align: center;
        }
        
        .info {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
        }
        
        .success {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
        }
        
        .warning {
            background-color: #fff3e0;
            border-left: 4px solid #ff9800;
        }
        
        .stats {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 6px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .history {
            margin-top: 1rem;
            max-height: 200px;
            overflow-y: auto;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 6px;
        }
        
        .history-item {
            padding: 0.5rem;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .history-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>猜数字游戏</h1>
            <div class="info-card">
                <div class="guess-game">
                    <p>我已经想好了一个1-100之间的数字，猜猜是多少？</p>
                    
                    <div class="game-controls">
                        <input type="number" id="guess-input" min="1" max="100" placeholder="1-100">
                        <button id="guess-btn">猜</button>
                        <button id="new-game-btn">新游戏</button>
                    </div>
                    
                    <div id="message" class="message info" style="display:none;"></div>
                    
                    <div class="stats">
                        <div class="stat-item">
                            <div class="stat-label">当前尝试</div>
                            <div class="stat-value" id="attempts">0</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">最快记录</div>
                            <div class="stat-value" id="best-score">-</div>
                        </div>
                    </div>
                    
                    <div class="history" id="guess-history"></div>
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
            
        // 游戏逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const guessInput = document.getElementById('guess-input');
            const guessBtn = document.getElementById('guess-btn');
            const newGameBtn = document.getElementById('new-game-btn');
            const messageEl = document.getElementById('message');
            const attemptsEl = document.getElementById('attempts');
            const bestScoreEl = document.getElementById('best-score');
            const guessHistoryEl = document.getElementById('guess-history');
            
            let targetNumber = 0;
            let attempts = 0;
            let bestScore = localStorage.getItem('guessNumberBestScore') || null;
            let guessedNumbers = [];
            
            // 初始化游戏
            initGame();
            
            // 设置最佳成绩显示
            if (bestScore) {
                bestScoreEl.textContent = bestScore;
            }
            
            // 猜数字按钮事件
            guessBtn.addEventListener('click', makeGuess);
            
            // 输入框回车事件
            guessInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    makeGuess();
                }
            });
            
            // 新游戏按钮事件
            newGameBtn.addEventListener('click', initGame);
            
            function initGame() {
                // 生成1-100的随机数
                targetNumber = Math.floor(Math.random() * 100) + 1;
                attempts = 0;
                guessedNumbers = [];
                
                // 更新UI
                attemptsEl.textContent = attempts;
                guessHistoryEl.innerHTML = '';
                messageEl.style.display = 'none';
                guessInput.value = '';
                guessInput.focus();
                
                console.log('目标数字:', targetNumber); // 调试用
            }
            
            function makeGuess() {
                const guess = parseInt(guessInput.value);
                
                // 验证输入
                if (isNaN(guess) || guess < 1 || guess > 100) {
                    showMessage('请输入1-100之间的有效数字', 'warning');
                    return;
                }
                
                // 检查是否已经猜过这个数字
                if (guessedNumbers.includes(guess)) {
                    showMessage(`你已经猜过${guess}了，请猜其他数字`, 'warning');
                    return;
                }
                
                // 记录猜测
                guessedNumbers.push(guess);
                attempts++;
                attemptsEl.textContent = attempts;
                
                // 添加到历史记录
                addToHistory(guess);
                
                // 比较猜测与目标数字
                if (guess === targetNumber) {
                    // 猜对了
                    showMessage(`恭喜！你猜对了！数字就是${targetNumber}。用了${attempts}次猜中。`, 'success');
                    guessBtn.disabled = true;
                    
                    // 更新最佳成绩
                    updateBestScore(attempts);
                } else if (guess < targetNumber) {
                    showMessage(`${guess} 太小了，再大一点！`, 'info');
                } else {
                    showMessage(`${guess} 太大了，再小一点！`, 'info');
                }
                
                // 清空输入框
                guessInput.value = '';
            }
            
            function showMessage(text, type) {
                messageEl.textContent = text;
                messageEl.className = `message ${type}`;
                messageEl.style.display = 'block';
            }
            
            function addToHistory(guess) {
                const historyItem = document.createElement('div');
                historyItem.className = 'history-item';
                
                let result = '';
                if (guess === targetNumber) {
                    result = '= 正确！';
                } else if (guess < targetNumber) {
                    result = '< 太小';
                } else {
                    result = '> 太大';
                }
                
                historyItem.textContent = `第${attempts}次: ${guess} ${result}`;
                guessHistoryEl.appendChild(historyItem);
                
                // 滚动到底部
                guessHistoryEl.scrollTop = guessHistoryEl.scrollHeight;
            }
            
            function updateBestScore(currentAttempts) {
                if (!bestScore || currentAttempts < bestScore) {
                    bestScore = currentAttempts;
                    bestScoreEl.textContent = bestScore;
                    
                    // 保存到本地存储
                    localStorage.setItem('guessNumberBestScore', bestScore);
                }
            }
        });
    </script>
</body>
</html>

