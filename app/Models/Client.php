<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Psy\CodeCleaner\FunctionContextPass;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
    ];

    public static function boot(){
        parent::boot();

        static::deleting(function($client){
            $client->reports()->delete();
            $client->estimates()->delete();
        });
    }

    public function reports(){
        return $this->hasMany(Report::class);
    }

    public function estimates(){
        return $this->hasMany(ClientEstimate::class);
    }
}
