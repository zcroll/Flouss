<?php

namespace App\Http\Resources\Test;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemSetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'ItemSetStep',
            'title' => $this->title,
            'lead_in_text' => $this->lead_in_text,
            'items' => ItemResource::collection($this->whenLoaded('items')),
            'option_sets' => OptionSetResource::collection($this->whenLoaded('optionSets')),
        ];
    }
}
