<?php

namespace App\Http\Resources\Career;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkEnvironmentResource extends JsonResource
{
    public function toArray($request)
    {
        $locale = app()->getLocale();
        
        return [
            'id' => $this->id,
            'name' => $locale === 'fr' ? $this->name_fr : $this->name,
            'description' => $locale === 'fr' ? $this->description_fr : $this->description,
            'category' => $this->category,
            'score' => (int)$this->score,
            'skills' => [],
        ];
    }
} 