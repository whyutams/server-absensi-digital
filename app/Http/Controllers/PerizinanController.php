<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use Auth;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perizinan = Perizinan::all();

        return response()->json([
            'status' => 'success',
            'data' => $perizinan,
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
            "siswa_id" => "required|exists:siswas,id",
            "keterangan" => "required|string",
            "date" => "required",
            "gambar" => "required|image|mimes:jpeg,png,jpg",
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/perizinan/'), $filename);
            $validated['gambar'] = 'storage/uploads/perizinan/' . $filename;
        }

        if (Auth::check()) {
            $validated['created_by'] = Auth::id();
        }

        Perizinan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Perizinan $perizinan)
    {
        $data = Perizinan::with(['created_by', 'siswa.jurusan', 'siswa.orangtua'])->where('id', $perizinan->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perizinan $perizinan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perizinan $perizinan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perizinan $perizinan)
    {
        $perizinan->delete();

        if ($perizinan->gambar && file_exists(public_path($perizinan->gambar))) {
            unlink(public_path($perizinan->gambar));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.',
        ]);
    }

    public function status(Request $request, Perizinan $perizinan)
    {
        $validated = $request->validate([
            "status" => "required|in:" . Perizinan::STATUS_REJECTED . "," . Perizinan::STATUS_APPROVED,
        ]);

        $perizinan->update([
            "status" => $validated['status'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
        ]);
    }
}
