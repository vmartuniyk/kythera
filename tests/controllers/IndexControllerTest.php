<?php
//http://taylorotwell.com/full-ioc-unit-testing-with-laravel/

class IndexControllerTest extends TestCase
{

    public function testIndexController()
    {
        $response = $this->action('GET', 'PageController@index');

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertViewHas('page');
    }

    public function testIndexRoute()
    {
        $response = $this->action('GET', 'site.page.home');

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertViewHas('page');
    }

    public function testIndexControllerEnglish()
    {
        $this->client->request('GET', '/en');

        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertViewHas('page');
    }

    /*
	public function testIndexControllerGreek()
	{
		$this->client->request('GET', '/gr');

		$this->assertTrue($this->client->getResponse()->isOk());
	}
	*/

    public function testPagesController()
    {
// 		$this->client->request('GET', '/');
// 		$this->assertEquals($response->getStatusCode(), 301);

        $this->client->request('GET', '/en');
        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertViewHas('page');

        $this->client->request('GET', '/en/contact');
        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertViewHas('page');
    }

    public function testGuestbookController()
    {
        $response = $this->action('GET', 'DocumentGuestbookController@getIndex');

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertViewHas('page');
    }

    public function testAdminController()
    {
        $response = $this->action('GET', 'AdminDashboardController@getIndex');

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testAdminControllerLogin()
    {
        $this->action('GET', 'AdminDashboardController@getIndex');
        $this->assertResponseStatus(200);
    }


    public function testDocumentTextControllerID()
    {
        //existing id
        ///en/people/nicknames/id/20904
        $this->action('GET', 'DocumentTextController@getId', ['id'=>5]);
        $this->assertResponseStatus(301);

        //non-existing id
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $response = $this->client->request('GET', '/en/people/nicknames/id/500000');
        $this->assertViewHas('q');
    }


    public function testDocumentTextControllerENTRIES()
    {
        //entries
        $this->action('GET', 'DocumentTextController@getIndex');
        $this->assertResponseOk();
        $this->assertViewHas('page');
        $this->assertViewHas('items');

        //existing entry
        ///en/people/nicknames/nicknames
        $this->action('GET', 'DocumentTextController@getEntry', ['entry'=>'nicknames']);
        $this->assertResponseOk();
        $this->assertViewHas('page');
        $this->assertViewHas('item');

        //non-existing entry
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $this->action('GET', 'DocumentTextController@getEntry', ['entry'=>'xxxxxxxxx']);
        $this->assertViewHas('q');
    }


    public function testDocumentAudioControllerID()
    {
        //existing id
        ///en/audiovideo/kytherian-music/id/9618
        $this->action('GET', 'DocumentAudioController@getId', ['id'=>9618]);
        $this->assertResponseStatus(301);

        //non-existing id
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $response = $this->client->request('GET', '/en/audiovideo/kytherian-music/id/500000');
        $this->assertViewHas('q');
    }


    public function testDocumentAudioControllerENTRIES()
    {
        //entries
        $this->action('GET', 'DocumentAudioController@getIndex');
        $this->assertResponseOk();
        $this->assertViewHas('page');
        $this->assertViewHas('items');

        //existing entry
        //en/audiovideo/kytherian-music/tsirigiotopoula
        $this->action('GET', 'DocumentAudioController@getEntry', ['entry'=>'tsirigiotopoula']);
        $this->assertResponseOk();
        $this->assertViewHas('page');
        $this->assertViewHas('item');

        //non-existing entry
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $this->action('GET', 'DocumentAudioController@getEntry', ['entry'=>'xxxxxxxxx']);
        $this->assertViewHas('q');
    }


    /**
     * TODO
     */
    public function testDocumentImageControllerID()
    {
    }


    /**
     * TODO
     */
    public function testDocumentImageControllerENTRIES()
    {
    }
}
