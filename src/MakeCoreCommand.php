<?php

namespace Hanson\Wechat\Handler;

use Illuminate\Console\Command;
use Illuminate\Console\AppNamespaceDetectorTrait;

class MakeCoreCommand extends Command
{
    use AppNamespaceDetectorTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:handler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create handler/controller/config for easyWechat';

    /**
     * The handlers that need to be exported.
     *
     * @var array
     */
    protected $handlers = [
        'EventHandler.stub' => 'EventHandler.php',
        'HandlerInterface.stub' => 'HandlerInterface.php',
        'ImageHandler.stub' => 'ImageHandler.php',
        'LinkHandler.stub' => 'LinkHandler.php',
        'LocationHandler.stub' => 'LocationHandler.php',
        'ShortVideoHandler.stub' => 'ShortVideoHandler.php',
        'VideoHandler.stub' => 'VideoHandler.php',
        'TextHandler.stub' => 'TextHandler.php',
        'VoiceHandler.stub' => 'VoiceHandler.php',
        'WechatHandler.stub' => 'WechatHandler.php',
        'events/LocationHandler.stub' => 'EventHandlers/LocationHandler.php',
        'events/ScanHandler.stub' => 'EventHandlers/ScanHandler.php',
        'events/SubscribeHandler.stub' => 'EventHandlers/SubscribeHandler.php',
        'events/UnSubscribeHandler.stub' => 'EventHandlers/UnSubscribeHandler.php',
        'events/ViewHandler.stub' => 'EventHandlers/ViewHandler.php',
        'events/ClickHandler.stub' => 'EventHandlers/ClickHandler.php',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->createDirectories();

        $this->createHandlers();

        $this->createConfig();

        $this->makeControllers('HomeController');
        $this->makeControllers('BaseController');
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        if (! is_dir(base_path($this->appPath() . '/Handlers'))) {
            mkdir(base_path($this->appPath() . '/Handlers'), 0755, true);
        }

        if (! is_dir(base_path($this->appPath() . '/Handlers/EventHandlers'))) {
            mkdir(base_path($this->appPath() . '/Handlers/EventHandlers'), 0755, true);
        }

        if (! is_dir(base_path($this->appPath() . '/Http/Controllers/Wechat'))) {
            mkdir(base_path($this->appPath() . '/Http/Controllers/Wechat'), 0755, true);
        }

        if (! is_dir(base_path('config'))) {
            mkdir(base_path('config'), 0755, true);
        }
    }

    protected function createHandlers()
    {
        foreach ($this->handlers as $key => $handler) {
            $path = base_path($this->appPath() . '/Handlers/'.$handler);

            $this->line('<info>Created Handler:</info> '.$path);

            copy(__DIR__.'/stubs/handlers/'.$key, $path);
        }
    }

    protected function createConfig()
    {
        $path = base_path('config/wechat.php');

        copy(__DIR__.'/stubs/wechat.stub', $path);
    }

    protected function makeControllers($controller)
    {
        file_put_contents(
            base_path($this->appPath() . '/Http/Controllers/Wechat/'.$controller.'.php'),
            $this->compileControllerStub($controller)
        );
    }

    protected function compileControllerStub($controller)
    {
        return str_replace(
            '{{namespace}}',
            $this->getAppNamespace(),
            file_get_contents(__DIR__.'/stubs/controller/'.$controller.'.stub')
        );
    }

    protected function appPath()
    {
        $basePath = base_path();
        
        return substr($basePath, 0, strlen($basePath) - 1);
    }

}