<?php

namespace App\Models\Siska;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class AnalisisData extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_siska_analisisdata';
    public $timestamps = false;

    protected $fillable = [
        'idanalisis',
        'idformulir',
        'idformulirdata',
        'value',
        'insertedat',
        'insertedby',
        'updatedat',
        'updatedby'
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ],
        'idanalisis' => [
            'searchable' => true,
        ],
        'idformulir' => [
            'searchable' => true,
        ],
        'idformulirdata' => [
            'searchable' => true,
        ],
        'value' => [
            'searchable' => false,
        ],
    ];
}
