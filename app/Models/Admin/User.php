<?php

namespace App\Models\Admin;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, LaravelVueDatatableTrait;

    public $table = 'nxt_users';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname',
        'username',
        'email',
        'password',
        'levelid',
        'empid',
        'structureid',
        'rankid',
        'phone',
        'address',
        'city',
        'active',
        'avatar'
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'fullname' => [
            'searchable' => true,
        ],
        'email' => [
            'searchable' => true,
        ],
        'avatar' => [
            'searchable' => false,
        ],
        'username' => [
            'searchable' => true,
        ],
        'address' => [
            'searchable' => false,
        ],
        'city' => [
            'searchable' => false,
        ],
        'phone' => [
            'searchable' => false,
        ],
        'active' => [
            'searchable' => false,
        ],
        'empid' => [
            'searchable' => true,
        ]
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier() {

        return $this->getKey();

    }

    public function getJWTCustomClaims() {

        return [];

    }

    protected $dataTableRelationships = [
        "belongsTo" => [
            'level' => [
                "model" => \App\Models\Admin\Level::class,
                'foreign_key' => 'levelid',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'rank' => [
                "model" => \App\Models\Admin\Rank::class,
                'foreign_key' => 'rankid',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'structure' => [
                "model" => \App\Models\Admin\Structure::class,
                'foreign_key' => 'structureid',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ],
        "belongsToMany" => [
            "akses" => [
                "model" => \App\Models\Admin\Akses::class,
                "foreign_key" => "role_id",
                "pivot" => [
                    "table_name" => "nxt_userroles",
                    "primary_key" => "id",
                    "foreign_key" => "roleid",
                    "local_key" => "userid",
                ],
                "order_by" => "description",
                "columns" => [
                    "description" => [
                        "searchable" => true,
                        "orderable" => true,
                    ]
                ],
            ],
        ],
    ];

    public function level()
    {
        return $this->belongsTo(\App\Models\Admin\Level::class, 'levelid', 'id');
    }

    public function rank()
    {
        return $this->belongsTo(\App\Models\Admin\Rank::class, 'rankid', 'id');
    }

    public function structure()
    {
        return $this->belongsTo(\App\Models\Admin\Structure::class, 'structureid', 'id');
    }

    public function akses() {
        return $this->belongsToMany(\App\Models\Admin\Akses::class, 'nxt_userroles', 'userid', 'roleid');
    }
}
