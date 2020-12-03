<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Dokter extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_dokter';
    public $timestamps = false;

    protected $fillable = [
        'namadokter',
        'nohp',
        'idspesialisasi'
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'namadokter' => [
            'searchable' => true,
        ],
        'nohp' => [
            'searchable' => true,
        ],
        'idspesialisasi' => [
            'searchable' => true,
        ]
    ];
}
