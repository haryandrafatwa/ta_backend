<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class plotting extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nip_pembimbing_1' => $this->nip_pembimbing_1,
            'nip_pembimbing_2' => $this->nip_pembimbing_2,
        ];
    }
}
