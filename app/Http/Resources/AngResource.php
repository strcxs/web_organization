<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AngResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public $status, $message;
    public function __construct($status, $message, $resource,$session = null)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
        $this->session  = $session;
    }
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "success"=> $this->status,
            "message"=> $this->message,
            "session"=> $this->session,
            "data"=> $this->resource
        ];
    }
}
