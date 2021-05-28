<?php declare(strict_types=1);

namespace App\Listener\Service;

use App\Model\Dao\UserDao;
use App\Helper\MemoryTable;
use App\Event\HttpServiceEvent;
use Swoft\Event\EventInterface;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Server\SwooleEvent;
use Swoft\Stdlib\Helper\JsonHelper;

/**
 * Class StartServiceListener
 * @package App\Listener\Service
 * @Listener(SwooleEvent::START)
 */
class StartServiceListener implements EventHandlerInterface
{

    public function handle(EventInterface $event): void
    {
        /** @var UserDao $userDao */
        $userDao = bean('App\Model\Dao\UserDao');
        /** @var MemoryTable $table */
        $table = bean('App\Helper\MemoryTable');
        $userList = $userDao->getAdminList();
        if (empty($userList)) {
            $result = $userDao->createUser([
                'account' => 'admin',
                'username' => 'admin',
                'password' => null,
                'is_sys' => 1,
                'create_at' => time(),
                'update_at' => time()
            ]);

            if ($result > 0) {
                $table->store(
                    MemoryTable::TASK_ADMIN,
                    'admin',
                    ['list' => JsonHelper::encode([$result])]
                );
            }
        } else {
            $adminList = $table->get(MemoryTable::TASK_ADMIN, 'admin');
            if (empty($adminList)) {
                $adminList = [];
                foreach ($userList as $item) {
                    $adminList[] = $item->getId();
                }

                $table->store(
                    MemoryTable::TASK_ADMIN,
                    'admin',
                    ['list' => JsonHelper::encode($adminList)]
                );
            }
        }
    }
}
