<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_test
 *
 *
 */

defined('_JEXEC') or die;

?>
<form method="post" action="<?php echo JRoute::_( 'index.php?option=com_test' );?>">
<?php
foreach($this->form->getFieldset('base') as $field):
	echo $field->label;
	echo $field->input;
endforeach;
?>
	<input type="hidden" name="task" value="test.save" />
	<div style="clear:both;"></div>
	<input type="submit" />
	<?php echo JHtml::_('form.token'); ?>
</form>