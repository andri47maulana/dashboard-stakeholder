<?php
namespace App\Imports;

use App\Models\DerajatHubungan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DerajatHubunganImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new DerajatHubungan([
            'id_unit' => $row['id_unit'],
            'lingkungan' => $row['lingkungan'],
            'ekonomi' => $row['ekonomi'],
            'pendidikan' => $row['pendidikan'],
            'sosial_kesesjahteraan' => $row['sosial_kesesjahteraan'],
            'okupasi' => $row['okupasi'],
            'skor_socmap' => $row['skor_socmap'],
            'prioritas_socmap' => $row['prioritas_socmap'],
            'kepuasan' => $row['kepuasan'],
            'kontribusi' => $row['kontribusi'],
            'komunikasi' => $row['komunikasi'],
            'kepercayaan' => $row['kepercayaan'],
            'keterlibatan' => $row['keterlibatan'],
            'indeks_kepuasan' => $row['indeks_kepuasan'],
            'derajat_hubungan' => $row['derajat_hubungan'],
            'deskripsi' => $row['deskripsi'],
            'tahun' => $row['tahun'],
            'input_date' => now(),
            'modified_date' => now(),
        ]);
    }
}
