<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>图表生成器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-controls {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .chart-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .chart-area {
            flex: 1;
            min-width: 100%;
            height: 400px;
            position: relative;
        }
        
        .chart-icon-box {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
            z-index: 10;
        }
        
        .data-input {
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
        
        .chart-options {
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
        
        select, input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        
        .chart-buttons {
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
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>图表生成器</h1>
            <div class="info-card">
                <div class="chart-controls">
                    <div class="chart-container">
                        <div class="chart-area">
                            <div class="chart-icon-box">📊</div>
                            <canvas id="chartCanvas"></canvas>
                        </div>
                        
                        <div class="data-input">
                            <label for="data-input">输入数据 (JSON/CSV):</label>
                            <textarea id="data-input" placeholder='JSON格式示例: 
{
    "labels": ["一月", "二月", "三月"],
    "datasets": [{
        "label": "销售额",
        "data": [65, 59, 80]
    }]
}

CSV格式示例:
月份,销售额
一月,65
二月,59
三月,80'></textarea>
                        </div>
                    </div>
                    
                    <div class="chart-options">
                        <div class="option-group">
                            <div class="option-item">
                                <label for="chart-type">图表类型:</label>
                                <select id="chart-type">
                                    <option value="bar">柱状图</option>
                                    <option value="line">折线图</option>
                                    <option value="pie">饼图</option>
                                    <option value="doughnut">环形图</option>
                                    <option value="radar">雷达图</option>
                                    <option value="polarArea">极性区域图</option>
                                </select>
                            </div>
                            
                            <div class="option-item">
                                <label for="data-format">数据格式:</label>
                                <select id="data-format">
                                    <option value="json">JSON</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="option-group">
                            <div class="option-item">
                                <label for="chart-title">图表标题:</label>
                                <input type="text" id="chart-title" placeholder="输入图表标题">
                            </div>
                            
                            <div class="option-item">
                                <label for="color-scheme">配色方案:</label>
                                <select id="color-scheme">
                                    <option value="default">默认</option>
                                    <option value="rainbow">彩虹</option>
                                    <option value="pastel">柔和</option>
                                    <option value="warm">暖色</option>
                                    <option value="cool">冷色</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chart-buttons">
                        <button id="generate-btn" class="use-btn">生成图表</button>
                        <button id="download-btn" class="use-btn secondary">下载图表</button>
                        <button id="clear-btn" class="use-btn secondary">清空数据</button>
                        <button id="example-btn" class="use-btn secondary">示例数据</button>
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
            
        // 图表生成器逻辑
        document.addEventListener('DOMContentLoaded', function() {
            const chartCanvas = document.getElementById('chartCanvas');
            const dataInput = document.getElementById('data-input');
            const chartTypeSelect = document.getElementById('chart-type');
            const dataFormatSelect = document.getElementById('data-format');
            const chartTitleInput = document.getElementById('chart-title');
            const colorSchemeSelect = document.getElementById('color-scheme');
            const generateBtn = document.getElementById('generate-btn');
            const downloadBtn = document.getElementById('download-btn');
            const clearBtn = document.getElementById('clear-btn');
            const exampleBtn = document.getElementById('example-btn');
            const errorMessage = document.getElementById('error-message');
            
            let chart = null;
            
            // 初始化
            loadExampleData();
            
            // 按钮事件
            generateBtn.addEventListener('click', generateChart);
            downloadBtn.addEventListener('click', downloadChart);
            clearBtn.addEventListener('click', clearData);
            exampleBtn.addEventListener('click', loadExampleData);
            
            // 生成图表
            function generateChart() {
                try {
                    const dataFormat = dataFormatSelect.value;
                    let chartData;
                    
                    if (dataFormat === 'json') {
                        chartData = parseJsonData(dataInput.value);
                    } else {
                        chartData = parseCsvData(dataInput.value);
                    }
                    
                    if (!chartData || !chartData.labels || !chartData.datasets) {
                        throw new Error('无效的图表数据格式');
                    }
                    
                    // 应用颜色方案
                    applyColorScheme(chartData);
                    
                    // 创建或更新图表
                    const ctx = chartCanvas.getContext('2d');
                    const chartType = chartTypeSelect.value;
                    const chartTitle = chartTitleInput.value || '我的图表';
                    
                    if (chart) {
                        chart.destroy();
                    }
                    
                    chart = new Chart(ctx, {
                        type: chartType,
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: chartTitle,
                                    font: {
                                        size: 16
                                    }
                                },
                                legend: {
                                    position: 'top'
                                }
                            }
                        }
                    });
                    
                    hideError();
                } catch (error) {
                    showError(error.message);
                }
            }
            
            // 解析JSON数据
            function parseJsonData(jsonString) {
                try {
                    return JSON.parse(jsonString);
                } catch (error) {
                    throw new Error('无效的JSON格式: ' + error.message);
                }
            }
            
            // 解析CSV数据
            function parseCsvData(csvString) {
                try {
                    const lines = csvString.trim().split('\n');
                    if (lines.length < 2) {
                        throw new Error('CSV数据至少需要两行(标题行和数据行)');
                    }
                    
                    const headers = lines[0].split(',').map(h => h.trim());
                    if (headers.length < 2) {
                        throw new Error('CSV数据至少需要两列(标签列和数据列)');
                    }
                    
                    const labels = [];
                    const datasets = [];
                    
                    // 为每个数据列创建一个数据集
                    for (let i = 1; i < headers.length; i++) {
                        datasets.push({
                            label: headers[i],
                            data: []
                        });
                    }
                    
                    // 处理数据行
                    for (let j = 1; j < lines.length; j++) {
                        const values = lines[j].split(',');
                        if (values.length !== headers.length) {
                            throw new Error(`第${j+1}行列数与标题行不一致`);
                        }
                        
                        labels.push(values[0].trim());
                        
                        for (let k = 1; k < values.length; k++) {
                            const numValue = parseFloat(values[k]);
                            if (isNaN(numValue)) {
                                throw new Error(`第${j+1}行第${k+1}列不是有效数字`);
                            }
                            datasets[k-1].data.push(numValue);
                        }
                    }
                    
                    return {
                        labels: labels,
                        datasets: datasets
                    };
                } catch (error) {
                    throw new Error('CSV解析错误: ' + error.message);
                }
            }
            
            // 应用颜色方案
            function applyColorScheme(chartData) {
                const colorScheme = colorSchemeSelect.value;
                const colorSets = {
                    default: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    rainbow: [
                        'rgba(255, 0, 0, 0.7)',
                        'rgba(255, 127, 0, 0.7)',
                        'rgba(255, 255, 0, 0.7)',
                        'rgba(0, 255, 0, 0.7)',
                        'rgba(0, 0, 255, 0.7)',
                        'rgba(75, 0, 130, 0.7)',
                        'rgba(148, 0, 211, 0.7)'
                    ],
                    pastel: [
                        'rgba(255, 179, 186, 0.7)',
                        'rgba(255, 223, 186, 0.7)',
                        'rgba(255, 255, 186, 0.7)',
                        'rgba(186, 255, 201, 0.7)',
                        'rgba(186, 225, 255, 0.7)',
                        'rgba(186, 186, 255, 0.7)'
                    ],
                    warm: [
                        'rgba(255, 100, 100, 0.7)',
                        'rgba(255, 150, 50, 0.7)',
                        'rgba(255, 200, 0, 0.7)',
                        'rgba(255, 220, 100, 0.7)',
                        'rgba(255, 180, 150, 0.7)'
                    ],
                    cool: [
                        'rgba(100, 100, 255, 0.7)',
                        'rgba(100, 200, 255, 0.7)',
                        'rgba(50, 150, 200, 0.7)',
                        'rgba(150, 220, 255, 0.7)',
                        'rgba(180, 150, 255, 0.7)'
                    ]
                };
                
                const colors = colorSets[colorScheme] || colorSets.default;
                
                chartData.datasets.forEach((dataset, index) => {
                    if (chartTypeSelect.value === 'pie' || 
                        chartTypeSelect.value === 'doughnut' || 
                        chartTypeSelect.value === 'polarArea') {
                        // 对于饼图等，每个数据点需要不同颜色
                        dataset.backgroundColor = colors.slice(0, chartData.labels.length);
                    } else {
                        // 对于柱状图等，每个数据集使用单一颜色
                        dataset.backgroundColor = colors[index % colors.length];
                    }
                    
                    // 添加边框颜色
                    dataset.borderColor = dataset.backgroundColor.replace('0.7', '1');
                    dataset.borderWidth = 1;
                });
            }
            
            // 下载图表
            function downloadChart() {
                if (!chart) {
                    showError('请先生成图表');
                    return;
                }
                
                const link = document.createElement('a');
                link.download = 'chart.png';
                link.href = chartCanvas.toDataURL('image/png');
                link.click();
            }
            
            // 清空数据
            function clearData() {
                dataInput.value = '';
                chartTitleInput.value = '';
                
                if (chart) {
                    chart.destroy();
                    chart = null;
                }
                
                hideError();
            }
            
            // 加载示例数据
            function loadExampleData() {
                dataInput.value = `{
    "labels": ["一月", "二月", "三月", "四月", "五月", "六月"],
    "datasets": [
        {
            "label": "销售额",
            "data": [65, 59, 80, 81, 56, 55]
        },
        {
            "label": "利润",
            "data": [28, 48, 40, 19, 86, 27]
        }
    ]
}`;
                chartTitleInput.value = '上半年销售数据';
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
