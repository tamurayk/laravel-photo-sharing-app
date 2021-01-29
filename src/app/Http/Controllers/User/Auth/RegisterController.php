<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\UseCases\User\Auth\Interfaces\RegisterInterface;
use App\Http\UseCases\User\Auth\SignUp;
use App\Models\Eloquents\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /** @var SignUp */
    private $signUpUseCase;

    /**
     * Create a new controller instance.
     *
     * @param RegisterInterface $useCase
     * @return void
     */
    public function __construct(RegisterInterface $useCase)
    {
        $this->middleware('guest');
        $this->signUpUseCase = $useCase;
    }

    /**
     * Override to \Illuminate\Foundation\Auth\RegistersUsers
     *
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user');
    }

    /**
     * Override to \Illuminate\Foundation\Auth\RegistersUsers
     *
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('user.auth.register');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     * @throws \App\Http\UseCases\User\Auth\Exceptions\RegisterException
     */
    protected function create(array $data)
    {
        $user = $this->signUpUseCase->__invoke($data);
        return $user;
    }
}
