<?php

return [

    /*
     * Настройки изображений
     */

    'images' => [
        'quality' => 75,
        'conversions' => [
            'tag' => [
                'og_image' => [
                    'default' => [
                        [
                            'name' => 'og_image_default',
                            'size' => [
                                'width' => 968,
                                'height' => 475,
                            ],
                        ],
                    ],
                ],
                'content' => [
                    'default' => [
                        [
                            'name' => 'content_admin',
                            'size' => [
                                'width' => 140,
                            ],
                        ],
                        [
                            'name' => 'content_front',
                            'quality' => 70,
                            'fit' => [
                                'width' => 768,
                                'height' => 512,
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'crops' => [
            'tag' => [
                'og_image' => [
                    [
                        'title' => 'Выбрать область',
                        'name' => 'default',
                        'ratio' => '968/475',
                        'size' => [
                            'width' => 968,
                            'height' => 475,
                            'type' => 'min',
                            'description' => 'Минимальный размер области — 968x475 пикселей',
                        ],
                    ],
                ],
            ],
        ],
    ],

    /*
     * Настройки связей.
     */

    'relationships' => [
        'articles' => [
            'relationship' => 'morphedByMany',
            'model' => 'InetStudio\Articles\Contracts\Models\ArticleModelContract',
            'params' => [
                'taggable',
            ],
        ],
        'ingredients' => [
            'relationship' => 'morphedByMany',
            'model' => 'InetStudio\IngredientsPackage\Ingredients\Contracts\Models\IngredientModelContract',
            'params' => [
                'taggable',
            ],
        ],
    ],
];
