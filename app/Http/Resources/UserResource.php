<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/** @mixin User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name'=> $this->name,
            'email' => $this->email,
            'infos' => $this->userInfo,
            'id' => $this->id
        ];
    }
}
