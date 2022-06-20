<?php

$envVal = env('WAREHOUSE_CODE');

return [
    // app.warehouseCode
    // 'warehouseCode' => ['a', 'b'],
    'warehouseCode' => $envVal ? explode(',', $envVal) : [],
    "job_hash_key"=>"jobKeyHash",
    "job_done_hash_key"=>"jobDoneTimeKeyHash",
    "done_chan"=>"jobDone",
    "redis_fds"=>"fds"
];
