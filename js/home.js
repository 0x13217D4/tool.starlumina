// 加载公共组件
function loadCommonComponents() {
    // 加载页眉
    fetch('templates/header.html')
        .then(response => response.text())
        .then(html => document.getElementById('header-container').innerHTML = html);
        
    // 加载页脚
    fetch('templates/footer.html')
        .then(response => response.text())
        .then(html => {
            document.getElementById('footer-container').innerHTML = html;
            document.getElementById('current-year').textContent = new Date().getFullYear();
        });
}

// 页面加载完成后执行
document.addEventListener('DOMContentLoaded', function() {
    loadCommonComponents();
});
