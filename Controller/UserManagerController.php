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
			$this->request->data['UsersLanguage'] = array();
			foreach (array_keys($this->viewVars['languages']) as $langId) {
				$index = count($this->request->data['UsersLanguage']);

				$usersLanguage = $this->UsersLanguage->create(array(
					'id' => null,
					'language_id' => $langId,
				));
				$this->request->data['UsersLanguage'][$index] = $usersLanguage['UsersLanguage'];
			}
			$this->request->data = Hash::merge($this->request->data,
				$this->User->create(array(
					'id' => null,
					'role_key' => UserRole::USER_ROLE_KEY_COMMON_USER
				))
			);
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
