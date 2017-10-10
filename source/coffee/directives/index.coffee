catellani = angular.module 'catellani'
catellani
	.directive 'ngStore', [require './store.coffee'] 
	.directive 'ngForm', [require './form.coffee'] 
	#.directive 'collectionSearch', [require './search.coffee']
	.directive 'collectionSearch', [require './search_full.coffee']
	.directive 'postTypeArchive', [require './archive.coffee']
	.directive 'ngSm', ["$rootScope", "$timeout", require './sm.coffee'] 
	.directive 'ngSwiper', [ "$timeout", "$rootScope", require './swiper.coffee'] 
	.directive 'ngInstagram', [ require './instagram.coffee']
	.directive 'ngVideo', [ "$rootScope", require './video.coffee'] 
	.directive 'ngPlayer', [ "angularLoad", "$timeout", "$rootScope", require './player.coffee'] 
	.directive 'ngMagazine', [ require './magazine.coffee'] 
	#.directive 'storia', [ 'ScrollbarService', require './storia.coffee']
	.directive 'ngLoader', [ '$timeout', require './loader.coffee'] 
	.directive 'ngFooter', ["$window", require './footer.coffee']
	.directive 'glossaryAutocomplete', [ require './glossary.coffee']
	.directive 'ngScrollCarousel', ['ScrollbarService', "$window", "$timeout", "$state", "$rootScope", require './carousel.coffee' ]
	.directive 'clickedElement', ['$rootScope', ($rootScope)->
		clicked =
			restrict : 'A'
			link : (scope, element, attr)->
				element.on 'click', ->
					$rootScope.fromElement = element
					return
				return
	]
	.directive 'nextElement', ['$rootScope', ($rootScope)->
		clicked =
			restrict : 'A'
			link : (scope, element, attr)->
				element.on 'click', ->
					rect = element[0].getBoundingClientRect()
					element.addClass 'next--active'
					top = rect.top
					bottom = window.innerHeight - rect.bottom
					TweenMax.set element,
						top : top
						bottom : bottom
					$rootScope.prevElement = element
					return
				return
	]
	.directive 'menu', ['$rootScope', ($rootScope)->
		restrict : 'A'
		scope : 
			cond : '=menu'
		link : (scope, element)->
			$rootScope.isRunning = off
			element.on 'click', ->
				return if $rootScope.isRunning
				$rootScope.isRunning = on
				$rootScope.isMenu = scope.cond
				return
			return
	]
	.directive 'onFinishRender', ['$timeout',($timeout)->
		onFinish =
			restrict : 'A'
			link : (scope, element, attr)->
				if scope.$last is true
					$timeout ->
						scope.$emit attr.onFinishRender
						return
				return
	]
	.directive 'ngLightMask', ->
		mask =
			link : (scope, element)->
				scope.onOff = off
				scope.moveMask = (evt, id)->
					body = document.body
					docEl = document.documentElement
					el = element[0].querySelector 'svg'
					rect = el.getBoundingClientRect()
					size = 
						actual : rect
						real : 
							width : el.querySelector('image').getAttribute 'width'
							height : el.querySelector('image').getAttribute 'height'
					wRatio = size.real.width / size.actual.width 
					hRatio = size.real.height / size.actual.height
					## For scrollX
					scrollTop = window.pageYOffset or docEl.scrollTop or body.scrollTop
					scrollLeft = window.pageXOffset or docEl.scrollLeft or body.scrollLeft
					startX = rect.left + scrollLeft
					startY = element[0].getBoundingClientRect().top + scrollTop
					moveX = evt.pageX
					moveY = evt.pageY
					TweenMax.to "#{id} .light__circle", .5,
						# attr :
						# 	cx : ~~((moveX - startX ) * wRatio)
						# 	cy : ~~((moveY - startY) * hRatio)
						x : ~~((moveX - startX ) * wRatio)
						y : ~~((moveY - startY) * hRatio)
					return
				return
	.directive 'ngZoom', ['ScrollbarService', '$timeout', (ScrollbarService, $timeout)->
		zoom = 
			scope : on
			link : (scope, element)->
				scope.isZoom = []
				zoomScrollbar = ScrollbarService.getInstance 'zoom'
				scope.x = 0
				scope.y = 0
				zoomScrollbar.then (scrollbar)->
					scope.updateScrollbar = ->
						$timeout ->
							size = scrollbar.getSize()
							x = if size.content.width > size.container.width then (size.content.width - size.container.width) / 2 else 0
							y = if size.content.height > size.container.height then (size.content.height - size.container.height) / 2 else 0
							scrollbar.setPosition x, y
							scrollbar.update()
							return
						, 500
						return
					container = angular.element scrollbar.targets.container
					return
				
				scope.cursor = (evt)->
					startX = element[0].offsetLeft
					startY = element[0].offsetTop
					moveX = evt.pageX
					moveY = evt.pageY
					$this = element[0].querySelector '.zoom__cursor'
					h = $this.offsetHeight
					x = if element.hasClass 'zoom--active' then evt.clientX else moveX - startX
					y = if element.hasClass 'zoom--active' then evt.clientY - h else moveY - startY
					TweenMax.set $this,
						left : x
						top : y
					return
				return
	]