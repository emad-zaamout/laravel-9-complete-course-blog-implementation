<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up()
    {
        $blogId = DB::table("blogs")->insertGetId([
            "url" => "laravel-websockets-chat-application-example",
            "is_trending" => 1,
            "author" => "Emad Zaamout",
            "author_image_url" => "https://avatars.githubusercontent.com/u/50178852?v=4",
            "image_url_portrait" => "https://picsum.photos/300/350",
            "image_url_landscape" => "https://picsum.photos/350/300",
            "title" => "Laravel WebSockets | Chat Application Example",
            "date" => "October 16 2022",
            "description" => "In this course, we will cover custom Laravel WebSocket Chat Application implementation using the Laravel framework. We will not be using any third-party vendor for the WebSockets server. We will implement it from scratch, and we will use our server to handle all the WebSockets communications. Before we get started, dont forget to subscribe to my channel to stay up to date with my latest training videos.",
            "content" => "In this course, we will cover custom Laravel WebSocket Chat Application implementation using the Laravel framework. We will not be using any third-party vendor for the WebSockets server. We will implement it from scratch, and we will use our server to handle all the WebSockets communications. Before we get started, dont forget to subscribe to my channel to stay up to date with my latest training videos."
        ]);

        DB::table("blog_tags")->insert([
            [
                "tag" => "Laravel",
                "blogs_id" => $blogId
            ],
            [
                "tag" => "WebSockets",
                "blogs_id" => $blogId
            ],
            [
                "tag" => "PHP",
                "blogs_id" => $blogId
            ],
        ]);
    }

    public function down()
    {

    }
};
