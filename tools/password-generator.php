<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>密码生成器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .password-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .password-options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .option-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .option-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .option-row label {
            font-weight: normal;
        }
        
        .length-control {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .length-slider {
            flex: 1;
        }
        
        .length-value {
            width: 3rem;
            text-align: center;
            font-weight: bold;
        }
        
        input[type="range"] {
            -webkit-appearance: none;
            height: 8px;
            border-radius: 4px;
            background: #e0e0e0;
            outline: none;
        }
        
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #3498db;
            cursor: pointer;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .password-result {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 6px;
            background-color: #f8f9fa;
            font-family: monospace;
            font-size: 1.2rem;
            text-align: center;
            word-break: break-all;
        }
        
        .password-strength {
            margin-top: 0.5rem;
            height: 6px;
            border-radius: 3px;
            background-color: #e0e0e0;
            overflow: hidden;
        }
        
        .strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }
        
        .strength-text {
            margin-top: 0.25rem;
            font-size: 0.9rem;
            color: #6c757d;
            text-align: center;
        }
        
        .password-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .use-btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            font-size: 1rem;
            flex: 1;
        }
        
        .use-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .use-btn.secondary {
            background-color: #6c757d;
        }
        
        .use-btn.secondary:hover {
            background-color: #5a6268;
        }
        
        label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            display: block;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>密码生成器</h1>
            <div class="info-card">
                <div class="password-controls">
                    <div class="password-options">
                        <div class="option-group">
                            <label>密码长度：</label>
                            <div class="length-control">
                                <input type="range" id="length-slider" class="length-slider" min="8" max="32" value="12">
                                <span id="length-value" class="length-value">12</span>
                            </div>
                        </div>
                        
                        <div class="option-group">
                            <label>包含字符类型：</label>
                            <div class="option-row">
                                <input type="checkbox" id="lowercase" checked>
                                <label for="lowercase">小写字母 (a-z)</label>
                            </div>
                            <div class="option-row">
                                <input type="checkbox" id="uppercase" checked>
                                <label for="uppercase">大写字母 (A-Z)</label>
                            </div>
                            <div class="option-row">
                                <input type="checkbox" id="numbers" checked>
                                <label for="numbers">数字 (0-9)</label>
                            </div>
                            <div class="option-row">
                                <input type="checkbox" id="symbols" checked>
                                <label for="symbols">符号 (!@#$%^&*)</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label>生成的密码：</label>
                        <div class="password-result" id="password-result">点击"生成密码"按钮</div>
                        <div class="password-strength">
                            <div class="strength-bar" id="strength-bar"></div>
                        </div>
                        <div class="strength-text" id="strength-text">密码强度: 无</div>
                    </div>
                    
                    <div class="password-buttons">
                        <button id="generate-btn" class="use-btn">生成密码</button>
                        <button id="copy-btn" class="use-btn secondary">复制密码</button>
                    </div>
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
            
        // 密码生成器逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const lengthSlider = document.getElementById('length-slider');
            const lengthValue = document.getElementById('length-value');
            const lowercaseCheckbox = document.getElementById('lowercase');
            const uppercaseCheckbox = document.getElementById('uppercase');
            const numbersCheckbox = document.getElementById('numbers');
            const symbolsCheckbox = document.getElementById('symbols');
            const passwordResult = document.getElementById('password-result');
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            const generateBtn = document.getElementById('generate-btn');
            const copyBtn = document.getElementById('copy-btn');
            
            // 字符集
            const lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
            const uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const numberChars = '0123456789';
            const symbolChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';
            
            // 更新长度显示
            lengthSlider.addEventListener('input', function() {
                lengthValue.textContent = this.value;
            });
            
            // 生成密码
            generateBtn.addEventListener('click', generatePassword);
            
            // 复制密码
            copyBtn.addEventListener('click', copyPassword);
            
            // 初始生成密码
            generatePassword();
            
            function generatePassword() {
                // 获取选项
                const length = parseInt(lengthSlider.value);
                const useLowercase = lowercaseCheckbox.checked;
                const useUppercase = uppercaseCheckbox.checked;
                const useNumbers = numbersCheckbox.checked;
                const useSymbols = symbolsCheckbox.checked;
                
                // 验证至少选择一种字符类型
                if (!useLowercase && !useUppercase && !useNumbers && !useSymbols) {
                    passwordResult.textContent = '请至少选择一种字符类型';
                    updateStrengthIndicator(0);
                    return;
                }
                
                // 构建字符集
                let charSet = '';
                if (useLowercase) charSet += lowercaseChars;
                if (useUppercase) charSet += uppercaseChars;
                if (useNumbers) charSet += numberChars;
                if (useSymbols) charSet += symbolChars;
                
                // 生成密码
                let password = '';
                for (let i = 0; i < length; i++) {
                    const randomIndex = Math.floor(Math.random() * charSet.length);
                    password += charSet[randomIndex];
                }
                
                // 显示密码
                passwordResult.textContent = password;
                
                // 计算密码强度
                const strength = calculatePasswordStrength(password);
                updateStrengthIndicator(strength);
            }
            
            function calculatePasswordStrength(password) {
                let strength = 0;
                
                // 长度评分
                strength += Math.min(password.length / 4, 5); // 每4个字符加1分，最多5分
                
                // 字符类型评分
                const hasLowercase = /[a-z]/.test(password);
                const hasUppercase = /[A-Z]/.test(password);
                const hasNumbers = /[0-9]/.test(password);
                const hasSymbols = /[^a-zA-Z0-9]/.test(password);
                
                const charTypes = [hasLowercase, hasUppercase, hasNumbers, hasSymbols].filter(Boolean).length;
                strength += (charTypes - 1) * 2; // 每种额外字符类型加2分
                
                // 返回0-10的强度值
                return Math.min(Math.round(strength), 10);
            }
            
            function updateStrengthIndicator(strength) {
                // 更新强度条
                const percentage = strength * 10;
                strengthBar.style.width = percentage + '%';
                
                // 更新颜色和文本
                if (strength <= 3) {
                    strengthBar.style.backgroundColor = '#dc3545';
                    strengthText.textContent = '密码强度: 弱';
                } else if (strength <= 6) {
                    strengthBar.style.backgroundColor = '#ffc107';
                    strengthText.textContent = '密码强度: 中等';
                } else if (strength <= 8) {
                    strengthBar.style.backgroundColor = '#28a745';
                    strengthText.textContent = '密码强度: 强';
                } else {
                    strengthBar.style.backgroundColor = '#007bff';
                    strengthText.textContent = '密码强度: 非常强';
                }
            }
            
            function copyPassword() {
                const password = passwordResult.textContent;
                if (!password || password === '点击"生成密码"按钮') {
                    return;
                }
                
                navigator.clipboard.writeText(password).then(function() {
                    const originalText = copyBtn.textContent;
                    copyBtn.textContent = '已复制!';
                    setTimeout(function() {
                        copyBtn.textContent = originalText;
                    }, 2000);
                });
            }
        });
    </script>
</body>
</html>
