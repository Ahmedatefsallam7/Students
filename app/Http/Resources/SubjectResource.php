<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class SubjectResource extends JsonResource
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
            'Subject Name' => $this->sub_name,
            'Subject Code' => $this->sub_code,
            'Attned Code' => $this->attend_code,
            // 'Creator Name' => $this->creator->name,
            // 'Creator Email' => $this->creator->email,
        ];
    }
}