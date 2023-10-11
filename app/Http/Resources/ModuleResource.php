<?php

namespace App\Http\Resources;

use App\Http\Resources\ProgramResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
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
            'program_id' => $this->program_id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'homework_content' => $this->homework_content,
            'program' => new ProgramResource($this->whenLoaded('program')),
            'topics' => TopicResource::collection($this->whenLoaded('topics')),
            'created_at' => $this->whenNotNull($this->created_at ?? null),
            'updated_at' => $this->whenNotNull($this->updated_at ?? null),
            'deleted_at' => $this->whenNotNull($this->deleted_at ?? null),
        ];
    }
}
