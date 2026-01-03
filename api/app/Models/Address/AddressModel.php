<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Model;

class AddressModel extends Model
{
    protected $table = 'address_data';

    //protected $primaryKey = 'address_id';

    protected $fillable = [    
        'address_id',
        'user_id',
        'user',         
        'cep',
        'state', 
        'city', 
        'neighborhood', 
        'street',         
        'service', 
        'longitude', 
        'latitude', 
        'was_edited', 
        'is_main_address'
    ];
}