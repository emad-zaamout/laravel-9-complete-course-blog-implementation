<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("users")
            ->insert([
                "email" => "support@ahtcloud.com",
                "name" => "Emad Zaamout",
                "password" => bcrypt("password")
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table("users")->where("email", "=", "support@ahtcloud.com")->delete();
    }
};
