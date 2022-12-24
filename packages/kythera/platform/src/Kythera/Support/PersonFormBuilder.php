<?php
/**
 * @author virgilm
 *
 */
namespace Kythera\Support;

use Illuminate\Support\Facades\URL;
use Kythera\Models\Person;

class FormBuilderException extends \Exception {};


class FormBuilder
{


	protected $fields = array();


	public function setValues($values) {
		foreach ($this->fields as $i=>$field) {
			if (isset($values->{$field->name})) {
				$value = null;
				switch ($field->type) {
					case FormField::FF_TEXT:
					case FormField::FF_RADIO:
					default:
						$value = (string)$values->{$field->name};
				}
				$this->fields[$i]->value = $value;
			}
		}
	}


	public function convertFields($type) {
		foreach ($this->fields as $i=>$field) {
			switch ($type) {
				case 'p':
				case 'f':
				case 'm':
				case 's':
				case 'c':
				case 'person':
				case 'father':
				case 'mother':
				case 'spouse':
				case 'child':
					$this->fields[$i]->name = sprintf('%s[%s]', $type, $this->fields[$i]->name);
				break;
				case 'F':
					$this->fields[$i]->name = sprintf('%s[%s]', 'father', $this->fields[$i]->name);
				break;	
				case 'M':
					$this->fields[$i]->name = sprintf('%s[%s]', 'mother', $this->fields[$i]->name);
				break;	
			}
		}
	}
}

class FormField
{


	const FF_TEXT = 1;
	const FF_RADIO = 2;
	const FF_DATE_YEAR = 3;
	const FF_FILE = 4;
	const FF_HIDDEN = 5;
	const FF_SELECT = 6;
	const FF_NUMBER = 7;
	const FF_TEXTAREA = 8;


	public $name;
	public $label;
	public $type;
	public $value;
	public $options;


	public function __construct($name, $label = null, $type = FormField::FF_TEXT, $value = null, $options = array())
	{
		$this->name    = $name;
		$this->label   = !$label ? $name : $label;
		$this->type    = $type;
		$this->value   = $value;
		$this->options = $options;
	}
}

class PersonFormBuilder extends FormBuilder
{

	public function build($member = 0, $submit = true)
	{
		$h = '';
		$h.= '
		<style>
		.form-group {margin-bottom:-15px;}
		</style>';

		$this->fields[] = new FormField('member', null, FormField::FF_HIDDEN, $member);

		foreach ($this->fields as $field) {
			switch ($field->type) {
				case FormField::FF_TEXT:
					$h.= sprintf('
					<div class="form-group">
						<label class="control-label">%s</label>
						<input class="form-control" type="text" name="%s" value="%s"/>
					</div>
					', ucfirst($field->label), $field->name, $field->value);
					break;
				case FormField::FF_HIDDEN:
					$h.= sprintf('
					<input type="hidden" name="%s" value="%s"/>
					', $field->name, $field->value);
					break;
				case FormField::FF_FILE:
					if ($field->value) {
						$h.= sprintf('
						<div class="form-group">
							<label class="control-label">%s</label>
							<div class="avatar" style="margin-bottom:10px;"><img class="avatar" src="%s"></div>
							<input type="file" name="%s"/>
						</div>
						', ucfirst($field->label), $field->value, $field->name);
					} else {
						$h.= sprintf('
						<div class="form-group">
							<label class="control-label">%s</label>
							<div class="avatar" style="margin-bottom:10px;"><img class="avatar" src="/assets/img/avatar.png"></div>	
							<input type="file" name="%s"/>
						</div>
						', ucfirst($field->label), $field->name);
					}
					break;
				case FormField::FF_DATE_YEAR:
					$h.= sprintf('
					<div class="form-group">
						<label class="control-label">%s</label>
						<input class="form-control" type="date" name="%s" value="%s"/>
					</div>
					', ucfirst($field->label), $field->name, $field->value);
					break;
				case FormField::FF_NUMBER:
					$h.= sprintf('
					<div class="form-group">
						<label class="control-label">%s</label>
						<input class="form-control" type="number" name="%s" value="%s"/>
					</div>
					', ucfirst($field->label), $field->name, $field->value);
					break;
				case FormField::FF_TEXTAREA:
					$h.= sprintf('
					<div class="form-group">
						<label class="control-label">%s</label>
						<textarea class="form-control" name="%s">%s</textarea>
					</div>
					', ucfirst($field->label), $field->name, $field->value);
					break;
				case FormField::FF_RADIO:
					$h.= sprintf('
					<div class="form-group">
						<label class="control-label">%s</label>
						<div class="radio">', ucfirst($field->label));
					foreach ($field->options as $k=>$option) {
						$h.= sprintf('
						<label>
						<input type="radio" name="%s" value="%s" %s/>
						%s
						</label>', $field->name, $k, ($k==$field->value ? 'checked' : ''), $option);
					}
					$h.='
						</div>
					</div>';
					break;
				case FormField::FF_SELECT:
					$h.= sprintf('
					<div class="form-group">
						<label class="control-label">%s</label>
						<select class="form-control" name="%s" autocomplete="off">
						', ucfirst($field->label), $field->name);
					$h.= sprintf('<option value="0"%s>Choose a person...</option>', is_null($field->value) ? ' selected="selected"' : '');
					//$h.= '<option value="0">Choose a person...</option>';
					foreach ($field->options as $k=>$option) {
						$h.= sprintf('
						<option value="%d"%s>%s</option>
						', $k, ($k && ($k == $field->value) ? ' selected="selected"' : ''), $option);
					}
					$h.='
						</select>
					</div>';
					break;
			}
		}

		if ($submit) {
			$h.=sprintf('
	        <hr class="thin"/>
	        <div class="form-group">
	       	<a class="btn btn-cancel btn-default" href="%s">%s</a>
	   		<button type="submit" class="btn btn-black pull-right">%s</button>
	        </div>',
			URL::previous(),
			trans('locale.button.cancel'),
			trans('locale.button.save'));
		}
		return $h;
	}
}

class PersonForm extends PersonFormBuilder
{

	public function __construct($subject, $type = 'p', $values = null, $parent = null, $persons = null, $others = null)
	{
		/*
		if ($parent) {
			$this->fields[] = new FormField('personsId', null, FormField::FF_TEXT, $parent->id);
		}
		*/
		/*
		if (isset($values->persons_id)) {
			$this->fields[] = new FormField('personsId', null, FormField::FF_TEXT, $values->persons_id);
		}
		*/

		if (!is_null($subject->personsId))
			$this->fields[] = new FormField('personsId', null, FormField::FF_HIDDEN, $subject->personsId);

		if (isset($values->entry_id)) {
			$this->fields[] = new FormField('entryId', null, FormField::FF_HIDDEN, $values->entry_id);
		}

		if ($others) {
			$options = array();
			foreach ($others as $other) {
				//exclude selected parent
				if ($other->persons_id == $subject->id) continue;
				$options[$other->persons_id] = Person::buildDescription($other);
			}
			$this->fields[] = new FormField('parentId', 'Other parent', FormField::FF_SELECT, null, $options);
		}

		if ($persons) {
			$options = array();
			foreach ($persons as $person) {
				$options[$person->persons_id] = Person::buildDescription($person);
			}
			$this->fields[] = new FormField('existingId', 'Existing person', FormField::FF_SELECT, null, $options);
		}

		//main details
		$this->fields[] = new FormField('firstname', 'Firstname');
		$this->fields[] = new FormField('middlename');
		$this->fields[] = new FormField('lastname');
		$this->fields[] = new FormField('nickname');
		if ($values && $values->gender != 'M')
		$this->fields[] = new FormField('maidenname');
		$this->fields[] = new FormField('gender', null, FormField::FF_RADIO, null, array('M'=>'Male', 'F'=>'Female'));

		//birth
		//$this->fields[] = new FormField('year_of_birth', 'Birth year (YYYY)', FormField::FF_NUMBER);
 		$this->fields[] = new FormField('date_of_birth', 'Date of birth (YYYY-MM-DD or YYYY-MM or YYYY)');
 		$this->fields[] = new FormField('country_of_birth', 'Birth country');
 		$this->fields[] = new FormField('state_of_birth', 'Birth state/island');
 		$this->fields[] = new FormField('city_of_birth', 'Birth town/city');
// 		$this->fields[] = new FormField('still_living', null, FormField::FF_RADIO, null, array('yes'=>'yes', 'no'=>'no'));

		//death
 		//$this->fields[] = new FormField('year_of_death', 'Year of death (YYYY)', FormField::FF_NUMBER);
 		$this->fields[] = new FormField('date_of_death', 'Date of death (YYYY-MM-DD or YYYY-MM or YYYY)');
// 		$this->fields[] = new FormField('country_of_death', 'Country of death');
// 		$this->fields[] = new FormField('state_of_death', 'State/Island of death');
// 		$this->fields[] = new FormField('city_of_death', 'Town/City of death');

 		$this->fields[] = new FormField('profession');
 		$this->fields[] = new FormField('religion');
 		$this->fields[] = new FormField('education');

 		$this->fields[] = new FormField('life_story', 'Life story', FormField::FF_TEXTAREA);

 		$this->fields[] = new FormField('photo', null, FormField::FF_FILE, isset($values->avatar) ? $values->avatar : null);

		$this->setValues($values);
		$this->convertFields($type);
	}
}


class FemaleForm extends PersonFormBuilder
{
	public function __construct($subject, $type = 'f', $values = null, $parent = null, $persons = null)
	{
		if (!is_null($subject->personsId))
			$this->fields[] = new FormField('personsId', null, FormField::FF_HIDDEN, $subject->personsId);

		if ($parent) {
            throw new FormBuilderException('depricated');
			$this->fields[] = new FormField('personsId', null, FormField::FF_TEXT, $parent->id);
		}

		if ($persons) {
		    $parents = $subject->getParents();
		    $options = array();
		    $value = null;
		    foreach ($persons as $person) {
		        if ($person->gender!='F') continue;
		        if ($person->persons_id == $subject->id) continue;
		        $options[$person->persons_id] = Person::buildDescription($person);

		        //set selected
		        $key = array_search($person->persons_id, $parents);
		        if ($key !== FALSE)
		            $value = $parents[$key];
		    }
		    $this->fields[] = new FormField('existingId', 'Existing person', FormField::FF_SELECT, null, $options);
		}

		//main details
		$this->fields[] = new FormField('firstname', 'Firstname');
		$this->fields[] = new FormField('middlename');
		$this->fields[] = new FormField('lastname');
		$this->fields[] = new FormField('nickname');
		$this->fields[] = new FormField('maidenname');
		$this->fields[] = new FormField('gender', null, FormField::FF_HIDDEN, 'F', array('M'=>'Male', 'F'=>'Female'));

		//birth
		//$this->fields[] = new FormField('year_of_birth', 'Birth year (YYYY)', FormField::FF_NUMBER);
		$this->fields[] = new FormField('date_of_birth', 'Date of birth (YYYY-MM-DD)');
		$this->fields[] = new FormField('country_of_birth', 'Birth country');
		$this->fields[] = new FormField('state_of_birth', 'Birth state/island');
		$this->fields[] = new FormField('city_of_birth', 'Birth town/city');
		// 		$this->fields[] = new FormField('still_living', null, FormField::FF_RADIO, null, array('yes'=>'yes', 'no'=>'no'));

		//death
		// 		$this->fields[] = new FormField('year_of_death', 'Year of death (YYYY)', FormField::FF_DATE_YEAR);
		// 		$this->fields[] = new FormField('date_of_death', 'Date of death (YYYY-MM-DD)');
		// 		$this->fields[] = new FormField('country_of_death', 'Country of death');
		// 		$this->fields[] = new FormField('state_of_death', 'State/Island of death');
		// 		$this->fields[] = new FormField('city_of_death', 'Town/City of death');

		$this->fields[] = new FormField('profession');
		$this->fields[] = new FormField('religion');
		$this->fields[] = new FormField('education');

		$this->fields[] = new FormField('life_story', 'Life story', FormField::FF_TEXTAREA);
		
		$this->fields[] = new FormField('photo', null, FormField::FF_FILE, isset($values->avatar) ? $values->avatar : null);

		$this->setValues($values);
		$this->convertFields($type);
	}
}

class MaleForm extends PersonFormBuilder
{

	public function __construct($subject, $type = 'm', $values = null, $parent = null, $persons = null)
	{
		if (!is_null($subject->personsId))
			$this->fields[] = new FormField('personsId', null, FormField::FF_HIDDEN, $subject->personsId);

		if ($parent) {
		    throw new FormBuilderException('depricated');
			$this->fields[] = new FormField('personsId', null, FormField::FF_TEXT, $parent->id);
		}

		if ($persons) {
		    $parents = $subject->getParents();
			$options = array();
			$value = null;
			foreach ($persons as $person) {
				//if ($person->gender!=strtoupper($type)) continue;
				if ($type == 'father' && $person->gender!='M') continue;
				if ($type == 'mother' && $person->gender!='F') continue;
				
				if ($person->persons_id == $subject->id) continue;
				$options[$person->persons_id] = Person::buildDescription($person);

				//set selected
				$key = array_search($person->persons_id, $parents);
				if ($key !== FALSE)
				    $value = $parents[$key];
			}
			$this->fields[] = new FormField('existingId', 'Existing person', FormField::FF_SELECT, $value, $options);
		}

		//main details
		$this->fields[] = new FormField('firstname', 'Firstname');
		$this->fields[] = new FormField('middlename');
		$this->fields[] = new FormField('lastname');
		$this->fields[] = new FormField('nickname');
		//$this->fields[] = new FormField('maidenname');
		$this->fields[] = new FormField('gender', null, FormField::FF_HIDDEN, 'M', array('M'=>'Male', 'F'=>'Female'));

		//birth
		$this->fields[] = new FormField('year_of_birth', 'Birth year (YYYY)', FormField::FF_NUMBER);
		// 		$this->fields[] = new FormField('date_of_birth', 'Birth date (YYYY-MM-DD)');
		$this->fields[] = new FormField('country_of_birth', 'Birth country');
		$this->fields[] = new FormField('state_of_birth', 'Birth state/island');
		$this->fields[] = new FormField('city_of_birth', 'Birth town/city');
		// 		$this->fields[] = new FormField('still_living', null, FormField::FF_RADIO, null, array('yes'=>'yes', 'no'=>'no'));

		//death
		// 		$this->fields[] = new FormField('year_of_death', 'Year of death (YYYY)', FormField::FF_DATE_YEAR);
		// 		$this->fields[] = new FormField('date_of_death', 'Date of death (YYYY-MM-DD)');
		// 		$this->fields[] = new FormField('country_of_death', 'Country of death');
		// 		$this->fields[] = new FormField('state_of_death', 'State/Island of death');
		// 		$this->fields[] = new FormField('city_of_death', 'Town/City of death');

		$this->fields[] = new FormField('profession');
		$this->fields[] = new FormField('religion');
		$this->fields[] = new FormField('education');

		//$this->fields[] = new FormField('photo', null, FormField::FF_FILE);
		$this->fields[] = new FormField('life_story', 'Life story', FormField::FF_TEXTAREA);

		$this->fields[] = new FormField('photo', null, FormField::FF_FILE, isset($values->avatar) ? $values->avatar : null);
		
		$this->setValues($values);
		$this->convertFields($type);
	}
}

class PersonFormFactory
{

	public static function create($subject, $type, $values = null, $parent = null, $persons = null)
	{
		switch ($type) {
			case 'person':
				return new PersonForm($subject, 'person', $values);
			break;
			case 'father':
				return new MaleForm($subject, 'father', $values, $parent, $persons);
			break;
			case 'mother':
				return new FemaleForm($subject, 'mother', $values, $parent, $persons);
			break;
			case 'partner':
			case 'spouse':
				return new FemaleForm($subject, 'spouse', $values, $parent, $persons);
			break;
			case 'child':
				return new PersonForm($subject, 'child', $values, null, $persons, $persons);
			break;
			default:
				throw new FormBuilderException('Undefined type: '.$type);
		}
	}

}