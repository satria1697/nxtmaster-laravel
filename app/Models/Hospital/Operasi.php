<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Operasi extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_operasi';
    public $timestamps = false;

    protected $fillable = [
        'ranap_id',
        'tgloperasi',
        'tglkeluar',
        'dokter_id',
        'dokteranestesi_id',
        'icd10_id',
        'tindakan',
        'jenisanestesi',
        'perawat_id',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'ranap_id' => [
            'searchable' => true,
        ],
        'tgloperasi' => [
            'searchable' => true,
        ],
        'tglkeluar' => [
            'searchable' => true,
        ],
        'dokter_id' => [
            'searchable' => true,
        ],
        'dokteranestesi_id' => [
            'searchable' => true,
        ],
        'icd10_id' => [
            'searchable' => true,
        ],
        'tindakan' => [
            'searchable' => true,
        ],
        'jenisanestesi' => [
            'searchable' => true,
        ],
        'perawat_id' => [
            'searchable' => true,
        ],
    ];
}
