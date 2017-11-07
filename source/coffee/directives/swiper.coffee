module.exports = ($timeout, $rootScope, $location, ScrollbarService, screenSize)->
	ngSwiper =
		scope : on
		link : (scope, element, attr)->
			screenSize.rules = {
				min : "screen and (min-width: #{(850/16)}em)"
			}	
			scope.main = {}
			scope.nav = {}
			scope.storia = {} if attr.isStoria
			scope.navInit = off
			scope.current = 0
			$rootScope.isYears = 0 if attr.isStoria
			if $rootScope.currentCollection
				if attr.isHome
					s = element[0].querySelector "[data-collection='#{$rootScope.currentCollection}']"
					i = parseInt s.getAttribute 'data-index'
					# if $rootScope.homeClicked
					# 	scope.start = i
					# else	
					# 	i = if i - 1 is 0 then 0 else i - 1
					# 	scope.start = i
					scope.start = i
					$rootScope.currentCollection = off
					$rootScope.homeClicked = off
			else		
				scope.start = 0
			scope.next = (val)->
				scope.storia.slideNext() if scope.storia.slideNext
				$timeout ->
					$rootScope.isYears = val
					return
				#scope.nav.slideNext() if scope.nav.slideNext
				return
			scope.prev = (swiper)->
				scope.main.slidePrev() if scope.main.slidePrev
				#scope.nav.slidePrev() if scope.nav.slidePrev
				#scope.current = scope.main.realIndex
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
			scroll = ()->
				if not vars.main.mobile
					offset = if angular.element(document.body).hasClass('admin-bar') then 112 else 80
				else
					offset = if screenSize.is 'min' then 180 else 140
				TweenMax.to window, .5,
					scrollTo :
						y : "#storia"
						autoKill : off
						offsetY : offset
				return
			scope.$watch 'storia', (newValue, oldValue)->
				return if oldValue is newValue
				scope.storia.on 'slideChangeStart', (swiper)->
					scope.current = swiper.realIndex
					swiper.update()
					scroll()
					return
				hash = $location.hash()
				if hash and hash isnt 'contact' and hash isnt 'search' and hash isnt 'languages' and hash isnt 'downloads'
					slide = element[0].querySelector "[data-hash='#{hash}']"
					index = parseInt slide.getAttribute 'data-current'
					scope.current = index
					scroll()
				return
				# swiper.on 'slideChangeStart', (swiper)->
				# 	scope.current = swiper.realIndex 
				# 	return
			if attr.isStoria
				storia = ScrollbarService.getInstance 'storia'
				storia
					.then (scrollbar)->
						arrows = ->
							$timeout ->
								scope.isPrev = if scrollbar.offset.x isnt 0 then on else off
								scope.isNext = if scrollbar.offset.x isnt scrollbar.limit.x then on else off
								return
							return
						item = element[0].querySelector('.storia__items .storia__item')
						last = element[0].querySelector('.storia__items .storia__item:nth-last-child(1)')
						scope.storiaMove = (cond)->
							if cond
								return if scrollbar.offset.x is scrollbar.limit.x
							else
								return if scrollbar.offset.x is 0
							x = if cond then scrollbar.offset.x + item.offsetWidth else scrollbar.offset.x - item.offsetWidth
							scrollbar.scrollTo x, 0, 500
							return
						arrows()
						angular.element(window).on 'resize', arrows
						scrollbar.addListener (status)->
							arrows()
							return
						return
			scope.expandStory = (cond)->
				$rootScope.isYearsActive = !$rootScope.isYearsActive
				scroll()
				return
			scope.$on 'swiperChaged', ->
				scope.main.update() if not angular.equals {}, scope.main
				scope.storia.update() if attr.isStoria and not angular.equals {}, scope.storia
				return
			
			element.on '$destroy', ->
				$rootScope.isYearsActive = off
				return
			return