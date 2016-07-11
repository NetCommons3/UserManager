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
		//ユーザデータ取得
		if ($this->request->is('post')) {
			$userId = $this->data['User']['id'];
		} else {
			$userId = $this->params['pass'][0];
		}
		$user = $this->User->getUser($userId);
		if (! $user) {
			return $this->throwBadRequest();
		}

		$this->set('user', $user['User']);
		$this->set('userName', $user['User']['handlename']);
		$this->set('activeUserId', $userId);

		if ($this->request->is('post')) {
			//登録処理
			$result = $this->RolesRoomsUser->saveRolesRoomsUsersForUsers($this->request->data);
			if ($result) {
				//正常処理
				$this->NetCommons->setFlashNotification(__d('net_commons', 'Successfully saved.'), array(
					'class' => 'success',
				));
				return $this->redirect('/user_manager/user_manager/index');
			} else {
				//異常処理
				$this->NetCommons->handleValidationError($this->RolesRoomsUser->validationErrors);
			}
		}

		//** ルームデータセット
		$this->Rooms->setRoomsForPaginator();

		//** ロールデータセット
		$this->viewVars['defaultRoles'][''] = __d('users', 'Non members');

		//** ルームロールデータ取得
		$rolesRooms = $this->Room->getRolesRoomsNotInDraft(array(
			'Room.space_id' => [Space::PUBLIC_SPACE_ID, Space::ROOM_SPACE_ID]
		));
		$rolesRooms = Hash::combine($rolesRooms, '{n}.RolesRoom.role_key', '{n}', '{n}.Room.id');
		$this->set('rolesRooms', $rolesRooms);

		//** ロールルームユーザデータ取得
		$rolesRoomsUsers = $this->RolesRoomsUser->getRolesRoomsUsers(array(
			'RolesRoomsUser.user_id' => $userId,
		));
		$rolesRoomsUsers['RolesRoomsUser'] = Hash::combine(
			$rolesRoomsUsers, '{n}.RolesRoomsUser.room_id', '{n}.RolesRoomsUser'
		);
		$this->set('rolesRoomsUsers', $rolesRoomsUsers);
	}

}
