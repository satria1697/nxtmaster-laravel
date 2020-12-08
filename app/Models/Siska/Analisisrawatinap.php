<?php

namespace App\Models\Siska;

use App\Models\Hospital\Dokter;
use App\Models\Hospital\Perawat;
use App\Models\Hospital\Rawatinap;
use App\Models\Hospital\TenagaMedis;
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
        'dokter_id',
        'perawat_id',
        'idformulir',
        'idstatus',
        'jatuhtempo',
        'tgllengkap',
        'nilaianalisis',
        'nilaitotal',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
//        'idranap' => [
//            'searchable' => true,
//        ],
        'tglinput' => [
            'searchable' => true,
        ],
        'dokter_id' => [
            'searchable' => true,
        ],
        'perawat_id' => [
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
        'nilaianalisis' => [
            'searchable' => false,
        ],
        'nilaitotal' => [
            'searchable' => false,
        ],
    ];

    protected  $dataTableRelationships = [
        "belongsTo" => [
//            'dokter' => [
//                "model" => TenagaMedis::class,
//                'foreign_key' => 'dokter_id',
//                'columns' => [
//                    'namadokter' => [
//                        'searchable' => true,
//                        'orderable' => true,
//                    ],
//                ],
//            ],
//            'perawat' => [
//                "model" => TenagaMedis::class,
//                'foreign_key' => 'perawat_id',
//                'columns' => [
//                    'namaperawat' => [
//                        'searchable' => true,
//                        'orderable' => true,
//                    ],
//                ],
//            ],
            'ranap' => [
                "model" => Rawatinap::class,
                'foreign_key' => 'idranap',
                'columns' => [
                    'norm' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ],
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

    public function dokter()
    {
        return $this->belongsTo(TenagaMedis::class, 'jenis_id', 'id');
    }

    public function perawat()
    {
        return $this->belongsTo(TenagaMedis::class, 'jenis_id', 'id');
    }

    public function ranap()
    {
        return $this->belongsTo(Rawatinap::class, 'idranap', 'id');
    }
}
