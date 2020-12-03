<?php

namespace App\Models\Siska;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Analisisoperasi extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_siska_analisisoperasi';
    public $timestamps = false;

    protected $fillable = [
        'idanalisis',
        'idoperasi',
        'idformulir',
        'idstatus',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'idanalisis' => [
            'searchable' => true,
        ],
        'idoperasi' => [
            'searchable' => true,
        ],
        'idformulir' => [
            'searchable' => true,
        ],
        'idstatus' => [
            'searchable' => true,
        ],
    ];
}
