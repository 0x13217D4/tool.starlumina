<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文本加密/解密工具 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/crypto-js@4.1.1/crypto-js.min.js"></script>
    <style>
        .encryptor-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .encryptor-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .text-area {
            flex: 1;
            min-width: 300px;
        }
        
        textarea {
            width: 100%;
            height: 200px;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: monospace;
            font-size: 0.95rem;
            resize: vertical;
        }
        
        .encryptor-options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .option-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .option-item {
            flex: 1;
            min-width: 150px;
        }
        
        select, input[type="password"], input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        
        .encryptor-buttons {
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
        
        .error-message {
            color: #dc3545;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }
        
        .result-info {
            margin-top: 1rem;
            padding: 0.75rem;
            border-radius: 6px;
            background-color: #f8f9fa;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>文本加密/解密工具</h1>
            <div class="info-card">
                <div class="encryptor-controls">
                    <div class="encryptor-container">
                        <div class="text-area">
                            <label for="text-input">输入文本:</label>
                            <textarea id="text-input" placeholder="在此输入要加密/解密的文本..."></textarea>
                        </div>
                        
                        <div class="text-area">
                            <label for="text-output">结果文本:</label>
                            <textarea id="text-output" placeholder="加密/解密结果将显示在这里..." readonly></textarea>
                        </div>
                    </div>
                    
                    <div class="encryptor-options">
                        <div class="option-group">
                            <div class="option-item">
                                <label for="operation">操作:</label>
                                <select id="operation">
                                    <option value="encrypt">加密</option>
                                    <option value="decrypt">解密</option>
                                </select>
                            </div>
                            
                            <div class="option-item">
                                <label for="algorithm">加密算法:</label>
                                <select id="algorithm">
                                    <option value="AES">AES</option>
                                    <option value="DES">DES</option>
                                    <option value="TripleDES">TripleDES</option>
                                    <option value="Rabbit">Rabbit</option>
                                    <option value="RC4">RC4</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="option-group">
                            <div class="option-item">
                                <label for="password">密码:</label>
                                <input type="password" id="password" placeholder="输入加密/解密密码">
                            </div>
                            
                            <div class="option-item">
                                <label for="salt">盐值 (可选):</label>
                                <input type="text" id="salt" placeholder="输入盐值 (增强安全性)">
                            </div>
                        </div>
                    </div>
                    
                    <div class="result-info" id="result-info" style="display:none;"></div>
                    
                    <div class="encryptor-buttons">
                        <button id="process-btn" class="use-btn">执行</button>
                        <button id="copy-btn" class="use-btn secondary">复制结果</button>
                        <button id="clear-btn" class="use-btn secondary">清空</button>
                        <button id="example-btn" class="use-btn secondary">示例</button>
                    </div>
                    
                    <div id="error-message" class="error-message" style="display:none;"></div>
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
            
        // 加密/解密逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const textInput = document.getElementById('text-input');
            const textOutput = document.getElementById('text-output');
            const operation = document.getElementById('operation');
            const algorithm = document.getElementById('algorithm');
            const password = document.getElementById('password');
            const salt = document.getElementById('salt');
            const processBtn = document.getElementById('process-btn');
            const copyBtn = document.getElementById('copy-btn');
            const clearBtn = document.getElementById('clear-btn');
            const exampleBtn = document.getElementById('example-btn');
            const errorMessage = document.getElementById('error-message');
            const resultInfo = document.getElementById('result-info');
            
            // 初始化
            loadExample();
            
            // 按钮事件
            processBtn.addEventListener('click', processText);
            copyBtn.addEventListener('click', copyResult);
            clearBtn.addEventListener('click', clearText);
            exampleBtn.addEventListener('click', loadExample);
            
            // 处理文本
            function processText() {
                try {
                    console.clear();
                    console.log('开始处理文本...');
                    
                    const inputText = textInput.value;
                    const op = operation.value;
                    const algo = algorithm.value;
                    const pwd = password.value;
                    const saltValue = salt.value;
                    
                    console.log('输入参数:', {op, algo, inputText, pwd, saltValue});
                    
                    if (!inputText || !inputText.trim()) {
                        throw new Error('请输入有效的处理文本');
                    }
                    
                    if (!pwd || !pwd.trim()) {
                        throw new Error('请输入有效的密码');
                    }
                    
                    if (op === 'decrypt' && !/^[a-zA-Z0-9+/=]+$/.test(inputText)) {
                        throw new Error('解密文本格式无效，请检查输入是否为加密后的文本');
                    }
                    
                    let result;
                    let infoText;
                    
                    if (op === 'encrypt') {
                        console.log('开始加密过程...');
                        result = encryptText(inputText, algo, pwd, saltValue);
                        infoText = `使用${algo}算法加密成功 | 盐值: ${saltValue || '无'}`;
                        console.log('加密完成:', result);
                    } else {
                        console.log('开始解密过程...');
                        result = decryptText(inputText, algo, pwd, saltValue);
                        infoText = `使用${algo}算法解密成功 | 盐值: ${saltValue || '无'}`;
                        console.log('解密完成:', result);
                    }
                    
                    textOutput.value = result;
                    resultInfo.textContent = infoText;
                    resultInfo.style.display = 'block';
                    resultInfo.style.backgroundColor = '#d4edda';
                    resultInfo.style.color = '#155724';
                    
                    hideError();
                } catch (error) {
                    console.error('处理过程中出错:', error);
                    showError(error.message);
                    resultInfo.textContent = `处理失败: ${error.message}`;
                    resultInfo.style.display = 'block';
                    resultInfo.style.backgroundColor = '#f8d7da';
                    resultInfo.style.color = '#721c24';
                }
            }
            
            // 加密文本
            function encryptText(text, algorithm, password, salt) {
                try {
                    // 检查CryptoJS是否加载
                    if (typeof CryptoJS === 'undefined') {
                        throw new Error('加密库未正确加载，请刷新页面重试');
                    }

                    if (!text || !text.trim()) {
                        throw new Error('加密文本不能为空');
                    }

                    if (!password || !password.trim()) {
                        throw new Error('加密密码不能为空');
                    }

                    let encrypted;
                    let key;
                    
                    try {
                        console.log('开始生成密钥...', {algorithm, password, salt});
                        if (salt && salt.trim()) {
                            key = CryptoJS.PBKDF2(password, salt.trim(), { 
                                keySize: getKeySize(algorithm),
                                iterations: 1000 
                            });
                            console.log('使用PBKDF2生成的密钥:', key.toString());
                        } else {
                            key = CryptoJS.enc.Utf8.parse(password.trim());
                            console.log('使用密码直接生成的密钥:', key.toString());
                        }
                    } catch (e) {
                        console.error('密钥生成错误:', e);
                        throw new Error(`密钥生成失败: ${e.message}`);
                    }
                    
                    if (!key || key.sigBytes <= 0) {
                        console.error('无效的密钥:', key);
                        throw new Error('生成的密钥无效');
                    }

                    console.log('开始加密...', {algorithm, key});
                    try {
                        switch(algorithm) {
                            case 'AES':
                                encrypted = CryptoJS.AES.encrypt(
                                    CryptoJS.enc.Utf8.parse(text),
                                    key, 
                                    { 
                                        mode: CryptoJS.mode.CBC,
                                        padding: CryptoJS.pad.Pkcs7,
                                        iv: CryptoJS.enc.Hex.parse('00000000000000000000000000000000')
                                    }
                                );
                                break;
                            case 'DES':
                                encrypted = CryptoJS.DES.encrypt(
                                    CryptoJS.enc.Utf8.parse(text),
                                    key, 
                                    { 
                                        mode: CryptoJS.mode.CBC,
                                        padding: CryptoJS.pad.Pkcs7,
                                        iv: CryptoJS.enc.Hex.parse('0000000000000000')
                                    }
                                );
                                break;
                            case 'TripleDES':
                                encrypted = CryptoJS.TripleDES.encrypt(
                                    CryptoJS.enc.Utf8.parse(text),
                                    key, 
                                    { 
                                        mode: CryptoJS.mode.CBC,
                                        padding: CryptoJS.pad.Pkcs7,
                                        iv: CryptoJS.enc.Hex.parse('0000000000000000')
                                    }
                                );
                                break;
                            case 'Rabbit':
                                encrypted = CryptoJS.Rabbit.encrypt(
                                    CryptoJS.enc.Utf8.parse(text),
                                    key
                                );
                                break;
                            case 'RC4':
                                encrypted = CryptoJS.RC4.encrypt(
                                    CryptoJS.enc.Utf8.parse(text),
                                    key
                                );
                                break;
                            default:
                                throw new Error('不支持的加密算法');
                        }
                    } catch (e) {
                        console.error('加密过程错误:', e);
                        throw new Error(`加密过程出错: ${e.message}`);
                    }
                    
                    if (!encrypted) {
                        throw new Error('加密过程未返回有效结果');
                    }
                    
                    const result = encrypted.toString();
                    console.log('加密完成，结果:', result);
                    return result;
                } catch (error) {
                    console.error('加密失败:', error);
                    throw new Error(`加密失败: ${error.message}`);
                }
            }

            // 获取密钥大小
            function getKeySize(algorithm) {
                switch(algorithm) {
                    case 'AES': return 256/32;
                    case 'DES': return 64/32;
                    case 'TripleDES': return 192/32;
                    case 'Rabbit': return 128/32;
                    case 'RC4': return 256/32;
                    default: return 256/32;
                }
            }
            
            // 解密文本
            function decryptText(text, algorithm, password, salt) {
                try {
                    if (!text || !password) {
                        throw new Error('文本和密码不能为空');
                    }

                    let decrypted;
                    let key;
                    
                    try {
                        key = salt ? 
                            CryptoJS.PBKDF2(password, salt, { keySize: algorithm === 'AES' ? 256/32 : 64/32, iterations: 1000 }) : 
                            CryptoJS.enc.Utf8.parse(password);
                    } catch (e) {
                        throw new Error('密钥生成失败: ' + e.message);
                    }
                    
                    if (!key) {
                        throw new Error('无法生成有效密钥');
                    }

                    switch(algorithm) {
                        case 'AES':
                            decrypted = CryptoJS.AES.decrypt(text, key, { 
                                mode: CryptoJS.mode.CBC,
                                padding: CryptoJS.pad.Pkcs7
                            });
                            break;
                        case 'DES':
                            decrypted = CryptoJS.DES.decrypt(text, key, { 
                                mode: CryptoJS.mode.CBC,
                                padding: CryptoJS.pad.Pkcs7
                            });
                            break;
                        case 'TripleDES':
                            decrypted = CryptoJS.TripleDES.decrypt(text, key, { 
                                mode: CryptoJS.mode.CBC,
                                padding: CryptoJS.pad.Pkcs7
                            });
                            break;
                        case 'Rabbit':
                            decrypted = CryptoJS.Rabbit.decrypt(text, key);
                            break;
                        case 'RC4':
                            decrypted = CryptoJS.RC4.decrypt(text, key);
                            break;
                        default:
                            throw new Error('不支持的加密算法');
                    }
                    
                    if (!decrypted) {
                        throw new Error('解密过程未返回结果');
                    }
                    
                    const result = decrypted.toString(CryptoJS.enc.Utf8);
                    
                    if (!result) {
                        throw new Error('解密结果为空，可能是密码或盐值错误');
                    }
                    
                    return result;
                } catch (error) {
                    throw new Error('解密失败: ' + error.message);
                }
            }
            
            // 复制结果
            function copyResult() {
                if (!textOutput.value) {
                    showError('没有可复制的内容');
                    return;
                }
                
                textOutput.select();
                document.execCommand('copy');
                
                resultInfo.textContent = '结果已复制到剪贴板';
                resultInfo.style.display = 'block';
                resultInfo.style.backgroundColor = '#cce5ff';
                resultInfo.style.color = '#004085';
                
                setTimeout(() => {
                    resultInfo.style.display = 'none';
                }, 2000);
            }
            
            // 清空文本
            function clearText() {
                textInput.value = '';
                textOutput.value = '';
                password.value = '';
                salt.value = '';
                resultInfo.style.display = 'none';
                hideError();
            }
            
            // 加载示例
            function loadExample() {
                if (operation.value === 'encrypt') {
                    textInput.value = '这是一段需要加密的敏感文本';
                    password.value = 'mySecretPassword';
                    salt.value = 'randomSalt123';
                } else {
                    textInput.value = 'U2FsdGVkX1+4O3LZx8vGNw6Q3qJjZJtD6vzW0R5L1nY=';
                    password.value = 'mySecretPassword';
                    salt.value = 'randomSalt123';
                }
                
                hideError();
            }
            
            // 显示错误信息
            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }
            
            // 隐藏错误信息
            function hideError() {
                errorMessage.style.display = 'none';
            }
        });
    </script>
</body>
</html>
