<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Friendship extends BaseModel
{

    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'friendship';

    protected $fillable = [
        'first_user_id',
        'second_user_id',
        'email',
        'confirmed'
    ];


    public function first_user()
    {
        return $this->belongsTo(User::class, 'first_user_id');
    }

    public function second_user()
    {
        return $this->belongsTo(User::class, 'second_user_id');
    }

}
