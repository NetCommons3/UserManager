<?php
/**
 * UserSearchForm Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FormHelper', 'View/Helper');

/**
 * UserSearchForm Helper
 *
 * @package NetCommons\Users\View\Helper
 */
class UserSearchFormHelper extends FormHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.NetCommonsForm',
	);

/**
 * Default Constructor
 *
 * @param View $View The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 */
	//public function __construct(View $View, $settings = array()) {
	//	parent::__construct($View, $settings);
	//}

/**
 * 会員検索の入力フォームHTMLを生成する
 *
 * @param array $userAttribute ユーザ項目属性データ
 * @return string inputのHTML
 */
	public function userSearchInput($userAttribute) {
		$html = '';
		//var_dump($userAttribute);

		$dataTypeKey = $userAttribute['UserAttributeSetting']['data_type_key'];
		$userAttributeKey = $userAttribute['UserAttribute']['key'];

		//パスワードは項目表示しない
		if ($dataTypeKey === DataType::DATA_TYPE_PASSWORD) {
			return $html;
		}

		$options = null;
		if ($dataTypeKey === DataType::DATA_TYPE_IMG) {
			//あり、なし、指定なしのラジオボタン
			$dataTypeKey = DataType::DATA_TYPE_RADIO;
			$options = array(
				//未着手
			);
		} elseif (in_array($dataTypeKey,
				array(DataType::DATA_TYPE_RADIO, DataType::DATA_TYPE_CHECKBOX, DataType::DATA_TYPE_SELECT), true)) {
			if ($userAttributeKey === 'role_key') {
				$keyPath = '{n}.key';
			} else {
				$keyPath = '{n}.code';
			}
			//ラジオボタン、チェックボタン、セレクトボタン
			$options = Hash::combine($userAttribute, 'UserAttributeChoice.' . $keyPath, 'UserAttributeChoice.{n}.name');
		}

		if (in_array($userAttributeKey, array('modified', 'created', 'password_modified', 'last_login'), true)) {
			$dataTypeKey = DataType::DATA_TYPE_DATETIME;
		}

		$html .= '<div class="form-group">';
		$html .= $this->__label($dataTypeKey, $userAttribute);
		$html .= $this->__input($dataTypeKey, $userAttribute, $options);
		$html .= '</div>';

		return $html;
	}

/**
 * 会員検索のラベルHTMLを生成する
 *
 * @param string $dataTypeKey inputタイプ
 * @param array $userAttribute ユーザ項目属性データ
 * @return string ラベルHTML
 */
	private function __label($dataTypeKey, $userAttribute) {
		$html = '';

		$html .= '<div>';
		if (in_array($dataTypeKey,
				array(DataType::DATA_TYPE_RADIO, DataType::DATA_TYPE_CHECKBOX, DataType::DATA_TYPE_SELECT), true)) {
			//ラジオボタン、チェックボタン、セレクトボタン、日時
			$html .= '<strong>' . h($userAttribute['UserAttribute']['name']) . '</strong>';
		} else {
			$html .= $this->NetCommonsForm->label($userAttribute['UserAttribute']['key'], $userAttribute['UserAttribute']['name']);
		}
		$html .= '</div>';

		return $html;
	}

/**
 * 会員検索の入力フォームHTMLを生成する
 *
 * @param string $dataTypeKey inputタイプ
 * @param array $userAttribute ユーザ項目属性データ
 * @param array $options オプションデータ(radio, checkbox, select)
 * @return string 入力フォームHTML
 */
	private function __input($dataTypeKey, $userAttribute, $options) {
		$html = '';

		$userAttributeKey = $userAttribute['UserAttribute']['key'];

		$type = DataType::DATA_TYPE_TEXT;
		switch ($dataTypeKey) {
			case DataType::DATA_TYPE_RADIO:
				if ($options) {
					$options = array('' => __d('user_manager', 'Not specified')) + $options;
				}

				$html .= '<div class="form-control nc-data-label">';
				$html .= $this->NetCommonsForm->radio($userAttribute['UserAttribute']['key'], $options, array(
					'div' => array('class' => 'form-control form-inline'),
					'separator' => '<span class="radio-separator"></span>',
					'default' => ''
				));
				$html .= '</div>';
				break;
			case DataType::DATA_TYPE_CHECKBOX:
				break;
			case DataType::DATA_TYPE_SELECT:
				break;
			case DataType::DATA_TYPE_DATETIME:
				if ($userAttributeKey === 'last_login') {
					//最終ログイン日時の場合、ラベル変更(○日以上ログインしていない、○日以内ログインしている)
				} else {
					//○日以上前、○日以内
				}
				break;

			default:
				$html .= $this->NetCommonsForm->input($userAttribute['UserAttribute']['key'], array(
					'type' => $type,
					'label' => false,
					'div' => false
				));
		}

		return $html;
	}

}
