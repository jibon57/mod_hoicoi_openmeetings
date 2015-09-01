<?php
/**
 * @package    Hoicoi_Openmeetings
 * @subpackage Base
 * @author     Jibon Lawrence Costa
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access'); // no direct access
require_once __DIR__ . '/helper.php';
$doc = JFactory::getDocument();
$values = explode(',',rtrim($params->get('rooms'),","));
$rooms = modHoicoiopenmeetingsHelper::getRooms($values);
$js = <<<JS
jQuery("document").ready(function($){
	$("#submit").click(function(){
		$("#meeting_status").html("Checking Information....");
		var room_id = $('select[name=room]').val(),
			name = $('input[name=name]').val(),
			email = $('input[name=email]').val(),
			password = $('input[name=pass]').val(),
			request = {
						'option' : 'com_ajax',
						'module' : 'hoicoi_openmeetings',
						'room_id' 	 :  room_id,
						'name' 	 	 :  name,
						'email' 	 :  email,
						'password' 	 :  password,
						'format' : 'json'
					};
		if(room_id ===  "" || name ===  "" || email ===  "" || password ===  "") {
			$("#meeting_status").html("Missing field !!!");
			return false;
		} else {
			$.ajax({
				type : 'GET',
				data : request,
				success : function (response){
						if (response.data.url) {
							$("#meeting_status").html("Please wait redirecting.......");
							window.location = response.data.url;
						} else {
							$("#meeting_status").html(response.data.text);
						}
				},
				error: function(response) {
					console.log(response);
				}
			});
		}
	});
});
JS;

$doc->addScriptDeclaration($js);
require(JModuleHelper::getLayoutPath('mod_hoicoi_openmeetings'));

?>