<?php

return [
    'role_structure' => [
        'super' => [
            'users'      => 'c,r,u,d',
            'roles'      => 'c,r,u,d',
            'doctors'    => 'c,r,u,d',
            'patients'    => 'c,r,u,d',
            'firstaids'    => 'c,r,u,d',
            'firstaidchildren'    => 'c,r,u,d',
            'emergencs'    => 'c,r,u,d',
            'emergencchildren'    => 'c,r,u,d',
            'logoutdoctors'    => 'c,r,u,d',
            'logoutpatients'    => 'c,r,u,d',




            
        ],
    ],
    // 'permission_structure' => [
    //     'cru_user' => [
    //         'profile' => 'c,r,u'
    //     ],
    // ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
