<?php

namespace App\Http\Resources\Career;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobInfoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();
        
        return [
            'id' => $this->id,
            'name' => $locale === 'fr' ? $this->name_fr : $this->name,
            'slug' => $this->slug,
            'image' => $this->image,
            'is_favorited' => $this->isFavorite(),
        ];
    }
} 