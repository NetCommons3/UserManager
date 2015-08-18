<?php
/**
 * UserManager Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerAppController', 'UserManager.Controller');

/**
 * UserManager Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller
 */
class UserManagerController extends UserManagerAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Users.User',
		'Users.UsersLanguage',
		'UserRoles.UserRole',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout',
		'M17n.SwitchLanguage',
		'Users.UsersSearch',
		'UserAttributes.UserAttributeLayouts',
	);

/**
 * index
 *
 * @return void
 */
	public function index() {
		$this->helpers[] = 'Users.UserSearchForm';
CakeLog::debug(print_r($this->viewVars['userAttributes'], true));

		//unset();
	}

/**
 * view
 *
 * @return void
 */
	public function search() {
		$this->helpers[] = 'Users.UserSearchForm';
	}

/**
 * add
 *
 * @return void
 */
	public function add() {
		$this->view = 'edit';
		$this->helpers[] = 'Users.UserEditForm';

		if ($this->request->isPost()) {
			//不要パラメータ除去
			$data = $this->data;
			unset($data['save'], $data['active_lang_id']);

			//登録処理
			if ($user = $this->User->saveUser($data, true)) {
				//正常の場合
				$this->redirect('/user_manager/users_roles_rooms/edit/' . $user['User']['id'] . '/');
				return;
			}
			$this->handleValidationError($this->User->validationErrors);

			$this->request->data = $data;

		} else {
			//表示処理
			$this->request->data = $this->User->createUser();
		}

		$this->set('userName', '');
	}

/**
 * edit
 *
 * @param int $userId users.id
 * @return void
 */
	public function edit($userId = null) {
		$this->helpers[] = 'Users.UserEditForm';

		if ($this->request->isPut()) {
			//不要パラメータ除去
			$data = $this->data;
			unset($data['save'], $data['active_lang_id']);

			//登録処理
			if ($user = $this->User->saveUser($data, false)) {
				//正常の場合
				$this->setFlashNotification(__d('net_commons', 'Successfully saved.'), array('class' => 'success'));
				$this->redirect('/user_manager/user_manager/edit/' . $user['User']['id'] . '/');
				return;
			}

			var_dump($this->User->validationErrors);
			$this->handleValidationError($this->User->validationErrors);

			$this->request->data = $data;

		} else {
			//表示処理
			$this->request->data = $this->User->getUser($userId);
		}

		$this->set('userName', $this->request->data['User']['handlename']);
		$this->set('activeUserId', $userId);
	}

/**
 * delete
 *
 * @param int $userId users.id
 * @return void
 */
	public function delete($userId = null) {
	}
}
