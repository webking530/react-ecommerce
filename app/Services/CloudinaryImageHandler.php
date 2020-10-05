<?php

/**
 * Local Image Handler
 *
 * @package     Spiffy
 * @subpackage  Services
 * @category    Image Handler
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
*/

namespace App\Services;

use App\Contracts\ImageHandlerInterface;
use Cloudder;

class CloudinaryImageHandler implements ImageHandlerInterface
{
	protected function validateExtension($ext)
	{
		$ext = strtolower($ext);
		return in_array($ext,['png', 'jpg', 'jpeg', 'gif', 'webp']);
	}
	
	public function upload($image, $options = [])
	{
		try {
			$ext = $image->getClientOriginalExtension();
			$valid = $this->validateExtension($ext);
			if(!$valid) {
				return [
					'status' => false,
					'status_message' => 'Invalid File Type',
				];
			}

            if(isset($options['resouce_type']) && $options['resouce_type'] == "video") {
                Cloudder::uploadVideo($image);
            }
            else {
                Cloudder::upload($image);    
            }        
            $cloud_result = Cloudder::getResult();
            
            return [
				'status' => true,
				'file_name' => $cloud_result["public_id"],
			];
        }
        catch (\Exception $e) {
        	return [
				'status' => false,
				'status_message' => $e->getMessage(),
			];
        }
	}

	public function delete($image)
	{
		try {
			Cloudder::destroy($image);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}