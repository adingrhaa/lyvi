<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Models\Master_product;

class Produk_bundling extends Model
{
    use HasFactory;

    protected $fillable = [
            'nama_bundle',
            'harga_bundle',
            'detail_bundle',
            'foto_bundle',
            'pilih_produk',
            'redirect'
    ];
    protected $table = 'produk_bundlings';

    protected $casts = [
        'pilih_produk' => 'array',
        'redirect' => 'array'
    ];

    public function masterBundling()
    {
        return $this->hasMany(Master_produk_bundling::class, 'id_produk_bundling');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $produkIds = $model->pilih_produk;
            $validProdukIds = Master_product::pluck('id')->toArray();
            $invalidProdukIds = array_diff($produkIds, $validProdukIds);

            if (!empty($invalidProdukIds)) {
                throw new \Exception('Beberapa produk ID tidak valid: ' . implode(', ', $invalidProdukIds));
            }
        });
    }
}
