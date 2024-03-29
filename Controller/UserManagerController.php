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
App::uses('ImportExportBehavior', 'Users.Model/Behavior');
App::uses('User', 'Users.Model');

/**
 * UserManager Controller
 *
 * @property AutoUserRegist $AutoUserRegist
 * @property AutoUserRegistMail $AutoUserRegistMail
 * @property User $User
 * @property UserSearch $UserSearch
 * @property DownloadComponent $Download
 * @property FileUploadComponent $FileUpload
 * @property SwitchLanguageComponent $SwitchLanguage
 * @property RoomsComponent $Rooms
 * @property UserAttributeLayoutComponent $UserAttributeLayout
 * @property UserManagerComponent $UserManager
 * @property UserManagerBulkComponent $UserManagerBulk
 * @property UserSearchCompComponent $UserSearchComp
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
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
		'Auth.AutoUserRegist',
		'Auth.AutoUserRegistMail',
		'Users.User',
		'Users.UserSearch'
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'Files.Download',
		'Files.FileUpload',
		'M17n.SwitchLanguage',
		'Rooms.Rooms',
		'UserAttributes.UserAttributeLayout',
		'UserManager.UserManager',
		'UserManager.UserManagerBulk',
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
		'NetCommons.TableList'
	);

/**
 * indexアクション
 *
 * @return void
 */
	public function index() {
		$this->helpers[] = 'Users.UserSearchForm';

		//ユーザ一覧データ取得
		$this->UserSearchComp->search([
			'fields' => array_merge(['origin_role_key'], self::$displaFields),
			'displayFields' => self::$displaFields,
			'conditions' => array('space_id !=' => Space::PRIVATE_SPACE_ID),
			'joins' => array('Room' => array(
				'conditions' => array(
					'Room.page_id_top NOT' => null,
				)
			))
		]);

		$queries = $this->request->query;
		if (! empty($queries)) {
			$this->Session->write(self::USER_MANAGER_SEARCH_CONDITIONS, $queries);
		} else {
			$this->Session->delete(self::USER_MANAGER_SEARCH_CONDITIONS);
		}

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
		$userId = $this->params['user_id'];
		$user = $this->User->getUser($userId);
		if (! $user || $user['User']['is_deleted']) {
			return $this->throwBadRequest();
		}
		$this->set('canUserEdit', $this->User->canUserEdit($user));

		$this->set('user', $user);
		$this->set('title', false);
		$this->set('pluginName', Current::read('Plugin.name'));

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
		$this->UserManager->setUserRoleAdminOnBasic();

		if ($this->request->is('put')) {
			$userId = $this->data['User']['id'];
		} else {
			$userId = $this->params['user_id'];
		}
		$user = $this->User->getUser($userId);

		//編集できるかどうかチェック
		if (! $this->User->canUserEdit($user)) {
			return $this->throwBadRequest();
		}
		$this->set('canUserDelete', $this->User->canUserDelete($user));

		//状態の選択肢から承認待ち、承認済みを除外
		$this->UserManager->setStatusOnBasic($user);

		if ($this->request->is('put')) {
			//不要パラメータ除去
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			//登録処理
			$this->UserManager->prepareBasicSave();

			if ($this->User->saveUser($this->request->data)) {
				$this->NetCommons->setFlashNotification(
					__d('net_commons', 'Successfully saved.'), array('class' => 'success')
				);
				$user = $this->User->getUser($userId);
			} else {
				$this->NetCommons->handleValidationError($this->User->validationErrors);
				$this->request->data = Hash::merge($user, $this->request->data);
			}

		} else {
			//表示処理
			$this->User->languages = $this->viewVars['languages'];
			$this->request->data = $user;
		}

		// 絞り込み条件
		$this->set('query', $this->Session->read(self::USER_MANAGER_SEARCH_CONDITIONS));

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
		if (! $this->request->is('delete')) {
			return $this->throwBadRequest();
		}

		//削除できるかチェック
		$user = $this->User->getUser($this->data['User']['id']);
		if (! $this->User->canUserDelete($user)) {
			return $this->throwBadRequest();
		}

		$this->User->deleteUser($user);

		$this->NetCommons->setFlashNotification(
			__d('net_commons', 'Successfully deleted.'), array('class' => 'success')
		);
		$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
	}

/**
 * view method
 *
 * @return void
 */
	public function status() {
		if (! $this->request->is('put')) {
			return $this->throwBadRequest();
		}

		$userId = $this->data['User']['id'];
		$user = $this->User->getUser($userId);

		//編集できるかどうかチェック
		if (! $this->User->canUserEdit($user)) {
			return $this->throwBadRequest();
		}

		$result = $this->AutoUserRegist->saveUserStatus(
			['id' => $userId], AutoUserRegist::CONFIRMATION_ADMIN_APPROVAL, false
		);
		if ($result) {
			$user = Hash::merge($user, $result);
			$this->AutoUserRegistMail->sendMail(AutoUserRegist::CONFIRMATION_USER_OWN, $user);

			$message = __d('auth', 'hank you for your registration.<br>' .
							'We have sent you the registration key to your registered e-mail address.');
			$this->NetCommons->setFlashNotification($message, array('class' => 'success'));

		} else {
			$message = __d('auth', 'Your registration was not approved.<br>' .
							'Please consult with the system administrator.');
			$this->NetCommons->setFlashNotification(__d('net_commons', 'Bad Request'), array(
				'class' => 'danger',
				'interval' => NetCommonsComponent::ALERT_VALIDATE_ERROR_INTERVAL,
				'error' => $message
			), 400);
		}

		$this->redirect($this->request->referer(true));
	}

/**
 * importアクション
 *
 * @return void
 */
	public function import() {
		//タイムアウトはっせいするなら適宜設定
		set_time_limit(1800);

		$this->set('importHelp', $this->User->getCsvHeader(true));

		if ($this->request->is('post')) {
			$file = $this->FileUpload->getTemporaryUploadFile('import_csv');
			if (! $this->User->importUsers($file, $this->data['import_type'])) {
				//バリデーションエラーの場合
				$this->set('errorMessages', Hash::flatten($this->User->validationErrors));
				$this->NetCommons->handleValidationError($this->User->validationErrors);
				return;
			}

			$this->NetCommons->setFlashNotification(
				__d('net_commons', 'Successfully saved.'), array('class' => 'success')
			);
			$this->redirect('/user_manager/user_manager/index/');
		} else {
			$this->set('errorMessages', null);
		}
	}

/**
 * importファイルフォーマットのダウンロード
 *
 * @return void
 */
	public function download_import_format() {
		App::uses('CsvFileWriter', 'Files.Utility');

		$header = $this->User->getCsvHeader();
		$csvWriter = new CsvFileWriter(array('header' => $header));
		$csvWriter->close();

		return $csvWriter->download('import_file.csv');
	}

/**
 * exportアクション
 *
 * @return void
 */
	public function export() {
		//タイムアウトはっせいするなら適宜設定
		set_time_limit(1800);

		$this->helpers[] = 'Users.UserSearchForm';
		$this->helpers[] = 'Users.UserSearch';

		if (Hash::check($this->request->query, 'save')) {
			App::uses('CsvFileWriter', 'Files.Utility');

			$csvWriter = $this->User->exportUsers(
				array(
					'conditions' => array(
						'space_id !=' => Space::PRIVATE_SPACE_ID,
						'User.role_key NOT' => array(
							UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR,
							UserRole::USER_ROLE_KEY_ADMINISTRATOR
						),
						'User.is_deleted' => false,
					),
					'joins' => array('Room' => array(
						'conditions' => array(
							'Room.page_id_top NOT' => null,
						)
					))
				),
				$this->request->query
			);
			if (! $csvWriter) {
				//バリデーションエラーの場合
				$this->NetCommons->handleValidationError($this->User->validationErrors);
				return;
			}
			return $csvWriter->zipDownload(
				'export_user.zip', 'export_user.csv', $this->request->query['pass']
			);

		} else {
			$defaultConditions = $this->UserSearch->cleanSearchFields($this->request->query);
			$this->request->data['UserSearch'] = $defaultConditions;

			$this->set('cancelQuery', $this->request->query);
			$defaultConditions['search'] = '1';
			$this->request->query = $defaultConditions;
		}
	}

/**
 * bulkアクション
 *
 * @return void
 */
	public function bulk() {
		//タイムアウト発生するなら適宜設定
		set_time_limit(1800);
		if (! $this->request->is('post')) {
			return $this->throwBadRequest();
		}

		if ($this->request->data['UserManagerBulk']['submit'] === 'nonactive') {
			//利用不可に設定
			return $this->UserManagerBulk->bulkNonactive();
		} elseif ($this->request->data['UserManagerBulk']['submit'] === 'delete') {
			//削除する
			return $this->UserManagerBulk->bulkDelete();
		} else {
			//それ以外
			return $this->throwBadRequest();
		}
	}

}
