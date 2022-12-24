<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected $baseUrl = 'http://localhost';
    
    /**
    * Default preparation for each test
    */
    public function setUp()
    {
        parent::setUp();

        $this->prepareForTests();
    }


    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__.'/../../bootstrap/start.php';
    }

    /**
    * Migrate the database
    */
    private function prepareForTests()
    {
        //Artisan::call('migrate');
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0';
        $_SERVER["REMOTE_ADDR"] = '111.111.111.111';


        //setup test database
        //mysqldump -u root laravel_kythera | mysql -u root test_kythera
    }
}
