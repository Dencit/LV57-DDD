<?php

namespace Modules\Demo\Consoles;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Demo\Http\Logics\SampleLogic;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * notes: 领域层-指令类
 * desc: 执行的业务逻辑统一抽象到 同名业务类中,这里不写具体业务代码.
 */
class SampleCmd extends Command
{
    /*
     * The console command name.
     */
    protected $name = 'command:sample';

    /*
     * The console command description.
     */
    protected $description = 'Sample Command description.';

    /*
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * Execute the console command.
     */
    public function handle()
    {
        $SampleLogic = new SampleLogic();
        try {
            //命令行 业务逻辑
            $result = $SampleLogic->sampleCmd();
        }catch (\Exception $e){
            Log::channel('task')->error($e->getMessage());throw $e;
        }

        // 指令输出
        if($result){
            Log::channel('task')->notice('sample cmd ok');
            $this->info( 'sample cmd ok' );
        }else{
            Log::channel('task')->notice('sample cmd end');
            $this->info( 'sample cmd end' );
        }
    }

    /*
     * Get the console command arguments.
     */
    protected function getArguments()
    {
        return [
            //['example', InputArgument::REQUIRED, 'An example argument.'],
            ['example', InputArgument::OPTIONAL, 'An example argument.'],
        ];
    }

    /*
     * Get the console command options.
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
