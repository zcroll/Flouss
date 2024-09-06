<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
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
            'scores' => $this->scores,
            'jobs' => $this->jobs,
            'highest_two_scores' => $this->highestTwoScores,
            'archetype' => $this->Archetype,
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
