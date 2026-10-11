<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('jurusan');

        if ($request->filled('role')) {
            $query->where('role', $request->query('role'));
        } else {
            $query->whereNotIn('role', [User::ROLE_SUPERADMIN, User::ROLE_KEPSEK]);
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        $query->latest();

        $perPage = $request->query('per_page', 15);
        if ($perPage === 'all') {
            $data = $query->get();
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'total' => $data->count(),
            ]);
        }

        $perPage = max(1, (int)$perPage);
        $paginated = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $paginated->items(),
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
            'has_more' => $paginated->hasMorePages(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nip" => "nullable|string|unique:users,nip",
            "nama" => "required|string",
            "jurusan_id" => "nullable|exists:jurusans,id",
            "nomor_hp" => "nullable|unique:users,nomor_hp",
            "email" => "nullable|email|unique:users,email",
            "alamat" => "nullable|string",
            "foto_profil" => "nullable|image|mimes:jpg,jpeg,png",
            "role" => "required|in:" . User::ROLE_SUPERADMIN . "," . User::ROLE_GURU_PIKET . "," . User::ROLE_GURU . "," . User::ROLE_KESISWAAN . "," . User::ROLE_KEPSEK . "," . User::ROLE_BK  . "," . User::ROLE_SATPAM . "," . User::ROLE_ORANGTUA,
            "username" => "required|string|unique:users,username",
            "password" => "required|string|min:6",
        ]);

        // mengubah username dan email menjadi huruf kecil semua
        $validated['username'] = strtolower($validated['username']);
        if (isset($validated['email'])) {
            $validated['email'] = strtolower($validated['email']);
        }

        // Handle file upload
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/foto_profil/'), $filename);
            $validated['foto_profil'] = 'storage/uploads/foto_profil/' . $filename;
        }

        User::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $data = User::with('jurusan')->where('id', $user->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "nip" => "nullable|string|unique:users,nip," . $user->id,
            "nama" => "nullable|string",
            "nomor_hp" => "nullable|unique:users,nomor_hp," . $user->id,
            "email" => "nullable|email|unique:users,email," . $user->id,
            "alamat" => "nullable|string",
            "foto_profil" => "nullable|image|mimes:jpg,jpeg,png",
            "role" => "nullable|in:" . User::ROLE_SUPERADMIN . "," . User::ROLE_GURU_PIKET . "," . User::ROLE_GURU . "," . User::ROLE_KESISWAAN . "," . User::ROLE_KEPSEK,
            "username" => "nullable|string|unique:users,username," . $user->id,
            "password" => "nullable|string|min:6",
        ]);

        // Handle file upload
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/foto_profil/'), $filename);
            $validated['foto_profil'] = 'storage/uploads/foto_profil/' . $filename;

            // cek dan hapus gambar lama jika ada
            if ($user->foto_profil && file_exists(public_path($user->foto_profil))) {
                unlink(public_path($user->foto_profil));
            }
        }

        // Update data (jika field tidak diisi, gunakan nilai lama)
        $user->update([
            "nip" => $request['nip'] ?? $user->nip,
            "nomor_hp" => $request['nomor_hp'] ?? $user->nomor_hp,
            "nama" => $request['nama'] ?? $user->nama,
            "alamat" => $request['alamat'] ?? $user->alamat,
            "foto_profil" => $validated['foto_profil'] ?? $user->foto_profil,
            "username" => $request['username'] ? strtolower($validated['username']) : $user->username,
            "email" => $request['email'] ? strtolower($validated['email']) : $user->email,
            "role" => $request['role'] ?? $user->role,
            "password" => isset($validated['password']) ? bcrypt($validated['password']) : $user->password,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->role == User::ROLE_SUPERADMIN) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak dapat dihapus.',
            ], 403);
        }

        $user->delete();

        if ($user->foto_profil && file_exists(public_path($user->foto_profil))) {
            unlink(public_path($user->foto_profil));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}
