<?php declare(strict_types=1);


namespace Database\Migration;


use Swoft\Db\Schema\Blueprint;
use Swoft\Devtool\Annotation\Mapping\Migration;
use Swoft\Devtool\Migration\Migration as BaseMigration;

/**
 * Class KtpTaskWork
 *
 * @since 2.0
 *
 * @Migration(time=20200527111323)
 */
class KtpTaskWork extends BaseMigration
{
    const TABLE = 'task_work';

    /**
     * @return void
     */
    public function up(): void
    {
        $this->schema->createIfNotExists(self::TABLE,function (Blueprint $table){
            $table->engine = 'InnoDB';

            $table->string('task_id', 50)->comment('任务id');
            $table->char('names', 15)->comment('任务名称');
            $table->char('describe',50)->nullable()->comment('任务描述');
            $table->integer('execution')->comment('执行时间');
            $table->integer('retry')->default(1)->comment("任务重试次数 默认1 重试一次");
            $table->integer('overtime')->default(0)->comment("超时时间 默认0 单位秒 0不超时");
            $table->string('bodys')->comment('执行体');
            $table->integer('status')->default(0)->comment('虚拟状态 由模型去控制 默认 0 ');

            $table->integer('uid')->comment("用户id");

            $table->integer('created_at')->comment("创建时间");
            $table->integer('updated_at')->comment("更新时间");

            $table->primary('task_id');
            $table->comment('系统任务表');
            $table->charset = 'utf8mb4';

        });

    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->schema->drop(self::TABLE);
    }
}
