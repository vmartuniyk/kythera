<?php

namespace App\Http\Controllers\Treechecker;

use App\Models\GedcomChild;
use App\Models\GedcomError;
use App\Models\GedcomEvent;
use App\Models\GedcomFamily;
use App\Models\GedcomGeocode;
use App\Models\GedcomIndividual;
use App\Models\GedcomNote;
use App\Models\GedcomSource;
use App\Models\GedcomSystem;
use Illuminate\Support\Facades\DB;

/*
 * TreeChecker: Error recognition for genealogical trees
 * 
 * Copyright (C) 2014 Digital Humanities Lab, Faculty of Humanities, Universiteit Utrecht
 * Corry Gellatly <corry.gellatly@gmail.com>
 * Martijn van der Klis <M.H.vanderKlis@uu.nl>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class ParseGedcomController extends ParseController
{

    private $noteMap = [];
    private $sourceMap = [];
    private $familyMap = [];


    /**
     * Actions before parsing:
     * - Set some definitions used during parsing.
     * @param int $gedcom_id
     */
    protected function doBeforeParse($gedcom_id)
    {
        if (!defined('WT_GED_ID')) {
            define('WT_GED_ID', $gedcom_id);
            define('WT_REGEX_XREF', '[A-Za-z0-9:_-]+');
            define('WT_REGEX_TAG', '[_A-Z][_A-Z0-9]*');
            define('WT_USER_ACCESS_LEVEL', 0);
            define('WT_WEBTREES', 'webtrees');
            define('WT_UTF8_BOM', "\xEF\xBB\xBF");
            define('WT_UTF8_LRM', "\xE2\x80\x8E");
            define('WT_UTF8_RLM', "\xE2\x80\x8F");
        }
    }
    
    /**
     * Actions after parsing:
     * - Enter place names from Events table into the Geocodes table
     * - Create the families in the familyMap
     * - Add parse errors for non-matched notes in the noteMap
     * - Add parse errors for non-matched sources in the sourceMap
     * @param int $gedcom_id
     */
    protected function doAfterParse($gedcom_id)
    {

        //insert place names and coordinates from the events table into geocodes table
        $eventPlaces = $this->eventPlaces($gedcom_id);

        foreach ($eventPlaces as $eventPlace) {
            $geocode = new GedcomGeocode();
            $geocode->gedcom_id = $eventPlace->gedcom_id;
            $geocode->place = $eventPlace->place;
            $geocode->town = null;
            $geocode->region = null;
            $geocode->country = null;
            $geocode->lati = $eventPlace->latitude;
            $geocode->long = $eventPlace->longitude;
            $geocode->checked = 0;
            $geocode->gedcom = 'See events table';
            $geocode->save();
        }
        
        
        //update the events table geo_id to link to the geocodes table
        //based on unique place, latitude and longitude
        DB::statement("update `gedcom_events` as `e` 
                        inner join `gedcom_geocodes` as `g` 
                        on `e`.`place` = `g`.`place` 
                            set `e`.`geo_id` = `g`.`id`
                        where `e`.`gedcom_id` = $gedcom_id AND `g`.`gedcom_id` = $gedcom_id
                            AND `e`.`place` IS NOT NULL AND `g`.`place` IS NOT NULL
                            AND `e`.`place` != '' AND `g`.`place`  != '' ");
        
        
        // Create the families in the familyMap
        foreach ($this->familyMap as $f => $r) {
            $this->createFamily($f, $r, $gedcom_id);
        }

        // If we reached this point, and there are still notes in the noteMap, add parse errors
        foreach ($this->noteMap as $n => $r) {
            $error = new GedcomError();
            $error->gedcom_id = $gedcom_id;
            $error->stage = 'parsing';
            $error->type_broad = 'missing';
            $error->type_specific = 'note definition';
            $error->eval_broad = 'error';
            $error->eval_specific = '';
            $error->message = sprintf('No definition found for NOTE %s on %s', $n, $r);
            $error->save();
        }

        // If we reached this point, and there are still notes in the sourceMap, add parse errors
        foreach ($this->sourceMap as $s => $r) {
            $error = new GedcomError();
            $error->gedcom_id = $gedcom_id;
            $error->stage = 'parsing';
            $error->type_broad = 'missing';
            $error->type_specific = 'note definition';
            $error->eval_broad = 'error';
            $error->eval_specific = '';
            $error->message = sprintf('No definition found for SOUR %s on %s', $s, $r);
            $error->save();
        }
    }

    /**
     * Split records per 0 line and import.
     * @param integer $gedcom_id
     * @param string $gedcom
     */
    protected function doImport($gedcom_id, $gedcom)
    {
        foreach (preg_split('/(\r|\n)+(?=0)/', $gedcom) as $record) {
            $this->importRecord($record, $gedcom_id);
        }
    }

    /**
     * Import record into database (COPIED/MODIFIED FROM webtrees).
     * This function will parse the given GEDCOM record and add it to the database.
     * @author webtrees
     * @param string $gedcom the raw gedcom record to parse
     * @param int $gedcom_id import the record into this gedcom
     */
    private function importRecord($gedcom, $gedcom_id)
    {
        // Standardise gedcom format
        $gedrec = \Webtrees\Import::reformat_record_import($gedcom);
        // import different types of records

        if (preg_match('/^0 @(' . WT_REGEX_XREF . ')@ (' . WT_REGEX_TAG . ')/', $gedrec, $match)) {
            list(, $xref, $type) = $match;
            // check for a _UID, if the record doesn't have one, add one
            if (!strpos($gedrec, "\n1 _UID ")) {
                $gedrec .= "\n1 _UID " . \Webtrees\Import::uuid();
            }
        } elseif (preg_match('/0 (HEAD|TRLR)/', $gedrec, $match)) {
            $type = $match[1];
            $xref = $type; // For HEAD/TRLR, use type as pseudo XREF.
        } elseif (preg_match('/0 (_PLAC |_PLAC_DEFN)/', $gedrec, $match)) {
            $type = '_PLAC';
            $xref = $type; // Again use type as pseudo XREF.
        } else {
            // If there's no match, add a parsing error and return
            $error = new GedcomError();
            $error->gedcom_id = $gedcom_id;
            $error->stage = 'parsing';
            $error->type_broad = 'standards';
            $error->type_specific = 'non-standard tags';
            $error->eval_broad = 'warning';
            $error->eval_specific = '';
            $error->message = sprintf('Invalid GEDCOM format: %s', $gedrec);
            $error->save();

            return;
        }

        switch ($type) {
            case 'HEAD':
                $this->processHeader($xref, $gedrec, $gedcom_id);
                break;
            case 'INDI':
                $this->processIndividual($xref, $gedrec, $gedcom_id);
                break;
            case 'FAM':
                $this->processFamily($xref, $gedrec, $gedcom_id);
                break;
            case 'NOTE':
                $this->processNote($xref, $gedrec, $gedcom_id);
                break;
            case 'SOUR':
                $this->processSource($xref, $gedrec, $gedcom_id);
                break;
            case '_PLAC':
                $this->processGeocode($gedrec, $gedcom_id);
                break;
            case 'SUBN':
            case 'SUBM': #author
            case 'REPO':
            case 'OBJE': #multi media object
            case 'TRLR': #end file
                break;
            default:
        }
    }

    /**
     * Creates a GedcomSystem.
     * @param string $xref
     * @param string $gedrec
     * @param int $gedcom_id
     */
    private function processHeader($xref, $gedrec, $gedcom_id)
    {
        $record = new WT_GedcomRecord($xref, $gedrec, null, $gedcom_id);
        $source = $record->getFacts('SOUR')[0]->getGedcom();

        $system = new GedcomSystem();
        $system->gedcom_id = $gedcom_id;
        $system->system_id = $this->matchTag($source, 'SOUR');
        $system->version_number = $this->matchTag($source, 'VERS');
        $system->product_name = $this->matchTag($source, 'NAME');
        $system->corporation = $this->matchTag($source, 'CORP');
        $system->gedcom = $gedrec;
        $system->save();
    }

    private function matchTag($source, $tag)
    {
        preg_match('/\d ' . $tag . ' (.*)/', $source, $matches);
        return $matches ? $matches[1] : null;
    }

    /**
     * Creates a GedcomIndividual and possibly GedcomEvents.
     * @param string $xref
     * @param string $gedrec
     * @param int $gedcom_id
     */
    private function processIndividual($xref, $gedrec, $gedcom_id)
    {
        $record = new WT_Individual($xref, $gedrec, null, $gedcom_id);

        $name = $record->getAllNames()[0];
        $givn = utf8_encode(trim($name["givn"]));
        $surname = utf8_encode(trim($name["surname"]));

        $individual = new GedcomIndividual();
        $individual->gedcom_id = $gedcom_id;
        $individual->first_name = $givn;
        $individual->last_name = $surname;
        $individual->sex = strtolower($record->getSex());
        $individual->gedcom_key = $xref;
        $individual->gedcom = $gedrec;
        $individual->private = $this->isPrivate($gedrec);
        $individual->save();

        $this->processEvents($record, $gedcom_id, $individual->id);
    }

    private function isPrivate($gedrec)
    {
        return str_contains($gedrec, '1 RESN privacy') ||
                str_contains($gedrec, '1 RESN confidential');
    }

    /**
     * Creates a GedcomFamily and possibly GedcomChildren and GedcomEvents.
     * @param string $xref
     * @param string $gedrec
     * @param int $gedcom_id
     */
    private function processFamily($xref, $gedrec, $gedcom_id)
    {
        // Find the husband and wife in the Gedcom
        $husb = $this->getIndividualKey($gedrec, 'HUSB');
        $wife = $this->getIndividualKey($gedrec, 'WIFE');

        // Find the husband and wife in the database
        $husb_ind = GedcomIndividual::GedcomKey($gedcom_id, $husb)->first();
        $wife_ind = GedcomIndividual::GedcomKey($gedcom_id, $wife)->first();

        // If we can't find either the husband or the wife,
        // delay family creation until after the whole file has been processed.
        // Families might be stated before individuals (#16)
        if (($husb && !$husb_ind) || ($wife && !$wife_ind)) {
            $this->familyMap[$xref] = $gedrec;
        } // Otherwise, directly create the family.
        else {
            $this->createFamily($xref, $gedrec, $gedcom_id);
        }
    }

    private function createFamily($xref, $gedrec, $gedcom_id)
    {
        $record = new WT_Family($xref, $gedrec, null, $gedcom_id);

        // Find the husband and wife in the Gedcom
        $husb = $this->getIndividualKey($gedrec, 'HUSB');
        $wife = $this->getIndividualKey($gedrec, 'WIFE');
        $husb_ind = $this->retrieveIndividual($gedcom_id, $husb);
        $wife_ind = $this->retrieveIndividual($gedcom_id, $wife);

        // Create the GedcomFamily
        $family = new GedcomFamily();
        $family->gedcom_id = $gedcom_id;
        $family->indi_id_husb = $husb_ind ? $husb_ind->id : null;
        $family->indi_id_wife = $wife_ind ? $wife_ind->id : null;
        $family->gedcom_key = $xref;
        $family->gedcom = $gedrec;
        $family->save();

        // Check the gender of husband and wife
        $this->checkGender($gedcom_id, $family->id, $husb_ind, 'm');
        $this->checkGender($gedcom_id, $family->id, $wife_ind, 'f');

        // Process the GedcomChildren and GedcomEvents
        $this->processChildren($record, $gedcom_id, $family);
        $this->processEvents($record, $gedcom_id, null, $family->id);
    }

    /**
     * Retrieves the key for an individual of a given sex in a family record
     * @param string $gedrec
     * @param string $sex
     * @return string
     */
    private function getIndividualKey($gedrec, $sex)
    {
        if (preg_match('/\n1 ' . $sex . ' @(' . WT_REGEX_XREF . ')@/', $gedrec, $match)) {
            $result = $match[1];
        } else {
            $result = '';
        }
        return $result;
    }

    /**
     * Processes NOTE tags, finds its reference.
     * Creates a GedcomNote if the reference if found, adds a parse error otherwise.
     * @param string $xref
     * @param string $gedrec
     * @param int $gedcom_id
     */
    private function processNote($xref, $gedrec, $gedcom_id)
    {
        $record = new WT_Note($xref, $gedrec, null, $gedcom_id);

        // Find the Note in the noteMap
        if (array_key_exists($xref, $this->noteMap)) {
            $ref = $this->noteMap[$xref];

            // Create the GedcomNote
            $this->createNote($xref, $gedrec, $gedcom_id, $ref, $record->getNote());

            // Remove the key from the noteMap
            unset($this->noteMap[$xref]);
        } // If the Note doesn't exist, add a parse error
        else {
            $error = new GedcomError();
            $error->gedcom_id = $gedcom_id;
            $error->stage = 'parsing';
            $error->type_broad = 'missing';
            $error->type_specific = 'note missing';
            $error->eval_broad = 'error';
            $error->eval_specific = '';
            $error->message = sprintf('No NOTE reference found for %s', $xref);
            $error->save();
        }
    }

    /**
     * Creates a new GedcomNote and finds it's reference(s)
     * @param string $xref
     * @param string $gedrec
     * @param int $gedcom_id
     * @param string $ref
     * @param string $note_text
     */
    private function createNote($xref, $gedrec, $gedcom_id, $ref, $note_text)
    {
        $note = new GedcomNote();
        $note->gedcom_id = $gedcom_id;
        $note->gedcom_key = $xref;
        if (starts_with($ref, 'I')) {
            $note->indi_id = substr($ref, 1);
        } else if (starts_with($ref, 'F')) {
            $note->fami_id = substr($ref, 1);
        } else if (starts_with($ref, 'E')) {
            $note->even_id = substr($ref, 1);
        }
        $note->note = $note_text;
        $note->gedcom = $gedrec;
        $note->save();
    }

    /**
     * Processes SOUR tags, finds its reference.
     * Creates a GedcomSource if the reference if found, adds a parse error otherwise.
     * @param string $xref
     * @param string $gedrec
     * @param int $gedcom_id
     */
    private function processSource($xref, $gedrec, $gedcom_id)
    {
        // Find the Source in the sourceMap
        if (array_key_exists($xref, $this->sourceMap)) {
            $ref = $this->sourceMap[$xref];

            // Create the GedcomSource
            $this->createSource($xref, $gedrec, $gedcom_id, $ref);

            // Remove the key from the sourceMap
            unset($this->sourceMap[$xref]);
        } // If the Source doesn't exist, add a parse error
        else {
            $error = new GedcomError();
            $error->gedcom_id = $gedcom_id;
            $error->stage = 'parsing';
            $error->type_broad = 'missing';
            $error->type_specific = 'source missing';
            $error->eval_broad = 'error';
            $error->eval_specific = '';
            $error->message = sprintf('No SOUR reference found for %s', $xref);
            $error->save();
        }
    }

    /**
     * Creates a new GedcomSource and finds it's reference(s)
     * @param string $xref
     * @param string $gedrec
     * @param int $gedcom_id
     * @param string $ref
     */
    private function createSource($xref, $gedrec, $gedcom_id, $ref)
    {
        $source = new GedcomSource();
        $source->gedcom_id = $gedcom_id;
        $source->gedcom_key = $xref;

        // Set title
        if (preg_match('/\n1 TITL (.+)/', $gedrec, $match)) {
            $source->title = $match[1];
        } else if (preg_match('/\n1 ABBR (.+)/', $gedrec, $match)) {
            $source->title = $match[1];
        } else {
            $source->title = $xref;
        }

        // Set references
        if (starts_with($ref, 'I')) {
            $source->indi_id = substr($ref, 1);
        } else if (starts_with($ref, 'F')) {
            $source->fami_id = substr($ref, 1);
        } else if (starts_with($ref, 'E')) {
            $source->even_id = substr($ref, 1);
        }

        $source->gedcom = $gedrec;
        $source->save();
    }

    /**
     * Create Geocode (place definition) record
     * @param string $gedrec
     * @param int $gedcom_id
     */
    private function processGeocode($gedrec, $gedcom_id)
    {
        //default null values for attributes
        $place = null;
        $latitude = 99.9999999;
        $longitude = 999.9999999;

        if (preg_match('/(?:0 _PLAC) +(.+)/', $gedrec, $match)) {
            //'RootsMagic' and 'Next Generation of Genealogy Sitebuilding'
            //GEDCOM exports have separate place definitions under the _PLAC tag,
            //which may be linked to events via the place name, e.g.
            //0 @I1235@ INDI
            //1 BIRT
            //2 DATE 1689
            //2 PLAC Brögbern
            //
            //0 _PLAC Brögbern
            //1 MAP
            //2 LATI N52,5666667
            //2 LONG E7,3666667

            if ($match[1]) {
                $place = $match[1];
            }

            //Match LATI/LONG in RootsMagic files, which use N,S,W,E in the coordinates
            if (preg_match('/\n2 LATI (N|S)(\d{1,2})(,|.)(\d{1,7})/', $gedrec, $match)) {
                //$match[1] = N or S; $match[2] = degree integer; $match[3] = decimal point/comma
                //$match[4] = numbers after decimal
                //convert to numeric latitude - negative if southern hemisphere
                switch ($match[1]) {
                    case 'N':
                        $latitude = $match[2] . '.' . $match[4];
                        break;
                    case 'S':
                        $latitude = ($match[2] * -1) . '.' . $match[4];
                        break;
                    default:
                        break;
                }
            }

            if (preg_match('/\n2 LONG (W|E)(\d{1,3})(,|.)(\d{1,7})/', $gedrec, $match)) {
                //$match[1] = N or S; $match[2] = degree integer; $match[3] = decimal point/comma
                //$match[4] = numbers after decimal
                //convert to numeric latitude - negative if western hemisphere
                switch ($match[1]) {
                    case 'E':
                        $longitude = $match[2] . '.' . $match[4];
                        break;
                    case 'W':
                        $longitude = ($match[2] * -1) . '.' . $match[4];
                        break;
                    default:
                        break;
                }
            }

            //Match LATI/LONG in 'Next Generation of Genealogy Sitebuilding' files,
            //which do not use N,S,W,E in the coordinates
            if (preg_match('/\n2 LATI (-|)(\d{1,2})(,|.)(\d{1,7})/', $gedrec, $match)) {
                //$match[1] = - or NULL; $match[2] = degree integer; $match[3] = decimal point/comma
                //$match[4] = numbers after decimal

                $latitude = $match[1] . $match[2] . $match[3] . $match[4];
            }

            if (preg_match('/\n2 LONG (-|)(\d{1,3})(,|.)(\d{1,7})/', $gedrec, $match)) {
                //$match[1] = - or NULL; $match[2] = degree integer; $match[3] = decimal point/comma
                //$match[4] = numbers after decimal

                $longitude = $match[1] . $match[2] . $match[3] . $match[4];
            }
        } elseif (preg_match('/0 _PLAC_DEFN/', $gedrec)) {
            //'Legacy' GEDCOM exports program have separate place definitions
            //under the _PLAC_DEFN tag, which again may be linked via the place name, e.g.
            //
            //0 @I3@ INDI
            //1 DEAT
            //2 DATE 14 Apr 1976
            //2 PLAC Hasselt, Limburg, Belgium
            //
            //0 _PLAC_DEFN
            //1 PLAC Hasselt, Limburg, Belgium
            //2 ABBR Hasselt, Limburg, BEL
            //2 MAP
            //3 LATI N51.2
            //3 LONG E5.41666666666667

            if (preg_match('/(?:1 PLAC) +(.+)/', $gedrec, $match)) {
                $place = $match[1];
            }

            //Match LATI/LONG, which use N,S,W,E in the coordinates
            //and may exclude any decimals
            if (preg_match('/\n3 LATI (N|S)(\d{1,2})(.\d{1,7}.*?)?/', $gedrec, $match)) {
                //$match[1] = N or S; $match[2] = degree integer;
                //$match[3] = decimal point and numbers after
                //convert to numeric latitude - negative if southern hemisphere
                switch ($match[1]) {
                    case 'N':
                        $latitude = $match[2] . $this->decimalsExist($match);
                        break;
                    case 'S':
                        $latitude = ($match[2] * -1) . $this->decimalsExist($match);
                        break;
                    default:
                        break;
                }
            }

            if (preg_match('/\n3 LONG (W|E)(\d{1,3})(.\d{1,7}.*?)?/', $gedrec, $match)) {
                //$match[1] = N or S; $match[2] = degree integer;
                //$match[3] = decimal point and numbers after
                //$numbers = $match[3];
                //convert to numeric latitude - negative if western hemisphere
                switch ($match[1]) {
                    case 'E':
                        $longitude = $match[2] . $this->decimalsExist($match);
                        break;
                    case 'W':
                        $longitude = ($match[2] * -1) . $this->decimalsExist($match);
                        break;
                    default:
                        break;
                }
            }
        }
        
        //save to geocodes table
        $geocode = new GedcomGeocode();
        $geocode->gedcom_id = $gedcom_id;
        $geocode->place = utf8_encode($place);
        $geocode->lati = $latitude;
        $geocode->long = $longitude;
        $geocode->checked = 0;
        $geocode->gedcom = $gedrec;
        $geocode->save();
    }

    /*
     * Checks for missing decimal numbers and returns blank string if so.
     * @param array $match
     */

    private function decimalsExist($match)
    {
        if (!array_key_exists(3, $match)) {
            return '';
        } else {
            return $match[3];
        }
    }

    /**
     * Creates the GedcomChildren for a GedcomFamily.
     * @param string $record
     * @param int $gedcom_id
     * @param GedcomFamily $family
     */
    private function processChildren($record, $gedcom_id, $family)
    {
        foreach ($record->getChildren() as $child) {
            // Try to find the individual in the database
            $ind = GedcomIndividual::GedcomKey($gedcom_id, $child)->first();
            if ($ind) {
                // If found, create a GedcomChild
                $child = new GedcomChild();
                $child->gedcom_id = $gedcom_id;
                $child->fami_id = $family->id;
                $child->indi_id = $ind->id;
                $child->save();
            } else {
                // If not found, add a parsing error
                $error = new GedcomError();
                $error->gedcom_id = $gedcom_id;
                $error->fami_id = $family->id;
                $error->stage = 'parsing';
                $error->type_broad = 'data integrity';
                $error->type_specific = 'no @I ref. for child';
                $error->eval_broad = 'error';
                $error->eval_specific = '';
                $error->message = sprintf('No record for individual %s, but listed as a child in family %s.', $child, $family->gedcom_key);
                $error->save();
            }
        }
    }

    /**
     * Create events for either an individual or family record.
     * @param WT_GedcomRecord $record
     * @param int $indi_id
     * @param int $fami_id
     * @return array
     */
    private function processEvents($record, $gedcom_id, $indi_id = null, $fami_id = null)
    {
        foreach ($record->getFacts() as $i => $fact) {
            $tag = $fact->getTag();
            
            $event = null;

            // Retrieve the date and place
            $date = $this->retrieveDate($fact, $gedcom_id, $indi_id, $fami_id);
            $place = $this->retrievePlace($fact);
            $latitude = $this->retrieveLati($fact);
            $longitude = $this->retrieveLong($fact);
            //We skip sources
            //$sources = $fact->getCitations();
            
            //Match LATI/LONG, which use N,S,W,E in the coordinates
            //and may exclude the decimal
            if (preg_match('/(N|S)(\d{1,2})(.\d{1,7}.*?)?/', $latitude, $match)) {
                //$match[1] = N or S; $match[2] = degree integer;
                //$match[3] = decimal point and numbers after
                //convert to numeric latitude - negative if southern hemisphere
                switch ($match[1]) {
                    case 'N':
                        $latitude = $match[2] . $this->decimalsExist($match);
                        break;
                    case 'S':
                        $latitude = ($match[2] * -1) . $this->decimalsExist($match);
                        break;
                    default:
                        break;
                }
            }

            if (preg_match('/(W|E)(\d{1,3})(.\d{1,7}.*?)?/', $longitude, $match)) {
                //$match[1] = N or S; $match[2] = degree integer;
                //$match[3] = decimal point and numbers after
                //convert to numeric latitude - negative if western hemisphere
                switch ($match[1]) {
                    case 'E':
                        $longitude = $match[2] . $this->decimalsExist($match);
                        break;
                    case 'W':
                        $longitude = ($match[2] * -1) . $this->decimalsExist($match);
                        break;
                    default:
                        break;
                }
            }

            // Create the event, except with the following tags:
            // CHAN
            // NEW
            // _UID
            // FAMS
            // FAMC
            // CHIL
            // NAME
            // CREA
            // _FID
            // OBJE
            // HUSB
            // WIFE
            // SEX
            //NOTE
            //SOUR
            if (!in_array($fact->getTag(), ['CHAN', 'NEW', '_UID', 'FAMS', 'FAMC', 'CHIL',
                        'NAME', 'CREA', '_FID', 'OBJE', 'HUSB', 'WIFE', 'SEX', 'NOTE', 'SOUR',])) {
                $time = new DateTime();
                switch ($fact->getTag()) {
                    #parse OCCU tag
                    case 'OCCUx':
                    case 'NICKx':
                        $event = $this->parseEventExtras($fact, $gedcom_id, $indi_id, $fami_id);
                        break;
                    default:
                        $event = [
                                'gedcom_id' => $gedcom_id,
                                'indi_id' => $indi_id,
                                'fami_id' => $fami_id,
                                'event' => $fact->getTag(),
                                'date' => $date ? $date['date'] : null,
                                'est_date' => $date ? $date['est_date'] : null,
                                'datestring' => $date ? $date['string'] : null,
                                'valuestring' => $fact->getValue(),
                                'place' => utf8_encode($place),
                                'lati' => $latitude,
                                'long' => $longitude,
                                'gedcom' => utf8_encode($fact->getGedcom()),
                                'created_at' => $time,
                                'updated_at' => $time,
                        ];
                }
            } else if ($fact->getTag() == 'NOTE') {
                $this->addOrCreateNote($fact, $gedcom_id, $indi_id, $fami_id);
            } else if ($fact->getTag() == 'SOUR') {
                $this->addSource($fact, $indi_id, $fami_id);
            }

            if ($event) {
                // Check if an event exists for this event type
                $this->checkEventExists($event, $gedcom_id, $indi_id, $fami_id);

                // Insert the event into the database
                $event_id = DB::table('gedcom_events')->insertGetId($event);

                // Parse event notes and sources
                $this->parseEventNotes($fact, $gedcom_id, $event_id);
                $this->parseEventSources($fact, $event_id);
            }
        }
    }

    
    /**
     * Extract custom tags OCCU|....
     *
     * @param unknown $fact
     * @param unknown $gedcom_id
     * @param unknown $event_id
     */
    private function parseEventExtras($fact, $gedcom_id, $indi_id, $fami_id)
    {
        $time = new DateTime();
        $event = [
                'gedcom_id' => $gedcom_id,
                'indi_id' => $indi_id,
                'fami_id' => $fami_id,
                'event' => $fact->getTag(),
                //'date' => $date ? $date['date'] : NULL,
                //'est_date' => $date ? $date['est_date'] : NULL,
                //'datestring' => $date ? $date['string'] : NULL,
                //'place' => utf8_encode($place),
                //'lati' => $latitude,
                //'long' => $longitude,
                'gedcom' => utf8_encode($fact->getGedcom()),
                'created_at' => $time,
                'updated_at' => $time,
        ];
        
        // Create a reference for later lookup
        //$ref = 'E' . $event_id;
    
        // Parse values on an event
        preg_match_all('/1 '.$fact->getTag().' (.*)/', $fact->getGedcom(), $matches);
        foreach ($matches[1] as $match) {
            $event['valuestring'] = $match;
        }

        return $event;
    }
    
    
    /**
     * Checks whether an event exists. If so, creates a parse warning.
     * @param GedcomEvent $event
     * @param integer $gedcom_id
     * @param integer $indi_id
     * @param integer $fami_id
     * @return boolean
     */
    private function checkEventExists($event, $gedcom_id, $indi_id = null, $fami_id = null)
    {
        $event_type = $event['event'];

        if ($indi_id) {
            if (GedcomEvent::where('indi_id', $indi_id)->where('event', $event_type)->first()) {
                $i = GedcomIndividual::find($indi_id);

                $error = new GedcomError();
                $error->gedcom_id = $gedcom_id;
                $error->indi_id = $indi_id;
                $error->stage = 'parsing';
                $error->type_broad = 'event';
                $error->type_specific = 'duplicate event';
                $error->eval_broad = 'warning';
                $error->eval_specific = '';
                $error->message = sprintf('Duplicate event of type %s for individual %s', $event_type, $i->gedcom_key);
                $error->save();
            }
        } else if ($fami_id) {
            if (GedcomEvent::where('fami_id', $fami_id)->where('event', $event_type)->first()) {
                $f = GedcomFamily::find($fami_id);

                $error = new GedcomError();
                $error->gedcom_id = $gedcom_id;
                $error->fami_id = $fami_id;
                $error->stage = 'parsing';
                $error->type_broad = 'event';
                $error->type_specific = 'duplicate event';
                $error->eval_broad = 'warning';
                $error->eval_specific = '';
                $error->message = sprintf('Duplicate event of type %s for family %s', $event_type, $f->gedcom_key);
                $error->save();
            }
        }
    }

    /**
     * Either creates a note in the noteMap for later lookup
     * or creates the note directly (for embedded notes)
     * @param WT_Fact $fact
     * @param int $gedcom_id
     * @param int $indi_id
     * @param int $fami_id
     */
    private function addOrCreateNote($fact, $gedcom_id, $indi_id, $fami_id)
    {
        // Create a reference for later lookup
        $ref = $indi_id ? ('I' . $indi_id) : ('F' . $fami_id);

        // If there is a reference to a note; save that in the noteMap
        if (starts_with($fact->getValue(), '@')) {
            $key = trim($fact->getValue(), '@');
            $this->noteMap[$key] = $ref;
        } // If it's an embedded note, save it directly
        else {
            $this->createNote(null, $fact->getGedcom(), $gedcom_id, $ref, $fact->getValue());
        }
    }

    /**
     * Parses embedded and referenced notes on an event.
     * @param WT_Fact $fact
     * @param int $gedcom_id
     * @param int $event_id
     */
    private function parseEventNotes($fact, $gedcom_id, $event_id)
    {
        // Create a reference for later lookup
        $ref = 'E' . $event_id;

        // Parse notes on an event
        preg_match_all('/\n2 NOTE ?(.*(?:\n3.*)*)/', $fact->getGedcom(), $matches);
        foreach ($matches[1] as $match) {
            $note = preg_replace("/\n3 CONT ?/", "\n", $match);
            if (preg_match('/@(' . WT_REGEX_XREF . ')@/', $note, $nmatch)) {
                // If there is a reference to a note; save that in the noteMap
                $this->noteMap[$nmatch[1]] = $ref;
            } else {
                // If it's an embedded note, save it directly
                $this->createNote(null, $match, $gedcom_id, $ref, $note);
            }
        }
    }

    /**
     * Creates a note in the sourceMap for later lookup
     * @param WT_Fact $fact
     * @param int $indi_id
     * @param int $fami_id
     */
    private function addSource($fact, $indi_id, $fami_id)
    {
        // Create a reference for later lookup
        $ref = $indi_id ? ('I' . $indi_id) : ('F' . $fami_id);
        $key = trim($fact->getValue(), '@');
        $this->sourceMap[$key] = $ref;
    }

    /**
     * Parses referenced sources on an event.
     * @param WT_Fact $fact
     * @param int $event_id
     */
    private function parseEventSources($fact, $event_id)
    {
        // Create a reference for later lookup
        $ref = 'E' . $event_id;

        // Parse notes on an event
        preg_match_all('/\n2 SOUR ?(.*(?:\n3.*)*)/', $fact->getGedcom(), $matches);
        foreach ($matches[1] as $match) {
            if (preg_match('/@(' . WT_REGEX_XREF . ')@/', $match, $nmatch)) {
                // If there is a reference to a source; save that in the sourceMap
                $this->sourceMap[$nmatch[1]] = $ref;
            }
        }
    }

    /**
     * Retrieve the date from a fact.
     * @param WT_Fact $fact
     * @param int $gedcom_id
     * @param int $indi_id
     * @param int $fami_id
     * @return array
     */
    private function retrieveDate($fact, $gedcom_id, $indi_id, $fami_id)
    {
        $result = [];
        $date = $fact->getDate();

        //webtrees date processing
        if (get_class($date->date1) != 'NumericGregorianDate') {
            if ($date->isOk()) {
                $result['date'] = implode('-', [$date->date1->y, $date->date1->m, $date->date1->d]);
                $result['string'] = $fact->getAttribute('DATE');
                $result['est_date'] = $date->estimate;
                return $result;
            }
        }
        //additional date processing to deal with purely numeric dates, e.g. 12-03-1786
        if (get_class($date->date1) == 'NumericGregorianDate') {
            if (checkdate($date->date1->m, $date->date1->d, $date->date1->y)) {
                $result['date'] = implode('-', [$date->date1->y, $date->date1->m, $date->date1->d]);
                $result['string'] = $fact->getAttribute('DATE');
                $result['est_date'] = $date->estimate;
                return $result;
            } else {
                $error = new GedcomError();
                $error->gedcom_id = $gedcom_id;
                $error->indi_id = $indi_id;
                $error->fami_id = $fami_id;
                $error->stage = 'parsing';
                $error->type_broad = 'date format';
                $error->type_specific = 'impossible or US format';
                $error->eval_broad = 'error';
                $error->eval_specific = '';
                $error->message = sprintf('Impossible or US formatted date ' . implode('-', [$date->date1->y, $date->date1->m, $date->date1->d]) . '');
                $error->save();
            }
        }
    }

    /**
     * Retrieve the place from a fact
     * @param WT_Fact $fact
     * @return string
     */
    private function retrievePlace($fact)
    {
        $result = $fact->getPlace()->getGedcomName();
        if (empty($result)) {
            $result = null;
        }
        return $result;
    }

    /**
     * Retrieve the latitude from a fact
     * @param WT_Lati $fact
     * @return string
     */
    private function retrieveLati($fact)
    {
        $result = $fact->getLati()->getGedcomName();
        if (empty($result)) {
            $result = null;
        }
        return $result;
    }

    /**
     * Retrieve the longitude from a fact
     * @param WT_Long $fact
     * @return string
     */
    private function retrieveLong($fact)
    {
        $result = $fact->getLong()->getGedcomName();
        if (empty($result)) {
            $result = null;
        }
        return $result;
    }
    
    /**
     * Retrieve the places that exist in the events table, which do not already
     * exist in the geocodes table (via a place definition)
     * @param $gedcom_id
     * @return array
     */
    private function eventPlaces($gedcom_id)
    {
        
        return DB::select("SELECT `e`.`id` AS id, `e`.`gedcom_id` AS gedcom_id, 
                    `e`.`place` AS place, `e`.`lati` AS latitude, 
                    `e`.`long` AS longitude
                    FROM 
                    (SELECT `place` 
                    FROM `gedcom_geocodes` 
                    WHERE `gedcom_id` = $gedcom_id) AS g
                    RIGHT JOIN (SELECT `id`, `gedcom_id`, `place`, `lati`, `long`
                    FROM `gedcom_events` WHERE `gedcom_id` = $gedcom_id
                    GROUP BY `place`) AS e
                    ON `g`.`place` = `e`.`place`
                    WHERE `g`.`place` IS NULL");
    }
    
    /**
     * Checks whether the given husband/wife is actually male/female.
     * If not, a GedcomError will be created.
     * @param int $gedcom_id
     * @param int $family_id
     * @param GedcomIndividual $ind
     * @param string $gender
     */
    private function checkGender($gedcom_id, $family_id, $ind, $gender)
    {
        if ($ind) {
            if (!in_array($ind->sex, ['u', $gender])) {
                $error = new GedcomError();
                $error->gedcom_id = $gedcom_id;
                $error->indi_id = $ind->id;
                $error->fami_id = $family_id;
                $error->stage = 'parsing';
                $error->type_broad = 'gender';
                $error->type_specific = 'parent of other sex';
                $error->eval_broad = 'error';
                $error->eval_specific = '';
                $error->message = sprintf('Individual %s is listed as %s in family record, '
                        . 'but listed as %s in individual record.', $ind->gedcom_key, $gender === 'm' ? 'husband' : 'wife', $ind->sex === 'm' ? 'male' : 'female');
                $error->save();
            }
        }
    }
    
    public static function getTagText($tag)
    {
        $tags['ABBR'] = ['t'=>'ABBREVIATION','l'=>'A short name of a title, description, or name.'];
        $tags['ADDR'] = ['t'=>'ADDRESS','l'=>'The contemporary place, usually required for postal purposes, of an individual, a submitter of information, a repository, a business, a school, or a company.'];
        $tags['ADR1'] = ['t'=>'ADDRESS1','l'=>'The first line of an address.'];
        $tags['ADR2'] = ['t'=>'ADDRESS2','l'=>'The second line of an address.'];
        $tags['ADOP'] = ['t'=>'ADOPTION','l'=>'Pertaining to creation of a child-parent relationship that does not exist biologically.'];
        $tags['AFN'] = ['t'=>'AFN','l'=>'A unique permanent record file number of an individual record stored in Ancestral File.'];
        $tags['AGE'] = ['t'=>'AGE','l'=>'The age of the individual at the time an event occurred, or the age listed in the document.'];
        $tags['AGNC'] = ['t'=>'AGENCY','l'=>'The institution or individual having authority and/or responsibility to manage or govern.'];
        $tags['ALIA'] = ['t'=>'ALIAS','l'=>'An indicator to link different record descriptions of a person who may be the same person.'];
        $tags['ANCE'] = ['t'=>'ANCESTORS','l'=>'Pertaining to forbearers of an individual.'];
        $tags['ANCI'] = ['t'=>'ANCES_INTEREST','l'=>'Indicates an interest in additional research for ancestors of this individual. (See also <a href=DESI>DESI</a>.)'];
        $tags['ANUL'] = ['t'=>'ANNULMENT','l'=>'Declaring a marriage void from the beginning (never existed).'];
        $tags['ASSO'] = ['t'=>'ASSOCIATES','l'=>'An indicator to link friends, neighbors, relatives, or associates of an individual.'];
        $tags['AUTH'] = ['t'=>'AUTHOR','l'=>'The name of the individual who created or compiled information.'];
        $tags['BAPL'] = ['t'=>'BAPTISM-LDS','l'=>'The event of baptism performed at age eight or later by priesthood authority of the LDS Church. (See also <a href=BAPM>BAPM</a>)'];
        $tags['BAPM'] = ['t'=>'BAPTISM','l'=>'The event of baptism (not LDS), performed in infancy or later. (See also <a href=BAPL>BAPL</a>, and <a href=CHR>CHR</a>.)'];
        $tags['BARM'] = ['t'=>'BAR_MITZVAH','l'=>'The ceremonial event held when a Jewish boy reaches age 13.'];
        $tags['BASM'] = ['t'=>'BAS_MITZVAH','l'=>'The ceremonial event held when a Jewish girl reaches age 13, also known as "Bat Mitzvah."'];
        $tags['BIRT'] = ['t'=>'BIRTH','l'=>'The event of entering into life.'];
        $tags['BLES'] = ['t'=>'BLESSING','l'=>'A religious event of bestowing divine care or intercession. Sometimes given in connection with a naming ceremony.'];
        $tags['BLOB'] = ['t'=>'BINARY_OBJECT','l'=>'A grouping of data used as input to a multimedia system that processes binary data to represent images, sound, and video.'];
        $tags['BURI'] = ['t'=>'BURIAL','l'=>'The event of the proper disposing of the mortal remains of a deceased person.'];
        $tags['CALN'] = ['t'=>'CALL_NUMBER','l'=>'The number used by a repository to identify the specific items in its collections.'];
        $tags['CAST'] = ['t'=>'CASTE','l'=>'  The name of an individual\'s rank or status in society, based on racial or religious differences, or differences in wealth, inherited rank, profession, occupation, etc.'];
        $tags['CAUS'] = ['t'=>'CAUSE','l'=>'A description of the cause of the associated event or fact, such as the cause of death.'];
        $tags['CENS'] = ['t'=>'CENSUS','l'=>'The event of the periodic count of the population for a designated locality, such as a national or state Census.'];
        $tags['CHAN'] = ['t'=>'CHANGE','l'=>'Indicates a change, correction, or modification. Typically used in connection with a DATE to specify when a change in information occurred.'];
        $tags['CHAR'] = ['t'=>'CHARACTER','l'=>'An indicator of the character set used in writing this automated information.'];
        $tags['CHIL'] = ['t'=>'CHILD','l'=>'The natural, adopted, or sealed (LDS) child of a father and a mother.'];
        $tags['CHR'] = ['t'=>'CHRISTENING','l'=>'The religious event (not LDS) of baptizing and/or naming a child.'];
        $tags['CHRA'] = ['t'=>'ADULT_CHRISTENING','l'=>'The religious event (not LDS) of baptizing and/or naming an adult person.'];
        $tags['CITY'] = ['t'=>'CITY','l'=>'A lower level jurisdictional unit.  Normally an incorporated municipal unit.'];
        $tags['CONC'] = ['t'=>'CONCATENATION','l'=>'An indicator that additional data belongs to the superior value. The information from the CONC value is to be connected to the value of the superior preceding line without a space and without a carriage return and/or new line character. Values that are split for a CONC tag must always be split at a non-space.  If the value is split on a space the space will be lost when concatenation takes place.  This is because of the treatment that spaces get as a GEDCOM delimiter, many GEDCOM values are trimmed of trailing spaces and some systems look for the first non-space starting after the tag to determine the beginning of the value.'];
        $tags['CONF'] = ['t'=>'CONFIRMATION','l'=>'The religious event (not LDS) of conferring the gift of the Holy Ghost and, among protestants, full church membership.'];
        $tags['CONL'] = ['t'=>'CONFIRMATION_L','l'=>'The religious event by which a person receives membership in the LDS Church.'];
        $tags['CONT'] = ['t'=>'CONTINUED','l'=>'An indicator that additional data belongs to the superior value. The information from the CONT value is to be connected to the value of the superior preceding line with a carriage return and/or new line character. Leading spaces could be important to the formatting of the resultant text. When importing values from CONT lines the reader should assume only one delimiter character following the CONT tag. Assume that the rest of the leading spaces are to be a part of the value.'];
        $tags['COPR'] = ['t'=>'COPYRIGHT','l'=>'A statement that accompanies data to protect it from unlawful duplication and distribution.'];
        $tags['CORP'] = ['t'=>'CORPORATE','l'=>'A name of an institution, agency, corporation, or company.'];
        $tags['CREM'] = ['t'=>'CREMATION','l'=>'Disposal of the remains of a person\'s body by fire.'];
        $tags['CTRY'] = ['t'=>'COUNTRY','l'=>'The name or code of the country.'];
        $tags['DATA'] = ['t'=>'DATA','l'=>'Pertaining to stored automated information.'];
        $tags['DATE'] = ['t'=>'DATE','l'=>'The time of an event in a calendar format.'];
        $tags['DEAT'] = ['t'=>'DEATH','l'=>'The event when mortal life terminates.'];
        $tags['DESC'] = ['t'=>'DESCENDANTS','l'=>'Pertaining to offspring of an individual.'];
        $tags['DESI'] = ['t'=>'DESCENDANT_INT','l'=>'Indicates an interest in research to identify additional descendants of this individual. (See also <a href=ANCI>ANCI</a>.)'];
        $tags['DEST'] = ['t'=>'DESTINATION','l'=>'A system receiving data.'];
        $tags['DIV'] = ['t'=>'DIVORCE','l'=>'An event of dissolving a marriage through civil action.'];
        $tags['DIVF'] = ['t'=>'DIVORCE_FILED','l'=>'An event of filing for a divorce by a spouse.'];
        $tags['DSCR'] = ['t'=>'PHY_DESCRIPTION','l'=>'The physical characteristics of a person, place, or thing.'];
        $tags['EDUC'] = ['t'=>'EDUCATION','l'=>'Indicator of a level of education attained.'];
        $tags['EMIG'] = ['t'=>'EMIGRATION','l'=>'An event of leaving one\'s homeland with the intent of residing elsewhere.'];
        $tags['ENDL'] = ['t'=>'ENDOWMENT','l'=>'A religious event where an endowment ordinance for an individual was performed by priesthood authority in an LDS temple.'];
        $tags['ENGA'] = ['t'=>'ENGAGEMENT','l'=>'An event of recording or announcing an agreement between two people to become married.'];
        $tags['EVEN'] = ['t'=>'EVENT','l'=>'A noteworthy happening related to an individual, a group, or an organization.'];
        $tags['FAM'] = ['t'=>'FAMILY','l'=>'Identifies a legal, common law, or other customary relationship of man and woman and their children, if any, or a family created by virtue of the birth of a child to its biological father and mother.'];
        $tags['FAMC'] = ['t'=>'FAMILY_CHILD','l'=>'Identifies the family in which an individual appears as a child.'];
        $tags['FAMF'] = ['t'=>'FAMILY_FILE','l'=>'Pertaining to, or the name of, a family file. Names stored in a file that are assigned to a family for doing temple ordinance work.'];
        $tags['FAMS'] = ['t'=>'FAMILY_SPOUSE','l'=>'Identifies the family in which an individual appears as a spouse.'];
        $tags['FCOM'] = ['t'=>'FIRST_COMMUNION','l'=>'A religious rite, the first act of sharing in the Lord\'s supper as part of church worship.'];
        $tags['FILE'] = ['t'=>'FILE','l'=>'An information storage place that is ordered and arranged for preservation and reference.'];
        $tags['FORM'] = ['t'=>'FORMAT','l'=>'An assigned name given to a consistent format in which information can be conveyed.'];
        $tags['GEDC'] = ['t'=>'GEDCOM','l'=>'Information about the use of GEDCOM in a transmission.'];
        $tags['GIVN'] = ['t'=>'GIVEN_NAME}</B> A given or earned name used for official identification of a person.'];
        $tags['GRAD'] = ['t'=>'GRADUATION','l'=>'An event of awarding educational diplomas or degrees to individuals.'];
        $tags['HEAD'] = ['t'=>'HEADER','l'=>'Identifies information pertaining to an entire GEDCOM transmission.'];
        $tags['HUSB'] = ['t'=>'HUSBAND','l'=>'An individual in the family role of a married man or father.'];
        $tags['IDNO'] = ['t'=>'IDENT_NUMBER','l'=>'A number assigned to identify a person within some significant external system.'];
        $tags['IMMI'] = ['t'=>'IMMIGRATION','l'=>'An event of entering into a new locality with the intent of residing there.'];
        $tags['INDI'] = ['t'=>'INDIVIDUAL','l'=>'A person.'];
        $tags['LANG'] = ['t'=>'LANGUAGE','l'=>'The name of the language used in a communication or transmission of information.'];
        $tags['LEGA'] = ['t'=>'LEGATEE','l'=>'A role of an individual acting as a person receiving a bequest or legal devise.'];
        $tags['MARB'] = ['t'=>'MARRIAGE_BANN','l'=>'An event of an official public notice given that two people intend to marry.'];
        $tags['MARC'] = ['t'=>'MARR_CONTRACT','l'=>'An event of recording a formal agreement of marriage, including the prenuptial agreement in which marriage partners reach agreement about the property rights of one or both, securing property to their children.'];
        $tags['MARL'] = ['t'=>'MARR_LICENSE','l'=>'An event of obtaining a legal license to marry.'];
        $tags['MARR'] = ['t'=>'MARRIAGE','l'=>'A legal, common-law, or customary event of creating a family unit of a man and a woman as husband and wife.'];
        $tags['MARS'] = ['t'=>'MARR_SETTLEMENT','l'=>'An event of creating an agreement between two people contemplating marriage, at which time they agree to release or modify property rights that would otherwise arise from the marriage.'];
        $tags['MEDI'] = ['t'=>'MEDIA','l'=>'Identifies information about the media or having to do with the medium in which information is stored.'];
        $tags['NAME'] = ['t'=>'NAME','l'=>'A word or combination of words used to help identify an individual, title, or other item. More than one NAME line should be used for people who were known by multiple names.'];
        $tags['NATI'] = ['t'=>'NATIONALITY','l'=>'The national heritage of an individual.'];
        $tags['NATU'] = ['t'=>'NATURALIZATION','l'=>'The event of obtaining citizenship.'];
        $tags['NCHI'] = ['t'=>'CHILDREN_COUNT','l'=>'The number of children that this person is known to be the parent of (all marriages) when subordinate to an individual, or that belong to this family when subordinate to a  FAM_RECORD.'];
        $tags['NICK'] = ['t'=>'NICKNAME','l'=>'A descriptive or familiar that is used instead of, or in addition to, one\'s proper name.'];
        $tags['NMR'] = ['t'=>'MARRIAGE_COUNT','l'=>'The number of times this person has participated in a family as a spouse or parent.'];
        $tags['NOTE'] = ['t'=>'NOTE','l'=>'Additional information provided by the submitter for understanding the enclosing data.'];
        $tags['NPFX'] = ['t'=>'NAME_PREFIX','l'=>'Text which appears on a name line before the given and surname parts of a name.'];
        $tags['NSFX'] = ['t'=>'NAME_SUFFIX','l'=>'Text which appears on a name line after or behind the given and surname parts of a name.'];
        $tags['OBJE'] = ['t'=>'OBJECT','l'=>'Pertaining to a grouping of attributes used in describing something.  Usually referring to the data required to represent a multimedia object, such an audio recording, a photograph of a person, or an image of a document.'];
        $tags['OCCU'] = ['t'=>'OCCUPATION','l'=>'The type of work or profession of an individual.'];
        $tags['ORDI'] = ['t'=>'ORDINANCE','l'=>'Pertaining to a religious ordinance in general.'];
        $tags['ORDN'] = ['t'=>'ORDINATION','l'=>'A religious event of receiving authority to act in religious matters.'];
        $tags['PAGE'] = ['t'=>'PAGE','l'=>'A number or description to identify where information can be found in a referenced work.'];
        $tags['PEDI'] = ['t'=>'PEDIGREE','l'=>'Information pertaining to an individual to parent lineage chart.'];
        $tags['PHON'] = ['t'=>'PHONE','l'=>'A unique number assigned to access a specific telephone.'];
        $tags['PLAC'] = ['t'=>'PLACE','l'=>'A jurisdictional name to identify the place or location of an event.'];
        $tags['POST'] = ['t'=>'POSTAL_CODE','l'=>'A code used by a postal service to identify an area to facilitate mail handling.'];
        $tags['PROB'] = ['t'=>'PROBATE','l'=>'An event of judicial determination of the validity of a will. May indicate several related court activities over several dates.'];
        $tags['PROP'] = ['t'=>'PROPERTY','l'=>'Pertaining to possessions such as real estate or other property of interest.'];
        $tags['PUBL'] = ['t'=>'PUBLICATION','l'=>'Refers to when and/or were a work was published or created.'];
        $tags['QUAY'] = ['t'=>'QUALITY_OF_DATA','l'=>'An assessment of the certainty of the evidence to support the conclusion drawn from evidence.'];
        $tags['REFN'] = ['t'=>'REFERENCE','l'=>'A description or number used to identify an item for filing, storage, or other reference purposes.'];
        $tags['RELA'] = ['t'=>'RELATIONSHIP','l'=>'A relationship value between the indicated contexts.'];
        $tags['RELI'] = ['t'=>'RELIGION','l'=>'A religious denomination to which a person is affiliated or for which a record applies.'];
        $tags['REPO'] = ['t'=>'REPOSITORY','l'=>'An institution or person that has the specified item as part of their collection(s).'];
        $tags['RESI'] = ['t'=>'RESIDENCE','l'=>'The act of dwelling at an address for a period of time.'];
        $tags['RESN'] = ['t'=>'RESTRICTION','l'=>'A processing indicator signifying access to information has been denied or otherwise restricted.'];
        $tags['RETI'] = ['t'=>'RETIREMENT','l'=>'An event of exiting an occupational relationship with an employer after a qualifying time period.'];
        $tags['RFN'] = ['t'=>'REC_FILE_NUMBER','l'=>'A permanent number assigned to a record that uniquely identifies it within a known file.'];
        $tags['RIN'] = ['t'=>'REC_ID_NUMBER','l'=>'A number assigned to a record by an originating automated system that can be used by a receiving system to report results pertaining to that record.'];
        $tags['ROLE'] = ['t'=>'ROLE','l'=>'A name given to a role played by an individual in connection with an event.'];
        $tags['SEX'] = ['t'=>'SEX','l'=>'Indicates the sex of an individual--male or female.'];
        $tags['SLGC'] = ['t'=>'SEALING_CHILD','l'=>'A religious event pertaining to the sealing of a child to his or her parents in an LDS temple ceremony.'];
        $tags['SLGS'] = ['t'=>'SEALING_SPOUSE','l'=>'A religious event pertaining to the sealing of a husband and wife in an LDS temple ceremony.'];
        $tags['SOUR'] = ['t'=>'SOURCE','l'=>'The initial or original material from which information was obtained.'];
        $tags['SPFX'] = ['t'=>'SURN_PREFIX','l'=>'A name piece used as a non-indexing pre-part of a surname.'];
        $tags['SSN'] = ['t'=>'SOC_SEC_NUMBER','l'=>'A number assigned by the United States Social Security Administration. Used for tax identification purposes.'];
        $tags['STAE'] = ['t'=>'STATE','l'=>'A geographical division of a larger jurisdictional area, such as a State within the United States of America.'];
        $tags['STAT'] = ['t'=>'STATUS','l'=>'An assessment of the state or condition of something.'];
        $tags['SUBM'] = ['t'=>'SUBMITTER','l'=>'An individual or organization who contributes genealogical data to a file or transfers it to someone else.'];
        $tags['SUBN'] = ['t'=>'SUBMISSION','l'=>'Pertains to a collection of data issued for processing.'];
        $tags['SURN'] = ['t'=>'SURNAME','l'=>'A family name passed on or used by members of a family.'];
        $tags['TEMP'] = ['t'=>'TEMPLE','l'=>'The name or code that represents the name a temple of the LDS Church.'];
        $tags['TEXT'] = ['t'=>'TEXT','l'=>'The exact wording found in an original source document.'];
        $tags['TIME'] = ['t'=>'TIME','l'=>' A time value in a 24-hour clock format, including hours, minutes, and optional seconds, separated by a colon (;). Fractions of seconds are shown in decimal notation.'];
        $tags['TITL'] = ['t'=>'TITLE','l'=>'A description of a specific writing or other work, such as the title of a book when used in a source context, or a formal designation used by an individual in connection with positions of royalty or other social status, such as Grand Duke.'];
        $tags['TRLR'] = ['t'=>'TRAILER','l'=>'At level 0, specifies the end of a GEDCOM transmission.'];
        $tags['TYPE'] = ['t'=>'TYPE','l'=>'A further qualification to the meaning of the associated superior tag. The value does not have any computer processing reliability. It is more in the form of a short one or two word note that should be displayed any time the associated data is displayed.'];
        $tags['VERS'] = ['t'=>'VERSION','l'=>'Indicates which version of a product, item, or publication is being used or referenced.'];
        $tags['WIFE'] = ['t'=>'WIFE','l'=>' An individual in the role as a mother and/or married woman.'];
        $tags['WILL'] = ['t'=>'WILL','l'=>'A legal document treated as an event, by which a person disposes of his or her estate, to take effect after death. The event date is the date the will was signed while the person was alive. (See also <a href=PROB>PROBate</a>.)'];
        return $tags[$tag];
    }
}
