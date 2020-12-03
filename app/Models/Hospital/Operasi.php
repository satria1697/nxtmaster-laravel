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
        'idranap',
        'tgloperasi',
        'tglkeluar',
        'iddokter',
        'idicd10',
        'tindakan',
        'jenisanestesi',
        'idperawat',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'idranap' => [
            'searchable' => true,
        ],
        'tgloperasi' => [
            'searchable' => true,
        ],
        'tglkeluar' => [
            'searchable' => true,
        ],
        'iddokter' => [
            'searchable' => true,
        ],
        'idicd10' => [
            'searchable' => true,
        ],
        'tindakan' => [
            'searchable' => true,
        ],
        'jenisanestesi' => [
            'searchable' => true,
        ],
        'idperawat' => [
            'searchable' => true,
        ],
    ];
}
