<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>五子棋游戏 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        /* 五子棋游戏特定样式 */
        .game-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .game-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .game-title {
            font-size: 2.5rem;
            color: #8B5A2B;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        
        .game-area {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .board-section {
            flex: 1;
            min-width: 300px;
            max-width: 600px;
        }
        
        .controls-section {
            width: 300px;
            flex-shrink: 0;
        }
        
        .game-board-container {
            background: #DEB887;
            border: 4px solid #8B5A2B;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 8px 25px rgba(139, 90, 43, 0.3);
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .game-info {
            background: #f8f9fa;
            border: 2px solid #DEB887;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .game-canvas {
            display: block;
            width: 100%;
            cursor: pointer;
            border-radius: 4px;
        }
        
        .control-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .btn {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid #8B5A2B;
            background: white;
            color: #8B5A2B;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
            min-width: 100px;
        }
        
        .btn:hover {
            background: #DEB887;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 90, 43, 0.2);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn.primary {
            background: #8B5A2B;
            color: white;
        }
        
        .btn.primary:hover {
            background: #6B4226;
        }
        
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .game-status {
            background: #f8f9fa;
            border: 2px solid #DEB887;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .status-title {
            font-weight: bold;
            color: #8B5A2B;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .status-content {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .current-player {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #ccc;
        }
        
        .current-player.black {
            background: #000;
        }
        
        .current-player.white {
            background: #fff;
        }
        
        .game-rules {
            background: #f8f9fa;
            border: 2px solid #DEB887;
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .rules-title {
            font-weight: bold;
            color: #8B5A2B;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .rules-list {
            list-style: none;
            padding: 0;
        }
        
        .rules-list li {
            margin-bottom: 0.75rem;
            padding-left: 1.5rem;
            position: relative;
            color: #555;
        }
        
        .rules-list li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #8B5A2B;
            font-weight: bold;
        }
        
        .mode-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            border: 4px solid #8B5A2B;
            border-radius: 12px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .modal-title {
            font-size: 1.8rem;
            color: #8B5A2B;
            margin-bottom: 1.5rem;
            font-weight: bold;
        }
        
        .modal-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .modal-btn {
            flex: 1;
            padding: 1rem;
            border: 2px solid #8B5A2B;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        
        .modal-btn.pvp {
            background: #8B5A2B;
            color: white;
        }
        
        .modal-btn.pve {
            background: #DEB887;
            color: #8B5A2B;
        }
        
        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 90, 43, 0.3);
        }
        
        .win-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .win-content {
            background: white;
            border: 4px solid #8B5A2B;
            border-radius: 12px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .win-title {
            font-size: 2rem;
            color: #8B5A2B;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        
        .win-message {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 2rem;
        }
        
        .win-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .game-stats {
            background: #f8f9fa;
            border: 2px solid #DEB887;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stats-title {
            font-weight: bold;
            color: #8B5A2B;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }
        
        .stat-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            color: #555;
        }
        
        .stat-value {
            font-weight: 600;
            color: #8B5A2B;
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .game-area {
                flex-direction: column;
                align-items: center;
            }
            
            .controls-section {
                width: 100%;
                max-width: 400px;
            }
            
            .game-title {
                font-size: 2rem;
            }
            
            .control-buttons {
                flex-direction: column;
            }
            
            .modal-buttons,
            .win-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            
            <div class="game-container">
                <div class="game-area">
                    <div class="board-section">
                        <div class="game-board-container">
                            <canvas id="gameCanvas" class="game-canvas"></canvas>
                        </div>
                        
                        <div class="game-info">
                            <div class="status-title">游戏信息</div>
                            <div class="status-content">
                                <span>当前模式:</span>
                                <strong id="currentMode">选择模式</strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="controls-section">
                        <div class="game-status">
                            <div class="status-title">游戏状态</div>
                            <div class="status-content">
                                <div class="current-player" id="currentPlayer"></div>
                                <span id="statusText">等待开始游戏</span>
                            </div>
                        </div>
                        
                        <div class="game-stats">
                            <div class="stats-title">游戏统计</div>
                            <div class="stat-item">
                                <span>游戏时间:</span>
                                <span class="stat-value" id="gameTime">00:00</span>
                            </div>
                            <div class="stat-item">
                                <span>步数:</span>
                                <span class="stat-value" id="moveCount">0</span>
                            </div>
                        </div>
                        
                        <div class="control-buttons">
                            <button class="btn primary" id="restartBtn">
                                <i class="fa fa-refresh"></i> 重新开始
                            </button>
                            <button class="btn" id="undoBtn">
                                <i class="fa fa-undo"></i> 悔棋
                            </button>
                        </div>
                        
                        <div class="game-rules">
                            <div class="rules-title">
                                <i class="fa fa-info-circle"></i> 游戏规则
                            </div>
                            <ul class="rules-list">
                                <li>黑棋和白棋轮流在棋盘上落子</li>
                                <li>先在横、竖或斜方向形成五子连线者获胜</li>
                                <li>点击棋盘上的交叉点放置棋子</li>
                                <li>支持悔棋功能，可以撤销上一步操作</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- 游戏模式选择模态框 -->
    <div class="mode-modal" id="modeModal">
        <div class="modal-content">
            <h2 class="modal-title">选择游戏模式</h2>
            <div class="modal-buttons">
                <button class="modal-btn pvp" id="pvpBtn">
                    <i class="fa fa-users"></i> 玩家对战
                </button>
                <button class="modal-btn pve" id="pveBtn">
                    <i class="fa fa-robot"></i> 人机对战
                </button>
            </div>
            <p style="margin-top: 1.5rem; color: #666; font-size: 0.9rem;">
                <i class="fa fa-info-circle"></i> 黑棋先行，先形成五子连线者获胜
            </p>
        </div>
    </div>
    
    <!-- 胜利模态框 -->
    <div class="win-modal" id="winModal">
        <div class="win-content">
            <h2 class="win-title" id="winTitle">游戏结束!</h2>
            <p class="win-message" id="winMessage">黑棋获胜!</p>
            <div class="win-buttons">
                <button class="btn primary" id="newGameBtn">
                    <i class="fa fa-play"></i> 开始新游戏
                </button>
                <button class="btn" id="changeModeBtn">
                    <i class="fa fa-sliders"></i> 更换模式
                </button>
            </div>
        </div>
    </div>
    
    <div id="footer-container"></div>

    <script>
        // 游戏常量
        const BOARD_SIZE = 15;
        const CELL_SIZE = 30;
        
        // 游戏状态
        let gameState = {
            board: Array(BOARD_SIZE).fill().map(() => Array(BOARD_SIZE).fill(0)),
            currentPlayer: 1, // 1: 黑棋, 2: 白棋
            gameActive: false,
            gameMode: null, // 'pvp' 或 'pve'
            moveHistory: [],
            gameTime: 0,
            timerInterval: null,
            aiPlayer: 2 // AI执白棋
        };
        
        // DOM元素
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const modeModal = document.getElementById('modeModal');
        const winModal = document.getElementById('winModal');
        const winTitle = document.getElementById('winTitle');
        const winMessage = document.getElementById('winMessage');
        const currentMode = document.getElementById('currentMode');
        const statusText = document.getElementById('statusText');
        const currentPlayerEl = document.getElementById('currentPlayer');
        const gameTimeEl = document.getElementById('gameTime');
        const moveCountEl = document.getElementById('moveCount');
        const restartBtn = document.getElementById('restartBtn');
        const undoBtn = document.getElementById('undoBtn');
        const newGameBtn = document.getElementById('newGameBtn');
        const changeModeBtn = document.getElementById('changeModeBtn');
        const pvpBtn = document.getElementById('pvpBtn');
        const pveBtn = document.getElementById('pveBtn');
        
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
        
        // 初始化游戏
        function initGame() {
            // 设置画布尺寸
            canvas.width = CELL_SIZE * (BOARD_SIZE - 1);
            canvas.height = CELL_SIZE * (BOARD_SIZE - 1);
            
            // 重置游戏状态
            gameState.board = Array(BOARD_SIZE).fill().map(() => Array(BOARD_SIZE).fill(0));
            gameState.currentPlayer = 1;
            gameState.gameActive = false;
            gameState.moveHistory = [];
            gameState.gameTime = 0;
            
            // 清除计时器
            if (gameState.timerInterval) {
                clearInterval(gameState.timerInterval);
                gameState.timerInterval = null;
            }
            
            // 重置显示
            gameTimeEl.textContent = '00:00';
            moveCountEl.textContent = '0';
            statusText.textContent = '等待开始游戏';
            currentMode.textContent = '选择模式';
            
            // 绘制棋盘
            drawBoard();
            
            // 显示模式选择
            modeModal.style.display = 'flex';
            winModal.style.display = 'none';
        }
        
        // 绘制棋盘
        function drawBoard() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // 绘制网格线
            ctx.strokeStyle = '#8B5A2B';
            ctx.lineWidth = 1.5;
            
            for (let i = 0; i < BOARD_SIZE; i++) {
                // 水平线
                ctx.beginPath();
                ctx.moveTo(0, i * CELL_SIZE);
                ctx.lineTo(canvas.width, i * CELL_SIZE);
                ctx.stroke();
                
                // 垂直线
                ctx.beginPath();
                ctx.moveTo(i * CELL_SIZE, 0);
                ctx.lineTo(i * CELL_SIZE, canvas.height);
                ctx.stroke();
            }
            
            // 绘制星位
            const starPoints = [
                {x: 3, y: 3}, {x: 3, y: 11}, {x: 7, y: 7}, 
                {x: 11, y: 3}, {x: 11, y: 11}
            ];
            
            starPoints.forEach(point => {
                ctx.beginPath();
                ctx.arc(point.x * CELL_SIZE, point.y * CELL_SIZE, 4, 0, Math.PI * 2);
                ctx.fillStyle = '#8B5A2B';
                ctx.fill();
            });
            
            // 绘制棋子
            for (let i = 0; i < BOARD_SIZE; i++) {
                for (let j = 0; j < BOARD_SIZE; j++) {
                    if (gameState.board[i][j] !== 0) {
                        drawPiece(i, j, gameState.board[i][j]);
                    }
                }
            }
        }
        
        // 绘制棋子
        function drawPiece(row, col, player) {
            const x = col * CELL_SIZE;
            const y = row * CELL_SIZE;
            
            // 棋子阴影
            ctx.beginPath();
            ctx.arc(x, y, CELL_SIZE * 0.4 + 2, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
            ctx.fill();
            
            // 棋子本体
            ctx.beginPath();
            ctx.arc(x, y, CELL_SIZE * 0.4, 0, Math.PI * 2);
            
            if (player === 1) {
                // 黑棋
                const gradient = ctx.createRadialGradient(
                    x - CELL_SIZE * 0.1, y - CELL_SIZE * 0.1, CELL_SIZE * 0.05,
                    x, y, CELL_SIZE * 0.4
                );
                gradient.addColorStop(0, '#555');
                gradient.addColorStop(1, '#000');
                ctx.fillStyle = gradient;
            } else {
                // 白棋
                const gradient = ctx.createRadialGradient(
                    x - CELL_SIZE * 0.1, y - CELL_SIZE * 0.1, CELL_SIZE * 0.05,
                    x, y, CELL_SIZE * 0.4
                );
                gradient.addColorStop(0, '#fff');
                gradient.addColorStop(1, '#ddd');
                ctx.fillStyle = gradient;
            }
            
            ctx.fill();
            
            // 棋子边缘
            ctx.strokeStyle = player === 1 ? '#333' : '#ccc';
            ctx.lineWidth = 1;
            ctx.stroke();
        }
        
        // 开始游戏
        function startGame(mode) {
            gameState.gameMode = mode;
            gameState.gameActive = true;
            gameState.currentPlayer = 1;
            
            // 更新显示
            currentMode.textContent = mode === 'pvp' ? '玩家对战' : '人机对战';
            updateGameStatus();
            
            // 隐藏模式选择
            modeModal.style.display = 'none';
            
            // 开始计时
            startTimer();
        }
        
        // 更新游戏状态
        function updateGameStatus() {
            if (gameState.gameActive) {
                const playerName = gameState.currentPlayer === 1 ? '黑棋' : '白棋';
                statusText.textContent = `游戏进行中 - ${playerName}回合`;
                
                currentPlayerEl.className = `current-player ${gameState.currentPlayer === 1 ? 'black' : 'white'}`;
            }
            
            moveCountEl.textContent = gameState.moveHistory.length;
        }
        
        // 开始计时
        function startTimer() {
            clearInterval(gameState.timerInterval);
            gameState.timerInterval = setInterval(() => {
                gameState.gameTime++;
                const minutes = Math.floor(gameState.gameTime / 60).toString().padStart(2, '0');
                const seconds = (gameState.gameTime % 60).toString().padStart(2, '0');
                gameTimeEl.textContent = `${minutes}:${seconds}`;
            }, 1000);
        }
        
        // 停止计时
        function stopTimer() {
            clearInterval(gameState.timerInterval);
            gameState.timerInterval = null;
        }
        
        // 处理点击事件
        function handleClick(event) {
            if (!gameState.gameActive) return;
            
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            
            const x = (event.clientX - rect.left) * scaleX;
            const y = (event.clientY - rect.top) * scaleY;
            
            const col = Math.round(x / CELL_SIZE);
            const row = Math.round(y / CELL_SIZE);
            
            if (row >= 0 && row < BOARD_SIZE && col >= 0 && col < BOARD_SIZE && 
                gameState.board[row][col] === 0) {
                makeMove(row, col);
            }
        }
        
        // 落子
        function makeMove(row, col) {
            gameState.board[row][col] = gameState.currentPlayer;
            gameState.moveHistory.push({row, col, player: gameState.currentPlayer});
            
            drawBoard();
            updateGameStatus();
            
            // 检查胜利
            if (checkWin(row, col, gameState.currentPlayer)) {
                endGame(gameState.currentPlayer);
                return;
            }
            
            // 检查平局
            if (checkDraw()) {
                endGame(null);
                return;
            }
            
            // 切换玩家
            gameState.currentPlayer = gameState.currentPlayer === 1 ? 2 : 1;
            updateGameStatus();
            
            // AI回合
            if (gameState.gameMode === 'pve' && gameState.currentPlayer === gameState.aiPlayer) {
                setTimeout(aiMove, 500);
            }
        }
        
        // 检查胜利
        function checkWin(row, col, player) {
            const directions = [
                [1, 0],   // 水平
                [0, 1],   // 垂直
                [1, 1],   // 对角线
                [1, -1]   // 反对角线
            ];
            
            for (const [dx, dy] of directions) {
                let count = 1;
                
                // 正向检查
                for (let i = 1; i < 5; i++) {
                    const newRow = row + i * dy;
                    const newCol = col + i * dx;
                    
                    if (newRow < 0 || newRow >= BOARD_SIZE || newCol < 0 || newCol >= BOARD_SIZE) {
                        break;
                    }
                    
                    if (gameState.board[newRow][newCol] === player) {
                        count++;
                    } else {
                        break;
                    }
                }
                
                // 反向检查
                for (let i = 1; i < 5; i++) {
                    const newRow = row - i * dy;
                    const newCol = col - i * dx;
                    
                    if (newRow < 0 || newRow >= BOARD_SIZE || newCol < 0 || newCol >= BOARD_SIZE) {
                        break;
                    }
                    
                    if (gameState.board[newRow][newCol] === player) {
                        count++;
                    } else {
                        break;
                    }
                }
                
                if (count >= 5) {
                    return true;
                }
            }
            
            return false;
        }
        
        // 检查平局
        function checkDraw() {
            for (let i = 0; i < BOARD_SIZE; i++) {
                for (let j = 0; j < BOARD_SIZE; j++) {
                    if (gameState.board[i][j] === 0) {
                        return false;
                    }
                }
            }
            return true;
        }
        
        // 游戏结束
        function endGame(winner) {
            gameState.gameActive = false;
            stopTimer();
            
            if (winner) {
                const winnerName = winner === 1 ? '黑棋' : '白棋';
                winTitle.textContent = '游戏结束!';
                winMessage.textContent = `${winnerName}获胜!`;
            } else {
                winTitle.textContent = '游戏结束!';
                winMessage.textContent = '平局!';
            }
            
            winModal.style.display = 'flex';
        }
        
        // 悔棋
        function undoMove() {
            if (gameState.moveHistory.length === 0 || !gameState.gameActive) return;
            
            const lastMove = gameState.moveHistory.pop();
            gameState.board[lastMove.row][lastMove.col] = 0;
            
            // 如果是人机对战且是AI的回合，需要悔两步
            if (gameState.gameMode === 'pve' && lastMove.player === gameState.aiPlayer && gameState.moveHistory.length > 0) {
                const playerMove = gameState.moveHistory.pop();
                gameState.board[playerMove.row][playerMove.col] = 0;
                gameState.currentPlayer = playerMove.player;
            } else {
                gameState.currentPlayer = lastMove.player;
            }
            
            drawBoard();
            updateGameStatus();
        }
        
        // AI智能策略
        function aiMove() {
            if (!gameState.gameActive) return;
            
            let bestMove = null;
            let bestScore = -Infinity;
            
            // 遍历所有空位，计算每个位置的分数
            for (let i = 0; i < BOARD_SIZE; i++) {
                for (let j = 0; j < BOARD_SIZE; j++) {
                    if (gameState.board[i][j] === 0) {
                        const score = evaluatePosition(i, j, gameState.aiPlayer);
                        
                        // 如果找到必胜或必防的位置，直接返回
                        if (score >= 10000) {
                            makeMove(i, j);
                            return;
                        }
                        
                        if (score > bestScore) {
                            bestScore = score;
                            bestMove = {row: i, col: j};
                        }
                    }
                }
            }
            
            // 如果没有找到好位置，使用改进的随机策略
            if (bestMove) {
                makeMove(bestMove.row, bestMove.col);
            }
        }
        
        // 评估位置分数
        function evaluatePosition(row, col, player) {
            let score = 0;
            const opponent = player === 1 ? 2 : 1;
            
            // 临时放置棋子
            gameState.board[row][col] = player;
            
            // 检查是否能直接获胜
            if (checkWin(row, col, player)) {
                gameState.board[row][col] = 0;
                return 10000; // 最高优先级：获胜
            }
            
            // 检查是否能阻止对手获胜
            gameState.board[row][col] = opponent;
            if (checkWin(row, col, opponent)) {
                gameState.board[row][col] = 0;
                return 9000; // 高优先级：阻止对手获胜
            }
            
            gameState.board[row][col] = player;
            
            // 评估各个方向的连珠潜力
            const directions = [
                [1, 0],   // 水平
                [0, 1],   // 垂直
                [1, 1],   // 对角线
                [1, -1]   // 反对角线
            ];
            
            for (const [dx, dy] of directions) {
                // 评估自己的连珠
                const myScore = evaluateDirection(row, col, dx, dy, player);
                score += myScore * 10;
                
                // 评估对手的威胁
                gameState.board[row][col] = opponent;
                const opponentScore = evaluateDirection(row, col, dx, dy, opponent);
                score += opponentScore * 8; // 稍微降低对手威胁权重
                gameState.board[row][col] = player;
            }
            
            // 位置价值评估
            const centerDistance = Math.abs(row - 7) + Math.abs(col - 7);
            score += (14 - centerDistance) * 2; // 越靠近中心越好
            
            // 恢复空位
            gameState.board[row][col] = 0;
            
            return score;
        }
        
        // 评估某个方向的连珠情况
        function evaluateDirection(row, col, dx, dy, player) {
            let score = 0;
            let count = 1; // 包括当前位置
            let openEnds = 0; // 开口数量
            
            // 正向检查
            let blocked = false;
            for (let i = 1; i < 5; i++) {
                const newRow = row + i * dy;
                const newCol = col + i * dx;
                
                if (newRow < 0 || newRow >= BOARD_SIZE || newCol < 0 || newCol >= BOARD_SIZE) {
                    blocked = true;
                    break;
                }
                
                if (gameState.board[newRow][newCol] === player) {
                    count++;
                } else if (gameState.board[newRow][newCol] === 0) {
                    openEnds++;
                    break;
                } else {
                    blocked = true;
                    break;
                }
            }
            
            // 反向检查
            if (!blocked) {
                for (let i = 1; i < 5; i++) {
                    const newRow = row - i * dy;
                    const newCol = col - i * dx;
                    
                    if (newRow < 0 || newRow >= BOARD_SIZE || newCol < 0 || newCol >= BOARD_SIZE) {
                        break;
                    }
                    
                    if (gameState.board[newRow][newCol] === player) {
                        count++;
                    } else if (gameState.board[newRow][newCol] === 0) {
                        openEnds++;
                        break;
                    } else {
                        break;
                    }
                }
            }
            
            // 根据连珠数量和开口情况计算分数
            if (count >= 5) {
                score = 1000; // 五连
            } else if (count === 4) {
                score = openEnds >= 2 ? 500 : 100; // 活四或冲四
            } else if (count === 3) {
                score = openEnds >= 2 ? 50 : 10; // 活三或眠三
            } else if (count === 2) {
                score = openEnds >= 2 ? 5 : 1; // 活二或眠二
            }
            
            return score;
        }
        
        // 事件监听器
        canvas.addEventListener('click', handleClick);
        restartBtn.addEventListener('click', () => {
            if (gameState.gameMode) {
                startGame(gameState.gameMode);
            }
        });
        undoBtn.addEventListener('click', undoMove);
        
        // 模式选择
        pvpBtn.addEventListener('click', () => startGame('pvp'));
        pveBtn.addEventListener('click', () => startGame('pve'));
        
        // 胜利模态框
        newGameBtn.addEventListener('click', () => {
            if (gameState.gameMode) {
                startGame(gameState.gameMode);
            }
        });
        
        changeModeBtn.addEventListener('click', initGame);
        
        // 鼠标悬停效果
        canvas.addEventListener('mousemove', (event) => {
            if (!gameState.gameActive) return;
            
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            
            const x = (event.clientX - rect.left) * scaleX;
            const y = (event.clientY - rect.top) * scaleY;
            
            const col = Math.round(x / CELL_SIZE);
            const row = Math.round(y / CELL_SIZE);
            
            // 重绘棋盘
            drawBoard();
            
            // 如果是有效空位，绘制预览
            if (row >= 0 && row < BOARD_SIZE && col >= 0 && col < BOARD_SIZE && 
                gameState.board[row][col] === 0) {
                ctx.beginPath();
                ctx.arc(col * CELL_SIZE, row * CELL_SIZE, CELL_SIZE * 0.3, 0, Math.PI * 2);
                ctx.fillStyle = gameState.currentPlayer === 1 ? 'rgba(0, 0, 0, 0.3)' : 'rgba(255, 255, 255, 0.6)';
                ctx.fill();
            }
        });
        
        canvas.addEventListener('mouseleave', () => {
            drawBoard();
        });
        
        // 初始化
        initGame();
    </script>
</body>
</html>