catellani = angular.module 'catellani'
catellani
	.directive 'ngStore', [require './store.coffee'] 
	.directive 'ngForm', [require './form.coffee'] 
	#.directive 'collectionSearch', [require './search.coffee']
	.directive 'collectionSearch', [require './search_full.coffee']
	.directive 'postTypeArchive', [require './archive.coffee']
	.directive 'ngSm', ["$rootScope", "$timeout", require './sm.coffee'] 
	.directive 'ngSwiper', [ "$timeout", "$rootScope", '$location', "ScrollbarService", "screenSize", require './swiper.coffee'] 
	.directive 'ngInstagram', [ require './instagram.coffee']
	.directive 'ngVideo', [ "$rootScope", "$timeout", require './video.coffee'] 
	.directive 'ngPlayer', [ "angularLoad", "$timeout", "$rootScope", "$window", require './player.coffee'] 
	.directive 'ngMagazine', [ require './magazine.coffee'] 
	#.directive 'storia', [ 'ScrollbarService', require './storia.coffee']
	.directive 'ngLoader', [ '$timeout', require './loader.coffee'] 
	.directive 'ngFooter', ["$window", "$document", require './footer.coffee']
	.directive 'glossaryAutocomplete', [ require './glossary.coffee']
	.directive 'ngScrollCarousel', ['ScrollbarService', "$window", "$timeout", "$state", "$rootScope", "screenSize", require './carousel.coffee' ]
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
					if vars.main.mobile
						bottom = bottom - 2
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
	# .directive 'homeElement', ['$rootScope', ($rootScope)->
	# 	restrict : 'A'
	# 	link : (scope, element)->
	# 		element.on 'click', ->
	# 			$rootScope.homeClicked = on
	# 			return
	# 		return
	# ]
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
				element.find('img').one 'load', ->
					controller.update on
					return
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
				scope.closeHover = off
				scope.isZoom = []
				#zoomScrollbar = ScrollbarService.getInstance 'zoom'
				scope.x = 0
				scope.y = 0
				scope.isCursor = off
				draggable = element[0].querySelector '.zoom__scroll > img'
				Draggable.create draggable,
					type : 'x,y'
					bounds : element[0].querySelector '.zoom__scroll'
					onDrag : (evt)->
						scope.cursor evt
						return
	
				scope.cursor = (evt)->
					el = element[0].querySelector '.zoom__container'
					startX = el.offsetLeft
					startY = el.offsetTop
					moveX = evt.pageX
					moveY = evt.pageY
					$this = element[0].querySelector '.zoom__cursor'
					h = $this.offsetHeight
					x = if element.hasClass 'zoom--active' then evt.clientX else moveX - startX
					y = if element.hasClass 'zoom--active' then evt.clientY - h else moveY - startY
					TweenMax.to $this, .15,
						left : x
						top : y
					return
				scope.leave = ->
					TweenMax.to element[0].querySelector('.zoom__cursor'), .5,
						left : '50%'
						top : '50%'
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
					img = angular.element "<img src='#{src}' class='lazy-img' />"
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
				element.removeClass 'lazy-loading'
				element.addClass 'lazy-loaded'
				img.remove()
				return
			_appendImg()
			return
	.directive 'ngErrorPage', ['$state', '$animate', ($state, $animate)->
		restrict : 'A'
		link : (scope, element)->
			btn = angular.element element[0].querySelector '.error__contain'
			scope.goToHome = (evt)->
				evt.preventDefault();
				$animate.addClass btn, 'error__contain--hidden'
					.then ->
						$state.go 'app.root'
						return
				return
			return
	]
	.directive 'downloadForm', ["$window", ($window)->
		restrict : 'A'
		link : (scope)->
			scope.download = (id)->
				ajax = vars.api.ajax
				post_pdf = id
				$window.location.href = "#{ajax.url}?action=#{ajax.action}&post_pdf=#{post_pdf}"
					# .get(ajax.url, data)
					# .then (res)->
					# 	console.log res
					# 	return
				return
			return
	]
