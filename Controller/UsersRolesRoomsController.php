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
		'Pages.Page',
		'Roles.Role',
		'Rooms.RolesRoom',
		'Rooms.RolesRoomsUser',
		'Rooms.RoomsLanguage',
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
		'Rooms.RoomsUtility',
		'Rooms.SpacesUtility',
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
 * @param int $userId users.id
 * @param int $spaceId spaces.id
 * @return void
 */
	public function edit($userId = null, $spaceId = null) {
		//登録処理の場合、URLよりPOSTパラメータでチェックする
		if ($this->request->isPost()) {
			$userId = $this->data['RolesRoomsUser']['user_id'];
		}

		//スペースデータチェック＆セット
		if (! $this->SpacesUtility->validSpace($spaceId)) {
			return;
		}

		if ($this->request->isPost()) {
			//登録処理

			//--不要パラメータ除去
			$data = $this->data;
			unset($data['save']);

			if ($data['RolesRoomsUser']['roles_room_id']) {
				$result = $this->RolesRoomsUser->saveRolesRoomsUser($data);
			} else {
				$result = $this->RolesRoomsUser->deleteRolesRoomsUser($data);
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
		}

		//ルームデータ取得
		$rooms = $this->RoomsUtility->getRoomsForPaginator($spaceId, $this->viewVars['space']['Room']['id']);
		$this->set('rooms', $rooms);

		//ロールデータ取得
		$roles = $this->Role->find('list', array(
			'fields' => array('key', 'name'),
			'conditions' => array(
				'is_systemized' => true,
				'language_id' => Configure::read('Config.languageId'),
				'type' => Role::ROLE_TYPE_ROOM
			),
			'order' => array('id' => 'asc')
		));
		$roles[''] = __d('users', 'Non members');

		$this->set('roles', $roles);

		//ルームロールデータ取得
		$rolesRooms = $this->RolesRoom->getRolesRooms(array(
				'Room.space_id' => $spaceId
			));
		$rolesRooms = Hash::combine($rolesRooms, '{n}.RolesRoom.role_key', '{n}', '{n}.Room.id');
		$this->set('rolesRooms', $rolesRooms);

		//ユーザデータ取得
		$user = $this->User->getUser($userId);
		$this->set('userName', $user['User']['handlename']);
		$this->set('activeUserId', $userId);

		//ロールルームユーザデータ取得
		$rolesRoomsUsers = $this->RolesRoomsUser->getRolesRoomsUsers(array(
				'RolesRoomsUser.user_id' => $userId,
				'Room.space_id' => $spaceId
			));
		$rolesRoomsUsers = Hash::combine($rolesRoomsUsers, '{n}.Room.id', '{n}');

		$this->set('rolesRoomsUsers', $rolesRoomsUsers);
	}

}
