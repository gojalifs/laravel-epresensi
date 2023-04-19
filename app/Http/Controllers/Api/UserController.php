<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Password;
use App\Models\User;
use Auth;
use Carbon\Carbon;
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

        $token = $user->createToken('auth_token');
        $tokenValue = $token->plainTextToken;
        // $expire_at = $token->expires_at;

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
                'token' => $tokenValue,
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

    public function loginStatus(Request $request)
    {
        return $this->sendResponse(null, 'authenticated');
        // Get the currently authenticated user
        $user = Auth::user();

        // Get the personal access token for the user
        $tokens = $user->tokens;

        // Get the token value from the request headers
        $provided_token_value = $request->header('Authorization');

        // Remove the "Bearer " prefix from the token value
        $provided_token_value = str_replace('Bearer ', '', $provided_token_value);

        // Loop through the tokens and check if the provided value matches
        $valid_token = false;
        foreach ($tokens as $token) {
            if ($token->id === $provided_token_value) {
                $valid_token = true;
                break;
            }
        }
        // Check if the token value matches the provided value
        if ($valid_token) {
            echo 'The token is valid and has not been changed.';
        } else {
            echo 'The token is not valid or has been changed.';
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUser(Request $request)
    {
        $nik = $request->nik;
        $column = $request->column;
        $data = $request->data;

        $sqlFind = "SELECT nik FROM users WHERE nik = ?";
        $params = [$nik];
        $findResult = DB::select($sqlFind, $params);

        if (count($findResult) === 1) {


            $sql = "UPDATE users SET $column = ? WHERE nik = ?";
            $params = [$data, $nik];
            $affectedRows = DB::update($sql, $params);

            if ($affectedRows == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data updated'
                ]);

            } else {
                $user = User::where('nik', $request->nik)->first();
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully',
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
                        'token' => $request->bearerToken(),
                        'tokenExpiry' => null,
                        'createdAt' => $user->created_at,
                        'updatedAt' => $user->updated_at
                    ],
                    'token_type' => 'Bearer'
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => 'nik not found'
            ]);

        }

    }

    public function updateAvatar(Request $request)
    {
        $nik = $request->nik;

        // Mendapatkan file gambar yang diupload
        $file = $request->file('img');
        $extension = $file->getClientOriginalExtension();
        // Memformat nama file
        $filename = $nik . '_' . date("His") . '.' . $extension;
        // Menyimpan file gambar dengan nama yang sudah diformat
        $img_path = $file->storeAs('public/img/ava', $filename);
        $newImgPath = str_replace('public/', '', $img_path);

        $sql = "UPDATE users SET ava_path = ? WHERE nik = ?";
        $params = [$newImgPath, $nik];
        $affectedRows = DB::update($sql, $params);

        if ($affectedRows == 0) {
            return response()->json([
                'success' => false,
                'message' => 'No data updated'
            ]);
        }

        $user = User::where('nik', $request->nik)->first();

        return response()->json([
            'success' => true,
            'message' => 'Avatar updated' . $newImgPath,
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
                'token' => $request->bearerToken(),
                'tokenExpiry' => null,
                'createdAt' => $user->created_at,
                'updatedAt' => $user->updated_at
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}