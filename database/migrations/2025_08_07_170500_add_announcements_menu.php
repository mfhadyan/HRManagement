<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddAnnouncementsMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add the announcements menu entry
        DB::table('menus')->insert([
            'id' => 13,
            'name' => 'announcements',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add access entries for announcements menu
        // Administrator (role_id = 1) gets full access (status = 2)
        DB::table('accesses')->insert([
            'menu_id' => 13,
            'role_id' => 1,
            'status' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User (role_id = 2) gets read-only access (status = 1)
        DB::table('accesses')->insert([
            'menu_id' => 13,
            'role_id' => 2,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove access entries for announcements menu
        DB::table('accesses')->where('menu_id', 13)->delete();
        
        // Remove the announcements menu entry
        DB::table('menus')->where('id', 13)->delete();
    }
}
