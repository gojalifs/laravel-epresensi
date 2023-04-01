<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresensiDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_presensi' => $this->id_presensi,
            'type' => $this->jenis,
            'time' => $this->jam,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'imgPath' => $this->img_path,
        ];
    }
}
