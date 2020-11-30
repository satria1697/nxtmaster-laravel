<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class AksesUser extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_userroles';
    public $timestamps = false;

    protected $fillable = [
        'userid',
        'roleid',
    ];

    protected $dataTableColumns = [
        'userid' => [
            'searchable' => false,
        ],
        'roleid' => [
            'searchable' => false
        ]
    ];
}
