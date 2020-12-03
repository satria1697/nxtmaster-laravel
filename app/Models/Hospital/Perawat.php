<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Perawat extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_perawat';
    public $timestamps = false;

    protected $fillable = [
        'namaperawat',
        'nohp',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'namaperawat' => [
            'searchable' => true,
        ],
        'nohp' => [
            'searchable' => true,
        ],
    ];
}
