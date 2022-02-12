<?php

return [
    'pagination' => [
        'max_page_size' => 1000,
        'default_page_size' => 100
    ],
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
