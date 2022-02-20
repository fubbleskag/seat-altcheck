<?php

return [

    'altcheck' => [
        'name' => 'Alt Check',
        'permission' => 'altcheck.view',
        'icon' => 'fas fa-people-arrows',
        'route_segment' => 'altcheck',
        'entries' => [
            [
                'name' => 'Check some alts!',
                'icon' => 'fas fa-th-list',
                'route' => 'altcheck.view',
            ],
        ],
    ],
];
