<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

/**
 * @author virgilm
 *
 */

/*
 * Get original size: /en/photos/9/0/0/1053635008.jpg
 * Resize smaller: /en/photos/9/250/0/1053635008.jpg
 * Resize bigger: /en/photos/9/1000/0/1053635008.jpg
 *
 * todo: Check original filenames in photos folder for
 * ./originalSize_1307862975.jpg
 * ./mediumSize_1307862975.jpg
 * ./thumb_1307862975.jpg
 * ./1307862975.jpg
 *
 * todo: cache images and verify headers send
 *
 * http://blog.nedex.io/dynamic-thumbnail-image-compression-on-laravel-5/
 */
class ImageController extends Controller
{

/*
	private function apc_expire($key)
	{
		$cache = apc_cache_info ( 'user' );
		if (empty ( $cache ['cache_list'] )) {
			return false;
		}
		foreach ( $cache ['cache_list'] as $entry ) {
			if ($entry ['info'] != $key) {
				continue;
			}
			if ($entry ['ttl'] == 0) {
				return 0;
			}
			$expire = $entry ['creation_time'] + $entry ['ttl'];
			return $expire;
		}
		return false;
	}
	*/

    /*
	public function __construct()
	{
		$since = @$_SERVER ['HTTP_IF_MODIFIED_SINCE'];
		$etag = @$_SERVER ['HTTP_IF_NONE_MATCH'];
		// Log::info(array('since'=>$since, 'etag'=>$etag));
	}
	*/


    private function imagePlaceholder2($width, $height, $text = '404')
    {
        /*
		 * $img = Image::canvas($width, $height, array(255,255,255,0.5));
		 * $img->text($text, $width/4, $height/5*5, function($font){
		 * $ttf = public_path() .'/'. 'assets/fonts/felbridgestd-light.ttf';
		 * $font->file($ttf);
		 * $font->size(16);
		 * $font->color('#00adf0');
		 * $font->color('#ccc');
		 * $font->angle(45);
		 * });
		 */
        $img = Image::cache(function ($image) use ($width, $height, $text) {
            $image->canvas($width, $height, [
                    255,
                    255,
                    255,
                    0.5
            ]);
            $image->text($text, $width / 4, $height / 5 * 5, function ($font) {
                $ttf = public_path() . '/' . 'assets/fonts/felbridgestd-light.ttf';
                $font->file($ttf);
                $font->size(16);
                $font->color('#00adf0');
                $font->color('#ccc');
                $font->angle(45);
            });
        }, 10, true);

        return $img;
    }



    /*
	 * $img->fit(800, 600, function ($constraint) {
	 * $constraint->upsize();
	 * });
	 */
    /*
	public function image2($path, $width, $height, $name)
	{
		$filename = sprintf ( 'photos/%d/%s', $path, $name );
		$height = $height ? $height : 1000;

		if (file_exists ( public_path () . '/' . $filename )) {
			if (! $width) {
				// original size
				$img = Image::cache ( function ($image) use($filename) {
					$image->make ( $filename );
				}, 10, true );

				return $img->response ();
			} else {
				// resized
				$img = Image::cache ( function ($image) use($filename, $width, $height) {
					$image->make ( $filename )->resize ( $width, $height, function ($constraint) {
						$constraint->aspectRatio ();
					} );
				}, 10, true );

				return $img->response ();
			}
		}

		// return placeholder
		if ($width == 673)
			$height = 200;
		return $this->imagePlaceholder ( $width, $height, $filename )->response ();
	}
	*/

    /*
	private function imagePlaceholder($width, $height, $text = '404', $ttl = 1)
	{
		$image = Cache::remember ( md5 ( $text . $width ), $ttl, function () use($text, $width, $height) {
			$img = Image::canvas ( $width, $height, array (
					255,
					255,
					255,
					0.5
			) );

			$img->text ( $text, $width / 4, $height / 5 * 5, function ($font) {
				$ttf = public_path () . '/' . 'assets/fonts/felbridgestd-light.ttf';
				$font->file ( $ttf );
				$font->size ( 16 );
				$font->color ( '#00adf0' );
				$font->color ( '#ccc' );
				$font->angle ( 45 );
			} );

			return $img->encode ( 'jpg' );
		} );

		return $image;
	}
	*/

    /**
     *
     * @param unknown $etag
     * @param number $expire
     *          In minutes
     * @return boolean
     *
     */
        /*
	private function isCached($etag, $expire = 1)
	{
		$result = ((@strtotime ( @$_SERVER ['HTTP_IF_MODIFIED_SINCE'] ) + ($expire * 60) > time ()) && (trim ( @$_SERVER ['HTTP_IF_NONE_MATCH'] ) == $etag));
		$result = ((@strtotime ( @$_SERVER ['HTTP_IF_MODIFIED_SINCE'] ) > time ()) && (trim ( @$_SERVER ['HTTP_IF_NONE_MATCH'] ) == $etag));
		//$result=1;
		return $result;
		if ($result) {
			// header("HTTP/1.1 304 Not Modified");
			Log::info ( 'SEND 304' );
			return Response::make ( null, 304 );
			return App::abort ( 304, 'HTTP/1.1 304 Not Modified' );
		}
		// return $result;
	}
	*/


    public function image($path, $width, $height, $name)
    {
        $filename   = public_path().'/'.sprintf('photos/%d/%s', $path, $name);
        $width      = $width ? $width : null;
        $height     = $height ? $height : null;
        $ttl        = 60*24*30; //month
        $headers    =  [];

        if (file_exists($filename)) {
            if (!$width) {
                //original size
                $image = Image::make($filename);
                $image->encode('jpg');

                return $image->response()
                        ->setMaxAge($ttl * 60)
                        ->setPublic()
                        ->header('Etag', md5($image->getEncoded()));

                return $image->response()
                        ->header('Pragma', 'public')
                        ->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + ($ttl * 60)))
                        ->setTtl($ttl);


                //$headers['Pragma'] = 'public';
                $headers['Content-Type'] = $image->mime();
                //$headers['Expires'] = gmdate('D, d M Y H:i:s \G\M\T', time() + ($ttl * 60));
                //$headers['X-KFN'] = '2.0';

                return response($image, 200, $headers)
                        ->setTtl($ttl);
            } else {
                /*
				$image = Image::make($filename);

				//determine max width
				$width = min($width, $image->width());

				$image->resize($width, $height, function($constraint){
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$image->encode('jpg');

			    //$headers['Pragma'] = 'public';
			    $headers['Content-Type'] = $image->mime();
			    //$headers['Expires'] = gmdate('D, d M Y H:i:s \G\M\T', time() + ($ttl * 60));
			    //$headers['X-KFN'] = '2.0';

				return response($image, 200, $headers)
					->setTtl($ttl);
				*/

                //fixme: caching not working
                $image = Image::cache(function ($image) use ($filename, $width, $height) {

                    $image->make($filename);

                    $image->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    $image->encode('jpg');
                }, $ttl, true);

                //'Etag' => md5($content)
                return $image->response()
                            ->setMaxAge($ttl * 60)
                            ->setPublic()
                            ->header('Etag', md5($image->getEncoded()));
                /*
							->header('Pragma', 'public')
							->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + ($ttl * 60)))
							->setTtl($ttl);
					*/
                //$headers['Pragma'] = 'public';
                $headers['Content-Type'] = $image->mime();
                //$headers['Expires'] = gmdate('D, d M Y H:i:s \G\M\T', time() + ($ttl * 60));
                //$headers['X-KFN'] = '2.0';

                return response($image, 200, $headers)
                        ->setTtl($ttl);

                return response($image->encode('jpg'), 200, $headers)->setTtl($ttl);
                return $image->response();
            }
        } else {
            // or just send file not found
            //abort(404);

            // placeholder
            if ($width == 673) {
                $height = 200;
            }
            $placeholder = 1;
            $width = $width ? $width : 500;
            $height = $height ? $height : 500;

            $image = Cache::remember(md5($filename.$width.$placeholder), $ttl, function () use ($filename, $width, $height) {
                $img = Image::canvas($width, $height, [
                    255,
                    255,
                    255,
                    0.5
                ]);

                $img->text(basename($filename), $width / 3, $height / 2, function ($font) {
                    $ttf = public_path() . '/' . 'assets/fonts/felbridgestd-light.ttf';
                    $font->file($ttf);
                    $font->size(12);
                    $font->color('#aaa');
                    $font->angle(45);
                });

                return $img->encode('jpg');
            });

            $headers['Content-Type'] = $image->mime();
            $headers['Expires'] = gmdate('D, d M Y H:i:s \G\M\T', time() + ($ttl * 60));

            return response($image, 200, $headers)
                    ->setPublic()
                    ->setTtl($ttl);
        }

        abort(404);
    }


    /**
     * Depricated 24/03/2016
     * @param unknown $path
     * @param unknown $width
     * @param unknown $height
     * @param unknown $name
     * @return \Illuminate\Http\Response
     */
    public function image_depricated($path, $width, $height, $name)
    {
        $filename = sprintf('photos/%d/%s', $path, $name);
        $width = $width ? $width : null;
        $height = $height ? $height : null;
        $ttl = 60*24*30; //month
        $headers =  [];
        //echo __FILE__.__LINE__.'<pre>$filename='.htmlentities(print_r($filename,1)).'</pre>';

        if (file_exists(public_path().'/'.$filename)) {
            //echo __FILE__.__LINE__.'<pre>$width='.htmlentities(print_r($width,1)).'</pre>';die;

            if (!$width) {
                //original size
                $filename = public_path().'/'.$filename;
                $image = Image::make($filename);
                //echo __FILE__.__LINE__.'<pre>$img='.htmlentities(print_r($img,1)).'</pre>';die;
                $image->encode('jpg');

                /*
				 $image = Cache::remember(md5($filename).$width.$height, $ttl, function() use($filename, $width, $height)
					{
					$img = Image::make($filename);

					$img->resize($width, $height, function($constraint){
					$constraint->aspectRatio();
					$constraint->upsize();
					});

					return $img->encode('jpg');
					});

					if ($e = $this->apc_expire('kfn_'.md5($filename)))
						$headers ['Last-Modified'] = date(DATE_RFC822, $e);
						*/
            } else {
                //resize
                $filename = public_path().'/'.$filename;

                $image = Cache::remember(md5($filename).$width.$height, $ttl, function () use ($filename, $width, $height) {
                    $img = Image::make($filename);

                    //determine max width
                    $width = min($width, $img->width());

                    $img->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                        return $img->encode('jpg');
                });









                //$height = $height == 1000 ? null : $height;

                /* 				$image = Cache::remember($filename.'.'.$width, $ttl, function() use($filename, $width, $height)
					{
					$img = Image::make($filename)->resize($width, $height, function($constraint)
					{
					$constraint->aspectRatio();
					});
					return $img->encode('jpg');
					});
				*/
                /*
					$image = Cache::remember(md5($filename.$width), $ttl, function() use($filename, $width, $height)
					{
					echo __FILE__.__LINE__.'<pre>$width='.htmlentities(print_r($width,1)).'</pre>';
					echo __FILE__.__LINE__.'<pre>$height='.htmlentities(print_r($height,1)).'</pre>';die;
					$img = Image::make($filename)->resize($width, $height, function($constraint)
					{
					$constraint->aspectRatio();
					});
					return $img->encode('jpg');
					} );

					if ($e = $this->apc_expire('kfn_'.md5($filename.$width)))
						$headers ['Last-Modified'] = date(DATE_RFC822, $e);
						// $headers['Last-Modified'] = gmdate("D, d M Y H:i:s", $e) . " GMT";
						*/
            }
        } else {
            // or just send file not found
            abort(404);

            // placeholder
            if ($width == 673) {
                $height = 200;
            }
                $placeholder = 1;
                $image = Cache::remember(md5($filename.$width.$placeholder), $ttl, function () use ($filename, $width, $height) {
                    $width = $width ? $width : 1000;
                    $img = Image::canvas($width, $height, [
                            255,
                            255,
                            255,
                            0.5
                    ]);

                    $img->text($filename, $width / 4, $height / 5 * 5, function ($font) {
                        $ttf = public_path() . '/' . 'assets/fonts/felbridgestd-light.ttf';
                        $font->file($ttf);
                        $font->size(16);
                        $font->color('#00adf0');
                        $font->color('#ccc');
                        $font->angle(45);
                    });

                    return $img->encode('jpg');
                });

                $e = $this->apc_expire('kfn_'.md5($filename . $width . $placeholder));
                $headers['Last-Modified'] = gmdate("D, d M Y H:i:s", $e) . " GMT";
        }


        $headers['Content-Type'] = 'image/jpeg';
        $headers['X-KFN'] = '2.0';
        return response($image, 200, $headers)
        ->setTtl($ttl);


        $etag = md5($image);

        if ($this->isCached($etag, $ttl)) {
            //if ($e>time()) {
            $xheaders = [];
            $xheaders['Cache-Control'] = 'private, max-age=10800, pre-check=10800';
            $xheaders['Pragma'] = 'expires';
            $xheaders['Expires'] = date(DATE_RFC822, strtotime(" 2 day"));
            /*
			 * HTTP/1.1 304 Not Modified
			 * Date: Thu, 13 Nov 2014 22:03:44 GMT
			 * Expires: Thu, 13 Nov 2014 22:03:44 GMT
			 */
            //Log::info('SEND 304');
            return response($image, 304, $xheaders);

            return response($image, 200, $headers)
            ->setTtl($ttl);
        }

        /*
			$headers['Content-Type'] = 'image/jpeg';
			$headers['Cache-Control'] = 'max-age=' . $ttl * 60; // seconds
			$headers['Pragma'] = 'public';
			$headers['Etag'] = $etag;
			$headers['X-KFN'] = '2.0';
			*/


        return response($image, 200)
        ->setTtl($ttl);



        return response($image, 200, $headers)
        ->setTtl($ttl);
    }


    public function placeholder($width, $height, $name)
    {
        $width = $width ?  : 100;
        $height = $height ? $height : 10;
        $ttl = 1; // minutes
        $headers =  [];

        $image = Image::canvas($width, $height, [
            255,
            255,
            255,
            0.5
        ]);

        $image->text($name, $width / 4, $height / 5 * 5, function (\Intervention\Image\Gd\Font $font) {
            $ttf = public_path() . '/' . 'assets/fonts/felbridgestd-light.ttf';
            $ttf = public_path() . '/' . 'assets/fonts/arial.ttf';
            $font->file($ttf);
            $font->size(16);
            $font->color('#00adf0');
            $font->color('#ccc');
            $font->angle(45);
        });

        $image->encode('jpg');

        $headers ['Content-Type'] = 'image/jpeg';
        $headers ['Pragma'] = 'public';
        $headers ['X-KFN'] = '2.0';

        return response($image, 200, $headers)
                ->setTtl($ttl);
    }


    /*
	public function image3($path, $width, $height, $name)
	{
		$filename = sprintf ( 'photos/%d/%s', $path, $name );
		// $height = $height ? $height : 1000;
		$headers = array ();
		$headers ['Content-Type'] = 'image/jpeg';

		$filename = file_exists ( public_path () . '/' . $filename ) ? file_exists ( public_path () . '/' . $filename ) : 'photos/placeholder.jpg';
		if ($filename) {
			if (! $height) {
				// original size
				$image = new SimpleImage ();
				$image->load ( public_path () . '/' . $filename );
				$buffer = $image->output2 ();
				$headers ['Content-Type'] = image_type_to_mime_type ( $image->image_type );
			} elseif ($height) {
				// resized
				// original size
				$image = new SimpleImage ();
				$image->load ( public_path () . '/' . $filename );
				// $image->resizeToHeight($height);
				$image->resize ( $width, $height );
				$buffer = $image->output2 ();
				$headers ['Content-Type'] = image_type_to_mime_type ( $image->image_type );
			}
		}

		return Response::make ( $buffer, 200, $headers );
	}
	*/
}
