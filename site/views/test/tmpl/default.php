<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_test
 *
 *
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');

JFactory::getDocument()->addScriptDeclaration(
'(function($){'
 	.'$(function(){'
 		.'$("#linkpopup").click(function(event){'
 			.'event.preventDefault();'
 			.'var url = $(this).attr("href");'
     		.'window.open(url,"targetWindow","toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600");'
    	.'});'
    .'});'
.'})(jQuery);'
);
?>
<a id="linkpopup" href="<?php echo JRoute::_( 'index.php?option=com_test&task=test.signin&case=1' );?>">Sign in with Linkedin</a>