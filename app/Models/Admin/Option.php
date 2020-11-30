<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Option extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_options';
    protected $fillable = [
        'header1',
        'header2',
        'companycode',
        'companyname',
        'companyaddress',
        'companycity',
        'companyphone',
        'companyfax',
        'companyemail',
        'avatar',
    ];
    public $timestamps = false;
    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ]
    ];
}
