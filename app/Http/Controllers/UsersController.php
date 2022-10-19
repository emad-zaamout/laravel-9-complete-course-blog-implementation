<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Users\UsersService;
use App\Http\Requests\DatatablesRequest;
use App\Http\Requests\UserUpdateRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UsersController extends Controller
{

    public function __construct(
        private readonly UsersService $service
    )
    {

    }

    public function index(DatatablesRequest $request): JsonResponse
    {
        try {
            return response()->json($this->service->index($request->data()));
        } catch (Exception $error) {
            return response()->json(
                [
                    "exception" => get_class($error),
                    "errors" => $error->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function get(int $id): JsonResponse
    {
        try {
            return response()->json($this->service->get($id));
        } catch (Exception $error) {
            return response()->json(
                [
                    "exception" => get_class($error),
                    "errors" => $error->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function update(UserUpdateRequest $request): JsonResponse
    {
        try {
            return response()->json($this->service->update($request));
        } catch (Exception $error) {
            return response()->json(
                [
                    "exception" => get_class($error),
                    "errors" => $error->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $error) {
            return response()->json(
                [
                    "exception" => get_class($error),
                    "errors" => $error->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

}
