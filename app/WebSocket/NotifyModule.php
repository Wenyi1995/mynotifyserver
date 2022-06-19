<?php declare(strict_types=1);

namespace App\WebSocket;

use App\WebSocket\Notify\NotifyController;
use Swoft\Http\Message\Request;
use Swoft\Redis\Redis;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\OnClose;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\WebSocket\Server\MessageParser\JsonParser;
use Swoole\WebSocket\Server;
/**
 * Class NotifyModule
 *
 * @WsModule(
 *     "/notify",
 *     defaultCommand="notice.index",
 *     messageParser=JsonParser::class,
 *     controllers={NotifyController::class}
 * )
 */
class NotifyModule
{
    /**
     * @OnOpen()
     * @param Request $request
     * @param int $fd
     */
    public function onOpen(Request $request, int $fd): void
    {
        Redis::sAdd(config('app.redis_fds'), (string)$fd);
        Session::current()->push(json_encode(getAllJobStatus()));
    }


    /**
     *
     * @OnClose()
     * @param Server $server
     * @param int    $fd
     */
    public function onClose(Server $server, int $fd): void
    {
        Redis::sRem(config('app.redis_fds'), (string)$fd);
    }
}
