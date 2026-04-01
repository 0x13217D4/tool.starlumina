<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>音响测试 - 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .back-btn {
            display: inline-block;
            margin: 0 0 20px 0;
            padding: 8px 16px;
            text-align: center;
            width: auto;
        }
        .tool-content h1 {
            margin-top: 0;
            text-align: center;
            color: #2c3e50;
        }
        .subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 20px;
        }
        
        /* 兼容性提示 */
        .compatibility-note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            display: none;
        }
        .compatibility-note.show {
            display: block;
        }
        .compatibility-note i {
            color: #ffc107;
            margin-right: 8px;
        }
        
        /* 标签页 */
        .tabs {
            display: flex;
            background: #f8f9fa;
            border-radius: 15px;
            padding: 5px;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
            flex-wrap: wrap;
        }
        .tab {
            padding: 12px 20px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 190px;
            justify-content: center;
            color: #495057;
        }
        .tab:hover {
            background: #e9ecef;
        }
        .tab.active {
            background: #007bff;
            color: white;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }
        
        .tab-content {
            display: none;
            width: 100%;
            animation: fadeIn 0.5s;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* 主容器 */
        .main-content {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            width: 100%;
            justify-content: center;
        }
        .testing-area {
            flex: 1;
            min-width: 300px;
            max-width: 900px;
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #dee2e6;
        }
        .stats-panel {
            flex: 1;
            min-width: 300px;
            max-width: 650px;
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #dee2e6;
        }
        .panel-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .panel-title i {
            font-size: 1.3rem;
            color: #3498db;
        }
        
        /* 声道配置 */
        .channel-config {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .config-btn {
            padding: 12px 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px solid #dee2e6;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #495057;
        }
        .config-btn:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }
        .config-btn.active {
            background: #007bff;
            border-color: #0056b3;
            color: white;
        }
        
        /* 扬声器布局 */
        .speaker-layout {
            width: 100%;
            height: 300px;
            background: #f8f9fa;
            border-radius: 12px;
            position: relative;
            margin: 20px 0;
            overflow: hidden;
            border: 2px dashed #dee2e6;
        }
        .speaker {
            position: absolute;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
            flex-direction: column;
            transform: translate(-50%, -50%);
        }
        .speaker:hover {
            transform: translate(-50%, -50%) scale(1.05);
        }
        .speaker.active {
            background: linear-gradient(135deg, #28a745, #20c997);
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.5);
            animation: pulse 1.5s infinite;
            transform: translate(-50%, -50%);
        }
        .speaker.subwoofer {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }
        .speaker.subwoofer.active {
            background: linear-gradient(135deg, #fd7e14, #e74c3c);
            box-shadow: 0 0 15px rgba(231, 76, 60, 0.5);
            transform: translate(-50%, -50%);
        }
        .speaker-label {
            font-size: 0.8rem;
            margin-top: 5px;
        }
        
        /* 频响测试 */
        .frequency-slider-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-top: 10px;
            border: 1px solid #dee2e6;
        }
        .slider-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .frequency-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #3498db;
        }
        .frequency-unit {
            color: #6c757d;
            font-size: 1rem;
        }
        .slider-container {
            width: 100%;
            position: relative;
            height: 40px;
        }
        .slider-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-size: 0.9rem;
            color: #6c757d;
        }
        input[type="range"] {
            width: 100%;
            height: 8px;
            -webkit-appearance: none;
            background: #e9ecef;
            border-radius: 4px;
            outline: none;
        }
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: white;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            border: 2px solid #3498db;
        }
        
        /* 波形显示 */
        .waveform-display {
            width: 100%;
            height: 120px;
            background: #f8f9fa;
            border-radius: 12px;
            margin: 20px 0;
            position: relative;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }
        .waveform-canvas {
            width: 100%;
            height: 100%;
        }
        
        /* 频率标记 */
        .frequency-markers {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .frequency-marker {
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #dee2e6;
            text-align: center;
            min-width: 70px;
        }
        .frequency-marker:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }
        .frequency-marker.active {
            background: #007bff;
            border-color: #0056b3;
            color: white;
        }
        .frequency-marker.active .frequency-marker-value,
        .frequency-marker.active .frequency-marker-label {
            color: white;
        }
        .frequency-marker-value {
            font-weight: bold;
            color: #3498db;
        }
        .frequency-marker-label {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        /* 环绕声测试 */
        .surround-test-area {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }
        .sound-source-controls {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .sound-btn {
            padding: 12px 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px solid #dee2e6;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 120px;
            justify-content: center;
            color: #495057;
        }
        .sound-btn:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }
        .sound-btn.active {
            background: #007bff;
            border-color: #0056b3;
            color: white;
        }
        
        .surround-layout {
            width: 100%;
            height: 450px;
            background: #f8f9fa;
            border-radius: 12px;
            position: relative;
            margin: 20px 0;
            overflow: hidden;
            border: 2px dashed #dee2e6;
            cursor: crosshair;
        }
        .listener-position {
            position: absolute;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            justify-content: center;
            align-items: center;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 2px 10px rgba(52, 152, 219, 0.4);
            z-index: 10;
            pointer-events: none;
        }
        .listener-icon {
            font-size: 1.5rem;
            color: white;
        }
        .sound-source {
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: grab;
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.4);
            z-index: 20;
            transform: translate(-50%, -50%);
            transition: all 0.2s;
        }
        .sound-source:active {
            cursor: grabbing;
            transform: translate(-50%, -50%) scale(0.95);
        }
        .sound-source.active {
            background: linear-gradient(135deg, #fd7e14, #e74c3c);
            box-shadow: 0 0 15px rgba(231, 76, 60, 0.6);
            animation: pulse 1.5s infinite;
            transform: translate(-50%, -50%);
        }
        .sound-source-icon {
            font-size: 1.2rem;
            color: white;
        }
        
        .position-info {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
            border: 1px solid #dee2e6;
        }
        .position-data {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }
        .position-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #3498db;
        }
        .position-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .auto-surround-controls {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            width: 100%;
            flex-wrap: wrap;
        }
        .surround-speed-control {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 10px;
            flex: 1;
            min-width: 200px;
            border: 1px solid #dee2e6;
        }
        .speed-label {
            font-size: 0.9rem;
            color: #6c757d;
            min-width: 70px;
        }
        .speed-value {
            font-size: 1.1rem;
            font-weight: bold;
            color: #3498db;
            min-width: 40px;
            text-align: center;
        }
        
        .test-sound-controls {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
            width: 100%;
        }
        .test-sound-option {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid #dee2e6;
        }
        .test-sound-option:hover {
            background: #e9ecef;
            transform: translateY(-3px);
            border-color: #adb5bd;
        }
        .test-sound-option.active {
            background: #007bff;
            border-color: #0056b3;
        }
        .test-sound-option.active .sound-icon,
        .test-sound-option.active .sound-name,
        .test-sound-option.active .sound-description {
            color: white;
        }
        .sound-icon {
            font-size: 2rem;
            color: #3498db;
            margin-bottom: 10px;
        }
        .sound-name {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .sound-description {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        /* 控制按钮 */
        .controls {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        button {
            padding: 14px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            flex: 1;
            min-width: 160px;
        }
        .btn-start {
            background: #28a745;
            color: white;
        }
        .btn-start:hover {
            background: #218838;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4);
        }
        .btn-reset {
            background: #ffc107;
            color: #212529;
        }
        .btn-reset:hover {
            background: #e0a800;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.4);
        }
        .btn-sweep {
            background: #17a2b8;
            color: white;
        }
        .btn-sweep:hover {
            background: #138496;
            box-shadow: 0 2px 8px rgba(23, 162, 184, 0.4);
        }
        .btn-surround {
            background: #6f42c1;
            color: white;
        }
        .btn-surround:hover {
            background: #5a32a3;
            box-shadow: 0 2px 8px rgba(111, 66, 193, 0.4);
        }
        .btn-position {
            background: #3498db;
            color: white;
        }
        .btn-position:hover {
            background: #2980b9;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.4);
        }
        
        /* 统计卡片 */
        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #dee2e6;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .stat-value {
            font-size: 2.8rem;
            font-weight: 700;
            margin: 10px 0;
            color: #3498db;
        }
        .stat-label {
            font-size: 1rem;
            color: #6c757d;
        }
        .stat-unit {
            font-size: 1.2rem;
            color: #6c757d;
            margin-left: 5px;
        }
        
        /* 信息卡片 */
        .info-section {
            margin-top: 40px;
            width: 100%;
            max-width: 1400px;
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #dee2e6;
        }
        .info-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        .info-card-dark {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            border-left: 5px solid #3498db;
        }
        .info-card-dark h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #495057;
        }
        .info-card-dark p {
            color: #6c757d;
            line-height: 1.6;
        }
        
        /* 状态指示器 */
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-good {
            background-color: #28a745;
            box-shadow: 0 0 6px #28a745;
        }
        .status-warning {
            background-color: #ffc107;
            box-shadow: 0 0 6px #ffc107;
        }
        .status-bad {
            background-color: #dc3545;
            box-shadow: 0 0 6px #dc3545;
        }
        .test-status {
            display: flex;
            align-items: center;
            font-size: 1rem;
            margin-top: 10px;
            color: #495057;
        }
        
        /* 音量控制 */
        .volume-control {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
            width: 100%;
        }
        .volume-label {
            font-size: 1rem;
            color: #6c757d;
            min-width: 70px;
        }
        .volume-slider {
            flex: 1;
        }
        .volume-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #3498db;
            min-width: 40px;
            text-align: right;
        }
        
        /* 声波效果 */
        .sound-wave {
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(52, 152, 219, 0.3);
            pointer-events: none;
            z-index: 5;
        }
        .surround-path {
            position: absolute;
            border: 2px dashed rgba(52, 152, 219, 0.5);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        }
        
        /* 动画 */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(52, 152, 219, 0); }
            100% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0); }
        }
        @keyframes sweep {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .sweep-active {
            background: linear-gradient(90deg, #e9ecef, #3498db, #28a745, #e9ecef);
            background-size: 400% 100%;
            animation: sweep 3s linear infinite;
        }
        
        /* 响应式 */
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            .testing-area, .stats-panel {
                max-width: 100%;
            }
            .speaker-layout {
                height: 250px;
            }
            .speaker {
                width: 60px;
                height: 60px;
                font-size: 0.8rem;
            }
            .speaker.subwoofer {
                width: 75px;
                height: 75px;
            }
            .surround-layout {
                height: 350px;
            }
            .listener-position {
                width: 50px;
                height: 50px;
            }
            .sound-source {
                width: 40px;
                height: 40px;
            }
            .position-info {
                flex-direction: column;
                gap: 10px;
            }
            .auto-surround-controls {
                flex-direction: column;
            }
            .test-sound-controls {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }
        
        /* 滚动条 */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body>
    <?php include '../templates/header.php'; ?>
    
    <div class="tool-container">
        <a href="../index.php" class="back-btn">返回首页</a>
        <h1>🔊 音响全面测试工具</h1>
        <p class="subtitle">声道测试、频响范围分析与环绕声定位测试</p>
        
        <div class="compatibility-note" id="compatibilityNote">
            <p><i class="fas fa-exclamation-triangle"></i> 您的浏览器需要支持 Web Audio API 以获得最佳测试效果。建议使用最新版 Chrome、Edge 或 Firefox。</p>
        </div>
        
        <div class="tabs">
            <div class="tab active" data-tab="channel-test">
                <i class="fas fa-surround"></i> 声道配置测试
            </div>
            <div class="tab" data-tab="frequency-test">
                <i class="fas fa-wave-square"></i> 频响范围测试
            </div>
            <div class="tab" data-tab="surround-test">
                <i class="fas fa-compass"></i> 环绕声定位测试
            </div>
        </div>
        
        <!-- 声道测试标签页 -->
        <div class="tab-content active" id="channel-test-tab">
            <div class="main-content">
                <div class="testing-area">
                    <div class="panel-title">
                        <i class="fas fa-sliders-h"></i> 声道配置测试
                    </div>
                    
                    <div class="channel-config">
                        <div class="config-btn active" data-config="2.0">
                            <i class="fas fa-volume-down"></i> 2.0 立体声
                        </div>
                        <div class="config-btn" data-config="2.1">
                            <i class="fas fa-volume-down"></i> 2.1 立体声 + 低音炮
                        </div>
                        <div class="config-btn" data-config="5.1">
                            <i class="fas fa-volume-up"></i> 5.1 环绕声
                        </div>
                        <div class="config-btn" data-config="7.1">
                            <i class="fas fa-volume-up"></i> 7.1 环绕声
                        </div>
                    </div>
                    
                    <div class="speaker-layout" id="speakerLayout">
                        <!-- 扬声器位置将通过JavaScript动态生成 -->
                    </div>
                    
                    <div class="volume-control">
                        <div class="volume-label">
                            <i class="fas fa-volume-up"></i> 音量:
                        </div>
                        <input type="range" min="0" max="100" value="50" class="volume-slider" id="volumeSlider">
                        <div class="volume-value" id="volumeValue">50%</div>
                    </div>
                    
                    <div class="controls">
                        <button class="btn-start" id="testAllBtn">
                            <i class="fas fa-play"></i> 测试所有声道
                        </button>
                        <button class="btn-reset" id="resetChannelTestBtn">
                            <i class="fas fa-redo"></i> 重置测试
                        </button>
                    </div>
                    
                    <div class="test-status">
                        <span class="status-indicator status-good" id="channelTestStatus"></span>
                        <span id="channelTestStatusText">状态: 准备就绪</span>
                    </div>
                </div>
                
                <div class="stats-panel">
                    <div class="panel-title">
                        <i class="fas fa-clipboard-check"></i> 声道测试结果
                    </div>
                    
                    <div class="stat-cards">
                        <div class="stat-card">
                            <div class="stat-label">声道配置</div>
                            <div class="stat-value" id="currentConfig">2.0</div>
                            <div class="stat-label">当前设置</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-label">已测试声道</div>
                            <div class="stat-value" id="channelsTested">0</div>
                            <div class="stat-label">数量</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-label">测试进度</div>
                            <div class="stat-value" id="channelTestProgress">0%</div>
                            <div class="stat-label">完成度</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-label">综合评分</div>
                            <div class="stat-value" id="channelScore">100</div>
                            <div class="stat-label">分数 <span class="stat-unit">(满分100)</span></div>
                        </div>
                    </div>
                    
                    <div class="info-card-dark" style="margin-top: 20px;">
                        <h3><i class="fas fa-diagnoses"></i> 声道健康诊断</h3>
                        <p id="channelDiagnosisText">所有声道工作正常，未检测到问题。</p>
                    </div>
                    
                    <div class="info-card-dark" style="margin-top: 15px;">
                        <h3><i class="fas fa-info-circle"></i> 测试说明</h3>
                        <p id="channelTestDescription">点击"测试所有声道"按钮将按顺序测试每个声道。您也可以点击布局图中的单个扬声器图标进行独立测试。</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 频响测试标签页 -->
        <div class="tab-content" id="frequency-test-tab">
            <div class="main-content">
                <div class="testing-area">
                    <div class="panel-title">
                        <i class="fas fa-wave-square"></i> 频响范围测试
                    </div>
                    
                    <div class="frequency-slider-container">
                        <div class="slider-header">
                            <div>
                                <div class="frequency-label">测试频率</div>
                                <div class="frequency-value" id="frequencyValue">440</div>
                            </div>
                            <div class="frequency-unit">Hz</div>
                        </div>
                        
                        <div class="slider-container">
                            <input type="range" min="20" max="20000" value="440" step="1" class="frequency-slider" id="frequencySlider">
                        </div>
                        
                        <div class="slider-labels">
                            <span>20 Hz (低音)</span>
                            <span>1 kHz (中音)</span>
                            <span>20 kHz (高音)</span>
                        </div>
                    </div>
                    
                    <div class="waveform-display">
                        <canvas class="waveform-canvas" id="waveformCanvas"></canvas>
                    </div>
                    
                    <div class="frequency-markers">
                        <div class="frequency-marker" data-frequency="20">
                            <div class="frequency-marker-value">20 Hz</div>
                            <div class="frequency-marker-label">超低音</div>
                        </div>
                        <div class="frequency-marker" data-frequency="100">
                            <div class="frequency-marker-value">100 Hz</div>
                            <div class="frequency-marker-label">低音</div>
                        </div>
                        <div class="frequency-marker" data-frequency="440">
                            <div class="frequency-marker-value">440 Hz</div>
                            <div class="frequency-marker-label">标准A音</div>
                        </div>
                        <div class="frequency-marker" data-frequency="1000">
                            <div class="frequency-marker-value">1 kHz</div>
                            <div class="frequency-marker-label">中音</div>
                        </div>
                        <div class="frequency-marker" data-frequency="10000">
                            <div class="frequency-marker-value">10 kHz</div>
                            <div class="frequency-marker-label">高音</div>
                        </div>
                    </div>
                    
                    <div class="volume-control">
                        <div class="volume-label">
                            <i class="fas fa-volume-up"></i> 音量:
                        </div>
                        <input type="range" min="0" max="100" value="50" class="volume-slider" id="freqVolumeSlider">
                        <div class="volume-value" id="freqVolumeValue">50%</div>
                    </div>
                    
                    <div class="controls">
                        <button class="btn-start" id="playToneBtn">
                            <i class="fas fa-play"></i> 播放音调
                        </button>
                        <button class="btn-sweep" id="sweepBtn">
                            <i class="fas fa-sync-alt"></i> 自动扫频
                        </button>
                        <button class="btn-reset" id="resetFreqTestBtn">
                            <i class="fas fa-redo"></i> 重置测试
                        </button>
                    </div>
                    
                    <div class="test-status">
                        <span class="status-indicator status-good" id="frequencyTestStatus"></span>
                        <span id="frequencyTestStatusText">状态: 准备就绪</span>
                    </div>
                </div>
                
                <div class="stats-panel">
                    <div class="panel-title">
                        <i class="fas fa-chart-line"></i> 频响测试结果
                    </div>
                    
                    <div class="stat-cards">
                        <div class="stat-card">
                            <div class="stat-label">当前频率</div>
                            <div class="stat-value" id="currentFrequency">440</div>
                            <div class="stat-label">Hz <span class="stat-unit">(赫兹)</span></div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-label">波长</div>
                            <div class="stat-value" id="wavelength">0.78</div>
                            <div class="stat-label">米 <span class="stat-unit">(空气中)</span></div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-label">音高</div>
                            <div class="stat-value" id="pitchNote">A4</div>
                            <div class="stat-label">音符 <span class="stat-unit">(标准音)</span></div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-label">人耳敏感度</div>
                            <div class="stat-value" id="sensitivity">100%</div>
                            <div class="stat-label">相对值 <span class="stat-unit">(1kHz基准)</span></div>
                        </div>
                    </div>
                    
                    <div class="info-card-dark" style="margin-top: 20px;">
                        <h3><i class="fas fa-diagnoses"></i> 频响范围诊断</h3>
                        <p id="frequencyDiagnosisText">频率响应正常，可播放全频段声音。</p>
                    </div>
                    
                    <div class="info-card-dark" style="margin-top: 15px;">
                        <h3><i class="fas fa-info-circle"></i> 频率测试说明</h3>
                        <p>人耳可听范围大约为20Hz到20kHz。随着年龄增长，高频听力会逐渐下降。使用扫频功能可以测试音响系统的全频响应能力。</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 环绕声测试标签页 -->
        <div class="tab-content" id="surround-test-tab">
            <div class="main-content">
                <div class="testing-area" style="max-width: 100%;">
                    <div class="panel-title">
                        <i class="fas fa-compass"></i> 环绕声定位测试
                    </div>
                    
                    <div class="surround-test-area">
                        <div class="sound-source-controls">
                            <div class="sound-btn active" data-source="single">
                                <i class="fas fa-bullseye"></i> 单声源定位
                            </div>
                            <div class="sound-btn" data-source="multi">
                                <i class="fas fa-layer-group"></i> 多声源测试
                            </div>
                        </div>
                        
                        <div class="surround-layout" id="surroundLayout">
                            <div class="listener-position">
                                <i class="fas fa-user listener-icon"></i>
                            </div>
                            <div class="sound-source" id="soundSource" style="left: 70%; top: 30%;">
                                <i class="fas fa-volume-up sound-source-icon"></i>
                            </div>
                            <div class="surround-path" id="surroundPath" style="display: none;"></div>
                            <div class="sound-wave" id="soundWave" style="display: none;"></div>
                        </div>
                        
                        <div class="position-info">
                            <div class="position-data">
                                <div class="position-value" id="positionX">30°</div>
                                <div class="position-label">水平角度</div>
                            </div>
                            <div class="position-data">
                                <div class="position-value" id="positionDistance">5m</div>
                                <div class="position-label">距离</div>
                            </div>
                            <div class="position-data">
                                <div class="position-value" id="positionElevation">0°</div>
                                <div class="position-label">垂直角度</div>
                            </div>
                        </div>
                        
                        <div class="auto-surround-controls">
                            <div class="surround-speed-control">
                                <div class="speed-label">环绕速度:</div>
                                <input type="range" min="1" max="10" value="3" class="volume-slider" id="surroundSpeedSlider">
                                <div class="speed-value" id="surroundSpeedValue">3</div>
                            </div>
                            <div class="surround-speed-control">
                                <div class="speed-label">环绕半径:</div>
                                <input type="range" min="20" max="80" value="40" class="volume-slider" id="surroundRadiusSlider">
                                <div class="speed-value" id="surroundRadiusValue">40%</div>
                            </div>
                        </div>
                        
                        <div class="test-sound-controls">
                            <div class="test-sound-option active" data-sound="sweep">
                                <div class="sound-icon">
                                    <i class="fas fa-wave-square"></i>
                                </div>
                                <div class="sound-name">扫频音</div>
                                <div class="sound-description">频率变化的测试音，定位清晰</div>
                            </div>
                            <div class="test-sound-option" data-sound="footsteps">
                                <div class="sound-icon">
                                    <i class="fas fa-walking"></i>
                                </div>
                                <div class="sound-name">脚步声</div>
                                <div class="sound-description">急促脚步声，测试环境音效</div>
                            </div>
                            <div class="test-sound-option" data-sound="gunshot">
                                <div class="sound-icon">
                                    <i class="fas fa-gun"></i>
                                </div>
                                <div class="sound-name">枪声</div>
                                <div class="sound-description">5连发枪声，测试动态响应</div>
                            </div>
                            <div class="test-sound-option" data-sound="beep">
                                <div class="sound-icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="sound-name">提示音</div>
                                <div class="sound-description">简短提示音，测试瞬态响应</div>
                            </div>
                        </div>
                        
                        <div class="volume-control">
                            <div class="volume-label">
                                <i class="fas fa-volume-up"></i> 音量:
                            </div>
                            <input type="range" min="0" max="100" value="50" class="volume-slider" id="surroundVolumeSlider">
                            <div class="volume-value" id="surroundVolumeValue">50%</div>
                        </div>
                    </div>
                    
                    <div class="controls">
                        <button class="btn-position" id="playPositionBtn">
                            <i class="fas fa-play"></i> 播放位置音效
                        </button>
                        <button class="btn-surround" id="startSurroundBtn">
                            <i class="fas fa-sync-alt"></i> 开始环绕测试
                        </button>
                        <button class="btn-reset" id="resetSurroundTestBtn">
                            <i class="fas fa-redo"></i> 重置测试
                        </button>
                    </div>
                    
                    <div class="test-status">
                        <span class="status-indicator status-good" id="surroundTestStatus"></span>
                        <span id="surroundTestStatusText">状态: 准备就绪</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-title">
                <i class="fas fa-info-circle"></i> 音响测试说明
            </div>
            
            <div class="info-grid">
                <div class="info-card-dark">
                    <h3>声道配置测试</h3>
                    <p>测试2.0、2.1、5.1、7.1等不同声道配置。每个声道会依次播放测试音，确保所有扬声器工作正常且位置正确。</p>
                </div>
                
                <div class="info-card-dark">
                    <h3>频响范围测试</h3>
                    <p>测试音响系统的频率响应范围（20Hz-20kHz）。可播放特定频率音调或进行自动扫频，检测音响能否准确还原不同频率的声音。</p>
                </div>
                
                <div class="info-card-dark">
                    <h3>环绕声定位测试</h3>
                    <p>测试音响系统的声场定位能力。可以手动拖动声源测试特定位置，或使用自动环绕测试评估系统的全方位声场表现。</p>
                </div>
                
                <div class="info-card-dark">
                    <h3>测试建议</h3>
                    <p>1. 测试时使用适中的音量<br>2. 在安静环境中进行测试<br>3. 环绕测试时注意声源移动轨迹<br>4. 使用不同类型的测试音效进行全面评估</p>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../templates/footer.php'; ?>

    <script>
        // 检测浏览器兼容性
        const compatibilityNote = document.getElementById('compatibilityNote');
        const supportsWebAudio = typeof AudioContext !== 'undefined' || 
                                 typeof webkitAudioContext !== 'undefined';
        
        if (!supportsWebAudio) {
            compatibilityNote.classList.add('show');
        }
        
        // ==================== 全局变量 ====================
        let audioContext = null;
        let oscillator = null;
        let gainNode = null;
        let pannerNode = null;
        let isPlaying = false;
        let currentConfig = '2.0';
        let currentChannelIndex = 0;
        let channelTestInterval = null;
        let sweepInterval = null;
        let isSweeping = false;
        let waveformCanvas, waveformCtx;
        
        // 环绕声测试变量
        let isSurroundActive = false;
        let surroundInterval = null;
        let surroundAngle = 0;
        let surroundLaps = 0;
        let surroundStartTime = 0;
        let soundSourceDragging = false;
        let currentSoundType = 'sweep';
        let surroundTestActive = false;
        
        // 声道配置定义
        const channelConfigs = {
            '2.0': {
                name: '2.0 立体声',
                channels: [
                    { id: 'L', name: '左声道', x: 25, y: 50, pan: -1 },
                    { id: 'R', name: '右声道', x: 75, y: 50, pan: 1 }
                ]
            },
            '2.1': {
                name: '2.1 立体声 + 低音炮',
                channels: [
                    { id: 'L', name: '左声道', x: 25, y: 50, pan: -1 },
                    { id: 'R', name: '右声道', x: 75, y: 50, pan: 1 },
                    { id: 'SW', name: '低音炮', x: 50, y: 80, pan: 0, isSubwoofer: true }
                ]
            },
            '5.1': {
                name: '5.1 环绕声',
                channels: [
                    { id: 'FL', name: '前置左', x: 25, y: 30, pan: -0.8 },
                    { id: 'FR', name: '前置右', x: 75, y: 30, pan: 0.8 },
                    { id: 'C', name: '中置', x: 50, y: 25, pan: 0 },
                    { id: 'SW', name: '低音炮', x: 50, y: 80, pan: 0, isSubwoofer: true },
                    { id: 'SL', name: '环绕左', x: 15, y: 70, pan: -0.5 },
                    { id: 'SR', name: '环绕右', x: 85, y: 70, pan: 0.5 }
                ]
            },
            '7.1': {
                name: '7.1 环绕声',
                channels: [
                    { id: 'FL', name: '前置左', x: 25, y: 30, pan: -1 },
                    { id: 'FR', name: '前置右', x: 75, y: 30, pan: 1 },
                    { id: 'C', name: '中置', x: 50, y: 25, pan: 0 },
                    { id: 'SW', name: '低音炮', x: 50, y: 80, pan: 0, isSubwoofer: true },
                    { id: 'SL', name: '侧环绕左', x: 15, y: 60, pan: -0.7 },
                    { id: 'SR', name: '侧环绕右', x: 85, y: 60, pan: 0.7 },
                    { id: 'RL', name: '后环绕左', x: 25, y: 80, pan: -0.3 },
                    { id: 'RR', name: '后环绕右', x: 75, y: 80, pan: 0.3 }
                ]
            }
        };
        
        // ==================== 初始化函数 ====================
        function init() {
            console.log('音响测试工具初始化...');
            
            try {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                audioContext = new AudioContextClass();
                console.log('AudioContext 创建成功');
            } catch (e) {
                console.error('无法创建 AudioContext:', e);
                return;
            }
            
            initTabs();
            initChannelTest();
            initFrequencyTest();
            initSurroundTest();
            initWaveformDisplay();
            
            updateChannelTestStatus('good', '准备就绪');
            updateFrequencyTestStatus('good', '准备就绪');
            updateSurroundTestStatus('good', '准备就绪');
            
            console.log('初始化完成！');
        }
        
        function initTabs() {
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                    });
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                    
                    stopAllAudio();
                    stopSurroundTest();
                });
            });
        }
        
        function initChannelTest() {
            document.querySelectorAll('.config-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const config = this.getAttribute('data-config');
                    setChannelConfig(config);
                });
            });
            
            document.getElementById('testAllBtn').addEventListener('click', startChannelTest);
            document.getElementById('resetChannelTestBtn').addEventListener('click', resetChannelTest);
            
            const volumeSlider = document.getElementById('volumeSlider');
            const volumeValue = document.getElementById('volumeValue');
            
            volumeSlider.addEventListener('input', function() {
                volumeValue.textContent = `${this.value}%`;
            });
            
            setChannelConfig('2.0');
        }
        
        function initFrequencyTest() {
            const frequencySlider = document.getElementById('frequencySlider');
            const frequencyValue = document.getElementById('frequencyValue');
            
            frequencySlider.addEventListener('input', function() {
                const freq = parseInt(this.value);
                frequencyValue.textContent = freq;
                updateFrequencyStats(freq);
                drawWaveform(freq);
                
                if (isPlaying && !isSweeping) {
                    updateOscillatorFrequency(freq);
                }
            });
            
            document.querySelectorAll('.frequency-marker').forEach(marker => {
                marker.addEventListener('click', function() {
                    const freq = parseInt(this.getAttribute('data-frequency'));
                    frequencySlider.value = freq;
                    frequencyValue.textContent = freq;
                    updateFrequencyStats(freq);
                    drawWaveform(freq);
                    
                    document.querySelectorAll('.frequency-marker').forEach(m => {
                        m.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    if (isPlaying && !isSweeping) {
                        updateOscillatorFrequency(freq);
                    }
                });
            });
            
            const freqVolumeSlider = document.getElementById('freqVolumeSlider');
            const freqVolumeValue = document.getElementById('freqVolumeValue');
            
            freqVolumeSlider.addEventListener('input', function() {
                freqVolumeValue.textContent = `${this.value}%`;
                if (gainNode) {
                    gainNode.gain.value = this.value / 100 * 0.5;
                }
            });
            
            document.getElementById('playToneBtn').addEventListener('click', playTone);
            document.getElementById('sweepBtn').addEventListener('click', toggleSweep);
            document.getElementById('resetFreqTestBtn').addEventListener('click', resetFrequencyTest);
            
            updateFrequencyStats(440);
            drawWaveform(440);
        }
        
        function initSurroundTest() {
            document.querySelectorAll('.sound-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const mode = this.getAttribute('data-source');
                    setSoundSourceMode(mode);
                });
            });
            
            document.querySelectorAll('.test-sound-option').forEach(option => {
                option.addEventListener('click', function() {
                    const soundType = this.getAttribute('data-sound');
                    setTestSoundType(soundType);
                });
            });
            
            const speedSlider = document.getElementById('surroundSpeedSlider');
            const speedValue = document.getElementById('surroundSpeedValue');
            const radiusSlider = document.getElementById('surroundRadiusSlider');
            const radiusValue = document.getElementById('surroundRadiusValue');
            const volumeSlider = document.getElementById('surroundVolumeSlider');
            const volumeValue = document.getElementById('surroundVolumeValue');
            
            speedSlider.addEventListener('input', function() {
                speedValue.textContent = this.value;
            });
            
            radiusSlider.addEventListener('input', function() {
                radiusValue.textContent = `${this.value}%`;
                updateSurroundPath();
            });
            
            volumeSlider.addEventListener('input', function() {
                volumeValue.textContent = `${this.value}%`;
            });
            
            document.getElementById('playPositionBtn').addEventListener('click', playPositionSound);
            document.getElementById('startSurroundBtn').addEventListener('click', toggleSurroundTest);
            document.getElementById('resetSurroundTestBtn').addEventListener('click', resetSurroundTest);
            
            initSoundSourceDrag();
            
            updatePositionInfo();
            updateSurroundPath();
        }
        
        function initWaveformDisplay() {
            waveformCanvas = document.getElementById('waveformCanvas');
            waveformCtx = waveformCanvas.getContext('2d');
            
            function resizeCanvas() {
                waveformCanvas.width = waveformCanvas.offsetWidth;
                waveformCanvas.height = waveformCanvas.offsetHeight;
                drawWaveform(parseInt(document.getElementById('frequencySlider').value));
            }
            
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);
        }
        
        // ==================== 声道测试功能 ====================
        function setChannelConfig(config) {
            currentConfig = config;
            
            document.querySelectorAll('.config-btn').forEach(btn => {
                if (btn.getAttribute('data-config') === config) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            
            document.getElementById('currentConfig').textContent = config;
            generateSpeakerLayout(config);
            resetChannelTest();
        }
        
        function generateSpeakerLayout(config) {
            const layout = document.getElementById('speakerLayout');
            layout.innerHTML = '';
            
            const channels = channelConfigs[config].channels;
            
            channels.forEach((channel, index) => {
                const speaker = document.createElement('div');
                speaker.className = `speaker ${channel.isSubwoofer ? 'subwoofer' : ''}`;
                speaker.id = `speaker-${channel.id}`;
                speaker.style.left = `${channel.x}%`;
                speaker.style.top = `${channel.y}%`;
                speaker.setAttribute('data-index', index);
                speaker.setAttribute('data-pan', channel.pan);
                
                speaker.innerHTML = `
                    <i class="fas fa-volume-up"></i>
                    <div class="speaker-label">${channel.name}</div>
                `;
                
                speaker.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-index'));
                    testSingleChannel(idx);
                });
                
                layout.appendChild(speaker);
            });
            
            document.getElementById('channelsTested').textContent = '0';
            document.getElementById('channelTestProgress').textContent = '0%';
            document.getElementById('channelScore').textContent = '100';
        }
        
        function startChannelTest() {
            stopAllAudio();
            
            currentChannelIndex = 0;
            updateChannelTestStatus('warning', '测试进行中...');
            
            resetAllSpeakers();
            testNextChannel();
        }
        
        function testNextChannel() {
            const channels = channelConfigs[currentConfig].channels;
            
            if (currentChannelIndex >= channels.length) {
                playAllChannelsCompletion();
                return;
            }
            
            resetAllSpeakers();
            
            const currentSpeaker = document.getElementById(`speaker-${channels[currentChannelIndex].id}`);
            if (currentSpeaker) {
                currentSpeaker.classList.add('active');
            }
            
            playTestTone(channels[currentChannelIndex].pan);
            
            const progress = Math.round((currentChannelIndex + 1) / channels.length * 100);
            document.getElementById('channelTestProgress').textContent = `${progress}%`;
            document.getElementById('channelsTested').textContent = currentChannelIndex + 1;
            
            channelTestInterval = setTimeout(() => {
                currentChannelIndex++;
                testNextChannel();
            }, 1500);
        }
        
        function playAllChannelsCompletion() {
            clearTimeout(channelTestInterval);
            
            const channels = channelConfigs[currentConfig].channels;
            channels.forEach(channel => {
                const speaker = document.getElementById(`speaker-${channel.id}`);
                if (speaker) {
                    speaker.classList.add('active');
                }
            });
            
            playAllChannelsSound();
            
            updateChannelTestStatus('warning', '播放结束提示音...');
        }
        
        function playAllChannelsSound() {
            if (!audioContext) return;
            
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }
            
            const now = audioContext.currentTime;
            const channels = channelConfigs[currentConfig].channels;
            
            channels.forEach((channel, index) => {
                const startTime = now + index * 0.05;
                
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                const pannerNode = audioContext.createStereoPanner();
                
                oscillator.type = 'sine';
                oscillator.frequency.value = 440 + (index * 50);
                
                pannerNode.pan.value = channel.pan;
                
                gainNode.gain.setValueAtTime(0, startTime);
                gainNode.gain.linearRampToValueAtTime(0.2, startTime + 0.1);
                gainNode.gain.linearRampToValueAtTime(0.2, startTime + 0.5);
                gainNode.gain.linearRampToValueAtTime(0, startTime + 0.6);
                
                oscillator.connect(gainNode);
                gainNode.connect(pannerNode);
                pannerNode.connect(audioContext.destination);
                
                oscillator.start(startTime);
                oscillator.stop(startTime + 0.6);
            });
            
            setTimeout(() => {
                let flashCount = 0;
                const flashInterval = setInterval(() => {
                    const channels = channelConfigs[currentConfig].channels;
                    channels.forEach(channel => {
                        const speaker = document.getElementById(`speaker-${channel.id}`);
                        if (speaker) {
                            if (flashCount % 2 === 0) {
                                speaker.classList.add('active');
                            } else {
                                speaker.classList.remove('active');
                            }
                        }
                    });
                    
                    flashCount++;
                    if (flashCount >= 6) {
                        clearInterval(flashInterval);
                        resetAllSpeakers();
                        updateChannelTestStatus('good', '测试完成！');
                        
                        document.getElementById('channelDiagnosisText').textContent = 
                            '所有声道测试完成，未检测到问题。';
                        document.getElementById('channelScore').textContent = '100';
                    }
                }, 300);
            }, 800);
        }
        
        function testSingleChannel(index) {
            stopAllAudio();
            
            const channels = channelConfigs[currentConfig].channels;
            if (index < 0 || index >= channels.length) return;
            
            resetAllSpeakers();
            
            const currentSpeaker = document.getElementById(`speaker-${channels[index].id}`);
            if (currentSpeaker) {
                currentSpeaker.classList.add('active');
            }
            
            playTestTone(channels[index].pan);
            
            updateChannelTestStatus('warning', `正在测试 ${channels[index].name}...`);
            
            setTimeout(() => {
                currentSpeaker.classList.remove('active');
                updateChannelTestStatus('good', '准备就绪');
            }, 2000);
        }
        
        function playTestTone(panValue) {
            if (!audioContext) return;
            
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }
            
            oscillator = audioContext.createOscillator();
            gainNode = audioContext.createGain();
            pannerNode = audioContext.createStereoPanner();
            
            oscillator.type = 'sine';
            oscillator.frequency.value = 440;
            
            pannerNode.pan.value = panValue;
            
            const now = audioContext.currentTime;
            gainNode.gain.setValueAtTime(0, now);
            gainNode.gain.linearRampToValueAtTime(0.3, now + 0.1);
            gainNode.gain.linearRampToValueAtTime(0.3, now + 0.9);
            gainNode.gain.linearRampToValueAtTime(0, now + 1.0);
            
            oscillator.connect(gainNode);
            gainNode.connect(pannerNode);
            pannerNode.connect(audioContext.destination);
            
            oscillator.start(now);
            oscillator.stop(now + 1.0);
            
            isPlaying = true;
        }
        
        function resetChannelTest() {
            clearTimeout(channelTestInterval);
            stopAllAudio();
            resetAllSpeakers();
            
            currentChannelIndex = 0;
            
            document.getElementById('channelsTested').textContent = '0';
            document.getElementById('channelTestProgress').textContent = '0%';
            document.getElementById('channelScore').textContent = '100';
            
            updateChannelTestStatus('good', '准备就绪');
            document.getElementById('channelDiagnosisText').textContent = 
                '所有声道工作正常，未检测到问题。';
        }
        
        function resetAllSpeakers() {
            document.querySelectorAll('.speaker').forEach(speaker => {
                speaker.classList.remove('active');
            });
        }
        
        // ==================== 频响测试功能 ====================
        function playTone() {
            if (isPlaying) return;
            
            if (!audioContext) return;
            
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }
            
            oscillator = audioContext.createOscillator();
            gainNode = audioContext.createGain();
            
            const frequency = parseInt(document.getElementById('frequencySlider').value);
            
            oscillator.type = 'sine';
            oscillator.frequency.value = frequency;
            
            const volume = parseInt(document.getElementById('freqVolumeSlider').value) / 100;
            gainNode.gain.value = volume * 0.5;
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.start();
            isPlaying = true;
            
            updateFrequencyTestStatus('warning', '正在播放音调...');
            document.getElementById('playToneBtn').disabled = true;
            document.getElementById('sweepBtn').disabled = true;
        }
        
        function updateOscillatorFrequency(frequency) {
            if (oscillator && isPlaying) {
                oscillator.frequency.value = frequency;
            }
        }
        
        function toggleSweep() {
            if (isSweeping) {
                stopSweep();
            } else {
                startSweep();
            }
        }
        
        function startSweep() {
            if (isSweeping) return;
            
            isSweeping = true;
            
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }
            
            oscillator = audioContext.createOscillator();
            gainNode = audioContext.createGain();
            
            oscillator.type = 'sine';
            
            const volume = parseInt(document.getElementById('freqVolumeSlider').value) / 100;
            gainNode.gain.value = volume * 0.3;
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.start();
            isPlaying = true;
            
            let frequency = 20;
            oscillator.frequency.value = frequency;
            document.getElementById('frequencySlider').value = frequency;
            document.getElementById('frequencyValue').textContent = frequency;
            updateFrequencyStats(frequency);
            
            sweepInterval = setInterval(() => {
                frequency += 20;
                if (frequency >= 20000) {
                    frequency = 20;
                }
                
                oscillator.frequency.value = frequency;
                document.getElementById('frequencySlider').value = frequency;
                document.getElementById('frequencyValue').textContent = frequency;
                updateFrequencyStats(frequency);
                drawWaveform(frequency);
            }, 100);
            
            updateFrequencyTestStatus('warning', '扫频进行中...');
            document.getElementById('sweepBtn').classList.add('sweep-active');
            document.getElementById('sweepBtn').innerHTML = '<i class="fas fa-stop"></i> 停止扫频';
            document.getElementById('playToneBtn').disabled = true;
        }
        
        function stopSweep() {
            isSweeping = false;
            clearInterval(sweepInterval);
            stopAllAudio();
            document.getElementById('sweepBtn').classList.remove('sweep-active');
            document.getElementById('sweepBtn').innerHTML = '<i class="fas fa-sync-alt"></i> 自动扫频';
            document.getElementById('playToneBtn').disabled = false;
        }
        
        function resetFrequencyTest() {
            stopSweep();
            stopAllAudio();
            
            document.getElementById('frequencySlider').value = 440;
            document.getElementById('frequencyValue').textContent = 440;
            updateFrequencyStats(440);
            drawWaveform(440);
            
            updateFrequencyTestStatus('good', '准备就绪');
            document.getElementById('playToneBtn').disabled = false;
            document.getElementById('sweepBtn').disabled = false;
        }
        
        // ==================== 环绕声测试功能 ====================
        function initSoundSourceDrag() {
            const soundSource = document.getElementById('soundSource');
            const surroundLayout = document.getElementById('surroundLayout');
            
            soundSource.addEventListener('mousedown', startDrag);
            soundSource.addEventListener('touchstart', startDrag);
            
            function startDrag(e) {
                e.preventDefault();
                soundSourceDragging = true;
                soundSource.style.cursor = 'grabbing';
                
                const moveHandler = (e) => {
                    if (!soundSourceDragging) return;
                    
                    const rect = surroundLayout.getBoundingClientRect();
                    let clientX, clientY;
                    
                    if (e.type.includes('touch')) {
                        clientX = e.touches[0].clientX;
                        clientY = e.touches[0].clientY;
                    } else {
                        clientX = e.clientX;
                        clientY = e.clientY;
                    }
                    
                    let x = ((clientX - rect.left) / rect.width) * 100;
                    let y = ((clientY - rect.top) / rect.height) * 100;
                    
                    x = Math.max(5, Math.min(x, 95));
                    y = Math.max(5, Math.min(y, 95));
                    
                    soundSource.style.left = `${x}%`;
                    soundSource.style.top = `${y}%`;
                    
                    updatePositionInfo();
                    
                    if (isPlaying && surroundTestActive) {
                        updateSoundPosition();
                    }
                };
                
                const stopHandler = () => {
                    soundSourceDragging = false;
                    soundSource.style.cursor = 'grab';
                    
                    document.removeEventListener('mousemove', moveHandler);
                    document.removeEventListener('touchmove', moveHandler);
                    document.removeEventListener('mouseup', stopHandler);
                    document.removeEventListener('touchend', stopHandler);
                };
                
                document.addEventListener('mousemove', moveHandler);
                document.addEventListener('touchmove', moveHandler, { passive: false });
                document.addEventListener('mouseup', stopHandler);
                document.addEventListener('touchend', stopHandler);
            }
        }
        
        function setSoundSourceMode(mode) {
            document.querySelectorAll('.sound-btn').forEach(btn => {
                if (btn.getAttribute('data-source') === mode) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            
            if (mode === 'single') {
                document.getElementById('soundSource').style.display = 'flex';
            }
        }
        
        function setTestSoundType(soundType) {
            currentSoundType = soundType;
            
            document.querySelectorAll('.test-sound-option').forEach(option => {
                if (option.getAttribute('data-sound') === soundType) {
                    option.classList.add('active');
                } else {
                    option.classList.remove('active');
                }
            });
        }
        
        function updatePositionInfo() {
            const soundSource = document.getElementById('soundSource');
            const layout = document.getElementById('surroundLayout');
            
            if (!soundSource || !layout) return;
            
            const rect = layout.getBoundingClientRect();
            const sourceRect = soundSource.getBoundingClientRect();
            
            const sourceX = sourceRect.left + sourceRect.width / 2;
            const sourceY = sourceRect.top + sourceRect.height / 2;
            
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            
            const dx = sourceX - centerX;
            const dy = centerY - sourceY;
            let angle = Math.atan2(dy, dx) * (180 / Math.PI);
            
            const maxDistance = Math.min(rect.width, rect.height) / 2;
            const distance = Math.sqrt(dx * dx + dy * dy);
            const distancePercent = Math.min(100, Math.round((distance / maxDistance) * 100));
            
            const elevation = 0;
            
            document.getElementById('positionX').textContent = `${Math.round(angle)}°`;
            document.getElementById('positionDistance').textContent = `${distancePercent}%`;
            document.getElementById('positionElevation').textContent = `${elevation}°`;
        }
        
        function updateSurroundPath() {
            const path = document.getElementById('surroundPath');
            const radius = parseInt(document.getElementById('surroundRadiusSlider').value);
            
            if (!path) return;
            
            const size = radius * 2;
            path.style.width = `${size}%`;
            path.style.height = `${size}%`;
            path.style.left = `${50 - radius}%`;
            path.style.top = `${50 - radius}%`;
        }
        
        function playPositionSound() {
            stopAllAudio();
            
            if (!audioContext) return;
            
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }
            
            switch (currentSoundType) {
                case 'sweep':
                    playSweepSound();
                    break;
                case 'footsteps':
                    playFootstepSound();
                    break;
                case 'gunshot':
                    playGunshotSound();
                    break;
                case 'beep':
                    playBeepSound();
                    break;
            }
            
            updateSurroundTestStatus('warning', '正在播放位置音效...');
            createSoundWaveEffect();
        }
        
        function playSweepSound() {
            oscillator = audioContext.createOscillator();
            gainNode = audioContext.createGain();
            pannerNode = audioContext.createStereoPanner();
            
            oscillator.type = 'sine';
            
            const now = audioContext.currentTime;
            oscillator.frequency.setValueAtTime(200, now);
            oscillator.frequency.exponentialRampToValueAtTime(1000, now + 1.5);
            
            updateSoundPosition();
            
            gainNode.gain.setValueAtTime(0, now);
            gainNode.gain.linearRampToValueAtTime(0.3, now + 0.1);
            gainNode.gain.linearRampToValueAtTime(0.3, now + 1.4);
            gainNode.gain.linearRampToValueAtTime(0, now + 1.5);
            
            oscillator.connect(gainNode);
            gainNode.connect(pannerNode);
            pannerNode.connect(audioContext.destination);
            
            oscillator.start(now);
            oscillator.stop(now + 1.5);
            
            isPlaying = true;
        }
        
        function playFootstepSound() {
            const numberOfSteps = 8;
            const stepInterval = 0.15;
            const now = audioContext.currentTime;
            
            for (let i = 0; i < numberOfSteps; i++) {
                const stepTime = now + i * stepInterval;
                
                const bufferSize = audioContext.sampleRate * 0.08;
                const buffer = audioContext.createBuffer(1, bufferSize, audioContext.sampleRate);
                const data = buffer.getChannelData(0);
                
                let b0 = 0, b1 = 0, b2 = 0, b3 = 0, b4 = 0, b5 = 0, b6 = 0;
                for (let j = 0; j < bufferSize; j++) {
                    const white = Math.random() * 2 - 1;
                    b0 = 0.99886 * b0 + white * 0.0555179;
                    b1 = 0.99332 * b1 + white * 0.0750759;
                    b2 = 0.96900 * b2 + white * 0.1538520;
                    b3 = 0.86650 * b3 + white * 0.3104856;
                    b4 = 0.55000 * b4 + white * 0.5329522;
                    b5 = -0.7616 * b5 - white * 0.0168980;
                    data[j] = b0 + b1 + b2 + b3 + b4 + b5 + b6 + white * 0.5362;
                    data[j] *= 0.15;
                    b6 = white * 0.115926;
                }
                
                const noiseSource = audioContext.createBufferSource();
                noiseSource.buffer = buffer;
                
                const gainNode = audioContext.createGain();
                const pannerNode = audioContext.createStereoPanner();
                
                updatePannerPosition(pannerNode);
                
                gainNode.gain.setValueAtTime(0, stepTime);
                gainNode.gain.linearRampToValueAtTime(0.5, stepTime + 0.03);
                gainNode.gain.exponentialRampToValueAtTime(0.001, stepTime + 0.15);
                
                noiseSource.connect(gainNode);
                gainNode.connect(pannerNode);
                pannerNode.connect(audioContext.destination);
                
                noiseSource.start(stepTime);
                noiseSource.stop(stepTime + 0.15);
            }
            
            isPlaying = true;
            
            setTimeout(() => {
                isPlaying = false;
                updateSurroundTestStatus('good', '音效播放完成');
            }, numberOfSteps * stepInterval * 1000 + 200);
        }
        
        function playGunshotSound() {
            const now = audioContext.currentTime;
            const numberOfShots = 5;
            const shotInterval = 0.1;
            
            for (let shot = 0; shot < numberOfShots; shot++) {
                const shotTime = now + shot * shotInterval;
                
                const impulseDuration = 0.05;
                const bufferSize = audioContext.sampleRate * impulseDuration;
                const buffer = audioContext.createBuffer(1, bufferSize, audioContext.sampleRate);
                const data = buffer.getChannelData(0);
                
                for (let i = 0; i < bufferSize; i++) {
                    const t = i / bufferSize;
                    const variation = 0.8 + (Math.random() * 0.4);
                    data[i] = (Math.random() * 2 - 1) * variation;
                    data[i] *= Math.exp(-t * 15) * (1 - t);
                }
                
                const impulseSource = audioContext.createBufferSource();
                impulseSource.buffer = buffer;
                
                const gainNode = audioContext.createGain();
                const pannerNode = audioContext.createStereoPanner();
                
                updatePannerPosition(pannerNode);
                
                const volume = 0.7 - (shot * 0.1);
                gainNode.gain.setValueAtTime(volume, shotTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, shotTime + impulseDuration);
                
                impulseSource.connect(gainNode);
                gainNode.connect(pannerNode);
                pannerNode.connect(audioContext.destination);
                
                impulseSource.start(shotTime);
                impulseSource.stop(shotTime + impulseDuration);
                
                if (shot === 0 || shot === numberOfShots - 1) {
                    const echoTime = shotTime + 0.2;
                    const echoBufferSize = audioContext.sampleRate * 0.1;
                    const echoBuffer = audioContext.createBuffer(1, echoBufferSize, audioContext.sampleRate);
                    const echoData = echoBuffer.getChannelData(0);
                    
                    for (let i = 0; i < echoBufferSize; i++) {
                        const t = i / echoBufferSize;
                        echoData[i] = Math.random() * 2 - 1;
                        echoData[i] *= Math.exp(-t * 8) * (1 - t) * 0.3;
                    }
                    
                    const echoSource = audioContext.createBufferSource();
                    echoSource.buffer = echoBuffer;
                    
                    const echoGain = audioContext.createGain();
                    const echoPanner = audioContext.createStereoPanner();
                    
                    updatePannerPosition(echoPanner);
                    
                    echoGain.gain.setValueAtTime(0.2, echoTime);
                    echoGain.gain.exponentialRampToValueAtTime(0.001, echoTime + 0.2);
                    
                    echoSource.connect(echoGain);
                    echoGain.connect(echoPanner);
                    echoPanner.connect(audioContext.destination);
                    
                    echoSource.start(echoTime);
                    echoSource.stop(echoTime + 0.2);
                }
            }
            
            isPlaying = true;
            
            setTimeout(() => {
                isPlaying = false;
                updateSurroundTestStatus('good', '音效播放完成');
            }, (numberOfShots * shotInterval + 0.3) * 1000);
        }
        
        function playBeepSound() {
            oscillator = audioContext.createOscillator();
            gainNode = audioContext.createGain();
            pannerNode = audioContext.createStereoPanner();
            
            oscillator.type = 'sine';
            oscillator.frequency.value = 1000;
            
            updateSoundPosition();
            
            const now = audioContext.currentTime;
            gainNode.gain.setValueAtTime(0, now);
            gainNode.gain.linearRampToValueAtTime(0.4, now + 0.05);
            gainNode.gain.linearRampToValueAtTime(0.4, now + 0.15);
            gainNode.gain.linearRampToValueAtTime(0, now + 0.2);
            
            oscillator.connect(gainNode);
            gainNode.connect(pannerNode);
            pannerNode.connect(audioContext.destination);
            
            oscillator.start(now);
            oscillator.stop(now + 0.2);
            
            isPlaying = true;
            
            setTimeout(() => {
                isPlaying = false;
                updateSurroundTestStatus('good', '音效播放完成');
            }, 250);
        }
        
        function updateSoundPosition() {
            if (!pannerNode) return;
            
            const soundSource = document.getElementById('soundSource');
            const layout = document.getElementById('surroundLayout');
            
            if (!soundSource || !layout) return;
            
            const rect = layout.getBoundingClientRect();
            const sourceRect = soundSource.getBoundingClientRect();
            
            const sourceX = sourceRect.left + sourceRect.width / 2;
            
            const panValue = ((sourceX - rect.left) / rect.width) * 2 - 1;
            
            pannerNode.pan.value = panValue;
        }
        
        function updatePannerPosition(panner) {
            const soundSource = document.getElementById('soundSource');
            const layout = document.getElementById('surroundLayout');
            
            if (!soundSource || !layout) return;
            
            const rect = layout.getBoundingClientRect();
            const sourceRect = soundSource.getBoundingClientRect();
            
            const sourceX = sourceRect.left + sourceRect.width / 2;
            
            const panValue = ((sourceX - rect.left) / rect.width) * 2 - 1;
            
            panner.pan.value = panValue;
        }
        
        function createSoundWaveEffect() {
            const soundWave = document.getElementById('soundWave');
            const soundSource = document.getElementById('soundSource');
            
            if (!soundWave || !soundSource) return;
            
            const sourceRect = soundSource.getBoundingClientRect();
            const layoutRect = document.getElementById('surroundLayout').getBoundingClientRect();
            
            const centerX = sourceRect.left + sourceRect.width / 2 - layoutRect.left;
            const centerY = sourceRect.top + sourceRect.height / 2 - layoutRect.top;
            
            soundWave.style.left = `${centerX}px`;
            soundWave.style.top = `${centerY}px`;
            soundWave.style.width = '0';
            soundWave.style.height = '0';
            soundWave.style.opacity = '0.7';
            soundWave.style.display = 'block';
            
            let size = 0;
            const maxSize = Math.max(layoutRect.width, layoutRect.height) * 2;
            const startTime = Date.now();
            
            function animateWave() {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / 1500, 1);
                
                size = progress * maxSize;
                soundWave.style.width = `${size}px`;
                soundWave.style.height = `${size}px`;
                soundWave.style.marginLeft = `-${size/2}px`;
                soundWave.style.marginTop = `-${size/2}px`;
                soundWave.style.opacity = 0.7 * (1 - progress);
                
                if (progress < 1) {
                    requestAnimationFrame(animateWave);
                } else {
                    soundWave.style.display = 'none';
                }
            }
            
            animateWave();
        }
        
        function toggleSurroundTest() {
            if (surroundTestActive) {
                stopSurroundTest();
            } else {
                startSurroundTest();
            }
        }
        
        function startSurroundTest() {
            stopAllAudio();
            
            surroundTestActive = true;
            surroundAngle = 0;
            surroundLaps = 0;
            surroundStartTime = Date.now();
            
            document.getElementById('surroundPath').style.display = 'block';
            
            document.getElementById('startSurroundBtn').innerHTML = '<i class="fas fa-stop"></i> 停止环绕';
            document.getElementById('startSurroundBtn').classList.remove('btn-surround');
            document.getElementById('startSurroundBtn').classList.add('btn-reset');
            
            updateSurroundTestStatus('warning', '环绕测试进行中...');
            
            surroundInterval = setInterval(updateSurroundPosition, 50);
        }
        
        function updateSurroundPosition() {
            if (!surroundTestActive) return;
            
            const speed = parseInt(document.getElementById('surroundSpeedSlider').value);
            const radius = parseInt(document.getElementById('surroundRadiusSlider').value);
            
            surroundAngle += speed * 0.5;
            if (surroundAngle >= 360) {
                surroundAngle -= 360;
                surroundLaps++;
            }
            
            const angleRad = surroundAngle * (Math.PI / 180);
            const centerX = 50;
            const centerY = 50;
            
            const x = centerX + Math.cos(angleRad) * radius;
            const y = centerY + Math.sin(angleRad) * radius;
            
            const soundSource = document.getElementById('soundSource');
            soundSource.style.left = `${x}%`;
            soundSource.style.top = `${y}%`;
            
            updatePositionInfo();
            
            if (Math.round(surroundAngle) % 30 === 0) {
                playPositionSound();
            }
        }
        
        function stopSurroundTest() {
            surroundTestActive = false;
            
            if (surroundInterval) {
                clearInterval(surroundInterval);
                surroundInterval = null;
            }
            
            document.getElementById('surroundPath').style.display = 'none';
            
            document.getElementById('startSurroundBtn').innerHTML = '<i class="fas fa-sync-alt"></i> 开始环绕测试';
            document.getElementById('startSurroundBtn').classList.remove('btn-reset');
            document.getElementById('startSurroundBtn').classList.add('btn-surround');
            
            updateSurroundTestStatus('good', '环绕测试已停止');
        }
        
        function resetSurroundTest() {
            stopSurroundTest();
            
            const soundSource = document.getElementById('soundSource');
            soundSource.style.left = '70%';
            soundSource.style.top = '30%';
            
            updatePositionInfo();
            updateSurroundTestStatus('good', '准备就绪');
        }
        
        // ==================== 通用音频控制 ====================
        function stopAllAudio() {
            if (oscillator) {
                try {
                    oscillator.stop();
                    oscillator.disconnect();
                } catch (e) {}
                oscillator = null;
            }
            
            if (gainNode) {
                gainNode.disconnect();
                gainNode = null;
            }
            
            if (pannerNode) {
                pannerNode.disconnect();
                pannerNode = null;
            }
            
            isPlaying = false;
            
            if (isSweeping) {
                stopSweep();
            }
            
            updateFrequencyTestStatus('good', '准备就绪');
            document.getElementById('playToneBtn').disabled = false;
            document.getElementById('sweepBtn').disabled = false;
        }
        
        // ==================== 显示更新函数 ====================
        function updateChannelTestStatus(status, text) {
            updateTestStatus('channel', status, text);
        }
        
        function updateFrequencyTestStatus(status, text) {
            updateTestStatus('frequency', status, text);
        }
        
        function updateSurroundTestStatus(status, text) {
            updateTestStatus('surround', status, text);
        }
        
        function updateTestStatus(type, status, text) {
            const statusEl = document.getElementById(`${type}TestStatus`);
            const statusTextEl = document.getElementById(`${type}TestStatusText`);
            
            if (!statusEl || !statusTextEl) return;
            
            statusEl.className = 'status-indicator';
            if (status === 'good') {
                statusEl.classList.add('status-good');
            } else if (status === 'warning') {
                statusEl.classList.add('status-warning');
            } else if (status === 'bad') {
                statusEl.classList.add('status-bad');
            }
            
            statusTextEl.textContent = text;
        }
        
        function updateFrequencyStats(frequency) {
            document.getElementById('currentFrequency').textContent = frequency;
            
            const wavelength = (343 / frequency).toFixed(2);
            document.getElementById('wavelength').textContent = wavelength;
            
            const note = frequencyToNote(frequency);
            document.getElementById('pitchNote').textContent = note;
            
            const sensitivity = calculateSensitivity(frequency);
            document.getElementById('sensitivity').textContent = `${sensitivity}%`;
            
            updateFrequencyDiagnosis(frequency);
        }
        
        function frequencyToNote(frequency) {
            const A4 = 440;
            const noteNames = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
            
            if (frequency <= 0) return '--';
            
            const semitones = Math.round(12 * Math.log2(frequency / A4));
            const octave = Math.floor(semitones / 12) + 4;
            const noteIndex = (semitones % 12 + 12) % 12;
            
            return `${noteNames[noteIndex]}${octave}`;
        }
        
        function calculateSensitivity(frequency) {
            if (frequency >= 1000 && frequency <= 4000) {
                return 100;
            } else if (frequency < 1000) {
                if (frequency < 100) return 30;
                if (frequency < 250) return 60;
                if (frequency < 500) return 80;
                return 90;
            } else {
                if (frequency > 8000) return 60;
                if (frequency > 12000) return 40;
                if (frequency > 16000) return 20;
                return 80;
            }
        }
        
        function updateFrequencyDiagnosis(frequency) {
            let diagnosis = '';
            
            if (frequency < 60) {
                diagnosis = '超低频，多数音响难以准确还原。';
            } else if (frequency < 200) {
                diagnosis = '低频，测试音响低音表现。';
            } else if (frequency < 1000) {
                diagnosis = '中低频，人声和多数乐器的基频范围。';
            } else if (frequency < 4000) {
                diagnosis = '中高频，人耳最敏感的频率范围。';
            } else if (frequency < 10000) {
                diagnosis = '高频，测试音响细节表现。';
            } else {
                diagnosis = '超高频，多数成年人难以听到。';
            }
            
            document.getElementById('frequencyDiagnosisText').textContent = diagnosis;
        }
        
        function drawWaveform(frequency) {
            if (!waveformCtx) return;
            
            const width = waveformCanvas.width;
            const height = waveformCanvas.height;
            
            waveformCtx.clearRect(0, 0, width, height);
            
            waveformCtx.strokeStyle = 'rgba(100, 100, 150, 0.3)';
            waveformCtx.lineWidth = 1;
            
            for (let i = 1; i < 5; i++) {
                const y = i * height / 5;
                waveformCtx.beginPath();
                waveformCtx.moveTo(0, y);
                waveformCtx.lineTo(width, y);
                waveformCtx.stroke();
            }
            
            waveformCtx.strokeStyle = '#4cc9f0';
            waveformCtx.lineWidth = 3;
            waveformCtx.beginPath();
            
            const amplitude = height / 3;
            const centerY = height / 2;
            
            const cycles = Math.min(10, frequency / 50);
            
            for (let x = 0; x < width; x++) {
                const phase = (x / width) * cycles * 2 * Math.PI;
                
                const noise = Math.sin(x * 0.05) * 0.1;
                
                let y = centerY + Math.sin(phase + noise) * amplitude;
                
                const envelope = Math.min(1, x / 50) * Math.min(1, (width - x) / 50);
                y = centerY + (y - centerY) * envelope;
                
                if (x === 0) {
                    waveformCtx.moveTo(x, y);
                } else {
                    waveformCtx.lineTo(x, y);
                }
            }
            
            waveformCtx.stroke();
            
            waveformCtx.fillStyle = '#a0a0c0';
            waveformCtx.font = '14px Arial';
            waveformCtx.textAlign = 'center';
            waveformCtx.fillText(`${frequency} Hz`, width / 2, 30);
            
            waveformCtx.font = '12px Arial';
            waveformCtx.fillStyle = '#4cc9f0';
            waveformCtx.fillText('正弦波', width / 2, height - 15);
        }
        
        // ==================== 页面加载初始化 ====================
        window.addEventListener('load', init);
        
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAllAudio();
                stopSurroundTest();
            }
        });
    </script>
</body>
</html>