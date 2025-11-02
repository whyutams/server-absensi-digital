<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthController extends Controller
{
    /**
     * LOGIN API (untuk aplikasi mobile)
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::with('jurusan')->where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username atau password salah'
            ], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nip' => $user->nip,
                'nama' => $user->nama,
                'nomor_hp' => $user->nomor_hp,
                'email' => $user->email,
                'alamat' => $user->alamat,
                'role' => $user->role,
                'foto_profil' => $user->foto_profil,
                'jurusan' => $user->jurusan,
            ],
            'timestamp' => now(),
            'token' => $token
        ], 200);
    }

    /**
     * LOGOUT (hapus token saat ini)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }

    public function logged_user()
    {
        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nip' => $user->nip,
                'nama' => $user->nama,
                'nomor_hp' => $user->nomor_hp,
                'email' => $user->email,
                'alamat' => $user->alamat,
                'role' => $user->role,
                'foto_profil' => $user->foto_profil,
                'jurusan' => $user->jurusan,
            ],
        ], 200);
    }

    public function update_profile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'nullable|string|max:500',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $user->nama = $request->nama;
        $user->nomor_hp = $request->nomor_hp;
        $user->email = $request->email;
        $user->alamat = $request->alamat;

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/foto_profil'), $filename);
            $user->foto_profil = 'storage/uploads/foto_profil/' . $filename;
        }

        $user->save();

        $user=User::with('jurusan')->find($user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui',
            'user' => $user,
        ], 200);
    }
}
