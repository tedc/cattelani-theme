module.exports = (ScrollbarService, $window, $timeout, $state, $rootScope)->
	link : (scope, element, attrs)->
		carousel = ScrollbarService.getInstance 'carousel'
		scope.isVisible = off
		scope.isPrev = off
		scope.isNext = off
		w = angular.element $window
		carousel
			.then (scrollbar)->
				scope.isState = off
				scope.currentState = 
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
						console.log item
					else
						item = if cond then 1 else 0
						console.log item
					scrollbar.scrollIntoView(items[item])
					return
				scope.goto = (index, params)->
					scrollbar.removeListener()
					if scrollbar.isVisible items[index]
						if index isnt 0
							left = if items[index].offsetWidth isnt scrollbar.getSize().container.width then items[index].offsetLeft - items[index].offsetWidth else items[index].offsetLeft
							scrollbar.scrollTo left, 0, 750, ->
								scope.isState = on
								scope.currentState = params.slug
								$timeout ->
									$state.go 'app.page', params
									return
								, 400
								return
						else
							$timeout ->
								$state.go 'app.page', params
								return
							, 400
					else
						width = if items[index].offsetWidth isnt scrollbar.getSize().container.width then items[index].offsetLeft - items[index].offsetWidth else items[index].offsetLeft
						left = if index is 0 then items[index].offsetLeft else width
						scrollbar.scrollTo left, 0, 750, ->
							scope.isState = on
							scope.currentState = params.slug
							$timeout ->
								$state.go 'app.page', params
								return
							, 400
							return
					return
				scope.$on 'collection_change', (evt, data)->
					index = parseInt data.index
					width = if items[index].offsetWidth isnt scrollbar.getSize().container.width then items[index].offsetLeft - items[index].offsetWidth else items[index].offsetLeft
					left = if index is 0 then items[index].offsetLeft else width
					console.log left, scrollbar
					scrollbar.scrollTo left, 0, 0
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