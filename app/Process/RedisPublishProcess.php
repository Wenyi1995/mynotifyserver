<?php declare(strict_types=1);

namespace App\Process;

use App\Model\Logic\PublishLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Exception\DbException;
use Swoft\Process\Process;
use Swoft\Process\UserProcess;
use Swoft\Redis\Redis;

/**
 * Class MonitorProcess
 *
 * @since 2.0
 *
 * @Bean()
 */
class RedisPublishProcess extends UserProcess
{

    /**
     * @param Process $process
     *
     * @throws DbException
     */
    public function run(Process $process): void
    {
        ini_set('default_socket_timeout', '-1');
        while (true) {
            Redis::subscribe([config('app.done_chan')], function ($redis, $chan, $key) {
                \Swoft::server()->sendToAll(json_encode([$key => 'done']));
            });

        }
    }
}
