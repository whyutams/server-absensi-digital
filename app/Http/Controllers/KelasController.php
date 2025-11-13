<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::all();

        return response()->json([
            'status' => 'success',
            'data' => $kelas,
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
            'kelas' => 'required|numeric',
            'jumlah_kelas' => 'required|numeric',
            'jurusan_id' => 'required|exists:jurusans,id',
        ]);

        Kelas::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        return response()->json([
            'status' => 'success',
            'data' => $kelas,
        ]);    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'kelas' => 'nullable|numeric',
            'jumlah_kelas' => 'nullable|numeric',
            'jurusan_id' => 'nullable|exists:jurusans,id',
        ]);

        $kelas->update([
            'kelas' => $request['kelas'] ?? $kelas->kelas,
            'jumlah_kelas' => $request['jumlah_kelas'] ?? $kelas->jumlah_kelas,
            'jurusan_id' => $request['jurusan_id'] ?? $kelas->jurusan_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.'
        ]);
    }

    public function generate_list_aturan_kelas()
    {
        $jurusans = Jurusan::all();

        $result = [];

        foreach ($jurusans as $jurusan) {
            for ($i = 0; $i < 3; $i++) {
                $result[] = [
                    'kelas' => 10 + ($i),
                    'jumlah_kelas' => 3,
                    'jurusan_id' => $jurusan->id
                ];
            }
        }

        Kelas::truncate();
        Kelas::insert($result);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil di-generate.'
        ]);
    }

    public function list_aturan_kelas()
    {
        $kelas = Kelas::with('jurusan')->get();

        $result = [];

        foreach ($kelas as $k) {
            for ($i = 1; $i <= (int) $k->jumlah_kelas; $i++) {
                $result[] = [
                    'kelas' => $k->kelas,
                    'jumlah_kelas' => $k->jumlah_kelas,
                    'jurusan' => $k->jurusan->nama ?? null,
                    'nama_kelas' => strtoupper($k->kelas) . ' ' . strtoupper($k->jurusan->alias ?? $k->jurusan->nama) . ' ' . $i
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }
}
