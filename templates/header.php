<style>
    /* 顶部栏专用样式 */
    .top-bar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        text-align: left;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        position: sticky;
        top: 0;
        z-index: 1000;
        backdrop-filter: blur(10px);
    }
    
    .logo-container {
        display: flex;
        align-items: center;
        gap: 15px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .site-logo {
        height: 2.5rem;
        width: 2.5rem;
        border-radius: 50%;
        object-fit: contain;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        background: white;
    }
    
    .site-logo:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 4px 12px rgba(255,255,255,0.3);
    }
    
    .top-bar h1 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 600;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        letter-spacing: 1px;
    }
    
    @media (max-width: 768px) {
        .top-bar {
            padding: 1rem;
        }
        
        .logo-container {
            gap: 10px;
        }
        
        .site-logo {
            height: 2rem;
            width: 2rem;
        }
        
        .top-bar h1 {
            font-size: 1.4rem;
        }
    }
</style>
<header class="top-bar">
    <div class="logo-container">
        <link rel="shortcut icon" href="https://vip.123pan.cn/1832150722/yk6baz03t0n000d7w33gzr20dllunnpiDIYwDqeyDdUvDpxPAdDxDF==.png" type="image/x-icon" />
        <img src="https://vip.123pan.cn/1832150722/yk6baz03t0n000d7w33gzr20dllunnpiDIYwDqeyDdUvDpxPAdDxDF==.png" alt="星芒工具箱Logo" class="site-logo">
        <h1>星芒工具箱</h1>
    </div>
</header>