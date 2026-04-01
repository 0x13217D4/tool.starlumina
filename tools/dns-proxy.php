<?php
// 只允许特定的源访问（生产环境域名）
$allowedOrigins = [
    'http://localhost', 
    'http://127.0.0.1',
    'https://tool.starlumina.com'
];
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array($origin, $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理OPTIONS预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // 启动会话用于速率限制
    session_start();
    
    // 速率限制：每分钟最多 60 次请求
    $currentTime = time();
    $rateLimit = 60; // 每分钟请求数
    $timeWindow = 60; // 时间窗口（秒）
    
    if (!isset($_SESSION['dns_requests'])) {
        $_SESSION['dns_requests'] = [];
    }
    
    // 清理过期的请求记录
    $_SESSION['dns_requests'] = array_filter($_SESSION['dns_requests'], function($timestamp) use ($currentTime, $timeWindow) {
        return ($currentTime - $timestamp) < $timeWindow;
    });
    
    // 检查是否超过速率限制
    if (count($_SESSION['dns_requests']) >= $rateLimit) {
        throw new Exception('请求过于频繁，请稍后再试');
    }
    
    // 记录当前请求
    $_SESSION['dns_requests'][] = $currentTime;
    
    // 只允许 POST 请求
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('只支持 POST 请求');
    }
    
    // 获取请求数据
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['domain']) || !isset($input['type'])) {
        throw new Exception('请求参数不完整');
    }
    
    $domain = trim($input['domain']);
    $type = strtoupper(trim($input['type']));
    
    // 验证域名格式
    if (!preg_match('/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/i', $domain)) {
        throw new Exception('域名格式不正确');
    }
    
    // 验证记录类型
    $validTypes = ['A', 'AAAA', 'CNAME', 'MX', 'TXT', 'NS', 'SOA', 'PTR', 'SRV', 'ANY'];
    if (!in_array($type, $validTypes)) {
        throw new Exception('不支持的记录类型');
    }
    
    // 使用多个DNS API作为备选
    $apis = [
        [
            'url' => 'https://cloudflare-dns.com/dns-query',
            'name' => 'Cloudflare DNS'
        ],
        [
            'url' => 'https://8.8.8.8/resolve',
            'name' => 'Google DNS'
        ],
        [
            'url' => 'https://1.1.1.1/dns-query',
            'name' => 'Cloudflare 1.1.1.1'
        ]
    ];
    
    $result = null;
    $lastError = null;
    
    foreach ($apis as $api) {
        $url = $api['url'] . '?name=' . urlencode($domain) . '&type=' . urlencode($type);
        
        $options = [
            'http' => [
                'header' => [
                    'Accept: application/dns-json',
                    'User-Agent: DNS-Proxy/1.0'
                ],
                'timeout' => 10,
                'method' => 'GET'
            ]
        ];
        
        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);
        
        if ($response !== false) {
            $data = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($data)) {
                $result = $data;
                $result['source'] = $api['name'];
                break;
            }
        }
        
        $lastError = error_get_last();
        error_log("DNS API " . $api['name'] . " failed: " . ($lastError['message'] ?? 'Unknown error'));
    }
    
    if ($result === null) {
        throw new Exception('所有DNS API都无法访问');
    }
    
    // 返回成功结果
    echo json_encode([
        'success' => true,
        'data' => $result,
        'source' => $result['source'] ?? 'Unknown'
    ]);
    
} catch (Exception $e) {
    // 返回错误信息
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>