<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;

class AddressModel extends Model
{
    protected $table = 'address_data';

    protected $primaryKey = 'id';

    protected $fillable = [    
        'cep',
        'state', 
        'city', 
        'neighborhood', 
        'street',         
        'service', 
        'longitude', 
        'latitude', 
        'was_edited'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}