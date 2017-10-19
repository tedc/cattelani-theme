module.exports = ($rootScope, $scope, data)->
	$scope.post = data
	$scope.content = if data then $scope.post.content.rendered else vars.main.error
	document.querySelector('title').innerHTML = if data then data.yoats_title else vars.main.errorTitle
	return if not data
	$scope.type = $scope.post.type
	#$rootScope.title = $scope.post.yoats_title
	$rootScope.lang_menu = $scope.post.wpml_menu
	$rootScope.body_class = $scope.post.body_class + vars.main.logged_classes
	$rootScope.breadcrumbs = $scope.post.breadcrumbs
	$rootScope.fromElement = off if $scope.post.type isnt 'lampade'
	return