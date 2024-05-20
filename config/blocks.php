<?php

return [

    /*
    |
    | All block types available for your application.
    | Whenever you create a new block using the "php artisan varbox:make-block" command or manually, append the type here.
    |
    | --- [Label]:
    | The pretty formatted block type name.
    | This is mainly used inside the admin panel, in places that reference blocks.
    |
    | --- [Composer Class]:
    | The full namespace to the block's view composer.
    | Each block you create will have a "view composer" that's automatically bound to the block's front view namespace.
    | So any logic your block might use, you can define it in the block's view composer class.
    |
    | --- [Views Path]:
    | The full path to the block's views directory.
    | When creating a new block type, besides the "view composer" class, you will also have two views (front & admin).
    |
    | --- [Preview Image]:
    | The name of the image used as block type preview in admin.
    | This should contain the full path to an image of yours inside the "public/" directory.
    | The path is relative to the "public/" directory.
    |
    */
    'types' => [

        'ExampleBlock' => [
            'label' => 'Example Block',
            'composer_class' => 'App\Blocks\ExampleBlock\Composer',
            'views_path' => 'app/Blocks/ExampleBlock/Views',
            'preview_image' => 'images/blocks/example-block.jpg',
        ],

    ],

];
