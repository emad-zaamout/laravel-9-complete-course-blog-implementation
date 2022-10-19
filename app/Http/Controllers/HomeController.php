<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Blogs\BlogsRepository;
use App\Modules\Home\HomeService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private readonly HomeService $homeService,
        private readonly BlogsRepository $blogsRepository

    )
    {

    }

    public function home(Request $request) : View
    {
        return view('home', $this->homeService->home($request));
    }

    public function getByUrl(string $url): View
    {
        try {
            $blog = $this->blogsRepository->getByUrl($url);

            return view(
                "blog",
                [
                    "blog" => $blog,
                    "title" => $blog->title
                ]
            );
        } catch (Exception $error) {
            abort(404);
        }
    }
}
