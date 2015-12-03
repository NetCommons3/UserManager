/**
 * @fileoverview UserManager Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 */


/**
 * UserManager controller
 */
NetCommonsApp.controller('UserManager.controller', function(
    $scope, NetCommonsModal) {

      /**
       * 検索ダイアログ表示
       *
       * @param {array} condtions 条件配列
       * @param {string} callbackUrl callbackするURL
       * @return {void}
       */
      $scope.showUserSearch = function(condtions, callbackUrl) {
        console.log(callbackUrl);
        NetCommonsModal.show(
            $scope, 'UserManager.search',
            $scope.baseUrl + '/user_manager/user_manager/search/conditions',
            {
              backdrop: 'static',
              size: 'lg',
              resolve: {
                options: {
                  condtions: condtions,
                  callbackUrl: callbackUrl
                }
              }
            }
        );
      };
    });


/**
 * UserManager search condtion modal controller
 */
NetCommonsApp.controller('UserManager.search', function(
    $scope, $http, $modalInstance, $window, options) {

      /**
       * 検索条件を保持する変数
       */
      $scope.condtions = options['condtions'];

      /**
       * 初期処理
       *
       * @return {void}
       */
      $scope.initialize = function(domId) {
        $scope.domId = domId;
      };

      /**
       * 検索処理
       *
       * @return {void}
       */
      $scope.search = function() {
        var element = angular.element('#' + $scope.domId);
        $scope.condtions = {};
        angular.forEach(element.serializeArray(), function(input) {
          if (input['value'] !== '') {
            this.condtions[input['name']] = input['value'];
          }
        }, $scope);

        $http.post($scope.baseUrl +
                            '/user_manager/user_manager/search/result',
            $.param({_method: 'POST', data: $scope.condtions}),
            {cache: false,
              headers:
                  {'Content-Type': 'application/x-www-form-urlencoded'}
            }
        )
          .success(function(data) {
              //success condition
              $window.location.href =
                          $scope.baseUrl + options['callbackUrl'] + '?reload';
              //$modalInstance.close('success');
            })
          .error(function(data, status) {
              //error condition
              $modalInstance.dismiss('error');
            });
      };

      /**
       * キャンセル処理
       *
       * @return {void}
       */
      $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
      };
    });
