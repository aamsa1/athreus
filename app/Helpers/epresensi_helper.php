<?php

use App\Models\PresensiModel;

function no_event()
{
    $presensimodel = new PresensiModel();
    $data = $presensimodel
        ->orderBy('id_event', 'DESC')
        ->get()->getRowObject();
    if ($data != null) {
        $noEvent = $data->no_event;

        // mengambil angka dari kode Produk terbesar, menggunakan fungsi substr
        // dan diubah ke integer dengan (int)
        $urutan = (int) substr($noEvent, 5, 4);

        // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
        $urutan++;

        // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
        // misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
        // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG
        $huruf = "QR-";
        $no_event = $huruf . sprintf("%03s", $urutan);
        return $no_event;
    } else {
        return 'QR-001';
    }
}
