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
        'idbangsal',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ],
        'idbangsal' => [
            'searchable' => true,
        ]
    ];
}
