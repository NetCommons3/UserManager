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
 * edit
 *
 * @param int $userId users.id
 * @return void
 */
	public function email($userId = null) {
		//登録処理の場合、URLよりPOSTパラメータでチェックする
		if ($this->request->isPost()) {
			$userId = $this->data['RolesRoomsUser']['user_id'];
		}

		if ($this->request->isPost()) {
			//登録処理

			//--不要パラメータ除去
			$data = $this->data;
			unset($data['send']);

		} else {
			$this->request->data['title'] = '';
			$this->request->data['body'] = '';
		}

		//ユーザデータ取得
		$user = $this->User->getUser($userId);
		$this->set('userName', $user['User']['handlename']);
		$this->set('activeUserId', $userId);
	}

}
