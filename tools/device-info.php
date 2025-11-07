<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>设备信息 | 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
        <a href="../index.php" class="back-btn">返回首页</a>
            <h1>设备信息</h1>
            <div class="info-card">
                <h3>硬件信息</h3>
                <p><strong>处理器核心数:</strong> <span id="cpu-cores"></span></p>
                <h3>操作系统信息</h3>
                <p><strong>操作系统类型:</strong> <span id="platform"></span></p>
                <p><strong>语言设置:</strong> <span id="language"></span></p>
                <h3>其他信息</h3>
                <p><strong>系统时间(UTC):</strong> <span id="system-time"></span></p>
                <p><strong>浏览器名称:</strong> <span id="browser-name"></span></p>
                <p><strong>UserAgent:</strong> <span id="user-agent"></span></p>
            </div>
            
            <div class="info-card">
                <h3>网络信息</h3>
                <p><strong>IP地址:</strong> <span id="ip-address">检测中...</span></p>
                <p><strong>来源页面:</strong> <span id="referrer"></span></p>
            </div>
            
            <div class="info-card">
                <h3>屏幕信息</h3>
                <p><strong>屏幕分辨率:</strong> <span id="screen-resolution"></span></p>
                <p><strong>可用屏幕分辨率:</strong> <span id="screen-available"></span></p>
                <p><strong>窗口大小:</strong> <span id="window-size"></span></p>
                <p><strong>颜色深度:</strong> <span id="color-depth"></span></p>
                <p><strong>像素比:</strong> <span id="pixel-ratio"></span></p>
            </div>
            
            <div class="info-card">
                <h3>Cookie信息</h3>
                <p><strong>Cookie总数:</strong> <span id="cookie-count"></span></p>
                <p><strong>Cookie详情:</strong></p>
                <div id="cookie-details" style="max-height: 200px; overflow-y: auto; background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px;"></div>
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
            
        // 设备信息逻辑
        document.getElementById('user-agent').textContent = navigator.userAgent;
        // 操作系统检测
        const ua = navigator.userAgent;
        let os = "Unknown";
        
        if (/Windows NT 10\.0/.test(ua)) os = "Windows 10/11";
        else if (/OpenHarmony/.test(ua)) os = "OpenHarmony";
        else if (/Windows NT 6\.3/.test(ua)) os = "Windows 8.1";
        else if (/Windows NT 6\.2/.test(ua)) os = "Windows 8";
        else if (/Windows NT 6\.1/.test(ua)) os = "Windows 7";
        else if (/Macintosh/.test(ua)) os = "macOS";
        else if (/Linux/.test(ua)) os = "Linux";
        else if (/Android/.test(ua)) os = "Android";
        else if (/like Mac/.test(ua)) os = "iOS";
        
        document.getElementById('platform').textContent = os;
        document.getElementById('language').textContent = navigator.language;
        
        // 新增其他信息
        function getBrowser() {
            const ua = navigator.userAgent;
            if (ua.includes("MicroMessenger")) return "微信";
            if (ua.includes("StarLumina")) return "星芒集盒";
            if (ua.includes("MQQBrowser") || ua.includes("QQBrowser")) return "QQ浏览器";
            if (ua.includes("UCBrowser") || ua.includes("UCWEB")) return "UC浏览器";
            if (ua.includes("baidubrowser") || ua.includes("baiduboxapp")) return "手机百度";
            if (ua.includes("Huawei") || ua.includes("HONOR") || ua.includes("HuaweiBrowser")) return "华为浏览器";
            if (ua.includes("MiuiBrowser")) return "MIUI浏览器";
            if (ua.includes("Edg/")) return "Microsoft Edge";
            if (ua.includes("Chrome")) return "Chrome";
            if (ua.includes("Safari") && !ua.includes("Chrome")) return "Safari";
            if (ua.includes("Firefox")) return "Firefox";
            if (ua.includes("Via")) return "Via浏览器";
            return "未知";
        }
        
        document.getElementById('browser-name').textContent = getBrowser();
        
        // 简单显示CPU核心数
        document.getElementById('cpu-cores').textContent = navigator.hardwareConcurrency || '未知';
        
        function formatTime(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

        function updateSystemTime() {
            document.getElementById('system-time').textContent = formatTime(new Date());
        }
        
        // 立即更新时间
        updateSystemTime();
        // 每秒更新一次时间
        setInterval(updateSystemTime, 1000);
        
        // 获取IP地址
        async function getIPAddress() {
            const ipElement = document.getElementById('ip-address');
            ipElement.textContent = '正在获取IP地址...';
            
            try {
                console.log('开始请求IP地址...');
                const response = await fetch('https://test.ustc.edu.cn/backend/getIP.php');
                console.log('响应状态:', response.status);
                console.log('响应头:', response.headers);
                
                if (!response.ok) {
                    throw new Error(`HTTP错误! 状态: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('获取到的数据:', data);
                
                if (data && data.processedString) {
                    ipElement.textContent = data.processedString;
                } else {
                    ipElement.textContent = 'IP地址格式错误';
                }
            } catch (error) {
                console.error('获取IP地址失败:', error);
                ipElement.textContent = `无法获取IP地址: ${error.message}`;
                
                // 尝试备用API
                try {
                    console.log('尝试备用API...');
                    const backupResponse = await fetch('https://api.ipify.org?format=json');
                    const backupData = await backupResponse.json();
                    console.log('备用API返回:', backupData);
                    if (backupData && backupData.ip) {
                        ipElement.textContent = backupData.ip;
                    }
                } catch (backupError) {
                    console.error('备用API也失败:', backupError);
                }
            }
        }
        
        // 获取来源页面
        document.getElementById('referrer').textContent = document.referrer || '无来源页面';
        
        // 获取屏幕信息
        function getScreenInfo() {
            document.getElementById('screen-resolution').textContent = `${screen.width} × ${screen.height}`;
            document.getElementById('screen-available').textContent = `${screen.availWidth} × ${screen.availHeight}`;
            document.getElementById('window-size').textContent = `${window.innerWidth} × ${window.innerHeight}`;
            document.getElementById('color-depth').textContent = `${screen.colorDepth} 位`;
            document.getElementById('pixel-ratio').textContent = window.devicePixelRatio;
        }
        
        // 获取Cookie信息
        function getCookieInfo() {
            const cookies = document.cookie.split(';');
            document.getElementById('cookie-count').textContent = cookies.length;
            
            const cookieDetails = document.getElementById('cookie-details');
            if (cookies.length === 0 || (cookies.length === 1 && cookies[0].trim() === '')) {
                cookieDetails.textContent = '无Cookie';
            } else {
                cookies.forEach(cookie => {
                    const cookieLine = document.createElement('div');
                    cookieLine.textContent = cookie.trim();
                    cookieDetails.appendChild(cookieLine);
                });
            }
        }
        
        // 监听窗口大小变化
        window.addEventListener('resize', getScreenInfo);
        
        // 初始化所有信息
        getIPAddress();
        getScreenInfo();
        getCookieInfo();
    </script>
</body>
</html>

