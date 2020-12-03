<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Diagnosisrawatinap extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_diagnosisrawatinap';
    public $timestamps = false;

    protected $fillable = [
        'idranap',
        'idicd10',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'idranap' => [
            'searchable' => true,
        ],
        'idicd10' => [
            'searchable' => true,
        ]
    ];
}
