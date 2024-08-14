<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_produk_bundling extends Model
{
    protected $table = 'master_produk_bundlings';

    public function product()
    {
        return $this->belongsTo(Master_product::class, 'id_produk_master');
    }

    public function produkBundlings()
    {
        return $this->hasMany(Produk_bundling::class, 'id_master_produk_bundling');
    }
}
