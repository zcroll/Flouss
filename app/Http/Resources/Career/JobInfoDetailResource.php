<?php

namespace App\Http\Resources\Career;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobInfoDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();
        
        return [
            'role_description_main' => $locale === 'fr' ? $this->role_description_main_fr : $this->role_description_main,
            'role_description_secondary' => $locale === 'fr' ? $this->role_description_secondary_fr : $this->role_description_secondary,
        ];
    }
} 