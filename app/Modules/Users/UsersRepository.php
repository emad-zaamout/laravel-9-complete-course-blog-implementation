<?php

declare(strict_types=1);

namespace App\Modules\Users;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

class UsersRepository
{
    public function get(int $id): User
    {
        return User::findOrFail($id);
    }

    public function update(UserUpdateRequest $request): User
    {
        $data = $request->data();

        $newUser = ($data["id"] === null)
            ? new User()
            : $this->get($data["id"]);

        $newUser->name = $data["name"];
        $newUser->email = $data["email"];

        if ($data["password"] !== null) {
            $newUser->password = bcrypt($data["password"]);
        }

        $newUser->save();

        return $newUser;
    }

    public function delete(int $id): void
    {
        User::findOrFail($id)->delete();
    }
}
