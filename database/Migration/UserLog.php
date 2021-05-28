<?php declare(strict_types=1);


namespace Database\Migration;


use Swoft\Db\Schema\Blueprint;
use Swoft\Db\Exception\DbException;
use Swoft\Devtool\Annotation\Mapping\Migration;
use Swoft\Devtool\Migration\Migration as BaseMigration;

/**
 * Class UserLog
 *
 * @since 2.0
 *
 * @Migration(time=20210426163904)
 */
class UserLog extends BaseMigration
{
    const TABLE = 'user_log';

    /**
     * @return void
     * @throws DbException
     */
    public function up(): void
    {
        $this->schema->createIfNotExists(self::TABLE, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->comment('主键');
            $table->integer('uid')->index('idx_uid')
                ->comment('用户ID');
            $table->integer('action')->comment('用户操作行为');
            $table->text('log')->comment('用户日志');
            $table->integer('create_at')->index('idx_create_time')
                ->comment("创建时间");
            $table->ipAddress('visitor')->comment("操作ip地址");

            $table->comment('用户操作日志表');
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
