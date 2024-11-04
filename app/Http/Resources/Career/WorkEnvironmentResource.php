<?php

namespace App\Http\Resources\Career;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkEnvironmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();
        $nameColumn = $locale === 'fr' ? 'name_fr' : 'name';
        $descriptionColumn = $locale === 'fr' ? 'description_fr' : 'description';
        
        return [
            'name' => $this->$nameColumn,
            'category' => $this->category,
            'score' => $this->score,
            'description' => $this->$descriptionColumn,
        ];
    }
} 