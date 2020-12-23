<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class JenisTenagaMedis extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_jenistenagamedis';
    public $timestamps = false;

    protected $fillable = [
        'description',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ],
    ];

    protected $dataTableRelationships = [
        "hasMany" => [
            'tenagamedis' => [
                "model" => TenagaMedis::class,
                'foreign_key' => 'jenis_id',
                'columns' => [
                    'nama' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ]
    ];

    public function tenagamedis() {
        return $this->hasMany(TenagaMedis::class, 'jenis_id', 'id');
    }
}
