<?php

namespace App\Http\Controllers;

use App\Models\Gedcom;
use App\Models\GedcomEvent;
use App\Models\GedcomFamily;
use App\Models\GedcomIndividual;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Kythera\Models\Person;
use Kythera\Router\Facades\Router;

/*
SET FOREIGN_KEY_CHECKS=0;SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";SET time_zone = "+00:00";truncate gedcom_children;truncate gedcom_errors;truncate gedcom_events;truncate gedcom_families;truncate gedcom_gedcoms;truncate gedcom_geocodes;truncate gedcom_individuals;truncate gedcom_notes;truncate gedcom_sources;truncate gedcom_stats_lifespans;truncate gedcom_stats_marriages;truncate gedcom_stats_parents;truncate gedcom_systems;SET FOREIGN_KEY_CHECKS=1;
delete from document_entities where document_type_id=63;truncate family;truncate parents;truncate persons;truncate individuum;truncate names;
*/

class GedcomController extends Treechecker\GedcomsController
{


    /**
     * Overridden
     */
    public function getSummary($id)
    {
        $gedcom = Gedcom::findOrFail($id);

        if (!$gedcom->parsed) {
            // TODO: this is a bit too much of course
            abort(403, 'GEDCOM not parsed yet');
        }

            $individuals = $gedcom->individuals();
            $all_ind = $individuals->count();
            $males = $individuals->sex('m')->count();
            $females = $gedcom->individuals()->sex('f')->count();

            $indi_events = $gedcom->individualEvents()->count();
            $fami_events = $gedcom->familyEvents()->count();
            $births = $gedcom->individualEvents()->whereEvent('BIRT');
            $deaths = $gedcom->individualEvents()->whereEvent('DEAT');

            $fam_count = $gedcom->families()->count();

            $max_fam_size = $gedcom->childrenThroughFamily()
            ->groupBy('fami_id')
            ->get(['fami_id', DB::raw('count(*) as count')])
            ->max('count');

            $sum_fam_size = $gedcom->childrenThroughFamily()
            ->groupBy('fami_id')
            ->get(['fami_id', DB::raw('count(*) as count')])
            ->sum('count');

            //this gives number of families with children, whereas fam_count gives all families
            $num_fams_with_children = $gedcom->childrenThroughFamily()
            ->groupBy('fami_id')
            ->get(['fami_id', DB::raw('count(*)')])
            ->count();

            $avg_fam_size = 0;

            $statistics = [
                    'all_ind' => $all_ind,
                    'males' => sprintf('%d', $males),
                    'females' => sprintf('%d', $females),
                    'unknowns' => $gedcom->individuals()->sex('u')->count(),
                    'min_birth' => $births->min('date'),
                    'max_birth' => $births->max('date'),
                    'min_death' => $deaths->min('date'),
                    'max_death' => $deaths->max('date'),
                    'total_events' => $indi_events + $fami_events,
                    'indi_events' => $indi_events,
                    'fami_events' => $fami_events,
                    'avg_age' => number_format($gedcom->avg_lifespan(), 2),
                    'max_age' => $gedcom->max_lifespan(),
                    'min_age' => $gedcom->min_lifespan(),
                    // TODO: recalculate marriage age based on marriage_ages table
                    //'cnt_marriage_age_husb' => $gedcom->cntMarriageAge('husb'),
                    //'avg_marriage_age_husb' => $gedcom->avgMarriageAge('husb'),
                    //'max_marriage_age_husb' => $gedcom->maxMarriageAge('husb'),
                    //'min_marriage_age_husb' => $gedcom->minMarriageAge('husb'),
                    //'cnt_marriage_age_wife' => $gedcom->cntMarriageAge('wife'),
                    //'avg_marriage_age_wife' => $gedcom->avgMarriageAge('wife'),
                    //'max_marriage_age_wife' => $gedcom->maxMarriageAge('wife'),
                    //'min_marriage_age_wife' => $gedcom->minMarriageAge('wife'),
                    'all_fami' => $fam_count,
                    'fams_with_children' => sprintf('%d', $num_fams_with_children),
                    'avg_fam_size' => $avg_fam_size,
                    'max_fam_size' => $max_fam_size,
            ];

            return view('site.document.person.gedcom.summary', compact('gedcom', 'statistics'));
    }


    public function buildIndividuals($gedcomId)
    {
        $user = Auth::user();
        $gedcom = Gedcom::findOrFail($gedcomId);

        $individuals = $gedcom->individuals()->orderBy('gedcom_individuals.last_name')->orderBy('gedcom_individuals.first_name')->get();
        foreach ($individuals as $ind) {
            $events = GedcomEvent::leftJoin('gedcom_gedcoms', 'gedcom_gedcoms.id', '=', 'gedcom_events.gedcom_id')
                ->leftJoin('gedcom_notes', 'gedcom_notes.even_id', '=', 'gedcom_events.id')
                ->select(['gedcom_events.event', 'gedcom_events.date', 'gedcom_events.place', 'gedcom_events.valuestring', 'gedcom_notes.note'])
                ->where('gedcom_events.indi_id', $ind->id)
                ->where('gedcom_gedcoms.user_id', $user->id)
                ->orderBy('gedcom_events.event')
                ->get();

            $ind->events = $events;
        }

        return $individuals;

        /*
		$import = [];

		$h = '';
		$h.= '<table class="table table-striped">';
		foreach ($individuals as $ind) {
			$person = new GedcomPerson($ind);

			$h.=sprintf('<tr valign="top">');
			$h.=sprintf('<td>%s</td>', $ind->first_name);
			$h.=sprintf('<td>%s</td>', $ind->last_name);

			if (count($ind->events)) {
				foreach ($ind->events as $e) {
					$person->addEvent($e);
				}
			} else {
				$h.=sprintf('<td colspan=8>no evenets</td>');
			}
			$h.=sprintf('</tr>');

			$import[$ind->id] = $person;
		}
		$h.= '</table>';

		return $import;
		*/
    }


    /**
     * overridden
     */
    public function getIndidata($id)
    {
        $gedcom = Gedcom::findOrFail($id);
        $user   = Auth::user();

        $individuals = GedcomIndividual::leftJoin('gedcom_gedcoms', 'gedcom_gedcoms.id', '=', 'gedcom_individuals.gedcom_id')
            ->select(['gedcom_individuals.id', 'gedcom_individuals.gedcom_key', 'gedcom_individuals.first_name', 'gedcom_individuals.last_name', 'gedcom_individuals.sex', 'gedcom_individuals.id AS in_id']);
        $individuals
            ->where('gedcom_id', $id);
        $individuals
            ->where('gedcom_gedcoms.user_id', $user->id);
        $individuals
            ->orderBy('gedcom_individuals.last_name');
        $individuals
            ->orderBy('gedcom_individuals.first_name');
        $individuals = $individuals->get();

        return view('site.document.person.gedcom.individuals', compact('gedcom', 'individuals'));
    }


    /**
     * overridden
     */
    public function getFamidata($id)
    {
        $gedcom = Gedcom::findOrFail($id);
        $user   = Auth::user();

        $families = GedcomFamily::leftJoin('gedcom_gedcoms AS g', 'gedcom_families.gedcom_id', '=', 'g.id')
            ->leftJoin('gedcom_individuals AS h', 'gedcom_families.indi_id_husb', '=', 'h.id')
            ->leftJoin('gedcom_individuals AS w', 'gedcom_families.indi_id_wife', '=', 'w.id')
            ->select(['g.file_name',
                    'gedcom_families.gedcom_id AS gc_id', 'gedcom_families.gedcom_key', 'gedcom_families.id AS fa_id',
                    'gedcom_families.indi_id_husb AS hu_id', 'gedcom_families.indi_id_wife AS wi_id',
                    'h.gedcom_key AS hgk', DB::raw('CONCAT(h.last_name, ", ", h.first_name) AS husb_name'),
                    'w.gedcom_key AS wgk', DB::raw('CONCAT(w.last_name, ", ", w.first_name) AS wife_name')]);
        $families
            ->where('g.id', $id);
        $families
            ->where('g.user_id', $user->id);
        $families
            ->orderBy('h.last_name');
        $families
            ->orderBy('h.first_name');
        $items = $families->get();

        $count = $gedcom->families()->count();
        //echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;

        return view('site.document.person.gedcom.families', compact('gedcom', 'count'))->with('families', $items);

        /*
		return Datatables::of($families)
		->edit_column('file_name', '{{ HTML::link("gedcoms/show/" . $gc_id, $file_name) }}')
		->edit_column('gedcom_key', '{{ HTML::link("families/show/" . $fa_id, $gedcom_key) }}')
		->edit_column('hgk', '{{ $hu_id ? HTML::link("individuals/show/" . $hu_id, $hgk) : "" }}')
		->edit_column('wgk', '{{ $wi_id ? HTML::link("individuals/show/" . $wi_id, $wgk) : "" }}')
		->remove_column('fa_id')
		->remove_column('gc_id')
		->remove_column('hu_id')
		->remove_column('wi_id')
		->make();
		*/
    }


    /**
     * Taken from FamilyController->getShow()
     * Taken from FamilyController->getEvents()
     * @param unknown $id
     * @return \Illuminate\Http\Response
     */
    public function getFamily($id)
    {
        $user   = Auth::user();
        $family = GedcomFamily::findOrFail($id);
        //echo __FILE__.__LINE__.'<pre>$family='.htmlentities(print_r($family,1)).'</pre>';die;
        $gedcom = Gedcom::findOrFail($family->gedcom_id);

        $events = GedcomEvent::leftJoin('gedcom_gedcoms', 'gedcom_gedcoms.id', '=', 'gedcom_events.gedcom_id')
                    ->select(['gedcom_events.event', 'gedcom_events.date', 'gedcom_events.place'])
                    ->where('gedcom_events.fami_id', $id)
                    ->where('gedcom_gedcoms.user_id', $user->id);
        $items  = $events->get();

        if ($this->allowedAccess($family->gc->user_id)) {
            $husband = $family->husband;
            $wife = $family->wife;
            //$this->layout->content = view('gedcom/families/detail', compact('family', 'husband', 'wife'));
            return view('site.document.person.gedcom.family', compact('gedcom', 'family', 'husband', 'wife'))->with('events', $items);
        } else {
            return response('Unauthorized', 401);
        }
    }


    /**
     * Taken from IndividualsController->getShow()
     * Taken from IndividualsController->getEvents()
     * @param int $id
     */
    public function getIndividual($id, $gedcom_id = 0)
    {
        $user       = Auth::user();
        $gedcom     = Gedcom::findOrFail($gedcom_id);
        $individual = GedcomIndividual::findOrFail($id);
        $events     = GedcomEvent::leftJoin('gedcom_gedcoms', 'gedcom_gedcoms.id', '=', 'gedcom_events.gedcom_id')
                        ->leftJoin('gedcom_notes', 'gedcom_notes.even_id', '=', 'gedcom_events.id')
                        ->select(['gedcom_events.event', 'gedcom_events.date', 'gedcom_events.place', 'gedcom_events.valuestring', 'gedcom_notes.note'])
                        ->where('gedcom_events.indi_id', $id)
                        ->where('gedcom_gedcoms.user_id', $user->id)
                        ->orderBy('gedcom_events.event');
        $items      = $events->get();

        #get tag notes
        //$notes      = $individual->notes()->get();
        #get tag sources
        //$sources    = $individual->sources()->get();

        #get global notes
        $notes      = $individual->notes()->get();
        #get global sources
        $sources    = $individual->sources()->get();
        /*
		$json = [];
		$h = '<table width=100% border=1>';
		foreach ($items as $event) {
			$tag   = $event->event;
			$place = $event->place;
			$date  = $event->date;
			$note  = $event->note;
			$h.='<tr>';
			$h.=sprintf('<td>%s</td>', $tag);
			$h.=sprintf('<td>%s</td>', $date);
			$h.=sprintf('<td>%s</td>', $place);
			$h.=sprintf('<td>%s</td>', $note);
			$h.='</tr>';

			$json['NOTES']   = $individual->notes()->pluck('note');
			$json['SOURCES'] = $individual->sources()->pluck('title');

			$json[$tag] = array('place'=>utf8_encode($place), 'date'=>$date, 'note'=>utf8_encode($note));
		}
		$h.='</table>';
		echo($h);
		*/
        /*
		foreach ($items as $event) {
			$tag   = $event->event;
			echo __FILE__.__LINE__.'<pre>$tag='.htmlentities(print_r($tag,1)).'</pre>';

			$n = $individual->notes()->get();
			echo __FILE__.__LINE__.'<pre>$n='.htmlentities(print_r($n,1)).'</pre>';
			$s = $individual->sources()->get();
			echo __FILE__.__LINE__.'<pre>$s='.htmlentities(print_r($s,1)).'</pre>';
		}

		die;
		*/

        //$json = json_encode($json);
        //echo __FILE__.__LINE__.'<pre>$json='.htmlentities(print_r($json,1)).'</pre>';die;

        if ($this->allowedAccess($individual->gc->user_id)) {
            return view('site.document.person.gedcom.individual', compact('gedcom', 'individual', 'notes', 'sources'))->with('events', $items);
        } else {
            return response('Unauthorized', 401);
        }
    }


    /**
     * Overridden
     */
    public function postImport($id)
    {
        //ini_set('memory_limit', '2G');
        set_time_limit(0);
        $pn = 0;
        $fn =0;

        $gedcom = Gedcom::findOrFail($id);
        $persons = [];

        #add idividuals
        $individuals = $gedcom->individuals()->get();
        foreach ($individuals as $individual) {
            $entry = Person::setGedcomPerson($individual);
            //echo __FILE__.__LINE__.'<pre>$entryId='.htmlentities(print_r($entryId,1)).'</pre>';
            $persons[$individual->id] = $entry->getPersonsId();
            //http://kfn.laravel.debian.mirror.virtec.org/en
            $entryId = $entry->id;
            //echo "<br><a target='xxx' href='/en/family-trees/{$entryId}'>{$entryId}</a><br>";
        }
        $pn = $individuals->count();
        unset($individuals);
        //echo __FILE__.__LINE__.'<pre>$persons='.htmlentities(print_r($persons,1)).'</pre>';

        #add families
        $families = $gedcom->families()->get();
        //echo __FILE__.__LINE__.'<pre>$families='.htmlentities(print_r($families,1)).'</pre>';die;
        foreach ($families as $family) {
            //echo __FILE__.__LINE__.'<pre>$family='.htmlentities(print_r($family,1)).'</pre>';
            $partner1 = GedcomIndividual::find($family->indi_id_husb);
            $partner2 = GedcomIndividual::find($family->indi_id_wife);
            //echo __FILE__.__LINE__.'<pre>$partner1='.htmlentities(print_r($partner1,1)).'</pre>';
            //echo __FILE__.__LINE__.'<pre>$partner2='.htmlentities(print_r($partner2,1)).'</pre>';

            $person1Id  = @$persons[$partner1->id] ? $persons[$partner1->id] : null;
            $person2Id  = @$persons[$partner2->id] ? $persons[$partner2->id] : null;
            //$person1Id  = $persons[$partner1->id];
            //$person2Id  = $persons[$partner2->id];

            if (!$person1Id && !$person2Id) {
                continue;
            }

            //echo __FILE__.__LINE__.'<pre>$person1Id='.htmlentities(print_r($person1Id,1)).'</pre>';
            //echo __FILE__.__LINE__.'<pre>$person2Id='.htmlentities(print_r($person2Id,1)).'</pre>';

            #add couples (with or without childeren)
            $coupleId = Person::addPartner(['existingId'=>$person1Id, 'personsId'=>$person2Id]);
            //echo __FILE__.__LINE__.'<pre>$coupleId='.htmlentities(print_r($coupleId,1)).'</pre>';

            #add children
            foreach ($family->children as $child) {
                $childId = $persons[$child->id];
                echo __FILE__.__LINE__.'<pre>$childId='.htmlentities(print_r($childId, 1)).'</pre>';
                //$familyId = Person::addChild(array('personsId'=>$person1Id, 'parentId'=>$person2Id, 'existingId'=>$childId));
                $familyId = Person::addChild(['personsId'=>$person2Id, 'parentId'=>$person1Id, 'existingId'=>$childId]);
                echo __FILE__.__LINE__.'<pre>$familyId='.htmlentities(print_r($familyId, 1)).'</pre>';
            }
        }
        $fn = $families->count();
        //die;

        //done redirect to family tree index
        return Redirect::route('site.page.family.trees')
                         ->with('global', sprintf('Successfully imported %d persons and %d families.', $pn, $fn));
    }
}
