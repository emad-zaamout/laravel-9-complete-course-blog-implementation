<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "url" => $this->url,
            "is_trending" => $this->is_trending,
            "author" => $this->author,
            "author_image_url" => $this->author_image_url,
            "image_url_portrait" => $this->image_url_portrait,
            "image_url_landscape" => $this->image_url_landscape,
            "title" => $this->title,
            "date" => $this->date,
            "description" => $this->description,
            "content" => $this->content,
            "tags" => implode(
                ", ",
                array_map(function($row) {
                    return $row["tag"];
                }, json_decode(json_encode($this->tags), true))
            )
        ];
    }
}
