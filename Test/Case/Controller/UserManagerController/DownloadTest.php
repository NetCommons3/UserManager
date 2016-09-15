<?php
/**
 * UserManagerController::download()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerControllerTestCase', 'UserManager.TestSuite');

/**
 * UserManagerController::download()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Test\Case\Controller\UserManagerController
 */
class UserManagerControllerDownloadTest extends UserManagerControllerTestCase {

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'user_manager';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログイン
		TestAuthGeneral::login($this);

		//事前準備
		$this->_testApp = App::pluginPath('Users') . DS . 'Test' . DS . 'test_app' . DS;
		$this->_testWebroot = $this->_testApp . 'webroot' . DS;
		$this->_testTmp = $this->_testApp . 'tmp' . DS;
		$this->_testUploadPath = $this->_testWebroot . 'files' . DS . 'upload_file' . DS . 'real_file_name' . DS;

		$this->generateNc(Inflector::camelize($this->_controller), array(
			'components' => array(
				'Files.Download' => array('doDownload')
			)
		));
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
 * UserAttributeデータ取得
 *
 * @param string $userId ユーザID
 * @return void
 */
	private function __getAvatarPath($userId) {
		if ($userId === '2') {
			$avatarPath = $this->_testUploadPath .
					'1' . DS . 'thumb_38bfb11bf48fc2f56d2ca2d796d0b0af.gif';

			$this->controller->Download->expects($this->at(0))->method('doDownload')
				->with($userId, array('field' => 'avatar', 'size' => 'thumb'))
				->will($this->returnCallback(function () {
					$this->controller->response->file(
						$this->_testUploadPath . '1' . DS . 'thumb_38bfb11bf48fc2f56d2ca2d796d0b0af.gif'
					);
					return $this->controller->response;
				}));

		} elseif ($userId === '3') {
			$avatarPath = $this->_testUploadPath .
							'2' . DS . 'thumb_7bb5a56eb63531bcb40bda56aafceef3.png';

			$this->controller->Download->expects($this->at(0))->method('doDownload')
				->with($userId, array('field' => 'avatar', 'size' => 'thumb'))
				->will($this->returnCallback(function () {
					$this->controller->response->file(
						$this->_testUploadPath . '2' . DS . 'thumb_7bb5a56eb63531bcb40bda56aafceef3.png'
					);
					return $this->controller->response;
				}));

		} elseif ($userId === '7') {
			$avatarPath = $this->_testTmp . 'avatar' . DS . '5fe6005bf6e415c950c011fb65f12b8f.png';
			$this->_mockForReturn(
				'Users.User', 'temporaryAvatar', $avatarPath
			);

		} elseif ($userId === '1') {
			$avatarPath = $this->_testTmp . 'avatar' . DS . 'a7e1833849089f83c4caabc93a168e99.png';
			$this->_mockForReturn(
				'Users.User', 'temporaryAvatar', $avatarPath
			);
		} else {
			$avatarPath = App::pluginPath('Users') . 'webroot' . DS . 'img' . DS . User::AVATAR_THUMB;
		}

		return $avatarPath;
	}

/**
 * download()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testDownloadGet() {
		//テストデータ
		$userId = '2';
		$avatarPath = $this->__getAvatarPath($userId);

		//テスト実行
		$this->_testGetAction(
			array('action' => 'download', 'user_id' => $userId, 'field_name' => 'avatar', 'size' => 'thumb'),
			null, null, 'view'
		);

		//チェック
		$this->assertEquals($this->controller->response->header()['Content-Length'], filesize($avatarPath));
	}

/**
 * avatarを登録していない場合のテスト(UploadFile.avatar.field_nameがない)
 *
 * @return void
 */
	public function testNoSavedAvatar() {
		//事前準備
		$userId = '1';
		$avatarPath = $this->__getAvatarPath($userId);

		//テスト実行
		$this->_testGetAction(
			array('action' => 'download', 'user_id' => $userId, 'field_name' => 'avatar', 'size' => 'thumb'),
			null, null, 'view'
		);

		//チェック
		$this->assertEquals($this->controller->response->header()['Content-Length'], filesize($avatarPath));
	}

/**
 * 削除されたユーザテスト
 *
 * @return void
 */
	public function testDeleted() {
		//事前準備
		$userId = '7';
		$avatarPath = $this->__getAvatarPath($userId);

		//テスト実行
		$this->_testGetAction(
			array('action' => 'download', 'user_id' => $userId, 'field_name' => 'avatar', 'size' => 'thumb'),
			null, null, 'view'
		);

		//チェック
		$this->assertEquals($this->controller->response->header()['Content-Length'], filesize($avatarPath));
	}

/**
 * 存在しないユーザテスト
 *
 * @return void
 */
	public function testNotExists() {
		//事前準備
		$userId = '99';
		$avatarPath = $this->__getAvatarPath($userId);

		//テスト実行
		$this->_testGetAction(
			array('action' => 'download', 'user_id' => $userId, 'field_name' => 'avatar', 'size' => 'thumb'),
			null, null, 'view'
		);

		//チェック
		$this->assertEquals($this->controller->response->header()['Content-Length'], filesize($avatarPath));
	}

}
