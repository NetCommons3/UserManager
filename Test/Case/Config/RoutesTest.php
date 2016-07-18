<?php
/**
 * Config/routes.phpのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsRoutesTestCase', 'NetCommons.TestSuite');

/**
 * Config/routes.phpのテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Pages\Test\Case\Routing\Route\SlugRoute
 */
class RoutesTest extends NetCommonsRoutesTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_manager';

/**
 * DataProvider
 *
 * ### 戻り値
 * - url URL
 * - expected 期待値
 *
 * @return array テストデータ
 */
	public function dataProvider() {
		return array(
			array(
				'url' => '/user_manager/user_manager/view/1',
				'expected' => array(
					'plugin' => 'user_manager', 'controller' => 'user_manager', 'action' => 'view',
					'user_id' => '1',
				)
			),
			array(
				'url' => '/user_manager/user_manager/edit/1',
				'expected' => array(
					'plugin' => 'user_manager', 'controller' => 'user_manager', 'action' => 'edit',
					'user_id' => '1',
				)
			),
			array(
				'url' => '/user_manager/user_manager/index',
				'expected' => array(
					'plugin' => 'user_manager', 'controller' => 'user_manager', 'action' => 'index',
				)
			),
			array(
				'url' => '/user_manager/users_roles_rooms/edit/1',
				'expected' => array(
					'plugin' => 'user_manager', 'controller' => 'users_roles_rooms', 'action' => 'edit',
					'user_id' => '1',
				)
			),
			array(
				'url' => '/user_manager/user_add/basic',
				'expected' => array(
					'plugin' => 'user_manager', 'controller' => 'user_add', 'action' => 'basic',
				)
			),
		);
	}

}
