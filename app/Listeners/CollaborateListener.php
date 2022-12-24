<?php

namespace App\Listeners;

use App\Events\CollaborateEvent;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Event;
use Log;
use Mail;
use Kythera\Models\Person;

class CollaborateListener implements ShouldQueue
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
     * Handle Message events.
     */
    public function onCollaborateInvitation($user, $entry, $author, $person)
    {
        #set template vars
        $data = [];
        $data['inviter_name']      = $author->firstname .' '. $author->lastname;
        $data['inviter_email']     = $author->email;

        $data['invitee_name']      = $user->firstname .' '. $user->lastname;
        $data['invitee_email']     = $user->email;

        $data['edit']              = urldecode(action('DocumentPersonController@edit', $entry->id));
        $data['title']             = $title = Person::buildDescription($person);
        $data['host']              = 'http://' . $this->request->server('HTTP_HOST');

        #queue
        $queue_id = Mail::queue('emails.notify.collaborate.invitation', $data, function ($message) use ($user, $author, $title) {
            $message
                ->from(config('app.administrator'), 'kythera-family.net Administrator')
                ->to($user->email, $user->firstname .' '. $user->lastname)
                ->replyTo($author->email, $author->firstname .' '. $author->lastname)
                ->bcc(config('app.developer'), 'developer')
                ->subject("[KFN] Invitation to collaborate on '" .$title . "'");
        });
        Log::info('collaborate.invitation', [$user->id, $author->id, $queue_id, config('app.developer')]);
    }


    public function onCollaborateCancel($user, $entry, $author, $person)
    {
        //
    }

    /**
    * Register the listeners for the subscriber.
    *
    * @param Dispatcher $events
    */
    public function subscribe($events)
    {
        $events->listen(
            'collaborate.invitation',
            'App\Listeners\CollaborateListener@onCollaborateInvitation'
        );
        $events->listen(
            'collaborate.cancel',
            'App\Listeners\CollaborateListener@onCollaborateCancel'
        );
    }
}
