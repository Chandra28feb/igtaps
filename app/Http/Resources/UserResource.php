<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        return [
                [
                    'id'      => $this->id,
                    'name' => $this->name,
                    'email' => $this->email,
                    'mobile' => $this->mobile,
                ]
            
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
    
}
