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
		'Rooms.RolesRoomsUser',
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
	public function edit($userId = null) {
		//登録処理の場合、URLよりPOSTパラメータでチェックする
		if ($this->request->isPost()) {
			$userId = $this->data['User']['id'];
		}


		if ($this->request->isPost()) {
			//登録処理
			$data = $this->data;

			//--不要パラメータ除去
			unset($data['save']);

			$this->request->data = $data;
		}

		$this->set('activeUserId', $userId);
	}

}
