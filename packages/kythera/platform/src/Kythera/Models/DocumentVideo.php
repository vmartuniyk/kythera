<?php
namespace Kythera\Models;

use Kythera\Entity\Entity;
use URL;
use Illuminate\Support\Facades\Queue;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Config;


/**
 * @author virgilm
 *
 */
class DocumentVideo extends DocumentAudio
{

    protected $mediaTable = 'document_video';
    
	public function setMedia($parent_entity, $data)
	{
		$id   = false;
		$src  = $data['path'].$data['name'];
		//$ext  = strtolower(pathinfo($src, PATHINFO_EXTENSION));
		$ext  = DocumentEntity::getExtension($src);
		$base = time();
		$name = $base.'.'.$ext;
		$dst  = DocumentEntity::getRandomDirectory(\Config::get('files.video'));
		$date = !empty($data['d']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['d']))) : null;

		//store in db
		if (DocumentEntity::isMedia($src)) {
			//remove current image
			\DB::table($this->mediaTable)->where('entry_id', $this->id)->delete();

			if ($id = \DB::table($this->mediaTable)->insertGetId(array(
					'entry_id' => $this->id,
					'original_audio_name' => $data['name'],
					'audio_name' => $name,
					'audio_path' => $dst,
					'recorded' => $date
					//village = $data['v]
					//copyright = $data['c]
			))) {
				//store in media folder
				$dst1  = public_path().'/'.$dst.$name;
				rename($src, $dst1);

				//convert media into HTML5 format
				$this->convert($id, $base, $dst1, $dst);
				
				//attach media
				//\DB::table('document_media')->insert(array('entry_id'=>$parent_entity->id, 'audio_id'=>$this->id));
			}
		}
		return $id;
	}


	public function getFiles()
	{
		/*
			//version 2
			$query = \DB::table('document_media')
			->select('image_id as kfnid', 'document_images.*')
			->leftJoin('document_images', 'document_media.image_id', '=', 'document_images.entry_id')
			->leftJoin('document_audio', 'document_media.audio_id', '=', 'document_audio.entry_id')
			->where('document_media.entry_id', $this->id)
			->get();
			*/

		//version 1
		$query = \DB::table($this->mediaTable)
			->select('id as kfnid', 'document_video.*')
			->where('document_video.entry_id', $this->id)
			->get();
		return $query;
	}


	/**
	 * @param DocumentEntity $entity -- depricated
	 * @param array $data
	 * @return integer $id
	 */
	public function updateMedia($entity, $data)
	{
		if (!$data['kfnid']) {
			//detach current media
			\DB::table($this->mediaTable)->where(array('entry_id'=>$this->id))->delete();

			//insert in db
			$id = $this->setMedia($this, $data);
		} else {
			//update in db
			$date = !empty($data['d']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['d']))) : null;
			\DB::table($this->mediaTable)
				->where('id', $data['kfnid'])
				->update(array(
						//'entry_id' => $this->id,
						//'original_image_name' => $data['name'],
						//'image_name' => $name,
						//'image_path' => $dst,
						'recorded' => $date
						//village = $data['v]
						//copyright = $data['c]
				));
			$id = $data['kfnid'];
		}
		return $id;
	}


	private function convert($imageId, $base, $file, $dst)
	{
	    Queue::push(function($job) use ($imageId, $base, $dst, $file) {
	        
	        if (file_exists($file)) {
    	        $dst_path   = sprintf('%s/%s', public_path(), $dst);
    	        $dst_mp4    = sprintf('%s%s.m4v', $dst_path, $base);
    	        $dst_ogg    = sprintf('%s%s.ogg', $dst_path, $base);
    	        $dst_webm   = sprintf('%s%s.webm', $dst_path, $base);
    	        $dst_poster = sprintf('%s%s.jpg', $dst_path, $base);
    	        
    	        $supplied   = array();
    	         
    	        $ffmpeg = \FFMpeg\FFMpeg::create(
    	            array(
    	                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
    	                'ffprobe.binaries' => '/usr/bin/ffprobe',
    	                'timeout' => 0,
    	                'ffmpeg.threads' => 6
    	            ),
    	            app()['log']->getMonolog()
    	        );
    	         
    	        $video = $ffmpeg->open($file);
    	        $video
    	           ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(3))
    	           ->save($dst_poster);

    	        try {
    	            // .mp4
    	            // audio/mp4, video/mp4
        	        $format = new \FFMpeg\Format\Video\X264();
        	        $video->save($format, $dst_mp4);
        	        //$supplied[] = "mp4";
        	        $supplied[] = "m4v";
    	        } catch (RuntimeException $e) {
    	            $t = $e->getPrevious()->getTrace();
    	            $t = $t[1];
    	            $p = $t['args'][0];
    	            $o = $p->getErrorOutput();
    	            Log::alert('VIDEO', [$o]);
    	        }
    	         
    	        try {
    	            //.ogg, .ogv, .oga, .ogx, .ogm, .spx, .opus
    	            //video/ogg, audio/ogg, application/ogg
        	        $format = new \FFMpeg\Format\Video\Ogg();
        	        $video->save($format, $dst_ogg);
        	        $supplied[] = "ogg";
    	        } catch (RuntimeException $e) {
    	            $t = $e->getPrevious()->getTrace();
    	            $t = $t[1];
    	            $p = $t['args'][0];
    	            $o = $p->getErrorOutput();
    	            Log::alert('VIDEO', [$o]);
    	        }
    	         
    	        try {
        	        // .webm
        	        // audio/webm, video/webm
        	        $format = new \FFMpeg\Format\Video\WebM();
        	        $video->save($format, $dst_webm);
        	        $supplied[] = "webm";
    	        } catch (RuntimeException $e) {
    	            $t = $e->getPrevious()->getTrace();
    	            $t = $t[1];
    	            $p = $t['args'][0];
    	            $o = $p->getErrorOutput();
    	            Log::alert('VIDEO', [$o]);
    	        }

    	        //update db
    	        \DB::table('document_video')
        	           ->where('id', $imageId)
        	           ->update(array(
        	            'supplied' => implode(',', $supplied)
        	           ));
	        }
	         
	        $job->delete();
	    });
	}
	
	
	static public function isVideo($file)
	{
	    $supported = array(
	        'video/quicktime', //mov
	        'video/mp4', 'application/mp4', //mp4, mp4s
	        'video/mpeg', 'video/mp4', //mpe, mpeg, mpg, mpg4
	        'video/x-flv', //flv
	        'video/x-msvideo', //avi
	        'video/x-m4v', 'application/mp4', //m4v, m4p
	        'video/ogg', 'application/ogg', //ogv ogx
	        'video/webm', //webm
	        'video/x-ms-wmv' //wmv
	    );

	    if (file_exists($file)) {
    	    if ($finfo = new \finfo(FILEINFO_MIME_TYPE)) {
    	        return in_array($finfo->file($file), Config::get('files.supported_mimes'));
    	    }
	    }
	    return false;
	     
	}

}
