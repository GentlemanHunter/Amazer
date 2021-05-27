<?php


namespace App\Listener;


use App\Enum\ActionEnum;
use App\Model\Entity\UserLog;
use Swoft\Event\Annotation\Mapping\Subscriber;
use Swoft\Event\EventInterface;
use Swoft\Event\Listener\ListenerPriority;
use Swoft\Event\EventSubscriberInterface;

/**
 * @Subscriber
 */
class ActionLog implements EventSubscriberInterface
{

    const USERLOGIN = 'user.login';

    public static function getSubscribedEvents(): array
    {
        return [
            self::USERLOGIN => ['addLog', ListenerPriority::HIGH]
        ];
    }

    /**
     * @throws \Swoft\Db\Exception\DbException
     */
    public function addLog(EventInterface $event)
    {
        $message = $event->getTarget();
        $params = [
            'uid' => $event->getParam('uid'),
            'visitor' => $event->getParam('visitor', getRequestIp()),
            'log' => $event->getParam('log'),
            'action' => $message,
            'create_at' => time()
        ];

        if (is_null($params['uid'])) {
            $params['uid'] = UID();
        }

        if (empty($params['log'])) {
            $userInfo = getUserInfo($params['uid']);
            $params['log'] = '{' . $userInfo['username'] . '} ' . ActionEnum::$EnumMessage[$message];
        }

        UserLog::insert($params);
    }
}
