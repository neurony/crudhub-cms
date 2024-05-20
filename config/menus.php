<?php

return [

    /*
    |
    | Here you can define different menu locations for your menu items.
    |
    | This way, in the admin you will see your new locations, and you can add menu items directly to them.
    | Afterward, on the front-end, you can use the "menu()->get('your-location')" to fetch all viable menu items from a specific location.
    |
    */
    'locations' => [

        'header',
        'footer',

    ],

    /*
    |
    | All custom menu types available for your application.
    |
    | --- ARRAY KEY:
    | The actual menu type name.
    | This will be persisted to the menus database table.
    |
    | --- ARRAY VALUE:
    | The FQN of the model class representing the respective menu type.
    | This is used in the admin menu section, to specify a menu type upon creating / updating.
    |
    | Using this configuration, you will be able to reference entity records, directly in your menus from the admin
    | (e.g. link a menu button to a blog post)
    |
    | !!!IMPORTANT!!!
    |
    | Every model class you specify below should implement the "\Zbiller\CrudhubCms\Contracts\MenuableContract" interface.
    |
    */
    'types' => [

        'your_model' => \App\Models\User::class,

    ],

];
