<?php
class ControllerTests extends TestCase
{
    public function testIndexController()
    {
        $response = $this->action('GET', 'PageController@index');

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testIndexRoute()
    {
        $response = $this->action('GET', 'site.page.home');

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testIndexControllerEnglish()
    {
        $this->client->request('GET', '/en');

        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testIndexControllerGreek()
    {
        $this->client->request('GET', '/gr');

        $this->assertTrue($this->client->getResponse()->isOk());
    }

    public function testGuestbookController()
    {
        $response = $this->action('GET', 'DocumentGuestbookController@getIndex');

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testAdminController()
    {
        $response = $this->action('GET', 'AdminDashboardController@getIndex');

        $this->assertEquals($response->getStatusCode(), 200);

        /*
		$crawler = $this->client->request('GET', '/en/admin');

		$this->assertTrue($this->client->getResponse()->isOk());
		*/
    }

    public function testAdminControllerLogin()
    {
        //$this->call('GET', '/en/admin');
        //$this->client->request('GET', '/en/admin');

        $this->action('GET', 'AdminDashboardController@getIndex');
        $this->assertResponseStatus(200);
    }
}
