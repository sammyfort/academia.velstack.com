<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class ParentService
{
    public static function create(array $data)
    {
        return school()->parents()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'identity_number' => $data['identity_number'],
            'password' => Hash::make($data['phone']),
            'occupation' => $data['occupation'],
        ]);
    }

}
