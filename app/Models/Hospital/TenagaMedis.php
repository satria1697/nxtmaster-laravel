<?php

namespace App\Models\Hospital;

use App\Models\Siska\Analisisrawatinap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class TenagaMedis extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_tenagamedis';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'nohp',
        'jenis_id',
        'spesialisasi_id',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'nama' => [
            'searchable' => true,
        ],
        'nohp' => [
            'searchable' => true,
        ],
//        'jenis_id' => [
//            'searchable' => true,
//        ],
//        'spesialisasi_id' => [
//            'searchable' => true,
//        ],
    ];

    protected $dataTableRelationships = [
        "belongsTo" => [
            'spesialisasi' => [
                "model" => Spesialisasi::class,
                'foreign_key' => 'spesialisasi_id',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'jenis' => [
                "model" => JenisTenagaMedis::class,
                'foreign_key' => 'jenis_id',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ]
    ];

    public function spesialisasi()
    {
        return $this->belongsTo(Spesialisasi::class, 'spesialisasi_id', 'id');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisTenagaMedis::class, 'jenis_id', 'id');
    }

    public function analisrawatinap() {
        return $this->hasMany(Analisisrawatinap::class, 'id');
    }
}
