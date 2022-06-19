<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

function getJobStatus($job_name): string
{
    return \Swoft\Redis\Redis::hGet(config('app.job_hash_key'), $job_name);
}

function getAllJobStatus(): array
{
    return \Swoft\Redis\Redis::hGetAll(config('app.job_hash_key'));
}

function sendMsgToAll($msg): bool
{
    $fds = \Swoft\Redis\Redis::sMembers(config('app.redis_fds'));
    if ($fds) {
        foreach ($fds as $fd) {
            \Swoft::server()->sendTo((int)$fd, $msg);
        }
    }
    return true;
}
