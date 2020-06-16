<?php

return [
    'routes' => [
        'static' => [
            'admin/blog/edit' => lightningsdk\sitemanager_blog\Pages\Admin\Posts::class,
            'admin/blog/categories' => lightningsdk\sitemanager_blog\Pages\Admin\Categories::class,
        ],
    ],
    'classes' => [
        lightningsdk\blog\Model\Blog::class => lightningsdk\sitemanager_blog\Model\Blog::class,
        lightningsdk\blog\Model\Category::class => lightningsdk\sitemanager_blog\Model\Category::class,
        lightningsdk\blog\Model\Post::class => lightningsdk\sitemanager_blog\Model\Post::class,
    ],
    'modules' => [
        'include' => [
            'lightningsdk/blog'
        ],
    ],
];
