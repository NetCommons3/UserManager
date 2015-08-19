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

App::uses('UsersAppController', 'Users.Controller');

/**
 * RolesRoomsUser Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Users\Controller
 */
class UsersRolesRoomsController extends UsersAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Pages.Page',
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
 * edit
 *
 * @param int $userId users.id
 * @return void
 */
	public function edit($userId = null, $spaceId = null) {
		//登録処理の場合、URLよりPOSTパラメータでチェックする
		if ($this->request->isPost()) {
			$userId = $this->data['User']['id'];
		}

		//スペースデータチェック＆セット
		if (! $this->SpacesUtility->validSpace($spaceId)) {
			return;
		}

		if ($this->request->isPost()) {
			//登録処理
			$data = $this->data;

			//--不要パラメータ除去
			unset($data['save']);

			$this->request->data = $data;
		}

		//ルームデータ取得
		$rooms = $this->RoomsUtility->getRoomsForPaginator($spaceId, $this->viewVars['space']['Room']['id']);
		$this->set('rooms', $rooms);

		//ロールデータ取得
		$roomRoles = $this->Role->find('list', array(
			'fields' => array('key', 'name'),
			'conditions' => array(
				'is_systemized' => true,
				'language_id' => Configure::read('Config.languageId'),
				'type' => Role::ROLE_TYPE_ROOM
			),
			'order' => array('id' => 'asc')
		));
		$this->set('roomRoles', $roomRoles);

		//ユーザデータ取得
		$user = $this->User->getUser($userId);
		$this->set('userName', $user['User']['handlename']);

		$this->set('activeUserId', $userId);
	}

}
