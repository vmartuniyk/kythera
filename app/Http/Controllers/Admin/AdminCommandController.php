<?php

namespace App\Http\Controllers\Admin;

use Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Kythera\Models\PageEntity;

/*
 * Ajax call wrapper from admin
 *
 */

/**
 *
 * @author virgilm
 *
 */
class AdminCommandController extends AdminController
{

    /**
     *
     * @var version
     */
    const VERSION = 0.1;

    /**
     * Registered ajax calls and model mappings
     *
     * @var array
     */
    private $commands =  [
        'ToggleActive' => 'Kythera\Models\PageEntity',
        'FolderState' => 'Kythera\Models\PageEntity',
        'OrderPages' => 'Kythera\Models\PageEntity'
    ];

    /**
     * Admin GET command requests
     */
    public function getIndex()
    {
        // just return version number
        return Response::json(AdminCommandController::VERSION);
    }

    /**
     * Admin POST command requests
     */
    public function postIndex()
    {
        if (Request::ajax()) {
            $result = new \stdClass();
            $result->result = false;
            $result->reload = false; // Page reload required?
            $result->message = ''; // Set message or error explanation
            try {
                if ($request = Input::get('request')) {
                    // Check if command is registered and has model assigned.
                    if (array_key_exists($request['c'], $this->commands)) {
                        $model = $this->commands[$request['c']];
                        // Command is case IN sensitive.. cmdToggle cmdtoggle both work?!
                        $call = 'cmd'.$request ['c'];
                        $result = forward_static_call_array([
                            $model,
                            $call
                        ], $request['p']);
                    } else {
                        throw new Exception('Command not implemented: ' . $request['c']);
                    }
                }
            } catch (Exception $e) {
                $result->message = $e->getMessage();
            }

            if (!$result->result) {
                $result->reload = true;
            }

            return Response::json($result);
        }
    }

    /**
     * Helper function to generate HTML for ajax commands
     *
     * @param string $command
     * @param array $parameters
     * @param boolean $full:
     *          produce full command or just json string
     * @return string
     *
     * @example 'cms.command({"c":"togglePageActive","p":{"id":%d}})'
     */
    public static function command($command, array $parameters = [], $full = false)
    {
        $request =  [
            'c' => $command,
            'p' => $parameters
        ];
        $request = json_encode($request);
        if ($full) {
            $request = sprintf("'cms.command(jQuery(this), %s); return false;'", $request);
        }
        return $request;
    }
}
