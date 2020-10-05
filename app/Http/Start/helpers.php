<?php

namespace App\Http\Start;

use View;
use Session;
use App\Models\Metas;
use Image;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class Helpers
{

    // Get current controller method name
    public function current_action($route)
    {
        $current = explode('@', $route); // Example $route value: App\Http\Controllers\HomeController@login
        View::share('current_action',$current[1]); // Share current action to all view pages
    }

	// Set Flash Message function
	public function flash_message($class, $message)
	{
		Session::flash('alert-class', 'alert-'.$class);
	    Session::flash('message', $message);
	}

        // Dynamic Function for Showing Meta Details
    public static function meta($url, $field)
    {
        $metas = Metas::where('url', $url);
    
        if($metas->count())
            return $metas->first()->$field;
        else if($field == 'title')
            return 'Page Not Found';
        else
            return '';
    }

	public function compress_image($source_url, $destination_url, $quality, $width = 225, $height = 225) 
	{
        try {
            $info = getimagesize($source_url);
        }
        catch(\Exception $e) {
            return false;
        }

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source_url);
        }
        elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source_url);
        }
        elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source_url);
        }
        imagejpeg($image, $destination_url, $quality);

        $this->crop_image($source_url, $width, $height);

        return $destination_url;
    }

    public function crop_image($source_url='', $crop_width=225, $crop_height=225, $destination_url = '')
    {
    	$image = Image::make($source_url); 
        $image_width = $image->width();
        $image_height = $image->height();

        if($image_width < $crop_width && $crop_width < $crop_height){
            $image = $image->fit($crop_width, $image_height);
        }
        if($image_height < $crop_height  && $crop_width > $crop_height){
            $image = $image->fit($crop_width, $crop_height);
        }

  		$primary_cropped_image = $image;

        $croped_image = $primary_cropped_image->fit($crop_width, $crop_height);

		if($destination_url == ''){
			$source_url_details = pathinfo($source_url); 
			$destination_url = @$source_url_details['dirname'].'/'.@$source_url_details['filename'].'_'.$crop_width.'x'.$crop_height.'.'.@$source_url_details['extension']; 
		}
		$croped_image->save($destination_url); 
		return $destination_url; 
    }

    public static function buildExcelFile($filename, $data, $width = array())
    {
        /** @var \Maatwebsite\Excel\Excel $excel */
        $excel = app('excel');

        $excel->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal('left');
        foreach ($data as $key => $array) {
            foreach ($array as $k => $v) {
                if(!$v){
                    $data[$key][$k] = '--';
                }
            }
        }

        // dd($filename, $data, $width);
        return $excel->create($filename, function (LaravelExcelWriter $excel) use($data, $width){
            $excel->sheet('exported-data', function (LaravelExcelWorksheet $sheet) use($data, $width) {
                $sheet->fromArray($data)->setWidth($width);
                $sheet->setAllBorders('thin');
            });
        });
    }

    public function cloud_upload($file,$last_src="",$resouce_type="image")
    {
        try 
        {
            if($resouce_type=="video")
            {
                \Cloudder::uploadVideo($file);
            }
            else
            {
                \Cloudder::upload($file);    
            }        
            $c=\Cloudder::getResult();
            // if($last_src!="") \Cloudder::destroy($last_src);
            $data['status']="success";
            $data['message']=$c;
        }
        catch (\Exception $e) 
        {
            $data['status']="error";
            $data['message']=$e->getMessage();
        }
        return $data;       
    }
    public function delete_cloud_upload($last_src="")
    {
        try 
        {
            // if($last_src!="") \Cloudder::destroy($last_src);
            $data['status']="success";
        }
        catch (\Exception $e) 
        {
            $data['status']="error";
            $data['message']=$e->getMessage();
        }
        return $data;       
    }
    public function show_cloud_image($public_id,$options)
    {
        $src=\Cloudder::show($public_id,$options);
        return $src;
    }

    /**
 * Resize image given a height and width and return raw image data.
 *
 * Note : You can add more supported image formats adding more parameters to the switch statement.
 *
 * @param type $file filepath
 * @param type $w width in px
 * @param type $h height in px
 * @param type $crop Crop or not
 * @return type
 */
    function resize_image($file,$max_width,$max_height,$resize,$quality=9) {

      list($ImageWidth,$ImageHeight,$TypeCode)=getimagesize($file);

      /* For JPG */

        $exploding = explode(".",$file);
        $org_ext = end($exploding);
        $ext = strtolower($org_ext);

     /* For JPG */

       $ImageType=($TypeCode==1?"gif":($TypeCode==2?"jpeg":
                 ($TypeCode==3?"png":FALSE)));

      $CreateFunction="imagecreatefrom".$ImageType;
      $OutputFunction="image".$ImageType;

      if ($ImageType) {

        $Ratio=($ImageHeight/$ImageWidth);
        $ImageSource=$CreateFunction($file);

        if ($ImageWidth > $max_width || $ImageHeight > $max_height) {

          if ($ImageWidth > $max_width) {

             $ResizedWidth=$max_width;
             $ResizedHeight=$ResizedWidth*$Ratio;
          }
          else {

            $ResizedWidth=$ImageWidth;
            $ResizedHeight=$ImageHeight;

          }        
          if ($ResizedHeight > $max_height) {

            $ResizedHeight=$max_height;
            $ResizedWidth=$ResizedHeight/$Ratio;
          }       

          $ResizedImage=imagecreatetruecolor($ResizedWidth,$ResizedHeight);
          imagecopyresampled($ResizedImage,$ImageSource,0,0,0,0,$ResizedWidth,
                             $ResizedHeight,$ImageWidth,$ImageHeight);
        } 
        else {

          $ResizedWidth=$ImageWidth;
          $ResizedHeight=$ImageHeight;      
          $ResizedImage=$ImageSource;

        }  

        $OutputFunction($ResizedImage,$resize.'.'.$ext,$quality);
        return true;
      }    
      else
        return false;
    }

    // if (!function_exists('count')) {
        function count($array)
        {
            if(is_array($array))
            {
                $sum = 0;
            foreach($array as $ar)
            {
              $sum+= 1;
            }
              return $sum;
            }
            return 0;
            }
        }
// }
