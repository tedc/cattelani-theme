catellani = angular.module 'catellani'
catellani
	.config ["$stateProvider", "$locationProvider", require './state.coffee' ]
	.run ["$transitions", "$state", "$location", "$rootScope", "$timeout", ($transitions, $state, $location, $rootScope, $timeout)->
		$rootScope.isFinish = on

		oldUrl = $location.absUrl()
		$rootScope.isGlossary = []
		$rootScope.body_class = "#{vars.main.body_classes}#{vars.main.logged_classes}"
		$transitions.onStart {}, (trans)->
			$rootScope.scrollFrom = document.body.scrollTop
			newUrl = trans.router.stateService.href(trans.to().name, trans.params(), {absolute : on})
			$rootScope.fromState = if newUrl is oldUrl then trans.$to().name.replace('app.', '') else $rootScope.fromState
			return false if newUrl is oldUrl
			from = if $rootScope.from then $rootScope.from else trans.$from().name.replace('app.', '')
			to = trans.$to().name.replace('app.', '')
			$rootScope.isFinish = off
			oldUrl = newUrl
			$rootScope.$broadcast 'sceneDestroy'
			$rootScope.$broadcast 'updateScenes'
			$rootScope.$broadcast 'destroySwiper'
			#fromElementAnim $rootScope.fromElement
			return
					
	]