<?php
use Illuminate\Routing\Router;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Models\PageEntity;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use FFMpeg\Format\Video\Ogg;
use Kythera\Models\DocumentVideo;
use Carbon\Carbon;
use Kythera\Models\Person;


/**
 * ------------------------------------------
 * Route model binding
 * ------------------------------------------
 */
// Route::model('page', 'Page');

/**
 * ------------------------------------------
 * Route constraint patterns
 * ------------------------------------------
 */
// Route::pattern('page', '[0-9]+');



/*
Route::get('/', function() {
    echo 'homex';
});
*/

Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]
],
function()
{

    /**
     * ------------------------------------------
     * Admin Routes
     * ------------------------------------------
     */
    // update routes with ./composer.phar dump-autoload
    Route::group([
            'prefix' => 'admin',
            'before' => 'admin'
    ], function () {
        // Admin pages
        // Route::resource('pages', 'AdminPageController');
        Route::get('pages/', [
                'as' => 'admin.page.index',
                'uses' => 'Admin\AdminPageController@index'
        ]);
        Route::get('pages/create', [
                'as' => 'admin.page.create',
                'uses' => 'Admin\AdminPageController@create'
        ]);
        Route::post('pages/order', [
                'as' => 'admin.page.order',
                'uses' => 'Admin\AdminPageController@order'
        ]);
        Route::post('pages/', [
                'as' => 'admin.page.store',
                'uses' => 'Admin\AdminPageController@store'
        ]);
        Route::get('pages/{pages}/edit', [
                'as' => 'admin.page.edit',
                'uses' => 'Admin\AdminPageController@edit'
        ]);
        Route::post('pages/{pages}', [
                'as' => 'admin.page.update',
                'uses' => 'Admin\AdminPageController@update'
        ]);
        Route::post('pages/{pages}/delete', [
                'as' => 'admin.page.destroy',
                'uses' => 'Admin\AdminPageController@destroy'
        ]);

        //Admin villages
        Route::get('villages/', [
            'as' => 'admin.village.index',
            'uses' => 'Admin\AdminVillageController@index'
        ]);
        Route::get('villages/{id}/edit', [
            'as' => 'admin.village.edit',
            'uses' => 'Admin\AdminVillageController@edit'
        ]);
        Route::put('villages/{id}', [
            'as' => 'admin.village.update',
            'uses' => 'Admin\AdminVillageController@update'
        ]);
        Route::post('villages/', [
            'as' => 'admin.village.store',
            'uses' => 'Admin\AdminVillageController@store'
        ]);
        Route::post('villages/{id}/compounds/{compound_id}/delete', [
            'as' => 'admin.village.compound.destroy',
            'uses' => 'Admin\AdminVillageController@destroyCompound'
        ]);
// 		Route::get ('villages/compounds/create/{villageId}', array (
// 			'as' => 'admin.compound.create',
// 			'uses' => 'AdminVillageController@createCompound'
// 		));
        Route::get('villages/create', [
            'as' => 'admin.village.create',
            'uses' => 'Admin\AdminVillageController@create'
        ]);
        Route::post('villages/{id}/delete', [
            'as' => 'admin.village.destroy',
            'uses' => 'Admin\AdminVillageController@destroy'
        ]);

        //Admin names
        Route::get('names/', [
            'as' => 'admin.name.index',
            'uses' => 'Admin\AdminPeopleNameController@index'
        ]);
        Route::get('names/create', [
            'as' => 'admin.name.create',
            'uses' => 'Admin\AdminPeopleNameController@create'
        ]);
        Route::get('names/{id}/edit', [
            'as' => 'admin.name.edit',
            'uses' => 'Admin\AdminPeopleNameController@edit'
        ]);
        Route::post('names/', [
            'as' => 'admin.name.store',
            'uses' => 'Admin\AdminPeopleNameController@store'
        ]);
        Route::post('names/{id}/delete', [
            'as' => 'admin.name.destroy',
            'uses' => 'Admin\AdminPeopleNameController@destroy'
        ]);
        Route::post('names/{id}/compounds/{compound_id}/delete', [
            'as' => 'admin.name.compound.destroy',
            'uses' => 'Admin\AdminPeopleNameController@destroyCompound'
        ]);
        Route::put('names/{id}', [
            'as' => 'admin.name.update',
            'uses' => 'Admin\AdminPeopleNameController@update'
        ]);

        /*
		// People controllers
		// Route::resource('people/', 'Admin\AdminPeopleController');
		Route::group ( array (
				'prefix' => 'people'
		), function () {
			// /admin/people/names
			// Route::resource('people/names', 'Admin\AdminPeopleNamesController');
			Route::resource ( 'names', 'Admin\AdminPeopleNamesController', array (
					'names' => array (
							'index' => 'admin.people.names.index',
							'create' => 'admin.people.names.create',
							'store' => 'admin.people.names.store',
							'show' => 'admin.people.names.show',
							'edit' => 'admin.people.names.edit',
							'update' => 'admin.people.names.update',
							'destroy' => 'admin.people.names.destroy'
					)
			) );
			// /admin/people/nicknames
			// /admin/people/surnames
		} );
		*/


        //Admin Users
/*		Route::get ('users/', array (
			'as' => 'admin.user.index',
			'uses' => 'Admin\AdminUserController@index'
		));
		Route::get ('users/{id}/edit', array (
			'as' => 'admin.user.edit',
			'uses' => 'Admin\AdminUserController@edit'
		));


*/
        //Admin resources
        Route::resource('users', 'Admin\AdminUserController', [
                'names' =>  [
                        'index' => 'admin.user.index',
                        'create' => 'admin.user.create',
                        'store' => 'admin.user.store',
                        'show' => 'admin.user.show',
                        'edit' => 'admin.user.edit',
                        'update' => 'admin.user.update',
                        'destroy' => 'admin.user.destroy'
                ]
        ]);
        Route::get('users/{id}/action/{action}', [
            'as' => 'admin.users.action',
            'uses' => 'Admin\AdminUserController@action'
        ])->where([
            'id' => '[0-9]+',
            'action' => 'disable|enable|promote|degrade|confirm|delete|activate-admin-email|deactivate-admin-email'
        ]);

        Route::get('entries/{type}', [
            'as' => 'admin.entry.index',
            'uses' => 'Admin\AdminEntryController@index'
        ])->where([
            'type' => 'document|comment|guestbook|tree|top'
        ]);
        Route::get('entries/{type}/search', [
            'as' => 'admin.entry.search',
            'uses' => 'Admin\AdminEntryController@search'
        ])->where([
            'type' => 'document|comment|guestbook|tree|top'
        ]);

        Route::any('entries/multiDelete/{ids}', [
            'as' => 'admin.entry.multiDelete',
            'uses' => 'Admin\AdminEntryController@multiDelete'
        ]);

/*
	    Route::resource('entries', 'Admin\AdminEntryController', array (
    	    'names' => array (
        	    'index' => 'admin.entry.index',
        	    'create' => 'admin.entry.create',
        	    'store' => 'admin.entry.store',
        	    'show' => 'admin.entry.show',
        	    'edit' => 'admin.entry.edit',
        	    'update' => 'admin.entry.update',
        	    'destroy' => 'admin.entry.destroy'
            )
	    ));
	*/

        Route::resource('document_types', 'Admin\AdminDocumentTypesController');

        // Admin controllers
        Route::controller('command', 'Admin\AdminCommandController');
        Route::controller('migrate', 'Admin\AdminImportController');
        Route::controller('/', 'Admin\AdminDashboardController');
    });

    /**
     * ------------------------------------------
     * Frontend Routes
     * ------------------------------------------
     */
    Route::group([
        'middleware' => 'auth'
    ], function () {
        // Single entry
        Route::post('entry/upload', [
            'as' => 'entry.upload',
            'uses' => 'EntryController@upload'
        ]);
        Route::post('entry/delete', [
            'as' => 'entry.delete',
            'uses' => 'EntryController@delete'
        ]);
        Route::resource('entry', 'EntryController', [
            'names' =>  [
                'index' => 'entry.index',
                'create' => 'entry.create',
                'store' => 'entry.store',
                'show' => 'entry.show',
                'edit' => 'entry.edit',
                'update' => 'entry.update',
                'destroy' => 'entry.destroy'
            ]
        ]);
        Route::get('entry/create/{cat?}', [
            'as' => 'entry.create.cat',
            'uses' => 'EntryController@create'
        ])->where([
            'cat' => '[0-9]+'
        ]);

        Route::post('entry/next/{entry?}', [
            'as' => 'entry.next',
            'uses' => 'EntryController@next'
        ])->where([
                'entry' => '[0-9]+'
        ]);
        Route::get('entry/enable/{entry}/{value}', [
            'as' => 'entry.enable',
            'uses' => 'EntryController@enable'
        ])->where([
            'entry' => '[0-9]+',
            'value' => '0|1'
        ]);
        Route::get('entry/promote/{entry}/{value}', [
            'as' => 'entry.promote',
            'uses' => 'EntryController@promote'
        ])->where([
            'entry' => '[0-9]+',
            'value' => 'promote|degrade'
        ]);
        //Notification mails
        Route::get('entry/{entry}/action/{action}', [
                'as' => 'entry.action',
                'uses' => 'EntryController@action'
        ])->where([
                'entry' => '[0-9]+',
                'action' => 'disable|promote|degrade|facebook'
        ]);

        //Multi entry
        Route::get('entries', [
                'as' => 'entries.create',
                'uses' => 'EntriesController@create'
        ]);
        Route::get('entries/next', [
                'as' => 'entries.next',
                'uses' => 'EntriesController@next'
        ]);
        Route::post('entries/next', [
                'as' => 'entries.next',
                'uses' => 'EntriesController@next'
        ]);
        Route::post('entries/store', [
                'as' => 'entries.store',
                'uses' => 'EntriesController@store'
        ]);

        //Comments
        Route::get('comment/{id}/edit', [
                'as' => 'comment.edit',
                'uses' => 'DocumentCommentController@edit'
        ])->where([
                'id' => '[0-9]+'
        ]);
        Route::put('comment/{id}', [
                'as' => 'comment.update',
                'uses' => 'DocumentCommentController@update'
        ])->where([
                'id' => '[0-9]+'
        ]);
        Route::get('comment/enable/{id}/{value}', [
                'as' => 'comment.enable',
                'uses' => 'DocumentCommentController@enable'
        ])->where([
                'id' => '[0-9]+',
                'value' => '0|1'
        ]);

        //Guestbook
        Route::get('guestbook/contact/{id}', [
                'as' => 'guestbook.contact',
                'uses' => 'DocumentGuestbookController@contact'
        ])->where([
                'id' => '[0-9]+'
        ]);
        Route::post('guestbook/send', [
                'as' => 'guestbook.send',
                'uses' => 'DocumentGuestbookController@send'
        ]);
        Route::get('guestbook/{id}/edit', [
                'as' => 'guestbook.edit',
                'uses' => 'DocumentGuestbookController@edit'
        ])->where([
                'id' => '[0-9]+'
        ]);
        Route::put('guestbook/{id}', [
                'as' => 'guestbook.update',
                'uses' => 'DocumentGuestbookController@update'
        ])->where([
                'id' => '[0-9]+'
        ]);
				Route::get('guestbook/{id}/delete', [
                'as' => 'guestbook.delete',
                'uses' => 'DocumentGuestbookController@delete'
        ])->where([
                'id' => '[0-9]+'
        ]);


        //Contact user
        Route::get('user/contact/{entry}/{category?}', [
                'as' => 'user.contact',
                'uses' => 'UsersController@getContact'
        ])->where([
                'entry' => '[0-9]+',
                'category' => '[0-9]+'
        ]); 
        Route::post('user/contact/send', [
                'as' => 'user.contact.send',
                'uses' => 'UsersController@postContact'
        ]);

        //Messageboard
        Route::post('message-board/store', [
                'as' => 'message.board.store',
                'uses' => 'DocumentMessageController@store'
        ]);
        Route::put('message-board/{id}', [
                'as' => 'message.board.update',
                'uses' => 'DocumentMessageController@update'
        ])->where([
                'id' => '[0-9]+'
        ]);

        //FamilyTree
        Route::get('family-trees/{persons_id}/edit', [
                'as' => 'person.edit',
                'uses' => 'DocumentPersonController@edit'
        ])->where([
                'persons_id' => '[0-9]+'
        ]);
        Route::get('family-trees/{persons_id}/add/{member}', [
                'as' => 'person.add',
                'uses' => 'DocumentPersonController@add'
        ])->where([
                'persons_id' => '[0-9]+',
                'member' => '[1-4]' //update FamilyPerson::MEMBER_* consts
        ]);
        Route::get('family-trees/create', [
                'as' => 'person.create',
                'uses' => 'DocumentPersonController@create'
        ]);
        Route::post('family-trees/store', [
                'as' => 'person.store',
                'uses' => 'DocumentPersonController@store'
        ]);
        Route::get('family-trees/{persons_id}/delete/{member}/{relative_id}', [
            'as' => 'person.delete',
            'uses' => 'DocumentPersonController@delete'
        ])->where([
            'persons_id' => '[0-9]+',
            'member' => '[1-4]', //update FamilyPerson::MEMBER_* consts
            'relative_id' => '[0-9]+',
        ]);
        Route::post('family-trees/invite', [
                'as' => 'person.invite',
                'uses' => 'DocumentPersonController@invite'
        ]);

        #GEDCOM
        Route::controller('gedcom', 'GedcomController');
        Route::controller('gc-upload', 'GedcomUploadController');
        Route::controller('gc-parse', 'GedcomParseController');


        Route::get('test-admin', function () {
            foreach (config('app.administrators') as $administrator) {
                echo "$administrator<br>";
            }
        });

        Route::get('test-roles', function () {
            /*
             * $owner = new Role;
             * $owner->name = 'Owner';
             * $owner->save();
             */
            /*
             * $admin = new Role;
             * $admin->name = 'administrator';
             * $admin->save();
             */

            echo "<br>Finding admin permission....";

            $admin = Role::whereName('administrator')->first();
            echo __FILE__ . __LINE__ . '<pre>$admin=' . htmlentities(print_r($admin, 1)) . '</pre>';

            $emails = [
                'james@kythera-family.net',
                'ewto@cs.tu-berlin.de',
                'nauman@naumanch.com',
                'virgil@virtec.org',
                'virgilm@mirror.virtec.org'
            ];
            foreach ($emails as $email) {
                echo "<br>Setting permissions to $email....";
                if ($user = User::where('email', $email)->first()) {
                    if (! $user->hasRole('administrator')) {
                        $user->attachRole($admin);
                        echo "OK";
                    } else {
                        echo "Already admin";
                    }
                } else {
                    echo "User doesnt exist.";
                }
            }

            echo "<br>DONE";
        });

        Route::get('test-mail', function () {
            echo "<br>Sending mail....";
            $data = [];
            $data['body'] = 'Normal mail.';
            $result = Mail::send('emails.test', $data, function ($message) {
                $message
                    ->from(config('app.administrator'), 'kythera-family.net Administrator')
                    ->to(config('app.developer'), 'kythera-family.net Administrator')
                    ->bcc(config('app.developer'), 'developer')
                    ->subject('[KFN-TEST] Normal mail');
            });
            echo __FILE__ . __LINE__ . '<pre>$result=' . htmlentities(print_r($result, 1)) . '</pre>';
            echo "<br>DONE";


            echo "<br>Sending mail through queue....";
            $data = [];
            $data['body'] = 'Queued mail.';
            $result = Mail::queue('emails.test', $data, function ($message) {
                $message
                    ->from(config('app.administrator'), 'kythera-family.net Administrator')
                    ->to(config('app.developer'), 'kythera-family.net Administrator')
                    ->bcc(config('app.developer'), 'developer')
                    ->subject('[KFN-TEST] Queued mail');
            });
            echo __FILE__ . __LINE__ . '<pre>$result=' . htmlentities(print_r($result, 1)) . '</pre>';
            echo "<br>DONE";
        });
    });

    // Info page
    Route::get('info', 'InfoController@index');
    // Route::controller( 'natural-history-museum', 'InfoController');

		// Authentication routes
		Route::get('users/login', 'UsersController@getLogin');
		Route::get('users/access', 'UsersController@getAccess');
		Route::post('users/login', 'UsersController@postLogin');
		Route::get('users/logout', 'UsersController@getLogout');

		// Registration routes
		Route::get('users/register', 'UsersController@getRegister');
		Route::post('users/register', 'UsersController@postRegister');
    Route::get('users/confirm/{code}', 'UsersController@getConfirm');

		// Password reset routes
    Route::get('users/password/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('users/password/email', 'Auth\PasswordController@sendResetLinkEmail');
		Route::post('users/password/reset', 'Auth\PasswordController@reset');


		// @francesdath 2017-06-13
    // putting this here as it should ideally reference Confide with updating
    Route::get('your-personal-page/edit', 'PersonalPageController@edit');
    Route::get('your-personal-page/comments', 'PersonalCommentController@index');
    Route::get('your-personal-page/categories', 'PersonalCategoryController@index');
    Route::get('your-personal-page/families', 'PersonalFamilyController@index');

//    Route::get('your-personal-page/get-comment/{id}', 'PersonalPageController@getComment');

    Route::put('your-personal-page/edit', 'PersonalPageController@update');
    Route::post('your-personal-page/edit', 'PersonalPageController@update');


//	Route::get ( 'your-personal-page/edit', 'PersonalPageController@update' );
//	Route::post ( 'your-personal-page/edit', 'PersonalPageController@changePassword' );
//	Route::put ( 'your-personal-page/edit', 'PersonalPageController@changePassword' );

/*
	Route::get ( 'your-personal-page/edit', array (
		'as' => 'site.page.your.personal.page.edit',
		'uses' => 'PersonalPageController@edit'

	));

	Route::put( 'your-personal-page/edit', [
		'as' => 'updateprofile',
		'uses' => 'PersonalPageController@update'
	]);

	Route::post( 'your-personal-page/edit', [
		'as' => 'updateprofile',
		'uses' => 'PersonalPageController@update'
	]);

	Route::put( 'your-personal-page/edit', [
		'as' => 'changepassword',
		'uses' => 'PersonalPageController@changePassword'
	]);

	Route::post( 'your-personal-page/edit', [
		'as' => 'changepassword',
		'uses' => 'PersonalPageController@changePassword'
	]);

*/


/*
	Route::post ('your-personal-page/edit{$id}', array (
		'as' => 'site.page.your.personal.page.update',
		'uses' => 'PersonalPageController@update'
	));

*/


    // Handle urls referencing old domain
    Route::get('redirect', 'RedirectController@index');

    // Handle media requests
    Route::get('photos/{width}/{height}/placeholder/{name?}', [
        'as' => 'image.placeholder',
        'uses' => 'ImageController@placeholder'
    ])->where([
        'width' => '[0-9]+',
        'height' => '[0-9]+'
    ]);
    /*
	 * Route::get('photosx/{path}/{width}/{height}/{name?}', array(
	 * 'as'=>'image.view',
	 * 'uses'=>'ImageController@image3'
	 * ))->where(array('path'=>'[0-9]+', 'width'=>'[0-9]+', 'height'=>'[0-9]+'));
	 */
    Route::get('audio/{path}/{name?}', [
        'as' => 'audio.view',
        'uses' => 'AudioController@audio'
    ])->where([
        'path' => '[0-9]+'
    ]);
    Route::get('video/{path}/{name?}', [
        'as' => 'video.view',
        'uses' => 'VideoController@video'
    ])->where([
        'path' => '[0-9]+'
    ]);

    // Language
    Route::post(Lang::get('routes.language'), [
            'as' => 'site.language',
            'uses' => 'LanguageController@postLanguage'
    ]);

    /*
	 * // No caching for pages
	 * Route::filter('after', function($response)
	 * {
	 * $response->header("Pragma", "no-cache");
	 * $response->header("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0");
	 * });
	 */

    //Comments
    Route::post('comment/create', [
        'as' => 'comment.create.post',
        'uses' => 'DocumentCommentController@create'
    ]);
    //->required when a user posts without being loggedin (data has been stored in session)
    Route::get('comment/create', [
        'as' => 'comment.create.get',
        'uses' => 'DocumentCommentController@create'
    ]);

    //Guestbook
    Route::post('guestbook/create', [
        'as' => 'guestbook.create',
        'uses' => 'DocumentGuestbookController@create'
    ]);

    //Message Board
    Route::get('message-board/create', [
        'as' => 'message.board.create',
        'uses' => 'DocumentMessageController@create'
    ]);
    Route::post('message-board/store', [
            'as' => 'message.board.store',
            'uses' => 'DocumentMessageController@store'
    ]);
    //->required when a user posts without being loggedin
    Route::get('message-board/store', [
            'as' => 'message.board.store',
            'uses' => 'DocumentMessageController@store'
    ]);

    Route::post('message-board/reply', [
        'as' => 'message.board.reply',
        'uses' => 'DocumentMessageController@reply'
    ]);
    //->required when a user posts without being loggedin
    Route::get('message-board/reply', [
        'as' => 'message.board.reply.get',
        'uses' => 'DocumentMessageController@reply'
    ]);

    //Familytree
    Route::get('family-trees/{persons_id}/info', [
        'as' => 'family.info',
        'uses' => 'AjaxController@getFamilyTreePersonInfo'
    ])->where([
        'persons_id' => '[0-9]+'
    ]);
		// Since Laravel 5.2
		Route::get('family-trees/{entry}', [
						'as' => 'site.page.family.trees.entry',
						'uses' => 'DocumentFamilyController@getEntry'
		])->where([
						'entry' => '[0-9]+'
		]);


    //newsletter
    /*
	Route::post('newsletter/subscribe', array(
		'as' => 'newsletter.subscribe',
		'uses' => 'NewsLetterController@subscribe'
	));
	*/

    //kfn.laravel.debian.mirror.virtec.org/en/photos/1/100/100/1057695278.jpg
    Route::get('photos/{path}/{width}/{height}/{name?}', [
        'as' => 'image.view',
        'uses' => 'ImageController@image'
    ])->where([
        'path' => '[0-9]+',
        'width' => '[0-9]+',
        'height' => '[0-9]+'
    ]);



    Route::get('document/{id}', [
        'as' => 'document.info',
        'uses' => 'DocumentTextController@getId'
    ])->where([
        'id' => '[0-9]+'
    ]);

    Route::get('decode', function () {
        /*
	    Queue::push(function($job) {
	        $file = public_path('video'). '/small.mov';

	        $ffmpeg = FFMpeg\FFMpeg::create(
	            array(
	                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
	                'ffprobe.binaries' => '/usr/bin/ffprobe',
	                'timeout' => 0,
	                'ffmpeg.threads' => 6
	            ),
	            app()['log']->getMonolog()
	        );

	        $video = $ffmpeg->open($file);
	        $video
    	        ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(3))
    	        ->save(public_path('video'). '/frame.jpg');

	        $format = new FFMpeg\Format\Video\X264();
	        $video
	            ->save($format, public_path('video'). '/export-x264.mp4');

	        $format = new FFMpeg\Format\Video\Ogg();
	        $video
	           ->save($format, public_path('video'). '/export-ogg.ogg');

	        $format = new FFMpeg\Format\Video\WebM();
	        $video
	           ->save($format, public_path('video'). '/export-webm.webm');

	        $job->delete();

	    });
	        echo "done";
	    return;
	    */

        echo "decoding";
        $file = public_path('video'). '/small.mov';
        $file = public_path('video'). '/small.flv';
        //$file = public_path('video'). '/small.avi';//mpeg4
        //$file = public_path('video'). '/small.m4v';//h246
        //$file = public_path('video'). '/small.3gp';//not h263
        //$file = public_path('video'). '/small.mkv';
        //$file = public_path('video'). '/small.mp4';//h246
        ////$file = public_path('video'). '/small.mpg';
        //$file = public_path('video'). '/small.ogv';//theora
        //$file = public_path('video'). '/small.webm';//vp8
        //$file = public_path('video'). '/small.wmv';//wmv2
        //$file = public_path('video'). '/x.m4v';//h246
        //$file = public_path('video'). '/posters/poster.jpg';
        //$file = public_path('video'). '/posters/poster.png';
        //$file = public_path('video'). '/x.pdf';
        $isVideo = DocumentVideo::isVideo($file);
        echo __FILE__.__LINE__.'<pre>$isVideo='.htmlentities(print_r($isVideo, 1)).'</pre>';


        $ffmpeg = FFMpeg\FFMpeg::create(
            [
                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/bin/ffprobe',
                'timeout' => 0,
                'ffmpeg.threads' => 6
            ],
            app()['log']->getMonolog()
        );
        $video = $ffmpeg->open($file);

        /*
		try {
    		$ffprobe = FFMpeg\FFProbe::create();
    		$c = $ffprobe
        		->streams($file) // extracts streams informations
        		->videos()                      // filters video streams
        		->first()                       // returns the first video stream
        		->get('codec_name');
            echo __FILE__.__LINE__.'<pre>$c='.htmlentities(print_r($c,1)).'</pre>';
		} catch (RuntimeException $e) {
		    echo "PROBE failed: " . $e->getPrevious()->getMessage();
		}
		*/

        $video
          ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(3))
          ->save(public_path('video'). '/frame.jpg');
        return;

        try {
            $format = new FFMpeg\Format\Video\X264();
            $video
              ->save($format, public_path('video'). '/export-x264.mp4');
            echo "MP4 OK";
        } catch (RuntimeException $e) {
            echo ("MP4 failed: " . $e->getMessage());
            $t = $e->getPrevious()->getTrace();
            $t = $t[1];//0=command
            $p = $t['args'][0];//Symfony\Component\Process\Process Object
            $o = $p->getErrorOutput();
            Log::alert('MPEG', [$o]);
        }

        try {
            $format = new FFMpeg\Format\Video\Ogg();
            $video
                ->save($format, public_path('video'). '/export-ogg.ogg');
            echo "OGG OK";
        } catch (RuntimeException $e) {
            echo ("OGG failed: " . $e->getMessage());
            $t = $e->getPrevious()->getTrace();
            $t = $t[1];//0=command
            $p = $t['args'][0];//Symfony\Component\Process\Process Object
            $o = $p->getErrorOutput();
            Log::alert('MPEG', [$o]);
        }

        try {
            $format = new FFMpeg\Format\Video\WebM();
            $video
                ->save($format, public_path('video'). '/export-webm.webm');
            echo "WebM OK";
        } catch (RuntimeException $e) {
            echo ("WebM failed: " . $e->getMessage());
            $t = $e->getPrevious()->getTrace();
            $t = $t[1];//0=command
            $p = $t['args'][0];//Symfony\Component\Process\Process Object
            $o = $p->getErrorOutput();
            Log::alert('MPEG', [$o]);
        }

        echo "done";
    });

    //Search
    /*
	Route::get('suggest', array(
		'as' => 'suggest',
		'uses' => 'AjaxController@suggest'
	));
	*/
    Route::get('suggest/{length}', [
        'as' => 'suggest',
        'uses' => 'AjaxController@esuggest'
    ])->where([
        'length' => '[1-9]'
    ]);


    Route::get('weather', function () {
        //http://www.yourweather.co.uk/widget/?gclid=CJ7Z5rmm68kCFSHmwgodjy4F7A
        //http://www.wunderground.com/about/data.asp
        $page = new stdClass();
        $page->title = 'Kythera weather';
        $page->crumbs = [];
        $page->content = '';
        return view('site.page.weather.index', ['page'=>$page]);
    });


        Route::get('/ff', function () {

            $file = public_path('video').'/kythera1964.mp4';

            if (file_exists($file)) {
                echo __FILE__.__LINE__.'<pre>$file='.htmlentities(print_r($file, 1)).'</pre>';
                die;


                $dst_path   = sprintf('%s/%s', public_path(), $dst);
                $dst_mp4    = sprintf('%s%s.m4v', $dst_path, $base);
                $dst_ogg    = sprintf('%s%s.ogg', $dst_path, $base);
                $dst_webm   = sprintf('%s%s.webm', $dst_path, $base);
                $dst_poster = sprintf('%s%s.jpg', $dst_path, $base);

                $supplied   = [];

                $ffmpeg = \FFMpeg\FFMpeg::create(
                    [
                                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                                'ffprobe.binaries' => '/usr/bin/ffprobe',
                                'timeout' => 0,
                                'ffmpeg.threads' => 6
                        ],
                    app()['log']->getMonolog()
                );

                $video = $ffmpeg->open($file);
                $video
                ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(3))
                ->save($dst_poster);

                try {
                    // .mp4
                    // audio/mp4, video/mp4
                    $format = new \FFMpeg\Format\Video\X264();
                    $video->save($format, $dst_mp4);
                    //$supplied[] = "mp4";
                    $supplied[] = "m4v";
                } catch (RuntimeException $e) {
                    $t = $e->getPrevious()->getTrace();
                    $t = $t[1];
                    $p = $t['args'][0];
                    $o = $p->getErrorOutput();
                    Log::alert('VIDEO', [$o]);
                }

                try {
                    //.ogg, .ogv, .oga, .ogx, .ogm, .spx, .opus
                    //video/ogg, audio/ogg, application/ogg
                    $format = new \FFMpeg\Format\Video\Ogg();
                    $video->save($format, $dst_ogg);
                    $supplied[] = "ogg";
                } catch (RuntimeException $e) {
                    $t = $e->getPrevious()->getTrace();
                    $t = $t[1];
                    $p = $t['args'][0];
                    $o = $p->getErrorOutput();
                    Log::alert('VIDEO', [$o]);
                }

                try {
                    // .webm
                    // audio/webm, video/webm
                    $format = new \FFMpeg\Format\Video\WebM();
                    $video->save($format, $dst_webm);
                    $supplied[] = "webm";
                } catch (RuntimeException $e) {
                    $t = $e->getPrevious()->getTrace();
                    $t = $t[1];
                    $p = $t['args'][0];
                    $o = $p->getErrorOutput();
                    Log::alert('VIDEO', [$o]);
                }

                //update db
                \DB::table('document_video')
                ->where('id', $imageId)
                ->update([
                        'supplied' => implode(',', $supplied)
                ]);
            }
        });




    // setup dynamic routes
    Route::register();

});


/*
use Kythera\Models\DocumentPerson;
use Kythera\Support\ViewEntity;
$p = DocumentPerson::find(1831);
$v = ViewEntity::build($p, ViewEntity::VIEW_MODE_SIDEBAR);
//echo __FILE__.__LINE__.'<pre>$v='.htmlentities(print_r($v,1)).'</pre>';echo __FILE__.__LINE__.'<pre>$p='.htmlentities(print_r($p,1)).'</pre>';die;
*/
/*
use Kythera\Models\DocumentPerson;
use Kythera\Support\ViewEntity;
$p = DocumentPerson::findByPersonsId(14681);
$p2 = \Kythera\Models\FamilyPerson::getPartners($p->getPersonsId(), true);
echo __FILE__.__LINE__.'<pre>$p='.htmlentities(print_r($p,1)).'</pre>';
echo __FILE__.__LINE__.'<pre>$p2='.htmlentities(print_r($p2,1)).'</pre>';

die;
*/

/*
$getLocale = \App::getLocale();echo __FILE__.__LINE__.'<pre>$getLocale='.htmlentities(print_r($getLocale,1)).'</pre>';

$page = PageEntity::find(1);
//echo __FILE__.__LINE__.'<pre>$page='.htmlentities(print_r($page,1)).'</pre>';die;
$page->title = 'gr_title';
$page->uri = 'gr_uri';
$page->content = 'gr_content';
$page->save();
echo __FILE__.__LINE__.'<pre>$page='.htmlentities(print_r($page,1)).'</pre>';die;
*/


if (php_sapi_name() != 'cli') {
    /*Fix old links*/
    $redirect = (boolean)!stripos($_SERVER['REQUEST_URI'], '/redirect?')!==false;
    PageEntity::convert(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', $redirect);
}



/*
$entry = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
->where('document_attributes.l', App::getLocale())
->where('document_attributes.key', 'title')
->where('document_entities.id', 23409)
->first();
echo __FILE__.__LINE__.'<pre>$entry='.htmlentities(print_r($entry,1)).'</pre>';
$images = $entry->images();
echo __FILE__.__LINE__.'<pre>$images='.htmlentities(print_r($images,1)).'</pre>';
$data = [];
foreach ($images as $image) {
    $uri = sprintf('http://%s/%s%s', $_SERVER['HTTP_HOST'], $image->image_path, $image->image_name);
    $data[] = $uri;
}
echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';die;
*/

/*
 *  3
down vote


The answer by Pouet nearly did it for me, but the following list took it all the way:

    Edit your apt-get source list by adding another source. sudo nano /etc/apt/sources.list Paste in deb http://www.deb-multimedia.org/ wheezy main non-free on a new line. Save and exit.
    sudo apt-get install debian-keyring

    Next, we would perform sudo apt-get update, but it will complain that the public key for the new source is missing. You should probably go ahead and try it before continuing, because I would strongly recommend against blindly installing keys that you don't know what they are just because som random guy (me) told you so. apt-get will complain about key 07DC563D1F41B907 (which has fingerprint 1F41B907) is unknown.

        The actions 4 and 5 need to be properly run as root (sudo bash, and then executing them), simply prepending every command with sudo did not do it for me.

    gpg --keyserver pgp.mit.edu --recv-keys 1F41B907
    gpg --armor --export 1F41B907 | apt-key add -
    sudo apt-get update
    sudo apt-get install libfaac-dev

*/
/*
$data['year_of_birth']='1612';
$data['year_of_birth']='1317-05';
$r = Person::formatPersonDate($data['year_of_birth']);
$queryId = \DB::table('persons')->insertGetId(array(
		'hide'			=> 0,
		'gender'		=> 'M',
		'date_of_birth'	=> $r,
		'date_of_death'	=> '',
		'gedcom_id'     => null
));
echo __FILE__.__LINE__.'<pre>$queryId='.htmlentities(print_r($queryId,1)).'</pre>';die;
*/
Route::get('test_job', 'EntryController_26082020_kiran@test_job');