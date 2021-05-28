<?php declare(strict_types=1);


namespace Database\Migration;


use Swoft\Db\Schema\Blueprint;
use Swoft\Db\Exception\DbException;
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
        $this->schema->createIfNotExists(self::TABLE, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->comment('ID');
            $table->string('account', 50)->comment('User`s account');
            $table->string('email', 30)->nullable()->default(null)
                ->comment('User`s email');
            $table->char('mobile', 11)->nullable()->default(null)
                ->comment('User`s mobile');
            $table->char('username', 30)->comment('User`s nickname');
            $table->char('password', 60)->nullable()->default(null)
                ->comment('User`s password');
            $table->ipAddress('visitor')->nullable()->comment("Last login ip");
            $table->integer('create_at')->comment("Creation time");
            $table->integer('update_at')->comment("Update time");
            $table->integer('delete_at')->nullable()->default(null)
                ->comment('Delete time, null is not deleted');
            $table->integer('is_sys')->index()->default(0)
                ->comment('Whether it is created by the system, 0 is not');
            $table->integer('status')->default(1)->comment('user status');

            $table->unique('account','idx_account');
            $table->index('email','idx_email');
            $table->index('mobile','idx_mobile');
            $table->index('status','idx_status');
            $table->index('is_sys','idx_issys');
            $table->comment('System User Table');
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
