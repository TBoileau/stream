<?php

return [
    'app' => [
        'path' => 'app.js',
        'preload' => true,
    ],
    '@symfony/stimulus-bundle' => [
        'path' => '@symfony/stimulus-bundle/loader.js',
    ],
    '@symfony/ux-live-component' => [
        'path' => '@symfony/ux-live-component/live_controller.js',
    ],
    '@hotwired/stimulus' => [
        'url' => 'https://cdn.jsdelivr.net/npm/@hotwired/stimulus@3.2.2/+esm',
    ],
    'tmi.js' => [
        'url' => 'https://cdn.jsdelivr.net/npm/tmi.js@1.8.5/+esm',
    ],
];
