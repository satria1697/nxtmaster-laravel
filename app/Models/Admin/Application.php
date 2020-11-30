<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Application extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_applications';
    protected $fillable = [
        'name',
        'description',
        'path',
    ];
    public $timestamps = false;
    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'name' => [
            'searchable' => true,
        ],
        'description' => [
            'searchable' => true,
        ],
        'path' => [
            'searchable' => true,
        ],
    ];
}
