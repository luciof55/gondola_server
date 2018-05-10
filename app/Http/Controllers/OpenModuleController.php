<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OpenModuleController extends Controller
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
		$moduleKey = $request->route('moduleName');
		try {
			$modules = $this->moduleRepository->findWhere('key', $moduleKey);
			if ($modules->isNotEmpty()) {
				$module = $modules[0];
				$subModulesMenuItem = collect([]);
				foreach ($module->submodules as $submodule) {
					$action = action('OpenSubModuleController@index', ['subModuleName' => $submodule->key]);
					$menuItem = ['text' => $submodule->name, 'url' => $action];
					$subModulesMenuItem->put($submodule->id, $menuItem);
					Log::info('SubModuleMenuItem add SubModule: '. $submodule->key);
				}
				$collection->put('subModulesMenuItem', $subModulesMenuItem);
				$collection->put('subModules', $module->submodules);
				$collection->put('entity', 'modules');
			} else {
				return redirect('/')->with('statusError', 'Module not found');
			}
		} catch (ModelNotFoundException $e) {
			return redirect('/')->with('statusError', 'Module not found');
		}
		return view('modules.module', $collection->all());
    }
}
