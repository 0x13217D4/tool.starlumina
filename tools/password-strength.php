<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>密码强度检测器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .password-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        input[type="password"] {
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            width: 100%;
        }
        
        input[type="password"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        
        .strength-meter {
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        
        .strength-progress {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }
        
        .strength-weak {
            background-color: #dc3545;
        }
        
        .strength-medium {
            background-color: #ffc107;
        }
        
        .strength-strong {
            background-color: #28a745;
        }
        
        .strength-very-strong {
            background-color: #007bff;
        }
        
        .strength-feedback {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .strength-criteria {
            margin-top: 1rem;
            padding-left: 1.5rem;
        }
        
        .strength-criteria li {
            margin-bottom: 0.5rem;
            list-style-type: none;
            position: relative;
        }
        
        .strength-criteria li:before {
            content: "✓";
            color: #28a745;
            position: absolute;
            left: -1.5rem;
        }
        
        .strength-criteria li.invalid:before {
            content: "✗";
            color: #dc3545;
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
        }
        
        .use-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>密码强度检测器</h1>
            <div class="info-card">
                <div class="password-controls">
                    <div class="control-group">
                        <label for="password-input">输入密码：</label>
                        <input type="password" id="password-input" placeholder="输入要检测的密码">
                        <div class="strength-meter">
                            <div class="strength-progress" id="strength-progress"></div>
                        </div>
                        <div class="strength-feedback" id="strength-feedback"></div>
                    </div>
                    
                    <div class="control-group">
                        <label>密码强度要求：</label>
                        <ul class="strength-criteria" id="strength-criteria">
                            <li class="invalid" id="criteria-length">至少8个字符</li>
                            <li class="invalid" id="criteria-lowercase">包含小写字母</li>
                            <li class="invalid" id="criteria-uppercase">包含大写字母</li>
                            <li class="invalid" id="criteria-number">包含数字</li>
                            <li class="invalid" id="criteria-special">包含特殊字符</li>
                        </ul>
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
            
        // 密码强度检测逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password-input');
            const strengthProgress = document.getElementById('strength-progress');
            const strengthFeedback = document.getElementById('strength-feedback');
            const checkBtn = document.getElementById('check-btn');
            
            // 实时检测密码强度
            passwordInput.addEventListener('input', checkPasswordStrength);
            
            function checkPasswordStrength() {
                const password = passwordInput.value;
                let strength = 0;
                let feedback = '';
                
                // 重置所有条件
                document.querySelectorAll('.strength-criteria li').forEach(li => {
                    li.classList.add('invalid');
                });
                
                // 检查密码长度
                if (password.length >= 8) {
                    strength += 20;
                    document.getElementById('criteria-length').classList.remove('invalid');
                }
                
                // 检查小写字母
                if (/[a-z]/.test(password)) {
                    strength += 20;
                    document.getElementById('criteria-lowercase').classList.remove('invalid');
                }
                
                // 检查大写字母
                if (/[A-Z]/.test(password)) {
                    strength += 20;
                    document.getElementById('criteria-uppercase').classList.remove('invalid');
                }
                
                // 检查数字
                if (/[0-9]/.test(password)) {
                    strength += 20;
                    document.getElementById('criteria-number').classList.remove('invalid');
                }
                
                // 检查特殊字符
                if (/[^a-zA-Z0-9]/.test(password)) {
                    strength += 20;
                    document.getElementById('criteria-special').classList.remove('invalid');
                }
                
                // 更新强度条
                strengthProgress.style.width = strength + '%';
                
                // 根据强度设置颜色和反馈
                if (strength <= 20) {
                    strengthProgress.className = 'strength-progress strength-weak';
                    feedback = '密码强度: 非常弱';
                } else if (strength <= 40) {
                    strengthProgress.className = 'strength-progress strength-weak';
                    feedback = '密码强度: 弱';
                } else if (strength <= 60) {
                    strengthProgress.className = 'strength-progress strength-medium';
                    feedback = '密码强度: 中等';
                } else if (strength <= 80) {
                    strengthProgress.className = 'strength-progress strength-strong';
                    feedback = '密码强度: 强';
                } else {
                    strengthProgress.className = 'strength-progress strength-very-strong';
                    feedback = '密码强度: 非常强';
                }
                
                strengthFeedback.textContent = feedback;
            }
        });
    </script>
</body>
</html>
