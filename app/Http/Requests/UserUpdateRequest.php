<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->input("id", "");

        return [
            "id" => "nullable|numeric",
            "name" => "required|string|min:3",
            "email" => "required|email|unique:users,email,$id",
            "password" => "nullable|confirmed|min:6|required_without:id"
        ];
    }

    public function data(): array
    {
        $id = $this->input("id", null);
        return [
            "id" => ($id === null) ? null : (int)$id,
            "name" => $this->input("name"),
            "email" => $this->input("email"),
            "password" => $this->input("password", null),
        ];
    }
}
