<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>单位转换器 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .unit-converter {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .converter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .converter-row {
            display: flex;
            gap: 1.5rem;
        }
        
        .converter-row .converter-group {
            flex: 1;
        }
        
        input[type="number"],
        select {
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
        
        input[type="number"]:focus,
        select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 12px;
            padding-right: 2rem;
            cursor: pointer;
        }
        
        label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            display: block;
            font-size: 0.95rem;
        }
        
        .converter-group {
            margin-bottom: 1.25rem;
            padding: 0 0.75rem;
        }
        
        .result-display {
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
            margin: 1rem 0;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 6px;
        }
        
        .unit-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 0.5rem;
        }
        
        .unit-tab {
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .unit-tab.active {
            background-color: #3498db;
            color: white;
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>单位转换器</h1>
            <div class="info-card">
                <!-- 单位类型选项卡 -->
                <div class="unit-tabs">
                    <div class="unit-tab active" data-unit="length">长度</div>
                    <div class="unit-tab" data-unit="weight">重量</div>
                    <div class="unit-tab" data-unit="temperature">温度</div>
                    <div class="unit-tab" data-unit="power">功率</div>
                    <div class="unit-tab" data-unit="pressure">压力</div>
                    <div class="unit-tab" data-unit="volume">体积</div>
                    <div class="unit-tab" data-unit="area">面积</div>
                    <div class="unit-tab" data-unit="speed">速度</div>
                    <div class="unit-tab" data-unit="time">时间</div>
                    <div class="unit-tab" data-unit="data">数据存储</div>
                </div>
                
                <!-- 长度单位转换器 -->
                <div id="length-converter" class="unit-converter">
                    <div class="converter-row">
                        <div class="converter-group">
                            <label for="length-value">数值</label>
                            <input type="number" id="length-value" placeholder="输入数值">
                        </div>
                        <div class="converter-group">
                            <label for="length-from">从</label>
                            <select id="length-from">
                                <option value="mm">毫米(mm)</option>
                                <option value="cm">厘米(cm)</option>
                                <option value="m" selected>米(m)</option>
                                <option value="km">千米(km)</option>
                                <option value="in">英寸(in)</option>
                                <option value="ft">英尺(ft)</option>
                                <option value="yd">码(yd)</option>
                                <option value="mi">英里(mi)</option>
                            </select>
                        </div>
                        <div class="converter-group">
                            <label for="length-to">到</label>
                            <select id="length-to">
                                <option value="mm">毫米(mm)</option>
                                <option value="cm" selected>厘米(cm)</option>
                                <option value="m">米(m)</option>
                                <option value="km">千米(km)</option>
                                <option value="in">英寸(in)</option>
                                <option value="ft">英尺(ft)</option>
                                <option value="yd">码(yd)</option>
                                <option value="mi">英里(mi)</option>
                            </select>
                        </div>
                    </div>
                    <div class="result-display" id="length-result">0 cm</div>
                </div>
                
                <!-- 重量单位转换器 -->
                <div id="weight-converter" class="unit-converter" style="display:none;">
                    <div class="converter-row">
                        <div class="converter-group">
                            <label for="weight-value">数值</label>
                            <input type="number" id="weight-value" placeholder="输入数值">
                        </div>
                        <div class="converter-group">
                            <label for="weight-from">从</label>
                            <select id="weight-from">
                                <option value="mg">毫克(mg)</option>
                                <option value="g">克(g)</option>
                                <option value="kg" selected>千克(kg)</option>
                                <option value="t">吨(t)</option>
                                <option value="oz">盎司(oz)</option>
                                <option value="lb">磅(lb)</option>
                            </select>
                        </div>
                        <div class="converter-group">
                            <label for="weight-to">到</label>
                            <select id="weight-to">
                                <option value="mg">毫克(mg)</option>
                                <option value="g" selected>克(g)</option>
                                <option value="kg">千克(kg)</option>
                                <option value="t">吨(t)</option>
                                <option value="oz">盎司(oz)</option>
                                <option value="lb">磅(lb)</option>
                            </select>
                        </div>
                    </div>
                    <div class="result-display" id="weight-result">0 g</div>
                </div>
                
                <!-- 温度单位转换器 -->
                <div id="temperature-converter" class="unit-converter" style="display:none;">
                    <div class="converter-row">
                        <div class="converter-group">
                            <label for="temp-value">数值</label>
                            <input type="number" id="temp-value" placeholder="输入数值">
                        </div>
                        <div class="converter-group">
                            <label for="temp-from">从</label>
                            <select id="temp-from">
                                <option value="c" selected>摄氏度(°C)</option>
                                <option value="f">华氏度(°F)</option>
                                <option value="k">开尔文(K)</option>
                            </select>
                        </div>
                        <div class="converter-group">
                            <label for="temp-to">到</label>
                            <select id="temp-to">
                                <option value="c">摄氏度(°C)</option>
                                <option value="f" selected>华氏度(°F)</option>
                                <option value="k">开尔文(K)</option>
                            </select>
                        </div>
                    </div>
                    <div class="result-display" id="temp-result">0 °F</div>
                </div>
                
                <!-- 功率单位转换器 -->
                <div id="power-converter" class="unit-converter" style="display:none;">
                    <div class="converter-row">
                        <div class="converter-group">
                            <label for="power-value">数值</label>
                            <input type="number" id="power-value" placeholder="输入数值">
                        </div>
                        <div class="converter-group">
                            <label for="power-from">从</label>
                            <select id="power-from">
                                <option value="w" selected>瓦特(W)</option>
                                <option value="kw">千瓦(kW)</option>
                                <option value="hp">马力(hp)</option>
                                <option value="btu">BTU/h</option>
                            </select>
                        </div>
                        <div class="converter-group">
                            <label for="power-to">到</label>
                            <select id="power-to">
                                <option value="w">瓦特(W)</option>
                                <option value="kw" selected>千瓦(kW)</option>
                                <option value="hp">马力(hp)</option>
                                <option value="btu">BTU/h</option>
                            </select>
                        </div>
                    </div>
                    <div class="result-display" id="power-result">0 kW</div>
                </div>
                
                <!-- 压力单位转换器 -->
                <div id="pressure-converter" class="unit-converter" style="display:none;">
                    <div class="converter-row">
                        <div class="converter-group">
                            <label for="pressure-value">数值</label>
                            <input type="number" id="pressure-value" placeholder="输入数值">
                        </div>
                        <div class="converter-group">
                            <label for="pressure-from">从</label>
                            <select id="pressure-from">
                                <option value="pa" selected>帕斯卡(Pa)</option>
                                <option value="kpa">千帕(kPa)</option>
                                <option value="mpa">兆帕(MPa)</option>
                                <option value="bar">巴(bar)</option>
                                <option value="psi">磅/平方英寸(psi)</option>
                                <option value="atm">标准大气压(atm)</option>
                            </select>
                        </div>
                        <div class="converter-group">
                            <label for="pressure-to">到</label>
                            <select id="pressure-to">
                                <option value="pa">帕斯卡(Pa)</option>
                                <option value="kpa" selected>千帕(kPa)</option>
                                <option value="mpa">兆帕(MPa)</option>
                                <option value="bar">巴(bar)</option>
                                <option value="psi">磅/平方英寸(psi)</option>
                                <option value="atm">标准大气压(atm)</option>
                            </select>
                        </div>
                    </div>
                    <div class="result-display" id="pressure-result">0 kPa</div>
                </div>
                
                <!-- 体积单位转换器 -->
                <div id="volume-converter" class="unit-converter" style="display:none;">
                    <div class="converter-row">
                        <div class="converter-group">
                            <label for="volume-value">数值</label>
                            <input type="number" id="volume-value" placeholder="输入数值">
                        </div>
                        <div class="converter-group">
                            <label for="volume-from">从</label>
                            <select id="volume-from">
                                <option value="ml">毫升(ml)</option>
                                <option value="l" selected>升(l)</option>
                                <option value="m3">立方米(m³)</option>
                                <option value="cm3">立方厘米(cm³)</option>
                                <option value="mm3">立方毫米(mm³)</option>
                                <option value="gal">加仑(gal)</option>
                                <option value="qt">夸脱(qt)</option>
                                <option value="pt">品脱(pt)</option>
                            </select>
                        </div>
                        <div class="converter-group">
                            <label for="volume-to">到</label>
                            <select id="volume-to">
                                <option value="ml">毫升(ml)</option>
                                <option value="l" selected>升(l)</option>
                                <option value="m3">立方米(m³)</option>
                                <option value="cm3">立方厘米(cm³)</option>
                                <option value="mm3">立方毫米(mm³)</option>
                                <option value="gal">加仑(gal)</option>
                                <option value="qt">夸脱(qt)</option>
                                <option value="pt">品脱(pt)</option>
                            </select>
                        </div>
                    </div>
                    <div class="result-display" id="volume-result">0 l</div>
                </div>
                
                <!-- 面积单位转换器 -->
                <div id="area-converter" class="unit-converter" style="display:none;">
                    <div class="converter-row">
                        <div class="converter-group">
                            <label for="area-value">数值</label>
                            <input type="number" id="area-value" placeholder="输入数值">
                        </div>
                        <div class="converter-group">
                            <label for="area-from">从</label>
                            <select id="area-from">
                                <option value="mm2">平方毫米(mm²)</option>
                                <option value="cm2">平方厘米(cm²)</option>
                                <option value="m2" selected>平方米(m²)</option>
                                <option value="km2">平方千米(km²)</option>
                                <option value="ha">公顷(ha)</option>
                                <option value="in2">平方英寸(in²)</option>
                                <option value="ft2">平方英尺(ft²)</option>
                                <option value="yd2">平方码(yd²)</option>
                                <option value="acre">英亩(acre)</option>
                            </select>
                        </div>
                        <div class="converter-group">
                            <label for="area-to">到</label>
                            <select id="area-to">
                                <option value="mm2">平方毫米(mm²)</option>
                                <option value="cm2" selected>平方厘米(cm²)</option>
                                <option value="m2">平方米(m²)</option>
                                <option value="km2">平方千米(km²)</option>
                                <option value="ha">公顷(ha)</option>
                                <option value="in2">平方英寸(in²)</option>
                                <option value="ft2">平方英尺(ft²)</option>
                                <option value="yd2">平方码(yd²)</option>
                                <option value="acre">英亩(acre)</option>
                            </select>
                        </div>
                    </div>
                    <div class="result-display" id="area-result">0 cm²</div>
                </div>
                
                <!-- 速度单位转换器 -->
                <div id="speed-converter" class="unit-converter" style="display:none;">
                    <div class="converter-row">
                        <div class="converter-group">
                            <label for="speed-value">数值</label>
                            <input type="number" id="speed-value" placeholder="输入数值">
                        </div>
                        <div class="converter-group">
                            <label for="speed-from">从</label>
                            <select id="speed-from">
                                <option value="ms" selected>米/秒(m/s)</option>
                                <option value="kmh">千米/时(km/h)</option>
                                <option value="mph">英里/时(mph)</option>
                                <option value="knot">节(knot)</option>
                                <option value="fts">英尺/秒(ft/s)</option>
                                <option value="mach">马赫(Mach)</option>
                            </select>
                        </div>
                        <div class="converter-group">
                            <label for="speed-to">到</label>
                            <select id="speed-to">
                                <option value="ms">米/秒(m/s)</option>
                                <option value="kmh" selected>千米/时(km/h)</option>
                                <option value="mph">英里/时(mph)</option>
                                <option value="knot">节(knot)</option>
                                <option value="fts">英尺/秒(ft/s)</option>
                                <option value="mach">马赫(Mach)</option>
                            </select>
                        </div>
                    </div>
                    <div class="result-display" id="speed-result">0 km/h</div>
                </div>
                
                <!-- 时间单位转换器 -->
                <div id="time-converter" class="unit-converter" style="display:none;">
                    <div class="converter-row">
                        <div class="converter-group">
                            <label for="time-value">数值</label>
                            <input type="number" id="time-value" placeholder="输入数值">
                        </div>
                        <div class="converter-group">
                            <label for="time-from">从</label>
                            <select id="time-from">
                                <option value="ns">纳秒(ns)</option>
                                <option value="μs">微秒(μs)</option>
                                <option value="ms">毫秒(ms)</option>
                                <option value="s" selected>秒(s)</option>
                                <option value="min">分钟(min)</option>
                                <option value="h">小时(h)</option>
                                <option value="d">天(d)</option>
                                <option value="week">周(week)</option>
                                <option value="month">月(month)</option>
                                <option value="year">年(year)</option>
                            </select>
                        </div>
                        <div class="converter-group">
                            <label for="time-to">到</label>
                            <select id="time-to">
                                <option value="ns">纳秒(ns)</option>
                                <option value="μs">微秒(μs)</option>
                                <option value="ms">毫秒(ms)</option>
                                <option value="s">秒(s)</option>
                                <option value="min" selected>分钟(min)</option>
                                <option value="h">小时(h)</option>
                                <option value="d">天(d)</option>
                                <option value="week">周(week)</option>
                                <option value="month">月(month)</option>
                                <option value="year">年(year)</option>
                            </select>
                        </div>
                    </div>
                    <div class="result-display" id="time-result">0 min</div>
                </div>
                
                <!-- 数据存储单位转换器 -->
                <div id="data-converter" class="unit-converter" style="display:none;">
                    <div class="converter-row">
                        <div class="converter-group">
                            <label for="data-value">数值</label>
                            <input type="number" id="data-value" placeholder="输入数值">
                        </div>
                        <div class="converter-group">
                            <label for="data-from">从</label>
                            <select id="data-from">
                                <option value="b">比特(bit)</option>
                                <option value="B" selected>字节(B)</option>
                                <option value="KB">千字节(KB)</option>
                                <option value="MB">兆字节(MB)</option>
                                <option value="GB">吉字节(GB)</option>
                                <option value="TB">太字节(TB)</option>
                                <option value="PB">拍字节(PB)</option>
                                <option value="EB">艾字节(EB)</option>
                            </select>
                        </div>
                        <div class="converter-group">
                            <label for="data-to">到</label>
                            <select id="data-to">
                                <option value="b">比特(bit)</option>
                                <option value="B">字节(B)</option>
                                <option value="KB" selected>千字节(KB)</option>
                                <option value="MB">兆字节(MB)</option>
                                <option value="GB">吉字节(GB)</option>
                                <option value="TB">太字节(TB)</option>
                                <option value="PB">拍字节(PB)</option>
                                <option value="EB">艾字节(EB)</option>
                            </select>
                        </div>
                    </div>
                    <div class="result-display" id="data-result">0 KB</div>
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
            
        // 单位转换逻辑
        document.addEventListener('DOMContentLoaded', function() {
            // 选项卡切换
            const tabs = document.querySelectorAll('.unit-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // 隐藏所有转换器
                    document.querySelectorAll('.unit-converter').forEach(converter => {
                        converter.style.display = 'none';
                    });
                    
                    // 显示当前转换器
                    const unitType = this.dataset.unit;
                    document.getElementById(`${unitType}-converter`).style.display = 'flex';
                });
            });
            
            // 长度单位转换
            const lengthValue = document.getElementById('length-value');
            const lengthFrom = document.getElementById('length-from');
            const lengthTo = document.getElementById('length-to');
            const lengthResult = document.getElementById('length-result');
            
            // 重量单位转换
            const weightValue = document.getElementById('weight-value');
            const weightFrom = document.getElementById('weight-from');
            const weightTo = document.getElementById('weight-to');
            const weightResult = document.getElementById('weight-result');
            
            // 温度单位转换
            const tempValue = document.getElementById('temp-value');
            const tempFrom = document.getElementById('temp-from');
            const tempTo = document.getElementById('temp-to');
            const tempResult = document.getElementById('temp-result');
            
            // 添加事件监听器
            [lengthValue, lengthFrom, lengthTo].forEach(el => {
                el.addEventListener('input', updateLengthConversion);
            });
            
            [weightValue, weightFrom, weightTo].forEach(el => {
                el.addEventListener('input', updateWeightConversion);
            });
            
            [tempValue, tempFrom, tempTo].forEach(el => {
                el.addEventListener('input', updateTempConversion);
            });
            
            // 初始化显示
            updateLengthConversion();
            updateWeightConversion();
            updateTempConversion();
            
            function updateLengthConversion() {
                const value = parseFloat(lengthValue.value) || 0;
                const fromUnit = lengthFrom.value;
                const toUnit = lengthTo.value;
                
                const result = convertLength(value, fromUnit, toUnit);
                lengthResult.textContent = `${result.toFixed(4)} ${getUnitName(toUnit)}`;
            }
            
            function updateWeightConversion() {
                const value = parseFloat(weightValue.value) || 0;
                const fromUnit = weightFrom.value;
                const toUnit = weightTo.value;
                
                const result = convertWeight(value, fromUnit, toUnit);
                weightResult.textContent = `${result.toFixed(4)} ${getUnitName(toUnit)}`;
            }
            
            function updateTempConversion() {
                const value = parseFloat(tempValue.value) || 0;
                const fromUnit = tempFrom.value;
                const toUnit = tempTo.value;
                
                const result = convertTemperature(value, fromUnit, toUnit);
                tempResult.textContent = `${result.toFixed(2)} ${getUnitName(toUnit)}`;
            }
            
            function getUnitName(unit) {
                const units = {
                    'mm': 'mm',
                    'cm': 'cm',
                    'm': 'm',
                    'km': 'km',
                    'in': 'in',
                    'ft': 'ft',
                    'yd': 'yd',
                    'mi': 'mi',
                    'mg': 'mg',
                    'g': 'g',
                    'kg': 'kg',
                    't': 't',
                    'oz': 'oz',
                    'lb': 'lb',
                    'c': '°C',
                    'f': '°F',
                    'k': 'K'
                };
                return units[unit] || unit;
            }
            
            function convertLength(value, fromUnit, toUnit) {
                // 先转换为米
                let meters;
                switch(fromUnit) {
                    case 'mm': meters = value / 1000; break;
                    case 'cm': meters = value / 100; break;
                    case 'm': meters = value; break;
                    case 'km': meters = value * 1000; break;
                    case 'in': meters = value * 0.0254; break;
                    case 'ft': meters = value * 0.3048; break;
                    case 'yd': meters = value * 0.9144; break;
                    case 'mi': meters = value * 1609.344; break;
                    default: meters = value;
                }
                
                // 从米转换为目标单位
                switch(toUnit) {
                    case 'mm': return meters * 1000;
                    case 'cm': return meters * 100;
                    case 'm': return meters;
                    case 'km': return meters / 1000;
                    case 'in': return meters / 0.0254;
                    case 'ft': return meters / 0.3048;
                    case 'yd': return meters / 0.9144;
                    case 'mi': return meters / 1609.344;
                    default: return meters;
                }
            }
            
            function convertWeight(value, fromUnit, toUnit) {
                // 先转换为克
                let grams;
                switch(fromUnit) {
                    case 'mg': grams = value / 1000; break;
                    case 'g': grams = value; break;
                    case 'kg': grams = value * 1000; break;
                    case 't': grams = value * 1000000; break;
                    case 'oz': grams = value * 28.3495; break;
                    case 'lb': grams = value * 453.592; break;
                    default: grams = value;
                }
                
                // 从克转换为目标单位
                switch(toUnit) {
                    case 'mg': return grams * 1000;
                    case 'g': return grams;
                    case 'kg': return grams / 1000;
                    case 't': return grams / 1000000;
                    case 'oz': return grams / 28.3495;
                    case 'lb': return grams / 453.592;
                    default: return grams;
                }
            }
            
            function convertTemperature(value, fromUnit, toUnit) {
                // 先转换为摄氏度
                let celsius;
                switch(fromUnit) {
                    case 'c': celsius = value; break;
                    case 'f': celsius = (value - 32) * 5/9; break;
                    case 'k': celsius = value - 273.15; break;
                    default: celsius = value;
                }
                
                // 从摄氏度转换为目标单位
                switch(toUnit) {
                    case 'c': return celsius;
                    case 'f': return celsius * 9/5 + 32;
                    case 'k': return celsius + 273.15;
                    default: return celsius;
                }
            }
            
            function convertPower(value, fromUnit, toUnit) {
                // 先转换为瓦特
                let watts;
                switch(fromUnit) {
                    case 'w': watts = value; break;
                    case 'kw': watts = value * 1000; break;
                    case 'hp': watts = value * 745.7; break;
                    case 'btu': watts = value * 0.29307107; break;
                    default: watts = value;
                }
                
                // 从瓦特转换为目标单位
                switch(toUnit) {
                    case 'w': return watts;
                    case 'kw': return watts / 1000;
                    case 'hp': return watts / 745.7;
                    case 'btu': return watts / 0.29307107;
                    default: return watts;
                }
            }
            
            function convertPressure(value, fromUnit, toUnit) {
                // 先转换为帕斯卡
                let pascals;
                switch(fromUnit) {
                    case 'pa': pascals = value; break;
                    case 'kpa': pascals = value * 1000; break;
                    case 'mpa': pascals = value * 1000000; break;
                    case 'bar': pascals = value * 100000; break;
                    case 'psi': pascals = value * 6894.76; break;
                    case 'atm': pascals = value * 101325; break;
                    default: pascals = value;
                }
                
                // 从帕斯卡转换为目标单位
                switch(toUnit) {
                    case 'pa': return pascals;
                    case 'kpa': return pascals / 1000;
                    case 'mpa': return pascals / 1000000;
                    case 'bar': return pascals / 100000;
                    case 'psi': return pascals / 6894.76;
                    case 'atm': return pascals / 101325;
                    default: return pascals;
                }
            }
            
            function convertVolume(value, fromUnit, toUnit) {
                // 先转换为升
                let liters;
                switch(fromUnit) {
                    case 'ml': liters = value / 1000; break;
                    case 'l': liters = value; break;
                    case 'm3': liters = value * 1000; break;
                    case 'cm3': liters = value / 1000; break;
                    case 'mm3': liters = value / 1000000; break;
                    case 'gal': liters = value * 3.78541; break;
                    case 'qt': liters = value * 0.946353; break;
                    case 'pt': liters = value * 0.473176; break;
                    default: liters = value;
                }
                
                // 从升转换为目标单位
                switch(toUnit) {
                    case 'ml': return liters * 1000;
                    case 'l': return liters;
                    case 'm3': return liters / 1000;
                    case 'cm3': return liters * 1000;
                    case 'mm3': return liters * 1000000;
                    case 'gal': return liters / 3.78541;
                    case 'qt': return liters / 0.946353;
                    case 'pt': return liters / 0.473176;
                    default: return liters;
                }
            }
            
            function convertArea(value, fromUnit, toUnit) {
                // 先转换为平方米
                let squareMeters;
                switch(fromUnit) {
                    case 'mm2': squareMeters = value / 1000000; break;
                    case 'cm2': squareMeters = value / 10000; break;
                    case 'm2': squareMeters = value; break;
                    case 'km2': squareMeters = value * 1000000; break;
                    case 'ha': squareMeters = value * 10000; break;
                    case 'in2': squareMeters = value * 0.00064516; break;
                    case 'ft2': squareMeters = value * 0.092903; break;
                    case 'yd2': squareMeters = value * 0.836127; break;
                    case 'acre': squareMeters = value * 4046.86; break;
                    default: squareMeters = value;
                }
                
                // 从平方米转换为目标单位
                switch(toUnit) {
                    case 'mm2': return squareMeters * 1000000;
                    case 'cm2': return squareMeters * 10000;
                    case 'm2': return squareMeters;
                    case 'km2': return squareMeters / 1000000;
                    case 'ha': return squareMeters / 10000;
                    case 'in2': return squareMeters / 0.00064516;
                    case 'ft2': return squareMeters / 0.092903;
                    case 'yd2': return squareMeters / 0.836127;
                    case 'acre': return squareMeters / 4046.86;
                    default: return squareMeters;
                }
            }
            
            function convertSpeed(value, fromUnit, toUnit) {
                // 先转换为米/秒
                let metersPerSecond;
                switch(fromUnit) {
                    case 'ms': metersPerSecond = value; break;
                    case 'kmh': metersPerSecond = value / 3.6; break;
                    case 'mph': metersPerSecond = value * 0.44704; break;
                    case 'knot': metersPerSecond = value * 0.514444; break;
                    case 'fts': metersPerSecond = value * 0.3048; break;
                    case 'mach': metersPerSecond = value * 343; break;
                    default: metersPerSecond = value;
                }
                
                // 从米/秒转换为目标单位
                switch(toUnit) {
                    case 'ms': return metersPerSecond;
                    case 'kmh': return metersPerSecond * 3.6;
                    case 'mph': return metersPerSecond / 0.44704;
                    case 'knot': return metersPerSecond / 0.514444;
                    case 'fts': return metersPerSecond / 0.3048;
                    case 'mach': return metersPerSecond / 343;
                    default: return metersPerSecond;
                }
            }
            
            function convertTime(value, fromUnit, toUnit) {
                // 先转换为秒
                let seconds;
                switch(fromUnit) {
                    case 'ns': seconds = value / 1000000000; break;
                    case 'μs': seconds = value / 1000000; break;
                    case 'ms': seconds = value / 1000; break;
                    case 's': seconds = value; break;
                    case 'min': seconds = value * 60; break;
                    case 'h': seconds = value * 3600; break;
                    case 'd': seconds = value * 86400; break;
                    case 'week': seconds = value * 604800; break;
                    case 'month': seconds = value * 2629746; break;
                    case 'year': seconds = value * 31556952; break;
                    default: seconds = value;
                }
                
                // 从秒转换为目标单位
                switch(toUnit) {
                    case 'ns': return seconds * 1000000000;
                    case 'μs': return seconds * 1000000;
                    case 'ms': return seconds * 1000;
                    case 's': return seconds;
                    case 'min': return seconds / 60;
                    case 'h': return seconds / 3600;
                    case 'd': return seconds / 86400;
                    case 'week': return seconds / 604800;
                    case 'month': return seconds / 2629746;
                    case 'year': return seconds / 31556952;
                    default: return seconds;
                }
            }
            
            function convertData(value, fromUnit, toUnit) {
                // 先转换为字节
                let bytes;
                switch(fromUnit) {
                    case 'b': bytes = value / 8; break;
                    case 'B': bytes = value; break;
                    case 'KB': bytes = value * 1024; break;
                    case 'MB': bytes = value * 1048576; break;
                    case 'GB': bytes = value * 1073741824; break;
                    case 'TB': bytes = value * 1099511627776; break;
                    case 'PB': bytes = value * 1125899906842624; break;
                    case 'EB': bytes = value * 1152921504606847000; break;
                    default: bytes = value;
                }
                
                // 从字节转换为目标单位
                switch(toUnit) {
                    case 'b': return bytes * 8;
                    case 'B': return bytes;
                    case 'KB': return bytes / 1024;
                    case 'MB': return bytes / 1048576;
                    case 'GB': return bytes / 1073741824;
                    case 'TB': return bytes / 1099511627776;
                    case 'PB': return bytes / 1125899906842624;
                    case 'EB': return bytes / 1152921504606847000;
                    default: return bytes;
                }
            }
            
            // 添加事件监听器
            const powerValue = document.getElementById('power-value');
            const powerFrom = document.getElementById('power-from');
            const powerTo = document.getElementById('power-to');
            const powerResult = document.getElementById('power-result');
            
            const pressureValue = document.getElementById('pressure-value');
            const pressureFrom = document.getElementById('pressure-from');
            const pressureTo = document.getElementById('pressure-to');
            const pressureResult = document.getElementById('pressure-result');
            
            [powerValue, powerFrom, powerTo].forEach(el => {
                el.addEventListener('input', updatePowerConversion);
            });
            
            [pressureValue, pressureFrom, pressureTo].forEach(el => {
                el.addEventListener('input', updatePressureConversion);
            });
            
            // 体积转换器事件监听器
            const volumeValue = document.getElementById('volume-value');
            const volumeFrom = document.getElementById('volume-from');
            const volumeTo = document.getElementById('volume-to');
            const volumeResult = document.getElementById('volume-result');
            
            // 面积转换器事件监听器
            const areaValue = document.getElementById('area-value');
            const areaFrom = document.getElementById('area-from');
            const areaTo = document.getElementById('area-to');
            const areaResult = document.getElementById('area-result');
            
            // 速度转换器事件监听器
            const speedValue = document.getElementById('speed-value');
            const speedFrom = document.getElementById('speed-from');
            const speedTo = document.getElementById('speed-to');
            const speedResult = document.getElementById('speed-result');
            
            // 时间转换器事件监听器
            const timeValue = document.getElementById('time-value');
            const timeFrom = document.getElementById('time-from');
            const timeTo = document.getElementById('time-to');
            const timeResult = document.getElementById('time-result');
            
            // 数据存储转换器事件监听器
            const dataValue = document.getElementById('data-value');
            const dataFrom = document.getElementById('data-from');
            const dataTo = document.getElementById('data-to');
            const dataResult = document.getElementById('data-result');
            
            [volumeValue, volumeFrom, volumeTo].forEach(el => {
                el.addEventListener('input', updateVolumeConversion);
            });
            
            [areaValue, areaFrom, areaTo].forEach(el => {
                el.addEventListener('input', updateAreaConversion);
            });
            
            [speedValue, speedFrom, speedTo].forEach(el => {
                el.addEventListener('input', updateSpeedConversion);
            });
            
            [timeValue, timeFrom, timeTo].forEach(el => {
                el.addEventListener('input', updateTimeConversion);
            });
            
            [dataValue, dataFrom, dataTo].forEach(el => {
                el.addEventListener('input', updateDataConversion);
            });
            
            function updatePowerConversion() {
                const value = parseFloat(powerValue.value) || 0;
                const fromUnit = powerFrom.value;
                const toUnit = powerTo.value;
                
                const result = convertPower(value, fromUnit, toUnit);
                powerResult.textContent = `${result.toFixed(4)} ${getUnitName(toUnit)}`;
            }
            
            function updatePressureConversion() {
                const value = parseFloat(pressureValue.value) || 0;
                const fromUnit = pressureFrom.value;
                const toUnit = pressureTo.value;
                
                const result = convertPressure(value, fromUnit, toUnit);
                pressureResult.textContent = `${result.toFixed(4)} ${getUnitName(toUnit)}`;
            }
            
            function updateVolumeConversion() {
                const value = parseFloat(volumeValue.value) || 0;
                const fromUnit = volumeFrom.value;
                const toUnit = volumeTo.value;
                
                const result = convertVolume(value, fromUnit, toUnit);
                volumeResult.textContent = `${result.toFixed(4)} ${getUnitName(toUnit)}`;
            }
            
            function updateAreaConversion() {
                const value = parseFloat(areaValue.value) || 0;
                const fromUnit = areaFrom.value;
                const toUnit = areaTo.value;
                
                const result = convertArea(value, fromUnit, toUnit);
                areaResult.textContent = `${result.toFixed(4)} ${getUnitName(toUnit)}`;
            }
            
            function updateSpeedConversion() {
                const value = parseFloat(speedValue.value) || 0;
                const fromUnit = speedFrom.value;
                const toUnit = speedTo.value;
                
                const result = convertSpeed(value, fromUnit, toUnit);
                speedResult.textContent = `${result.toFixed(4)} ${getUnitName(toUnit)}`;
            }
            
            function updateTimeConversion() {
                const value = parseFloat(timeValue.value) || 0;
                const fromUnit = timeFrom.value;
                const toUnit = timeTo.value;
                
                const result = convertTime(value, fromUnit, toUnit);
                timeResult.textContent = `${result.toFixed(4)} ${getUnitName(toUnit)}`;
            }
            
            function updateDataConversion() {
                const value = parseFloat(dataValue.value) || 0;
                const fromUnit = dataFrom.value;
                const toUnit = dataTo.value;
                
                const result = convertData(value, fromUnit, toUnit);
                dataResult.textContent = `${result.toFixed(4)} ${getUnitName(toUnit)}`;
            }
            
            // 更新getUnitName函数
            function getUnitName(unit) {
                const units = {
                    'mm': 'mm',
                    'cm': 'cm',
                    'm': 'm',
                    'km': 'km',
                    'in': 'in',
                    'ft': 'ft',
                    'yd': 'yd',
                    'mi': 'mi',
                    'mg': 'mg',
                    'g': 'g',
                    'kg': 'kg',
                    't': 't',
                    'oz': 'oz',
                    'lb': 'lb',
                    'c': '°C',
                    'f': '°F',
                    'k': 'K',
                    'w': 'W',
                    'kw': 'kW',
                    'hp': 'hp',
                    'btu': 'BTU/h',
                    'pa': 'Pa',
                    'kpa': 'kPa',
                    'mpa': 'MPa',
                    'bar': 'bar',
                    'psi': 'psi',
                    'atm': 'atm',
                    'ml': 'ml',
                    'l': 'l',
                    'm3': 'm³',
                    'cm3': 'cm³',
                    'mm3': 'mm³',
                    'gal': 'gal',
                    'qt': 'qt',
                    'pt': 'pt',
                    'mm2': 'mm²',
                    'cm2': 'cm²',
                    'm2': 'm²',
                    'km2': 'km²',
                    'ha': 'ha',
                    'in2': 'in²',
                    'ft2': 'ft²',
                    'yd2': 'yd²',
                    'acre': 'acre',
                    'ms': 'm/s',
                    'kmh': 'km/h',
                    'mph': 'mph',
                    'knot': 'knot',
                    'fts': 'ft/s',
                    'mach': 'Mach',
                    'ns': 'ns',
                    'μs': 'μs',
                    'ms': 'ms',
                    's': 's',
                    'min': 'min',
                    'h': 'h',
                    'd': 'd',
                    'week': 'week',
                    'month': 'month',
                    'year': 'year',
                    'b': 'bit',
                    'B': 'B',
                    'KB': 'KB',
                    'MB': 'MB',
                    'GB': 'GB',
                    'TB': 'TB',
                    'PB': 'PB',
                    'EB': 'EB'
                };
                return units[unit] || unit;
            }
            
            // 初始化新转换器的显示
            updateVolumeConversion();
            updateAreaConversion();
            updateSpeedConversion();
            updateTimeConversion();
            updateDataConversion();
        });
    </script>
</body>
</html>
