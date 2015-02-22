<?php namespace Pmietlicki\Todo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTasksTable extends Migration
{

    public function up()
    {
        Schema::create('pmietlicki_todo_tasks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('title')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pmietlicki_todo_tasks');
    }

}
