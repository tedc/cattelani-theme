module.exports = ($rootScope, $scope, data)->
	$scope.post = data
	$scope.type = $scope.post.type
	$scope.content = $scope.post.content.rendered
	$rootScope.title = $scope.post.yoats_title
	$rootScope.lang_menu = $scope.post.wpml_menu
	$rootScope.body_class = $scope.post.body_class + vars.main.logged_classes
	$rootScope.isMenu = off if $rootScope.isMenu
	$rootScope.breadcrumbs = $scope.post.breadcrumbs
	console.log $scope.post.breadcrumbs
	return