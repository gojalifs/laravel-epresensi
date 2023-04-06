<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Password;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nipns' => 'nullable|string|unique:users',
            'email' => 'required|string|email|unique:users|max:255',
            'gender' => 'required|in:L,P',
            'telp' => 'required|string|unique:users|max:18',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'nullable|string|max:128',
            'token_expiry' => 'nullable|date',
        ]);

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



        // validate the input
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $user = User::create([
            'nik' => $nik,
            'nama' => $request->name,
            'nipns' => $request->nipns,
            'email' => $request->email,
            'gender' => $request->gender,
            'telp' => $request->telp,
            'is_admin' => 0
        ]);

        Password::create([
            'user_nik' => $nik,
            'pass' => Hash::make($request->password),
        ]);

        /// save the token to tabel
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ],
        ], 201);

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $user = User::where('nik', $request->nik)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 401);
        }

        $password = Password::where('user_nik', $request->nik)->first();

        if (!$password || !Hash::check($request->password, $password->pass)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'nik' => $user->nik,
                'nama' => $user->nama,
                'nipns' => $user->nipns,
                'email' => $user->email,
                'gender' => $user->gender,
                'telp' => $user->telp,
                'isAdmin' => $user->is_admin,
                'avaPath' => $user->ava_path,
                'token' => $token,
                'tokenExpiry' => null,
                'createdAt' => $user->created_at,
                'updatedAt' => $user->updated_at
            ],
            'token_type' => 'Bearer'
        ]);
    }

    public function logout()
    {
        $user = User::find(Auth::user()->id);
        $user->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'logout success'
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}