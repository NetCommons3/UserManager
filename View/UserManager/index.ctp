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
	'/user_manager/css/style.css',
	//'/user_manager/css/style2.css', //後で削除
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
<?php $this->end(); ?>

<div class="user-search-index-head-margin">
	<div class="text-center" ng-controller="UserManager.controller">
		<?php echo $this->Button->searchLink(__d('users', 'Search for the members'), false, array(
			'ng-click' => 'showUserSearch()'
		)); ?>
	</div>

	<div class="text-right">
		<?php echo $this->Button->addLink(); ?>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-condensed">
		<thead>
			<tr>
				<th></th>
				<?php echo $this->UserSearch->tableHeaders(); ?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($users as $index => $user) : ?>
				<tr>
					<td><?php echo ($index + 1); ?></td>
					<?php echo $this->UserSearch->tableRow($user, true); ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php echo $this->element('NetCommons.paginator');
