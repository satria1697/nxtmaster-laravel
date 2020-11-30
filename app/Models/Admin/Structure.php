<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Structure extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_structures';
    protected $fillable = ['structurelevelid', 'parentid', 'signabilty'];
    public $timestamps = false;
    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'label' => [
            'searchable' => true,
        ],
//        'structurelevelid' => [
//            'searchable' => true,
//        ],
        'parentid' => [
            'searchable' => true,
        ],
        'signability' => [
            'searchable' => false,
        ],
        'image_url' => [
            'searchable' => false,
        ],
        'expand' => [
            'searchable' => false,
        ]
    ];
    protected $dataTableRelationships = [
        "belongsTo" => [
            'structurelevel' => [
                "model" => \App\Models\Admin\StructureLevel::class,
                'foreign_key' => 'structurelevelid',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ],
    ];
    public function structurelevel()
    {
        return $this->belongsTo(\App\Models\Admin\StructureLevel::class, 'structurelevelid', 'id');
    }
}
