<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'priority' => $this->priority,
            'status' => $this->status,
            'contact_email' => $this->contact_email,
            'cc_emails' => $this->cc_emails,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'organization' => [
                'id' => $this->organization_id,
                'name' => $this->organization->name ?? null,
            ],
            'vessel' => [
                'id' => $this->vessel_id,
                'name' => $this->vessel->name ?? null,
            ],
            'service_line' => [
                'id' => $this->service_line_id,
                'name' => $this->serviceLine->name ?? null,
            ],
            'category' => [
                'id' => $this->category_id,
                'name' => $this->category->name ?? null,
            ],
            'assigned_to' => $this->assigned_to_user_id ? [
                'id' => $this->assigned_to_user_id,
                'name' => $this->assignedToUser->name ?? null,
            ] : null,
        ];
    }
}
