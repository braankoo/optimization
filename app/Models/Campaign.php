<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Campaign extends Model {

    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adGroups(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdGroup::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function webmasters(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Webmaster::class, 'campaign_webmasters');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function site(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Site::class);
    }
}
