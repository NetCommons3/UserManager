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
App::uses('Space', 'Rooms.Model');

/**
 * UserManager Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller
 */
class UserManagerController extends UserManagerAppController {

/**
 * 会員一覧の表示する項目
 *
 * @var const
 */
	public static $displaFields = array(
		'handlename',
		'name',
		'role_key',
		'status',
		'modified',
		'last_login'
	);

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Users.User'
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'Files.FileUpload',
		'M17n.SwitchLanguage',
		'Rooms.Rooms',
		'UserAttributes.UserAttributeLayout',
		'Users.UserSearchComp',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'UserAttributes.UserAttributeLayout',
		'Users.UserLayout',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Security->unlockedActions = array('search');
	}

/**
 * indexアクション
 *
 * @return void
 */
	public function index() {
		$this->helpers[] = 'Users.UserSearchForm';

		//ユーザ一覧データ取得
		$this->UserSearchComp->search([
			'fields' => self::$displaFields,
			'conditions' => array('space_id' => Space::PRIVATE_SPACE_ID),
			'joins' => array('Room' => array(
				'conditions' => array(
					'Room.page_id_top NOT' => null,
				)
			))
		]);

		$this->helpers[] = 'Users.UserSearch';
	}

/**
 * 検索フォーム表示アクション
 *
 * @return void
 */
	public function search_conditions() {
		//検索フォーム表示
		$this->UserSearchComp->conditions();
	}

/**
 * view method
 *
 * @return void
 */
	public function view() {
		$userId = $this->params['pass'][0];
		$user = $this->User->getUser($userId);
		if (! $user || $user['User']['is_deleted']) {
			return $this->throwBadRequest();
		}
		$this->set('user', $user);
		$this->set('title', false);

		//レイアウトの設定
		$this->viewClass = 'View';
		$this->layout = 'NetCommons.modal';

		//ルームデータ取得
		$this->Rooms->setReadableRooms($userId);
	}

/**
 * editアクション
 *
 * @return void
 */
	public function edit() {
		$this->helpers[] = 'Users.UserEditForm';

		//システム管理者以外は、選択肢からシステム管理者を除外
		if (Current::read('User.role_key') !== UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR) {
			$this->viewVars['userAttributes'] = Hash::remove(
				$this->viewVars['userAttributes'],
				'{n}.{n}.{n}.UserAttributeChoice.{n}[key=' . UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR . ']'
			);
		}

		if ($this->request->is('put')) {
			$userId = $this->data['User']['id'];
		} else {
			$userId = $this->params['pass'][0];
		}
		$user = $this->User->getUser($userId);

		//編集できるかどうかチェック
		if (! $this->User->canUserEdit($user)) {
			return $this->throwBadRequest();
		}
		$this->set('canUserDelete', $this->User->canUserDelete($user));

		//状態の選択肢から承認待ち、承認済みを除外
		if (Hash::get($user, 'User.status') !== UserAttributeChoice::STATUS_CODE_WAITING) {
			$this->viewVars['userAttributes'] = Hash::remove(
				$this->viewVars['userAttributes'],
				'{n}.{n}.{n}.UserAttributeChoice.{n}[key=' . UserAttributeChoice::STATUS_KEY_WAITING . ']'
			);
		}
		if (Hash::get($user, 'User.status') !== UserAttributeChoice::STATUS_CODE_APPROVED) {
			$this->viewVars['userAttributes'] = Hash::remove(
				$this->viewVars['userAttributes'],
				'{n}.{n}.{n}.UserAttributeChoice.{n}[key=' . UserAttributeChoice::STATUS_KEY_APPROVED . ']'
			);
		}

		if ($this->request->is('put')) {
			//不要パラメータ除去
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			//登録処理
			$this->_prepareSave();

			if ($this->User->saveUser($this->request->data)) {
				$this->NetCommons->setFlashNotification(
					__d('net_commons', 'Successfully saved.'), array('class' => 'success')
				);
				return $this->redirect('/user_manager/user_manager/index');
			}
			$this->NetCommons->handleValidationError($this->User->validationErrors);
			$this->request->data = Hash::merge($user, $this->request->data);

		} else {
			//表示処理
			$this->User->languages = $this->viewVars['languages'];
			$this->request->data = $user;
		}

		$this->set('user', $user['User']);
		$this->set('userName', $this->request->data['User']['handlename']);
		$this->set('activeUserId', $userId);
	}

/**
 * deleteアクション
 *
 * @return void
 */
	public function delete() {
		$user = $this->User->getUser($this->data['User']['id']);

		//削除できるかチェック
		if (! $this->User->canUserDelete($user)) {
			return $this->throwBadRequest();
		}

		if (! $this->request->is('delete')) {
			return $this->throwBadRequest();
		}

		$this->User->deleteUser($user);
		$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
	}

/**
 * importアクション
 *
 * @return void
 */
	public function import() {
		if ($this->request->is('post')) {
			$file = $this->FileUpload->getTemporaryUploadFile('import_csv');
			if (! $this->User->importUsers($file)) {
				//バリデーションエラーの場合
				$this->NetCommons->handleValidationError($this->User->validationErrors);
				return;
			}

			$this->NetCommons->setFlashNotification(
				__d('net_commons', 'Successfully saved.'), array('class' => 'success')
			);
			$this->redirect('/user_manager/user_manager/index/');
		}
	}

/**
 * exportアクション
 *
 * @return void
 */
	public function export() {
		if (Hash::check($this->request->query, 'pass')) {
			App::uses('CsvFileWriter', 'Files.Utility');

			$csvWriter = $this->User->exportUsers();
			if (! $csvWriter) {
				//バリデーションエラーの場合
				$this->NetCommons->handleValidationError($this->User->validationErrors);
				return;
			}

			return $csvWriter->zipDownload(
				'export_user.zip', 'export_user.csv', $this->request->query['pass']
			);
		}
	}
}
