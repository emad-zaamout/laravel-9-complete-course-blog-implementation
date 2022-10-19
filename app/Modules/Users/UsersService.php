<?php

declare(strict_types=1);

namespace App\Modules\Users;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Modules\Datatable\Buttons;

class UsersService
{
    public function __construct(
        private readonly UsersDatatableRepository $datatableRepository,
        private readonly UsersRepository $repository
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

    public function get(int $id): UserResource
    {
        $user = $this->repository->get($id);

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request): UserResource
    {
        $user = $this->repository->update($request);

        return new UserResource($user);
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
    }
}
