<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use Swoft;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\View\Renderer;
use Throwable;
use function bean;
use function context;
use Swoft\Redis\Redis;


/**
 * Class HomeController
 * @Controller()
 */
class HomeController
{
    /**
     * 创建监听
     * @RequestMapping("/")
     * @throws Throwable
     */
    public function index(): Response
    {
        return context()->getResponse()->withData(['code' => 200]);
    }

    /**
     * 创建监听
     * @RequestMapping("/create")
     * @throws Throwable
     */
    public function create(): Response
    {
        $request = context()->getRequest();
        $response = context()->getResponse();

        $jobKey = $request->post('job_key');
        $server = $request->post('server');
        $jobKey = $server . '-' . $jobKey;
        $isHasKey = Redis::hGet(config('app.job_hash_key'), $jobKey);
        if ($isHasKey == "create") {

            return $response->withData(['code' => 400, 'status' => false, 'msg' => 'this key has created!']);

        } else {

            Redis::hSet(config('app.job_hash_key'), $jobKey, "create");

            return $response->withData(['code' => 200, 'status' => true, 'msg' => 'success']);

        }
    }

    /**
     * 创建监听
     * @RequestMapping("/done")
     * @throws Throwable
     */
    public function done(): Response
    {
        $request = context()->getRequest();
        $response = context()->getResponse();

        $jobKey = $request->post('job_key');
        $server = $request->post('server');

        $key = $server . '-' . $jobKey;
        Redis::hSet(config('app.job_hash_key'), $key, "done");

        Redis::publish(config('app.done_chan'), $key);
        return $response->withData(['code' => 200, 'status' => true, 'msg' => 'success']);
    }

}
