<?php

namespace App\Http\Resources\Career;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalityTraitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'career_name' => $this->career_name,
            'description' => $this->description,
            'trait_type' => $this->trait_type,
        ];
    }
} 