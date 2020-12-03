<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Pasien extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_pasien';
    public $timestamps = false;

    protected $fillable = [
        'norm',
        'namapasien',
        'tempatlahir',
        'tanggallahir',
        'usia',
        'jeniskelamin',
        'agama',
        'alamat',
        'idwilayah',
        'pendidikan',
        'pekerjaan',
        'nohp',
        'asuransi',
        'nopeserta',
        'penanggungjawab',
        'nohppenanggungjawab',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'norm' => [
            'searchable' => true,
        ],
        'namapasien' => [
            'searchable' => true,
        ],
        'tempatlahir' => [
            'searchable' => true,
        ],
        'tanggallahir' => [
            'searchable' => true,
        ],
        'usia' => [
            'searchable' => true,
        ],
        'jeniskelamin' => [
            'searchable' => true,
        ],
        'agama' => [
            'searchable' => true,
        ],
        'alamat' => [
            'searchable' => true,
        ],
        'idwilayah' => [
            'searchable' => true,
        ],
        'pendidikan' => [
            'searchable' => true,
        ],
        'pekerjaan' => [
            'searchable' => true,
        ],
        'nohp' => [
            'searchable' => true,
        ],
        'asuransi' => [
            'searchable' => true,
        ],
        'nopeserta' => [
            'searchable' => true,
        ],
        'penanggungjawab' => [
            'searchable' => true,
        ],
        'nohppenanggungjawab' => [
            'searchable' => true,
        ],
    ];
}
