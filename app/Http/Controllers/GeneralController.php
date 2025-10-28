<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Keterlambatan;
use App\Models\Perizinan;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function info()
    {
    
        $kelas = Kelas::all();
        $jurusan = Jurusan::all();
        $keterlambatan = Keterlambatan::all();
        $perizinan = Perizinan::all();
        $siswa = Siswa::all();
        $user = User::all();


        return response()->json([
            'status' => 'success',
            'data' => [
                'kelas' => count($kelas),
                'jurusan' => count($jurusan),
                'keterlambatan' => count ($keterlambatan),
                'perizinan' => count ($perizinan),
                'siswa' => count  ($siswa),
                'user' => count ($user),
            ],
        ]);
    }
}