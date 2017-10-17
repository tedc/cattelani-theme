module.exports = ($rootScope, $scope, data)->
	$scope.post = data
	$scope.type = $scope.post.type
	$scope.content = $scope.post.content.rendered
	#$rootScope.title = $scope.post.yoats_title
	document.title = $scope.post.yoats_title
	$rootScope.lang_menu = $scope.post.wpml_menu
	$rootScope.body_class = $scope.post.body_class + vars.main.logged_classes
	$rootScope.isMenu = off if $rootScope.isMenu
	$rootScope.isPopup = off if $rootScope.isPopup
	$rootScope.breadcrumbs = $scope.post.breadcrumbs
	$rootScope.fromElement = off if $scope.post.type isnt 'lampade'
	return