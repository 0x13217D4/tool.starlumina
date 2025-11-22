<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>屏幕帧率检测 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <style>
        /* FPS 监测器特定样式 */
        .fps-monitor-grid {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .fps-monitor-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1200px) {
            .fps-monitor-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        /* 卡片基础样式 */
        .card-fps-display {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-fps-display:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }
        
        .card-fps-display::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }
        
        .card-screen-info,
        .card-performance-stats,
        .card-performance-rating {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-screen-info:hover,
        .card-performance-stats:hover,
        .card-performance-rating:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        /* 卡片头部样式 */
        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .card-screen-info .card-header,
        .card-performance-stats .card-header,
        .card-performance-rating .card-header {
            border-bottom-color: #e9ecef;
        }
        
        .card-header i {
            font-size: 1.5rem;
            margin-right: 0.75rem;
            color: #3498db;
        }
        
        .card-fps-display .card-header i {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .card-header h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .card-fps-display .card-header h2 {
            color: white;
        }
        
        .fps-display-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 3rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            margin-bottom: 2rem;
        }
        
        .fps-display-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .fps-value {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: bold;
            font-variant-numeric: tabular-nums;
            text-shadow: 0 0 20px rgba(255,255,255,0.3);
            margin: 1rem 0;
            line-height: 1;
        }
        
        .fps-status {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size: 1rem;
            margin-top: 1rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 0.75rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.2); }
        }
        
        .info-section {
            display: grid;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .info-section {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .info-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            padding: 2rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .info-card h3 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: #2c3e50;
            display: flex;
            align-items: center;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .info-card h3 i {
            margin-right: 0.75rem;
            color: #3498db;
            font-size: 1.25rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.2s ease;
        }
        
        .info-row:hover {
            background-color: rgba(52, 152, 219, 0.05);
            margin: 0 -0.5rem;
            padding: 1rem 0.5rem;
            border-radius: 8px;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #6c757d;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .info-value {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
            font-variant-numeric: tabular-nums;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
            margin-top: 1.5rem;
        }
        
        .stat-item {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            padding: 1.5rem 1rem;
            border-radius: 12px;
            text-align: center;
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
        }
        
        .stat-item:nth-child(2) {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.2);
        }
        
        .stat-item:nth-child(2):hover {
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.3);
        }
        
        .stat-item:nth-child(3) {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.2);
        }
        
        .stat-item:nth-child(3):hover {
            box-shadow: 0 8px 25px rgba(243, 156, 18, 0.3);
        }
        
        .stat-item:nth-child(4) {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.2);
        }
        
        .stat-item:nth-child(4):hover {
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* 响应式优化 */
        @media (max-width: 576px) {
            .fps-display-container {
                padding: 2rem 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .info-card {
                padding: 1.5rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .info-section {
                grid-template-columns: 1fr;
            }
        }
        
        /* FPS 显示区域样式 */
        .fps-metric {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .fps-number {
            font-size: clamp(3rem, 8vw, 5rem);
            font-weight: bold;
            font-variant-numeric: tabular-nums;
            text-shadow: 0 0 30px rgba(255,255,255,0.3);
            margin: 0;
            line-height: 1;
        }
        
        .fps-status {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size: 1rem;
            margin-top: 1rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 0.75rem;
            animation: pulse 2s infinite;
        }
        
        .fps-indicator {
            width: 100%;
            margin-top: 1.5rem;
        }
        
        .indicator-bar {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .indicator-fill {
            height: 100%;
            background: linear-gradient(90deg, #e74c3c 0%, #f39c12 50%, #27ae60 100%);
            border-radius: 4px;
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .indicator-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        /* 信息网格样式 */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: rgba(52, 152, 219, 0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .info-item:hover {
            background: rgba(52, 152, 219, 0.1);
            transform: translateX(5px);
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border-radius: 10px;
            margin-right: 1rem;
            font-size: 1.1rem;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-content .info-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }
        
        .info-content .info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        /* 性能网格样式 */
        .performance-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .performance-item {
            display: flex;
            align-items: center;
            padding: 1.25rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .performance-item.avg {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }
        
        .performance-item.max {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }
        
        .performance-item.min {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }
        
        .performance-item.variance {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }
        
        .performance-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .perf-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            margin-right: 1rem;
            font-size: 1.1rem;
        }
        
        .perf-content {
            flex: 1;
            text-align: left;
        }
        
        .perf-value {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }
        
        .perf-label {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-bottom: 0.25rem;
        }
        
        .perf-unit {
            font-size: 0.7rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* 评级显示样式 */
        .rating-display {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .rating-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 8px 25px rgba(243, 156, 18, 0.3);
            flex-shrink: 0;
        }
        
        .rating-value {
            font-size: 2.5rem;
            font-weight: bold;
            line-height: 1;
        }
        
        .rating-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 0.25rem;
        }
        
        .rating-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .rating-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .rating-item .rating-label {
            min-width: 60px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #6c757d;
        }
        
        .rating-bar {
            flex: 1;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .rating-fill {
            height: 100%;
            background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
            border-radius: 4px;
            transition: width 0.5s ease;
        }
        
        .rating-percent {
            min-width: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #2c3e50;
            text-align: right;
        }
        
        /* 动画优化 */
        .fps-number {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .info-item {
            animation: fadeInUp 0.6s ease-out backwards;
        }
        
        .info-item:nth-child(1) { animation-delay: 0.1s; }
        .info-item:nth-child(2) { animation-delay: 0.2s; }
        .info-item:nth-child(3) { animation-delay: 0.3s; }
        .info-item:nth-child(4) { animation-delay: 0.4s; }
        
        .performance-item {
            animation: fadeInUp 0.6s ease-out backwards;
        }
        
        .performance-item:nth-child(1) { animation-delay: 0.1s; }
        .performance-item:nth-child(2) { animation-delay: 0.2s; }
        .performance-item:nth-child(3) { animation-delay: 0.3s; }
        .performance-item:nth-child(4) { animation-delay: 0.4s; }
        
        /* 响应式优化 */
        @media (max-width: 768px) {
            .rating-display {
                flex-direction: column;
                text-align: center;
            }
            
            .rating-circle {
                width: 100px;
                height: 100px;
            }
            
            .rating-value {
                font-size: 2rem;
            }
            
            .performance-grid,
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            
            <div class="fps-monitor-grid">
                <!-- 实时帧率显示卡片 -->
                <div class="card-fps-display">
                    <div class="card-header">
                        <i class="fa fa-tachometer"></i>
                        <h2>实时帧率监测</h2>
                    </div>
                    <div class="card-content">
                        <div class="fps-metric">
                            <div id="fps-display" class="fps-number">--</div>
                            <div id="fps-status" class="fps-status">
                                <span class="status-dot" style="background-color: #95a5a6;"></span>
                                <span class="status-text">等待检测...</span>
                            </div>
                        </div>
                        <div class="fps-indicator">
                            <div class="indicator-bar">
                                <div class="indicator-fill" id="fps-indicator-fill"></div>
                            </div>
                            <div class="indicator-labels">
                                <span>0</span>
                                <span>60</span>
                                <span>120+</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 屏幕信息卡片 -->
                <div class="card-screen-info">
                    <div class="card-header">
                        <i class="fa fa-desktop"></i>
                        <h2>屏幕信息</h2>
                    </div>
                    <div class="card-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fa fa-expand"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">分辨率</div>
                                    <div id="screen-resolution" class="info-value">--</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fa fa-refresh"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">刷新率</div>
                                    <div id="screen-refresh-rate" class="info-value">--</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fa fa-palette"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">颜色深度</div>
                                    <div id="screen-color-depth" class="info-value">--</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fa fa-search-plus"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">像素密度</div>
                                    <div id="screen-pixel-density" class="info-value">--</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 性能统计卡片 -->
                <div class="card-performance-stats">
                    <div class="card-header">
                        <i class="fa fa-chart-line"></i>
                        <h2>性能统计</h2>
                    </div>
                    <div class="card-content">
                        <div class="performance-grid">
                            <div class="performance-item avg">
                                <div class="perf-icon">
                                    <i class="fa fa-calculator"></i>
                                </div>
                                <div class="perf-content">
                                    <div class="perf-value" id="avg-fps">--</div>
                                    <div class="perf-label">平均帧率</div>
                                    <div class="perf-unit">FPS</div>
                                </div>
                            </div>
                            <div class="performance-item max">
                                <div class="perf-icon">
                                    <i class="fa fa-arrow-up"></i>
                                </div>
                                <div class="perf-content">
                                    <div class="perf-value" id="max-fps">--</div>
                                    <div class="perf-label">最高帧率</div>
                                    <div class="perf-unit">FPS</div>
                                </div>
                            </div>
                            <div class="performance-item min">
                                <div class="perf-icon">
                                    <i class="fa fa-arrow-down"></i>
                                </div>
                                <div class="perf-content">
                                    <div class="perf-value" id="min-fps">--</div>
                                    <div class="perf-label">最低帧率</div>
                                    <div class="perf-unit">FPS</div>
                                </div>
                            </div>
                            <div class="performance-item variance">
                                <div class="perf-icon">
                                    <i class="fa fa-wave-square"></i>
                                </div>
                                <div class="perf-content">
                                    <div class="perf-value" id="fps-variance">--</div>
                                    <div class="perf-label">帧率波动</div>
                                    <div class="perf-unit">FPS</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 性能评级卡片 -->
                <div class="card-performance-rating">
                    <div class="card-header">
                        <i class="fa fa-star"></i>
                        <h2>性能评级</h2>
                    </div>
                    <div class="card-content">
                        <div class="rating-display">
                            <div class="rating-circle" id="rating-circle">
                                <div class="rating-value" id="rating-value">A+</div>
                                <div class="rating-label">优秀</div>
                            </div>
                            <div class="rating-details">
                                <div class="rating-item">
                                    <span class="rating-label">流畅度</span>
                                    <div class="rating-bar">
                                        <div class="rating-fill" id="smoothness-bar" style="width: 0%"></div>
                                    </div>
                                    <span class="rating-percent" id="smoothness-percent">0%</span>
                                </div>
                                <div class="rating-item">
                                    <span class="rating-label">稳定性</span>
                                    <div class="rating-bar">
                                        <div class="rating-fill" id="stability-bar" style="width: 0%"></div>
                                    </div>
                                    <span class="rating-percent" id="stability-percent">0%</span>
                                </div>
                                <div class="rating-item">
                                    <span class="rating-label">响应性</span>
                                    <div class="rating-bar">
                                        <div class="rating-fill" id="responsiveness-bar" style="width: 0%"></div>
                                    </div>
                                    <span class="rating-percent" id="responsiveness-percent">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div id="footer-container"></div>
    

    <script>
        // 全局变量
        let lastFrameTime = performance.now();
        let frameCount = 0;
        let fpsArray = [];
        let maxSamples = 60;
        let alertThreshold = 30;
        let alertEnabled = true;
        let alertActive = false;
        let stats = {
            avg: 0,
            max: 0,
            min: Infinity,
            variance: 0
        };
        
        // DOM元素
        const fpsDisplay = document.getElementById('fps-display');
        const fpsStatus = document.getElementById('fps-status');
        const screenResolution = document.getElementById('screen-resolution');
        const screenRefreshRate = document.getElementById('screen-refresh-rate');
        const screenColorDepth = document.getElementById('screen-color-depth');
        const screenPixelDensity = document.getElementById('screen-pixel-density');
        const avgFps = document.getElementById('avg-fps');
        const maxFps = document.getElementById('max-fps');
        const minFps = document.getElementById('min-fps');
        const fpsVariance = document.getElementById('fps-variance');
        // 移除了已删除的警报相关DOM元素引用
        
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
        
        // 初始化函数
        function init() {
            // 获取屏幕信息
            getScreenInfo();
            
            // 开始帧率检测
            requestAnimationFrame(calculateFPS);
            
            // 每5秒更新一次统计信息
            setInterval(updateStats, 5000);
            
            // 启动屏幕监控
            setupScreenMonitoring();
        }
        
        // 获取当前活动显示器信息
        function getScreenInfo() {
            try {
                // 获取当前窗口所在的显示器信息
                const currentScreen = getCurrentScreenInfo();
                
                if (currentScreen && currentScreen.width && currentScreen.height) {
                    screenResolution.textContent = `${currentScreen.width} × ${currentScreen.height}`;
                    screenColorDepth.textContent = `${currentScreen.colorDepth} 位`;
                    screenPixelDensity.textContent = `${currentScreen.pixelRatio}x`;
                    
                    // 尝试获取当前显示器的刷新率
                    if (currentScreen.refreshRate) {
                        screenRefreshRate.textContent = `${currentScreen.refreshRate} Hz`;
                    } else {
                        screenRefreshRate.textContent = '检测中...';
                    }
                } else {
                    // 降级到默认方法
                    const width = window.screen.width || window.innerWidth;
                    const height = window.screen.height || window.innerHeight;
                    screenResolution.textContent = `${width} × ${height}`;
                    screenColorDepth.textContent = `${window.screen.colorDepth || 24} 位`;
                    screenPixelDensity.textContent = `${window.devicePixelRatio || 1}x`;
                    screenRefreshRate.textContent = '检测中...';
                }
            } catch (error) {
                console.error('获取屏幕信息失败:', error);
                // 使用最基本的降级方法
                const width = window.innerWidth || 1920;
                const height = window.innerHeight || 1080;
                screenResolution.textContent = `${width} × ${height}`;
                screenColorDepth.textContent = '24 位';
                screenPixelDensity.textContent = '1x';
                screenRefreshRate.textContent = '检测中...';
            }
        }
        
        // 获取当前活动显示器的详细信息
        function getCurrentScreenInfo() {
            try {
                // 使用 Screen API 获取所有显示器信息
                if (window.screen.isExtended && window.getScreenDetails) {
                    // 如果支持多显示器API
                    return getMultiDisplayInfo();
                } else {
                    // 降级处理：通过窗口位置推断当前显示器
                    return inferCurrentDisplay();
                }
            } catch (error) {
                console.warn('无法获取多显示器信息，使用默认方法:', error);
                return null;
            }
        }
        
        // 使用现代多显示器API获取信息
        async function getMultiDisplayInfo() {
            try {
                const screenDetails = await window.getScreenDetails();
                const currentScreen = screenDetails.currentScreen;
                
                return {
                    width: currentScreen.width,
                    height: currentScreen.height,
                    colorDepth: window.screen.colorDepth, // 颜色深度通常相同
                    pixelRatio: window.devicePixelRatio, // 像素密度通常相同
                    refreshRate: currentScreen.refreshRate || null
                };
            } catch (error) {
                console.warn('多显示器API不可用:', error);
                return null;
            }
        }
        
        // 通过窗口位置推断当前显示器
        function inferCurrentDisplay() {
            const windowX = window.screenX || window.screenLeft || 0;
            const windowY = window.screenY || window.screenTop || 0;
            const windowWidth = window.outerWidth;
            const windowHeight = window.outerHeight;
            
            // 计算窗口中心点
            const centerX = windowX + windowWidth / 2;
            const centerY = windowY + windowHeight / 2;
            
            // 使用可用的屏幕信息
            return {
                width: window.screen.width,
                height: window.screen.height,
                colorDepth: window.screen.colorDepth,
                pixelRatio: window.devicePixelRatio,
                refreshRate: window.screen.refreshRate || null,
                // 添加显示器位置信息用于调试
                displayLeft: windowX,
                displayTop: windowY
            };
        }
        
        // 监听窗口移动事件，更新屏幕信息
        function setupScreenMonitoring() {
            let lastWindowX = window.screenX || window.screenLeft || 0;
            let lastWindowY = window.screenY || window.screenTop || 0;
            
            setInterval(() => {
                const currentX = window.screenX || window.screenLeft || 0;
                const currentY = window.screenY || window.screenTop || 0;
                
                // 检测窗口是否移动到不同显示器
                if (Math.abs(currentX - lastWindowX) > 100 || Math.abs(currentY - lastWindowY) > 100) {
                    getScreenInfo(); // 重新获取屏幕信息
                    lastWindowX = currentX;
                    lastWindowY = currentY;
                }
            }, 1000); // 每秒检查一次
        }
        
        // 获取当前刷新率数值
        function getCurrentRefreshRate() {
            if (fpsArray.length === 0) {
                return 60; // 默认值
            }
            
            const maxFps = Math.max(...fpsArray);
            const standardRates = [30, 60, 75, 90, 120, 144, 240];
            let closestRate = 60; // 默认值
            let minDifference = Infinity;
            
            // 找到最接近的标准刷新率
            for (const rate of standardRates) {
                const difference = Math.abs(maxFps - rate);
                if (difference < minDifference) {
                    minDifference = difference;
                    closestRate = rate;
                }
            }
            
            // 如果差距超过10%，返回最高帧率
            const tolerance = closestRate * 0.1;
            if (minDifference > tolerance) {
                return maxFps;
            } else {
                return closestRate;
            }
        }
        
        // 根据最高帧率判断刷新率
        function updateRefreshRate() {
            if (fpsArray.length === 0) {
                screenRefreshRate.textContent = '检测中...';
                return;
            }
            
            const currentRefreshRate = getCurrentRefreshRate();
            const maxFps = Math.max(...fpsArray);
            const standardRates = [30, 60, 75, 90, 120, 144, 240];
            let closestRate = 60; // 默认值
            let minDifference = Infinity;
            
            // 找到最接近的标准刷新率
            for (const rate of standardRates) {
                const difference = Math.abs(maxFps - rate);
                if (difference < minDifference) {
                    minDifference = difference;
                    closestRate = rate;
                }
            }
            
            // 如果差距超过10%，显示最高帧率
            const tolerance = closestRate * 0.1;
            if (minDifference > tolerance) {
                screenRefreshRate.textContent = `${maxFps} Hz (基于最高帧率)`;
            } else {
                screenRefreshRate.textContent = `${closestRate} Hz`;
            }
        }
        
        // 图表功能已被移除
        function initChart() {
            // 空函数，保留以防调用
        }
        
        // 计算帧率 - 优化高帧率检测
        function calculateFPS(timestamp) {
            const currentTime = performance.now();
            frameCount++;
            
            // 确保时间窗口正确计算
            if (!lastFrameTime) {
                lastFrameTime = currentTime;
            }
            
            // 使用更短的时间窗口来检测高帧率，特别是120FPS
            if (currentTime - lastFrameTime >= 500) { // 改为500ms窗口，提高检测精度
                const fps = Math.round((frameCount * 1000) / (currentTime - lastFrameTime));
                
                // 确保FPS是有效的数值
                if (fps > 0 && !isNaN(fps) && isFinite(fps)) {
                    // 先更新数组，再更新显示，确保数据同步
                    fpsArray.push(fps);
                    if (fpsArray.length > maxSamples) {
                        fpsArray.shift();
                    }
                    
                    updateFPSDisplay(fps);
                    checkAlert(fps);
                }
                
                frameCount = 0;
                lastFrameTime = currentTime;
            }
            
            requestAnimationFrame(calculateFPS);
        }
        
        // 更新FPS显示 - 优化高帧率状态判断
        function updateFPSDisplay(fps) {
            fpsDisplay.textContent = fps;
            
            // 更新刷新率显示（基于最高帧率）
            updateRefreshRate();
            
            let statusText = '';
            let statusColor = '';
            let textColor = '';
            
            if (fps >= 120) {
                statusText = '极致流畅';
                statusColor = '#27ae60';
                textColor = '#27ae60';
            } else if (fps >= 90) {
                statusText = '非常流畅';
                statusColor = '#2ecc71';
                textColor = '#2ecc71';
            } else if (fps >= 60) {
                statusText = '流畅';
                statusColor = '#3498db';
                textColor = '#3498db';
            } else if (fps >= 30) {
                statusText = '一般';
                statusColor = '#f39c12';
                textColor = '#f39c12';
            } else {
                statusText = '卡顿';
                statusColor = '#e74c3c';
                textColor = '#e74c3c';
            }
            
            fpsStatus.innerHTML = `
                <span class="status-indicator" style="background-color: ${statusColor};"></span>
                ${statusText}
            `;
            
            fpsDisplay.style.color = textColor;
        }
        
        // 图表功能已被移除
        function updateChart(fps) {
            // 空函数，保留以防调用
        }
        
        // 更新统计信息
        function updateStats() {
            if (fpsArray.length === 0) return;
            
            stats.avg = calculateAverage(fpsArray);
            stats.max = Math.max(...fpsArray);
            stats.min = Math.min(...fpsArray);
            stats.variance = calculateVariance(fpsArray, stats.avg);
            
            avgFps.textContent = stats.avg.toFixed(1);
            maxFps.textContent = stats.max;
            minFps.textContent = stats.min;
            fpsVariance.textContent = stats.variance.toFixed(1);
            
            // 更新性能评级
            updatePerformanceRating();
        }
        
        // 更新性能评级
        function updatePerformanceRating() {
            const avg = stats.avg;
            const variance = stats.variance;
            const currentFps = fpsArray[fpsArray.length - 1] || 0;
            
            // 计算各项指标
            const smoothness = Math.min(100, (avg / 120) * 100); // 基于120FPS标准
            const stability = Math.max(0, 100 - (variance / avg) * 100); // 基于方差稳定性
            const responsiveness = Math.min(100, (currentFps / 60) * 100); // 基于60FPS响应性
            
            // 更新进度条
            updateProgressBar('smoothness-bar', 'smoothness-percent', smoothness);
            updateProgressBar('stability-bar', 'stability-percent', stability);
            updateProgressBar('responsiveness-bar', 'responsiveness-percent', responsiveness);
            
            // 计算总体评级
            const overallScore = (smoothness + stability + responsiveness) / 3;
            const rating = calculateRating(overallScore);
            
            // 更新评级显示
            updateRatingDisplay(rating);
        }
        
        // 更新进度条
        function updateProgressBar(barId, percentId, value) {
            const bar = document.getElementById(barId);
            const percent = document.getElementById(percentId);
            
            if (bar && percent) {
                bar.style.width = `${value}%`;
                percent.textContent = `${Math.round(value)}%`;
            }
        }
        
        // 计算评级
        function calculateRating(score) {
            if (score >= 90) return { grade: 'A+', label: '优秀', color: '#27ae60' };
            if (score >= 80) return { grade: 'A', label: '良好', color: '#3498db' };
            if (score >= 70) return { grade: 'B+', label: '不错', color: '#f39c12' };
            if (score >= 60) return { grade: 'B', label: '一般', color: '#e67e22' };
            if (score >= 50) return { grade: 'C+', label: '较差', color: '#e74c3c' };
            return { grade: 'C', label: '差', color: '#c0392b' };
        }
        
        // 更新评级显示
        function updateRatingDisplay(rating) {
            const ratingValue = document.getElementById('rating-value');
            const ratingLabel = document.getElementById('rating-label');
            const ratingCircle = document.getElementById('rating-circle');
            
            if (ratingValue && ratingLabel && ratingCircle) {
                ratingValue.textContent = rating.grade;
                ratingLabel.textContent = rating.label;
                ratingCircle.style.background = `linear-gradient(135deg, ${rating.color} 0%, ${rating.color}dd 100%)`;
                ratingCircle.style.boxShadow = `0 8px 25px ${rating.color}66`;
            }
        }
        
        // 计算平均值
        function calculateAverage(array) {
            const sum = array.reduce((a, b) => a + b, 0);
            return sum / array.length;
        }
        
        // 计算方差
        function calculateVariance(array, avg) {
            const squaredDiffs = array.map(value => Math.pow(value - avg, 2));
            const variance = calculateAverage(squaredDiffs);
            return Math.sqrt(variance);
        }
        
        // 移除的警报相关函数
        // 这些函数已被移除，因为不再需要警报功能
        function updateAlertThreshold() {
            // 空函数，保留以防调用
        }
        
        function toggleAlert() {
            // 空函数，保留以防调用
        }
        
        function checkAlert(fps) {
            // 空函数，保留以防调用
        }
        
        function showAlert() {
            // 空函数，保留以防调用
        }
        
        function hideAlert() {
            // 空函数，保留以防调用
        }
        
        // 页面加载完成后初始化
        window.addEventListener('load', init);
    </script>
</body>
</html>