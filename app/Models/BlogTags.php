<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogTags extends Model
{
    protected $table = "blog_tags";
    protected $fillable = [
        "id",
        "blogs_id",
        "tag",
        "created_at",
        "updated_at"
    ];
}
