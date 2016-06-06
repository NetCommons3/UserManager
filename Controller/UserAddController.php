<?php
/**
 * UserManagerApp Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerAppController', 'UserManager.Controller');

/**
 * UserManagerApp Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller
 */
class UserAddController extends UserManagerAppController {

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
	);

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Rooms.Room',
		'Rooms.Space',
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
				parent::WIZARD_USERS => array(
					'url' => array(
						'controller' => 'user_add', 'action' => 'basic',
					),
					'label' => array('user_manager', 'General setting'),
				),
				parent::WIZARD_USERS_ROLES_ROOMS => array(
					'url' => array(
						'controller' => 'user_add', 'action' => 'user_roles_rooms', 'key2' => Space::ROOM_SPACE_ID,
					),
					'label' => array('user_manager', 'Select the rooms to join'),
				),
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
			$this->helpers['NetCommons.Wizard']['navibar'][parent::WIZARD_MAIL] = array(
				'url' => array(
					'controller' => 'user_add', 'action' => 'notify',
				),
				'label' => array('user_manager', 'Notify user by e-mail'),
			);
			unset($this->helpers['NetCommons.Wizard']['navibar'][parent::WIZARD_USERS]['url']);
			unset($this->helpers['NetCommons.Wizard']['navibar'][parent::WIZARD_USERS_ROLES_ROOMS]['url']);
		}
	}

/**
 * 基本項目を登録
 *
 * @return void
 */
	public function basic() {
		$this->helpers[] = 'Users.UserEditForm';

		if (UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR !== Current::read('User.role_key')) {
			$this->viewVars['userAttributes'] = Hash::remove(
				$this->viewVars['userAttributes'],
				'{n}.{n}.{n}.UserAttributeChoice.{n}[key=' . UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR . ']'
			);
		}

		if ($this->request->is('post')) {
			//不要パラメータ除去
			unset($this->request->data['save'], $this->request->data['active_lang_id']);

			//登録処理
			$this->_prepareSave();

			if ($this->User->validateUser($this->request->data)) {
				//正常の場合
				$this->Session->write('UserMangerEdit', $this->request->data);
				return $this->redirect('/user_manager/user_add/user_roles_rooms');
			}
			$this->NetCommons->handleValidationError($this->User->validationErrors);

		} else {
			//表示処理
			$this->User->languages = $this->viewVars['languages'];
			$this->request->data = $this->User->createUser();
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
			$data = Hash::merge($this->request->data, $this->Session->read('UserMangerEdit'));

			$user = $this->User->saveUser($data);
			if ($user) {
				$this->NetCommons->setFlashNotification(__d('net_commons', 'Successfully saved.'), array(
					'class' => 'success',
				));
				if (Hash::get($this->request->data, '_UserManager.notify')) {
					$this->Session->write('UserMangerEdit', $user);
					return $this->redirect('/user_manager/user_add/notify');
				} else {
					$this->Session->delete('UserMangerEdit');
					return $this->redirect('/user_manager/user_manager/index');
				}
			} else {
				$this->NetCommons->handleValidationError($this->User->validationErrors);
			}
		}

		//** ルームデータセット
		$this->Rooms->setRoomsForPaginator();

		//** ロールデータセット
		$this->viewVars['defaultRoles'][''] = __d('users', 'Non members');

		//** ルームロールデータ取得
		$rolesRooms = $this->Room->getRolesRooms(array(
			'Room.space_id' => [Space::PUBLIC_SPACE_ID, Space::ROOM_SPACE_ID]
		));
		$rolesRooms = Hash::combine($rolesRooms, '{n}.RolesRoom.role_key', '{n}', '{n}.Room.id');
		$this->set('rolesRooms', $rolesRooms);

		//** ロールルームユーザデータ取得
		$rolesRoomsUsers = $this->Room->getDefaultRolesRoomsUser();
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
		App::uses('NetCommonsMail', 'Mails.Utility');

		if ($this->request->is('post')) {
			unset($this->request->data['send']);

			//入力チェック
			$this->UserMail->set($this->request->data);
			if (! $this->UserMail->validates()) {
				return $this->NetCommons->handleValidationError($this->UserMail->validationErrors);
			}
			$this->Session->delete('UserMangerEdit.password');

			//メール送信処理
			$mail = new NetCommonsMail();
			$mail->mailAssignTag->setFixedPhraseSubject($this->request->data['UserMail']['title']);
			$mail->mailAssignTag->setFixedPhraseBody($this->request->data['UserMail']['body']);
			$mail->mailAssignTag->initPlugin(Current::read('Language.id'));

			$mail->setReplyTo($this->request->data['UserMail']['reply_to']);
			$mail->initPlugin(Current::read('Language.id'));

			$mail->to($this->viewVars['user']['email']); //ここだけ、CakeMailのメソッド使うの？
			$mail->setFrom(Current::read('Language.id'));
			if (! $mail->sendMailDirect()) {
				return $this->NetCommons->handleValidationError(array('SendMail Error'));
			}

			//リダイレクト
			return $this->redirect('/user_manager/user_manager/index/');

		} else {
			//ユーザデータ取得
			$user = $this->Session->read('UserMangerEdit');
			$this->set('user', $user['User']);

			$mail = new NetCommonsMail();
			$mailSetting = $this->UserMail->MailSetting->getMailSettingPlugin(null, 'save_notify');

			$mail->mailAssignTag->setFixedPhraseSubject(
				$mailSetting['MailSettingFixedPhrase']['mail_fixed_phrase_subject']
			);
			$mail->mailAssignTag->setFixedPhraseBody(
				$mailSetting['MailSettingFixedPhrase']['mail_fixed_phrase_body']
			);
			$mail->mailAssignTag->initPlugin(Current::read('Language.id'));

			$password = $this->Session->read('UserMangerEdit.password');
			if (! isset($password)) {
				$password = '';
			}
			$mail->mailAssignTag->assignTags(array(
				'X-HANDLENAME' => $this->viewVars['user']['handlename'],
				'X-USERNAME' => $this->viewVars['user']['username'],
				'X-PASSWORD' => $password,
				'X-EMAIL' => $this->viewVars['user']['email'],
				'X-URL' => NetCommonsUrl::url('/', true),
			));
			$mail->mailAssignTag->assignTagReplace();

			$this->request->data['UserMail']['title'] = $mail->mailAssignTag->fixedPhraseSubject;
			$this->request->data['UserMail']['body'] = $mail->mailAssignTag->fixedPhraseBody;
			$this->request->data['UserMail']['user_id'] = $this->viewVars['user']['id'];
			$this->request->data['UserMail']['reply_to'] = Current::read('User.email');
		}
	}

}
