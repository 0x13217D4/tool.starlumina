<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>扫雷游戏 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        /* 扫雷游戏特定样式 */
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
            color: #8B4513;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        
        .difficulty-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .difficulty-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid #8B4513;
            background: white;
            color: #8B4513;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .difficulty-btn:hover {
            background: #D2B48C;
            transform: translateY(-2px);
        }
        
        .difficulty-btn.active {
            background: #8B4513;
            color: white;
        }
        
        .game-board-container {
            background: #D2B48C;
            border: 4px solid #3E2723;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .game-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            background: #3E2723;
            padding: 0.75rem;
            border-radius: 8px;
        }
        
        .info-display {
            background: #000;
            color: #FF0000;
            padding: 0.5rem 1rem;
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 4px;
            min-width: 60px;
            text-align: center;
        }
        
        .reset-btn {
            width: 50px;
            height: 50px;
            background: #FFD700;
            border: 2px solid #3E2723;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.2s ease;
        }
        
        .reset-btn:hover {
            background: #FFA500;
            transform: scale(1.05);
        }
        
        .reset-btn:active {
            transform: scale(0.95);
        }
        
        .game-board {
            display: grid;
            gap: 1px;
            background: #3E2723;
            padding: 2px;
            margin: 0 auto;
        }
        
        .cell {
            background: #F5F5DC;
            border: 2px solid #F5F5DC;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            transition: all 0.1s ease;
            position: relative;
        }
        
        .cell:hover:not(.revealed):not(.flagged) {
            background: #E6E6D2;
        }
        
        .cell.revealed {
            background: #E6E6D2;
            border: 1px solid #D2B48C;
            cursor: default;
        }
        
        .cell.mine {
            background: #FF6B6B;
        }
        
        .cell.flagged {
            background: #87CEEB;
        }
        
        .cell.flagged::after {
            content: '🚩';
            position: absolute;
            font-size: 1rem;
        }
        
        .cell.mine::after {
            content: '💣';
            font-size: 1rem;
        }
        
        .cell.number-1 { color: #0000FF; }
        .cell.number-2 { color: #008000; }
        .cell.number-3 { color: #FF0000; }
        .cell.number-4 { color: #000080; }
        .cell.number-5 { color: #800000; }
        .cell.number-6 { color: #008080; }
        .cell.number-7 { color: #000000; }
        .cell.number-8 { color: #808080; }
        
        .scores-section {
            margin-top: 3rem;
            background: #F5F5DC;
            border: 2px solid #8B4513;
            border-radius: 12px;
            padding: 2rem;
        }
        
        .scores-title {
            text-align: center;
            font-size: 1.8rem;
            color: #8B4513;
            margin-bottom: 2rem;
            font-weight: bold;
        }
        
        .scores-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .score-card {
            background: white;
            border: 2px solid #D2B48C;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
        }
        
        .score-card h3 {
            color: #8B4513;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .score-list {
            list-style: none;
            padding: 0;
        }
        
        .score-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #E6E6D2;
        }
        
        .score-list li:last-child {
            border-bottom: none;
        }
        
        .score-rank {
            font-weight: bold;
            color: #8B4513;
        }
        
        .score-time {
            font-family: 'Courier New', monospace;
            color: #3E2723;
        }
        
        .game-over-modal {
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
        
        .modal-content {
            background: white;
            border: 4px solid #8B4513;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            max-width: 400px;
            margin: 1rem;
        }
        
        .modal-title {
            font-size: 2rem;
            color: #8B4513;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        
        .modal-message {
            font-size: 1.2rem;
            color: #3E2723;
            margin-bottom: 2rem;
        }
        
        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .modal-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid #8B4513;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .modal-btn.primary {
            background: #8B4513;
            color: white;
        }
        
        .modal-btn.secondary {
            background: white;
            color: #8B4513;
        }
        
        .modal-btn:hover {
            transform: translateY(-2px);
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .game-title {
                font-size: 2rem;
            }
            
            .difficulty-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .difficulty-btn {
                width: 200px;
            }
            
            .cell {
                font-size: 0.8rem;
            }
            
            .info-display {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1><i class="fa fa-bomb"></i> 扫雷游戏</h1>
            
            <div class="game-container">
                <div class="game-header">
                <div class="difficulty-buttons">
                    <button class="difficulty-btn active" data-level="beginner">简单 (9×9)</button>
                    <button class="difficulty-btn" data-level="intermediate">困难 (16×16)</button>
                </div>
                </div>
                
                <div class="game-board-container">
                    <div class="game-info">
                        <div class="info-display" id="mineCounter">010</div>
                        <button class="reset-btn" id="resetBtn">😊</button>
                        <div class="info-display" id="timer">000</div>
                    </div>
                    <div class="game-board" id="gameBoard"></div>
                </div>
                
                <div class="scores-section">
                    <h2 class="scores-title"><i class="fa fa-trophy"></i> 最佳成绩</h2>
                    <div class="scores-grid">
                        <div class="score-card">
                            <h3>简单</h3>
                            <ul class="score-list" id="beginnerScores">
                                <li><span class="score-rank">1.</span> <span class="score-time">--:--</span></li>
                                <li><span class="score-rank">2.</span> <span class="score-time">--:--</span></li>
                                <li><span class="score-rank">3.</span> <span class="score-time">--:--</span></li>
                            </ul>
                        </div>
                        <div class="score-card">
                            <h3>困难</h3>
                            <ul class="score-list" id="intermediateScores">
                                <li><span class="score-rank">1.</span> <span class="score-time">--:--</span></li>
                                <li><span class="score-rank">2.</span> <span class="score-time">--:--</span></li>
                                <li><span class="score-rank">3.</span> <span class="score-time">--:--</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div class="game-over-modal" id="gameOverModal">
        <div class="modal-content">
            <h2 class="modal-title" id="modalTitle">游戏结束!</h2>
            <p class="modal-message" id="modalMessage">你踩到地雷了!</p>
            <div class="modal-buttons">
                <button class="modal-btn primary" id="playAgainBtn">再玩一次</button>
                <button class="modal-btn secondary" id="closeModalBtn">关闭</button>
            </div>
        </div>
    </div>
    
    <div id="footer-container"></div>

    <script>
        // 游戏配置
        const gameConfig = {
            beginner: { rows: 9, cols: 9, mines: 10 },
            intermediate: { rows: 16, cols: 16, mines: 40 }
        };

        // 游戏状态
        let gameState = {
            difficulty: 'beginner',
            rows: 9,
            cols: 9,
            mines: 10,
            board: [],
            gameOver: false,
            gameStarted: false,
            timer: 0,
            timerInterval: null,
            flagsPlaced: 0,
            cellsRevealed: 0
        };

        // DOM 元素
        const gameBoard = document.getElementById('gameBoard');
        const mineCounter = document.getElementById('mineCounter');
        const timerDisplay = document.getElementById('timer');
        const resetBtn = document.getElementById('resetBtn');
        const gameOverModal = document.getElementById('gameOverModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const playAgainBtn = document.getElementById('playAgainBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');

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
            gameState.gameOver = false;
            gameState.gameStarted = false;
            gameState.timer = 0;
            gameState.flagsPlaced = 0;
            gameState.cellsRevealed = 0;
            
            // 清除计时器
            if (gameState.timerInterval) {
                clearInterval(gameState.timerInterval);
                gameState.timerInterval = null;
            }
            
            // 重置显示
            timerDisplay.textContent = '000';
            mineCounter.textContent = gameState.mines.toString().padStart(3, '0');
            resetBtn.textContent = '😊';
            
            // 创建游戏板
            createBoard();
            
            // 加载排行榜
            loadScores();
        }

        // 创建游戏板
        function createBoard() {
            const { rows, cols } = gameState;
            gameBoard.innerHTML = '';
            gameBoard.style.gridTemplateRows = `repeat(${rows}, 1fr)`;
            gameBoard.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
            
            // 计算单元格大小
            const containerElement = gameBoard.parentElement;
            const containerMaxWidth = containerElement.clientWidth - 32; // 减去padding
            const maxBoardWidth = Math.min(containerMaxWidth, 800);
            const cellSize = Math.min(30, Math.floor(maxBoardWidth / cols));
            
            // 设置最小和最大尺寸限制
            const minCellSize = 20;
            const finalCellSize = Math.max(minCellSize, Math.min(cellSize, 30));
            
            gameBoard.style.width = `${cols * finalCellSize}px`;
            gameBoard.style.height = `${rows * finalCellSize}px`;
            
            // 初始化游戏板数据
            gameState.board = [];
            for (let row = 0; row < rows; row++) {
                gameState.board[row] = [];
                for (let col = 0; col < cols; col++) {
                    const cell = createCell(row, col, finalCellSize);
                    gameBoard.appendChild(cell);
                    gameState.board[row][col] = {
                        element: cell,
                        isMine: false,
                        isRevealed: false,
                        isFlagged: false,
                        adjacentMines: 0
                    };
                }
            }
        }

        // 创建单个单元格
        function createCell(row, col, cellSize) {
            const cell = document.createElement('div');
            cell.className = 'cell';
            cell.dataset.row = row;
            cell.dataset.col = col;
            
            // 设置单元格大小
            const fontSize = Math.max("0.7rem", Math.min(cellSize * 0.3, "0.9rem"));
            cell.style.fontSize = fontSize;
            
            // 添加事件监听器
            cell.addEventListener('click', (e) => handleCellClick(e, row, col));
            cell.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                handleRightClick(e, row, col);
            });
            
            return cell;
        }

        // 处理左键点击
        function handleCellClick(event, row, col) {
            const cell = gameState.board[row][col];
            
            if (gameState.gameOver || cell.isRevealed || cell.isFlagged) return;
            
            // 第一次点击开始游戏
            if (!gameState.gameStarted) {
                startGame(row, col);
                return;
            }
            
            revealCell(row, col);
        }

        // 处理右键点击
        function handleRightClick(event, row, col) {
            const cell = gameState.board[row][col];
            
            if (gameState.gameOver || cell.isRevealed) return;
            
            if (!gameState.gameStarted) return;
            
            toggleFlag(row, col);
        }

        // 开始游戏
        function startGame(firstRow, firstCol) {
            gameState.gameStarted = true;
            
            // 放置地雷
            placeMines(firstRow, firstCol);
            
            // 计算相邻地雷数
            calculateAdjacentMines();
            
            // 揭开第一个单元格
            revealCell(firstRow, firstCol);
            
            // 开始计时
            startTimer();
        }

        // 放置地雷
        function placeMines(firstRow, firstCol) {
            const { rows, cols, mines } = gameState;
            let minesPlaced = 0;
            
            while (minesPlaced < mines) {
                const row = Math.floor(Math.random() * rows);
                const col = Math.floor(Math.random() * cols);
                
                // 确保第一个点击位置及其周围没有地雷
                const isProtectedArea = 
                    Math.abs(row - firstRow) <= 1 && Math.abs(col - firstCol) <= 1;
                
                if (!gameState.board[row][col].isMine && !isProtectedArea) {
                    gameState.board[row][col].isMine = true;
                    minesPlaced++;
                }
            }
        }

        // 计算相邻地雷数
        function calculateAdjacentMines() {
            const { rows, cols } = gameState;
            
            for (let row = 0; row < rows; row++) {
                for (let col = 0; col < cols; col++) {
                    if (!gameState.board[row][col].isMine) {
                        let count = 0;
                        
                        for (let r = Math.max(0, row - 1); r <= Math.min(rows - 1, row + 1); r++) {
                            for (let c = Math.max(0, col - 1); c <= Math.min(cols - 1, col + 1); c++) {
                                if (gameState.board[r][c].isMine) count++;
                            }
                        }
                        
                        gameState.board[row][col].adjacentMines = count;
                    }
                }
            }
        }
        // 揭开单元格
        function revealCell(row, col) {
            const cell = gameState.board[row][col];
            
            if (cell.isRevealed || cell.isFlagged) return;
            
            cell.isRevealed = true;
            cell.element.classList.add('revealed');
            gameState.cellsRevealed++;
            
            if (cell.isMine) {
                cell.element.classList.add('mine');
                gameOver(false);
                return;
            }
            
            if (cell.adjacentMines > 0) {
                cell.element.textContent = cell.adjacentMines;
                cell.element.classList.add(`number-${cell.adjacentMines}`);
            } else {
                // 递归揭开周围的空白单元格
                for (let r = Math.max(0, row - 1); r <= Math.min(gameState.rows - 1, row + 1); r++) {
                    for (let c = Math.max(0, col - 1); c <= Math.min(gameState.cols - 1, col + 1); c++) {
                        revealCell(r, c);
                    }
                }
            }
            
            checkWin();
        }

        // 切换旗帜
        function toggleFlag(row, col) {
            const cell = gameState.board[row][col];
            
            cell.isFlagged = !cell.isFlagged;
            
            if (cell.isFlagged) {
                cell.element.classList.add('flagged');
                gameState.flagsPlaced++;
            } else {
                cell.element.classList.remove('flagged');
                gameState.flagsPlaced--;
            }
            
            // 更新地雷计数器
            const remainingMines = gameState.mines - gameState.flagsPlaced;
            mineCounter.textContent = remainingMines.toString().padStart(3, '0');
        }

        // 开始计时
        function startTimer() {
            gameState.timerInterval = setInterval(() => {
                gameState.timer++;
                if (gameState.timer > 999) {
                    gameState.timer = 999;
                    clearInterval(gameState.timerInterval);
                }
                timerDisplay.textContent = gameState.timer.toString().padStart(3, '0');
            }, 1000);
        }

        // 检查胜利
        function checkWin() {
            const totalCells = gameState.rows * gameState.cols;
            const nonMineCells = totalCells - gameState.mines;
            
            if (gameState.cellsRevealed === nonMineCells) {
                gameOver(true);
            }
        }

        // 游戏结束
        function gameOver(isWin) {
            gameState.gameOver = true;
            
            // 停止计时器
            if (gameState.timerInterval) {
                clearInterval(gameState.timerInterval);
                gameState.timerInterval = null;
            }
            
            // 显示所有地雷
            for (let row = 0; row < gameState.rows; row++) {
                for (let col = 0; col < gameState.cols; col++) {
                    const cell = gameState.board[row][col];
                    if (cell.isMine) {
                        cell.element.classList.add('mine');
                        // 只有当没有旗帜且没有显示内容时才添加地雷图标
                        if (!cell.isFlagged && !cell.element.textContent) {
                            cell.element.textContent = '💣';
                        }
                    }
                }
            }
            
            // 更新表情
            resetBtn.textContent = isWin ? '😎' : '😵';
            
            // 显示结果
            if (isWin) {
                modalTitle.textContent = '恭喜！';
                modalMessage.textContent = `你成功扫雷完成，用时 ${formatTime(gameState.timer)}！`;
                saveScore(gameState.timer);
            } else {
                modalTitle.textContent = '游戏结束！';
                modalMessage.textContent = '你踩到地雷了！';
            }
            
            // 显示模态框
            gameOverModal.style.display = 'flex';
        }

        // 格式化时间
        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        // 保存成绩
        function saveScore(time) {
            const scores = JSON.parse(localStorage.getItem(`${gameState.difficulty}Scores`) || '[]');
            scores.push(time);
            scores.sort((a, b) => a - b);
            scores.splice(3); // 只保留前3名
            localStorage.setItem(`${gameState.difficulty}Scores`, JSON.stringify(scores));
            updateScoreboard();
        }

        // 加载成绩
        function loadScores() {
            for (const difficulty in gameConfig) {
                const scores = JSON.parse(localStorage.getItem(`${difficulty}Scores`) || '[]');
                updateScoreboardForDifficulty(difficulty, scores);
            }
        }

        // 更新排行榜
        function updateScoreboard() {
            const scores = JSON.parse(localStorage.getItem(`${gameState.difficulty}Scores`) || '[]');
            updateScoreboardForDifficulty(gameState.difficulty, scores);
        }

        // 更新特定难度的排行榜
        function updateScoreboardForDifficulty(difficulty, scores) {
            const scoreList = document.getElementById(`${difficulty}Scores`);
            scoreList.innerHTML = '';
            
            for (let i = 0; i < 3; i++) {
                const li = document.createElement('li');
                const rank = i + 1;
                const time = scores[i] !== undefined ? formatTime(scores[i]) : '--:--';
                li.innerHTML = `<span class="score-rank">${rank}.</span> <span class="score-time">${time}</span>`;
                scoreList.appendChild(li);
            }
        }

        // 切换难度
        function changeDifficulty(difficulty) {
            if (gameState.gameStarted && !gameState.gameOver) {
                if (!confirm('更改难度将重置当前游戏，确定要继续吗？')) {
                    return;
                }
            }
            
            gameState.difficulty = difficulty;
            gameState.rows = gameConfig[difficulty].rows;
            gameState.cols = gameConfig[difficulty].cols;
            gameState.mines = gameConfig[difficulty].mines;
            
            // 更新按钮状态
            document.querySelectorAll('.difficulty-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-level="${difficulty}"]`).classList.add('active');
            
            initGame();
        }

        // 事件监听器
        resetBtn.addEventListener('click', initGame);
        playAgainBtn.addEventListener('click', () => {
            gameOverModal.style.display = 'none';
            initGame();
        });
        closeModalBtn.addEventListener('click', () => {
            gameOverModal.style.display = 'none';
        });

        // 难度按钮事件
        document.querySelectorAll('.difficulty-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                changeDifficulty(btn.dataset.level);
            });
        });

        // 响应式调整
        window.addEventListener('resize', () => {
            if (!gameState.gameOver) {
                createBoard();
            }
        });

        // 初始化游戏
        initGame();
    </script>
</body>
</html>