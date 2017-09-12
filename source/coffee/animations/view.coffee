module.exports = ($rootScope, $timeout, $state)->
	views =
		enter : (element, done)->
			prev = $rootScope.PreviousState
			current = $state.current.name
			fromY = if $state.current.name isnt 'app.root' then 0 else -100
			toY = if $state.current.name isnt 'app.root' then -100 else 0
			element.addClass 'view-enter'
			element.addClass 'view-enter-home' if $state.current.name is 'app.root'
			TweenMax.fromTo element, .5,
				{
					yPercent : fromY
				}
				{
					yPercent : toY
					onComplete : ->
						$timeout ->
							element.removeClass 'view-enter'
							TweenMax.set element,
								clearProps : 'all'
							done()
							return
						return
				}
			return
		leave : (element, done)->
			header = element[0].querySelector '.header'
			prev = $rootScope.PreviousState
			current = $state.current.name
			fromY = if $state.current.name isnt 'app.root' then 0 else -100
			toY = if $state.current.name isnt 'app.root' then -100 else 0
			element.addClass 'view-leave'
			
			TweenMax.fromTo element, .5,
				{
					yPercent : fromY
				}
				{
					yPercent : toY
					onComplete : ->
						$timeout ->
							element.removeClass 'view-leave'
							TweenMax.set element,
								clearProps : 'all'
							done()
							return
						return
				}
			return