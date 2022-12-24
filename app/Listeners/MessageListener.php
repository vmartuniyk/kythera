<?php

namespace App\Listeners;

use App\Events\MessageEvent;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail;
use Event;
use xhtml;
use Kythera\Router\Facades\Router;
use Kythera\Models\DocumentMessage;

class MessageListener implements ShouldQueue
{
    protected $eventName;
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
    public function handleMessageEvent($entity, $reply)
    {
        $entity  = DocumentMessage::user()->find($entity->id);
        $reply   = DocumentMessage::user()->find($reply->id);

        $data = [];
        $data['author']  = trans('locale.submitted', ['fullname' => xhtml::fullname($entity, false), 'date'=>$entity->created_at->format('d/m/Y, H:i')])
          . ' from ' . $this->request->server('REMOTE_ADDR');
        $data['content'] = (string)$reply->content;
        $data['view']    = urldecode(Router::getItemUrl($entity));
        $data['host']    = 'http://' . $this->request->server('HTTP_HOST');

        #send message to author
        $queue_id = Mail::queue('emails.notify.message.author', $data, function ($message) use ($entity, $reply) {
            $message
                ->from(config('app.administrator'), 'kythera-family.net Administrator')
                ->to($entity->email, xhtml::fullname($entity, false))
                ->bcc(config('app.developer'), 'developer')
                ->subject("[KFN] A new messages was added to your entry: ".$entity->title);
        });

        #send message to administrators: handled through entity.* events
        /*
         * Already send through the entity.* events
        switch ($event = Event::firing()) {
            case 'message.created': $subject = 'New message: '; break;
            case 'message.updated': $subject = 'Changed message: '; break;
        }


        $data['edit'] = action('EntryController@edit', $entity->id);
        $data['disable'] = action('EntryController@action', array($entity->id, 'disable'));
        $data['promote'] = '';

        #send message to administrators
        $queue_id = Mail::queue('emails.notify.message.admin', $data, function($message) use ($entity, $subject)
        {
            $message
            ->from(config('app.administrator'), 'kythera-family.net Administrator');

            foreach (config('app.administrators') as $administrator) {
                $message
                ->to($administrator, 'kythera-family.net Administrator');
            }

            $message
            ->bcc(config('app.developer'), 'developer')
            ->subject('[KFN] '.$subject.$entity->title);
        });
        */

        // Log::info(Event::firing(), [$reply->id, $queue_id, config('app.developer')]);
    }


    public function onMessageCreated($entity, $reply) {
        $this->eventName = 'message.created';
        $this->handleMessageEvent($entity, $reply);
    }

    public function onMessageUpdated($entity, $reply) {
        $this->eventName = 'message.updated';
        $this->handleMessageEvent($entity, $reply);
    }

    /**
    * Register the listeners for the subscriber.
    *
    * @param Dispatcher $events
    */
    public function subscribe($events)
    {
        $events->listen(
            'message.created',
            'App\Listeners\MessageListener@onMessageCreated'
        );
        $events->listen(
            'message.updated',
            'App\Listeners\MessageListener@onMessageUpdated'
        );
    }
}
