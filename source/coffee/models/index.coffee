catellani = angular.module 'catellani'
catellani
	.config ["$stateProvider", "$locationProvider", require './state.coffee' ]
	.run ["$transitions", "$state", "$location", "$rootScope", "$timeout", ($transitions, $state, $location, $rootScope, $timeout)->
		$rootScope.isFinish = on
		animationDiv = document.createElement 'div'
		document.body.appendChild animationDiv
		animationDiv = angular.element animationDiv
		animationDiv.addClass 'transitioner'
		animationInner = document.createElement 'div'
		animationInner = angular.element animationInner
		animationInner.addClass 'transitioner__wrapper'
		animationDiv.append animationInner
		animationCover = document.createElement 'div'
		animationCover = angular.element animationCover
		animationCover.addClass 'transitioner__cover'
		animationInner.append animationCover
		complete = ->
			$timeout ->
				$rootScope.isFinish = on
				$rootScope.$broadcast 'is_finish'
				return
			return
		fromElementAnim = (element)->
			if $rootScope.isNew isnt element
				$rootScope.isNew = element
				$rootScope.el =
					element : element
					cover : element.attr 'data-item-background'
					total : element.attr 'data-item-total'
					size : element.attr 'data-item-size'
					carousel : element.attr 'data-carousel-item'
			else
				$rootScope.isNew = off
			TweenMax.set animationCover,
				backgroundImage : "url(#{$rootScope.el.cover})"
			animationInner.addClass "transitioner__wrapper--s#{$rootScope.el.size}"
			if $rootScope.el.carousel
				classAdd = ''
				if parseInt($rootScope.el.carousel) is 0
					classAdd = 'transitioner--flex-start'
				if parseInt($rootScope.el.carousel) is parseInt($rootScope.el.total)
					classAdd = 'transitioner--flex-end'
				animationDiv.addClass classAdd
			animationDiv.addClass 'transitioner--flex'
			coords = animationDiv[0].getBoundingClientRect()
			if $rootScope.isNew is element
				TweenMax.to animationCover, .5,
					width : coords.width
					onUpdate : ->
						if @time() > 0.35
							animationInner.removeClass "transitioner__wrapper--s#{$rootScope.el.size}" if animationInner.hasClass "transitioner__wrapper--s#{$rootScope.el.size}" 
							animationInner.addClass 'transitioner__wrapper--s12' if not animationInner.hasClass 'transitioner__wrapper--s12'
						return
					onComplete : complete
			else
				animationInner.removeClass 'transitioner__wrapper--s12' if  animationInner.hasClass 'transitioner__wrapper--s12'
				animationInner.addClass "transitioner__wrapper--s#{$rootScope.el.size}" if not animationInner.hasClass "transitioner__wrapper--s#{$rootScope.el.size}" 
				TweenMax.fromTo animationCover, .5,
					{
						width : coords.width
					}
					{
						width : "100%"
						delay : .35
						onComplete : complete
					}
			return

		cleanTransition = ->
			animationDiv.removeClass 'transitioner--flex'
			return

		oldUrl = $location.absUrl()
		$rootScope.isGlossary = []
		$rootScope.body_class = "#{vars.main.body_classes}#{vars.main.logged_classes}"
		$transitions.onStart {}, (trans)->
			newUrl = trans.router.stateService.href(trans.to().name, trans.params(), {absolute : on})
			$rootScope.from = if newUrl is oldUrl then trans.$to().name.replace('app.', '') else off
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
		# $transitions.onSuccess {}, (trans)->
		# 	cleanTransition() if $rootScope.isFinish
		# 	$rootScope.$on 'is_finish', cleanTransition
		# 	return			
	]