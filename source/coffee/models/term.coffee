module.exports = ($rootScope, data, $scope)->
	$scope.content = data.content
	$rootScope.title = data.yoats_title
	$rootScope.lang_menu = data.wpml_menu[0]
	$rootScope.body_class = data.body_class + vars.main.logged_classes
	$rootScope.breadcrumbs = data.breadcrumbs
	console.log data.breadcrumbs
	return