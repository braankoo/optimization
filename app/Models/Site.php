<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model {

    use HasFactory;


    /**
     * @var string[]
     */
    protected $fillable = [ 'url' ];


    public $timestamps = false;
}
