<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DatatablesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "columns" => "array|required",
            "start" => "required|numeric|min:0",
            "length" => "required|numeric|min:0",
            "order" => "required|array",
            "order.*.dir" => [
                "required",
                Rule::in(["asc", "desc"])
            ],
            "search" => "required",
            "search.value" => "nullable|string"
        ];
    }

    public function data(): array
    {
        return [
            "columns" => $this->formatRequest($this->input("columns")),
            "start" => (int)$this->input("start", 0),
            "length" => (int)$this->input("length", 0),
            "order" => $this->formatRequest($this->input("order")),
            "search" => $this->formatRequest($this->input("search")),
        ];
    }

    private function formatRequest(array $object): array
    {
        $result = [];
        foreach ($object as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->formatRequest($value);
            } else {
                if (in_array($value, ["true", "false"])) {
                    $result[$key] = (bool)$value;
                } else if (is_string($value) && is_numeric($value)) {
                    $result[$key] = (int)$value;
                } else {
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }
}
