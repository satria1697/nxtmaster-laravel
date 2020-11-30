<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class RoleLevel extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_rolelevels';
    protected $fillable = [];
    public $timestamps = false;
}
