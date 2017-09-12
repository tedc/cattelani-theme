catellani = angular.module 'catellani'
catellani
	.directive 'ngStore', [require './store.coffee'] 
	.directive 'ngForm', [require './form.coffee'] 
	.directive 'collectionSearch', [require './search.coffee']
	.directive 'postTypeArchive', [require './archive.coffee']
	.directive 'ngSm', ["$rootScope", "$timeout", require './sm.coffee'] 
	.directive 'ngSwiper', [ "$timeout", "$rootScope", require './swiper.coffee'] 
	.directive 'ngInstagram', [ require './instagram.coffee']
	.directive 'ngVideo', [ "$rootScope", require './video.coffee'] 
	.directive 'ngPlayer', [ "angularLoad", "$timeout", "$rootScope", require './player.coffee'] 
	.directive 'ngMagazine', [ require './magazine.coffee'] 
	.directive 'ngLoader', [ '$timeout', require './loader.coffee'] 
	.directive 'ngFooter', ["$window", require './footer.coffee']
	.directive 'glossaryAutocomplete', [ require './glossary.coffee']
	.directive 'ngScrollCarousel', ['ScrollbarService', "$window", "$timeout", (ScrollbarService, $window, $timeout)->
		link : (scope, element, attrs)->
			carousel = ScrollbarService.getInstance 'carousel'
			scope.isVisible = off
			scope.isPrev = off
			scope.isNext = off
			w = angular.element $window
			carousel
				.then (scrollbar)->
					$timeout ->
						scope.isVisible = on
						scope.isPrev = if scrollbar.offset.x > 0 then on else off
						scope.isNext = if scrollbar.offset.x < scrollbar.limit.x then on else off
						return
					, 0
					items = scrollbar.targets.content.querySelectorAll '[data-carousel-item]'
					scrollbar.addListener (status)->
						$timeout ->
							scope.inView = []
							for i in items
								if scrollbar.isVisible i
									scope.inView.push parseInt i.getAttribute 'data-carousel-item'	
							scope.isPrev = if status.offset.x > 0 then on else off
							scope.isNext = if status.offset.x < scrollbar.limit.x then on else off
							return
						, 0
						return
					scope.move = (cond)->
						if scope.inView
							item = if cond then scope.inView[0] + 1 else scope.inView[0] - 1
						else
							item = if cond then 1 else 0
						scrollbar.scrollIntoView(items[item])
						return
					w.on 'resize', ->
						$timeout ->
							#scrollbar.update()
							scope.isPrev = if scrollbar.offset.x > 0 then on else off
							scope.isNext = if scrollbar.offset.x < scrollbar.limit.x then on else off
							return
						, 0
						return
					return
			return

	]
	.directive 'clickedElement', ['$rootScope', ($rootScope)->
		clicked =
			restrict : 'A'
			link : (scope, element, attr)->
				element.on 'click', ->
					$rootScope.fromElement = element
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
				scope.moveMask = (evt, id)->
					el = element[0].querySelector 'svg'
					rect = el.getBoundingClientRect()
					size = 
						actual : rect
						real : 
							width : el.querySelector('image').getAttribute 'width'
							height : el.querySelector('image').getAttribute 'height'
					wRatio = size.real.width / size.actual.width 
					hRatio = size.real.height / size.actual.height
					startX = rect.left + document.body.scrollLeft
					startY = element[0].getBoundingClientRect().top + document.body.scrollTop
					moveX = evt.pageX
					moveY = evt.pageY
					TweenMax.to id, .5,
						attr :
							cx : (moveX - startX ) * wRatio
							cy : (moveY - startY) * hRatio
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