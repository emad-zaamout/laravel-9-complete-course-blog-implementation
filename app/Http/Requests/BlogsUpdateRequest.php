<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogsUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id" => "nullable|numeric",
            "is_trending" => "required|boolean",
            "author" => "required|string",
            "author_image_url" => "required|string",
            "image_url_portrait" => "required|string",
            "image_url_landscape" => "required|string",
            "date" => "required|string",
            "title" => "required|string",
            "tags" => "required|string",
            "description" => "required|string",
            "content" => "required|string",
        ];
    }

    public function data(): array
    {
        $id = $this->input("id", null);

        return [
            "id" => ($id === null) ? null : (int)$id,
            "is_trending" => $this->input("is_trending", 0),
            "author" => $this->input("author"),
            "author_image_url" => $this->input("author_image_url"),
            "image_url_portrait" => $this->input("image_url_portrait"),
            "image_url_landscape" => $this->input("image_url_landscape"),
            "date" => $this->input("date"),
            "url" => $this->generateUrl($this->input("title")),
            "title" => $this->input("title"),
            "tags" => array_map(function($row) {
                return trim($row);
            }, explode(",", $this->input("tags", []))),
            "description" => $this->input("description"),
            "content" => $this->input("content"),
        ];
    }

    private function generateUrl(string $title): string {
        $newUrl = trim(strtolower($title));
        $newUrl = str_replace("  ", " ", $newUrl);
        $newUrl = str_replace(" ", "-", $newUrl);
        return preg_replace("/[^A-Za-z0-9\-]/", "", $newUrl);
    }
}
