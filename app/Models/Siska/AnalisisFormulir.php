<?php

namespace App\Models\Siska;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class AnalisisFormulir extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_siska_analisisformulir';
    public $timestamps = false;

    protected $fillable = [
        'analisisid',
        'formulirid'
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'analisisid' => [
            'searchable' => false,
        ],
//        'formulirid' => [
//            'searchable' => false,
//        ],
    ];

    protected $dataTableRelationships = [
        "belongsTo" => [
            'formulir' => [
                "model" => \App\Models\Siska\Formulir::class,
                'foreign_key' => 'formulirid',
                'columns' => [
                    'keyid' => [
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
