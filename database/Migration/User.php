<?php declare(strict_types=1);


namespace Database\Migration;


use Swoft\Db\Exception\DbException;
use Swoft\Db\Schema\Blueprint;
use Swoft\Devtool\Annotation\Mapping\Migration;
use Swoft\Devtool\Migration\Migration as BaseMigration;

/**
 * Class User
 *
 * @since 2.0
 *
 * @Migration(time=20200526143913)
 */
class User extends BaseMigration
{
    const TABLE = 'user';

    /**
     * @return void
     * @throws DbException
     */
    public function up(): void
    {
        $this->schema->createIfNotExists(self::TABLE,function (Blueprint $table){
            $table->engine = 'InnoDB';

            $table->increments('id')->comment('主键');
            $table->string('account', 50)->comment('用户登录帐号');
            $table->char('username', 30)->comment('用户昵称');
            $table->char('password', 60)->comment('用户密码');
            $table->ipAddress('visitor')->comment("用户上次登录ip地址");
            $table->integer('create_at')->comment("创建时间");
            $table->integer('update_at')->comment("更新时间");
            $table->integer('delete_at')->nullable()->comment('删除时间 为NULL未删除');

            $table->index('account');
            $table->comment('系统用户表');
            $table->charset = 'utf8mb4';

        });

    }

    /**
     * @return void
     * @throws DbException
     */
    public function down(): void
    {
        $this->schema->drop(self::TABLE);
    }
}
