module.exports = ($rootScope, data, $scope)->
	$scope.content = if data then data.content else vars.main.error
	document.querySelector('title').innerHTML = if data then data.yoats_title else vars.main.errorTitle
	$rootScope.isAnim = 'error' if typeof data is 'undefined'		
	return if not data
	$rootScope.lang_menu = data.wpml_menu[0]
	$rootScope.body_class = data.body_class + vars.main.logged_classes
	$rootScope.breadcrumbs = data.breadcrumbs
	$rootScope.$broadcast 'resize_footer'
	return