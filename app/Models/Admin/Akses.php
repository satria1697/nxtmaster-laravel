<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Akses extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_roles';
    protected $fillable = ['description', 'active'];
    public $timestamps = false;
    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'description' => [
            'searchable' => true,
        ],
        'active' => [
            'searchable' => false,
        ],
        'title' => [
            'searchable' => true,
        ]
    ];
}
