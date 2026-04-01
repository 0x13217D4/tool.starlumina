<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2048 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <!-- Font Awesome -->
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <style>
        .game-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .game-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .game-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
        }
        
        .game-scores {
            display: flex;
            gap: 1rem;
        }
        
        .score-box {
            background-color: #3498db;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-align: center;
            min-width: 100px;
        }
        
        .score-label {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 0.25rem;
        }
        
        .score-value {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .best-score-box {
            background-color: #e74c3c;
        }
        
        .game-controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
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
            border: 2px solid #e0e0e0;
        }
        
        .btn-outline:hover {
            background-color: #f8f9fa;
            border-color: #bdc3c7;
        }
        
        .game-board {
            background-color: #bbada0;
            border-radius: 8px;
            padding: 1rem;
            position: relative;
            width: 100%;
            max-width: 500px;
            aspect-ratio: 1;
        }
        
        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(4, 1fr);
            gap: 0.5rem;
            width: 100%;
            height: 100%;
        }
        
        .grid-cell {
            background-color: rgba(238, 228, 218, 0.35);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            color: #776e65;
            position: relative;
        }
        
        .tile-2 { background-color: #eee4da; color: #776e65; }
        .tile-4 { background-color: #ede0c8; color: #776e65; }
        .tile-8 { background-color: #f2b179; color: #f9f6f2; }
        .tile-16 { background-color: #f59563; color: #f9f6f2; }
        .tile-32 { background-color: #f67c5f; color: #f9f6f2; }
        .tile-64 { background-color: #f65e3b; color: #f9f6f2; }
        .tile-128 { background-color: #edcf72; color: #f9f6f2; font-size: 1.75rem; }
        .tile-256 { background-color: #edcc61; color: #f9f6f2; font-size: 1.75rem; }
        .tile-512 { background-color: #edc850; color: #f9f6f2; font-size: 1.75rem; }
        .tile-1024 { background-color: #edc53f; color: #f9f6f2; font-size: 1.5rem; }
        .tile-2048 { background-color: #edc22e; color: #f9f6f2; font-size: 1.5rem; }
        .tile-super { background-color: #3c3a32; color: #f9f6f2; font-size: 1.25rem; }
        
        .tile-new {
            animation: appear 0.3s ease-in-out;
        }
        
        .tile-merged {
            animation: pop 0.3s ease-in-out;
        }
        
        @keyframes appear {
            0% { opacity: 0; transform: scale(0); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .game-message {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border-radius: 8px;
            font-size: 2rem;
            font-weight: bold;
            color: #776e65;
        }
        
        .game-message.show {
            display: flex;
        }
        
        .game-message .message-text {
            margin-bottom: 1rem;
        }
        
        .game-message .retry-btn {
            padding: 0.75rem 1.5rem;
            background-color: #8f7a66;
            color: #f9f6f2;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        
        .game-message .retry-btn:hover {
            background-color: #9f8b7a;
        }
        
        .game-instructions {
            text-align: center;
            color: #666;
            margin-top: 1rem;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        .mobile-controls {
            display: none;
            margin-top: 1rem;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            width: 200px;
        }
        
        .mobile-btn {
            padding: 1rem;
            background-color: #8f7a66;
            color: #f9f6f2;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.25rem;
            transition: background-color 0.3s;
        }
        
        .mobile-btn:hover {
            background-color: #9f8b7a;
        }
        
        .mobile-btn:active {
            background-color: #7f6b60;
        }
        
        @media (max-width: 768px) {
            .game-title {
                font-size: 2rem;
            }
            
            .game-scores {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .score-box {
                min-width: auto;
                padding: 0.5rem 1rem;
            }
            
            .game-controls {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .grid-cell {
                font-size: 1.5rem;
            }
            
            .tile-128, .tile-256, .tile-512 {
                font-size: 1.25rem;
            }
            
            .tile-1024, .tile-2048 {
                font-size: 1.125rem;
            }
            
            .tile-super {
                font-size: 1rem;
            }
            
            .mobile-controls {
                display: grid;
            }
            
            .game-instructions {
                font-size: 0.8rem;
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
                <div class="game-header">
                    <h1 class="game-title">2048</h1>
                    <div class="game-scores">
                        <div class="score-box">
                            <div class="score-label">分数</div>
                            <div class="score-value" id="score">0</div>
                        </div>
                        <div class="score-box best-score-box">
                            <div class="score-label">最高分</div>
                            <div class="score-value" id="best-score">0</div>
                        </div>
                    </div>
                </div>
                
                <div class="game-controls">
                    <button id="new-game" class="btn btn-primary">
                        <i class="fa fa-plus"></i> 新游戏
                    </button>
                    <button id="undo" class="btn btn-secondary">
                        <i class="fa fa-undo"></i> 撤销
                    </button>
                </div>
                
                <div class="game-board">
                    <div class="grid-container" id="grid">
                        <!-- Grid cells will be generated by JavaScript -->
                    </div>
                    <div class="game-message" id="game-message">
                        <div class="message-text" id="message-text">游戏结束！</div>
                        <button class="retry-btn" id="retry-btn">重新开始</button>
                    </div>
                </div>
                
                <div class="mobile-controls">
                    <div></div>
                    <button class="mobile-btn" id="up-btn"><i class="fa fa-arrow-up"></i></button>
                    <div></div>
                    <button class="mobile-btn" id="left-btn"><i class="fa fa-arrow-left"></i></button>
                    <button class="mobile-btn" id="down-btn"><i class="fa fa-arrow-down"></i></button>
                    <button class="mobile-btn" id="right-btn"><i class="fa fa-arrow-right"></i></button>
                </div>
                
                <div class="game-instructions">
                    <p><strong>游戏规则：</strong>使用方向键或点击按钮移动方块，相同数字合并成更大的数字。目标是创建2048方块！</p>
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

        class Game2048 {
            constructor() {
                this.size = 4;
                this.grid = [];
                this.score = 0;
                this.bestScore = 0;
                this.gameOver = false;
                this.gameWon = false;
                this.history = [];
                
                this.gridElement = document.getElementById('grid');
                this.scoreElement = document.getElementById('score');
                this.bestScoreElement = document.getElementById('best-score');
                this.messageElement = document.getElementById('game-message');
                this.messageTextElement = document.getElementById('message-text');
                this.retryBtn = document.getElementById('retry-btn');
                this.newGameBtn = document.getElementById('new-game');
                this.undoBtn = document.getElementById('undo');
                
                // 移动端控制
                this.upBtn = document.getElementById('up-btn');
                this.downBtn = document.getElementById('down-btn');
                this.leftBtn = document.getElementById('left-btn');
                this.rightBtn = document.getElementById('right-btn');
                
                this.init();
            }
            
            init() {
                this.loadBestScore();
                this.createGrid();
                this.setupEventListeners();
                this.startNewGame();
            }
            
            createGrid() {
                this.gridElement.innerHTML = '';
                for (let i = 0; i < this.size * this.size; i++) {
                    const cell = document.createElement('div');
                    cell.className = 'grid-cell';
                    cell.id = `cell-${i}`;
                    this.gridElement.appendChild(cell);
                }
            }
            
            setupEventListeners() {
                // 键盘控制
                document.addEventListener('keydown', (e) => {
                    if (this.gameOver) return;
                    
                    switch(e.key) {
                        case 'ArrowUp':
                            e.preventDefault();
                            this.move('up');
                            break;
                        case 'ArrowDown':
                            e.preventDefault();
                            this.move('down');
                            break;
                        case 'ArrowLeft':
                            e.preventDefault();
                            this.move('left');
                            break;
                        case 'ArrowRight':
                            e.preventDefault();
                            this.move('right');
                            break;
                    }
                });
                
                // 按钮控制
                this.newGameBtn.addEventListener('click', () => this.startNewGame());
                this.undoBtn.addEventListener('click', () => this.undo());
                this.retryBtn.addEventListener('click', () => this.startNewGame());
                
                // 移动端控制
                this.upBtn.addEventListener('click', () => this.move('up'));
                this.downBtn.addEventListener('click', () => this.move('down'));
                this.leftBtn.addEventListener('click', () => this.move('left'));
                this.rightBtn.addEventListener('click', () => this.move('right'));
                
                // 触摸控制
                let startX, startY;
                this.gridElement.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                });
                
                this.gridElement.addEventListener('touchend', (e) => {
                    if (!startX || !startY || this.gameOver) return;
                    
                    const endX = e.changedTouches[0].clientX;
                    const endY = e.changedTouches[0].clientY;
                    
                    const deltaX = endX - startX;
                    const deltaY = endY - startY;
                    
                    if (Math.abs(deltaX) > Math.abs(deltaY)) {
                        if (deltaX > 0) {
                            this.move('right');
                        } else {
                            this.move('left');
                        }
                    } else {
                        if (deltaY > 0) {
                            this.move('down');
                        } else {
                            this.move('up');
                        }
                    }
                    
                    startX = null;
                    startY = null;
                });
            }
            
            startNewGame() {
                this.grid = Array(this.size).fill().map(() => Array(this.size).fill(0));
                this.score = 0;
                this.gameOver = false;
                this.gameWon = false;
                this.history = [];
                
                this.addRandomTile();
                this.addRandomTile();
                this.updateDisplay();
                this.hideMessage();
            }
            
            addRandomTile() {
                const emptyCells = [];
                for (let r = 0; r < this.size; r++) {
                    for (let c = 0; c < this.size; c++) {
                        if (this.grid[r][c] === 0) {
                            emptyCells.push({r, c});
                        }
                    }
                }
                
                if (emptyCells.length > 0) {
                    const randomCell = emptyCells[Math.floor(Math.random() * emptyCells.length)];
                    const value = Math.random() < 0.9 ? 2 : 4;
                    this.grid[randomCell.r][randomCell.c] = value;
                    return randomCell;
                }
                
                return null;
            }
            
            move(direction) {
                if (this.gameOver) return;
                
                this.saveHistory();
                let moved = false;
                const newGrid = this.grid.map(row => [...row]);
                
                switch(direction) {
                    case 'up':
                        moved = this.moveUp(newGrid);
                        break;
                    case 'down':
                        moved = this.moveDown(newGrid);
                        break;
                    case 'left':
                        moved = this.moveLeft(newGrid);
                        break;
                    case 'right':
                        moved = this.moveRight(newGrid);
                        break;
                }
                
                if (moved) {
                    this.grid = newGrid;
                    this.addRandomTile();
                    this.updateDisplay();
                    
                    if (this.checkWin()) {
                        this.gameWon = true;
                        this.showMessage('恭喜！你赢了！');
                    } else if (this.checkGameOver()) {
                        this.gameOver = true;
                        this.showMessage('游戏结束！');
                    }
                } else {
                    this.history.pop(); // 撤销历史记录
                }
            }
            
            moveUp(grid) {
                let moved = false;
                for (let c = 0; c < this.size; c++) {
                    let writePos = 0;
                    for (let r = 0; r < this.size; r++) {
                        if (grid[r][c] !== 0) {
                            if (grid[writePos][c] === 0) {
                                grid[writePos][c] = grid[r][c];
                                grid[r][c] = 0;
                                moved = true;
                            } else if (grid[writePos][c] === grid[r][c] && writePos !== r) {
                                grid[writePos][c] *= 2;
                                this.score += grid[writePos][c];
                                grid[r][c] = 0;
                                moved = true;
                            }
                            writePos++;
                        }
                    }
                }
                return moved;
            }
            
            moveDown(grid) {
                let moved = false;
                for (let c = 0; c < this.size; c++) {
                    let writePos = this.size - 1;
                    for (let r = this.size - 1; r >= 0; r--) {
                        if (grid[r][c] !== 0) {
                            if (grid[writePos][c] === 0) {
                                grid[writePos][c] = grid[r][c];
                                grid[r][c] = 0;
                                moved = true;
                            } else if (grid[writePos][c] === grid[r][c] && writePos !== r) {
                                grid[writePos][c] *= 2;
                                this.score += grid[writePos][c];
                                grid[r][c] = 0;
                                moved = true;
                            }
                            writePos--;
                        }
                    }
                }
                return moved;
            }
            
            moveLeft(grid) {
                let moved = false;
                for (let r = 0; r < this.size; r++) {
                    let writePos = 0;
                    for (let c = 0; c < this.size; c++) {
                        if (grid[r][c] !== 0) {
                            if (grid[r][writePos] === 0) {
                                grid[r][writePos] = grid[r][c];
                                grid[r][c] = 0;
                                moved = true;
                            } else if (grid[r][writePos] === grid[r][c] && writePos !== c) {
                                grid[r][writePos] *= 2;
                                this.score += grid[r][writePos];
                                grid[r][c] = 0;
                                moved = true;
                            }
                            writePos++;
                        }
                    }
                }
                return moved;
            }
            
            moveRight(grid) {
                let moved = false;
                for (let r = 0; r < this.size; r++) {
                    let writePos = this.size - 1;
                    for (let c = this.size - 1; c >= 0; c--) {
                        if (grid[r][c] !== 0) {
                            if (grid[r][writePos] === 0) {
                                grid[r][writePos] = grid[r][c];
                                grid[r][c] = 0;
                                moved = true;
                            } else if (grid[r][writePos] === grid[r][c] && writePos !== c) {
                                grid[r][writePos] *= 2;
                                this.score += grid[r][writePos];
                                grid[r][c] = 0;
                                moved = true;
                            }
                            writePos--;
                        }
                    }
                }
                return moved;
            }
            
            checkWin() {
                for (let r = 0; r < this.size; r++) {
                    for (let c = 0; c < this.size; c++) {
                        if (this.grid[r][c] === 2048) {
                            return true;
                        }
                    }
                }
                return false;
            }
            
            checkGameOver() {
                // 检查是否有空格
                for (let r = 0; r < this.size; r++) {
                    for (let c = 0; c < this.size; c++) {
                        if (this.grid[r][c] === 0) {
                            return false;
                        }
                    }
                }
                
                // 检查是否有可合并的相邻方块
                for (let r = 0; r < this.size; r++) {
                    for (let c = 0; c < this.size; c++) {
                        const current = this.grid[r][c];
                        if (current !== 0) {
                            // 检查右边
                            if (c < this.size - 1 && this.grid[r][c + 1] === current) {
                                return false;
                            }
                            // 检查下边
                            if (r < this.size - 1 && this.grid[r + 1][c] === current) {
                                return false;
                            }
                        }
                    }
                }
                
                return true;
            }
            
            saveHistory() {
                this.history.push({
                    grid: this.grid.map(row => [...row]),
                    score: this.score
                });
                
                // 限制历史记录长度
                if (this.history.length > 10) {
                    this.history.shift();
                }
            }
            
            undo() {
                if (this.history.length === 0 || this.gameOver) return;
                
                const lastState = this.history.pop();
                this.grid = lastState.grid;
                this.score = lastState.score;
                this.updateDisplay();
                this.hideMessage();
            }
            
            updateDisplay() {
                // 更新分数
                this.scoreElement.textContent = this.score;
                if (this.score > this.bestScore) {
                    this.bestScore = this.score;
                    this.bestScoreElement.textContent = this.bestScore;
                    this.saveBestScore();
                }
                
                // 更新网格
                for (let r = 0; r < this.size; r++) {
                    for (let c = 0; c < this.size; c++) {
                        const cellIndex = r * this.size + c;
                        const cell = document.getElementById(`cell-${cellIndex}`);
                        const value = this.grid[r][c];
                        
                        cell.textContent = value || '';
                        cell.className = 'grid-cell';
                        
                        if (value > 0) {
                            cell.classList.add(`tile-${value}`);
                            if (value > 2048) {
                                cell.classList.add('tile-super');
                            }
                        }
                    }
                }
            }
            
            showMessage(text) {
                this.messageTextElement.textContent = text;
                this.messageElement.classList.add('show');
            }
            
            hideMessage() {
                this.messageElement.classList.remove('show');
            }
            
            loadBestScore() {
                const saved = localStorage.getItem('2048-best-score');
                if (saved) {
                    this.bestScore = parseInt(saved);
                    this.bestScoreElement.textContent = this.bestScore;
                }
            }
            
            saveBestScore() {
                localStorage.setItem('2048-best-score', this.bestScore);
            }
        }
        
        // 初始化游戏
        document.addEventListener('DOMContentLoaded', () => {
            new Game2048();
        });
    </script>
</body>
</html>