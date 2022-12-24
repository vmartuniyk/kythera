<?php

namespace App\Http\Controllers\Treechecker;

use App\Http\Controllers\Controller;
use App\Models\Gedcom;
use App\Models\GedcomFamily;
use App\Models\GedcomIndividual;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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


class GedcomsController extends Controller
{

    //protected $layout = "layouts.main";

    public function __construct()
    {
        //
    }

    /**
     * Show a list of all the gedcoms.
     */
    public function getIndex()
    {
        Session::forget('progress');
        return view('gedcom/gedcoms/index');
        //$this->layout->content = view('gedcom/gedcoms/index');
    }

    /**
     * Show a list of all the unparsed gedcoms.
     */
    public function getUnparsed()
    {
        Session::forget('progress');
        $this->layout->content = view('gedcom/gedcoms/unparsed');
    }

    /*
     * Show a list of all unchecked gedcoms.
     */

    public function getUnchecked()
    {
        Session::forget('progress');
        $this->layout->content = view('gedcom/gedcoms/unchecked');
    }

    /**
     * Shows a single GEDCOM file, with some statistics.
     * @param int $id the Gedcom ID
     */
    public function getShow($id)
    {
        $gedcom = Gedcom::findOrFail($id);

        if ($this->allowedAccess($gedcom->user_id)) {
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

            $avg_fam_size = $this->percentage($sum_fam_size, $num_fams_with_children, 1);

            $statistics = [
                'all_ind' => $all_ind,
                'males' => sprintf('%d (%.2f%%)', $males, $this->percentage($males, $all_ind)),
                'females' => sprintf('%d (%.2f%%)', $females, $this->percentage($females, $all_ind)),
                'unknowns' => $gedcom->individuals()->sex('u')->count(),
                'sex_ratio' => $this->percentage($males, $males + $females, 1),
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
                'cnt_marriage_age_husb' => $gedcom->cntMarriageAge('husb'),
                'avg_marriage_age_husb' => $gedcom->avgMarriageAge('husb'),
                'max_marriage_age_husb' => $gedcom->maxMarriageAge('husb'),
                'min_marriage_age_husb' => $gedcom->minMarriageAge('husb'),
                'cnt_marriage_age_wife' => $gedcom->cntMarriageAge('wife'),
                'avg_marriage_age_wife' => $gedcom->avgMarriageAge('wife'),
                'max_marriage_age_wife' => $gedcom->maxMarriageAge('wife'),
                'min_marriage_age_wife' => $gedcom->minMarriageAge('wife'),
                'all_fami' => $fam_count,
                'fams_with_children' => sprintf('%d (%.2f%%)', $num_fams_with_children, $this->percentage($num_fams_with_children, $fam_count)),
                'avg_fam_size' => $avg_fam_size,
                'max_fam_size' => $max_fam_size,
            ];

            //$this->layout->content = view('gedcom/gedcoms/detail', compact('gedcom', 'statistics'));
            return view('gedcom/gedcoms/detail', compact('gedcom', 'statistics'));
        } else {
            return response('Unauthorized', 401);
        }
    }

    /**
     * Creates the upload form (post is handled in FileUploadsController)
     */
    public function getUpload()
    {
        $this->layout->content = view('gedcom/gedcoms/upload');
    }

    /**
     * Show the form for editing the specified Gedcom.
     * @param int $id
     * @return Response the edit page
     */
    public function getEdit($id)
    {
        $gedcom = Gedcom::findOrFail($id);

        if ($this->allowedAccess($gedcom->user_id)) {
            $this->layout->content = view('gedcom/gedcoms/edit')->with('gedcom', $gedcom);
        } else {
            return response('Unauthorized', 401);
        }
    }

    /**
     * Update the specified Gedcom.
     * @param int $id
     * @return Response the index page if validation passed, else the edit page
     */
    public function postUpdate($id)
    {
        $gedcom = Gedcom::findOrFail($id);

        if ($this->allowedAccess($gedcom->user_id)) {
            $validator = Validator::make(Input::all(), Gedcom::$update_rules);

            if ($validator->passes()) {
                $gedcom->tree_name = Input::get('tree_name');
                $gedcom->source = Input::get('source');
                $gedcom->notes = Input::get('notes');
                $gedcom->save();

                return redirect('gedcoms/index')->with('message', 'The GEDCOM ' . $gedcom->file_name . ' has been updated.');
            } else {
                return redirect('gedcoms/edit/' . $id)->withErrors($validator)->withInput();
            }
        } else {
            return response('Unauthorized', 401);
        }
    }

    /**
     * Show a list of all the GedcomIndividuals for the specified Gedcom.
     */
    public function getIndividuals($id)
    {
        $gedcom = Gedcom::findOrFail($id);

        if ($this->allowedAccess($gedcom->user_id)) {
            $source = 'gedcoms/indidata/' . $id;
            $title = $gedcom->tree_name;
            $subtitle = Lang::get('gedcom/individuals/subtitle.result_one_tree');
            $count = $gedcom->individuals()->count();
            //$this->layout->content = view('gedcom/individuals/index', compact('source', 'title', 'subtitle', 'count'));
            return view('gedcom/individuals/index', compact('source', 'title', 'subtitle', 'count'));
        } else {
            return response('Unauthorized', 401);
        }
    }

    /**
     * Show a list of all the GedcomIndividuals for the specified Gedcom.
     */
    public function getFamilies($id)
    {
        $gedcom = Gedcom::findOrFail($id);

        if ($this->allowedAccess($gedcom->user_id)) {
            $source = 'gedcoms/famidata/' . $id;
            $title = $gedcom->tree_name;
            $subtitle = Lang::get('gedcom/families/subtitle.result_one_tree');
            $count = $gedcom->families()->count();
            //$this->layout->content = view('gedcom/families/index', compact('source', 'title', 'subtitle', 'count'));
            return view('gedcom/families/index', compact('source', 'title', 'subtitle', 'count'));
        } else {
            return response('Unauthorized', 401);
        }
    }

    /**
     * Remove the specified Gedcom (and the files).
     * @param int $id
     * @return the index page with a success message
     */
    public function getDelete($id)
    {
        $gedcom = Gedcom::findOrFail($id);

        if ($this->allowedAccess($gedcom->user_id)) {
            //delete database entries
            $gedcom->delete();

            $user_dir = config('app.upload_dir') . '/' . Auth::id() . '/';
            $files_dir = bin2hex($gedcom->file_name);

            chdir($user_dir);
            $this->removeDir($files_dir);

            return redirect('gedcoms/index')->with('message', 'Gedcom successfully deleted');
        } else {
            return response('Unauthorized', 401);
        }
    }

    public function getHistogram()
    {
        $this->layout->content = view('gedcom/gedcoms/histogram');
    }

    public function getHistodata()
    {
        $header = ['cols' => [
                ['label' => 'Families', 'type' => 'string'],
                ['label' => 'Children', 'type' => 'number'],
        ]];

        // FIXME: do this properly via the model.
        $raw = "select ucount, count(*) as count
        from (select count(children.id) as ucount
        from `families`
        left join `children` on `families`.`id` = `children`.`fami_id`
        where `families`.`gedcom_id` = 11
        group by families.id) as x
        group by x.ucount
        order by x.ucount";

        $results = DB::select(DB::raw($raw));

        $table = [];
        foreach ($results as $result) {
            array_push($table, ['c' => [
                    ['v' => $result->ucount],
                    ['v' => $result->count],
            ]]);
        }

        $rows = ['rows' => $table];

        return Response::json(array_merge($header, $rows));
    }

    /**
     * Show a list of all the gedcoms formatted for Datatables.
     * @return Datatables JSON
     */
    public function getData($parsed = null, $checked = null)
    {
        $user = Auth::user();

        $gedcoms = Gedcom::select(['file_name', 'tree_name', 'source', 'notes', 'parsed', 'id AS gc_id']);
        if (isset($parsed)) {
            $gedcoms->where('parsed', 0);
        }
        if (isset($checked)) {
            $gedcoms->where('error_checked', 0);
        }

        if ($user->role != 'admin') {
            $gedcoms->where('user_id', $user->id);
        }

        return Datatables::of($gedcoms)
                        ->edit_column('file_name', '{{ HTML::link("gedcoms/show/" . $gc_id, $file_name) }}')
                        ->edit_column('notes', '{{{ Str::words($notes, 10) }}}')
                        ->add_column('actions', function ($row) {
                            return $this->actions($row);
                        })
                        ->remove_column('parsed')
                        ->remove_column('gc_id')
                        ->make();
    }

    /**
     * Show a list of all the unparsed gedcoms formatted for Datatables.
     * @return Datatables JSON
     */
    public function getUnparseddata()
    {
        return $this->getData(false);
    }

    /**
     * Show a list of all the unchecked gedcoms formatted for Datatables.
     * @return Datatables JSON
     */
    public function getUncheckeddata()
    {
        return $this->getData(null, false);
    }

    /**
     * Show a list of all the GedcomIndividuals formatted for Datatables.
     * @return Datatables JSON
     */
    public function getIndidata($id)
    {
        $user = Auth::user();

        $individuals = GedcomIndividual::leftJoin('gedcom_gedcoms', 'gedcom_gedcoms.id', '=', 'gedcom_individuals.gedcom_id')
                ->select(['gedcom_individuals.gedcom_key', 'gedcom_individuals.first_name', 'gedcom_individuals.last_name', 'gedcom_individuals.sex', 'gedcom_individuals.id AS in_id']);
        $individuals->where('gedcom_id', $id);
        if ($user->role != 'admin') {
            $individuals->where('gedcom_gedcoms.user_id', $user->id);
        }

        return Datatables::of($individuals)
                        ->edit_column('gedcom_key', '{{ HTML::link("individuals/show/" . $in_id, $gedcom_key) }}')
                        ->remove_column('in_id')
                        ->make();
    }

    /**
     * Show a list of all the GedcomFamilies formatted for Datatables.
     * @return Datatables JSON
     */
    public function getFamidata($id)
    {
        $user = Auth::user();

        $families = GedcomFamily::leftJoin('gedcoms AS g', 'families.gedcom_id', '=', 'g.id')
                ->leftJoin('individuals AS h', 'families.indi_id_husb', '=', 'h.id')
                ->leftJoin('individuals AS w', 'families.indi_id_wife', '=', 'w.id')
                ->select(['g.file_name',
            'families.gedcom_id AS gc_id', 'families.gedcom_key', 'families.id AS fa_id',
            'families.indi_id_husb AS hu_id', 'families.indi_id_wife AS wi_id',
            'h.gedcom_key AS hgk', DB::raw('CONCAT(h.first_name, " ", h.last_name) AS husb_name'),
            'w.gedcom_key AS wgk', DB::raw('CONCAT(w.first_name, " ", w.last_name) AS wife_name')]);
        $families->where('g.id', $id);

        $families->take(100);

        if ($user->role != 'admin') {
            $families->where('g.user_id', $user->id);
        }

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
    }

    /**
     * Returns the actions for a Gedcom data row
     * @param Gedcom $row
     * @return array
     */
    private function actions($row)
    {
        $show = HTML::link('gedcoms/show/' . $row->gc_id, '', [
                    'class' => 'glyphicon glyphicon-zoom-in',
                    'title' => Lang::get('common/actions.show')]);
        $edit = HTML::link('gedcoms/edit/' . $row->gc_id, '', [
                    'class' => 'glyphicon glyphicon-pencil',
                    'title' => Lang::get('common/actions.edit')]);
        $delete = HTML::link('gedcoms/delete/' . $row->gc_id, '', [
                    'class' => 'glyphicon glyphicon-trash',
                    'title' => Lang::get('common/actions.delete')]);
        $p = ends_with($row->file_name, 'json') ? 'json_parse' : 'parse';
        $parse = HTML::link($p . '/parse/' . $row->gc_id, '', [
                    'class' => 'glyphicon glyphicon-save parse',
                    'title' => Lang::get('gedcom/gedcoms/actions.parse')]);
        $errors = HTML::link('errors/gedcom/' . $row->gc_id, '', [
                    'class' => 'glyphicon glyphicon-warning-sign',
                    'title' => Lang::get('gedcom/errors/table.errors')]);
        $export = HTML::link('gedcoms/json/' . $row->gc_id, '', [
                    'class' => 'glyphicon glyphicon-export',
                    'title' => Lang::get('gedcom/gedcoms/actions.export_as_json')]);

        $result = [$edit, $delete, $parse];
        if ($row->parsed) {
            $result = array_merge([$show], $result, [$errors], [$export]);
        }
        return implode(' ', $result);
    }

    /**
     * Returns the division of two values, multiplied by a factor, rounded to two decimals
     * @param int $val1 the numerater
     * @param int $val2 the denominator
     * @param int $multiplier the multiplier (100 for percentages)
     * @return int the result
     */
    private function percentage($val1, $val2, $multiplier = 100)
    {
        if ($val2 == 0) {
            return 0;
        }
        return number_format(($val1 / $val2) * $multiplier, 2);
    }

    /**
     * Removes a directory and its contents, recursively.
     * @param string $directory
     */
    private function removeDir($directory)
    {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) {
                $this->removeDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }

    /**
     * Serializes a Gedcom to JSON
     * @param integer $id
     * @return JSON
     */
    public function getJson($id)
    {
        return Response::json($this->serialize($id));
    }

    /**
     * Serializes a Gedcom to XML
     * @param integer $id
     * @return XML
     */
    public function getXml($id)
    {
        $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><gedcoms></gedcoms>");
        $xml_string = $this->array_to_xml($this->serialize($id)->toArray(), $xml);
        return response($xml_string)->header('Content-Type', 'application/xml');
    }

    /**
     * Serializes a Gedcom
     * @param integer $id
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function serialize($id)
    {
        return Gedcom::where('id', $id)
                        ->with('system')
                        ->with('individuals')
                        ->with('individuals.events')
                        ->with('individuals.events.notes')
                        ->with('individuals.events.notes.sources')
                        ->with('individuals.notes')
                        ->with('individuals.notes.sources')
                        ->with('individuals.sources')
                        ->with('families')
                        ->with('families.events')
                        ->with('families.events.notes')
                        ->with('families.events.notes.sources')
                        ->with('families.notes')
                        ->with('families.notes.sources')
                        ->with('families.sources')
                        ->get();
    }
}
