<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->whenLoaded('role', fn () => $this->role->name),
            'phone' => $this->phone,
            'address' => $this->address,
            'profile_photo_url' => $this->profile_photo_path
                ? url('storage/' . ltrim($this->profile_photo_path, '/'))
                : null,
        ];
    }
}
