<?php
/**
 * UserAddController::beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserAddController::beforeFilter()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserAddController
 */
class UserAddControllerBeforeFilterTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.plugin4test',
		'plugin.user_attributes.plugins_role4test',
		'plugin.user_attributes.user_attribute4test',
		'plugin.user_attributes.user_attribute_choice4test',
		'plugin.user_attributes.user_attribute_layout',
		'plugin.user_attributes.user_attribute_setting4test',
		'plugin.user_attributes.user_attributes_role4test',
		'plugin.user_roles.user_role',
		'plugin.mails.mail_setting_fixed_phrase',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_manager';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'user_add';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログイン
		TestAuthGeneral::login($this);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * basic()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testBasic() {
		//テスト実行
		$this->_testGetAction(array('action' => 'basic'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$expected = array(
			'navibar' => array(
				'user_manager' => array(
					'url' => array(
						'controller' => 'user_add',
						'action' => 'basic',
					),
					'label' => array(
						'user_manager', 'General setting',
					),
				),
				'users_roles_rooms' => array(
					'url' => array(
						'controller' => 'user_add',
						'action' => 'user_roles_rooms',
						'key2' => '4',
					),
					'label' => array(
						'user_manager', 'Select the rooms to join',
					),
				),
				'user_mail' => array(
					'url' => array(
						'controller' => 'user_add',
						'action' => 'notify',
					),
					'label' => array(
						'user_manager', 'Notify user by e-mail',
					),
				),
			),
			'cancelUrl' => array(
				'controller' => 'user_manager',
				'action' => 'index',
			),
		);
		$this->assertEquals($this->controller->helpers['NetCommons.Wizard'], $expected);
	}

/**
 * notify()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testNotify() {
		//テストコントローラ生成
		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array('Session' => array('read'))
		));

		$user = array(
			'id' => '',
			'email' => '',
			'handlename' => '',
			'username' => '',
		);
		$this->controller->Session
			->expects($this->once())->method('read')
			->will($this->returnValue(array('User' => $user)));

		//テスト実行
		$this->_testGetAction(array('action' => 'notify'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$expected = array(
			'navibar' => array(
				'user_manager' => array(
					'label' => array(
						'user_manager', 'General setting',
					),
				),
				'users_roles_rooms' => array(
					'label' => array(
						'user_manager', 'Select the rooms to join',
					),
				),
				'user_mail' => array(
					'url' => array(
						'controller' => 'user_add',
						'action' => 'notify',
					),
					'label' => array(
						'user_manager', 'Notify user by e-mail',
					),
				),
			),
			'cancelUrl' => array(
				'controller' => 'user_manager',
				'action' => 'index',
			),
		);
		$this->assertEquals($this->controller->helpers['NetCommons.Wizard'], $expected);
	}

}
