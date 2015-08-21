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
		'/user_attributes/css/style.css',
	),
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);
?>

<?php $this->start('subtitle'); ?>
	<!-- 1 / 5 ページ (合計: 5件) -->
	<span class="user-search-paginator-count">
		<?php echo sprintf(__d('users', '%s of %s (Total: %s)'),
					$this->Paginator->counter('{:page}'),
					$this->Paginator->counter('{:pages}'),
					$this->Paginator->counter('{:count}')); ?>
	</span>
<?php $this->end(); ?>

<div class="text-center">
	<a class="btn btn-info" href="<?php echo $this->Html->url('/user_manager/user_manager/search/'); ?>">
		<span class="glyphicon glyphicon-search"></span>
		<!-- 対象会員の絞込み -->
		<?php echo __d('users', 'Search for the members'); ?>
	</a>
</div>

<div class="text-right">
	<a class="btn btn-success" href="<?php echo $this->Html->url('/user_manager/user_manager/add/');?>">
		<span class="glyphicon glyphicon-plus"> </span>
	</a>
</div>

<table class="table table-condensed">
	<thead>
		<tr>
			<th></th>
			<?php foreach ($displayFields as $field) : ?>
				<th>
					<?php echo $this->UserValue->label($field); ?>
				</th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $index => $user) : ?>
			<tr>
				<td>
					<?php echo ($index + 1); ?>
				</td>

				<?php $this->UserValue->set($user); ?>

				<?php foreach ($displayFields as $field) : ?>
					<td>
						<?php echo $this->UserValue->display($field); ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php echo $this->element('NetCommons.paginator'); 
