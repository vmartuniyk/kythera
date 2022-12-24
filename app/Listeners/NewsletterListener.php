<?php

namespace App\Listeners;

use App\Events\NewsletterEvent;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;
use Mail;

class NewsletterListener implements ShouldQueue
{
    protected $request;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Handle Newsletter events.
     */
    public function onNewsletterConfirm($subscriber)
    {
        $data = [];
        $data['firstname'] = $subscriber->firstname;
        $data['lastname']  = $subscriber->lastname;
        $data['url']       = action('NewsLetterController@getConfirm', ['email'=>$subscriber->email, 'token'=>$subscriber->register_token]);
        $data['url2']      = action('NewsLetterController@getUnsubscribe', ['email'=>$subscriber->email, 'token'=>$subscriber->register_token]);
        $data['host']      = 'http://' . $this->request->server('HTTP_HOST');


        #queue
        $queue_id = Mail::queue('emails.notify.confirm', $data, function ($message) use ($subscriber) {
            $message
                ->from(config('app.administrator'), 'kythera-family.net Administrator')
                ->to($subscriber->email, $subscriber->firstname .' '. $subscriber->lastname)
                ->bcc(config('app.developer'), 'developer')
                ->subject("[KFN] Newsletter Subscription");
        });

        // Log::info('newsletter.confirm', [$subscriber->id, $queue_id, config('app.developer')]);
    }

    /**
    * Register the listeners for the subscriber.
    *
    * @param Dispatcher $events
    */
    public function subscribe($events)
    {
        $events->listen(
            'newsletter.confirm',
            'App\Listeners\NewsletterListener@onNewsletterConfirm'
        );
    }
}
