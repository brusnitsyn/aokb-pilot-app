<?php
return [
    'token' => env('GIGACHAT_TOKEN'),
    'scope' => env('GIGACHAT_SCOPE'),
    'urls' => [
        'auth' => env('GIGACHAT_URL_AUTH'),
        'completions' => env('GIGACHAT_URL_COMPLETIONS'),
    ]
];
