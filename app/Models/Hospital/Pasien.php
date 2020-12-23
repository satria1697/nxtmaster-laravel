<?php

namespace App\Models\Hospital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Pasien extends Model
{
    use HasFactory, LaravelVueDatatableTrait;
    protected $table = 'nxt_hospital_pasien';
    public $timestamps = false;

    protected $fillable = [
        'norm',
        'namapasien',
        'tempatlahir',
        'tanggallahir',
        'usia',
        'jeniskelamin_id',
        'agama_id',
        'alamat',
        'wilayah_id',
        'pendidikan_id',
        'pekerjaan_id',
        'nohp',
        'asuransi',
        'nopeserta',
        'penanggungjawab',
        'nohppenanggungjawab',
    ];

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
        ],
        'norm' => [
            'searchable' => true,
        ],
        'namapasien' => [
            'searchable' => true,
        ],
        'tempatlahir' => [
            'searchable' => true,
        ],
        'tanggallahir' => [
            'searchable' => true,
        ],
        'usia' => [
            'searchable' => true,
        ],
//        'jeniskelamin' => [
//            'searchable' => true,
//        ],
//        'agama' => [
//            'searchable' => true,
//        ],
        'alamat' => [
            'searchable' => true,
        ],
        'wilayah_id' => [
            'searchable' => true,
        ],
//        'pendidikan' => [
//            'searchable' => true,
//        ],
//        'pekerjaan' => [
//            'searchable' => true,
//        ],
        'nohp' => [
            'searchable' => true,
        ],
        'asuransi' => [
            'searchable' => true,
        ],
        'nopeserta' => [
            'searchable' => true,
        ],
        'penanggungjawab' => [
            'searchable' => true,
        ],
        'nohppenanggungjawab' => [
            'searchable' => true,
        ],
    ];

    protected $dataTableRelationships = [
        "belongsTo" => [
            'agama' => [
                "model" => Agama::class,
                'foreign_key' => 'agama_id',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'pendidikan' => [
                "model" => Pendidikan::class,
                'foreign_key' => 'pendidikan_id',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'pekerjaan' => [
                "model" => Pekerjaan::class,
                'foreign_key' => 'pekerjaan_id',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'jeniskelamin' => [
                "model" => Jeniskelamin::class,
                'foreign_key' => 'jeniskelamin_id',
                'columns' => [
                    'description' => [
                        'searchable' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ]
    ];

    public function agama()
    {
        return $this->belongsTo(Agama::class, 'agama_id', 'id');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id', 'id');
    }

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id', 'id');
    }

    public function jeniskelamin()
    {
        return $this->belongsTo(Jeniskelamin::class, 'jeniskelamin_id', 'id');
    }
}
