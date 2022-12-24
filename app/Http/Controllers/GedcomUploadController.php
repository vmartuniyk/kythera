<?php

namespace App\Http\Controllers;

use App\Models\Gedcom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class GedcomUploadController extends Treechecker\FileUploadsController
{

    /**
     * Uploads the Gedcom to the file server, splits it in chunks for faster processing.
     * @return Response the index page if validation passes, else the upload page.
     */
    public function postUpload()
    {
        foreach (Input::file('uploads') as $file) {
            if ($file->getError() != 0) {
                return Redirect::action('DocumentPersonController@create')->with('error', 'No file was uploaded.');
            }


            $validator = Validator::make(Input::all(), Gedcom::$rules);
            if ($validator->passes()) {
                $orig_file_name = $file->getClientOriginalName();


                // Create a file storage directory based on user id and hex of file name
                $abs_storage_dir = config('app.upload_dir') . '/' . Auth::id() . '/' . bin2hex($orig_file_name);
                $rel_storage_dir = '/' . Auth::id() . '/' . bin2hex($orig_file_name);

                // Check if the storage location already exists, i.e. if same file uploaded before
                if (file_exists($abs_storage_dir)) {
                    return Redirect::action('DocumentPersonController@create')->with('error', 'You already uploaded this file.');
                } else {
                    @mkdir($abs_storage_dir, 0775, true);
                }

                $new_file_name = 'original.ged';
                $file->move($abs_storage_dir, $new_file_name);

                // Split original file into separate files each of approx 10,000 lines
                // Each file starting with a /^0 /, i.e. top-level record
                // Place in storage location numbered consecutively

                chdir($abs_storage_dir);

                // Either use awk or the chunkFile method below
                //exec("awk 'BEGIN{out=1} NR>1 && ++i>10000 && /^0 / {++out; i=0} {print > out}' $new_file_name");
                $this->chunkFile($new_file_name);

                // Save to database
                $gedcom = new Gedcom();
                $gedcom->user_id = Auth::user()->id;
                $gedcom->file_name = $orig_file_name;
                $gedcom->path = $rel_storage_dir;
                $gedcom->tree_name = Input::get('tree_name');
                $gedcom->source = Input::get('source');
                $gedcom->notes = Input::get('notes');
                $gedcom->save();
            } else {
                return Redirect::action('DocumentPersonController@create')->withErrors($validator)->withInput();
            }
        }

        return redirect(sprintf('gc-parse/parse/%d', $gedcom->id));
    }
}
