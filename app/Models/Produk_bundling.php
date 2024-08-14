<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk_bundling extends Model
{
    protected $table = 'produk_bundlings';

    public function masterBundling()
    {
        return $this->hasMany(Master_produk_bundling::class, 'id_produk_bundling');
    }
}
