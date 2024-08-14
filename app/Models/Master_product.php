<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_product extends Model
{
    protected $table = 'master_products';

    public function bundlings()
    {
        return $this->hasMany(Master_produk_bundling::class, 'id_produk_master');
    }

    public function kategoris()
    {
        return $this->hasMany(Produk_kategori::class, 'id_produk');
    }
}