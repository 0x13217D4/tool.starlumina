<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>井字棋游戏 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .tool-content {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-template-rows: repeat(3, 100px);
            gap: 10px;
            margin: 20px auto;
        }
        
        .cell {
            width: 100px;
            height: 100px;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 48px;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .cell:hover {
            background-color: #e0e0e0;
            transform: scale(1.03);
        }
        
        .status {
            margin: 20px 0;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            color: #2c3e50;
        }
        
        .game-controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            background-color: #95a5a6;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>井字棋游戏</h1>
            
            <div class="status" id="status">轮到: X</div>
            <div class="board" id="board"></div>
            <div class="game-controls">
                <button id="reset">重新开始</button>
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
            const board = document.getElementById('board');
            const status = document.getElementById('status');
            const resetButton = document.getElementById('reset');
            let currentPlayer = 'X';
            let gameBoard = ['', '', '', '', '', '', '', '', ''];
            let gameActive = true;

            // 初始化游戏板
            function initializeBoard() {
                board.innerHTML = '';
                gameBoard = ['', '', '', '', '', '', '', '', ''];
                currentPlayer = 'X';
                gameActive = true;
                status.textContent = `轮到: ${currentPlayer}`;

                for (let i = 0; i < 9; i++) {
                    const cell = document.createElement('div');
                    cell.classList.add('cell');
                    cell.setAttribute('data-index', i);
                    cell.addEventListener('click', handleCellClick);
                    board.appendChild(cell);
                }
            }

            // 处理格子点击
            function handleCellClick(e) {
                const index = e.target.getAttribute('data-index');

                if (gameBoard[index] !== '' || !gameActive) return;

                gameBoard[index] = currentPlayer;
                e.target.textContent = currentPlayer;

                if (checkWinner()) {
                    status.textContent = `玩家 ${currentPlayer} 获胜!`;
                    gameActive = false;
                    return;
                }

                if (checkDraw()) {
                    status.textContent = '平局!';
                    gameActive = false;
                    return;
                }

                currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
                status.textContent = `轮到: ${currentPlayer}`;
            }

            // 检查胜利条件
            function checkWinner() {
                const winPatterns = [
                    [0, 1, 2], [3, 4, 5], [6, 7, 8], // 行
                    [0, 3, 6], [1, 4, 7], [2, 5, 8], // 列
                    [0, 4, 8], [2, 4, 6]             // 对角线
                ];

                return winPatterns.some(pattern => {
                    const [a, b, c] = pattern;
                    return gameBoard[a] && gameBoard[a] === gameBoard[b] && gameBoard[a] === gameBoard[c];
                });
            }

            // 检查平局
            function checkDraw() {
                return gameBoard.every(cell => cell !== '');
            }

            // 重置游戏
            resetButton.addEventListener('click', initializeBoard);

            // 初始化游戏
            initializeBoard();
        });
    </script>
</body>
</html>
