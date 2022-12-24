<?php
namespace Kythera\Models;

use Kythera\Entity\Entity;
use URL;
use DB;

/**
 * @author virgilm
 *
 */
class DocumentGuestbook extends DocumentEntity
{

	protected $user;


	protected $entity_attributes  = array (
		'uri' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
		'title' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
		'content' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
	);


	public static $rules = array(
		'firstname' => 'required',
		'surname' => 'required',
		'email' => 'required|email',
		'city' => 'required',
		'message_content' => 'required|min:5',
	);


	public static $messages = array(
		'g-recaptcha-response' => 'Please verify the Captcha code.'
	);


	public static $rulesContact = array(
		'surname' => 'required',
		'email' => 'required|email',
		'message_content' => 'required|min:5'
	);

	public static $rulesContactnew = array(
		's' => 'required',
		'e' => 'required|email',
		'content' => 'required|min:5'
	);


	public function getUser()
	{
		if (is_null($this->user)) {
			if ($this->persons_id>0) {
				$this->user = \User::find($this->persons_id);
			} else {
				$this->user = \DB::table('users_guestbook')->where('documents_id', $this->id)->first();
			}
		}
		return $this->user;
	}


	public function getUser2()
	{
		$this->user = DB::table('document_entities')->select(
				DB::raw('IF (users.firstname IS NULL, users_guestbook.firstname, users.firstname) AS xfirstname'),
				DB::raw('IF (users.lastname IS NULL, users_guestbook.surname, users.lastname) AS xlastname'),
				DB::raw('IF (users.email IS NULL, users_guestbook.email, users.email) AS xemail'),
				'users_guestbook.city'
				)
				->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
				->leftJoin('users_guestbook', 'document_entities.id', '=', 'users_guestbook.documents_id')
				->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
				->where('document_entities.id', $this->id)
				->first();
		return $this->user;
	}


	public function getEmail()
	{
		$result = false;
		if ($user = $this->getUser2()) {
			if ($user->xemail) {
				$result = mb_strtolower($user->xemail);
			}
		}
		return $result;
	}

	public function getFirstname()
	{
		$result = false;
		if ($user = $this->getUser2()) {
			if ($user->xfirstname) {
				$result = ucfirst($user->xfirstname);
			}
		}
		return $result;
	}

	public function getLastname()
	{
		$result = false;
		if ($user = $this->getUser2()) {
			if ($user->xlastname) {
				$result = ucfirst($user->xlastname);
			}
		}
		return $result;
	}

	public function getCity()
	{
		$result = false;
		if ($user = $this->getUser2()) {
			if ($user->city) {
				$result = ucfirst($user->city);
			}
		}
		return $result;
	}


}
