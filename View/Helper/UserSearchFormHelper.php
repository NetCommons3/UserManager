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

App::uses('AppHelper', 'View/Helper');

/**
 * UserSearchForm Helper
 *
 * @package NetCommons\Users\View\Helper
 */
class UserSearchFormHelper extends AppHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Button',
		'NetCommons.NetCommonsForm',
		'NetCommons.NetCommonsHtml',
	);

/**
 * 会員検索の入力フォームHTMLを生成する
 *
 * @param array $userAttribute ユーザ項目属性データ
 * @return string inputのHTML
 */
	public function userSearchInput($userAttribute) {
		$html = '';

		$dataTypeKey = $userAttribute['UserAttributeSetting']['data_type_key'];

		//以下の場合、条件のinputを表示させない
		// * パスワードは項目表示しない
		// * 管理項目 && 会員管理が使えない
		// * 他人の項目が読めない && 他人の項目が編集できない
		if ($dataTypeKey === DataType::DATA_TYPE_PASSWORD ||
				$userAttribute['UserAttributeSetting']['only_administrator'] && ! Current::allowSystemPlugin('user_manager') ||
				! $userAttribute['UserAttributesRole']['other_readable'] && ! $userAttribute['UserAttributesRole']['other_editable']) {

			return $html;
		}

		if (in_array($userAttribute['UserAttribute']['key'], UserAttribute::$typeDatetime, true)) {
			$dataTypeKey = DataType::DATA_TYPE_DATETIME;
		}

		$html .= '<div class="form-group">';
		$html .= $this->__label($dataTypeKey, $userAttribute);
		$html .= $this->__input($dataTypeKey, $userAttribute);
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
			DataType::DATA_TYPE_IMG,
			DataType::DATA_TYPE_RADIO,
			DataType::DATA_TYPE_CHECKBOX,
			//DataType::DATA_TYPE_SELECT,
			DataType::DATA_TYPE_DATETIME
		);
		if (in_array($dataTypeKey, $inArray, true)) {
			//ラジオボタン、チェックボタン、セレクトボタン、日時
			$html .= '<div>';
			$html .= '<label>' . h($userAttribute['UserAttribute']['name']) . '</label>';
			$html .= '</div>';
		} else {
			$html .= $this->NetCommonsForm->label($userAttribute['UserAttribute']['key'], $userAttribute['UserAttribute']['name']);
		}

		return $html;
	}

/**
 * 会員検索の入力フォームHTMLを生成する
 *
 * @param string $dataTypeKey inputタイプ
 * @param array $userAttribute ユーザ項目属性データ
 * @return string 入力フォームHTML
 */
	private function __input($dataTypeKey, $userAttribute) {
		$html = '';

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
			if ($userAttribute['UserAttribute']['key'] === 'role_key') {
				$keyPath = '{n}.key';
			} else {
				$keyPath = '{n}.code';
			}
			//ラジオボタン、チェックボタン、セレクトボタン
			$options = Hash::combine($userAttribute, 'UserAttributeChoice.' . $keyPath, 'UserAttributeChoice.{n}.name');
		}

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

		$html .= '<div class="form-control input-sm user-search-conditions nc-data-label">';

		$options = array('' => __d('user_manager', 'Not specified')) + $options;
		$html .= $this->NetCommonsForm->radio($userAttribute['UserAttribute']['key'], $options, array(
			//'div' => array('class' => 'form-control form-inline'),
			'div' => false,
			'separator' => '<span class="radio-separator"></span>',
			'default' => '',
			'hiddenField' => false,
		));
		$html .= '</div>';

		return $html;
	}

/**
 * 会員検索の入力フォームHTMLを生成する
 * 後でやる
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
			'class' => 'form-control input-sm',
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
			$html .= '<div class="user-search-conditions-datetime">';
		} else {
			//○日以上前、○日以内
			$moreThanDays = __d('user_manager', 'more than <span style="color:#ff0000;">X</span>days ago');
			$withinDays = __d('user_manager', 'within <span style="color:#ff0000;">X</span>days');
			$html .= '<div class="user-search-conditions-datetime">';
		}

		//○日以上前(○日以上ログインしていない)の出力
		$html .= '<div class="input-group">';
		$html .= $this->NetCommonsForm->input(
			$userAttribute['UserAttribute']['key'] . '.' . UserSearchComponent::MORE_THAN_DAYS,
			array(
				'name' => $userAttribute['UserAttribute']['key'] . '.' . UserSearchComponent::MORE_THAN_DAYS,
				'type' => 'number',
				'class' => 'form-control input-sm user-search-conditions-datetime-top',
				'label' => false,
				'div' => false,
				'error' => false,
			)
		);
		$html .= $this->NetCommonsForm->label(
			$userAttribute['UserAttribute']['key'] . '.' . UserSearchComponent::MORE_THAN_DAYS, $moreThanDays,
			array('class' => 'input-group-addon user-search-conditions-datetime-top')
		);
		$html .= '</div>';

		//○日以内(○日以内ログインしている)の出力
		$html .= '<div class="input-group">';
		$html .= $this->NetCommonsForm->input(
			$userAttribute['UserAttribute']['key'] . '.' . UserSearchComponent::WITHIN_DAYS,
			array(
				'name' => $userAttribute['UserAttribute']['key'] . '.' . UserSearchComponent::WITHIN_DAYS,
				'type' => 'number',
				'class' => 'form-control input-sm user-search-conditions-datetime-bottom',
				'label' => false,
				'div' => false,
				'error' => false,
			)
		);
		$html .= $this->NetCommonsForm->label(
			$userAttribute['UserAttribute']['key'] . '.' . UserSearchComponent::WITHIN_DAYS, $withinDays,
			array('class' => 'input-group-addon user-search-conditions-datetime-bottom')
		);
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
			'class' => 'form-control input-sm',
		));

		return $html;
	}

/**
 * 会員検索の入力フォームHTMLを生成する
 *
 * @return string inputのHTML
 */
	public function userSearchRoomsSelect() {
		$html = '';

		$options = array('' => __d('user_manager', '-- Not specify --')) + $this->_View->viewVars['rooms'];

		$html .= '<div class="form-group">';
		$html .= '<div>';
		$html .= '<label>' . __d('user_manager', 'Rooms') . '</label>';
		$html .= '</div>';
		$html .= $this->NetCommonsForm->input('room', array(
			'type' => 'select',
			'options' => $options,
			'label' => false,
			'div' => false,
			'error' => false,
			'class' => 'form-control input-sm',
		));
		$html .= '</div>';

		return $html;
	}

/**
 * 会員検索の入力フォームHTMLを生成する
 *
 * @return string inputのHTML
 */
	public function userSearchGroupsSelect() {
		$html = '';

		if (! $this->_View->viewVars['groups']) {
			return $html;
		}

		$options = array('' => __d('user_manager', '-- Not specify --')) + $this->_View->viewVars['groups'];

		$html .= '<div class="form-group">';
		$html .= '<div>';
		$html .= '<label>' . __d('user_manager', 'Groups') . '</label>';
		$html .= '</div>';
		$html .= $this->NetCommonsForm->input('group_id', array(
			'type' => 'select',
			'options' => $options,
			'label' => false,
			'div' => false,
			'error' => false,
			'class' => 'form-control input-sm',
		));
		$html .= '</div>';

		return $html;
	}

/**
 * 対象会員の絞り込みボタン表示
 *
 * @param string $url URL
 * @return string HTML
 */
	public function displaySearchButton($url) {
		$html = '';
		$html .= $this->NetCommonsHtml->css(array(
			'/user_manager/css/style.css',
			//'/users/css/style.css',
		));
		$html .= $this->NetCommonsHtml->script(array(
			'/user_manager/js/user_manager.js'
		));

		$html .= '<div class="text-center" ng-controller="UserManager.controller">';

		$html .= $this->Button->search(__d('users', 'Search for the members'), array(
			'type' => 'button',
			'ng-click' => 'showUserSearch(null, \'' . h($url) . '\')'
		));
		$html .= '</div>';

		return $html;
	}

}
