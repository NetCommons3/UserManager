<?php
/**
 * UserManagerApp Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserManagerAppModel', 'UserManager.Model');

/**
 * メール通知用Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserManager\Controller
 */
class UserMail extends UserManagerAppModel {

/**
 * テーブル名
 *
 * @var mixed
 */
	public $useTable = false;

/**
 * 使用ビヘイビア
 *
 * @var array
 */
	public $actsAs = array(
		'Mails.MailQueue' => array(
			'embedTags' => array(
				'X-SUBJECT' => 'UserMail.title',
				'X-BODY' => 'UserMail.body',
			),
		),
	);

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'user_id' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true
				),
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'reply_to_address' => array(
				'email' => array(
					'rule' => array('email'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false
				),
			),
			'title' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('user_manager', 'Mail title')),
					'required' => true
				),
			),
			'body' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('user_manager', 'Mail body')),
					'required' => true
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * Save Mail
 *
 * @param array $data リクエストデータ
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveMail($data) {
		//トランザクションBegin
		$this->begin();

		$data['UserMail']['key'] = ''; //←無駄だが、セットしないと動かないため。
		$data['UserMail']['key'] = ''; //←無駄だが、セットしないと動かないため。

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			return false;
		}

		try {
			// 後々、こんな感じにMailQueueBehaviorを改修してほしい
			//
			// 1. 下記の方法で送るアドレスを指定する
			// - ユーザIDが存在しないものへ送る場合
			// $this->setToAddress(xxxxx);
			//
			// - ユーザIDが存在するものへ送る場合
			// $this->setToUserId(xxxxx)
			//
			// 2. Reply Toを指定するとき
			// $this->setReplyTo(xxxxx);
			//
			// 3. $this->saveQueue()を呼ぶ
			if (! $this->saveQueuePostMail(Current::read('Language.id'), null, $data['UserMail']['user_id'])) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
