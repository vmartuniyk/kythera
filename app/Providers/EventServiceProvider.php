<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
      // 'App\Events\EntityEvent' => [
      //   'App\Listeners\EntityListener',
      // ],
      // 'App\Events\CommentEvent' => [
      //   'App\Listeners\CommentListener',
      // ],
      // 'App\Events\MessageEvent' => [
      //   'App\Listeners\MessageListener',
      // ],
      // 'App\Events\NewsletterEvent' => [
      //   'App\Listeners\NewsletterListener',
      // ],
      // 'App\Events\CollaborateEvent' => [
      //   'App\Listeners\CollaborateListener',
      // ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\EntityListener',
        'App\Listeners\CommentListener',
        'App\Listeners\MessageListener',
        'App\Listeners\NewsletterListener',
        'App\Listeners\CollaborateListener',
    ];


    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
