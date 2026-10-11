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
    public function index(Request $request)
    {
        $query = Kelas::with(['jurusan', 'created_by', 'updated_by']);

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('kelas', 'like', "%{$search}%")
                  ->orWhereHas('jurusan', function ($jq) use ($search) {
                      $jq->where('nama', 'like', "%{$search}%")
                         ->orWhere('alias', 'like', "%{$search}%");
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
            'kelas' => 'required|numeric',
            'jumlah_kelas' => 'required|numeric',
            'jurusan_id' => 'required|exists:jurusans,id',
        ]);

        if (Auth::check()) {
            $validated['created_by'] = Auth::id();
        }

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
        $data = Kelas::with(['jurusan', 'created_by', 'updated_by'])->where('id', $kelas->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $data,
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

        $updateData = [
            'kelas' => $request['kelas'] ?? $kelas->kelas,
            'jumlah_kelas' => $request['jumlah_kelas'] ?? $kelas->jumlah_kelas,
            'jurusan_id' => $request['jurusan_id'] ?? $kelas->jurusan_id
        ];

        if (Auth::check()) {
            $updateData['updated_by'] = Auth::id();
        }

        $kelas->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
            'data' => $kelas->load(['jurusan', 'created_by', 'updated_by']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        try {
            $kelas->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data kelas tidak dapat dihapus karena masih terkait dengan data lain.'
            ], 422);
        }
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
                    'nama_kelas' => $this->toRoman($k->kelas) . ' ' . strtoupper($k->jurusan->alias ?? $k->jurusan->nama) . ' ' . $i
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    private function toRoman($num)
    {
        $romanNumerals = [
            10 => 'X',
            9 => 'IX',
            5 => 'V',
            4 => 'IV',
            1 => 'I'
        ];

        $roman = '';
        foreach ($romanNumerals as $value => $numeral) {
            while ($num >= $value) {
                $roman .= $numeral;
                $num -= $value;
            }
        }
        return $roman;
    }
}
