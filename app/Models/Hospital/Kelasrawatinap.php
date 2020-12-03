<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Kelasrawatinap extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_kelasrawatinap';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'description',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => true,
        ],
        'description' => [
            'searchable' => true,
        ]
    ];
}
