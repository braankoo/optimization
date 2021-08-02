<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class MccAccount extends Model {

    public $timestamps = false;

    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function client(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Client::class);
    }
}
