<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>身份证查询校验 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        /* 身份证验证器特定样式 */
        .id-validator {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .input-area {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        textarea {
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            min-height: 120px;
            resize: vertical;
            margin: 0;
        }
        
        textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .result-area {
            margin-top: 2rem;
        }
        
        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .result-table th, 
        .result-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .result-table th {
            background-color: #f8f9fa;
            font-weight: 500;
        }
        
        .result-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .valid {
            color: #4caf50;
        }
        
        .invalid {
            color: #f44336;
        }
        
        .info-text {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>身份证查询校验</h1>
            <div class="info-card">
                <div class="id-validator">
                    <div class="input-area">
                        <label for="id-input">请输入身份证号码（每行一个）：</label>
                        <textarea id="id-input" placeholder="请输入6位及以上身份证号码，输入越多信息越精准"></textarea>
                        <div class="info-text">提示：支持15位和18位身份证号码，输入6位可查询归属地，输入完整号码可验证合法性</div>
                        <div class="info-text">本工具无需传输数据到服务器，完全由用户浏览器本地实现，因此无需担心泄密等安全问题。</div>
                        <div class="btn-group">
                            <button id="validate-btn">查询校验</button>
                            <button id="clear-btn">清空</button>
                        </div>
                    </div>
                    
                    <div class="result-area">
                        <h3>查询结果</h3>
                        <div id="result-container"></div>
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
            
        // 身份证验证逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const idInput = document.getElementById('id-input');
            const validateBtn = document.getElementById('validate-btn');
            const clearBtn = document.getElementById('clear-btn');
            const resultContainer = document.getElementById('result-container');
            
            // 加载行政区划代码数据
            let areaCodes = {};
            let dataLoaded = false;
            
            // 创建加载状态提示元素
            const loadingInfo = document.createElement('div');
            loadingInfo.className = 'info-text loading';
            loadingInfo.textContent = '正在加载行政区划数据...';
            document.querySelector('.input-area').appendChild(loadingInfo);
            
            // 添加加载状态样式
            const style = document.createElement('style');
            style.textContent = `
                .info-text.loading { color: #3498db; }
                .info-text.success { color: #4caf50; }
                .info-text.warning { color: #ff9800; }
            `;
            document.head.appendChild(style);
            
            // 使用fetch加载外部JSON文件
            fetch('../json/area-codes.json')
                .then(response => {
                    if (!response.ok) throw new Error('网络响应不正常');
                    return response.json();
                })
                .then(data => {
                    areaCodes = data;
                    // 添加获取完整归属地信息的方法
                    areaCodes.getFullArea = function(code) {
                        if (!code || code.length < 2) return '未知地区';
                        
                        const provinceCode = code.substring(0, 2) + '0000';
                        const cityCode = code.substring(0, 4) + '00';
                        
                        const province = this[provinceCode.substring(0, 2)] || '';
                        const city = this[cityCode] || '';
                        const district = this[code] || '';
                        
                        // 处理直辖市的情况
                        if (province === city) {
                            return `${province} ${district}`;
                        }
                        return `${province} ${city} ${district}`.trim();
                    };
                    dataLoaded = true;
                    loadingInfo.textContent = '行政区划数据加载完成！';
                    loadingInfo.className = 'info-text success';
                })
                .catch(error => {
                    console.error('加载行政区划代码失败:', error);
                    // 默认保留基本数据
                    areaCodes = {
                        '11': '北京市',
                        '12': '天津市',
                        '13': '河北省',
                        getFullArea: function(code) {
                            return this[code] || '未知地区';
                        }
                    };
                    loadingInfo.textContent = '行政区划数据加载失败，使用基础数据';
                    loadingInfo.className = 'info-text warning';
                });
            
            // 查询按钮事件
            validateBtn.addEventListener('click', validateIDs);
            
            // 清空按钮事件
            clearBtn.addEventListener('click', clearResults);
            
            function validateIDs() {
                const ids = idInput.value.trim().split('\n')
                    .map(id => id.trim())
                    .filter(id => id.length >= 6);
                
                if (ids.length === 0) {
                    resultContainer.innerHTML = '<div class="message warning">请输入至少一个6位及以上身份证号码</div>';
                    return;
                }
                
                let resultsHtml = `
                    <table class="result-table">
                        <thead>
                            <tr>
                                <th>身份证号</th>
                                <th>归属地</th>
                                <th>出生日期</th>
                                <th>性别</th>
                                <th>校验结果</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                ids.forEach(id => {
                    const result = validateID(id);
                    resultsHtml += `
                        <tr>
                            <td>${maskID(id)}</td>
                            <td>${result.area || '-'}</td>
                            <td>${result.birthday || '-'}</td>
                            <td>${result.gender || '-'}</td>
                            <td class="${result.isValid ? 'valid' : 'invalid'}">
                                ${result.isValid ? '有效' : '无效'}
                                ${result.message ? `<div class="info-text">${result.message}</div>` : ''}
                            </td>
                        </tr>
                    `;
                });
                
                resultsHtml += `
                        </tbody>
                    </table>
                `;
                
                resultContainer.innerHTML = resultsHtml;
            }
            
            function validateID(id) {
                const result = {
                    area: null,
                    birthday: null,
                    gender: null,
                    isValid: false,
                    message: ''
                };
                
                // 提取行政区划代码并获取完整归属地信息
                const areaCode = id.substring(0, 6);
                if (!dataLoaded) {
                    result.message = '行政区划数据正在加载中，部分功能可能受限';
                }
                result.area = areaCodes.getFullArea(areaCode);
                
                // 仅6位数字时只返回归属地信息
                if (id.length === 6) {
                    return result;
                }
                
                // 验证长度
                if (id.length !== 15 && id.length !== 18) {
                    result.message = '身份证号码长度应为6位、15位或18位';
                    return result;
                }
                
                // 如果是18位身份证
                if (id.length === 18) {
                    // 验证出生日期
                    const year = id.substring(6, 10);
                    const month = id.substring(10, 12);
                    const day = id.substring(12, 14);
                    
                    if (!isValidDate(year, month, day)) {
                        result.message = '出生日期无效';
                        return result;
                    }
                    
                    result.birthday = `${year}-${month}-${day}`;
                    
                    // 验证校验码
                    if (!validateCheckCode(id)) {
                        result.message = '校验码错误';
                        return result;
                    }
                    
                    // 提取性别
                    const genderCode = parseInt(id.substring(16, 17));
                    result.gender = genderCode % 2 === 1 ? '男' : '女';
                    result.isValid = true;
                }
                // 如果是15位身份证
                else if (id.length === 15) {
                    // 提取出生日期（15位身份证年份是2位，需要补全）
                    const year = '19' + id.substring(6, 8);
                    const month = id.substring(8, 10);
                    const day = id.substring(10, 12);
                    
                    if (!isValidDate(year, month, day)) {
                        result.message = '出生日期无效';
                        return result;
                    }
                    
                    result.birthday = `${year}-${month}-${day}`;
                    
                    // 提取性别
                    const genderCode = parseInt(id.substring(14, 15));
                    result.gender = genderCode % 2 === 1 ? '男' : '女';
                    result.isValid = true;
                }
                
                return result;
            }
            
            function isValidDate(year, month, day) {
                const date = new Date(`${year}-${month}-${day}`);
                return date && date.getMonth() + 1 === parseInt(month) && date.getDate() === parseInt(day);
            }
            
            function validateCheckCode(id) {
                // 18位身份证校验码验证算法
                const weights = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
                const checkCodes = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
                
                let sum = 0;
                for (let i = 0; i < 17; i++) {
                    sum += parseInt(id.charAt(i)) * weights[i];
                }
                
                const mod = sum % 11;
                return id.charAt(17).toUpperCase() === checkCodes[mod];
            }
            
            function maskID(id) {
                if (id.length <= 8) return id;
                return id.substring(0, 6) + '****' + id.substring(id.length - 4);
            }
            
            function clearResults() {
                idInput.value = '';
                resultContainer.innerHTML = '';
            }
        });
    </script>
</body>
</html>

