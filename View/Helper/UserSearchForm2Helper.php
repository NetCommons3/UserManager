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
		$choiceInArray = array(
			DataType::DATA_TYPE_RADIO,
			DataType::DATA_TYPE_CHECKBOX,
			DataType::DATA_TYPE_SELECT,
		);
		if ($dataTypeKey === DataType::DATA_TYPE_IMG) {
			//あり、なし、指定なしのラジオボタン
			$dataTypeKey = DataType::DATA_TYPE_RADIO;
			$options = array(
				'0' => __d('user_manager', 'No avatar.'),
				'1' => __d('user_manager', 'Has avatar.')
			);
		} elseif (in_array($dataTypeKey, $choiceInArray, true)) {
			if ($userAttributeKey === 'role_key') {
				$keyPath = '{n}.key';
			} else {
				$keyPath = '{n}.code';
			}
			//ラジオボタン、チェックボタン、セレクトボタン
			$options = Hash::combine($userAttribute, 'UserAttributeChoice.' . $keyPath, 'UserAttributeChoice.{n}.name');
		}

		if (in_array($userAttributeKey, UserAttribute::$typeDatetime, true)) {
			$dataTypeKey = DataType::DATA_TYPE_DATETIME;
		}

		$html .= '<div class="form-group input-group">';
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

		$inArray = array(
			DataType::DATA_TYPE_RADIO,
			DataType::DATA_TYPE_CHECKBOX,
			//DataType::DATA_TYPE_SELECT,
			DataType::DATA_TYPE_DATETIME
		);
		if (in_array($dataTypeKey, $inArray, true)) {
			//ラジオボタン、チェックボタン、セレクトボタン、日時
			//$html .= '<div>';
			$html .= '<label class="input-group-addon">' . h($userAttribute['UserAttribute']['name']) . '</label>';
			//$html .= '</div>';
		} else {
			$html .= $this->NetCommonsForm->label($userAttribute['UserAttribute']['key'], $userAttribute['UserAttribute']['name'], array(
				'class' => 'input-group-addon'
			));
		}

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

		switch ($dataTypeKey) {
			case DataType::DATA_TYPE_RADIO:
				$html .= $this->__inputRadio($dataTypeKey, $userAttribute, $options);
				break;

			case DataType::DATA_TYPE_CHECKBOX:
				$html .= $this->__inputCheckbox($dataTypeKey, $userAttribute, $options);
				break;

			case DataType::DATA_TYPE_SELECT:
				$html .= $this->__inputSelect($dataTypeKey, $userAttribute, $options);
				break;

			case DataType::DATA_TYPE_DATETIME:
				$html .= $this->__inputDatetime($dataTypeKey, $userAttribute);
				break;

			default:
				$html .= $this->__inputText($dataTypeKey, $userAttribute);
		}

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
	private function __inputRadio($dataTypeKey, $userAttribute, $options) {
		$html = '';

		$html .= '<div class="form-control user-search-conditions">';
		$html .= $this->NetCommonsForm->radio($userAttribute['UserAttribute']['key'],
			array('' => __d('user_manager', 'Not specified')),
			array('div' => false, 'default' => '')
		);
		$html .= '<br>';

		$html .= $this->NetCommonsForm->radio($userAttribute['UserAttribute']['key'], $options, array(
			//'div' => array('class' => 'form-control form-inline'),
			'div' => false,
			'separator' => '<span class="radio-separator"></span>',
		));
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
	private function __inputCheckbox($dataTypeKey, $userAttribute, $options) {
		$html = '';

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
	private function __inputSelect($dataTypeKey, $userAttribute, $options) {
		$html = '';

		if ($options) {
			$options = array('' => __d('user_manager', '-- Not specify --')) + $options;
		}
		$html .= $this->NetCommonsForm->input($userAttribute['UserAttribute']['key'], array(
			'type' => 'select',
			'options' => $options,
			'label' => false,
			'div' => false,
			'error' => false,
		));

		return $html;
	}

/**
 * 会員検索の入力フォームHTMLを生成する
 *
 * @param string $dataTypeKey inputタイプ
 * @param array $userAttribute ユーザ項目属性データ
 * @return string 入力フォームHTML
 */
	private function __inputDatetime($dataTypeKey, $userAttribute) {
		$html = '';

		if ($userAttribute['UserAttribute']['key'] === 'last_login') {
			//最終ログイン日時の場合、ラベル変更(○日以上ログインしていない、○日以内ログインしている)
			$moreThanDays = __d('user_manager', 'Not logged more than <span style="color:#ff0000;">X</span>days ago');
			$withinDays = __d('user_manager', 'Have logged in within <span style="color:#ff0000;">X</span>days');
			$html .= '<div class="user-search-conditions-datetime-login">';
		} else {
			//○日以上前、○日以内
			$moreThanDays = __d('user_manager', 'more than <span style="color:#ff0000;">X</span>days ago');
			$withinDays = __d('user_manager', 'within <span style="color:#ff0000;">X</span>days');
			$html .= '<div class="user-search-conditions-datetime">';
		}

		//○日以上前(○日以上ログインしていない)の出力
		$html .= '<div class="input-group">';
		$html .= $this->NetCommonsForm->input($userAttribute['UserAttribute']['key'] . '.more_than_days', array(
			'name' => $userAttribute['UserAttribute']['key'] . '[more_than_days]',
			'type' => 'number',
			'class' => 'form-control user-search-conditions-datetime-top',
			'label' => false,
			'div' => false,
			'error' => false,
		));
		$html .= $this->NetCommonsForm->label($userAttribute['UserAttribute']['key'] . '.more_than_days', $moreThanDays, array(
			'class' => 'input-group-addon user-search-conditions-datetime-top'
		));
		$html .= '</div>';

		//○日以内(○日以内ログインしている)の出力
		$html .= '<div class="input-group">';
		$html .= $this->NetCommonsForm->input($userAttribute['UserAttribute']['key'] . '.within_days', array(
			'name' => $userAttribute['UserAttribute']['key'] . '[within_days]',
			'type' => 'number',
			'class' => 'form-control user-search-conditions-datetime-bottom',
			'label' => false,
			'div' => false,
			'error' => false,
		));
		$html .= $this->NetCommonsForm->label($userAttribute['UserAttribute']['key'] . '.within_days', $withinDays, array(
			'class' => 'input-group-addon user-search-conditions-datetime-bottom'
		));
		$html .= '</div>';

		$html .= '</div>';

		return $html;
	}

/**
 * 会員検索の入力フォームHTMLを生成する
 *
 * @param string $dataTypeKey inputタイプ
 * @param array $userAttribute ユーザ項目属性データ
 * @return string 入力フォームHTML
 */
	private function __inputText($dataTypeKey, $userAttribute) {
		$html = '';

		$html .= $this->NetCommonsForm->input($userAttribute['UserAttribute']['key'], array(
			'type' => DataType::DATA_TYPE_TEXT,
			'label' => false,
			'div' => false,
			'error' => false,
			//'placeholder' => $this->__label($dataTypeKey, $userAttribute),
		));

		return $html;
	}

}
