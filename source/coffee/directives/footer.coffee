module.exports = ($window, $document)->
	footer =
		link : (scope, element)->
			w = angular.element $window
			resize = ->
				TweenMax.set 'body',
					paddingBottom : "#{(element[0].offsetHeight / 16)}em"
				return
			resize()
			w.on 'resize', resize
			new ScrollMagic.Scene
					triggerElement: 'body'
					triggerHook : "onLeave"
					offset: $document.find('body')[0].offsetHeight / 2
			.setClassToggle element[0], 'cat--active'
			.addTo controller
			return