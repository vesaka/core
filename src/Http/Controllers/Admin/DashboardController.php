<?php

namespace Vesaka\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Description of DashboardController
 *
 * @author vesak
 */
class DashboardController extends Controller {
    public function dashboard() {
        return view('admin::dashboard');
    }
}
