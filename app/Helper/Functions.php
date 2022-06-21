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

/**
 * 获取返回json
 * @param $method
 * @param $data
 * @param $is_push
 * @return false|string
 */
function getReturn($method, $data, $is_push = false)
{
    return json_encode(['method' => $method, 'data' => $data, 'is_push' => $is_push]);
}
