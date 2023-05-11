<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IzinKeluarResource extends JsonResource
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
            'alasan' => $this->alasan,
            'userNik' => $this->user_nik,
            'date' => $this->tanggal,
            'status' => $this->is_approved,
            'jamKeluar' => $this->jam_keluar,
            'jamKembali' => $this->jam_kembali
        ];
    }
}
