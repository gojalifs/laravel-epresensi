<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevisiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'user_nik' => $this->user_nik,
            'date' => $this->tanggal,
            'time' => $this->jam,
            'reason' => $this->alasan,
            'revised' => $this->yang_direvisi,
            'isApproved' => $this->is_approved,
            'approval' => $this->approval,
        ];
    }
}