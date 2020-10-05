<?php

/**
 * Image Handler Interface
 *
 * @package     Spiffy
 * @subpackage  Contracts
 * @category    Image Handler
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
*/

namespace App\Contracts;

interface ImageHandlerInterface
{
	public function upload($image, $options);
	public function delete($image);
}