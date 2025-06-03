<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    protected $token = null;

    public function __construct($resource, ?string $token = null)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Login successful.',
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'is_admin' => $this->is_admin,
            ],
            'token' => $this->token,
        ];
    }
}
