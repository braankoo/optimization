<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webmaster extends Model {

    use HasFactory;

    public $timestamps = false;

    protected $fillable = [ 'name', 'device' ];

    public function apiKeys(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

}
