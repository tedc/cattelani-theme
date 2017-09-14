module.exports = ($rootScope, $timeout, $state)->
	views =
		enter : (element, done)->
			prev = if $rootScope.PreviousState.Name is '' then $rootScope.fromState else $rootScope.PreviousState.Name.replace 'app.', ''
			current = $state.current.name.replace 'app.', ''
			if current is 'root' 
				fromY = -100
				toY = 0
			else if current is 'collection'
				if prev is 'root'
					fromY = 100
					toY = 0
				else
					fromY = -100
					toY = 0
			else
				fromY = 100
				toY = 0
			element.addClass 'view-enter'
			if not $rootScope.isTransitionerActive
				if not $rootScope.prevElement
					TweenMax.fromTo element, .49,
						{
							yPercent : fromY
						}
						{
							yPercent : toY
							onComplete : ->
								$timeout ->
									done()
									element.removeClass 'view-enter'
									TweenMax.set element,
										clearProps : 'all'
									$rootScope.isAnim = off
									return
								return
						}
			else
				$rootScope.$broadcast 'collection_change', index : $rootScope.carouselIndex
				TweenMax.to {number : 0}, .1,
					number : 1
					onCompleteParams : ['{self}']
					onComplete : ->
						$timeout ->
							done()
							$rootScope.isTransitionerActive = off
							element.addClass 'view-enter'
							return
						return
			if $rootScope.prevElement
				element.removeClass 'view-enter'
				done()
				$rootScope.isAnim = off
			return
		leave : (element, done)->
			prev = if $rootScope.PreviousState.Name is '' then $rootScope.fromState else $rootScope.PreviousState.Name.replace 'app.', ''
			current = $state.current.name.replace 'app.', ''
			fromY = if $state.current.name isnt 'app.root' then 100 else -100
			toY = if $state.current.name isnt 'app.root' then 0 else 0
			if current is 'root' 
				fromY = 0
				toY = 100
			else if current is 'collection'
				if prev is 'root'
					fromY = 0
					toY = -100
				else
					fromY = 0
					toY = 100
			else
				fromY = 0
				toY = -100
			element.addClass 'view-leave'
			if not $rootScope.isTransitionerActive
				if not $rootScope.prevElement
					TweenMax.fromTo element, .5,
						{
							yPercent : fromY
						}
						{
							yPercent : toY
							onComplete : ->
								$timeout ->
									done()
									element.removeClass 'view-leave'
									TweenMax.set element,
										clearProps : 'all'
									return
								return
						}
			else
				done()
				element.removeClass 'view-leave'
			if $rootScope.prevElement
				element.addClass 'view-leave-prev'		
				element.removeClass 'view-leave'
				TweenMax.to {num : 0}, .15,
					num : 1
					onCompleteParams : ['{self}']
					onComplete : ->
						$timeout ->
							element.removeClass 'view-leave-prev'		
							done()
							$rootScope.prevElement = off
							return
						return
			return