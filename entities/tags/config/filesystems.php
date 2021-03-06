<?php

return [

    /*
     * Расширение файла конфигурации app/config/filesystems.php
     * добавляет локальные диски для хранения изображений тегов
     */

    'tags' => [
        'driver' => 'local',
        'root' => storage_path('app/public/tags'),
        'url' => env('APP_URL').'/storage/tags',
        'visibility' => 'public',
    ],

];
