<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Attachment extends BaseModel
{
    protected $connection = 'mysql';

    protected $table = 'attachments';

    protected $fillable = [
        'name',
        'mime',
        'extension',
        'location'
    ];

    public static function fileUri($anexoId)
    {
        return route('file.show', ['id' => $anexoId]);
    }
}
