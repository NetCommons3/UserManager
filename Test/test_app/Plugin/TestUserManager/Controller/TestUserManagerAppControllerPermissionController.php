<?php
/**
 * アクセス権限(Permission)テスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerAppController', 'UserManager.Controller');

/**
 * アクセス権限(Permission)テスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\test_app\Plugin\TestUserManager\Controller
 */
class TestUserManagerAppControllerPermissionController extends UserManagerAppController {

/**
 * index
 *
 * @return void
 */
	public function index() {
		$this->autoRender = true;
	}

}
