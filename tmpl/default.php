<?php 
/**
 * @package    Hoicoi_glosbe
 * @subpackage default
 * @author     Jibon Lawrence Costa
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access'); 
JHtml::_('jquery.framework');
$user = JFactory::getUser();
?>

<div class="hoicoi_openmeetings<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	<p></p>
	<div id="meeting_status" style="color: red;"></div>
	<p></p>
	<div class="openmeetings">
		<div class="form-group">
			<?php echo $rooms; ?>
		</div>
		<p></p>
		<div class="form-group">
			<input type = "text" value = "<?php echo $user->username; ?>" name = "name"  placeholder="Name" />
		</div>
		<div class="form-group">
			<input type = "text" value = "<?php echo $user->email; ?>" name = "email"  placeholder="Email" />
		</div>
		<div class="form-group">
			<input type = "password" name = "pass"  placeholder="Password" />
		</div>
		<div class="form-group">
			<button class="btn btn-primary" id="submit">Log in</button>
		</div>
	</div>
	
</div>