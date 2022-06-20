<?php declare(strict_types=1);

namespace App\Process;

use App\Model\Logic\PublishLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Co;
use Swoft\Db\Exception\DbException;
use Swoft\Process\Process;
use Swoft\Process\UserProcess;
use Swoft\Redis\Redis;
use Swoole\Coroutine;

/**
 * Class MonitorProcess
 *
 * @since 2.0
 *
 * @Bean()
 */
class RedisKeysDelProcess extends UserProcess
{

    /**
     * @param Process $process
     *
     * @throws DbException
     */
    public function run(Process $process): void
    {
        while (true) {
            $time = time();
            $doneKeys = Redis::hSetAll(config('app.job_done_hash_key'));
            foreach ($doneKeys as $key => $t) {
                if (($time - $t) >= 1800) {
                    Redis::hDel(config('app.job_hash_key'), $key);
                    Redis::hDel(config('app.job_done_hash_key'), $key);
                }
            }
            Coroutine::sleep(60);
        }
    }
}
