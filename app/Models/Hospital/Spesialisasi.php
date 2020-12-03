<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Spesialisasi extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_spesialisasi';
    public $timestamps = false;

    protected $fillable = [
        'spesialisasi'
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'spesialisasi' => [
            'searchable' => true,
        ]
    ];
}
