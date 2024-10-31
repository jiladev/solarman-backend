<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'client_id',
        'consume_kv_copel',
        'public_light_value',
        'fatura_copel',
        'light_fase_copel',
        'percentage_value',
        'consume_kv_copel_final',
        'consume_kv_coop',
        'consume_kv_coop_final',
        'public_light',
        'ult_fatura_copel',
        'min_tax',
        'fasic_value',
        'taxa_tusd',
        'discount',
        'final_value_coop',
        'discount_monthly',
        'discount_percentage'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }
}
