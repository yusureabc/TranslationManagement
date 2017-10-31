<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:system');
    }
	/**
	 * 控制台
	 * @author Sheldon
	 * @date   2017-04-29
	 * @return [type]     [description]
	 */
    public function index()
    {
    	//return view('admin.dashboard.index');
        return redirect(route('salesdata.charts'));
    }
    /**
     * datatable国际化
     * @author Sheldon
     * @date   2017-04-29
     * @return [type]     [description]
     */
    public function dataTableI18n()
    {
    	return response()->json(trans('pagination.i18n'));
    }
}
