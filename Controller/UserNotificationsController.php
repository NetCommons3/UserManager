<?php
/**
 * UserNotifications Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerAppController', 'UserManager.Controller');

/**
 * UserNotifications Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Users\Controller
 */
class UserNotificationsController extends UserManagerAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Users.User',
		'UserManager.UserMail',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout',
	);

/**
 * メール通知
 *
 * @param int $userId users.id
 * @return void
 */
	public function email($userId = null) {
		//登録処理の場合、URLよりPOSTパラメータでチェックする
		if ($this->request->isPost()) {
			$userId = $this->data['UserMail']['user_id'];
		}
		if (! $this->User->existsUser($userId)) {
			return $this->throwBadRequest();
		}

		//ユーザデータ取得
		$user = $this->User->getUser($userId);
		$this->set('user', $user['User']);
		$this->set('userName', $user['User']['handlename']);
		$this->set('activeUserId', $userId);

		if ($this->request->isPost()) {
			//--不要パラメータ除去
			unset($this->request->data['send']);

			if ($this->UserMail->saveMail($this->request->data)) {
				$this->NetCommons->setFlashNotification(__d('net_commons', 'Successfully saved.'), array('class' => 'success'));
				return $this->redirect('/user_manager/user_manager/index/');
			}
			$this->NetCommons->handleValidationError($this->UserMail->validationErrors);

		} else {
			$this->request->data['UserMail']['title'] = '';
			$this->request->data['UserMail']['body'] = '';
			$this->request->data['UserMail']['user_id'] = $user['User']['id'];
			$this->request->data['UserMail']['reply_to'] = Current::read('User.email');
		}
	}

/**
 * 登録メール通知
 *
 * @param int $userId users.id
 * @return void
 */
	public function additional_notify($userId = null) {

	}


}
