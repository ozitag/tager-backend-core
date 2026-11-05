<?php

return [
    'pagination' => [
        'max_page_size' => 1000,
        'default_page_size' => 100
    ],
    /*
     * Доверенные прокси для TrustProxies.
     *
     * trusted_proxies — список адресов строкой через запятую, массивом, либо
     * '*' чтобы доверять любому (Symfony понимает звёздочку только строкой).
     * Пусто — адрес берётся резолвом trusted_proxy_host; если и он не
     * резолвится, доверенных прокси нет.
     */
    'trusted_proxies' => env('TRUSTED_PROXIES'),
    'trusted_proxy_host' => env('TRUSTED_PROXY_HOST', 'nginx'),
    'default_headers' => [
        'Accept' => 'application/json'
    ],
    'validation' => [
        'codePrefix' => 'VALIDATION',
        'multipleErrors' => false,
        'errorFormat' => [
            'code' => '#code',
            'message' => '#message',
        ],
    ]
];
