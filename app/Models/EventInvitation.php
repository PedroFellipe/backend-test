<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;

class EventInvitation extends BaseModel
{

    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'events_invitations';

    protected $fillable = [
        'user_id',
        'event_id',
        'status'
    ];

    protected $searchable = [
        'user_id' => 'like'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
