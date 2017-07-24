<?php

return [
    'user' => [
        'model' => 'App\User',
        'thread' => [
	        'model' => 'App\Models\UserTreatment',
	        'table' => 'user_treatments'
        ]
    ],
    'broadcast' => [
        'enable' => false,
        'app_name' => 'your-app-name',
        'pusher' => [
            'app_id' => '',
            'app_key' => '',
            'app_secret' => '',
            'options' => [
                'cluster' => 'ap1',
                'encrypted' => true
            ]
        ],
    ],
];
