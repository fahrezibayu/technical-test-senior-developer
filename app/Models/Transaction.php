<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'amount', 'status'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
