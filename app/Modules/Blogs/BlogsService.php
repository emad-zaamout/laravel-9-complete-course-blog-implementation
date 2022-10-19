<?php

declare(strict_types=1);

namespace App\Modules\Blogs;

use App\Http\Requests\BlogsUpdateRequest;
use App\Http\Resources\BlogsResource;
use App\Modules\Datatable\Buttons;

class BlogsService
{
    public function __construct(
        private readonly BlogsRepository $repository,
        private readonly BlogsDatatableRepository $datatableRepository
    )
    {

    }

    public function index(array $data): array
    {
        $result = $this->datatableRepository->index(
            $data["columns"],
            $data["start"],
            $data["length"],
            $data["order"],
            $data["search"],
        );

        $result["data"] = array_map(function ($row) {
            $eventId = $row["id"];
            $row["actions"] = Buttons::actionButton("editItem($eventId)", "Edit", "btn-dark");
            $row["actions"] .= Buttons::actionButton("deleteItem($eventId)", "Delete", "btn-danger");
            return $row;
        }, $result["data"]);

        return $result;
    }

    public function getTotalCount(): int
    {
        return $this->repository->getTotalCount();
    }

    public function UIList(int $page, int $pageLength, array $filters = []): array
    {
        return $this->repository->UIList($page, $pageLength, $filters);
    }

    public function UIListRecent(): array
    {
        return $this->repository->UIListRecent();
    }

    public function get(int $id): BlogsResource
    {
        $user = $this->repository->get($id);

        return new BlogsResource($user);
    }

    public function update(BlogsUpdateRequest $request): BlogsResource
    {
        $user = $this->repository->update($request);

        return new BlogsResource($user);
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }
}
