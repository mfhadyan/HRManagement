<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RemoveAnnouncementsMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove access entries for announcements menu
        DB::table('accesses')->where('menu_id', 6)->delete();
        
        // Remove the announcements menu entry
        DB::table('menus')->where('id', 6)->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restore the announcements menu entry
        DB::table('menus')->insert([
            'id' => 6,
            'name' => 'announcements',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
