<?php
/**
 * UserMail Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerAppController', 'UserManager.Controller');
App::uses('NetCommonsMail', 'Mails.Utility');
App::uses('MailSend', 'Mails.Utility');

/**
 * UserMail Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller
 */
class UserMailController extends UserManagerAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'Users.User',
		'UserManager.UserMail',
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		//登録処理の場合、URLよりPOSTパラメータでチェックする
		if ($this->request->is('post')) {
			$userId = $this->data['UserMail']['user_id'];
		} else {
			$userId = Hash::get($this->params['pass'], '0');
		}
		if (! $this->User->existsUser($userId)) {
			return $this->setAction('throwBadRequest');
		}

		//ユーザデータ取得
		$user = $this->User->getUser($userId);
		$this->set('user', $user['User']);
		$this->set('userName', $user['User']['handlename']);
		$this->set('activeUserId', $userId);
	}

/**
 * メール通知
 *
 * @return void
 */
	public function notify() {
		if ($this->request->is('post')) {
			//--不要パラメータ除去
			unset($this->request->data['send']);

			if ($this->UserMail->saveMail($this->request->data)) {
				// キューからメール送信
				MailSend::send();

				$this->NetCommons->setFlashNotification(
					__d('net_commons', 'Successfully saved.'), array('class' => 'success')
				);
				return $this->redirect('/user_manager/user_manager/index/');
			}
			$this->NetCommons->handleValidationError($this->UserMail->validationErrors);

		} else {
			$this->request->data['UserMail']['title'] = '';
			$this->request->data['UserMail']['body'] = '';
			$this->request->data['UserMail']['user_id'] = $this->viewVars['user']['id'];
			$this->request->data['UserMail']['reply_to'] = Current::read('User.email');
		}
	}

/**
 * 登録メール通知
 *
 * @return void
 */
	public function save_notify() {
		$this->view = 'notify';

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
			$mail->setSubject($this->request->data['UserMail']['title']);
			$mail->setBody($this->request->data['UserMail']['body']);
			$mail->setReplyTo($this->request->data['UserMail']['reply_to']);
			$mail->mailAssignTag->initPlugin(Current::read('Language.id'));
			$mail->initPlugin(Current::read('Language.id'));

			$mail->to($this->viewVars['user']['email']); //ここだけ、CakeMailのメソッド使うの？
			$mail->setFrom(Current::read('Language.id'));
			if (! $mail->sendMailDirect()) {
				return $this->NetCommons->handleValidationError(array('SendMail Error'));
			}

			//リダイレクト
			return $this->redirect('/user_manager/user_manager/index/');

		} else {
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
