<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class AksesManager extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_rolemanagers';
    protected $fillable = [
        'roleid',
        'rolelevelid',
        'parentid',
        'text',
        'applicationid',
        'moduleid',
        'icon',
    ];
    public $timestamps = false;
    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'roleid' => [
            'searchable' => true,
        ],
        'rolelevelid' => [
            'searchable' => true,
        ],
        'parentid' => [
            'searchable' => false,
        ],
        'text' => [
            'searchable' => true,
        ],
//        'applicationid' => [
//            'searchable' => true,
//        ],
        'icon' => [
            'searchable' => false,
        ],
    ];
    protected $dataTableRelationships =[
        "belongsTo" => [
            'modul' => [
                "model" => \App\Models\Admin\Modul::class,
                'foreign_key' => 'moduleid',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'application' => [
                "model" => \App\Models\Admin\Application::class,
                'foreign_key' => 'applicationid',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
//            'parent' => [
//                "model" => \App\Models\Admin\AksesManager::class,
//                'foreign_key' => 'parentid',
//                'columns' => [
//                    'text' => [
//                        'searchable' => false,
//                        'orderable' => false,
//                    ],
//                ],
//            ],
        ]
    ];

    public function modul() {
        return $this->belongsTo(\App\Models\Admin\Modul::class, 'moduleid', 'id');
    }

    public function application() {
        return $this->belongsTo(\App\Models\Admin\Application::class, 'applicationid', 'id');
    }
}
