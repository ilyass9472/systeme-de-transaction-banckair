<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','balance'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function sendMoney($sold,$montantEnvoyer){

        if()
            {

        }elseif(0 < $montantEnvoyer || $montantEnvoyer <= $sold)
            {

            return response()->json(
                [
                    'message'=>'sold isefusant'
                ],400
                );

        }

    }
}
