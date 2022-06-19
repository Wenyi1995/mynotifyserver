<?php declare(strict_types=1);

namespace App\Model\Logic;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\Exception\DbException;
use Swoft\Log\Helper\CLog;
use Swoft\Process\Process;
use Swoft\Redis\Redis;
use Swoole\Coroutine;

/**
 * @since 2.0
 *
 * @Bean()
 */
class PublishLogic
{
    /**
     * @param Process $process
     *
     * @throws DbException
     */
    public function doSend(Process $process): void
    {
        $process->name('redis-publish');

        while (true) {
            Redis::subscribe([config('app.done_chan')], function ($redis, $chan, $key) {
                CLog::info($key);
                sendMsgToAll(json_encode([$key => 'done']));
            });

            Coroutine::sleep(3);
        }
    }
}
