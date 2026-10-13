<?php

namespace App\Http\Controllers;

use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Siswa::with(['jurusan', 'orangtua', 'created_by', 'updated_by']);

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

        $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');

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

        if (Auth::check()) {
            $validated['created_by'] = Auth::id();
        }

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
        $data = Siswa::with(['jurusan', 'orangtua', 'created_by', 'updated_by'])->where('id', $siswa->id)->first();

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

        $updateData = [];
        if ($request->has('nis')) $updateData['nis'] = $request->input('nis');
        if ($request->has('nama')) $updateData['nama'] = $request->input('nama');
        if ($request->has('nomor_hp')) $updateData['nomor_hp'] = $request->input('nomor_hp');
        if ($request->has('jurusan_id')) $updateData['jurusan_id'] = $request->input('jurusan_id');
        if ($request->has('kelas')) $updateData['kelas'] = $request->input('kelas');
        if ($request->has('orangtua_id')) $updateData['orangtua_id'] = $request->input('orangtua_id');
        elseif ($request->has('user_id')) $updateData['orangtua_id'] = $request->input('user_id');

        if (Auth::check()) {
            $updateData['updated_by'] = Auth::id();
        }

        $siswa->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diupdate.',
            'data' => $siswa->load(['jurusan', 'orangtua', 'created_by', 'updated_by']),
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

    /**
     * Unduh template berkas Excel untuk import data siswa
     */
    public function export_template()
    {
        return Excel::download(new SiswaTemplateExport(), 'template_import_siswa.xlsx');
    }

    /**
     * Import data siswa dari berkas Excel (mendukung preview/dry-run)
     */
    public function import_excel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
            'preview' => 'nullable',
        ]);

        $preview = filter_var($request->input('preview', false), FILTER_VALIDATE_BOOLEAN);
        $importer = new SiswaImport($preview);
        $result = $importer->import($request->file('file'));

        return response()->json($result);
    }
}
