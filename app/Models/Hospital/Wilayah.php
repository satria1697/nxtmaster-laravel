<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Wilayah extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_wilayah';
    public $timestamps = false;

    protected $fillable = [
        'nama',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'nama' => [
            'searchable' => true,
        ]
    ];
}
