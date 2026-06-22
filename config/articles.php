<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Article Image Upload Limits
    |--------------------------------------------------------------------------
    |
    | These values constrain the images an author may attach to an article.
    | "max_image_kb" is the maximum size per image in kilobytes, and
    | "image_mimes" is the whitelist of allowed image extensions.
    |
    */

    'max_images' => (int) env('ARTICLE_MAX_IMAGES', 5),

    'max_image_kb' => (int) env('ARTICLE_MAX_IMAGE_KB', 2048),

    'image_mimes' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],

    'image_disk' => env('ARTICLE_IMAGE_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Article Tag Limits
    |--------------------------------------------------------------------------
    |
    | "max_tags" is the maximum number of admin-managed tags an author may
    | attach to a single article.
    |
    */

    'max_tags' => (int) env('ARTICLE_MAX_TAGS', 5),

];
