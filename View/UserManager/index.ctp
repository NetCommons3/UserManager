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

\App::uses('UserManagerSearchLib', 'UserManager.Lib');

echo $this->NetCommonsHtml->css(array(
	'/user_manager/css/style.css',
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

<div ng-controller="UserManagerController">
	<?php echo $this->MessageFlash->description(
		__d('user_manager', 'Click the handle name to read his/her data. And to edit the user data. And delete user data, please go from editing screen.')
	); ?>

	<div class="user-search-index-head-margin">
		<?php echo $this->UserSearchForm->displaySearchButton(__d('user_manager', 'Search for the members'), [], true); ?>

		<?php if ($this->Paginator->counter('{:count}') > 0) : ?>
			<div class="pull-left">
				<?php /* 選択した行を・・・ */ ?>
				<span class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<?php echo __d('user_manager', 'Selected row ...'); ?>
						<span class="caret"></span>
					</button>

					<ul class="dropdown-menu" role="menu">
						<?php /* 利用不可にする */ ?>
						<li>
							<?php
								$firstMessage = __d(
									'user_manager',
									'Nonactive the %s. Are you sure to proceed?',
									__d('user_manager', 'Selected members')
								);
								$notSelectMessage = __d('user_manager', 'Not found the select user.');
								echo $this->NetCommonsHtml->link(
									__d('user_manager', 'Change to nonactive'),
									'#',
									[
										'ng-click' => 'bulk($event, \'nonactive\', ' .
													'\'' . h($firstMessage) . '\', ' .
													'\'\', ' .
													'\'' . h($notSelectMessage) . '\')',
									]
								);
							?>
						</li>
						<li role="separator" class="divider"></li>
						<?php /* 削除する */ ?>
						<li>
							<?php
								$firstMessage = __d(
									'net_commons',
									'Deleting the %s. Are you sure to proceed?',
									__d('user_manager', 'Selected members')
								);
								$secondMessage = __d('user_manager', 'Is it really okay to delete it?');
								echo $this->NetCommonsHtml->link(
									__d('user_manager', 'Delete'),
									'#',
									[
										'ng-click' => 'bulk($event, \'delete\', ' .
													'\'' . h($firstMessage) . '\', ' .
													'\'' . h($secondMessage) . '\', ' .
													'\'' . h($notSelectMessage) . '\')',
									]
								);
							?>
						</li>
					</ul>
				</span>
			</div>
		<?php endif; ?>

		<div class="text-right">
			<?php echo $this->Button->addLink(__d('user_manager', 'Add user'), ['controller' => 'user_add', 'action' => 'basic']); ?>
		</div>
	</div>

	<div class="table-responsive">
		<?php
			$bulkUserIds = [];
			$hasAdminUser = false;
		?>

		<table class="table">
			<thead>
				<tr>
					<th class="text-center rooms-roles-users-checkbox">
						<label>
							<input type="checkbox" ng-click="allCheck($event)">
						</label>
					</th>
					<?php echo $this->UserSearch->tableHeaders(true); ?>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($users as $index => $user) : ?>
					<?php
						$domUserId = $this->NetCommonsForm->domId('User.id.' . $user['User']['id']);
					?>
					<tr<?php echo $this->UserSearch->userActiveClass($user); ?>>
						<td class="text-center rooms-roles-users-checkbox">
							<?php
								if (\UserManagerSearchLib::hasEditableBulkUser($user)) {
									echo '<label for="' . $domUserId . '">';
									echo '<input type="checkbox" id="' . h($domUserId) . '"' .
											' name="' . h($domUserId) . '"' .
											' value="' . h($user['User']['id']) . '"' .
											' ng-click="check($event, \'' . h($domUserId) . '\');"' .
											' ng-disabled="sending">';
									echo '</label>';
									$bulkUserIds[] = $user['User']['id'];
								} else {
									$hasAdminUser = true;
								}
							?>
						</td>
						<?php echo $this->UserSearch->tableRow($user, true, ['controller' => 'user_manager', 'action' => 'edit']); ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
			echo $this->NetCommonsForm->create('UserManagerBulk', [
				'url' => NetCommonsUrl::actionUrlAsArray(['controller' => 'user_manager', 'action' => 'bulk'])
			]);
			echo $this->NetCommonsForm->hidden('UserManagerBulk.displayIds', ['value' => implode(',', $bulkUserIds)]);
			echo $this->NetCommonsForm->hidden('UserManagerBulk.hasPrev', ['value' => $this->Paginator->hasPrev()]);
			echo $this->NetCommonsForm->hidden('UserManagerBulk.hasNext', ['value' => $this->Paginator->hasNext()]);
			echo $this->NetCommonsForm->hidden('UserManagerBulk.hasAdminUser', ['value' => $hasAdminUser]);

			echo $this->NetCommonsForm->unlockField('UserManagerBulk.checkedIds');
			echo $this->NetCommonsForm->hidden('UserManagerBulk.checkedIds', ['value' => '']);

			echo $this->NetCommonsForm->unlockField('UserManagerBulk.submit');
			echo $this->NetCommonsForm->hidden('UserManagerBulk.submit', ['value' => '']);

			echo $this->NetCommonsForm->end();
		?>
	</div>

	<?php echo $this->element('NetCommons.paginator'); ?>
</div>