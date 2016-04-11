<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('language')->nullable();
            $table->text('subjects')->nullable();
            $table->text('programs')->nullable();
            $table->text('academic_level')->nullable();
            $table->text('resume')->nullable();
            $table->text('sample_paper')->nullable();
            $table->integer('user_id')->unique()->nullable();
            $table->string('status')->nullable();
            $table->string('account_type')->nullable();
            $table->string('ni_admin')->nullable();
            $table->string('picha_ya_id')->nullable();
            $table->string('prof_pic')->default('https://pbs.twimg.com/profile_images/708415697798500353/eseXMvaB_400x400.png')->nullable();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
