<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Repositories\Contracts\ProfileRepository;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
	
	protected $profileRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\ProfileRepository $profileRepository)
    {
		$this->profileRepository = $profileRepository;
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		return Validator::make($data, [
            'name' => 'bail|required|string|max:150|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
			'profile_id' => 'bail|required|',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'profile_id' => $data['profile_id'],
		  ]);
    }
	
	/**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(\Illuminate\Http\Request $request)
    {
		$collectionCreate = collect([]);
        
		$command = new User();
		
		$page = $request->input('page');
		$actionBack = action('UserController@index');
		
		$filterAttributes = $command->getFilterAttributes();
		$collectionFilterAttributes = collect([]);
		$this->processRequestFilters($request, $filterAttributes, null, $collectionFilterAttributes);
		
		
		$collectionCreate->put('command', $command);
		$collectionCreate->put('actionBack', $actionBack);
		$collectionCreate->put('page', $page);
		$collectionCreate->put('method', 'POST');
		$collectionCreate->put('filters', $collectionFilterAttributes);
		
		$this->referenceData($request, $collectionCreate);
        return view('auth.register', $collectionCreate->all());
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(\Illuminate\Http\Request $request)
    {
		$this->validator($request->all())->validate();

		event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));
		
		$controller = resolve('App\Http\Controllers\UserController');
		
		return $controller->index($request, 'Successfully saved!');
    }
	
	/**
     * Add reference data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Support\Collection  $collection
     */
	protected function referenceData(\Illuminate\Http\Request $request, \Illuminate\Support\Collection $collection) {
		$profiles = $this->profileRepository->select(['id', 'name']);
		
		$select_profile = collect([]);
		foreach ($profiles as $profile) {
			$select_profile->put($profile->id, $profile->name);
		}
		$collection->put('profiles', $select_profile);
		$collection->put('actionCreate', 'register');
	}
	
	protected function processRequestFilters($request, $filterAttributes, $collectionFilter, $collectionFilterAttributes) {
		if (!empty($filterAttributes)) {
			Log::info('Hay Filtros');
			foreach ($filterAttributes as $attribute) {
				if (!empty($request->input($attribute.'_filter'))) {
					Log::info('Hay Valor');
					if (isset ($collectionFilter)) {
						$collectionFilter->put($attribute, $request->input($attribute.'_filter'));
					}
				} 
				$collectionFilterAttributes->put($attribute.'_filter', $request->input($attribute.'_filter'));
			}
		}
	}
}
