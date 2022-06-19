<?php declare(strict_types=1);

namespace App\Process;

use App\Model\Logic\PublishLogic;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Exception\DbException;
use Swoft\Process\Process;
use Swoft\Process\UserProcess;

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
     * @Inject()
     *
     * @var PublishLogic
     */
    private $logic;

    /**
     * @param Process $process
     *
     * @throws DbException
     */
    public function run(Process $process): void
    {
        $this->logic->doSend($process);
    }
}
