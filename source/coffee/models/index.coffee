catellani = angular.module 'catellani'
catellani
	.config ["$stateProvider", "$locationProvider", "cfpLoadingBarProvider", require './state.coffee' ]
	.run ["$transitions", "$state", "$location", "$rootScope", "$timeout", "cfpLoadingBar", ($transitions, $state, $location, $rootScope, $timeout, cfpLoadingBar)->
		$rootScope.isFinish = on
		$rootScope.isAnim = 'is-anim'
		$rootScope.isLeaving = 'is-leaving'
		oldUrl = $location.absUrl()
		$rootScope.isGlossary = []
		$rootScope.body_class = "#{vars.main.body_classes}#{vars.main.logged_classes}"
		#$rootScope.vimeo = angularLoad.loadScript 'https://player.vimeo.com/api/player.js'
		$transitions.onStart {}, (trans)->
			body = document.body
			docEl = document.documentElement
			scrollTop = window.pageYOffset or docEl.scrollTop or body.scrollTop
			$rootScope.scrollFrom = scrollTop
			newUrl = trans.router.stateService.href(trans.to().name, trans.params(), {absolute : on})
			$rootScope.fromState = if newUrl is oldUrl then trans.$to().name.replace('app.', '') else $rootScope.fromState
			$rootScope.isLeaving = off if newUrl is oldUrl
			$rootScope.isAnim = off if newUrl is oldUrl
			#$rootScope.fromParams = trans.params()
			return false if newUrl is oldUrl
			$rootScope.isAnim = 'is-anim'
			# from = if $rootScope.from then $rootScope.from else trans.$from().name.replace('app.', '')
			# to = trans.$to().name.replace('app.', '')
			oldUrl = newUrl
			$rootScope.$broadcast 'sceneDestroy'
			$rootScope.$broadcast 'updateScenes'
			$rootScope.$broadcast 'destroySwiper'
			#fromElementAnim $rootScope.fromElement
			return
		closeBar = ->
			cfpLoadingBar.complete()
			return
		$transitions.onSuccess {}, closeBar
		$transitions.onError {}, closeBar
					
	]