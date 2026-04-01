<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPv6网速测试 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .tool-content {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 2rem;
            box-sizing: border-box;
        }
        
        .speed-test-container {
            width: 100%;
            height: 80vh;
            min-height: 600px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background: white;
        }
        
        .speed-test-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .info-card {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #e74c3c;
        }
        
        .info-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        
        .info-card p {
            margin-bottom: 0;
            color: #495057;
        }
        
        .ipv6-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .speed-test-container {
                height: 70vh;
                min-height: 500px;
            }
        }
        
        @media (max-width: 480px) {
            .speed-test-container {
                height: 60vh;
                min-height: 400px;
            }
            
            .tool-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include '../templates/header.php'; ?>
    
    <div class="tool-container">
        <a href="../index.php" class="back-btn">返回首页</a>
        <div class="speed-test-container">
            <iframe 
                src="https://test6.ustc.edu.cn/" 
                class="speed-test-iframe"
                title="IPv6网速测试"
                frameborder="0"
                allowfullscreen>
            </iframe>
        </div>
        
        <div class="info-card" style="margin-top: 2rem;">
            <h3>⚠️ 注意事项</h3>
            <p>• IPv6测速需要您的网络环境支持IPv6连接</p>
            <p>• 测速结果受多种因素影响，包括网络拥堵、服务器负载等</p>
            <p>• 建议多次测试取平均值以获得更准确的结果</p>
        </div>
    </div>
    
    <?php include '../templates/footer.php'; ?>
    
    <script>
        // 页面加载完成后的处理
        document.addEventListener('DOMContentLoaded', function() {
            const iframe = document.querySelector('.speed-test-iframe');
            
            // 处理iframe加载错误
            iframe.addEventListener('error', function() {
                console.error('IPv6测速页面加载失败');
                // 显示错误提示
                const container = document.querySelector('.speed-test-container');
                container.innerHTML = `
                    <div style="padding: 2rem; text-align: center; color: #721c24; background-color: #f8d7da; border-radius: 8px;">
                        <h3>❌ IPv6测速页面加载失败</h3>
                        <p>可能的原因：</p>
                        <ul style="text-align: left; display: inline-block;">
                            <li>您的网络不支持IPv6连接</li>
                            <li>测速网站暂时无法访问</li>
                            <li>网络连接问题</li>
                        </ul>
                        <p>请检查您的IPv6网络连接后重试。</p>
                    </div>
                `;
            });
            
            // 显示加载状态
            iframe.addEventListener('load', function() {
                console.log('IPv6测速页面加载完成');
            });
        });
    </script>
</body>
</html>