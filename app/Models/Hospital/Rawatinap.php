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
        'pasien_id',
        'norm',
        'tglmasuk',
        'tglkeluar',
        'kelas_id',
        'bangsal_id',
        'kamar_id',
        'dokter_id',
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
        'norm' => [
            'searchable' => true,
        ],
        'tglmasuk' => [
            'searchable' => true,
        ],
        'tglkeluar' => [
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

    protected $dataTableRelationships = [
        "belongsTo" => [
            'kelas' => [
                "model" => Kelasrawatinap::class,
                'foreign_key' => 'kelas_id',
                'columns' => [
                    'description' => [
                        'description' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'bangsal' => [
                "model" => Bangsal::class,
                'foreign_key' => 'bangsal_id',
                'columns' => [
                    'description' => [
                        'description' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'kamar' => [
                "model" => Kamarrawatinap::class,
                'foreign_key' => 'kamar_id',
                'columns' => [
                    'description' => [
                        'description' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'dokter' => [
                "model" => Dokter::class,
                'foreign_key' => 'dokter_id',
                'columns' => [
                    'namadokter' => [
                        'description' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'pasien' => [
                "model" => Pasien::class,
                'foreign_key' => 'pasien_id',
                'columns' => [
                    'norm' => [
                        'description' => true,
                        'orderable' => true,
                    ],
                ],
            ],
            'operasi' => [
                "model" => Operasi::class,
                'foreign_key' => 'operasi_id',
                'columns' => [
                    'iddokter' => [
                        'description' => true,
                        'orderable' => true,
                    ],
                ],
            ],
        ]
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelasrawatinap::class, 'kelas_id', 'id');
    }

    public function bangsal()
    {
        return $this->belongsTo(Bangsal::class, 'bangsal_id', 'id');
    }

    public function kamarranap()
    {
        return $this->belongsTo(Kamarrawatinap::class, 'kamar_id', 'id');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id', 'id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'id');
    }

    public function operasi()
    {
        return $this->belongsTo(Operasi::class, 'operasi_id', 'id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamarrawatinap::class, 'kamar_id', 'id');
    }
}
