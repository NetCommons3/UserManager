<?php
/**
 * メール設定データのMigration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('MailsMigration', 'Mails.Config/Migration');

/**
 * メール設定データのMigration
 *
 * @package NetCommons\Mails\Config\Migration
 */
class UserManagerMailSettingRecords extends MailsMigration {

/**
 * プラグインキー
 *
 * @var string
 */
	const PLUGIN_KEY = 'user_manager';

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'mail_setting_records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * plugin data
 *
 * @var array $migration
 */
	public $records = array(
		'MailSetting' => array(
			//メール通知 - 定型文
			array(
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'is_mail_send' => true,
			),
		),
		'MailSettingFixedPhrase' => array(
			//メール通知 - 定型文
			// * 英語
			array(
				'language_id' => '1',
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				//contentsじゃないのに、contents??? 後でnotifyに修正予定
				//MaliQueueBehavior::saveQueuePostMail()でcontents固定になってしまっているため
				'type_key' => 'contents',
				'mail_fixed_phrase_subject' => '{X-SUBJECT}',
				'mail_fixed_phrase_body' => '{X-BODY}',
			),
			// * 日本語
			array(
				'language_id' => '2',
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'type_key' => 'contents',
				'mail_fixed_phrase_subject' => '{X-SUBJECT}',
				'mail_fixed_phrase_body' => '{X-BODY}',
			),
			//メール通知 - 定型文
			// * 英語
			array(
				'language_id' => '1',
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'type_key' => 'save_notfy',
				'mail_fixed_phrase_subject' => 'Welcome to {X-SITE_NAME}.',
				'mail_fixed_phrase_body' => 'Thank you for registering for {X-SITE_NAME}.
Handle: {X-HANDLENAME}
Login_id: {X-USERNAME}
Password: {X-PASSWORD}
e-mail: {X-EMAIL}

You may now log in by clicking on this link or copying and pasting it in your browser:
{X-URL}',
			),
			// * 日本語
			array(
				'language_id' => '2',
				'plugin_key' => self::PLUGIN_KEY,
				'block_key' => null,
				'type_key' => 'save_notfy',
				'mail_fixed_phrase_subject' => '{X-SITE_NAME}へようこそ',
				'mail_fixed_phrase_body' => '会員登録が完了しましたのでお知らせします。
ハンドル: {X-HANDLENAME}
ログインID: {X-USERNAME}
パスワード: {X-PASSWORD}
e-mail: {X-EMAIL}

下記アドレスからログインしてください。
{X-URL}',
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return parent::updateAndDelete($direction, self::PLUGIN_KEY);
	}
}
