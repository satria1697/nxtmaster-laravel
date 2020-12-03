<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Icd extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_icd10';
    public $timestamps = false;

    protected $fillable = [
        'kodeicd10',
        'diagnosis',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'kodeicd10' => [
            'searchable' => true,
        ],
        'diagnosis' => [
            'searchable' => true,
        ]
    ];
}
