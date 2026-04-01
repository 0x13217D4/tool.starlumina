<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>流量消耗器 - 星芒工具箱</title>
    <link rel="stylesheet" href="../css/main.css">
    <!-- ECharts CDN -->
    <script src="https://fastly.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <style>
        /* 页面布局样式 */
        .tool-content {
            max-width: 900px;
        }
        .tool-content h1 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }
        
        /* 卡片组件样式 */
        .card-preview {
            margin-bottom: 1.5rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .card-inner {
            padding: 1.5rem;
            position: relative;
        }
        
        /* 标题标签样式 - 使用星芒主色调 */
        .nya-title {
            position: absolute;
            top: -12px;
            background-color: #3498db;
            color: #fff;
            padding: 5px 18px;
            border-radius: 8px;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.35);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* 手风琴内部样式 */
        .accordion-inner {
            padding-top: 0.5rem;
        }
        .accordion-inner .title {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
        }
        
        /* 圆点和方角辅助样式 */
        .dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .dot-lg {
            width: 12px;
            height: 12px;
        }
        .sq {
            border-radius: 3px;
        }
        
        /* 表单控件样式 */
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #364a63;
        }
        .form-control-wrap {
            position: relative;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: 0.6875rem 1rem;
            font-size: 0.9375rem;
            line-height: 1.25rem;
            color: #364a63;
            background-color: #fff;
            border: 1px solid #dbdfea;
            border-radius: 4px;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            box-sizing: border-box;
        }
        .form-control:focus {
            border-color: #3498db;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        /* 数字调节器样式 */
        .number-spinner-wrap {
            display: flex;
            align-items: center;
        }
        .number-spinner {
            text-align: center;
            width: 80px;
        }
        .number-spinner-btn {
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        /* 按钮样式 - 使用星芒主色调 */
        .btn-outline-primary {
            color: #3498db;
            border: 1px solid #3498db;
            background: transparent;
        }
        .btn-outline-primary:hover {
            background: #3498db;
            color: #fff;
        }
        .btn-dim {
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s;
        }
        .btn-dim:hover {
            background: #2980b9;
        }
        
        /* 开关控件样式 */
        .custom-control {
            display: flex;
            align-items: center;
        }
        .custom-control-input {
            width: 2.4rem;
            height: 1.25rem;
            margin-right: 0.5rem;
            cursor: pointer;
        }
        .custom-control-label {
            cursor: pointer;
        }
        
        /* 列表项样式 */
        .nk-menu-item {
            list-style: none;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e9f2;
            display: flex;
            justify-content: space-between;
        }
        .nk-menu-item:last-child {
            border-bottom: none;
        }
        
        /* 统计卡片样式 */
        .stat {
            width: 100%;
            column-gap: 1rem;
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e5e9f2;
        }
        .stat-title {
            white-space: nowrap;
            opacity: .6;
            font-size: 0.9rem;
        }
        .stat-value {
            white-space: nowrap;
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 2.5rem;
            color: #2c3e50;
        }
        
        /* 图例样式 */
        .nk-ecwg8-legends {
            display: flex;
            justify-content: center;
            gap: 2rem;
            list-style: none;
            padding: 0;
            margin: 0 0 1rem 0;
        }
        .nk-ecwg8-legends li {
            display: flex;
            align-items: center;
        }
        .nk-ecwg8-legends .title {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #555;
        }
        
        /* 文本颜色辅助样式 */
        .text-waring {
            color: #e74c3c !important;
        }
        .text-info {
            color: #3498db;
        }
        .text-dark {
            color: #2c3e50;
        }
        
        /* 响应式适配 */
        @media (max-width: 768px) {
            .stat-value {
                font-size: 1.4rem;
            }
            .nya-title {
                font-size: 0.85rem;
                padding: 4px 12px;
            }
            .stat {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div id="header-container"></div>
    
    <main class="tool-page">
        <div class="tool-content">
            <a href="../index.php" class="back-btn">返回首页</a>
            <h1>流量消耗器</h1>
            
            <!-- 工具说明卡片 -->
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="nya-title"><span>💡</span><span>工具说明</span></div>
                    <div class="accordion-inner">
                        <div class="title"><span class="dot dot-lg sq" style="background: #9cabff;"></span>&nbsp;<span>支持任意跨域链接，自动检测并切换最佳加载模式</span></div>
                        <div class="title"><span class="dot dot-lg sq" style="background: #ffa9ce;"></span>&nbsp;<span>速度不满意？请尝试手动切换节点，加大运行线程</span></div>
                        <div class="title"><span class="dot dot-lg sq" style="background: #f98c45;"></span>&nbsp;<span>消耗机场流量，建议使用海外节点，速度可能会更快</span></div>
                    </div>
                </div>
            </div>
            
            <!-- 模式指示器 -->
            <div id="modeIndicator" style="display:none;align-items:center;gap:8px;padding:10px 15px;background:linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);border-radius:8px;margin-bottom:1rem;border:1px solid #f39c12;">
                <span class="dot dot-lg sq" style="background: #f39c12;"></span>
                <span style="color: #856404;font-weight:500;">跨域模式</span>
                <span class="size-info" style="color: #856404;font-size:0.9rem;">流量统计可能不精确</span>
            </div>
            
            <!-- 测试设置卡片 -->
            <div class="card card-preview">
                <div class="card-inner" style="margin-top: 0;">
                    <div class="nya-title"><span>⚙️</span><span>测试设置</span></div>
                    <div class="form-group">
                        <label class="form-label">测试地址</label>
                        <div class="form-control-wrap">
                            <select id='select' onchange="document.getElementById('link').value=this.options[this.selectedIndex].value;setCookie('select',this.selectedIndex,365);" type="text" class="form-control">
                                <option value="" id="diy">自定义</option>
                                <option selected="selected" value="https://issuecdn.baidupcs.com/issue/netdisk/apk/BaiduNetdiskSetup_wap_share.apk">百度CDN [高速]</option>
                                <option value="https://img.alicdn.com/imgextra/i1/O1CN01xA4P9S1JsW2WEg0e1_!!6000000001084-2-tps-2880-560.png">阿里CDN</option>
                                <option value="https://wegame.gtimg.com/g.55555-r.c4663/wegame-home/sc02-03.514d7db8.png">腾讯CDN</option>
                                <option value="https://lf9-cdn-tos.bytecdntp.com/cdn/yuntu-index/1.0.4/case/maiteng/detailbg.png">字节跳动</option>
                                <option value="https://pic-bucket.ws.126.net/photo/0003/2022-04-24/H5N2082C00AJ0003NOS.jpg">网易</option>
                                <option value="https://wwwstatic.vivo.com.cn/vivoportal/files/resource/funtouch/1651200648928/images/os2-jude-video.mp4">Vivo</option>
                                <option value="https://img.cmvideo.cn/publish/noms/2022/10/14/1O3VIGPVP6HTS.jpg">咪咕视频</option>
                                <option value="https://img.mcloud.139.com/material_prod/material_media/20221128/1669626861087.png">和彩云</option>
                                <option value="https://desk.ctyun.cn/desktop/software/clientsoftware/download/ff3e71dcc21152307f54700c62e5aef6">天翼云</option>
                                <option value="https://i2.hdslb.com/bfs/openplatform/202506/ndIMphTF1750406775833.png">哔哩哔哩</option>
                                <option value="https://cachefly.cachefly.net/100mb.test">Cachefly Test [Global]</option>
                                <option value="https://speed.cloudflare.com/__down?bytes=1073741824">Cloudflare Speed [Global]</option>
                            </select>
                            <input value="" oninput="document.getElementById('select').selectedIndex=0;document.getElementById('diy').value=this.value;setCookie('diy',this.value,365);setCookie('select',0,365);" type="text" id="link" placeholder="请输入下载链接" autocomplete="off" class="form-control" style="margin-top: 10px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">线程</label>
                        <div class="form-control-wrap number-spinner-wrap">
                            <button onclick="var thread=document.getElementById('thread');if(thread.value!=thread.min)thread.value--;" class="btn btn-icon btn-outline-primary number-spinner-btn number-minus">−</button>
                            <input onchange="var self= document.getElementById('thread');if(self.value<self.min)self.value=self.min;if(self.value>self.max)self.value=self.max" id='thread' type="number" min="1" max="32" value="1" class="form-control number-spinner">
                            <button onclick="var thread=document.getElementById('thread');if(thread.value!=thread.max)thread.value++;" class="btn btn-icon btn-outline-primary number-spinner-btn number-plus">+</button>
                        </div>
                    </div>
                    <div class="form-group" id="back">
                        <div class="preview-block">
                            <div class="custom-control custom-switch checked">
                                <input onclick="musiccontrol(this)" type="checkbox" id="customSwitch2" class="custom-control-input">
                                <label for="customSwitch2" class="custom-control-label">保持后台运行</label>
                            </div>
                        </div>
                    </div>
                    <button onclick="botton_clicked();" type="button" id="do" class="btn btn-dim btn-outline-secondary btn-block card-link" style="width:100%;padding:12px;font-size:16px;">开始</button>
                    <div class="row" style="margin-top: 1.5rem; display: flex; flex-wrap: wrap; gap: 1rem;">
                        <div class="col-sm-12 col-md-4 border stat" style="flex:1;min-width:200px;cursor:pointer;" onclick='inputMax=prompt("请输入流量上限(GB)\n不填为不设置上限","");if(inputMax !== null)setMax(inputMax)'>
                            <div class="text-dark" style="float:right;padding-top: 0.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                </svg>
                            </div>
                            <div class="stat-title">总流量 <a id="showMax"></a></div>
                            <div class="stat-value" id="total">-</div>
                        </div>
                        <div class="col-sm-12 col-md-4 border stat" style="flex:1;min-width:200px;">
                            <div class="text-info" style="float:right;padding-top: 0.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16.469,8.924l-2.414,2.413c-0.156,0.156-0.408,0.156-0.564,0c-0.156-0.155-0.156-0.408,0-0.563l2.414-2.414c1.175-1.175,1.175-3.087,0-4.262c-0.57-0.569-1.326-0.883-2.132-0.883s-1.562,0.313-2.132,0.883L9.227,6.511c-1.175,1.175-1.175,3.087,0,4.263c0.288,0.288,0.624,0.511,0.997,0.662c0.204,0.083,0.303,0.315,0.22,0.52c-0.171,0.422-0.643,0.17-0.52,0.22c-0.473-0.191-0.898-0.474-1.262-0.838c-1.487-1.485-1.487-3.904,0-5.391l2.414-2.413c0.72-0.72,1.678-1.117,2.696-1.117s1.976,0.396,2.696,1.117C17.955,5.02,17.955,7.438,16.469,8.924 M10.076,7.825c-0.205-0.083-0.437,0.016-0.52,0.22c-0.083,0.205,0.016,0.437,0.22,0.52c0.374,0.151,0.709,0.374,0.997,0.662c1.176,1.176,1.176,3.088,0,4.263l-2.414,2.413c-0.569,0.569-1.326,0.883-2.131,0.883s-1.562-0.313-2.132-0.883c-1.175-1.175-1.175-3.087,0-4.262L6.51,9.227c0.156-0.155,0.156-0.408,0-0.564c-0.156-0.156-0.408-0.156-0.564,0l-2.414,2.414c-1.487,1.485-1.487,3.904,0,5.391c0.72,0.72,1.678,1.116,2.696,1.116s1.976-0.396,2.696-1.116l2.414-2.413c1.487-1.486,1.487-3.905,0-5.392C10.974,8.298,10.55,8.017,10.076,7.825"></path>
                                </svg>
                            </div>
                            <div class="stat-title" id="describe">实时速度</div>
                            <div class="stat-value text-info" id="speed">-</div>
                        </div>
                        <div class="col-sm-12 col-md-4 border stat" style="flex:1;min-width:200px;">
                            <div class="text-dark" style="float:right;padding-top: 0.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="stat-title">带宽</div>
                            <div class="stat-value" id="mbps">-</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 实时图表卡片 -->
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="nya-title"><span>📊</span><span>实时图表</span></div>
                    <br>
                    <ul class="nk-ecwg8-legends">
                        <li><div class="title"><span class="dot" style="background: #536fc4;"></span>&nbsp;速 率</div></li>
                        <li><div class="title"><span class="dot" style="background: #90ca74;"></span>&nbsp;延 迟</div></li>
                    </ul>
                    <div id="dv" style="position: relative;height: 300px;overflow: hidden;"></div>
                </div>
            </div>
            
            <!-- 出口地址卡片 -->
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="nya-title"><span>🌍</span><span>出口地址</span></div>
                    <div class="accordion-inner">
                        <li class="nk-menu-item">
                            <a class="text-waring" style="pointer-events:none;">国内 IPv4</a>
                            <a class="text-waring" style="pointer-events:none;float: right;" id="ipcn_v4">Loading...</a>
                        </li>
                        <li class="nk-menu-item">
                            <a class="text-waring" style="pointer-events:none;">国内 IPv6</a>
                            <a class="text-waring" style="pointer-events:none;float: right;" id="ipcn_v6">Loading...</a>
                        </li>
                        <li class="nk-menu-item">
                            <a style="pointer-events:none;">海外 IPv4</a>
                            <a style="pointer-events:none;float: right;" id="ipgb_v4">Loading...</a>
                        </li>
                        <li class="nk-menu-item">
                            <a style="pointer-events:none;">海外 IPv6</a>
                            <a style="pointer-events:none;float: right;" id="ipgb_v6">Loading...</a>
                        </li>
                        <hr style="margin: 0.5rem 0;">
                        <li class="nk-menu-item">
                            <a class="text-waring" style="pointer-events:none;">百度连通性</a>
                            <a class="text-waring" style="pointer-events:none;float: right;" id="laycn">-ms</a>
                        </li>
                        <li class="nk-menu-item">
                            <a style="pointer-events:none;">Cloudflare连通性</a>
                            <a style="pointer-events:none;float: right;" id="laygb">-ms</a>
                        </li>
                        <li class="nk-menu-item">
                            <a class="text-waring" style="pointer-events:none;">Github连通性</a>
                            <a class="text-waring" style="pointer-events:none;float: right;" id="github">-ms</a>
                        </li>
                        <li class="nk-menu-item">
                            <a style="pointer-events:none;">Youtube连通性</a>
                            <a style="pointer-events:none;float: right;" id="youtube">-ms</a>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div id="footer-container"></div>

    <!-- 后台运行音频 -->
    <audio controls id="music" loop="loop" style="display:none">
        <source src="../traffic_deplete-main/res/background.mp3" type="audio/mpeg">
    </audio>

    <!-- JavaScript 核心逻辑 -->
    <script>
        var maxtheand;
        var testurl;
        var lsat_date = 0;
        var all_down_sum = 0;
        var run = false;
        var checkIP = true;
        var visibl = true;
        var thread_down = [];
        var lsat_all_down = 0;
        var refresh_lay = 5000;
        var now_speed = 0;
        var now_local_ping = 0;
        var now_global_ping = 0;
        
        // 跨域模式相关变量
        var crossDomainMode = false;  // 是否处于跨域模式
        var crossDomainLoaders = [];  // 存储跨域加载器实例
        var isEstimatedMode = false;  // 是否为估算模式（无法精确测量时）

        async function start_thread(index) {
            try {
                const response = await fetch(testurl, { cache: "no-store", mode: 'cors', referrerPolicy: 'no-referrer' });
                const reader = response.body.getReader();
                while (true) {
                    const { value, done } = await reader.read();
                    if (done) {
                        reader.cancel();
                        start_thread(index);
                        break;
                    }
                    if (!run) {
                        reader.cancel();
                        break;
                    }
                    thread_down[index] += value.length;
                }
            } catch (err) {
                console.log(err);
                if (run) start_thread(index);
            }
        }

        async function cale() {
            var all_down_a = sum(thread_down);
            now_speed = (all_down_a - lsat_all_down) / (new Date().getTime() - lsat_date) * 1000 / 1024 / 1024;
            if (visibl) document.getElementById("speed").innerText = show((all_down_a - lsat_all_down) / (new Date().getTime() - lsat_date) * 1000, ['B/s', 'KB/s', 'MB/s', 'GB/s', 'TB/s', 'PB/s'], [0, 0, 1, 2, 2, 2]);
            if (visibl) document.getElementById("mbps").innerText = show((all_down_a - lsat_all_down) / (new Date().getTime() - lsat_date) * 8000, ['Bps', 'Kbps', 'Mbps', 'Gbps', 'Tbps', 'Pbps'], [0, 0, 0, 2, 2, 2]);
            if (!visibl) document.title = show((all_down_sum + all_down_a), ['B', 'KB', 'MB', 'GB', 'TB', 'PB'], [0, 0, 0, 2, 2, 2]) + ' ' + show((all_down_a - lsat_all_down) / (new Date().getTime() - lsat_date) * 1000, ['B/s', 'KB/s', 'MB/s', 'GB/s', 'TB/s', 'PB/s'], [0, 0, 0, 2, 2, 2]);
            lsat_all_down = all_down_a;
            lsat_date = new Date().getTime();
            if (run) setTimeout(cale, 1000);
            else {
                var avg_speed = 1000 * (all_down_a) / (new Date().getTime() - start_time);
                document.title = '流量消耗器 - 星芒工具箱';
                now_speed = 0;
                document.getElementById("speed").innerText = show((avg_speed), ['B/s', 'KB/s', 'MB/s', 'GB/s', 'TB/s', 'PB/s'], [0, 0, 1, 2, 2, 2]);
                document.getElementById("mbps").innerText = show((avg_speed) * 8, ['Bps', 'Kbps', 'Mbps', 'Gbps', 'Tbps', 'Pbps'], [0, 0, 0, 2, 2, 2]);
                lsat_all_down = 0;
                document.getElementById('describe').innerText = '平均速度';
            }
        }

        async function total() {
            var all_down = sum(thread_down);
            if (visibl) document.getElementById("total").innerText = show((all_down_sum + all_down), ['B', 'KB', 'MB', 'GB', 'TB', 'PB'], [0, 0, 1, 2, 2, 2]);
            if ((all_down_sum + all_down) >= Maximum && Maximum != 0) stop();
            if (run) setTimeout(total, 16);
            else {
                all_down_sum += all_down;
                document.getElementById("total").innerText = show((all_down_sum), ['B', 'KB', 'MB', 'GB', 'TB', 'PB'], [0, 0, 1, 2, 2, 2]);
            }
        }

        async function start() {
            if (all_down_sum >= Maximum && Maximum != 0) {
                all_down_sum = 0;
            }
            maxtheand = document.getElementById("thread").value;
            testurl = document.getElementById("link").value;
            if (testurl.length < 10) {
                alert("链接不合法");
                return;
            }
            testurl = testurl.substring(0, 5).toLowerCase() + testurl.substring(5, testurl.length);
            if (!checkURL(testurl)) {
                alert("链接不合法");
                return;
            }
            if (testurl.startsWith("http://")) {
                alert("由于浏览器安全限制，不支持http协议，请使用https协议");
                return;
            }
            if (!testurl.startsWith("https://")) {
                alert("链接不合法");
                return;
            }
            document.getElementById('do').innerText = '正在检验链接...';
            document.getElementById('do').disabled = true;

            // 尝试 CORS 模式检测
            let corsSupported = false;
            try {
                const response = await fetch(testurl, { 
                    method: 'GET',
                    cache: "no-store", 
                    mode: 'cors', 
                    referrerPolicy: 'no-referrer' 
                });
                // 检查是否能获取响应体
                const reader = response.body.getReader();
                const { value, done } = await reader.read();
                if (value && value.length > 0) {
                    corsSupported = true;
                }
                reader.cancel();
            } catch (err) {
                console.warn('CORS not supported, switching to cross-domain mode:', err);
                corsSupported = false;
            }

            // 根据检测结果选择模式
            if (corsSupported) {
                // CORS 模式 - 精确测量
                crossDomainMode = false;
                isEstimatedMode = false;
                document.getElementById('describe').innerText = '实时速度';
                document.getElementById('do').innerText = '停止';
                document.getElementById('do').disabled = false;
                
                lsat_all_down = 0;
                start_time = new Date().getTime();
                run = true;
                thread_down = [];
                var num = maxtheand;
                while (num--) {
                    thread_down[num] = 0;
                    start_thread(num);
                }
                cale();
                total();
            } else {
                // 跨域模式 - 标签加载
                crossDomainMode = true;
                isEstimatedMode = false;
                document.getElementById('describe').innerText = '实时速度';
                document.getElementById('do').innerText = '停止';
                document.getElementById('do').disabled = false;
                
                lsat_all_down = 0;
                start_time = new Date().getTime();
                run = true;
                startCrossDomainThreads();
            }
            
            updateModeIndicator();
        }

        function stop() {
            run = false;
            document.getElementById('do').innerText = '开始';
            crossDomainMode = false;
            isEstimatedMode = false;
            // 清理跨域加载器
            crossDomainLoaders.forEach(loader => {
                if (loader && loader.element && loader.element.parentNode) {
                    loader.element.parentNode.removeChild(loader.element);
                }
            });
            crossDomainLoaders = [];
        }

        // ==================== 跨域模式支持 ====================
        
        /**
         * 根据URL扩展名判断资源类型
         */
        function getResourceType(url) {
            const lowerUrl = url.toLowerCase();
            
            if (lowerUrl.match(/\.(jpg|jpeg|png|gif|webp|svg|ico|bmp|avif)$/)) {
                return 'image';
            }
            if (lowerUrl.match(/\.(mp4|webm|ogg|mov|avi|mkv)$/)) {
                return 'video';
            }
            if (lowerUrl.match(/\.(js|mjs)$/)) {
                return 'script';
            }
            if (lowerUrl.match(/\.(css)$/)) {
                return 'style';
            }
            if (lowerUrl.match(/\.(mp3|wav|ogg|aac|flac)$/)) {
                return 'audio';
            }
            
            // 默认尝试图片方式
            return 'auto';
        }
        
        /**
         * CrossDomainLoader - 跨域资源加载器
         */
        class CrossDomainLoader {
            constructor(url, threadIndex) {
                this.url = url;
                this.threadIndex = threadIndex;
                this.resourceType = getResourceType(url);
                this.element = null;
                this.timeoutId = null;
            }
            
            getCacheBusterUrl() {
                const separator = this.url.includes('?') ? '&' : '?';
                return `${this.url}${separator}_cb=${Date.now()}_t=${this.threadIndex}`;
            }
            
            load() {
                const cacheBuster = this.getCacheBusterUrl();
                
                switch (this.resourceType) {
                    case 'image':
                        return this.loadAsImage(cacheBuster);
                    case 'video':
                        return this.loadAsVideo(cacheBuster);
                    case 'script':
                        return this.loadAsScript(cacheBuster);
                    case 'audio':
                        return this.loadAsAudio(cacheBuster);
                    default:
                        return this.loadAsAuto(cacheBuster);
                }
            }
            
            loadAsImage(url) {
                return new Promise((resolve) => {
                    const startTime = performance.now();
                    const img = new Image();
                    this.element = img;
                    
                    img.onload = img.onerror = () => {
                        const size = this.getSizeFromPerformance(url);
                        const duration = performance.now() - startTime;
                        resolve({ size, duration, success: img.complete && img.naturalWidth > 0 });
                    };
                    
                    // 设置30秒超时
                    this.timeoutId = setTimeout(() => {
                        resolve({ size: 0, duration: 30000, success: false, timeout: true });
                    }, 30000);
                    
                    img.src = url;
                });
            }
            
            loadAsVideo(url) {
                return new Promise((resolve) => {
                    const startTime = performance.now();
                    const video = document.createElement('video');
                    this.element = video;
                    video.style.display = 'none';
                    video.muted = true;
                    
                    video.onloadeddata = video.onerror = () => {
                        clearTimeout(this.timeoutId);
                        const size = this.getSizeFromPerformance(url);
                        const duration = performance.now() - startTime;
                        video.remove();
                        resolve({ size, duration, success: video.readyState > 0 });
                    };
                    
                    this.timeoutId = setTimeout(() => {
                        video.remove();
                        resolve({ size: 0, duration: 30000, success: false, timeout: true });
                    }, 30000);
                    
                    video.src = url;
                    video.load();
                    document.body.appendChild(video);
                });
            }
            
            loadAsScript(url) {
                return new Promise((resolve) => {
                    const startTime = performance.now();
                    const script = document.createElement('script');
                    this.element = script;
                    script.async = true;
                    
                    script.onload = script.onerror = () => {
                        clearTimeout(this.timeoutId);
                        const size = this.getSizeFromPerformance(url);
                        const duration = performance.now() - startTime;
                        script.remove();
                        resolve({ size, duration, success: true });
                    };
                    
                    this.timeoutId = setTimeout(() => {
                        script.remove();
                        resolve({ size: 0, duration: 30000, success: false, timeout: true });
                    }, 30000);
                    
                    script.src = url;
                    document.body.appendChild(script);
                });
            }
            
            loadAsAudio(url) {
                return new Promise((resolve) => {
                    const startTime = performance.now();
                    const audio = document.createElement('audio');
                    this.element = audio;
                    audio.style.display = 'none';
                    audio.muted = true;
                    
                    audio.onloadeddata = audio.onerror = () => {
                        clearTimeout(this.timeoutId);
                        const size = this.getSizeFromPerformance(url);
                        const duration = performance.now() - startTime;
                        audio.remove();
                        resolve({ size, duration, success: audio.readyState > 0 });
                    };
                    
                    this.timeoutId = setTimeout(() => {
                        audio.remove();
                        resolve({ size: 0, duration: 30000, success: false, timeout: true });
                    }, 30000);
                    
                    audio.src = url;
                    audio.load();
                    document.body.appendChild(audio);
                });
            }
            
            loadAsAuto(url) {
                // 先尝试图片方式，失败再尝试脚本方式
                return this.loadAsImage(url).then(result => {
                    if (result.success || result.size > 0) return result;
                    return this.loadAsScript(url);
                });
            }
            
            getSizeFromPerformance(url) {
                try {
                    const entries = performance.getEntriesByName(url);
                    if (entries.length > 0) {
                        const entry = entries[entries.length - 1];
                        // transferSize: 实际传输大小（跨域通常为0）
                        // encodedBodySize: 压缩后的资源大小（需要Timing-Allow-Origin）
                        // decodedBodySize: 解压后的资源大小
                        const size = entry.transferSize || entry.encodedBodySize || entry.decodedBodySize || 0;
                        return size;
                    }
                } catch (e) {
                    console.warn('Performance API error:', e);
                }
                return 0;
            }
            
            cancel() {
                if (this.timeoutId) {
                    clearTimeout(this.timeoutId);
                }
                if (this.element) {
                    if (this.element.parentNode) {
                        this.element.parentNode.removeChild(this.element);
                    }
                    this.element = null;
                }
            }
        }
        
        /**
         * 跨域模式单线程下载
         */
        async function start_thread_crossdomain(index) {
            let retryCount = 0;
            const maxRetries = 3;
            
            while (run) {
                try {
                    const loader = new CrossDomainLoader(testurl, index);
                    crossDomainLoaders[index] = loader;
                    
                    const result = await loader.load();
                    
                    if (!run) break;
                    
                    if (result.success || result.size > 0) {
                        thread_down[index] += result.size;
                        retryCount = 0;
                        
                        if (result.size === 0) {
                            // 无法测量精确大小，标记为估算模式
                            if (!isEstimatedMode) {
                                isEstimatedMode = true;
                                updateModeIndicator();
                            }
                        }
                    } else if (result.timeout && retryCount < maxRetries) {
                        retryCount++;
                        console.log(`Thread ${index} retry ${retryCount}/${maxRetries}`);
                    } else {
                        // 加载失败，短暂等待后重试
                        await new Promise(r => setTimeout(r, 1000));
                    }
                    
                    // 清理当前加载器引用
                    crossDomainLoaders[index] = null;
                    
                } catch (err) {
                    console.error(`CrossDomain thread ${index} error:`, err);
                    if (!run) break;
                    await new Promise(r => setTimeout(r, 1000));
                }
            }
        }
        
        /**
         * 启动跨域模式多线程下载
         */
        function startCrossDomainThreads() {
            crossDomainMode = true;
            crossDomainLoaders = [];
            isEstimatedMode = false;
            
            var num = maxtheand;
            thread_down = [];
            
            while (num--) {
                thread_down[num] = 0;
                crossDomainLoaders[num] = null;
                start_thread_crossdomain(num);
            }
            
            updateModeIndicator();
            cale();
            total();
        }
        
        /**
         * 启动CORS模式多线程下载
         */
        function startCORSThreads() {
            crossDomainMode = false;
            isEstimatedMode = false;
            updateModeIndicator();
            
            var num = maxtheand;
            thread_down = [];
            
            while (num--) {
                thread_down[num] = 0;
                start_thread(num);
            }
            
            cale();
            total();
        }
        
        /**
         * 更新模式指示器UI
         */
        function updateModeIndicator() {
            const indicator = document.getElementById('modeIndicator');
            if (indicator) {
                if (crossDomainMode) {
                    indicator.style.display = 'flex';
                    const sizeText = indicator.querySelector('.size-info');
                    if (sizeText) {
                        sizeText.textContent = isEstimatedMode ? '流量统计为估算值' : '流量统计可能不精确';
                    }
                } else {
                    indicator.style.display = 'none';
                }
            }
        }

        function sum(arr) {
            var s = 0;
            for (var i = 0; i < arr.length; i++) {
                s += arr[i];
            }
            return s;
        }

        function botton_clicked() {
            if (run) {
                stop();
            } else {
                start();
            }
        }

        function checkURL(URL) {
            var str = URL;
            var Expression = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
            var objExp = new RegExp(Expression);
            if (objExp.test(str) == true) {
                return true;
            } else {
                return false;
            }
        }

        function laycn() {
            if (visibl) {
                var start_ti = new Date().getTime();
                fetch("https://www.baidu.com/", { method: "HEAD", cache: "no-store", mode: 'no-cors', referrerPolicy: 'no-referrer' })
                    .then(function() {
                        var lay = new Date().getTime() - start_ti;
                        now_local_ping = lay;
                        document.getElementById("laycn").innerText = lay + 'ms';
                    })
                    .catch(error => document.getElementById("laycn").innerText = '-ms');
            }
            setTimeout(laycn, 2000);
        }

        function laygb() {
            if (visibl) {
                var start_ti = new Date().getTime();
                fetch("https://cp.cloudflare.com/", { method: "HEAD", cache: "no-store", mode: 'no-cors', referrerPolicy: 'no-referrer' })
                    .then(function() {
                        var lay = new Date().getTime() - start_ti;
                        now_global_ping = lay;
                        document.getElementById("laygb").innerText = lay + 'ms';
                    })
                    .catch(error => document.getElementById("laygb").innerText = '-ms');
            }
            setTimeout(laygb, 2000);
        }

        function laygithub() {
            if (visibl) {
                var start_ti = new Date().getTime();
                fetch("https://github.com/", { method: "HEAD", cache: "no-store", mode: 'no-cors', referrerPolicy: 'no-referrer' })
                    .then(function() {
                        var lay = new Date().getTime() - start_ti;
                        document.getElementById("github").innerText = lay + 'ms';
                    })
                    .catch(error => document.getElementById("github").innerText = '-ms');
            }
            setTimeout(laygithub, 2000);
        }

        function layyoutube() {
            if (visibl) {
                var start_ti = new Date().getTime();
                fetch("https://www.youtube.com/", { method: "HEAD", cache: "no-store", mode: 'no-cors', referrerPolicy: 'no-referrer' })
                    .then(function() {
                        var lay = new Date().getTime() - start_ti;
                        document.getElementById("youtube").innerText = lay + 'ms';
                    })
                    .catch(error => document.getElementById("youtube").innerText = '-ms');
            }
            setTimeout(layyoutube, 2000);
        }

        laycn();
        laygb();
        laygithub();
        layyoutube();

        document.addEventListener("visibilitychange", function() {
            var string = document.visibilityState;
            if (string === 'hidden') {
                visibl = false;
                if (run && !document.getElementById("customSwitch2").checked) botton_clicked();
            }
            if (string === 'visible') {
                visibl = true;
                document.title = "流量消耗器 - 星芒工具箱";
            }
        });

        // 图表初始化
        var chartDom = document.getElementById('dv');
        var myChart = echarts.init(chartDom);
        var option;

        option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross',
                    label: {
                        backgroundColor: '#6a7985'
                    }
                }
            },
            legend: {
                data: ['速率', '百度', 'Cloudflare']
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [{
                type: 'time',
                name: "时间",
                boundaryGap: false,
                axisLabel: { show: false }
            }],
            yAxis: [
                {
                    type: 'value',
                    name: "延迟 (ms)",
                    splitLine: { show: false }
                },
                {
                    type: 'value',
                    name: "速率 (MB/s)",
                    splitLine: { show: false }
                }
            ],
            series: [
                {
                    name: '速率',
                    type: 'line',
                    yAxisIndex: 1,
                    areaStyle: {},
                    emphasis: { focus: 'series' },
                    data: []
                },
                {
                    name: '百度',
                    type: 'line',
                    data: []
                },
                {
                    name: 'Cloudflare',
                    type: 'line',
                    data: []
                }
            ]
        };

        option && myChart.setOption(option);

        function dv() {
            if (visibl) {
                let now = new Date();
                option.series[0].data.push({
                    name: now.toString(),
                    value: [now.getTime(), parseFloat(now_speed).toFixed(1)]
                });
                option.series[1].data.push({
                    name: now.toString(),
                    value: [now.getTime(), now_local_ping]
                });
                option.series[2].data.push({
                    name: now.toString(),
                    value: [now.getTime(), now_global_ping]
                });
                myChart.setOption({
                    series: option.series
                });
            }
            setTimeout(dv, 1000);
        }

        dv();

        // 工具函数
        function show(num, des, flo) {
            var cnum = num;
            var total_index = 0;
            while (cnum >= 1024) {
                if (total_index == des.length - 1) break;
                cnum = cnum / 1024;
                total_index++;
            }
            return cnum.toFixed(flo[total_index]) + des[total_index];
        }

        function musiccontrol(botton) {
            if (/Mobi|Android|iPhone/i.test(navigator.userAgent)) {
                if (document.getElementById("customSwitch2").checked) document.getElementById("music").play();
                else document.getElementById("music").pause();
            }
        }
        document.getElementById("music").addEventListener("pause", function () {
            document.title = '流量消耗器 - 星芒工具箱';
            if (run && !visibl) botton_clicked();
            document.getElementById("customSwitch2").checked = false;
        });
        document.getElementById("music").addEventListener("play", function () {
            if (!(run || visibl)) botton_clicked();
            document.getElementById("customSwitch2").checked = true;
        });

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toGMTString();
            document.cookie = cname + "=" + cvalue + "; " + expires;
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i].trim();
                if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
            }
            return "";
        }
        document.getElementById('diy').value = getCookie("diy");
        if (getCookie("select")) document.getElementById('select').selectedIndex = getCookie("select");
        var selector = document.getElementById("select");
        document.getElementById('link').value = selector.options[selector.selectedIndex].value;

        var Maximum = 0;
        setMax(getCookie('Max'));

        function setMax(inputMax) {
            if (inputMax > 0) {
                setCookie("Max", inputMax, 365);
                Maximum = inputMax * 1073741824;
                document.getElementById("showMax").innerText = '/' + show(Maximum, ['B', 'KB', 'MB', 'GB', 'TB', 'PB'], [0, 0, 1, 2, 2, 2]);
            } else {
                Maximum = 0;
                document.getElementById("showMax").innerText = '';
                setCookie("Max", 0, 365);
            }
        }

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

        // IP获取
        const ipTargets = [
            { id: 'ipcn_v4', url: 'https://v4.ipgg.cn' },
            { id: 'ipcn_v6', url: 'https://v6.ipgg.cn' },
            { id: 'ipgb_v4', url: 'https://v4.wuxie.de' },
            { id: 'ipgb_v6', url: 'https://v6.wuxie.de' }
        ];

        ipTargets.forEach(target => {
            fetch(target.url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(text => {
                    document.getElementById(target.id).innerText = text.trim();
                })
                .catch(error => {
                    document.getElementById(target.id).innerText = 'NULL';
                    console.error('Fetching IP failed:', error);
                });
        });
    </script>
</body>
</html>