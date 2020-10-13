<?php declare(strict_types=1);

namespace App\Common;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class Wechat
 * @package App\Common
 * @Bean()
 */
class Wechat
{
    /** @var string 机器人推送地址 */
    const WECHAT = '';

    /**
     * 定义 错误码
     */
    const TESTLOG = -1;
    const ERRORLOG = 1000;

    public static $message = [
        self::TESTLOG => "
## 这是一条测试消息
> hello world!
执行时间: %s
",
        self::ERRORLOG => "
## 有一个任务执行失败
任务ID: %s
> 失败时间: %s 回调地址: %s

### 回调参数

```json

%s

```

**失败任务已入库**
"
    ];

    /**
     * 推送 机器人消息
     * @param $message
     * @throws GuzzleException
     */
    public function sendMessage($message): void
    {
        $client = new Client();
        $response = $client->request('POST', self::WECHAT, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => $message
        ]);

//        $body = $response->getBody()->getContents();
//        $code = $response->getStatusCode();
//        if ($code !== 200){
//            vdump($code);
//        }

//        vdump($body);
    }

    /**
     * 推送 markdown 消息
     * @param $message
     * @throws GuzzleException
     */
    public function sendMarkdownMessage($message)
    {
        $data = [
            'msgtype' => 'markdown',
            'markdown' => [
                'content' => $message
            ]
        ];

        $this->sendMessage($data);
    }

    /**
     * 发送 文本消息
     * @param $message
     * @param null|array $member
     * @throws GuzzleException
     */
    public function sendTextMessage($message,$member = null)
    {
        $data = [
            'msgtype' => 'text',
            'text' => [
                'content' => $message,
            ]
        ];

        if (!is_null($member)){
            $data['text']['mentioned_mobile_list'] = $member;
        }

        $this->sendMessage($data);
    }

}
