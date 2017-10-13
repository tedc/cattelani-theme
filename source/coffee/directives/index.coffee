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
	.directive 'lightCollection', ['$rootScope', ($rootScope)->
		restrict : 'A'
		link : (scope, element, attr)->
			$rootScope.currentCollection = attr.lightCollection
			return
	]
	.directive 'nextElement', ['$rootScope', ($rootScope)->
		clicked =
			restrict : 'A'
			link : (scope, element, attr)->
				element.on 'click', ->
					body = angular.element document.body
					body.addClass 'is-to-next'
					rect = element[0].getBoundingClientRect()
					top = rect.top
					height = rect.height
					bottom = window.innerHeight - rect.bottom
					divider = angular.element '<div id="next-divider"></div>'
					TweenMax.set divider,
						height : height
					element.after divider 
					element.addClass 'next--active'
					TweenMax.set element,
						top : top
						bottom : bottom
					$rootScope.prevElement = element
					return
				return
	]
	.directive 'homeElement', ['$rootScope', ($rootScope)->
		restrict : 'A'
		link : (scope, element)->
			element.on 'click', ->
				$rootScope.homeClicked = on
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
	.directive 'ngZoom', ['ScrollbarService', '$timeout', "$document", (ScrollbarService, $timeout, $document)->
		zoom = 
			scope : on
			link : (scope, element)->
				scope.isZoom = []
				#zoomScrollbar = ScrollbarService.getInstance 'zoom'
				scope.x = 0
				scope.y = 0
				draggable = element[0].querySelector '.zoom__scroll > img'
				Draggable.create draggable,
					type : 'x,y'
					bounds : element[0].querySelector '.zoom__scroll'
					onDrag : (evt)->
						scope.cursor evt
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
	.directive 'ngLazyImg', ->
		link : (scope, element, attrs)->
			img = null
			src = attrs.ngLazyImg	
			_appendImg = ->
				if not img
					element.addClass 'lazy-loading'
					img = angular.element "<img src='#{src}' />"
					img.one 'load', _loaded
					img.one 'error', _error
					element.append img
				return
			_loaded = ->
				element.removeClass 'lazy-loading'
				element.addClass 'lazy-loaded'
				img.remove()
				return
			_error = ->
				element.addClass 'lazy-loading-error'
				return
			_appendImg()
			return
			
