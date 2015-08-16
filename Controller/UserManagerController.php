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
	//public $uses = array();

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
 * use component
 *
 * @var array
 */
	public $helpers = array(
		'Users.UserSearchForm',
		'Users.UserEditForm',
	);

/**
 * index
 *
 * @return void
 */
	public function index() {
CakeLog::debug(print_r($this->viewVars['userAttributes'], true));

	}

/**
 * view
 *
 * @return void
 */
	public function search() {
	}

/**
 * add
 *
 * @return void
 */
	public function add() {
		$this->view = 'edit';

		$this->set('userName', '');
	}

/**
 * edit
 *
 * @param int $userId users.id
 * @return void
 */
	public function edit($userId = null) {
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
