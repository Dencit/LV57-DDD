<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $app = null;
    protected $base_path = null;
    protected $test_path = null;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->app = $this->createApplication();

        $this->base_path= $this->app->basePath();
        $this->test_path= $this->app->basePath().'\\'.env('MUI_TEST_PATH','none');
    }
}
