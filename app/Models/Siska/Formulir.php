<?php

namespace App\Models\Siska;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Formulir extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_siska_formulir';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'required',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ],
        'required' => [
            'searchable' => false,
        ]
    ];

    protected $dataTableRelationships = [
        "hasMany" => [
            'formulirdata' => [
                "model" => \App\Models\Siska\FormulirData::class,
                'foreign_key' => 'formulirid',
                'columns' => [
//                    'orderby' => 'keyid',
                    'keyid' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ],
    ];

    public function formulirdata()
    {
        return $this->hasMany(\App\Models\Siska\FormulirData::class, 'formulirid', 'id')->orderBy('keyid');
    }
}
