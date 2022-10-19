<?php

declare(strict_types=1);

namespace App\Modules\Blogs;

use App\Http\Requests\BlogsUpdateRequest;
use App\Models\Blogs;
use App\Models\BlogTags;

class BlogsRepository
{
    const RECENT_BLOGS_LIMIT = 5;

    public function getTotalCount(): int
    {
        return Blogs::all()->count();
    }

    public function UIList(int $page, int $pageLength, array $filters = []): array
    {
        if ($filters !== []) {
            return Blogs::with(["tags"])
                ->where($filters)
                ->where("id", ">", 0)
                ->limit($pageLength)
                ->offset(($page - 1) * $pageLength)
                ->get()
                ->toArray();
        }

        return Blogs::with(["tags"])
            ->where("id", ">", 0)
            ->limit($pageLength)
            ->offset(($page - 1) * $pageLength)
            ->get()
            ->toArray();
    }

    public function UIListRecent(): array
    {
        return Blogs::with(["tags"])
            ->where("id", ">", 0)
            ->limit(self::RECENT_BLOGS_LIMIT)
            ->orderBy("created_at", "desc")
            ->get()
            ->toArray();
    }

    public function get(int $id): Blogs
    {
        return Blogs::with("tags")->findOrFail($id);
    }

    public function update(BlogsUpdateRequest $request): Blogs
    {
        $data = $request->data();

        $newBlog = ($data["id"] === null)
            ? new Blogs()
            : $this->get($data["id"]);

        $newBlog->url = $data["url"];
        $newBlog->is_trending = $data["is_trending"];
        $newBlog->author = $data["author"];
        $newBlog->author_image_url = $data["author_image_url"];
        $newBlog->title = $data["title"];
        $newBlog->date = $data["date"];
        $newBlog->description = $data["description"];
        $newBlog->content = $data["content"];
        $newBlog->image_url_landscape = $data["image_url_landscape"];
        $newBlog->image_url_portrait= $data["image_url_portrait"];

        $newBlog->save();

        BlogTags::where("blogs_id", $newBlog->id)->delete();
        $toInsertTags = [];
        for ($i = 0; $i < count($data["tags"]); $i++) {
            $toInsertTags [] = [
                "tag" => $data["tags"][$i],
                "blogs_id" => $newBlog->id
            ];
        }
        BlogTags::insert($toInsertTags);

        return $this->get($newBlog->id);
    }

    public function delete(int $id): void
    {
        BlogTags::where("blogs_id", $id)->delete();
        Blogs::findOrFail($id)->delete();
    }

    public function getByUrl(string $url) : Blogs
    {
        return Blogs::where("url", $url)->first();
    }
}
