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
        'idkelas',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ],
        'idkelas' => [
            'searchable' => true,
        ]
    ];
}
