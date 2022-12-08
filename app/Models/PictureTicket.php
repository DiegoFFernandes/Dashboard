<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PictureTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'cd_tickets',
        'path'
    ];

    public function storeData($cd_ticket, $image)
    {
        $file = new PictureTicket();
        return $file::create([
            'cd_tickets' => $cd_ticket,
            'path' => $image['file']->store('image_tickets')
        ]);
    }
}
