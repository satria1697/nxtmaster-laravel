<?php

namespace App\Models\Siska;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class FormulirData extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_siska_formulirdata';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'formulirid',
        'keyid'
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ],
//        'formulirid' => [
//            'searchable' => true,
//        ],
        'keyid' => [
            'searchable' => false,
        ],
    ];

    protected $dataTableRelationships = [
        "belongsTo" => [
            'formulir' => [
                "model" => \App\Models\Siska\Formulir::class,
                'foreign_key' => 'formulirid',
//                'orderby' => 'keyid',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ]
    ];

    public function formulir()
    {
        return $this->belongsTo(\App\Models\Siska\Formulir::class, 'formulirid', 'id');
    }
}
