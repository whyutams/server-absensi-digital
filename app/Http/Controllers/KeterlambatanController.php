<?php

namespace App\Http\Controllers;

use App\Models\Keterlambatan;
use Illuminate\Http\Request;

class KeterlambatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keterlambatans = Keterlambatan::all();

        return response()->json([
            'status' => 'success',
            'data' => $keterlambatans,
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
            "nis"=>"required|string",
            "nomor_hp"=>"required|string",
            "nama"=>"required|string",
            "keterangan"=>"required|string",
            "gambar"=>"nullable|mimes:jpg,jpeg,png",
        ]);

        if($request->hasFile('gambar')){
            $file = $request->file('gambar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/keterlambatan/'), $filename);
            $validated['gambar'] = 'uploads/keterlambatan/'.$filename;
        }

        Keterlambatan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
