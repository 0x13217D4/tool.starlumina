<style>
    /* 底部栏专用样式 */
    .footer {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        text-align: center;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
        margin-top: auto;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    }
    
    .footer-info {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.8rem;
        flex-wrap: wrap;
        font-size: 0.9rem;
    }
    
    .footer-info a {
        color: #3498db;
        text-decoration: none;
        transition: all 0.3s;
        font-weight: 500;
    }
    
    .footer-info a:hover {
        color: #2ecc71;
        text-decoration: underline;
    }
    
    .logos {
        vertical-align: middle;
        margin: 0 5px;
        filter: brightness(0) invert(1);
    }
    
    .copyright {
        font-size: 0.85rem;
        opacity: 0.9;
    }
    
    .author-link {
        color: #3498db !important;
        text-decoration: none !important;
        font-weight: 600;
    }
    
    .author-link:hover {
        color: #2ecc71 !important;
        text-decoration: none !important;
    }
    
    @media (max-width: 768px) {
        .footer {
            padding: 1rem;
        }
        
        .footer-info {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>

<footer class="footer">
    <div class="footer-info">
        <span>友情链接：</span>
        <a href="https://www.starlumina.com/" target="_blank">星芒起始页</a>
        <span>|</span>
        <a href="https://blog.starlumina.com/" target="_blank">星芒博客</a>
        <span>|</span>
        <a href="https://app.starlumina.com/" target="_blank">星芒集盒</a>
    </div>
    <div class="footer-info">
        <a href="https://beian.miit.gov.cn/" target="_blank">蜀ICP备2024095899号-3</a>
        <img class="logos" src="https://vip.123pan.cn/1832150722/ymjew503t0l000d7w32xfcwa742s0k5lDIYwDqeyDdUvDpxPAdDxDF==.png" width="15" height="15">
        <a href="https://beian.mps.gov.cn/#/query/webSearch?code=51019002007728" target="_blank">川公网安备51019002007728号</a>
    </div>
    <div class="copyright">© <span id="current-year"></span> <a href="https://about.starlumina.com/" target="_blank" class="author-link">胡黄成霖</a> 版权所有</div>
</footer>