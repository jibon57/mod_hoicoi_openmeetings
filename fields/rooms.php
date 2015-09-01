<?php
/**
 * @author     Jibon Lawrence Costa
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die();

/**
 *
 */
class JFormFieldRooms extends JFormField
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Rooms';

    /**
     * @var string
     */
    protected $type = 'Rooms';

    /**
     * @return string
     */
    public function getInput()
	{
		
		$html = "
				<script type=\"text/javascript\">
					jQuery(\"document\").ready(function($){
						var i = 0;
						if (parseInt($('#field p:last').attr('id')) > 0){
								i = parseInt($('#field p:last').attr('id')) + 1;
							}											
						$('#add').click(function(){							
							 $('#field').append(\"<p style='border: 1px solid gainsboro; padding: 10px;' id='\"+i+\"'>Room ID:<input name='room_id'/> Room Name:<input name='room'/> Moderator Password:<input name='m_pass'/> <br/>Attendee Password:<input name='a_pass'/><span id='delete' action='\"+i+\"' class='btn btn-small icon-cancel'></span></p>\");
							i++;
						});
						$('#save').on('click',function(){
							$('#fieldval').val('');
							$('#field p').each(function(index){
								var id = $(this).attr('id');
								var room_id = $(this).children('input:nth(0)').val();
								var room = $(this).children('input:nth(1)').val();
								var m_pass = $(this).children('input:nth(2)').val();
								var a_pass = $(this).children('input:nth(3)').val();
								$('#fieldval').val($('#fieldval').val()+ id+'|'+room_id+'|'+room+'|'+m_pass+'|'+a_pass+',');
							});
							Joomla.submitbutton('module.apply');
						});
						$('#delete').live('click', function(){
							var id = $(this).attr('action');
							$('#field p[id=\"'+id+'\"]').remove();
						});
						
					});
				</script>
				
				<span id='add' class='btn btn-small'>Add</span>
				<div id='field'>
								
				
		";
		$input = "";
		if (!empty($this->value)){
			$primary = explode(',',rtrim($this->value,","));
			foreach ($primary as $value){
				$extract = explode("|", $value);
				if (!empty($extract[1])){
					$input .= "<p style='border: 1px solid gainsboro; padding: 10px;' id='".$extract[0]."'>Room ID:<input name='room_id' value='".$extract[1]."'/> Room Name:<input name='room' value='".htmlspecialchars($extract[2], ENT_QUOTES)."'/> Moderator Password:<input name='m_pass' value='".htmlspecialchars($extract[3], ENT_QUOTES)."'/> <br/> Attendee Password:<input name='m_pass' value='".htmlspecialchars($extract[4], ENT_QUOTES)."'/><span id='delete' action='".$extract[0]."' class='btn btn-small icon-cancel'></span></p>";
				}
			}
			$html .= $input;
		}
		
		$html .="</div><p></p><span id='save' class='btn btn-small btn-success'>Save</span>";
		$html .= '<textarea style="display: none;" name="'.$this->name.'" id="fieldval">'.$this->value.'</textarea>';
		return $html;

	}
	
}