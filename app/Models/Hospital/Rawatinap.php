<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Rawatinap extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_rawatinap';
    public $timestamps = false;

    protected $fillable = [
        'idpasien',
        'tglmasuk',
        'tglkeluar',
        'idkelas',
        'idbangsal',
        'idkamar',
        'iddokter',
        'jeniskasus',
        'tindakan',
        'caramasuk',
        'ketpulang',
        'carabayar',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'idpasien' => [
            'searchable' => true,
        ],
        'tglmasuk' => [
            'searchable' => true,
        ],
        'tglkeluar' => [
            'searchable' => true,
        ],
        'idkelas' => [
            'searchable' => true,
        ],
        'idbangsal' => [
            'searchable' => true,
        ],
        'idkamar' => [
            'searchable' => true,
        ],
        'iddokter' => [
            'searchable' => true,
        ],
        'jeniskasus' => [
            'searchable' => true,
        ],
        'tindakan' => [
            'searchable' => true,
        ],
        'caramasuk' => [
            'searchable' => true,
        ],
        'ketpulang' => [
            'searchable' => true,
        ],
        'carabayar' => [
            'searchable' => true,
        ],
    ];
}
