<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>井字棋游戏 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .tool-content {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .game-container {
            text-align: center;
        }
        
        .game-title {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 2rem;
            font-weight: bold;
        }
        
        .game-info {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        
        .info-value {
            color: #2c3e50;
        }
        
        .current-player {
            display: inline-block;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid #dee2e6;
            vertical-align: middle;
            margin-left: 0.5rem;
        }
        
        .current-player.x {
            background: #e74c3c;
            color: white;
            line-height: 26px;
            font-weight: bold;
        }
        
        .current-player.o {
            background: #3498db;
            color: white;
            line-height: 26px;
            font-weight: bold;
        }
        
        .board {
            display: grid;
            grid-template-columns: repeat(3, 120px);
            grid-template-rows: repeat(3, 120px);
            gap: 10px;
            margin: 2rem auto;
            background: #34495e;
            padding: 10px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .cell {
            width: 120px;
            height: 120px;
            background-color: #ecf0f1;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 3rem;
            font-weight: bold;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .cell:hover:not(.taken) {
            background-color: #d5dbdd;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .cell.x {
            color: #e74c3c;
            background: linear-gradient(135deg, #fdf2f2 0%, #f5e6e6 100%);
        }
        
        .cell.o {
            color: #3498db;
            background: linear-gradient(135deg, #f2f8ff 0%, #e6f2ff 100%);
        }
        
        .cell.taken {
            cursor: not-allowed;
        }
        
        .cell.winner {
            animation: winnerGlow 0.6s ease-in-out infinite alternate;
        }
        
        @keyframes winnerGlow {
            from {
                box-shadow: 0 0 10px #f1c40f;
                background: #fff3cd;
            }
            to {
                box-shadow: 0 0 20px #f39c12;
                background: #ffeaa7;
            }
        }
        
        .game-controls {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        
        .btn.primary {
            background-color: #2ecc71;
        }
        
        .btn.primary:hover {
            background-color: #27ae60;
            box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
        }
        
        .btn.secondary {
            background-color: #95a5a6;
        }
        
        .btn.secondary:hover {
            background-color: #7f8c8d;
            box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
        }
        
        .back-btn {
            display: inline-block;
            margin-bottom: 2rem;
            padding: 0.75rem 1.5rem;
            background-color: #95a5a6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .back-btn:hover {
            background-color: #7f8c8d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
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
            border: 4px solid #34495e;
            border-radius: 12px;
            padding: 2.5rem;
            max-width: 450px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .modal-title {
            font-size: 2rem;
            color: #2c3e50;
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
            border: 2px solid #34495e;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        
        .modal-btn.pvp {
            background: #3498db;
            color: white;
        }
        
        .modal-btn.pve {
            background: #e74c3c;
            color: white;
        }
        
        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .game-stats {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        
        .stats-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }
        
        .stat-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            color: #495057;
        }
        
        .stat-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .win-line {
            position: absolute;
            background: #f1c40f;
            z-index: 10;
            border-radius: 4px;
            pointer-events: none;
        }
        
        @media (max-width: 768px) {
            .tool-content {
                padding: 10px;
            }
            
            .game-title {
                font-size: 2rem;
            }
            
            .board {
                grid-template-columns: repeat(3, 90px);
                grid-template-rows: repeat(3, 90px);
                gap: 8px;
                padding: 8px;
            }
            
            .cell {
                width: 90px;
                height: 90px;
                font-size: 2.2rem;
            }
            
            .modal-buttons {
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
                <h1 class="game-title"><i class="fa fa-gamepad"></i> 井字棋游戏</h1>
                
                <div class="game-stats">
                    <div class="stats-title">游戏统计</div>
                    <div class="stat-item">
                        <span>游戏时间:</span>
                        <span class="stat-value" id="gameTime">00:00</span>
                    </div>
                    <div class="stat-item">
                        <span>回合数:</span>
                        <span class="stat-value" id="moveCount">0</span>
                    </div>
                </div>
                
                <div class="game-info">
                    <div class="info-row">
                        <span class="info-label">游戏模式:</span>
                        <span class="info-value" id="gameMode">选择模式</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">当前玩家:</span>
                        <span class="info-value">
                            <span id="currentPlayerText">等待开始</span>
                            <span class="current-player" id="currentPlayer"></span>
                        </span>
                    </div>
                </div>
                
                <div class="board" id="board"></div>
                
                <div class="game-controls">
                    <button class="btn primary" id="restartBtn">
                        <i class="fa fa-refresh"></i> 重新开始
                    </button>
                    <button class="btn secondary" id="changeModeBtn">
                        <i class="fa fa-sliders"></i> 更换模式
                    </button>
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
                <i class="fa fa-info-circle"></i> X先手，三子连线获胜
            </p>
        </div>
    </div>
    
    <div id="footer-container"></div>

    <script>
        // 游戏状态
        let gameState = {
            board: ['', '', '', '', '', '', '', '', ''],
            currentPlayer: 'X',
            gameActive: false,
            gameMode: null, // 'pvp' 或 'pve'
            moveCount: 0,
            gameTime: 0,
            timerInterval: null,
            aiPlayer: 'O' // AI执O
        };
        
        // DOM元素
        const board = document.getElementById('board');
        const modeModal = document.getElementById('modeModal');
        const gameModeEl = document.getElementById('gameMode');
        const currentPlayerText = document.getElementById('currentPlayerText');
        const currentPlayerEl = document.getElementById('currentPlayer');
        const gameTimeEl = document.getElementById('gameTime');
        const moveCountEl = document.getElementById('moveCount');
        const restartBtn = document.getElementById('restartBtn');
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
            // 重置游戏状态
            gameState.board = ['', '', '', '', '', '', '', '', ''];
            gameState.currentPlayer = 'X';
            gameState.gameActive = false;
            gameState.gameMode = null;
            gameState.moveCount = 0;
            gameState.gameTime = 0;
            
            // 清除计时器
            if (gameState.timerInterval) {
                clearInterval(gameState.timerInterval);
                gameState.timerInterval = null;
            }
            
            // 重置显示
            gameTimeEl.textContent = '00:00';
            moveCountEl.textContent = '0';
            gameModeEl.textContent = '选择模式';
            currentPlayerText.textContent = '等待开始';
            currentPlayerEl.className = 'current-player';
            currentPlayerEl.textContent = '';
            
            // 初始化棋盘
            initializeBoard();
            
            // 显示模式选择
            modeModal.style.display = 'flex';
        }
        
        // 初始化棋盘
        function initializeBoard() {
            board.innerHTML = '';
            
            for (let i = 0; i < 9; i++) {
                const cell = document.createElement('div');
                cell.classList.add('cell');
                cell.setAttribute('data-index', i);
                cell.addEventListener('click', handleCellClick);
                cell.addEventListener('mouseenter', handleCellHover);
                cell.addEventListener('mouseleave', handleCellLeave);
                board.appendChild(cell);
            }
        }
        
        // 开始游戏
        function startGame(mode) {
            // 重置游戏状态
            gameState.board = ['', '', '', '', '', '', '', '', ''];
            gameState.currentPlayer = 'X';
            gameState.gameActive = true;
            gameState.moveCount = 0;
            gameState.gameTime = 0;
            gameState.gameMode = mode;
            
            // 清除计时器
            if (gameState.timerInterval) {
                clearInterval(gameState.timerInterval);
                gameState.timerInterval = null;
            }
            
            // 重置显示
            gameTimeEl.textContent = '00:00';
            moveCountEl.textContent = '0';
            gameModeEl.textContent = mode === 'pvp' ? '玩家对战' : '人机对战';
            
            // 重新初始化棋盘
            initializeBoard();
            updateGameStatus();
            
            // 隐藏模式选择
            modeModal.style.display = 'none';
            
            // 开始计时
            startTimer();
        }
        
        // 更新游戏状态显示
        function updateGameStatus() {
            if (gameState.gameActive) {
                const playerName = gameState.currentPlayer === 'X' ? 'X' : 'O';
                currentPlayerText.textContent = playerName + '回合';
                currentPlayerEl.className = `current-player ${gameState.currentPlayer.toLowerCase()}`;
                currentPlayerEl.textContent = gameState.currentPlayer;
            }
            
            moveCountEl.textContent = gameState.moveCount;
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
        
        // 处理格子悬停
        function handleCellHover(e) {
            if (!gameState.gameActive || gameState.board[e.target.dataset.index] !== '') return;
            
            e.target.style.background = gameState.currentPlayer === 'X' ? '#fdf2f2' : '#f2f8ff';
        }
        
        // 处理格子离开
        function handleCellLeave(e) {
            if (!gameState.gameActive || gameState.board[e.target.dataset.index] === '') {
                e.target.style.background = '';
            }
        }
        
        // 处理格子点击
        function handleCellClick(e) {
            const index = parseInt(e.target.dataset.index);
            
            if (gameState.board[index] !== '' || !gameState.gameActive) return;
            
            makeMove(index);
        }
        
        // 落子
        function makeMove(index) {
            gameState.board[index] = gameState.currentPlayer;
            gameState.moveCount++;
            
            // 更新格子显示
            const cell = board.children[index];
            cell.textContent = gameState.currentPlayer;
            cell.classList.add(gameState.currentPlayer.toLowerCase(), 'taken');
            
            updateGameStatus();
            
            // 检查胜利
            if (checkWinner()) {
                endGame(gameState.currentPlayer);
                return;
            }
            
            // 检查平局
            if (checkDraw()) {
                endGame(null);
                return;
            }
            
            // 切换玩家
            gameState.currentPlayer = gameState.currentPlayer === 'X' ? 'O' : 'X';
            updateGameStatus();
            
            // AI回合
            if (gameState.gameMode === 'pve' && gameState.currentPlayer === gameState.aiPlayer) {
                setTimeout(aiMove, 500);
            }
        }
        
        // 检查胜利条件
        function checkWinner() {
            const winPatterns = [
                [0, 1, 2], [3, 4, 5], [6, 7, 8], // 行
                [0, 3, 6], [1, 4, 7], [2, 5, 8], // 列
                [0, 4, 8], [2, 4, 6]             // 对角线
            ];

            for (const pattern of winPatterns) {
                const [a, b, c] = pattern;
                if (gameState.board[a] && gameState.board[a] === gameState.board[b] && gameState.board[a] === gameState.board[c]) {
                    highlightWinningLine(pattern);
                    return true;
                }
            }
            
            return false;
        }
        
        // 高亮获胜线条
        function highlightWinningLine(pattern) {
            pattern.forEach(index => {
                board.children[index].classList.add('winner');
            });
        }
        
        // 检查平局
        function checkDraw() {
            return gameState.board.every(cell => cell !== '');
        }
        
        // 游戏结束
        function endGame(winner) {
            gameState.gameActive = false;
            stopTimer();
            
            if (winner) {
                const winnerName = winner === 'X' ? 'X' : 'O';
                currentPlayerText.textContent = `${winnerName}获胜!`;
            } else {
                currentPlayerText.textContent = '平局!';
            }
            
            currentPlayerEl.className = 'current-player';
            currentPlayerEl.textContent = '';
        }
        
        // AI移动算法
        function aiMove() {
            if (!gameState.gameActive) return;
            
            const move = getBestMove();
            if (move !== -1) {
                makeMove(move);
            }
        }
        
        // 获取最佳移动位置
        function getBestMove() {
            // 1. 检查是否能直接获胜
            for (let i = 0; i < 9; i++) {
                if (gameState.board[i] === '') {
                    gameState.board[i] = gameState.aiPlayer;
                    if (checkWinnerForPlayer(gameState.aiPlayer)) {
                        gameState.board[i] = '';
                        return i;
                    }
                    gameState.board[i] = '';
                }
            }
            
            // 2. 检查是否需要阻止对手获胜
            const humanPlayer = gameState.aiPlayer === 'X' ? 'O' : 'X';
            for (let i = 0; i < 9; i++) {
                if (gameState.board[i] === '') {
                    gameState.board[i] = humanPlayer;
                    if (checkWinnerForPlayer(humanPlayer)) {
                        gameState.board[i] = '';
                        return i;
                    }
                    gameState.board[i] = '';
                }
            }
            
            // 3. 占据中心位置
            if (gameState.board[4] === '') {
                return 4;
            }
            
            // 4. 占据角落
            const corners = [0, 2, 6, 8];
            const availableCorners = corners.filter(i => gameState.board[i] === '');
            if (availableCorners.length > 0) {
                return availableCorners[Math.floor(Math.random() * availableCorners.length)];
            }
            
            // 5. 占据边
            const edges = [1, 3, 5, 7];
            const availableEdges = edges.filter(i => gameState.board[i] === '');
            if (availableEdges.length > 0) {
                return availableEdges[Math.floor(Math.random() * availableEdges.length)];
            }
            
            return -1;
        }
        
        // 检查指定玩家是否获胜
        function checkWinnerForPlayer(player) {
            const winPatterns = [
                [0, 1, 2], [3, 4, 5], [6, 7, 8], // 行
                [0, 3, 6], [1, 4, 7], [2, 5, 8], // 列
                [0, 4, 8], [2, 4, 6]             // 对角线
            ];

            return winPatterns.some(pattern => {
                const [a, b, c] = pattern;
                return gameState.board[a] === player && gameState.board[b] === player && gameState.board[c] === player;
            });
        }
        
        // 事件监听器
        restartBtn.addEventListener('click', () => {
            if (gameState.gameMode) {
                startGame(gameState.gameMode);
            }
        });
        
        changeModeBtn.addEventListener('click', initGame);
        
        // 模式选择
        pvpBtn.addEventListener('click', () => startGame('pvp'));
        pveBtn.addEventListener('click', () => startGame('pve'));
        
        // 初始化游戏
        initGame();
    </script>
</body>
</html>
