<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return collect(parent::toArray($request))->merge([
            'user' => new UserResource($this->whenLoaded('user')),
            'module' => new ModuleResource($this->whenLoaded('module')),
        ])->all();
    }
}
