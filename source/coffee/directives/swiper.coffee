module.exports = ($timeout, $rootScope)->
	ngSwiper =
		scope : on
		link : (scope, element, attr)->
			scope.main = {}
			scope.nav = {}
			scope.storia = {} if attr.isStoria
			scope.current = 0
			scope.navInit = off
			if $rootScope.currentCollection
				s = element[0].querySelector "[data-collection='#{$rootScope.currentCollection}']"
				i = parseInt s.getAttribute 'data-index'
				if $rootScope.homeClicked
					scope.start = i
				else	
					i = if i - 1 is 0 then 0 else i - 1
					scope.start = i
				$rootScope.currentCollection = off
				$rootScope.homeClicked = off
			else		
				scope.start = 0
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
				#scope.main.slideTo index if scope.main.slideTo
				scope.nav.slideTo index if scope.nav.slideTo
				scope.storia.slideTo index if scope.storia.slideTo and attr.isStoria
				#scope.current = scope.main.realIndex
				#console.log index
				#slideChage() if scope.main.on
				#scope.current = index
				return
			scope.$watch 'main', (newValue, oldValue)->
				return if oldValue is newValue
				scope.$watch 'nav', ->
					if scope.nav.params && scope.main.params
						#scope.nav.params.control = scope.main 
						#scope.main.params.control = scope.nav 
						scope.nav.on 'slideChangeStart', (swiper)->
							scope.main.slideTo swiper.realIndex if scope.main.realIndex isnt swiper.realIndex
							return
						scope.main.on 'slideChangeStart', (swiper)->
							scope.nav.slideTo swiper.realIndex if scope.nav.realIndex isnt swiper.realIndex
							return
						$timeout ->
							scope.navInit = on
							return
						scope.nav.update()
						scope.main.update()
						scope.main.slideTo scope.start, 0
					return
				return
			$rootScope.isYearsActive = off
			scope.expandStory = (cond)->
				$rootScope.isYearsActive = !$rootScope.isYearsActive
				return
			$rootScope.$on 'swiperChaged', ->
				scope.main.update()
				scope.storia.update() if attr.isStoria
				return
			
			# $rootScope.$on 'destroySwiper', ->
			# 	console.log angular.equals({}, scope.main), angular.equals({}, scope.nav)
			# 	scope.main.destroy() if not angular.equals({}, scope.main)
			# 	#scope.nav.destroy() if not angular.equals({}, scope.nav)
			# 	return
				
			#slideChage()
			return