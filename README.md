# 星芒工具箱 V1.0.5
https://tool.starlumina.com/
一个功能丰富的在线工具集合，提供多种实用工具，涵盖信息查询、开发工具、图像处理、安全工具、娱乐游戏和网络测试等多个类别。

## 🚀 项目特性

- **响应式设计**：适配各种设备和屏幕尺寸
- **分类清晰**：工具按功能类别组织，便于查找
- **界面友好**：简洁直观的用户界面
- **功能丰富**：包含40+实用工具
- **无需安装**：基于Web的解决方案，即开即用

## 📋 功能分类

### 🔍 信息查询
- **设备信息**：获取浏览器和操作系统信息
- **进制转换器**：二进制、八进制、十进制、十六进制转换
- **单位转换器**：长度、重量、温度、体积、面积、速度、时间、数据存储转换
- **时间戳转换**：Unix时间戳与日期互转
- **颜色选择器**：选择、转换颜色代码

### 🛠️ 开发工具
- **JSON格式化**：格式化/压缩JSON数据
- **XML格式化**：格式化和美化XML文档
- **Markdown预览**：实时预览Markdown渲染效果
- **代码格式化**：自动格式化代码
- **正则表达式测试**：测试和调试正则表达式
- **Base64加解密**：Base64编码/解码工具
- **SHA哈希生成**：生成SHA-1/256/384/512哈希值
- **MD5加密**：生成MD5哈希值
- **CSV/JSON转换**：CSV和JSON格式互转
- **URL编码/解码**：URL编码和解码工具
- **中文域名转换**：中文域名与Punycode互转
- **图表生成器**：基于JSON/CSV数据生成图表
- **文本差异比较**：比较两个文本文件的差异
- **字数统计**：统计文本字数、字符数等信息

### 🖼️ 图像处理
- **二维码生成**：生成自定义二维码
- **二维码识别**：扫描识别二维码内容
- **条码生成器**：生成Code128条码
- **JPG压缩**：无损压缩JPG图片
- **图片格式转换**：转换图片为不同格式
- **EXIF查看器**：查看图片的EXIF信息
- **设备摄像头**：切换使用不同摄像头
- **SVG优化器**：优化和压缩SVG文件

### 🔒 安全工具
- **密码生成器**：生成安全随机密码
- **密码强度检测**：检测密码安全强度
- **HTTP测试工具**：发送和测试HTTP请求
- **文本加密/解密**：使用多种算法加密解密文本

### 🎮 娱乐游戏
- **随机数生成**：生成指定范围的随机数
- **2048游戏**：经典数字合并益智游戏
- **猜数字游戏**：猜1-100之间的数字
- **井字棋游戏**：经典的双人对战游戏
- **记忆配对游戏**：锻炼记忆力的翻牌游戏
- **24点游戏**：数学计算益智游戏

### ?? 网络测试
- **IPv4网速测试**：测试IPv4网络连接速度
- **IPv6网速测试**：测试IPv6网络连接速度
- **IP地址查询**：查询IP地址的地理位置
- **DNS查询**：查询域名的DNS记录信息

## 📁 项目结构

```
星芒工具箱/
├── css/                    # 样式文件
│   └── main.css
├── images/                 # 图片资源
├── js/                     # JavaScript文件
│   └── home.js
├── json/                   # JSON数据文件
│   ├── area-codes.json
│   └── elements.json
├── templates/              # 模板文件
│   ├── footer.php
│   └── header.php
├── tools/                  # 工具页面
│   ├── 2048-game.php
│   ├── 24-point-game.php
│   ├── barcode-generator.php
│   ├── base-converter.php
│   ├── base64-converter.php
│   ├── camera-switcher.php
│   ├── chart-generator.php
│   ├── code-formatter.php
│   ├── color-picker.php
│   ├── csv-json-converter.php
│   ├── device-info.php
│   ├── dns-lookup.php
│   ├── exif-viewer.php
│   ├── guess-number.php
│   ├── http-tester.php
│   ├── id-validator.php
│   ├── idn-converter.php
│   ├── image-converter.php
│   ├── ip-lookup.php
│   ├── jpg-compressor.php
│   ├── json-formatter.php
│   ├── markdown-preview.php
│   ├── md5-generator.php
│   ├── memory-game.php
│   ├── password-generator.php
│   ├── password-strength.php
│   ├── periodic-table.php
│   ├── qrcode-generator.php
│   ├── qrcode-scanner.php
│   ├── random-generator.php
│   ├── regex-tester.php
│   ├── sha-generator.php
│   ├── svg-optimizer.php
│   ├── text-counter.php
│   ├── text-diff.php
│   ├── text-encryptor.php
│   ├── tictactoe.php
│   ├── timestamp-converter.php
│   ├── unit-converter.php
│   ├── url-encoder.php
│   ├── user-agent-detector.php
│   └── xml-formatter.php
├── 更新日志.md             # 版本更新记录
├── index.php              # 主页面
└── README.md              # 项目说明文档
```

## 🚀 快速开始

1. **环境要求**
   - PHP 7.0+
   - 现代Web浏览器（Chrome、Firefox、Safari、Edge等）

2. **安装部署**
   ```bash
   # 克隆或下载项目文件到Web服务器目录
   # 确保Web服务器支持PHP
   
   # 直接访问index.php即可使用
   ```

3. **本地开发**
   ```bash
   # 使用PHP内置服务器
   php -S localhost:8000
   
   # 或使用其他Web服务器如Apache、Nginx等
   ```

## 📝 更新日志

### V1.0.5
- 修复IP地址无法显示的问题
- 修复返回首页按钮错误的问题
- 单位转化器中新增体积、面积、速度、时间、数据存储转换功能
- 修复XML格式化工具无法访问的问题
- 修复Markdown预览工具返回首页按钮错误的问题

## 🤝 贡献指南

欢迎提交Issue和Pull Request来改进项目！

1. Fork 本项目
2. 创建你的特性分支 (`git checkout -b feature/AmazingFeature`)
3. 提交你的修改 (`git commit -m 'Add some AmazingFeature'`)
4. 推送到分支 (`git push origin feature/AmazingFeature`)
5. 开启一个 Pull Request

## 📄 许可证

本项目采用 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情

## 📞 联系方式

如有问题或建议，欢迎通过以下方式联系：

- 提交 Issue
- 发送邮件

---

⭐ 如果这个项目对你有帮助，请给个Star支持一下！