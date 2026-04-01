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

    // App 内 WebView 优先判断
    if (ua.includes("MicroMessenger")) return "微信app";
    if (ua.includes("StarLumina")) return "星芒集盒app";
    if (ua.includes("Weibo") && ua.includes("__weibo__")) return "微博app";
    if (ua.includes("AlipayClient") && (ua.includes("NebulaSDK") || ua.includes("Nebula"))) return "支付宝app";
    if (ua.includes("DingTalk")) return "钉钉app";
    if (ua.includes("Taobao") || ua.includes("TB")) return "淘宝app";
    if (ua.includes("JD") && ua.includes("mobile")) return "京东app";
    if (ua.includes("TikTok") || ua.includes("Musical_ly")) return "抖音/TikTok app";
    if (ua.includes("Kuaishou")) return "快手app";
    if (ua.includes("Bilibili")) return "哔哩哔哩app";
    if (ua.includes("iqiyi")) return "爱奇艺app";
    if (ua.includes("youku")) return "优酷app";
    if (ua.includes("tencentvideo")) return "腾讯视频app";
    if (ua.includes("netease")) return "网易云音乐app";
    if (ua.includes("QQ")) return "QQ app";
    if (ua.includes("BaiduMap")) return "百度地图app";
    if (ua.includes("Amap")) return "高德地图app";
    if (ua.includes("Didi")) return "滴滴出行app";
    if (ua.includes("Meituan")) return "美团app";
    if (ua.includes("Eleme")) return "饿了么app";
    if (ua.includes("SohuNews")) return "搜狐新闻app";
    if (ua.includes("NeteaseNews")) return "网易新闻app";
    if (ua.includes("Toutiao")) return "今日头条app";
    if (ua.includes("Zhihu")) return "知乎app";
    if (ua.includes("WeChatWork")) return "企业微信app";
    if (ua.includes("Douyin")) return "抖音app";

    // 移动端第三方浏览器
    if (ua.includes("QQBrowser")) return "QQ浏览器";
    if (ua.includes("UCBrowser") || ua.includes("UCWEB")) return "UC浏览器";
    if (ua.includes("baidubrowser") || ua.includes("baiduboxapp")) return "百度浏览器";
    if (ua.includes("HuaweiBrowser") || ua.includes("Huawei") || ua.includes("HONOR")) return "华为浏览器";
    if (ua.includes("MiuiBrowser")) return "MIUI浏览器";
    if (ua.includes("Via")) return "Via浏览器";
    if (ua.includes("XiaoMiBrowser")) return "小米浏览器";
    if (ua.includes("OppoBrowser")) return "OPPO浏览器";
    if (ua.includes("VivoBrowser")) return "vivo浏览器";
    if (ua.includes("SamsungBrowser")) return "三星浏览器";
    if (ua.includes("LieBao")) return "猎豹浏览器";
    if (ua.includes("SogouMobile")) return "搜狗手机浏览器";
    if (ua.includes("360browser") || ua.includes("360 Aphone")) return "360浏览器";
    if (ua.includes("Mercury")) return " Mercury浏览器";
    if (ua.includes("JUC")) return "JUC浏览器";
    if (ua.includes("Mxbrowser")) return "Maxthon浏览器";
    if (ua.includes("Puffin")) return "Puffin浏览器";
    if (ua.includes("Skyfire")) return "Skyfire浏览器";
    if (ua.includes("Bolt")) return "Bolt浏览器";
    if (ua.includes("Teashark")) return "TeaShark浏览器";
    if (ua.includes("Blazer")) return "Blazer浏览器";
    if (ua.includes("Obigo")) return "Obigo浏览器";

    // 桌面/通用浏览器（注意顺序：Chrome 必须在 Safari 前）
    if (ua.includes("Edg/")) return "Microsoft Edge";
    if (ua.includes("OPR") || ua.includes("Opera")) return "Opera浏览器";
    if (ua.includes("Chrome")) return "Chrome";
    if (ua.includes("Firefox")) return "Firefox";
    if (ua.includes("Safari") && !ua.includes("Chrome") && !ua.includes("Edg/") && !ua.includes("OPR")) return "Safari";
    if (ua.includes("MSIE") || ua.includes("Trident")) return "Internet Explorer";
    if (ua.includes("Netscape")) return "Netscape浏览器";
    if (ua.includes("Konqueror")) return "Konqueror浏览器";
    if (ua.includes("Lynx")) return "Lynx浏览器";

    return "未知浏览器";
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

