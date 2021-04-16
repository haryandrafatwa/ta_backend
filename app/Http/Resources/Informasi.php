<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Informasi extends JsonResource
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
            'informasi_judul' => $this->informasi_judul,
            'informasi_isi' => $this->informasi_isi,
            'penerbit' => $this->penerbit,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
