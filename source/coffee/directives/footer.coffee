module.exports = ($window)->
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
					triggerElement: '.main'
					triggerHook : 0
					offset: 120
			.setClassToggle element[0], 'cat--inview'
			.addTo controller
			return