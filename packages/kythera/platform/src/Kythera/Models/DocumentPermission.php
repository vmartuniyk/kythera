<?php
namespace Kythera\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use \Carbon\Carbon;
use Kythera\Models;
use Illuminate\Support\Facades\Config;

class DocumentPermissionException extends \Exception {}

class DocumentPermission extends Eloquent
{

    public $timestamps = false;


    protected $table = "document_permissions";
    
    
    protected $fillable = array('document_entity', 'user_id');


    /**
     * Check if user has permission on entry
     * 
     * @param unknown $entryId
     * @param unknown $userId
     * @return boolean
     */
    public static function can($entryId, $userId)
    {
    	return is_object(self::query()->select('*')->where('document_entity', $entryId)->where('user_id', $userId)->first());
    }

    
    /**
     * Add permission to access entry by user
     * @param unknown $entryId
     * @param unknown $userId
     */
    public static function add($entryId, $userId)
    {
    	return DB::statement(DB::raw(sprintf('
            REPLACE INTO
                document_permissions
            SET
                document_entity = %d,
    			user_id = %d;
            ', $entryId, $userId)));
    }
    
    
    /**
     * Remove permission to access entry by user
     * @param unknown $entryId
     * @param unknown $userId
     */
    public static function remove($entryId, $userId)
    {
    	return self::where('document_entity', $entryId)->where('user_id', $userId)->delete();
    }

}