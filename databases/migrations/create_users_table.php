<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 17-6-26
 * Time: 下午2:04
 */

use Illuminate\Database\Schema\Blueprint;
use Notadd\Foundation\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->comment('用户名');
            $table->string('email')->nullable()->comment('邮箱');
            $table->string('password')->nullable()->comment('密码');
            $table->string('remember_token', 100);
            $table->timestamps();

        });

    }

    public function down() {
        $this->schema->drop('users');
    }

}