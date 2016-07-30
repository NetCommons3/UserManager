<?php
/**
 * RolesRoomsUser edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css(array(
	'/user_manager/css/style.css',
));
?>

<header>
	<h2>
		<?php echo __d('user_manager', 'Import title'); ?>
	</h2>

	<div class="well well-sm">
		<?php echo __d('user_manager', 'Import description'); ?>
	</div>

	<?php if ($errorMessages) : ?>
		<div class="alert alert-warning user-import pre-scrollable">
			<?php foreach ($errorMessages as $message) : ?>
				<div class="text-danger"><?php echo $message; ?></div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</header>

<article>
	<div class="text-right">
		<?php echo $this->NetCommonsHtml->link(__d('user_manager', 'Format file download'), array(
			'action' => 'download_import_format'
		)); ?>
	</div>
	<div class="panel panel-default">
		<?php echo $this->NetCommonsForm->create(false, array('type' => 'file')); ?>

		<div class="panel-body">
			<?php
				//重複データの扱い
				echo $this->NetCommonsForm->input('import_type', array(
					'type' => 'radio',
					'options' => array(
						ImportExportBehavior::IMPORT_TYPE_NEW => __d('user_manager', 'Error if have same datas.'),
						ImportExportBehavior::IMPORT_TYPE_UPDATE => __d('user_manager', 'Overwrite the same datas.'),
						ImportExportBehavior::IMPORT_TYPE_SKIP => __d('user_manager', 'Skip if have same datas.'),
					),
					'label' => __d('user_manager', 'Same datas'),
					'value' => ImportExportBehavior::IMPORT_TYPE_NEW
				));
			?>

			<?php
				//インポートファイルの指定
				echo $this->NetCommonsForm->input('import_csv', array(
						'type' => 'file',
					'class' => '',
					'label' => __d('user_manager', 'Import file'),
				));
			?>

			<?php //インポートファイルの説明 ?>
			<div ng-init="DetailsShow = false">
				<label>
					<?php echo __d('user_manager', 'Details of settings from import file'); ?>
				</label>
				<a href="" class="btn btn-default btn-xs" ng-click="DetailsShow = true" ng-show="!DetailsShow">
					<?php echo __d('user_manager', 'display'); ?>
				</a>
				<a href="" class="btn btn-default btn-xs" ng-click="DetailsShow = false" ng-show="DetailsShow">
					<?php echo __d('user_manager', 'hidden'); ?>
				</a>

				<div class="row" ng-show="DetailsShow">
					<div class="col-xs-offset-1 col-xs-11">
						<?php echo $this->TableList->startTable('table-bordered'); ?>
							<thead>
								<tr>
									<?php echo $this->TableList->tableHeader(null, __d('user_manager', 'Item')); ?>

									<?php echo $this->TableList->tableHeader(null, __d('user_manager', 'Description')); ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($importHelp as $item) : ?>
									<?php echo $this->TableList->startTableRow(); ?>
										<?php echo $this->TableList->tableData(null, $item['title']); ?>

										<?php
											$descriptions = implode('<br>', $item['description']) . '<br>';
											if (isset($item['options'])) {
												$descriptions .= __d('user_manager', '[option]');
												$descriptions .= '<div class="user-maanger-import-help-options pre-scrollable">';
												$descriptions .= implode(
													'',
													array_map(function ($key, $value) {
														return '<div><strong>' . $key . '</strong> : ' . $value . '</div>';
													}, array_keys($item['options']), array_values($item['options']))
												);
												$descriptions .= '</div>';
											}
											echo $this->TableList->tableData(
												null, $descriptions, array('escape' => false)
											);
										?>
									<?php echo $this->TableList->endTableRow(); ?>
								<?php endforeach; ?>
							</tbody>
						<?php echo $this->TableList->endTable(); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="panel-footer text-center">
			<?php echo $this->Button->cancelAndSave(
					__d('net_commons', 'Cancel'),
					__d('net_commons', 'OK'),
					$this->NetCommonsHtml->url(array('action' => 'index'))
				); ?>
		</div>

		<?php echo $this->NetCommonsForm->end(); ?>
	</div>
</article>
