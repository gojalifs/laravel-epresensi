<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Password;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nipns' => ['nullable', 'string'],
            'gender' => ['required', 'in:L,P'],
            'telp' => ['required', 'string', 'unique:users', 'max:18'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // return User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'nipns'
        //     'password' => Hash::make($data['password']),
        // ]);

        // get last user created at
        $lastUser = User::orderBy('created_at', 'desc')->first();

        if ($lastUser) {
            // parse last created at date
            $lastDate = Carbon::parse($lastUser->created_at);

            // check if last user created in the same month and year
            if ($lastDate->month == now()->month && $lastDate->year == now()->year) {
                // get last nik number and increment it by 1
                $lastNikNumber = intval(substr($lastUser->nik, 4)) + 1;
            } else {
                // start new sequence for the new month
                $lastNikNumber = 1;
            }
        } else {
            // start new sequence for the first user
            $lastNikNumber = 1;
        }

        // generate new nik
        $nik = now()->format('ym') . str_pad($lastNikNumber, 4, '0', STR_PAD_LEFT);
        $pass = Hash::make($data['password']);
        
        $returnUser = User::create([
                'nik' => $nik,
                'nama' => $data['name'],
                'nipns' => $data['nipns'],
                'email' => $data['email'],
                'gender' => $data['gender'],
                'telp' => $data['telp'],
                'password' => $pass,
                'is_admin' => 1
            ]);
    
        Password::create([
            'email' => $data['email'],
            'pass' => $pass
        ]);

        return $returnUser;
    }
}