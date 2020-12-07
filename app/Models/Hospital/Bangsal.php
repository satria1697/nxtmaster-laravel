<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Bangsal extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_bangsal';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'kelas_id',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ],
//        'idkelas' => [
//            'searchable' => true,
//        ]
    ];

    protected $dataTableRelationships = [
        "belongsTo" => [
            'kelasranap' => [
                "model" => Kelasrawatinap::class,
                'foreign_key' => 'kelas_id',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ]
    ];

    public function kelasranap()
    {
        return $this->belongsTo(Kelasrawatinap::class, 'kelas_id', 'id');
    }
}
