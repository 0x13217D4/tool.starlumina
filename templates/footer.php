<style>
    /* 底部栏专用样式 */
    .footer {
        background-color: #2c3e50;
        color: white;
        text-align: center;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .footer-info {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .footer-info a {
        color: white;
        text-decoration: none;
    }
    
    .footer-info a:hover {
        text-decoration: underline;
    }
    
    .logos {
        vertical-align: middle;
        margin: 0 5px;
    }
    
    .copyright {
        font-size: 0.9rem;
    }
    
    .author-link {
        color: white !important;
        text-decoration: none !important;
    }
    
    .author-link:hover {
        text-decoration: none !important;
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