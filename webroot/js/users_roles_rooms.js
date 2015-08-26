/**
 * @fileoverview Links Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 */


/**
 * UsersRolesRooms Javascript
 *
 * @param {string} Controller name
 * @param {function($scope, $http, $window)} Controller
 */
NetCommonsApp.controller('UsersRolesRooms', function($scope, $http) {

  /**
   * initialize
   *
   * @return {void}
   */
  $scope.initialize = function(data) {
    if (! angular.isUndefined(data.RolesRoomsUser)) {
      $scope.data = data;
      $scope.rolesRoomId = $scope.data.RolesRoomsUser['roles_room_id'];
    }
  };

  /**
   * Click link
   *
   * @param {integer} rolesRoomId
   * @return {void}
   */
  $scope.sendPost = function(rolesRoomId) {
    if ($scope.data.RolesRoomsUser['roles_room_id'] == rolesRoomId) {
      return true;
    }

    $scope.data.RolesRoomsUser['roles_room_id'] = rolesRoomId;
    $scope.$parent.sending = true;

    $http.get('/net_commons/net_commons/csrfToken.json')
      .success(function(token) {
          $scope.data._Token.key = token.data._Token.key;

          //POSTリクエスト
          $http.post(
              '/user_manager/users_roles_rooms/edit/' +
                  $scope.data.RolesRoomsUser['user_id'] + '/' +
                  $scope.data.Room['space_id'] + '.json',
              $.param({_method: 'POST', data: $scope.data}),
              {cache: false,
                headers:
                    {'Content-Type': 'application/x-www-form-urlencoded'}
              }
          ).success(function(data) {
            if (! $scope.data.RolesRoomsUser['roles_room_id']) {
              $scope.data.RolesRoomsUser['id'] = '';
            } else {
              $scope.data.RolesRoomsUser['id'] = data['rolesRoomsUser']['id'];
            }
            $scope.rolesRoomId = $scope.data.RolesRoomsUser['roles_room_id'];

            $scope.flashMessage(data['name'], data['class'], data['interval']);
            $scope.$parent.sending = false;
          }).error(function(data, status) {
            $scope.flashMessage(data['name'], 'danger', 0);
            $scope.$parent.sending = false;
          });
        });
  };
});
