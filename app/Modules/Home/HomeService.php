<?php

declare(strict_types=1);

namespace App\Modules\Home;

use App\Modules\Blogs\BlogsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HomeService
{
    const PAGE_LENGTH = 10;

    public function __construct(
        private readonly BlogsService $service
    )
    {

    }

    public function home(Request $request) : array
    {
        $totalCount = $this->service->getTotalCount();
        $page = $this->getPageNumber($request, $totalCount);

        return [
            "title" => "My Blog",
            "page_length" => self::PAGE_LENGTH,
            "total_blogs" => $totalCount,
            "page_number" => $page,
            "blogs" =>  $this->service->UIList($page, self::PAGE_LENGTH),
            "trending" => $this->service->UIList(
                $page,
                self::PAGE_LENGTH,
                ["is_trending" => 1]
            ),
            "recentlyWritten" => $this->service->UIListRecent(),
            "tags" => [
                [
                    "url" => "/",
                    "name" => "Laravel"
                ],
                [
                    "url" => "/",
                    "name" => "Amazon"
                ],
                [
                    "url" => "/",
                    "name" => "Laravel"
                ],
            ]
        ];
    }

    private function getPageNumber(Request $request, int $totalCount): int
    {
        $maxNumberOfPages = ceil($totalCount / self::PAGE_LENGTH);

        $page = $request->query("page", 1);

        try {
            $request->validate(
                [ "page" => "numeric|min:1|max:$maxNumberOfPages" ],
                [ "page" => $page ]
            );
        } catch (ValidationException $error) {
            Log::error($error->getMessage());
            abort(404);
        }

        return (int)$page;
    }
}
