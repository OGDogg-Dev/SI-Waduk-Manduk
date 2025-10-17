<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource JSON untuk Inquiry.
 */
class InquiryResource extends JsonResource
{
    /**
     * Konversi ke array JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => $this->type,
            'message' => $this->message,
            'status' => $this->status,
            'handled_by' => $this->handled_by,
            'handler' => $this->whenLoaded('handler', function () {
                return [
                    'id' => $this->handler->id,
                    'name' => $this->handler->name,
                    'email' => $this->handler->email,
                ];
            }),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
