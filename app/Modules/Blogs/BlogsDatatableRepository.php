<?php

declare(strict_types=1);

namespace App\Modules\Blogs;

use App\Models\Blogs;
use App\Modules\Datatable\DatatableRepository;

class BlogsDatatableRepository extends DatatableRepository
{
    protected string $table = "blogs";
    protected array $orderColumns = [
        "title",
        "url",
    ];
    protected array $searchColumns = [
        "title",
        "url",
    ];
    protected array $selectColumns = [
        "blogs.id",
        "blogs.title",
        "blogs.url"
    ];
    protected array $exceptionColumns = [
        "id" => "blogs.id",
        "title" => "blogs.title",
        "url" => "blogs.url",
    ];
    protected string $joinQuery = "";
    protected string $where = "blogs.id > 0";

    public function getTotalRecords() : int {
        return Blogs::count();
    }
}
