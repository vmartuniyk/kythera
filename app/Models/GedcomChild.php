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

class GedcomChild extends Model
{

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'gedcom_children';

    /**
     * Returns the GedcomIndividual to which this GedcomChild belongs.
     * @return GedcomIndividual
     */
    public function individual()
    {
        return $this->belongsTo('App\Models\GedcomIndividual', 'indi_id');
    }

    /**
     * Returns the GedcomFamily to which this GedcomChild belongs.
     * @return GedcomFamily
     */
    public function family()
    {
        return $this->belongsTo('App\Models\GedcomFamily', 'fami_id');
    }
}
