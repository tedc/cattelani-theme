catellani = angular.module 'catellani'
catellani
	.config ["$stateProvider", "$locationProvider", require './state.coffee' ]
	.run ["$transitions", "$state", "$location", "$rootScope", "$timeout", "$stateParams", ($transitions, $state, $location, $rootScope, $timeout, $stateParams)->
		FastClick.attach document.body
		$rootScope.isAnim = 'is-anim'
		oldUrl = $location.absUrl()
		$rootScope.isGlossary = []
		$rootScope.body_class = "#{vars.main.body_classes}#{vars.main.logged_classes}"
		#$rootScope.vimeo = angularLoad.loadScript 'https://player.vimeo.com/api/player.js'
		$transitions.onBefore {}, (trans)->
			newUrl = trans.router.stateService.href(trans.to().name, trans.params(), {absolute : on})
			$rootScope.isAnim = if newUrl.split('#')[0] is oldUrl.split('#')[0] then '' else 'is-anim'
			$rootScope.$broadcast 'filters_reset'
			return
		$transitions.onStart {}, (trans)->
			hash = $location.hash()
			if hash
				$timeout ->
					return if hash isnt 'contact' and hash isnt 'downloads' and hash isnt 'search' and hash isnt 'languages'
					$rootScope.modal hash
					$rootScope.$broadcast 'hash_change', {hash : hash}
					return
			body = document.body
			docEl = document.documentElement
			scrollTop = window.pageYOffset or docEl.scrollTop or body.scrollTop
			$rootScope.scrollFrom = scrollTop
			newUrl = trans.router.stateService.href(trans.to().name, trans.params(), {absolute : on})
			$rootScope.fromState = if newUrl is oldUrl then trans.$to().name.replace('app.', '') else $rootScope.fromState
			$rootScope.isAnim = '' if newUrl is oldUrl
			if hash.trim() is ''
				if newUrl is oldUrl 
					$rootScope.menuItem = if trans.params().slug then trans.params().slug else ''
				else
					$rootScope.menuItem = off
			#$rootScope.fromParams = trans.params()
			#console.log newUrl is oldUrl.split('#')[0], console.log /#/.test oldUrl, oldUrl.split('#')[0]
			#console.log newUrl.split('#')[0] is oldUrl.split('#')[0]
			#return false if hash
			#return false if /#/.test(oldUrl) and newUrl is oldUrl.split('#')[0]
			#console.log newUrl.split('#')[0] is oldUrl.split('#')[0] and hash.trim() is '',  hash.trim() is '', newUrl.split('#')[0] is oldUrl.split('#')[0]
			$rootScope.closePopup() if $rootScope.isPopup and hash.trim() is ''
			return false if newUrl.split('#')[0] is oldUrl.split('#')[0]
			# from = if $rootScope.from then $rootScope.from else trans.$from().name.replace('app.', '')
			# to = trans.$to().name.replace('app.', '')
			oldUrl = newUrl
			$rootScope.$broadcast 'sceneDestroy'
			$rootScope.$broadcast 'updateScenes'
			$rootScope.$broadcast 'destroySwiper'
			#fromElementAnim $rootScope.fromElement
			return
		angular.element(window).on 'hashchange', ->
			hash = $location.hash()
			$rootScope.closePopup() if $rootScope.isPopup and hash.trim() is ''
			return
		return
		# closeBar = ->
		# 	cfpLoadingBar.complete()
		# 	return
		# $transitions.onError {}, closeBar					
	]