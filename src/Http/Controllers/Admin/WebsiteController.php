<?php

namespace Vesaka\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;

/**
 * Description of WebsiteController
 *
 * @author vesak
 */
class WebsiteController extends ModelController {
    protected string $type = 'website';
    public function datatable(Request $request) {
        return app('website')->datatable($request);
    }
}
