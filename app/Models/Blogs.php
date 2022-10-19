<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    protected $table = "blogs";
    protected $fillable = [
        "id",
        "url",
        "is_trending",
        "author",
        "author_image_url",
        "image_url_portrait",
        "image_url_landscape",
        "title",
        "date",
        "description",
        "content",
        "created_at",
        "updated_at"
    ];

    public function tags() {
        return $this->hasMany(BlogTags::class);
    }
}
