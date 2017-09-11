<?php
/**
 * UserAddController::basic()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerControllerTestCase', 'UserManager.TestSuite');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');

/**
 * UserAddController::basic()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserAddController
 */
class UserAddControllerBasicTest extends UserManagerControllerTestCase {

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
	public function testBasicGet() {
		//テスト実行
		$this->_testGetAction(array('action' => 'basic'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assertCreateUser();
		$this->__assertInputs();
	}

/**
 * ごみデータ(Avatar)の削除テスト
 *
 * @return void
 */
	public function testTmpAvatarDelete() {
		//事前準備
		$avatarPath = TMP . 'logo.gif';
		//$this->assertFalse(file_exists($avatarPath));

		$tmpName = App::pluginPath('UserManager') . 'Test' . DS . 'Fixture' . DS . 'logo.gif';
		(new File($tmpName))->copy($avatarPath);
		$this->assertTrue(file_exists($avatarPath));

		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array('Session' => array('read'))
		));
		//ログイン
		TestAuthGeneral::login($this);

		if (Configure::read('debug')) {
			$exactly = 2;
		} else {
			$exactly = 1;
		}
		$this->controller->Session
			->expects($this->exactly($exactly))->method('read')
			->will($this->returnCallback(function ($key) use ($avatarPath) {
				if ($key === 'UserAdd.User.avatar.tmp_name') {
					return $avatarPath;
				} else {
					return null;
				}
			}));

		//テスト実行
		$this->_testGetAction(array('action' => 'basic'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assertCreateUser();
		$this->__assertInputs();
		$this->assertFalse(file_exists($avatarPath));
	}

/**
 * Refererテスト
 *
 * @return void
 */
	public function testReferer() {
		//事前準備
		$_SERVER['HTTP_REFERER'] = Router::url(
			$this->controller->request->webroot . '/user_manager/user_add/user_roles_rooms', true
		);
		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array('Session' => array('read'))
		));
		//ログイン
		TestAuthGeneral::login($this);

		$data = $this->__data();
		if (Configure::read('debug')) {
			$exactly = 2;
		} else {
			$exactly = 1;
		}
		$this->controller->Session
			->expects($this->exactly($exactly))->method('read')
			->will($this->returnCallback(function ($key) use ($data) {
				if ($key === 'UserAdd') {
					return $data;
				} else {
					return null;
				}
			}));

		//テスト実行
		$this->_testGetAction(array('action' => 'basic'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->assertEquals($this->controller->data, $this->__data());
		$this->__assertInputs();
	}

/**
 * ValidateUserテスト
 *
 * @return void
 */
	public function testValidateUser() {
		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array('Session' => array('write'))
		));
		//ログイン
		TestAuthGeneral::login($this);

		//テストデータ
		$this->_mockForReturnTrue('Users.User', 'validateUser');
		$this->controller->Components->Session
			->expects($this->once())->method('write')
			->with('UserAdd', $this->__data());

		//テストアクション実行
		$this->_testPostAction('post', $this->__data(), array('action' => 'basic'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertNotEmpty($header['Location']);
	}

/**
 * アバターも含めたValidateUserテスト
 *
 * @return void
 */
	public function testValidateUserWithAvatar() {
		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array(
				'Session' => array('write'),
				'UserManager.UserManager' => array(
					'setUserRoleAdminOnBasic', 'setStatusOnBasic', 'prepareBasicSave', 'moveUploadedFile'
				)
			)
		));

		//ログイン
		TestAuthGeneral::login($this);

		//事前準備
		$uploadPath = TMP . 'uploads';
		$avatarPath = $uploadPath . DS . 'logo.gif';

		$tmpName = App::pluginPath('UserManager') . 'Test' . DS . 'Fixture' . DS . 'logo.gif';
		(new Folder())->create($uploadPath);
		(new File($tmpName))->copy($avatarPath);
		$this->assertTrue(file_exists($avatarPath));

		//テストデータ
		$data = $this->__data();
		$data = Hash::insert($data, 'User.avatar', array(
			'name' => 'logo.gif',
			'type' => 'image/png',
			'tmp_name' => $avatarPath,
			'error' => 0,
			'size' => filesize($tmpName)
		));
		$this->_mockForReturnTrue('Users.User', 'validateUser');

		$expected = Hash::insert($data, 'User.avatar.tmp_name', TMP . 'logo.gif');
		$this->controller->Components->Session
			->expects($this->once())->method('write')
			->with('UserAdd', $expected);

		$this->controller->Components->UserManager
			->expects($this->once())->method('moveUploadedFile')
			->will($this->returnCallback(function ($tmpName, $destPath) {
				(new File($tmpName))->copy($destPath);
				(new File($tmpName))->delete();
				return true;
			}));

		//テストアクション実行
		$this->_testPostAction('post', $data, array('action' => 'basic'), null, 'view');

		//チェック
		$this->assertFalse(file_exists($avatarPath));
		$this->assertTrue(file_exists(TMP . 'logo.gif'));

		//後処理
		(new File(TMP . 'logo.gif'))->delete();
	}

/**
 * ValidateUserテスト
 *
 * @return void
 */
	public function testValidationError() {
		//テストアクション実行
		$this->_testPostAction('post', $this->__data(), array('action' => 'basic'), null, 'view');

		//チェック
		$this->__assertInputs();
		$this->assertTextContains(
			sprintf(__d('net_commons', 'Please input %s.'), __d('users', 'username')),
			$this->view
		);
	}

/**
 * データ取得
 *
 * @return void
 */
	private function __data() {
		$data = array(
			'UsersLanguage' => array(
				0 => array(
					'user_id' => '',
					'language_id' => 2,
					'name' => null,
					'profile' => null,
					'search_keywords' => null,
					'id' => null,
				),
				1 => array(
					'user_id' => '',
					'language_id' => 1,
					'name' => null,
					'profile' => null,
					'search_keywords' => null,
					'id' => null,
				),
			),
			'User' => array(
				'username' => '',
				'password' => null,
				'key' => null,
				'activate_key' => null,
				'activated' => null,
				'handlename' => null,
				'email' => null,
				'moblie_mail' => null,
				'sex' => null,
				'language' => 'auto',
				'timezone' => 'Asia/Tokyo',
				'role_key' => 'common_user',
				'status' => null,
				'created' => null,
				'modified' => null,
				'password_modified' => null,
				'last_login' => null,
				'previous_login' => null,
				'id' => null,
			),
		);
		return $data;
	}

/**
 * $this->User->createUser()のチェック
 *
 * @return void
 */
	private function __assertCreateUser() {
		//チェック
		$expected = array(
			'UsersLanguage' => array(
				0 => array(
					'user_id' => '',
					'language_id' => 1,
					'name' => null,
					'profile' => null,
					'search_keywords' => null,
					'id' => null,
				),
				1 => array(
					'user_id' => '',
					'language_id' => 2,
					'name' => null,
					'profile' => null,
					'search_keywords' => null,
					'id' => null,
				),
			),
			'User' => array(
				'is_deleted' => '0',
				'is_avatar_public' => '0',
				'is_avatar_auto_created' => '1',
				'is_handlename_public' => '0',
				'is_name_public' => '0',
				'is_email_public' => '0',
				'is_email_reception' => '1',
				'is_moblie_mail_public' => '0',
				'is_moblie_mail_reception' => '1',
				'is_sex_public' => '0',
				'is_language_public' => '0',
				'is_timezone_public' => '0',
				'is_role_key_public' => '0',
				'is_status_public' => '0',
				'is_created_public' => '0',
				'created_user' => '0',
				'is_created_user_public' => '0',
				'is_modified_public' => '0',
				'modified_user' => '0',
				'is_modified_user_public' => '0',
				'is_password_modified_public' => '0',
				'is_last_login_public' => '0',
				'is_previous_login_public' => '0',
				'is_profile_public' => '0',
				'is_search_keywords_public' => '0',
				'username' => '',
				'password' => null,
				'key' => null,
				'activate_key' => null,
				'activated' => null,
				'handlename' => null,
				'email' => null,
				'moblie_mail' => null,
				'sex' => null,
				'language' => 'auto',
				'timezone' => 'Asia/Tokyo',
				'role_key' => 'common_user',
				'status' => null,
				'created' => null,
				'modified' => null,
				'password_modified' => null,
				'last_login' => null,
				'previous_login' => null,
				'id' => null,
			),
		);
		$this->assertEquals($this->controller->data, $expected);
	}

/**
 * $this->User->createUser()のチェック
 *
 * @return void
 */
	private function __assertInputs() {
		//チェック
		$this->assertInput('form', null, '/user_manager/user_add/basic', $this->view);
		$this->assertTextContains(
			'<a class="nc-switch-language" href="#users-1" role="tab" data-toggle="tab"',
			$this->view
		);
		$this->assertTextContains(
			'<a class="nc-switch-language" href="#users-2" role="tab" data-toggle="tab"',
			$this->view
		);
		$this->assertInput('input', 'data[User][id]', null, $this->view);
		$this->assertInput('input', 'data[UsersLanguage][0][id]', null, $this->view);
		$this->assertInput('input', 'data[UsersLanguage][1][id]', null, $this->view);
		$this->assertInput('input', 'data[UsersLanguage][0][language_id]', '2', $this->view);
		$this->assertInput('input', 'data[UsersLanguage][1][language_id]', '1', $this->view);
		$this->assertInput('input', 'data[UsersLanguage][0][user_id]', '', $this->view);
		$this->assertInput('input', 'data[UsersLanguage][1][user_id]', '', $this->view);
		$this->assertInput('input', 'data[User][handlename]', '', $this->view);
	}

}
