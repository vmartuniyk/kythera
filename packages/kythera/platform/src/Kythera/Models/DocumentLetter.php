<?php
namespace Kythera\Models;

use Kythera\Entity\Entity;
use Illuminate\Database\Eloquent\Builder;
use URL;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

/**
 * @author virgilm
 *
 */
class DocumentLetter extends DocumentImage {

	public function getInfo() {
		$result = false;
		if ($item = DB::select(DB::raw(sprintf('
			SELECT
				CONCAT(sender.firstname, " ", sender.lastname) as sender,
				sender.character_set_id as senderCharacterSetId,
				CONCAT(addressee.firstname, " ", addressee.lastname) as addressee,
				addressee.character_set_id as addresseeCharacterSetId,
				year,
				DATE_FORMAT(date, "%%d.%%m.%%Y") as date
			FROM
				letters, names as sender, names as addressee
			WHERE
				letters.sender_id = sender.persons_id AND
				letters.addressee_id = addressee.persons_id AND
				letters.document_id = %d', $this->id)))) {
				$result = $item[0];
		}
		return $result;
	}

}