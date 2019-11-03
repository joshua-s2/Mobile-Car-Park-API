<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPasswordColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` ADD `password` VARCHAR(190) NOT NULL AFTER `email`; ");
        DB::statement("ALTER TABLE `users` CHANGE `phone` `phone` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL; ");
        DB::statement("ALTER TABLE `users` CHANGE `role` `role` ENUM('admin','user','partner') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user'; ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users` DROP `password`");
        DB::statement("ALTER TABLE `users` CHANGE `role` `role` ENUM('admin','user','partner') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL; ");
    }
}
