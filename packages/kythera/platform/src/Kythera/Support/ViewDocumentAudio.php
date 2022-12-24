<?php

namespace Kythera\Support;

use Illuminate\Support\Str;


class ViewDocumentAudio extends ViewDocumentEntity
{

    protected $audio_data;

	public function __construct($item, $mode = ViewEntity::VIEW_MODE_NORMAL)
	{
		parent::__construct($item, $mode);

		$this->audio_data = $this->getAudioData();

		switch($mode)
		{
    		case ViewEntity::VIEW_MODE_HOME:
    		    //342x217, 108x69
    		    $data            = $this->getImageFromContent(342, 217, $item->content);
				    $this->image     = $data->image;
    		    $this->content   = Str::words(e(strip_tags($data->content)), 20);
    		    $this->audio     = $this->getAudio($item);
    		    $this->copyright = $this->getCopyright();
		    break;
		    case ViewEntity::VIEW_MODE_SIDEBAR:
				$data            = $this->getImageFromContent(165, 105, $item->content);
				$this->image     = $data->image;
				$this->content   = Str::words(e(strip_tags($data->content)), 20);
				$this->audio     = $this->getAudio($item);
				$this->copyright = $this->getCopyright();
			break;
			case ViewEntity::VIEW_MODE_VIEW:
			default:
				$this->content   = $this->getImages($this->content);
				$this->audio     = $this->getAudio($item);
				$this->copyright = $this->getCopyright();
		}
	}


	private function getAudioData()
	{
	    return \DB::table('document_audio')
        	        ->where('entry_id', $this->id)
        	        ->first();
	}

	private function getAudio($item)
	{
	    if ($this->audio_data) {
	        return route('audio.view', ['path'=>preg_replace("/[^0-9]/","",$this->audio_data->audio_path), 'name' => $this->audio_data->audio_name]);
	    }
	    return false;
	}


	protected function getCopyright()
	{
	    $result = '';
	    if ($this->audio_data) {
	        if ($this->item->copyright && $this->audio_data->recorded) {
	            $year = substr($this->audio_data->recorded, 0, 4);
	            $result = sprintf('Copyright (%s) %s', $year, ucwords($this->item->copyright));
	        } else
	            if ($this->item->copyright) {
	                $result = sprintf('Copyright %s', ucwords($this->item->copyright));
	            } else
	                if ($this->audio_data->recorded) {
	                    $year = substr($this->audio_data->recorded, 0, 4);
	                    $result = sprintf('Copyright (%s)', $year);
	                } else {
	                    $result = sprintf('Copyright');
	                }
	    }
	    return $result;
	}

}
