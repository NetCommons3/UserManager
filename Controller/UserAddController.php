<?php
/**
 * ユーザ追加(ウィザード) Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerAppController', 'UserManager.Controller');
App::uses('NetCommonsMail', 'Mails.Utility');
App::uses('File', 'Utility');

/**
 * ユーザ追加(ウィザード) Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller
 */
class UserAddController extends UserManagerAppController {

/**
 * ウィザード定数(一般設定)
 *
 * @var string
 */
	const WIZARD_USERS = 'user_manager';

/**
 * ウィザード定数(参加ルームの選択)
 *
 * @var string
 */
	const WIZARD_USERS_ROLES_ROOMS = 'users_roles_rooms';

/**
 * ウィザード定数(メール通知)
 *
 * @var string
 */
	const WIZARD_MAIL = 'user_mail';

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
		'UserManager.UserManager',
	);

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Rooms.Room',
		'Rooms.Space',
		'UserAttributes.UserAttribute',
		'UserManager.UserMail',
		'Users.User',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Wizard' => array(
			'navibar' => array(
				self::WIZARD_USERS => array(
					'url' => array(
						'controller' => 'user_add', 'action' => 'basic',
					),
					'label' => array('user_manager', 'General setting'),
				),
				self::WIZARD_USERS_ROLES_ROOMS => array(
					'url' => array(
						'controller' => 'user_add', 'action' => 'user_roles_rooms', 'key2' => Space::ROOM_SPACE_ID,
					),
					'label' => array('user_manager', 'Select the rooms to join'),
				),
				self::WIZARD_MAIL => array(
					'url' => array(
						'controller' => 'user_add', 'action' => 'notify',
					),
					'label' => array('user_manager', 'Notify user by e-mail'),
				)
			),
			'cancelUrl' => array('controller' => 'user_manager', 'action' => 'index')
		),
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

		//ウィザードの設定
		if (in_array($this->params['action'], ['notify'], true)) {
			unset($this->helpers['NetCommons.Wizard']['navibar'][self::WIZARD_USERS]['url']);
			unset($this->helpers['NetCommons.Wizard']['navibar'][self::WIZARD_USERS_ROLES_ROOMS]['url']);
		}

		//メール通知の場合、NetCommonsMailUtilityをメンバー変数にセットする。Mockであれば、newをしない。
		//テストでMockに差し替えが必要なための処理であるので、カバレッジレポートから除外する。
		//@codeCoverageIgnoreStart
		if ($this->params['action'] === 'notify' && substr(get_class($this->mail), 0, 4) !== 'Mock') {
			$this->mail = new NetCommonsMail();
		}
		//@codeCoverageIgnoreEnd
	}

/**
 * 基本項目を登録
 *
 * @return void
 */
	public function basic() {
		$this->helpers[] = 'Users.UserEditForm';

		//システム管理者以外は、選択肢からシステム管理者を除外
		$this->UserManager->setUserRoleAdminOnBasic();

		//状態の選択肢から承認待ち、承認済みを除外
		$this->UserManager->setStatusOnBasic(array());

		if ($this->request->is('post')) {
			//不要パラメータ除去
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			//登録処理
			$this->UserManager->prepareBasicSave();

			if ($this->User->validateUser($this->request->data)) {
				//正常の場合

				//** アップロードファイルの退避
				$tmpName = Hash::get(
					$this->request->data,
					'User.' . UserAttribute::AVATAR_FIELD . '.tmp_name'
				);
				if ($tmpName) {
					$destPath = TMP . pathinfo($tmpName, PATHINFO_BASENAME);
					if ($this->UserManager->moveUploadedFile($tmpName, $destPath)) {
						$this->request->data = Hash::insert(
							$this->request->data, 'User.' . UserAttribute::AVATAR_FIELD . '.tmp_name', $destPath
						);
					}
				}

				$this->Session->write('UserAdd', $this->request->data);
				return $this->redirect('/user_manager/user_add/user_roles_rooms');
			}
			$this->NetCommons->handleValidationError($this->User->validationErrors);

		} else {
			//表示処理
			$this->User->languages = $this->viewVars['languages'];

			$referer = Router::url('/user_manager/user_add/user_roles_rooms', true);
			if ($this->referer(true) === $referer) {
				$this->request->data = $this->Session->read('UserAdd');
			} else {
				//アバター削除処理
				$tmpName = $this->Session->read('UserAdd.User.' . UserAttribute::AVATAR_FIELD . '.tmp_name');
				if ($tmpName) {
					(new File($tmpName))->delete();
				}
				$this->request->data = $this->User->createUser();
			}
		}
	}

/**
 * 参加ルームの選択
 *
 * @return void
 */
	public function user_roles_rooms() {
		if ($this->request->is('post')) {
			//登録処理
			$data = Hash::merge($this->request->data, $this->Session->read('UserAdd'));

			$user = $this->User->saveUser($data);
			if ($user) {
				//アバター削除処理
				$tmpName = $this->Session->read('UserAdd.User.' . UserAttribute::AVATAR_FIELD . '.tmp_name');
				if ($tmpName) {
					(new File($tmpName))->delete();
				}

				$this->NetCommons->setFlashNotification(__d('net_commons', 'Successfully saved.'), array(
					'class' => 'success',
				));
				if (Hash::get($this->request->data, '_UserManager.notify')) {
					$user = Hash::insert(
						$user, 'User.password', $this->Session->read('UserAdd.User.password')
					);
					$this->Session->write('UserAdd', $user);
					return $this->redirect('/user_manager/user_add/notify');
				} else {
					$this->Session->delete('UserAdd');
					return $this->redirect('/user_manager/user_manager/index');
				}
			} else {
				$this->NetCommons->handleValidationError($this->User->validationErrors);
			}
		}

		if ($this->UserMail->isUserMailSend() &&
				Hash::get($this->Session->read('UserAdd'), 'User.email')) {
			$this->set('isNotify', true);
		} else {
			$this->set('isNotify', false);
		}

		//** ルームデータセット
		$this->Rooms->setRoomsForPaginator();

		//** ロールデータセット
		$this->viewVars['defaultRoleOptions'][''] = __d('users', 'Non members');

		//** ルームロールデータ取得
		$rolesRooms = $this->Room->getRolesRoomsNotInDraft(array(
			'Room.space_id' => [Space::PUBLIC_SPACE_ID, Space::ROOM_SPACE_ID]
		));
		$rolesRooms = Hash::combine($rolesRooms, '{n}.RolesRoom.role_key', '{n}', '{n}.Room.id');
		$this->set('rolesRooms', $rolesRooms);

		//** ロールルームユーザデータ取得
		$rolesRoomsUsers['RolesRoomsUser'] = $this->Room->getDefaultRolesRoomsUser();
		$this->set('rolesRoomsUsers', $rolesRoomsUsers);

		//** ユーザID
		$this->set('activeUserId', null);
	}

/**
 * 登録メール通知
 *
 * @return void
 */
	public function notify() {
		$user = $this->Session->read('UserAdd');

		$this->set('user', $user['User']);

		if ($this->request->is('post')) {
			unset($this->request->data['send']);

			//入力チェック
			$this->UserMail->set($this->request->data);
			if ($this->UserMail->validates()) {
				//メール送信処理
				$this->mail->mailAssignTag->setFixedPhraseSubject($this->request->data['UserMail']['title']);
				$this->mail->mailAssignTag->setFixedPhraseBody($this->request->data['UserMail']['body']);
				$this->mail->mailAssignTag->initPlugin(Current::read('Language.id'));

				$this->mail->setReplyTo($this->request->data['UserMail']['reply_to']);
				$this->mail->initPlugin(Current::read('Language.id'));

				$this->mail->to($this->viewVars['user']['email']);
				try {
					$this->mail->setFrom(Current::read('Language.id'));
					if (! $this->mail->sendMailDirect()) {
						return $this->NetCommons->handleValidationError(array('SendMail Error'));
					}
				} catch (Exception $ex) {
					CakeLog::error($ex);
					return $this->NetCommons->handleValidationError(
						array('SendMail Error'),
						__d('mails', 'There is errors in the mail settings. It was not able to send mail.')
					);
				}

				//リダイレクト
				$this->Session->delete('UserAdd');
				$this->NetCommons->setFlashNotification(
					__d('user_manager', 'Successfully mail send.'), array('class' => 'success')
				);
				return $this->redirect('/user_manager/user_manager/index/');
			}
			$this->NetCommons->handleValidationError($this->UserMail->validationErrors);

		} else {
			//ユーザデータ取得
			$this->mailSetting = $this->UserMail->MailSetting->getMailSettingPlugin(null, 'save_notify');

			$this->mail->mailAssignTag->setFixedPhraseSubject(
				$this->mailSetting['MailSettingFixedPhrase']['mail_fixed_phrase_subject']
			);
			$this->mail->mailAssignTag->setFixedPhraseBody(
				$this->mailSetting['MailSettingFixedPhrase']['mail_fixed_phrase_body']
			);
			$this->mail->mailAssignTag->initPlugin(Current::read('Language.id'));

			$password = Hash::get($this->viewVars['user'], 'password', '');
			$passwordUrl = NetCommonsUrl::url('/auth/forgot_pass/request', true) .
							'?email=' . $this->viewVars['user']['email'];
			$this->mail->mailAssignTag->assignTags(array(
				'X-HANDLENAME' => $this->viewVars['user']['handlename'],
				'X-USERNAME' => $this->viewVars['user']['username'],
				'X-PASSWORD' => $password,
				'X-PASSWORD_URL' => $passwordUrl,
				'X-EMAIL' => $this->viewVars['user']['email'],
				'X-URL' => NetCommonsUrl::url('/', true),
			));
			$this->mail->mailAssignTag->assignTagReplace();

			$this->request->data['UserMail']['title'] = $this->mail->mailAssignTag->fixedPhraseSubject;
			$this->request->data['UserMail']['body'] = $this->mail->mailAssignTag->fixedPhraseBody;
			$this->request->data['UserMail']['user_id'] = $this->viewVars['user']['id'];
			$this->request->data['UserMail']['reply_to'] = SiteSettingUtil::read('Mail.from');
		}
	}

/**
 * アバター表示処理
 *
 * @return void
 */
	public function download() {
		$tmpName = $this->Session->read('UserAdd.User.' . UserAttribute::AVATAR_FIELD . '.tmp_name');
		if (! $tmpName) {
			$tmpName = App::pluginPath('Users') . 'webroot' . DS . 'img' . DS . 'noimage.gif';
		}

		$this->response->file($tmpName);
		return $this->response;
	}

}
