<?php

namespace App\Models\Siska;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Analisisrawatinap extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_siska_analisisrawatinap';
    public $timestamps = false;

    protected $fillable = [
        'idranap',
        'tglinput',
        'iddokter',
        'idperawat',
        'idformulir',
        'idstatus',
        'jatuhtempo',
        'tgllengkap',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'idranap' => [
            'searchable' => true,
        ],
        'tglinput' => [
            'searchable' => true,
        ],
        'iddokter' => [
            'searchable' => true,
        ],
        'idperawat' => [
            'searchable' => true,
        ],
        'idformulir' => [
            'searchable' => true,
        ],
        'idstatus' => [
            'searchable' => true,
        ],
        'jatuhtempo' => [
            'searchable' => true,
        ],
        'tgllengkap' => [
            'searchable' => true,
        ],
    ];

    protected  $dataTableRelationships = [
        "belongsToMany" => [
            "formulir" => [
                "model" => \App\Models\Siska\Formulir::class,
                "foreign_key" => "analisis_id",
                "pivot" => [
                    "table_name" => "nxt_siska_analisisformulir",
                    "primary_key" => "id",
                    "foreign_key" => "analisisid",
                    "local_key" => "formulirid",
                ],
                "order_by" => "description",
                "columns" => [
                    "description" => [
                        "searchable" => true,
                        "orderable" => true,
                    ]
                ],
            ],
        ],
    ];

    public function formulir() {
        return $this->belongsToMany(\App\Models\Siska\Formulir::class, 'nxt_siska_analisisformulir', 'analisisid', 'formulirid');
    }
}
