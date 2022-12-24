<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

class GedcomIndividual extends Model
{

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'gedcom_individuals';

    /**
     * Hidden fields in the JSON export.
     * @var array
     */
    protected $hidden = ['id', 'gedcom_id', 'gedcom',
        'created_at', 'updated_at'];

    /**
     * Returns the Gedcom to which this GedcomFamily belongs.
     * @return Gedcom
     */
    public function gc()
    {
        return $this->belongsTo('App\Models\Gedcom', 'gedcom_id');
    }

    /**
     * Returns the GedcomEvents belonging to this GedcomIndividual.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function events()
    {
        return $this->hasMany('App\Models\GedcomEvent', 'indi_id');
    }

    /**
     * Returns the GedcomEvents of a certain type (e.g. BIRT, DEAT).
     * @param string $type
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function eventsByType($type)
    {
        return $this->events()->whereEvent($type);
    }

    /**
     * Returns the GedcomErrors belonging to this GedcomIndividual.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function errors()
    {
        return $this->hasMany('App\Models\GedcomError', 'indi_id');
    }

    /**
     * Returns the GedcomNotes belonging to this GedcomIndividual.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function notes()
    {
        return $this->hasMany('App\Models\GedcomNote', 'indi_id');
    }

    /**
     * Returns the GedcomNotes belonging to this GedcomIndividual via a GedcomEvent.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function eventNotes()
    {
        return $this->hasManyThrough('App\Models\GedcomNote', 'App\Models\GedcomEvent', 'indi_id', 'even_id');
    }

    /**
     * Returns the GedcomSources belonging to this GedcomIndividual.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function sources()
    {
        return $this->hasMany('App\Models\GedcomSource', 'indi_id');
    }

    /**
     * Returns the GedcomSources belonging to this GedcomIndividual via a GedcomEvent.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function eventSources()
    {
        return $this->hasManyThrough('App\Models\GedcomSource', 'App\Models\GedcomEvent', 'indi_id', 'even_id');
    }

    /**
     * Returns the GedcomLifespan for this GedcomIndividual.
     * @return GedcomLifespan
     */
    public function lifespan()
    {
        return $this->hasOne('App\Models\GedcomLifespan', 'indi_id');
    }

    /**
     * Returns the first adoption event for this GedcomIndividual.
     * @return GedcomEvent
     */
    public function birth()
    {
        return $this->eventsByType('BIRT')->first();
    }

    /**
     * Returns the first death event for this GedcomIndividual.
     * @return GedcomEvent
     */
    public function death()
    {
        return $this->eventsByType('DEAT')->first();
    }

    /**
     * Returns the first adoption event for this GedcomIndividual.
     * @return GedcomEvent
     */
    public function isAdopted()
    {
        return $this->eventsByType('ADOP')->first();
    }

    /**
     * Calculates the age of a GedcomIndividual, based on BIRT and DEAT GedcomEvents.
     * @return int
     */
    public function age()
    {
        return DB::table('gedcom_events as e1')
                        ->join('gedcom_events as e2', 'e1.indi_id', '=', 'e2.indi_id')
                        ->where('e1.event', 'BIRT')
                        ->where('e2.event', 'DEAT')
                        ->where('e1.indi_id', $this->id)
                        ->select(DB::raw('YEAR(e2.date) - YEAR(e1.date) - (RIGHT(e1.date, 5) > RIGHT(e2.date, 5)) AS age'))
                        ->pluck('age');
    }

    /**
     * Finds all individuals for the given Gedcom ID and key.
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param int $gedcom_id
     * @param string $gedcom_key
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeGedcomKey($query, $gedcom_id, $gedcom_key)
    {
        return $query->where('gedcom_id', $gedcom_id)->where('gedcom_key', $gedcom_key);
    }

    /**
     * Finds all GedcomIndividuals of a certain sex.
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string $sex
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSex($query, $sex)
    {
        return $query->whereSex($sex);
    }

    /**
     * Returns the GedcomFamilies this GedcomIndividual is a child of.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function families()
    {
        return $this->belongsToMany('App\Models\GedcomFamily', 'gedcom_children', 'indi_id', 'fami_id');
    }

    /**
     * Returns the father of the current GedcomIndividual.
     * TODO: what if we have multiple fathers?
     * @return GedcomIndividual
     */
    public function father()
    {
        $family = $this->families()->first();
        return $family ? $family->husband() : null;
    }

    /**
     * Returns the mother of the current GedcomIndividual.
     * TODO: what if we have multiple mothers?
     * @return GedcomIndividual
     */
    public function mother()
    {
        $family = $this->families()->first();
        return $family ? $family->wife() : null;
    }

    /**
     * Returns the GedcomFamilies this GedcomIndividual is a husband of.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function familiesAsHusband()
    {
        return $this->hasMany('App\Models\GedcomFamily', 'indi_id_husb');
    }

    /**
     * Returns the GedcomFamilies this GedcomIndividual is a wife of.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function familiesAsWife()
    {
        return $this->hasMany('App\Models\GedcomFamily', 'indi_id_wife');
    }
    
    public function getPlace($tag)
    {
        $city    = null;
        $state   = null;
        $country = null;
         
        if ($event = $this->eventsByType($tag)->first()) {
            $items = explode(',', $event->place);
            if (count($items) == 3) {
                $city    = $items[0];
                $state   = $items[1];
                $country = $items[2];
            } else if (count($items) == 2) {
                $city    = $items[0];
                $state   = $items[1];
                $country = null;
            } else if (count($items) == 1) {
                $city    = $items[0];
                $state   = null;
                $country = null;
            }
        }
        
        return ['city'=>trim($city), 'state'=>trim($state), 'country'=>trim($country)];
    }

    public function getNotes()
    {
        $result = '';
        foreach ($this->eventNotes as $note) {
            $result .= $note->note . chr(13) . chr(10);
        }
        return $result;
    }
}
