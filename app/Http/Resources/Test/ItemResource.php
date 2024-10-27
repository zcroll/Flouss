<?php

namespace App\Http\Resources\Test;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'help_text' => $this->help_text,
            'is_completed' => (bool) $this->is_completed,
            'career_id' => $this->career_id,
            'degree_id' => $this->degree_id,
            'image_url' => $this->image_url,
            'image_colour' => $this->image_colour,
            'option_set' => new OptionSetResource($this->whenLoaded('optionSet')),
        ];
    }
}
