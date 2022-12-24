<?php

namespace App\Listeners;

use App;
use Event;
use Log;
use Mail;
use xhtml;
use App\Events\CommentEvent;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Kythera\Router\Facades\Router;
use App\Models\Comment;
use Kythera\Models\DocumentEntity;

class CommentListener implements ShouldQueue
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
     * Handle comment events.
     */
    public function handleCommentEvent($comment)
    {
        $original = $comment;
        $comment = Comment::user()->find($comment->id);
        $entry = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.email', 'users.id as user_id')
            ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
            ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
            ->where('document_attributes.l', App::getLocale())
            ->where('document_attributes.key', 'uri')
            ->where('document_entities.id', $comment->document_id)
            ->first();

        $data = [];
        $data['author']  = trans('locale.submitted', ['fullname' => xhtml::fullname($comment, false), 'date'=>$comment->created_at->format('d/m/Y, H:i')])
          . ' from ' . $this->request->server('REMOTE_ADDR');
        $data['content'] = $comment->comment;
        $data['view']    = urldecode(Router::getItemUrl($entry));
        $data['host']    = 'http://' . $this->request->server('HTTP_HOST');

        #send message to author
        $queue_id = Mail::queue('emails.notify.comment.author', $data, function ($message) use ($entry) {
            $message
                ->from(config('app.administrator'), 'kythera-family.net Administrator')
                ->to($entry->email, xhtml::fullname($entry, false))
                ->bcc(config('app.developer'), 'developer')
                ->subject("[KFN] A new comment added to your entry: ".$entry->title);
        });


        switch ($this->eventName) {
            case 'comment.created':
                $subject = 'New comment: ';
                break;
            case 'comment.updated':
                $subject = 'Changed comment: ';
                break;
        }

        $data['edit']    = action('DocumentCommentController@edit', $original->id);
        $data['disable'] = action('DocumentCommentController@enable', [$original->id, 0]);
        $data['promote'] = '';

        #send message to administrators
        $queue_id = Mail::queue('emails.notify.comment.admin', $data, function ($message) use ($entry, $subject) {
            $message
                ->from(config('app.administrator'), 'kythera-family.net Administrator');

            foreach (config('app.administrators') as $administrator) {
                $message
                ->to($administrator, 'kythera-family.net Administrator');
            }

            $message
                ->bcc(config('app.developer'), 'developer')
                ->subject("[KFN] ".$subject.$entry->title);
        });

        Log::info(Event::firing(), [$comment->id, $queue_id, config('app.developer')]);
    }


    public function onCommentCreated($comment) {
        $this->eventName = 'comment.created';
        $this->handleCommentEvent($comment);
    }

    public function onCommentUpdated($comment) {
        $this->eventName = 'comment.updated';
        $this->handleCommentEvent($comment);
    }

    /**
    * Register the listeners for the subscriber.
    *
    * @param Dispatcher $events
    */
    public function subscribe($events)
    {
        $events->listen(
            'comment.created',
            'App\Listeners\CommentListener@onCommentCreated'
        );
        $events->listen(
            'comment.updated',
            'App\Listeners\CommentListener@onCommentUpdated'
        );
    }
}
