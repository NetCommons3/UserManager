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

echo $this->NetCommonsHtml->css(array(
	//'/user_manager/css/style.css',
	'/users/css/style.css',
));
echo $this->NetCommonsHtml->script('/user_manager/js/user_manager.js');
?>

<?php $this->start('subtitle'); ?>
	<span class="user-search-paginator-count">
		<?php echo sprintf(__d('users', '%s of %s (Total: %s)'),
					$this->Paginator->counter('{:page}'),
					$this->Paginator->counter('{:pages}'),
					$this->Paginator->counter('{:count}')); ?>
	</span>

	<div class="pull-right">
		<?php echo $this->NetCommonsHtml->link(
			'<span class="glyphicon glyphicon-export"></span> ' . __d('user_manager', 'Export'),
			array('action' => 'export'),
			array('name' => 'import', 'class' => 'btn btn-default btn-sm', 'escapeTitle' => false)
		); ?>

		<?php echo $this->NetCommonsHtml->link(
			'<span class="glyphicon glyphicon-import"></span> ' . __d('user_manager', 'Import'),
			array('action' => 'import'),
			array('name' => 'import', 'class' => 'btn btn-default btn-sm', 'escapeTitle' => false)
		); ?>
	</div>
<?php $this->end(); ?>

<?php echo $this->MessageFlash->description(
	__d('user_manager', 'Click the handle name to read his/her data. And to edit the user data. And delete user data, please go from editing screen.')
); ?>

<div class="user-search-index-head-margin">
	<?php echo $this->UserSearchForm->displaySearchButton(); ?>

	<div class="text-right">
		<?php echo $this->Button->addLink(); ?>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-condensed">
		<thead>
			<tr>
				<?php echo $this->UserSearch->tableHeaders(); ?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($users as $index => $user) : ?>
				<tr>
					<?php echo $this->UserSearch->tableRow($user, true); ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php echo $this->element('NetCommons.paginator');
