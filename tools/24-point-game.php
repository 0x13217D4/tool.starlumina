<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>24点游戏 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .tool-content {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .cards {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        
        .card {
            width: 70px;
            height: 90px;
            background-color: #3498db;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            font-weight: bold;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .input-area {
            margin: 20px 0;
        }
        
        input {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        
        .buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        
        button {
            padding: 12px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
        }
        
        #check {
            background-color: #2ecc71;
            color: white;
        }
        
        #check:hover {
            background-color: #27ae60;
        }
        
        #new-game {
            background-color: #f39c12;
            color: white;
        }
        
        #new-game:hover {
            background-color: #d35400;
        }
        
        #solution {
            background-color: #95a5a6;
            color: white;
        }
        
        #solution:hover {
            background-color: #7f8c8d;
        }
        
        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            display: none;
            font-size: 1rem;
        }
        
        .success {
            background-color: #2ecc71;
            color: white;
        }
        
        .error {
            background-color: #e74c3c;
            color: white;
        }
        
        .hint {
            background-color: #f1c40f;
            color: #2c3e50;
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
            <h1>24点游戏</h1>
            
            <div class="cards" id="cards"></div>
            <div class="input-area">
                <input type="text" id="expression" placeholder="输入你的算式，例如: (1+2)*(3+4)">
                <div class="buttons">
                    <button id="check">检查</button>
                    <button id="new-game">新游戏</button>
                    <button id="solution">提示</button>
                </div>
            </div>
            <div class="message" id="message"></div>
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
            const cardsContainer = document.getElementById('cards');
            const expressionInput = document.getElementById('expression');
            const checkButton = document.getElementById('check');
            const newGameButton = document.getElementById('new-game');
            const solutionButton = document.getElementById('solution');
            const messageElement = document.getElementById('message');
            
            let currentCards = [];
            let solution = '';

            // 初始化游戏
            function initializeGame() {
                // 生成4个1-13的随机数
                currentCards = [];
                for (let i = 0; i < 4; i++) {
                    currentCards.push(Math.floor(Math.random() * 13) + 1);
                }
                
                // 检查是否有解，如果没有则重新生成
                while (!hasSolution(currentCards)) {
                    currentCards = [];
                    for (let i = 0; i < 4; i++) {
                        currentCards.push(Math.floor(Math.random() * 13) + 1);
                    }
                }
                
                // 显示卡片
                renderCards();
                
                // 清空输入和消息
                expressionInput.value = '';
                hideMessage();
            }

            // 渲染卡片
            function renderCards() {
                cardsContainer.innerHTML = '';
                currentCards.forEach(card => {
                    const cardElement = document.createElement('div');
                    cardElement.className = 'card';
                    cardElement.textContent = card;
                    cardsContainer.appendChild(cardElement);
                });
            }

            // 检查用户输入的表达式
            function checkExpression() {
                const expression = expressionInput.value.trim();
                
                if (!expression) {
                    showMessage('请输入算式', 'error');
                    return;
                }
                
                // 检查是否使用了所有数字
                const usedNumbers = expression.match(/\d+/g) || [];
                const cardNumbers = [...currentCards];
                
                if (usedNumbers.length !== 4) {
                    showMessage('请使用全部4个数字', 'error');
                    return;
                }
                
                // 检查是否使用了正确的数字
                for (const num of usedNumbers) {
                    const index = cardNumbers.indexOf(Number(num));
                    if (index === -1) {
                        showMessage(`数字 ${num} 不在给定的卡片中`, 'error');
                        return;
                    }
                    cardNumbers.splice(index, 1);
                }
                
                // 计算表达式结果
                try {
                    const result = eval(expression);
                    if (Math.abs(result - 24) < 0.0001) {
                        showMessage('恭喜！计算正确！', 'success');
                    } else {
                        showMessage(`计算结果为 ${result}，不是24`, 'error');
                    }
                } catch (e) {
                    showMessage('算式无效，请检查', 'error');
                }
            }

            // 显示提示
            function showSolution() {
                if (!solution) {
                    solution = findSolution(currentCards);
                }
                
                if (solution) {
                    showMessage(`一个可能的解法: ${solution}`, 'hint');
                } else {
                    showMessage('这组数字无解，点击"新游戏"获取新的数字', 'hint');
                }
            }

            // 检查是否有解
            function hasSolution(numbers) {
                solution = findSolution(numbers);
                return solution !== '';
            }

            // 寻找解法
            function findSolution(numbers) {
                // 尝试所有可能的排列组合和运算符
                const ops = ['+', '-', '*', '/'];
                const permutations = getPermutations(numbers);
                
                for (const nums of permutations) {
                    for (const op1 of ops) {
                        for (const op2 of ops) {
                            for (const op3 of ops) {
                                // 尝试不同的括号组合
                                const expressions = [
                                    `(${nums[0]}${op1}${nums[1]})${op2}(${nums[2]}${op3}${nums[3]})`,
                                    `((${nums[0]}${op1}${nums[1]})${op2}${nums[2]})${op3}${nums[3]}`,
                                    `(${nums[0]}${op1}(${nums[1]}${op2}${nums[2]}))${op3}${nums[3]}`,
                                    `${nums[0]}${op1}(${nums[1]}${op2}(${nums[2]}${op3}${nums[3]}))`,
                                    `${nums[0]}${op1}((${nums[1]}${op2}${nums[2]})${op3}${nums[3]})`
                                ];
                                
                                for (const expr of expressions) {
                                    try {
                                        if (Math.abs(eval(expr) - 24) < 0.0001) {
                                            return expr;
                                        }
                                    } catch (e) {
                                        continue;
                                    }
                                }
                            }
                        }
                    }
                }
                
                return '';
            }

            // 获取所有排列组合
            function getPermutations(arr) {
                const result = [];
                
                function permute(arr, start = 0) {
                    if (start === arr.length - 1) {
                        result.push([...arr]);
                        return;
                    }
                    
                    for (let i = start; i < arr.length; i++) {
                        [arr[start], arr[i]] = [arr[i], arr[start]];
                        permute(arr, start + 1);
                        [arr[start], arr[i]] = [arr[i], arr[start]];
                    }
                }
                
                permute(arr);
                return result;
            }

            // 显示消息
            function showMessage(text, type) {
                messageElement.textContent = text;
                messageElement.className = `message ${type}`;
                messageElement.style.display = 'block';
            }

            // 隐藏消息
            function hideMessage() {
                messageElement.style.display = 'none';
            }

            // 事件监听
            checkButton.addEventListener('click', checkExpression);
            newGameButton.addEventListener('click', initializeGame);
            solutionButton.addEventListener('click', showSolution);

            // 初始化游戏
            initializeGame();
        });
    </script>
</body>
</html>
