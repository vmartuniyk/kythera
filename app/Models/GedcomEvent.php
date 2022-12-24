<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

class GedcomEvent extends Model
{

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'gedcom_events';

    /**
     * Hidden fields in the JSON export.
     * @var array
     */
    protected $hidden = ['id', 'gedcom_id', 'gedcom',
        'indi_id', 'fami_id',
        'created_at', 'updated_at'];

    /**
     * Returns the GedcomIndividual to which this GedcomEvent belongs.
     * @return GedcomIndividual
     */
    public function individual()
    {
        return $this->belongsTo('App\Models\GedcomIndividual', 'indi_id');
    }

    /**
     * Returns the GedcomFamily to which this GedcomEvent belongs.
     * @return GedcomFamily
     */
    public function family()
    {
        return $this->belongsTo('App\Models\GedcomFamily', 'fami_id');
    }

    /**
     * Returns the GedcomNotes belonging to this GedcomEvent.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function notes()
    {
        return $this->hasMany('App\Models\GedcomNote', 'even_id');
    }

    /**
     * Returns the GedcomSources belonging to this GedcomEvent.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function sources()
    {
        return $this->hasMany('App\Models\GedcomSource', 'even_id');
    }
    
    /**
     * Returns the GedcomGeocode to which this GedcomEvent belongs.
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function geocode()
    {
        return $this->belongsTo('App\Models\GedcomGeocode', 'geo_id');
    }
    
    public function getEventValue()
    {
        switch ($this->event) {
            case 'BIRT':
            case 'DEAT':
                return $this->formatEvent($this);
            break;
            case 'OCCU':
            case 'NICK':
                return $this->formatExtras($this);
            break;
        }
    }
    
    private function formatEvent($event)
    {
        if ($event['date'] && $event['place']) {
            return sprintf('%s, %s', $event['date'], $event['place']);
        } else if ($event['date'] && !$event['place']) {
            return sprintf('%s', $event['date']);
        } else if (!$event['date'] && !$event['place']) {
            return sprintf('%s', $event['valuestring']);
        }
    }
    
    private function formatExtras($event)
    {
        return sprintf('%s', $event['valuestring']);
        if ($event['note']) {
            return sprintf('%s (%s)', $event['valuestring'], $event['note']);
        } else {
            return sprintf('%s', $event['valuestring']);
        }
    }
}
