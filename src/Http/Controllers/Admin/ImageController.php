<?php

namespace Vesaka\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;

/**
 * Description of UserController
 *
 * @author vesak
 */
class ImageController extends ModelController {
    public function datatable(Request $request) {
        return app('img')->datatable($request);
    }
}
