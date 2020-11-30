<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Modul extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_modules';
    protected $fillable = [
        'name',
        'description',
        'applicationid',
        'path'
    ];
    public $timestamps = false;
    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'name' => [
            'searchable' => true,
        ],
        'description' => [
            'searchable' => true,
        ],
//        'applicationid' => [
//            'searchable' => true,
//        ],
        'path' => [
            'searchable' => true,
        ],
    ];
    protected $dataTableRelationships = [
        "belongsTo" => [
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
        ],
    ];
    public function application()
    {
        return $this->belongsTo(\App\Models\Admin\Application::class, 'applicationid', 'id');
    }
}
