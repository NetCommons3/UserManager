<?php
/**
 * RolesRoomsUser Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerAppController', 'UserManager.Controller');

/**
 * RolesRoomsUser Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Users\Controller
 */
class UsersRolesRoomsController extends UserManagerAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Rooms.RolesRoomsUser',
		'Rooms.Room',
		'Users.User',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout',
		'Rooms.Rooms',
		'UserRoles.UserRoleForm',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Token',
	);

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		list($userId, $spaceId) = $this->params['pass'];

		//スペースのチェック
		if (! isset($this->viewVars['spaces'][$spaceId])) {
			$this->throwBadRequest();
			return;
		}

		//ユーザデータ取得
		if ($this->request->isPost()) {
			$userId = $this->data['RolesRoomsUser']['user_id'];
		}
		$user = $this->User->getUser($userId);
		if (! $user) {
			$this->throwBadRequest();
			return;
		}
		$this->set('userName', $user['User']['handlename']);
		$this->set('activeUserId', $userId);

		if ($this->request->isPost()) {
			//登録処理
			if ($this->data['RolesRoomsUser']['roles_room_id']) {
				$result = $this->RolesRoomsUser->saveRolesRoomsUser($this->data);
			} else {
				$result = $this->RolesRoomsUser->deleteRolesRoomsUser($this->data);
			}
			if ($result) {
				$this->NetCommons->setFlashNotification(__d('net_commons', 'Successfully saved.'), array(
					'class' => 'success',
					'rolesRoomsUser' => array('id' => $result['RolesRoomsUser']['id']),
				));
			} else {
				$this->NetCommons->handleValidationError($this->RolesRoomsUser->validationErrors);
			}
			return;

		} else {
			//表示処理
			//** ルームデータセット
			$this->set('activeSpaceId', $spaceId);
			$this->Rooms->setRoomsForPaginator($spaceId);

			//** ロールデータセット
			$this->viewVars['defaultRoles'][''] = __d('users', 'Non members');

			//** ルームロールデータ取得
			$rolesRooms = $this->Room->getRolesRooms(array(
				'Room.space_id' => $spaceId
			));
			$rolesRooms = Hash::combine($rolesRooms, '{n}.RolesRoom.role_key', '{n}', '{n}.Room.id');
			$this->set('rolesRooms', $rolesRooms);

			//** ロールルームユーザデータ取得
			$rolesRoomsUsers = $this->RolesRoomsUser->getRolesRoomsUsers(array(
				'RolesRoomsUser.user_id' => $userId,
				'Room.space_id' => $spaceId
			));
			$rolesRoomsUsers = Hash::combine($rolesRoomsUsers, '{n}.Room.id', '{n}');
			$this->set('rolesRoomsUsers', $rolesRoomsUsers);
		}
	}

}
