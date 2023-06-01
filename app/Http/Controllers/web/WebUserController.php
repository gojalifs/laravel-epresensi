<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Password;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WebUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $users = User::all();
        return view('content.user-content')->with('users', $users);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $users = User::where('nama', 'LIKE', '%' . $keyword . '%')
            ->orWhere('nik', 'LIKE', '%' . $keyword . '%')
            ->get();
        return view('content.user-content')->with('users', $users);
    }

    public function add(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'nipns' => 'nullable|unique:users,nipns',
            'gender' => 'required',
            'telp' => 'required|unique:users,telp',
            'password' => 'required',
            'is_admin' => 'nullable',
        ]);

        $user = new User();
        $user->nama = $request->nama;
        $user->nipns = $request->nipns;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->telp = $request->telp;
        $user->is_admin = 0;
        $user->password = Hash::make($request->password);

        if ($request->is_admin == 'on') {
            $user->is_admin = 1;
        } else {
            $user->is_admin = 0;
        }
        $user->save();

        return redirect()->route('users-list')->with('success', 'User added successfully.');
    }

    public function updateUser(Request $request)
    {
        Validator::make($request->all(), [
            'nik' => 'required',
            'name' => 'required|string|max:255',
            'nipns' => 'nullable|string|unique:users',
            'email' => 'required|string|email|unique:users|max:255',
            'gender' => 'required|in:L,P',
            'telp' => 'required|string|unique:users|max:18',
            'password' => 'nullable|string|min:8',
            'is_admin' => 'required',
        ]);

        $nik = $request->nik;
        $nama = $request->name;
        $nipns = $request->nipns ?? null;
        $email = $request->email;
        $gender = $request->gender;
        $telp = $request->telp;
        $password = Hash::make($request->password);

        $sqlFind = "SELECT nik FROM users WHERE nik = ?";
        $params = [$nik];
        $findResult = DB::select($sqlFind, $params);

        if (count($findResult) === 1) {

            $oldPassword = DB::select("SELECT pass FROM passwords WHERE user_nik = ? AND pass = ?", [$nik, $password]);
            if (count($oldPassword) != 1) {
                DB::update("UPDATE passwords SET pass = ? WHERE user_nik = ?", [$password, $nik]);
            }

            $sql = "UPDATE users SET nama = ?, nipns = ?, email = ?, gender = ?, telp = ?, password = ? WHERE nik = ?";
            $params = [$nama, $nipns, $email, $gender, $telp, $password, (int) $nik];

            try {
                $affectedRows = DB::update($sql, $params);
            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage();
                exit;
            }

            if ($affectedRows == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data updated'
                ]);

            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully',
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => 'nik not found'
            ]);
        }
    }

    public function delete(Request $request)
    {
        $nik = $request->input('nik');
        $user = User::where('nik', $nik)->first();

        if ($user) {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }
    }

    public function setRole(Request $request)
    {
        try {
            $request->validate([
                'is_admin' => 'required',
                'nik' => 'required'
            ]);

            $nik = $request->input('nik');

            $user = User::where('nik', $nik)->first();

            // Jika user tidak ditemukan, kembalikan respons error 404
            if (!$user) {
                return response()->json([
                    'error' => 'User not found',
                    'nik' => $nik,
                ], 404);
            }

            // Perbarui nilai kolom is_admin
            $user->is_admin = $request->input('is_admin');
            $user->save();

            // Kembalikan respons berhasil
            $users = User::all();
            return view('content.user-content')->with('users', $users);
            // return view('content.user-content', ['users' => $users]);
            return response()->json([
                'message' => 'User updated successfully',
                'users' => $user,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tangkap pengecualian ModelNotFoundException dan kembalikan respons error 404
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            // Tangkap pengecualian umum dan kembalikan respons error 500 dengan pesan pengecualian
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function admins(Request $request)
    {
        $admins = Admin::all();
        return view('content.admin')->with('admins', $admins);
    }
}