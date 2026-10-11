<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Siswa::with(['jurusan', 'orangtua']);

        if ($user && $user->role === User::ROLE_GURU) {
            $query->where('jurusan_id', $user->jurusan_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhereHas('orangtua', function ($oq) use ($search) {
                      $oq->where('nama', 'like', "%{$search}%");
                  });
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
            'nis' => 'nullable|unique:siswas,nis',
            'nama' => 'nullable|string',
            'nomor_hp' => 'nullable|string',
            'jurusan_id' => 'nullable|exists:jurusans,id',
            'kelas' => 'nullable',
            'orangtua_id' => 'nullable|exists:users,id',
        ]);

        Siswa::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $data = Siswa::with(['jurusan', 'orangtua'])->where('id', $siswa->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => 'nullable|unique:siswas,nis,' . $siswa->id,
            'nama' => 'nullable|string',
            'nomor_hp' => 'nullable|string',
            'jurusan_id' => 'nullable|exists:jurusans,id',
            'kelas' => 'nullable',
            'orangtua_id' => 'nullable|exists:users,id',
        ]);

        $siswa->update([
            'nis' => $request['nis'] ?? $siswa->nis,
            'nama' => $request['nama'] ?? $siswa->nama,
            'nomor_hp' => $request['nomor_hp'] ?? $siswa->nomor_hp,
            'jurusan_id' => $request['jurusan_id'] ?? $siswa->jurusan_id,
            'kelas_id' => $request['kelas_id'] ?? $siswa->kelas_id,
            'orangtua_id' => $request['orangtua_id'] ?? $siswa->orangtua_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}
