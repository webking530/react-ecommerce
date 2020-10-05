<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'merchant/product/add_photos/*',
    	'merchant/product/add_video_mp4/*',
        'merchant/product/add_video_webm/*',
        'merchant/product/add_video_thumb/*',
    	'merchant/product/add_option_photos/*',
        'merchant/store/update_logo/*',
        'merchant/store/update_header/*',
        'admin/products/add_photos/*',
        'admin/products/add_video/*',
        'admin/product/add_video_mp4/*',
        'admin/product/add_video_webm/*',
        'admin/product/add_video_thumb/*',
        'admin/products/add_option_photos/*',
        'admin/products/check_price',
        'admin/products/check_option_price',
    ];
}
