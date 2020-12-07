<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Dokter extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_tenagamedis';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'nohp',
        'jenistm',
        'spesialisasi_id'
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
        'jenistm' => [
            'searchable' => true,
        ],
        'spesialisasi_id' => [
            'searchable' => true,
        ],
    ];

    protected $dataTableRelationships = [
        "belongsTo" => [
            'spesialisasi' => [
                "model" => Spesialisasi::class,
                'foreign_key' => 'spesialisasi_id',
                'columns' => [
                    'spesialisasi' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ]
    ];

    public function spesialisasi()
    {
        return $this->belongsTo(Spesialisasi::class, 'idspesialisasi', 'id');
    }
}
