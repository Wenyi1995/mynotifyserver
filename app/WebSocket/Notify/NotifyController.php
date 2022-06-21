<?php declare(strict_types=1);

namespace App\WebSocket\Notify;

use Swoft\Log\Helper\CLog;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;
use Swoft\WebSocket\Server\Message\Message;
use function json_encode;

/**
 * Class NotifyController
 *
 * @WsController()
 */
class NotifyController
{

    /**
     *
     * @param Message $msg
     * @return void
     * @MessageMapping("index")
     */
    public function index(Message $msg): void
    {
        $data = $msg->getData();
        if ($data) {
            $info = getJobStatus($data);
            $info = [$data => $info];
        } else {
            $info = getAllJobStatus();
        }

        Session::current()->push(getReturn('show',$info));
    }

    /**
     *
     * @return void
     * @MessageMapping("ping")
     */
    public function ping(): void
    {
        Session::current()->push(getReturn('pong',''),WEBSOCKET_OPCODE_PONG);
    }

}
