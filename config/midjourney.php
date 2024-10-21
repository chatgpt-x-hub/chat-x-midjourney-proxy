<?php

use App\Midjourney\TaskStore\File;


return [
    'accounts' => [
        [
            'enable' => true,
            'token' => env('DISCORD_BOT_TOKEN', ''),
            'guild_id' => env('DISCORD_GUILD_ID', ''),
            'channel_id' => env('DISCORD_CHANNEL_ID', ''),
            'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.30 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.30',
            'concurrency' => 3, // 并发数
            'timeoutMinutes' => 10, // 10分钟后超时
        ]
    ],
    'http_proxy' => env('MIDJOURNEY_HTTP_PROXY', '10.10.10.2:7890'), // http 代理
    'proxy' => [
        'server' => env('DISCORD_SERVER_URL','https://discord.com'),      // 国内需要代理
        'cdn' => env('DISCORD_CDN_URL', 'https://cdn.discordapp.com'),  // 国内需要代理
        'gateway' => env('DISCORD_GATEWAY_URL', 'wss://gateway.discord.gg'), // 国内需要代理
        'upload' => env('DISCORD_UPLOAD_URL', 'https://discord-attachments-uploads-prd.storage.googleapis.com'), // 国内需要代理
    ],
    'store' => [
        'handler' => File::class,
        'expiredDates' => 30, // 30天后过期
        File::class => [
            'dataPath' => storage_path('app') . '/data/midjourney',
        ]
    ],
    'settings' => [
        'debug' => false,  // 调试模式会显示更多信息在终端
        'secret' => env('MJ-API-SECRET', ''),    // 接口密钥，不为空时需要在请求头 mj-api-secret 中传递
        'notifyUrl' => env('MI_NOTIFY_URL', ''), // webman ai项目请留空
        'apiPrefix' => env('MJ_API_PREFIX', ''), // 接口前缀
        'tmpPath' => storage_path('app') . '/tmp/midjourney' // 上传文件临时目录
    ]
];