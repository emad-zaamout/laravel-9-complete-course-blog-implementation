<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;


Route::middleware(["web"])->group(function () {
    Route::post("/login", [AuthController::class, "login"]);
    Route::get("/logout", [AuthController::class, "logout"])->middleware(["auth"]);
});

Route::middleware(["auth:sanctum"])->group(function () {
    Route::get("/dashboard/users/index", [UsersController::class, "index"]);
    Route::get("/dashboard/users/{id}", [UsersController::class, "get"])->where("id", "[0-9]+");
    Route::delete("/dashboard/users/{id}", [UsersController::class, "delete"])->where("id", "[0-9]+");
    Route::post("/dashboard/users", [UsersController::class, "update"]);

    Route::get("/dashboard/blogs/index", [BlogsController::class, "index"]);
    Route::get("/dashboard/blogs/{id}", [BlogsController::class, "get"])->where("id", "[0-9]+");
    Route::delete("/dashboard/blogs/{id}", [BlogsController::class, "delete"])->where("id", "[0-9]+");
    Route::post("/dashboard/blogs", [BlogsController::class, "update"]);
});
