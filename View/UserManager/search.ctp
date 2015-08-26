<?php
/**
 * UserManager index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->Html->css(
	array(
		'/user_attributes/css/style.css'
	),
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);
?>

<div class="panel panel-default">
	<div class="panel-body">
		<?php foreach ($userAttributeLayouts as $layout) : ?>
			<?php $row = $layout['UserAttributeLayout']['id']; ?>

			<?php echo $this->element('UserManager/render_index_row', array('row' => $row, 'layout' => $layout)); ?>
		<?php endforeach; ?>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Form->button('<span class="glyphicon glyphicon-search"></span> ' . __d('net_commons', 'Search'), array(
				'class' => 'btn btn-info btn-workflow',
				'name' => 'search',
			)); ?>
	</div>
</div>
