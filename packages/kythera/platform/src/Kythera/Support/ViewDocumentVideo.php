<?php

namespace Kythera\Support;

use Illuminate\Support\Str;

class ViewDocumentVideo extends ViewDocumentEntity
{
    protected $video_data;
    protected $original = false;

    public function __construct($item, $mode = ViewEntity::VIEW_MODE_NORMAL)
    {
        parent::__construct($item, $mode);

        $this->video_data = $this->getVideoData();

        switch($mode)
        {
            /*
            case ViewEntity::VIEW_MODE_HOME:
                //342x217, 108x69
                $data            = $this->getImageFromContent(342, 217, $item->content);
                $this->image     = $data->image;
                $this->content   = Str::words(e(strip_tags($data->content)), 20);
                $this->video     = $this->getVideo($item);
                $this->copyright = $this->getCopyright();
                break;
                */
            case ViewEntity::VIEW_MODE_LIST:
                //194x145
                $this->image     = $this->getImageCache(194, 145);
                $this->copyright = $this->getCopyright();
            break;
            case ViewEntity::VIEW_MODE_SIDEBAR:
                $data            = $this->getImageFromContent(165, 105, $item->content);
                $this->image     = $this->getImageCache(165, 105);
                $this->content   = Str::words(e(strip_tags($data->content)), 20);
                $this->video     = $this->getVideo($item);
                $this->copyright = $this->getCopyright();
                break;
            case ViewEntity::VIEW_MODE_VIEW:
            default:
                $this->content   = $this->getImages($this->content);
                $this->video     = $this->getVideo($item);
                $this->copyright = $this->getCopyright();
                $this->poster    = $this->getPoster();
                $this->image     = $this->getPoster();
                $this->format    = $this->getFormat();
                $this->formats   = $this->getFormats();
                $this->supplied  = $this->getSupplied();
        }
    }


    protected function getImageCache($width = 165, $height = 105, $original = false)
    {
        $result = route('image.placeholder', ['width' => $width, 'height'=> $height, 'name' => $this->title]);

        #image intervention package
        if ($this->video_data) {
            $pi      = pathinfo($this->video_data->audio_name);
            $base    = $pi['filename'];
            $name    = $base.'.jpg';

            switch($width) {
                case ViewEntity::HOMEROW_KEYS_WIDTH:
                    $path	= str_replace("videos/","",$this->video_data->audio_path);
                    $result = route('imagecache', ['template' => 'homerow', 'filename' => $path.$name]);
                break;
                case ViewEntity::VIEW_MODE_LIST_WIDTH:
                    $path	= str_replace("videos/","",$this->video_data->audio_path);
                    $result = route('imagecache', ['template' => 'list', 'filename' => $path.$name]);
                break;
            }
        }
        //echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;
        return $result;
    }


    private function getVideoData()
    {
        return \DB::table('document_video')
                    ->where('entry_id', $this->id)
                    ->first();
    }


    private function getVideo($item)
    {
        if ($this->video_data) {
            return route('video.view', ['path'=>preg_replace("/[^0-9]/","",$this->video_data->audio_path), 'name' => $this->video_data->audio_name]);
        }
        return false;
    }


    private function getPoster()
    {
        $h = '';
        //return $h;
        if ($this->video_data) {
            $pi      = pathinfo($this->video_data->audio_name);
            $base    = $pi['filename'];
            $uri     = sprintf('/%s%s', $this->video_data->audio_path, $base);
            $file    = sprintf('%s%s.jpg', public_path(), $uri);
            if (file_exists($file))
                $h.=sprintf('http://%s%s.jpg', $_SERVER['HTTP_HOST'], $uri);
        }
        return $h;
    }


    private function getFormat()
    {
    	if ($this->video_data) {
    		$pi = pathinfo($this->video_data->audio_name);
    		return $pi['extension'];
    	}
    	return "";
    }


    private function getFormats()
    {
        $h = '';
        if ($this->video_data) {
            if ($this->original) {
                $formats = explode(',', $this->getFormat().','.$this->video_data->supplied);
            } else {
                $formats = explode(',', $this->video_data->supplied);
            }
            $pi      = pathinfo($this->video_data->audio_name);
            $base    = $pi['filename'];
            $uri     = sprintf('/%s%s', $this->video_data->audio_path, $base);
            foreach ($formats as $format) {
                $file = sprintf('%s%s.%s', public_path(), $uri, $format);
                if (file_exists($file))
                $h.=sprintf('
                    data-%s="http://%s%s.%s"', $format, $_SERVER['HTTP_HOST'], $uri, $format);
            }
        }
        return $h;
    }


    private function getSupplied()
    {
        $h = '';
        if ($this->video_data) {
            if ($this->original) {
                $h.=$this->getFormat().',';
            }
            if ($this->video_data->supplied) {
                $h.=$this->video_data->supplied;
            }
        }
        //$h = 'ogg, m4v, webm';
        return $h;

    }


    protected function getCopyright()
    {
        $result = '';
        if ($this->video_data) {
            if ($this->item->copyright && $this->video_data->recorded) {
                $year = substr($this->video_data->recorded, 0, 4);
                $result = sprintf('Copyright (%s) %s', $year, ucwords($this->item->copyright));
            } else
                if ($this->item->copyright) {
                    $result = sprintf('Copyright %s', ucwords($this->item->copyright));
                } else
                    if ($this->video_data->recorded) {
                        $year = substr($this->video_data->recorded, 0, 4);
                        $result = sprintf('Copyright (%s)', $year);
                    } else {
                        $result = sprintf('CopyrighT');
                    }
        }
        return $result;
    }

}
