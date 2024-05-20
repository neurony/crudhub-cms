<?php

return [

    /*
    |
    | All page types available for your application.
    |
    | --- ARRAY KEY:
    | The actual page type name.
    | This will be persisted to the pages database table.
    |
    | --- [Controller]:
    | The controller to be used for pages on the front-end.
    |
    | --- [Action]:
    | The action from the controller to be used for pages on the front-end.
    |
    | --- [Locations]:
    | The locations in page available for inserting content blocks.
    |
    */
    'types' => [

        'home' => [
            'controller' => '\App\Http\Controllers\HomeController',
            'action' => 'show',
            'locations' => [
                'content',
            ]
        ],

        'default' => [
            'controller' => '\App\Http\Controllers\PageController',
            'action' => 'show',
            'locations' => [
                'content',
            ]
        ],

    ],

];
