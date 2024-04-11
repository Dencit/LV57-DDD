<?php

namespace Modules\Demo\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * notes: 领域层-队列
 * 说明: 执行的业务逻辑统一封装 放在 对应的业务类中,这里不写具体业务代码.
 */
class SampleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle($requestInput)
    {
        Log::channel('queue')->notice('sample job end');
    }
}
