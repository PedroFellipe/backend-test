<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Event extends BaseModel
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'events';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'date',
        'time',
        'place',
        'canceled'
    ];

    protected $searchable = [
        'date' => 'like',
        'place' => 'like'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
