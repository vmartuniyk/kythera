<?php

namespace App\Listeners;

use App;
use Event;
use Log;
use Mail;
use xhtml;
use App\Events\EntityEvent;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Kythera\Router\Facades\Router;
use Kythera\Models\HidrateDocument;
use Kythera\Models\DocumentEntity;

class EntityListener implements ShouldQueue
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
     * Handle Entity Create/Update/Delete events.
     */
    public function handleEntityEvent($entity)
    {
        //ES
        switch ($this->eventName) {
            case 'entity.created':
            case 'entity.updated':
                $hd = HidrateDocument::find($entity->id);
                if ($hd->enabled) {
                    $hd->addToindex();
                }
                break;
            case 'entity.deleted':
                try {
                    $hd = HidrateDocument::find($entity->id)->removeFromIndex();
                } catch (\Exception $e) {
                    //if id not found ES throws exception Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
                }
                return false;
            break;
        }

        $data = [];

        //mail
        $entry = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
            ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
            ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
            ->where('document_attributes.l', App::getLocale())
            ->where('document_attributes.key', 'title')
            ->where('document_entities.id', $entity->id)
            ->first();

        if ($images = $entry->images()) {
            if ($image = current($images)) {
                $data['image'] = sprintf('http://%s/%s%s', $this->request->server('HTTP_HOST'), $image->image_path, $image->image_name);
            }
        }

        switch ($this->eventName) {
            case 'entity.created':
                $subject = 'New Entry: ';
                break;
            case 'entity.updated':
                $subject = 'Changed Entry: ';
                break;
            case 'entity.deleted':
                $subject = 'Deleted Entry: ';
                break;
        }

        $data['author'] = ucfirst(trans('locale.submitted', [
          'fullname' => xhtml::fullname($entry, false),
          'date'     => $entry->updated_at->format('d/m/Y, H:i')
        ]) . ' from ' . $this->request->server('REMOTE_ADDR'));
        $data['title'] = (string)$entity->title;
        $data['content'] = (string)$entity->content;
        $data['view'] = urldecode(Router::getItemUrl($entity));

        $data['edit'] = action('EntryController@edit', $entity->id);
        $data['disable'] = action('EntryController@action', [$entity->id, 'disable']);
        $data['promote'] = action('EntryController@action', [$entity->id, 'promote']);
        $data['facebook'] = action('EntryController@action', [$entity->id, 'facebook']);
        $data['host'] = 'http://' . $this->request->server('HTTP_HOST');
        $data['server'] = $this->request->server();

        #send message to administrators
        $queue_id = Mail::send('emails.notify.document', $data, function ($message) use ($entity, $subject) {

            $message->from(config('app.administrator'), 'kythera-family.net administrator');

            foreach (config('app.administrators') as $administrator) {
                // $this->check($administrator);
                $message->to($administrator, 'kythera-family.net administrator');

                // Log::info($this->eventName, [$entity->id, $administrator]);
            }

            $message->bcc(config('app.developer'), 'developer')
                    ->subject('[KFN] '.$subject.$entity->title);
        });
    }
    // public function check($msg){
    //     $today = date('Y-m-d H:m:s');
    //     error_log( $msg . "\r\n", 3, '/var/www/vhosts/kythera-family.net/httpdocs/check/'. $today . '.log');
    // }

    public function onEntityCreated($entity) {
        $this->eventName = 'entity.created';
        $this->handleEntityEvent($entity);
    }

    public function onEntityUpdated($entity) {
        $this->eventName = 'entity.updated';
        $this->handleEntityEvent($entity);
    }

    public function onEntityDeleted($entity) {
        $this->eventName = 'entity.deleted';
        $this->handleEntityEvent($entity);
    }

    /**
    * Register the listeners for the subscriber.
    *
    * @param Dispatcher $events
    */
    public function subscribe($events)
    {
      $events->listen(
          'entity.created',
          'App\Listeners\EntityListener@onEntityCreated'
      );
      $events->listen(
          'entity.updated',
          'App\Listeners\EntityListener@onEntityUpdated'
      );
        $events->listen(
            'entity.deleted',
            'App\Listeners\EntityListener@onEntityDeleted'
        );
    }
}
