<?php declare(strict_types=1);


namespace Database\Migration;


use Swoft\Db\Schema\Blueprint;
use Swoft\Devtool\Annotation\Mapping\Migration;
use Swoft\Devtool\Migration\Migration as BaseMigration;

/**
 * Class KtpTaskWorkLog
 *
 * @since 2.0
 *
 * @Migration(time=20200527135150)
 */
class KtpTaskWorkLog extends BaseMigration
{
    const TABLE = 'task_work_log';
    /**
     * @return void
     */
    public function up(): void
    {
        $this->schema->createIfNotExists(self::TABLE,function (Blueprint $table){
            $table->engine = 'InnoDB';

            $table->increments('id')->comment("任务日志id");

            $table->string('task_id', 50)->comment('任务id');
            $table->integer('length')->default(1)->comment("当前执行次数 排序");
            $table->integer('overtime')->default(0)->comment("超时时间 默认0 单位秒 0不超时");
            $table->json('bodys')->comment('执行体');

            $table->integer('execution')->comment('开始时间');
            $table->integer('complete')->comment('完成时间');
            $table->integer('implement')->comment('执行时间');

            $table->string('result')->comment("任务执行结果");// 该字段 同时也是 任务返回结果

            $table->integer('status')->default(0)->comment('虚拟状态 由模型去控制 默认 0 ');

            $table->integer('created_at')->comment("创建时间");
            $table->integer('updated_at')->comment("更新时间");

            $table->unique('task_id');
            $table->comment('系统任务日志表');
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
