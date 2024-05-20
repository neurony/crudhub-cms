<?php

/*
| ------------------------------------------------------------------------------------------------------------------
| Class Bindings
| ------------------------------------------------------------------------------------------------------------------
|
| FQNs of the classes used by the Crudhub platform internally to achieve different functionalities.
| Each of these classes represents a concrete implementation that is bound to the Laravel IoC container.
|
| If you need to extend or modify a functionality, you can swap the implementation below with your own class.
| Swapping the implementation, requires some steps, like extending the core class, or implementing an interface.
|
*/
return [

    /*
    | --------------------------------------------------------------------------------------------------------------
    | Model Class Bindings
    | --------------------------------------------------------------------------------------------------------------
    */
    'models' => [

        /*
        |
        | Concrete implementation for the "page model".
        | To extend or replace this functionality, change the value below with your full "page model" FQN.
        |
        */
        'page_model' => \Zbiller\CrudhubCms\Models\Page::class,

        /*
        |
        | Concrete implementation for the "menu model".
        | To extend or replace this functionality, change the value below with your full "menu model" FQN.
        |
        */
        'menu_model' => \Zbiller\CrudhubCms\Models\Menu::class,

        /*
        |
        | Concrete implementation for the "block model".
        | To extend or replace this functionality, change the value below with your full "block model" FQN.
        |
        */
        'block_model' => \Zbiller\CrudhubCms\Models\Block::class,

    ],

    /*
    | --------------------------------------------------------------------------------------------------------------
    | Resource Class Bindings
    | --------------------------------------------------------------------------------------------------------------
    */
    'resources' => [

        /*
        |
        | Concrete implementation for the "page resource".
        | To extend or replace this functionality, change the value below with your full "page resource" FQN.
        |
        */
        'page_resource' => \Zbiller\CrudhubCms\Resources\PageResource::class,

        /*
        |
        | Concrete implementation for the "menu resource".
        | To extend or replace this functionality, change the value below with your full "menu resource" FQN.
        |
        */
        'menu_resource' => \Zbiller\CrudhubCms\Resources\MenuResource::class,

        /*
        |
        | Concrete implementation for the "block resource".
        | To extend or replace this functionality, change the value below with your full "block resource" FQN.
        |
        */
        'block_resource' => \Zbiller\CrudhubCms\Resources\BlockResource::class,

    ],

    /*
    | --------------------------------------------------------------------------------------------------------------
    | Controller Class Bindings
    | --------------------------------------------------------------------------------------------------------------
    */
    'controllers' => [

        /*
        |
        | Concrete implementation for the "page controller".
        | To extend or replace this functionality, change the value below with your full "page controller" FQN.
        |
        */
        'page_controller' => \Zbiller\CrudhubCms\Controllers\PageController::class,

        /*
        |
        | Concrete implementation for the "page tree controller".
        | To extend or replace this functionality, change the value below with your full "page tree controller" FQN.
        |
        */
        'page_tree_controller' => \Zbiller\CrudhubCms\Controllers\PageTreeController::class,

        /*
        |
        | Concrete implementation for the "menu controller".
        | To extend or replace this functionality, change the value below with your full "menu controller" FQN.
        |
        */
        'menu_controller' => \Zbiller\CrudhubCms\Controllers\MenuController::class,

        /*
        |
        | Concrete implementation for the "menu tree controller".
        | To extend or replace this functionality, change the value below with your full "menu tree controller" FQN.
        |
        */
        'menu_tree_controller' => \Zbiller\CrudhubCms\Controllers\MenuTreeController::class,

        /*
        |
        | Concrete implementation for the "block controller".
        | To extend or replace this functionality, change the value below with your full "block controller" FQN.
        |
        */
        'block_controller' => \Zbiller\CrudhubCms\Controllers\BlockController::class,

    ],

    'form_requests' => [

        /*
        |
        | Concrete implementation for the "page form request".
        | To extend or replace this functionality, change the value below with your full "page form request" FQN.
        |
        */
        'page_form_request' => \Zbiller\CrudhubCms\Requests\PageRequest::class,

        /*
        |
        | Concrete implementation for the "menu form request".
        | To extend or replace this functionality, change the value below with your full "menu form request" FQN.
        |
        */
        'menu_form_request' => \Zbiller\CrudhubCms\Requests\MenuRequest::class,

        /*
        |
        | Concrete implementation for the "block form request".
        | To extend or replace this functionality, change the value below with your full "block form request" FQN.
        |
        */
        'block_form_request' => \Zbiller\CrudhubCms\Requests\BlockRequest::class,

    ],

    /*
    | --------------------------------------------------------------------------------------------------------------
    | Service Class Bindings
    | --------------------------------------------------------------------------------------------------------------
    */
    'services' => [

        /*
        |
        | Concrete implementation for the "tree service".
        | To extend or replace this functionality, change the value below with your full "tree service" FQN.
        |
        */
        'tree_service' => \Zbiller\CrudhubCms\Services\TreeService::class,

    ],

    /*
    | --------------------------------------------------------------------------------------------------------------
    | Filter Class Bindings
    | --------------------------------------------------------------------------------------------------------------
    */
    'filters' => [

        /*
        |
        | Concrete implementation for the "page filter".
        | To extend or replace this functionality, change the value below with your full "page filter" FQN.
        |
        */
        'page_filter' => \Zbiller\CrudhubCms\Filters\PageFilter::class,

        /*
        |
        | Concrete implementation for the "menu filter".
        | To extend or replace this functionality, change the value below with your full "menu filter" FQN.
        |
        */
        'menu_filter' => \Zbiller\CrudhubCms\Filters\MenuFilter::class,

        /*
        |
        | Concrete implementation for the "block filter".
        | To extend or replace this functionality, change the value below with your full "block filter" FQN.
        |
        */
        'block_filter' => \Zbiller\CrudhubCms\Filters\BlockFilter::class,

    ],

    /*
    | --------------------------------------------------------------------------------------------------------------
    | Sort Class Bindings
    | --------------------------------------------------------------------------------------------------------------
    */
    'sorts' => [

        /*
        |
        | Concrete implementation for the "page sort".
        | To extend or replace this functionality, change the value below with your full "page sort" FQN.
        |
        */
        'page_sort' => \Zbiller\CrudhubCms\Sorts\PageSort::class,

        /*
        |
        | Concrete implementation for the "menu sort".
        | To extend or replace this functionality, change the value below with your full "menu sort" FQN.
        |
        */
        'menu_sort' => \Zbiller\CrudhubCms\Sorts\MenuSort::class,

        /*
        |
        | Concrete implementation for the "block sort".
        | To extend or replace this functionality, change the value below with your full "block sort" FQN.
        |
        */
        'block_sort' => \Zbiller\CrudhubCms\Sorts\BlockSort::class,

    ],

];
