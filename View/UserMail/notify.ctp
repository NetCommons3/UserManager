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
?>

<?php echo $this->element('UserManager.subtitle'); ?>
<?php
	if ($this->params['action'] === 'save_notify') {
		echo $this->Wizard->navibar(UserManagerAppController::WIZARD_MAIL);
		echo $this->MessageFlash->description(__d('user_manager', 'Press [OK] to notify the user.'));
	}
?>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create('UserMail'); ?>
		<div class="panel-body">
			<?php echo $this->NetCommonsForm->hidden('UserMail.user_id'); ?>
			<?php echo $this->NetCommonsForm->input('UserMail.to_address', array(
					'type' => 'text',
					'label' => __d('user_manager', 'To mail address'),
					'disabled' => true,
					'required' => true,
					'value' => $user['email']
				)); ?>

			<?php echo $this->NetCommonsForm->input('UserMail.reply_to', array(
					'type' => 'text',
					'label' => __d('user_manager', 'Reply to mail address'),
				)); ?>

			<?php echo $this->NetCommonsForm->input('UserMail.title', array(
					'type' => 'text',
					'label' => __d('user_manager', 'Mail title'),
					'required' => true
				)); ?>

			<?php echo $this->NetCommonsForm->input('UserMail.body', array(
					'type' => 'textarea',
					'label' => __d('user_manager', 'Mail body'),
					'required' => true
				)); ?>
		</div>

		<div class="panel-footer text-center">
			<?php
				if ($this->params['action'] === 'save_notify') {
					echo $this->Wizard->buttons(UserManagerAppController::WIZARD_MAIL);
				} else {
					echo $this->Button->cancel(
						__d('net_commons', 'Cancel'),
						$this->NetCommonsHtml->url(array('controller' => 'user_manager', 'action' => 'index'))
					);

					echo $this->NetCommonsForm->button(
						'<span class="glyphicon glyphicon-envelope"></span> ' . __d('user_manager', 'Send'),
						array(
							'class' => 'btn btn-primary btn-workflow',
							'name' => 'send',
							'ng-disabled' => 'sending'
						)
					);
				}
			?>
		</div>
	<?php echo $this->NetCommonsForm->end(); ?>
</div>
