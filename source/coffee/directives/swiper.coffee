module.exports = ($timeout, $rootScope)->
	ngSwiper =
		scope : on
		link : (scope, element, attr)->
			scope.main = {}
			scope.nav = {}
			scope.current = 0
			scope.navInit = off
			scope.next = (swiper)->
				scope.main.slideNext() if scope.main.slideNext
				#scope.nav.slideNext() if scope.nav.slideNext
				scope.current = scope.main.realIndex
				return
			scope.prev = (swiper)->
				scope.main.slidePrev() if scope.main.slidePrev
				#scope.nav.slidePrev() if scope.nav.slidePrev
				scope.current = scope.main.realIndex
				return
			scope.slideTo = (index)->
				scope.main.slideTo index if scope.main.slideTo
				scope.nav.slideTo index if scope.nav.slideTo
				scope.current = scope.main.realIndex
				#console.log index
				#slideChage() if scope.main.on
				#scope.current = index
				return
			scope.$watch 'main', (newValue, oldValue)->
				return if oldValue is newValue
				scope.$watch 'nav', ->
					if scope.nav.params && scope.main.params
						scope.nav.params.control = scope.main 
						scope.main.params.control = scope.nav 
						scope.nav.update()
						scope.main.update()
						scope.nav.on 'slideChangeEnd', (swiper)->
							swiper.update()
							console.log swiper
							return
						$timeout ->
							scope.navInit = on
							return
					return
			
				return
			$rootScope.isYearsActive = off
			scope.expandStory = (cond)->
				$rootScope.isYearsActive = !$rootScope.isYearsActive
				return
			$rootScope.$on 'swiperChaged', ->
				scope.main.update()
				return
			# $rootScope.$on 'destroySwiper', ->
			# 	console.log angular.equals({}, scope.main), angular.equals({}, scope.nav)
			# 	scope.main.destroy() if not angular.equals({}, scope.main)
			# 	#scope.nav.destroy() if not angular.equals({}, scope.nav)
			# 	return
				
			#slideChage()
			return