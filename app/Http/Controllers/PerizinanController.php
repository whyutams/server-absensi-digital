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
            "nis" => "required|string",
            "nomor_hp" => "required|string",
            "nama" => "required|string",
            "gambar" => "required|image|mimes:jpeg,png,jpg",
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/perizinan/'), $filename);
            $validated['gambar'] = 'storage/uploads/perizinan/' . $filename;
        }

        if (Auth::check()){
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
        //
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
        $validated = $request->validate([
            "nis" => "string",
            "nomor_hp" => "string",
            "nama" => "string",
            "gambar" => "image|mimes:jpeg,png,jpg",
        ]);
        if(request->hasFile('gambar')){
            $file = $request->file('gambar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/uploads/perizinan/'), $filename);
            $validated['gambar'] = 'storage/uploads/perizinan/'.$filename;

            if ($perizinan->gambar && file_exists(public_path($perizinan->gambar))) {
                unlink(public_path($perizinan->gambar));
            }
        }
            $perizinan->update([
                "nis" => $validated['nis'] ?? $perizinan->nis,
                "nomor_hp" => $validated['nomor_hp'] ?? $perizinan->nomor_hp,
                "nama" => $validated['nama'] ?? $perizinan->nama,
                "gambar" => $validated['gambar'] ?? $perizinan->gambar,
            ]);
            
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perizinan $perizinan)
    {
        if ($perizinan->gambar && file_exists(public_path($perizinan->gambar))) {
            unlink(public_path($perizinan->gambar));
        }

        $perizinan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}
