<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Kamarrawatinap extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_kamarrawatinap';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'bangsal_id',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ],
//        'idbangsal' => [
//            'searchable' => true,
//        ]
    ];

    protected $dataTableRelationships = [
        "belongsTo" => [
            'bangsal' => [
                "model" => Bangsal::class,
                'foreign_key' => 'bangsal_id',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ]
    ];

    public function bangsal()
    {
        return $this->belongsTo(Bangsal::class, 'bangsal_id', 'id');
    }
}
