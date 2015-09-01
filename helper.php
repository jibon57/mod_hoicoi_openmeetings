<?php
/**
 * @author     Jibon Lawrence Costa
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/classes/openmeetings_gateway.php';

class modHoicoiopenmeetingsHelper {
	
	static private $isAdmin = 0;
	static private $isRecodring = 0;
	
	public static function getAjax() {
		
		jimport('joomla.application.module.helper');
		$input  = JFactory::getApplication()->input;
		$module = JModuleHelper::getModule('hoicoi_openmeetings');
		$params = new JRegistry();
		$params->loadString($module->params);
		$values = explode(',',rtrim($params->get('rooms'),","));
		
		if (self::getVerification($values, $input->get("room_id"), $input->get("password", "", 'STRING'))) {
			
			$options = array(
				"protocol" => $params->get('protocol'),
				"port" => $params->get('port'),
				"host" => $params->get('host'),
				"webappname" => $params->get('webappname'),
				"adminUser" => $params->get('adminUser'),
				"adminPass" => $params->get('adminPass'),
			);

			$access = new openmeetings_gateway($options);
			if(!$access->loginuser()){
				$data = array(
						"error" => 03,
						"text" =>self::getErrorInfo(03),
					);
				return $data;
			}
			$hash = $access->setUserObjectAndGenerateRoomHash($input->get("name"), $input->get("name", "", 'STRING'), "", "", $input->get("email", "", 'STRING'), JSession::getInstance("","")->getId(), "Joomla", $input->get("room_id"), self::$isAdmin, self::$isRecodring);
			if (preg_match('/\D/', $hash)){
				$url = $access->getUrl()."/?secureHash=".$hash; //Get final URL
				$data = array(
							"url" => $url,
						);
				return $data;
			} else {
				$data = array(
							"error" => $hash,
							"text" =>self::getErrorInfo($hash),
						);
				return $data;
			}
			
		} else {
			$data = array(
						"error" => 02,
						"text" =>self::getErrorInfo(02),
					);
			return $data;
		}
		
		$data = array(
					"error" => 01,
					"text" =>self::getErrorInfo(01),
				);
		
		return $data;
	}
	
	public static function getRooms($values) {

		$rooms = '<select id="room" name="room" class="form-control"><option value="">Please Select Room</option>';
		foreach ($values as $value) {
			$extract = explode("|", $value);
			$rooms .= '<option value="'.$extract[1].'">'.$extract[2].'</option>';
		}
		$rooms .= '</select>';
		
		return $rooms;
	}
	
	private static function getVerification ($values, $id, $pass) {
		foreach ($values as $value) {
			$extract = explode("|", $value);
			if ($extract[1] == $id) {
				if ($extract[3] == $pass){ //Check pass for admin
					self::$isAdmin = 1;
					self::$isRecodring = 1;
					return true;
				} elseif($extract[4] == $pass){ //check pass for general user
					return true;
				}
				break;
			}
		}
		return false;
	}
	
	private static function getErrorInfo($status_code) {
		
		switch($status_code) {
			case 503:
				$text = 'Your call to OpenMeetings Web Services failed and returned an HTTP status of 503. That means: Service unavailable. An internal problem prevented us from returning data to you.';
				break;
			case 403:
				$text = 'Your call to OpenMeetings Web Services failed and returned an HTTP status of 403. That means: Forbidden. You do not have permission to access this resource, or are over your rate limit.';
				break;
			case 400:
				$text = 'Your call to OpenMeetings Web Services failed and returned an HTTP status of 400. That means:  Bad request. The parameters passed to the service did not match as expected. The exact error is returned in the XML response.';
				break;
			case 02:
				$text = "Password don't match !!!";
				break;
			case 03:
				$text = "Request OpenMeetings! OpenMeetings Service failed and no response was returned.";
				break;
			default:
				$text = 'Your call to OpenMeetings Web Services returned an unexpected HTTP status of: ' . $status_code;
		}
		return $text;
	}
}
?>