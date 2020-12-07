<?php

namespace App\Models\Siska;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Laporan extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_siska_analisisrawatinap';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'warna',
        'nilai'
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ],
        'warna' => [
            'searchable' => false,
        ],
        'nilai' => [
            'searchable' => false,
        ],
    ];
}
