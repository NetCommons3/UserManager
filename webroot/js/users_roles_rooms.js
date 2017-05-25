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
NetCommonsApp.controller('UsersRolesRooms', ['$scope', '$http', function($scope, $http) {
  $scope.domIdListAsParentIdKey = {};

  /**
   * initialize
   *
   * @return {void}
   */
  $scope.initValue = function(domId, roleRoomId, roomId, parentRoomId) {
    $scope[domId] = roleRoomId;

    if (!roomId) {
      return;
    }
    if (!$scope.domIdListAsParentIdKey[parentRoomId]) {
      $scope.domIdListAsParentIdKey[parentRoomId] = [];
    }
    $scope.domIdListAsParentIdKey[parentRoomId].push(domId);
  };

  /**
   * All select
   *
   * @return {void}
   */
  $scope.selectAll = function(roomRoleKey, spaceId) {
    var elements = $('input[data-input-key=' + roomRoleKey + '_' + spaceId + ']');
    angular.forEach(elements, function(el) {
      el.checked = true;
      el = angular.element(el);

      var domId = el.attr('data-dom-id');
      var value = el.attr('value');
      $scope[domId] = value;
    });
  };

  /**
   * Set RolesRoom.id
   *
   * @return {void}
   */
  $scope.setRoleRoomId = function(domId, roleRoomId, roomId) {
    $scope[domId] = roleRoomId;

    if (roleRoomId !== '0' ||
        !$scope.domIdListAsParentIdKey[roomId]
    ) {
      return;
    }

    angular.forEach($scope.domIdListAsParentIdKey[roomId], function(domId) {
      $scope[domId] = '0';
    });
  };

}]);
