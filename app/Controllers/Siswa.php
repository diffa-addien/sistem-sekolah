<?php

namespace App\Controllers;

class Siswa extends BaseController
{
    public function tambahSiswa()
    {
        return view('pages/tambah_siswa');
    }

    public function daftarSiswa()
    {
        return view('pages/data_siswa');
    }
}
