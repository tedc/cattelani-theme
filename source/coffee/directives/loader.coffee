module.exports = ($timeout)->
	loader = 
		restrict : 'A'
		link : (scope, element, attr)->
			tween = TweenMax.to element, .5,
						maxHeight : "#{(40/16)}em"
						autoAlpha : on
						onComplete : (evt)->
							return if scope.isNotLoading
							$timeout ->
								scope.$emit attr.ngLoader
								return
							return
			scene = new ScrollMagic.Scene
				triggerElement : element[0]
				triggerHook : 'onEnter'
				offset: 20	
			scene
				.setTween tween
				.addTo controller
			return