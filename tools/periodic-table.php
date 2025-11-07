<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>元素周期表 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .periodic-table {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }
        
        #periodic-table {
            border-collapse: collapse;
            margin: 0 auto;
        }
        
        #periodic-table {
            position: relative;
        }
        
        #periodic-table td {
            min-width: 40px;
            max-width: 60px;
            height: 55px; /* 增加高度以适应双行文本 */
            padding: 2px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9rem;
            word-break: break-word;
            position: relative;
            line-height: 1.2;
        }
        
        /* 周期标注(行号) */
        .period-label {
            position: sticky;
            left: 0;
            min-width: 25px;
            text-align: right;
            font-weight: bold;
            color: #666;
            background-color: white;
            z-index: 2;
            padding-right: 5px;
        }
        
        /* 族标注(列号) */
        .group-label {
            position: sticky;
            top: 0;
            height: 25px;
            text-align: center;
            font-weight: bold;
            color: #666;
            background-color: white;
            z-index: 2;
            padding-bottom: 5px;
        }
        
        /* 镧系/锕系标注 - 新样式 */
        .series-label-new {
            position: relative;
            left: 0;
            width: 60px;
            text-align: center;
            font-weight: bold;
            padding: 2px 5px;
            font-size: 1.1rem;
            color: #8b0000;
            border-right: 2px solid;
            background-color: #fff;
            z-index: 2;
        }
        
        /* 镧系行背景 */
        #periodic-table tr:nth-child(9) {
            background-color: #fff0f5;
        }
        
        /* 锕系行背景 */
        #periodic-table tr:nth-child(10) {
            background-color: #f5f0ff;
        }
        
        /* 元素类别颜色区分 */
        #periodic-table td[data-category="alkali-metal"] { background-color: #ff9999; }
        #periodic-table td[data-category="alkaline-earth"] { background-color: #ffcc99; }
        #periodic-table td[data-category="transition-metal"] { background-color: #ffeb99; }
        #periodic-table td[data-category="post-transition"] { background-color: #ccff99; }
        #periodic-table td[data-category="metalloid"] { background-color: #99ffcc; }
        #periodic-table td[data-category="nonmetal"] { background-color: #99ccff; }
        #periodic-table td[data-category="halogen"] { background-color: #cc99ff; }
        #periodic-table td[data-category="noble-gas"] { background-color: #ff99ff; }
        #periodic-table td[data-category="lanthanide"] { 
            background-color: #ffb6c1;
            font-weight: bold;
        }
        #periodic-table td[data-category="actinide"] { 
            background-color: #d8bfd8;
            font-weight: bold;
        }
        
        #periodic-table td:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            z-index: 1;
        }
        
        .element-details {
            display: none;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 1.5rem;
            background-color: #f8f9fa;
            margin-top: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .periodic-table {
                padding-left: 30px;
                padding-top: 30px;
            }
            
            #periodic-table td {
                min-width: 28px;
                height: 38px;
                font-size: 0.75rem;
                padding: 1px;
            }
            
            #periodic-table td span {
                font-size: 0.6rem;
            }
            
            .period-label {
                left: 0;
                min-width: 20px;
            }
            
            .group-label {
                top: 0;
                height: 20px;
                font-size: 0.8rem;
            }
            
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label {
                min-width: auto;
                margin-bottom: 0.2rem;
            }
        }
        
        .detail-section {
            margin-bottom: 1.5rem;
        }
        
        .detail-section h3 {
            margin-bottom: 0.75rem;
            color: #3498db;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 0.5rem;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 0.5rem;
        }
        
        .detail-label {
            font-weight: bold;
            min-width: 120px;
            color: #666;
        }
        
        .detail-value {
            flex: 1;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
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
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>元素周期表</h1>
            <div class="info-card">
                <div class="periodic-table">
                    <div class="table-container">
                        <table id="periodic-table">
                            <!-- 元素周期表将通过JavaScript动态生成 -->
                        </table>
                    </div>
                    
                    <div id="element-details" class="element-details">
                        <h2 id="element-name">元素名称</h2>
                        
                        <div class="detail-section">
                            <h3>基本信息</h3>
                            <div class="detail-row">
                                <div class="detail-label">符号：</div>
                                <div id="element-symbol" class="detail-value"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">英文名称：</div>
                                <div id="element-english" class="detail-value"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">相对原子质量：</div>
                                <div id="element-atomic-mass" class="detail-value"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">原子序数：</div>
                                <div id="element-atomic-number" class="detail-value"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">电子构型：</div>
                                <div id="element-electron-config" class="detail-value"></div>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <h3>发现与用途</h3>
                            <div class="detail-row">
                                <div class="detail-label">发现：</div>
                                <div id="element-discovery" class="detail-value"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">来源：</div>
                                <div id="element-source" class="detail-value"></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">用途：</div>
                                <div id="element-uses" class="detail-value"></div>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <h3>物理性质</h3>
                            <div id="element-physical-properties" class="detail-value"></div>
                        </div>
                        
                        <div class="detail-section">
                            <h3>化学性质</h3>
                            <div id="element-chemical-properties" class="detail-value"></div>
                        </div>
                        
                        <div class="detail-section">
                            <h3>地质数据</h3>
                            <div id="element-geological-data" class="detail-value"></div>
                        </div>
                        
                        <div class="detail-section">
                            <h3>生物数据</h3>
                            <div id="element-biological-data" class="detail-value"></div>
                        </div>
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
            
        // 元素数据将通过fetch从JSON文件加载
        let elements = [];
        
        // 加载元素数据
        fetch('../json/elements.json')
            .then(response => response.json())
            .then(data => {
                elements = data;
                initPeriodicTable();
            })
            .catch(error => {
                console.error('加载元素数据失败:', error);
                document.getElementById('element-details').innerHTML = 
                    '<p style="color:red">加载元素数据失败，请刷新页面重试</p>';
            });
            
        // 获取元素类别 (更新以匹配新布局)
        function getElementCategory(atomicNumber) {
            // 碱金属 (第1族)
            if ([3, 11, 19, 37, 55, 87].includes(atomicNumber)) return 'alkali-metal';
            // 碱土金属 (第2族)
            if ([4, 12, 20, 38, 56, 88].includes(atomicNumber)) return 'alkaline-earth';
            // 过渡金属 (3-12族)
            if ((atomicNumber >= 21 && atomicNumber <= 30) || 
                (atomicNumber >= 39 && atomicNumber <= 48) || 
                (atomicNumber >= 72 && atomicNumber <= 80) || 
                (atomicNumber >= 104 && atomicNumber <= 112)) return 'transition-metal';
            // 镧系 (57-71)
            if (atomicNumber >= 57 && atomicNumber <= 71) return 'lanthanide';
            // 锕系 (89-103)
            if (atomicNumber >= 89 && atomicNumber <= 103) return 'actinide';
            // 主族金属 (13-16族部分)
            if ([13, 31, 49, 50, 81, 82, 83].includes(atomicNumber)) return 'post-transition';
            // 类金属 (13-16族部分)
            if ([5, 14, 32, 33, 51, 52].includes(atomicNumber)) return 'metalloid';
            // 非金属 (13-16族部分)
            if ([1, 6, 7, 8, 15, 16, 34].includes(atomicNumber)) return 'nonmetal';
            // 卤素 (第17族)
            if ([9, 17, 35, 53, 85, 117].includes(atomicNumber)) return 'halogen';
            // 惰性气体 (第18族)
            if ([2, 10, 18, 36, 54, 86, 118].includes(atomicNumber)) return 'noble-gas';
            return '';
        }

        function initPeriodicTable() {
            const table = document.getElementById('periodic-table');
            const detailsPanel = document.getElementById('element-details');
            
            // 确保详情面板初始状态正确
            detailsPanel.style.display = 'none';
            
            // 添加关闭按钮
            const closeBtn = document.createElement('button');
            closeBtn.textContent = '关闭';
            closeBtn.style.marginTop = '1rem';
            closeBtn.addEventListener('click', () => {
                detailsPanel.style.display = 'none';
            });
            // 避免重复添加关闭按钮
            if (!detailsPanel.querySelector('button')) {
                detailsPanel.appendChild(closeBtn);
            }
            
            // 标准周期表布局 (18列7行+镧系锕系)
            const layout = [
                // 主表部分 (7个周期)
                [1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2],  // 第1周期
                [3, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 6, 7, 8, 9, 10], // 第2周期
                [11, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 13, 14, 15, 16, 17, 18], // 第3周期
                [19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36], // 第4周期
                [37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54], // 第5周期
                [55, 56,'镧', 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86], // 第6周期
                [87, 88,'锕', 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118], // 第7周期
                
                // 镧系元素单独一行 (15个元素)
                [0, 0, 0, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71],
                
                // 锕系元素单独一行 (15个元素)
                [0, 0, 0, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103]
            ];
            
            // 添加族编号(按化学标准)
            const groupHeader = document.createElement('tr');
            groupHeader.innerHTML = '<td></td>'; // 空单元格用于对齐周期编号
            const groupNames = [
                'IA', 'IIA', 
                'IIIB', 'IVB', 'VB', 'VIB', 'VIIB', 
                'VIII', 'VIII', 'VIII', 
                'IB', 'IIB', 
                'IIIA', 'IVA', 'VA', 'VIA', 'VIIA', 'VIIIA'
            ];
            for (let i = 0; i < 18; i++) {
                const th = document.createElement('th');
                th.textContent = groupNames[i];
                th.className = 'group-label';
                groupHeader.appendChild(th);
            }
            table.appendChild(groupHeader);
            
            // 创建周期表
            layout.forEach((row, rowIndex) => {
                const tr = document.createElement('tr');
                
                // 添加行标签
                if (rowIndex === 7) { // 镧系行
                    const seriesTh = document.createElement('th');
                    seriesTh.textContent = '镧系';
                    seriesTh.className = 'series-label-new';
                    tr.appendChild(seriesTh);
                } else if (rowIndex === 8) { // 锕系行
                    const seriesTh = document.createElement('th');
                    seriesTh.textContent = '锕系';
                    seriesTh.className = 'series-label-new';
                    tr.appendChild(seriesTh);
                } else if (rowIndex < 7) { // 普通周期行
                    const periodTh = document.createElement('th');
                    periodTh.textContent = rowIndex + 1;
                    periodTh.className = 'period-label';
                    tr.appendChild(periodTh);
                } else {
                    const emptyTh = document.createElement('th');
                    emptyTh.innerHTML = '&nbsp;';
                    tr.appendChild(emptyTh);
                }
                
                row.forEach(cellContent => {
                    const td = document.createElement('td');
                    if (typeof cellContent === 'number') {
                        if (cellContent > 0) {
                            const element = elements.find(e => e.atomicNumber === cellContent);
                            if (element) {
                                td.innerHTML = `${element.symbol}<br><span style="font-size:0.7em">${element.name}</span>`;
                                td.title = `${element.name} (${element.symbol}) - 原子序数: ${element.atomicNumber}`;
                                td.dataset.atomicNumber = element.atomicNumber;
                                const category = getElementCategory(element.atomicNumber);
                                if (category) {
                                    td.dataset.category = category;
                                }
                                td.style.padding = '2px'; // 调整内边距以适应双行文本
                                td.style.lineHeight = '1.2'; // 调整行高
                                td.addEventListener('click', (e) => {
                                    e.stopPropagation(); // 防止事件冒泡
                                    showElementDetails(element);
                                    detailsPanel.style.display = 'block';
                                    // 滚动到详情面板
                                    setTimeout(() => {
                                        detailsPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                                    }, 100);
                                });
                            }
                        } else {
                            // 空白单元格
                            td.innerHTML = '&nbsp;';
                            td.style.backgroundColor = 'transparent';
                            td.style.border = 'none';
                        }
                    } else if (typeof cellContent === 'string') {
                        // 处理标注文本
                        td.textContent = cellContent;
                        td.style.fontWeight = 'bold';
                        td.style.fontSize = '1.1rem';
                        td.style.color = '#8b0000';
                        td.style.backgroundColor = '#fff';
                        td.style.border = '2px solid #8b0000';
                        td.style.borderRadius = '4px';
                        td.style.padding = '5px';
                        td.style.margin = '2px';
                        td.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
                    }
                    tr.appendChild(td);
                });
                table.appendChild(tr);
            });
        }
        
        // 显示元素详情
        function showElementDetails(element) {
            if (!element) return;
            
            try {
                document.getElementById('element-name').textContent = `${element.name} (${element.symbol})`;
                document.getElementById('element-symbol').textContent = element.symbol;
                document.getElementById('element-english').textContent = element.englishName || '暂无数据';
                document.getElementById('element-atomic-mass').textContent = element.atomicMass || '暂无数据';
                document.getElementById('element-atomic-number').textContent = element.atomicNumber || '暂无数据';
                document.getElementById('element-electron-config').textContent = element.electronConfig || '暂无数据';
                document.getElementById('element-discovery').textContent = element.discovery || '暂无数据';
                document.getElementById('element-source').textContent = element.source || '暂无数据';
                document.getElementById('element-uses').textContent = element.uses || '暂无数据';
                document.getElementById('element-physical-properties').textContent = element.physicalProperties || '暂无数据';
                document.getElementById('element-chemical-properties').textContent = element.chemicalProperties || '暂无数据';
                document.getElementById('element-geological-data').textContent = element.geologicalData || '暂无数据';
                document.getElementById('element-biological-data').textContent = element.biologicalData || '暂无数据';
            } catch (e) {
                console.error('显示元素详情时出错:', e);
            }
        }
    </script>
</body>
</html>

