<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model {

    use HasFactory;

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mccAccount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MccAccount::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function adGroups(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(AdGroup::class, Campaign::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function platforms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Platform::class, $this->getConnection()->getDatabaseName() . '.client_platform');
    }

    /**
     * @return mixed
     */
    public function getDevTokenAttribute()
    {
        return $this->mccAccount()->first()->dev_token;
    }

    /**
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function getClientIdAttribute()
    {
        return $this->mccAccount()->first()->client_id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function getClientEmailAttribute()
    {
        return $this->mccAccount()->first()->email;
    }

    /**
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function getClientSecretAttribute()
    {
        return $this->mccAccount()->first()->client_secret;
    }

    /**
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function getRefreshTokenAttribute()
    {
        return $this->mccAccount()->first()->refresh_token;
    }
}
