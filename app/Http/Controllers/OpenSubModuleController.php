<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OpenSubModuleController extends Controller
{
    protected $moduleRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\ModuleRepository $moduleRepository)
    {
		$this->moduleRepository = $moduleRepository;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\Illuminate\Http\Request $request)
    {
		$collection = collect([]);
		$moduleKey = $request->route('subModuleName');
		try {
			$modules = $this->moduleRepository->findWhere('key', $moduleKey);
			if ($modules->isNotEmpty()) {
				$module = $modules[0];
				$collection->put('subModuleName', $module->name);
			} else {
				return redirect('/')->with('statusError', 'SubModule not found');
			}
		} catch (ModelNotFoundException $e) {
			return redirect('/')->with('statusError', 'SubModule not found');
		}
		return view('modules.submodule', $collection->all());
    }
}
