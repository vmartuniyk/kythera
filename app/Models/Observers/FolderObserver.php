<?php

namespace App\Models\Observers;

use Barryvdh\Debugbar\Facade;

class FolderObserver
{

    /*				'creating', 'created', 'updating', 'updated',
				'deleting', 'deleted', 'saving', 'saved',
				'restoring', 'restored',*/
    
    public function saved($model)
    {
        Debugbar::info('saved');
    }

    
    public function created($model)
    {
        Debugbar::info('created');
    }
}
