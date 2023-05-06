<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsenResource extends JsonResource
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
            'nik' => $this->nik,
            'date' => $this->tanggal,
            'endDate' => $this->tanggal_selesai,
            'alasan' => $this->alasan,
            'potong_cuti' => $this->potong_cuti
        ];
    }
}
