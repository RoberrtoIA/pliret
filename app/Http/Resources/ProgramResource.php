<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'modules' => ModuleResource::collection($this->whenLoaded('modules')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'developers' => UserResource::collection($this->whenLoaded('developers')),
            'created_at' => $this->whenNotNull($this->created_at ?? null),
            'updated_at' => $this->whenNotNull($this->updated_at ?? null),
            'deleted_at' => $this->whenNotNull($this->deleted_at ?? null),
            'developer' => $this->whenNotNull($this->developer),
        ];
    }
}
