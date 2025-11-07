<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>记忆配对游戏 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .tool-content {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .game-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 1.1rem;
            color: #2c3e50;
        }
        
        .game-board {
            display: grid;
            grid-template-columns: repeat(4, 100px);
            grid-template-rows: repeat(4, 100px);
            gap: 10px;
            margin: 0 auto;
        }
        
        .card {
            background-color: #3498db;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            perspective: 1000px;
        }
        
        .card.flipped {
            background-color: white;
            color: #333;
            transform: rotateY(180deg);
        }
        
        .card.matched {
            background-color: #2ecc71;
            cursor: default;
        }
        
        .game-controls {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        button:hover {
            background-color: #c0392b;
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
            <h1>记忆配对游戏</h1>
            
            <div class="game-info">
                <div>配对: <span id="pairs">0</span>/8</div>
                <div>步数: <span id="moves">0</span></div>
            </div>
            <div class="game-board" id="board"></div>
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
            
        document.addEventListener('DOMContentLoaded', function() {
            const board = document.getElementById('board');
            const pairsElement = document.getElementById('pairs');
            const movesElement = document.getElementById('moves');
            const resetButton = document.getElementById('reset');
            
            const emojis = ['🐶', '??', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼'];
            let cards = [];
            let flippedCards = [];
            let matchedPairs = 0;
            let moves = 0;
            let canFlip = true;

            // 初始化游戏
            function initializeGame() {
                // 创建卡片对
                const cardValues = [...emojis, ...emojis];
                
                // 洗牌
                cardValues.sort(() => Math.random() - 0.5);
                
                // 清空游戏板
                board.innerHTML = '';
                flippedCards = [];
                matchedPairs = 0;
                moves = 0;
                pairsElement.textContent = '0';
                movesElement.textContent = '0';
                canFlip = true;
                
                // 创建卡片
                cards = cardValues.map((value, index) => {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.dataset.index = index;
                    card.dataset.value = value;
                    card.addEventListener('click', flipCard);
                    board.appendChild(card);
                    return card;
                });
            }

            // 翻转卡片
            function flipCard() {
                if (!canFlip || this.classList.contains('flipped') || this.classList.contains('matched')) return;
                
                this.classList.add('flipped');
                this.textContent = this.dataset.value;
                flippedCards.push(this);
                
                if (flippedCards.length === 2) {
                    canFlip = false;
                    moves++;
                    movesElement.textContent = moves;
                    
                    if (flippedCards[0].dataset.value === flippedCards[1].dataset.value) {
                        // 配对成功
                        flippedCards.forEach(card => {
                            card.classList.add('matched');
                            card.removeEventListener('click', flipCard);
                        });
                        matchedPairs++;
                        pairsElement.textContent = matchedPairs;
                        
                        if (matchedPairs === 8) {
                            setTimeout(() => {
                                alert(`恭喜你赢了！用了 ${moves} 步`);
                            }, 500);
                        }
                        
                        flippedCards = [];
                        canFlip = true;
                    } else {
                        // 配对失败
                        setTimeout(() => {
                            flippedCards.forEach(card => {
                                card.classList.remove('flipped');
                                card.textContent = '';
                            });
                            flippedCards = [];
                            canFlip = true;
                        }, 1000);
                    }
                }
            }

            // 重置游戏
            resetButton.addEventListener('click', initializeGame);

            // 初始化游戏
            initializeGame();
        });
    </script>
</body>
</html>
