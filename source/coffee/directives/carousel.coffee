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
				$rootScope.currentCollection = attrs.currentCollection 
				$timeout ->
					scope.isVisible = on
					scope.isPrev = if scrollbar.offset.x > 0 then on else off
					scope.isNext = if scrollbar.offset.x < scrollbar.limit.x then on else off
					return
				, 0
				items = scrollbar.targets.content.querySelectorAll '[data-carousel-item]'
				# customController = new ScrollMagic.Controller {container : scrollbar.targets.container, vertical : off}
				# scenes = off
				# addScenes = ->
				# 	return if scenes
				# 	for el in items
				# 		width = el.offsetWidth
				# 		duration = width * 1.5
				# 		scene1 = new ScrollMagic.Scene
				# 				triggerHook : 1
				# 				triggerElement : el
				# 				duration : duration
				# 			.setTween TweenMax.from el.querySelector('.collections__cover'), .5, {backgroundPosition : "85% 50%"}
				# 		scene2 = new ScrollMagic.Scene
				# 				triggerHook : 0.5
				# 				triggerElement : el
				# 				duration : duration
				# 				offset : width / 2
				# 			.setTween TweenMax.to el.querySelector('.collections__cover'), .5, {backgroundPosition : "15% 50%"}
				# 		scene1.addTo customController
				# 		scene2.addTo customController
				# 	return
				# customController.scrollPos ->
				# 	scrollbar.offset.x
				scrollbar.addListener (status)->
					# addScenes()
					# scenes = on
					# customController.update()
					$timeout ->
						scope.inView = []
						for i in items
							if scrollbar.isVisible i
								scope.inView.push parseInt i.getAttribute 'data-carousel-item'
							else
								idx = parseInt i.getAttribute 'data-carousel-item'
								idx = scope.inView.indexOf idx
								scope.inView.splice idx, 1 if idx > -1 
						scope.isPrev = if status.offset.x > 0 then on else off
						scope.isNext = if status.offset.x < scrollbar.limit.x then on else off
						return
					, 0
					return
				scope.move = (cond)->
					if cond
						return if not scope.isNext
					else
						return if not scope.isPrev
					if scope.inView
						item = if cond then scope.inView[0] + 1 else scope.inView[0] - 1
					else
						item = if cond then 1 else 0
					item = if item < 0 then 0 else item
					scrollbar.scrollIntoView(items[item])
					return
				scope.goto = (index, params)->
					scrollbar.removeListener()
					if scrollbar.isVisible items[index]
						if index isnt 0 and index isnt parseInt items[index].getAttribute 'data-item-total'
							left = if items[index].offsetWidth isnt scrollbar.getSize().container.width then items[index].offsetLeft - items[index].offsetWidth else items[index].offsetLeft
							if index is 1 and items.length <= 3
								$state.go 'app.page', params
							else
								scrollbar.scrollTo left, 0, 750, ->
									scope.isState = on
									scope.currentState = params.slug
									$state.go 'app.page', params
									return
						else
							$state.go 'app.page', params
					else
						width = if items[index].offsetWidth isnt scrollbar.getSize().container.width then items[index].offsetLeft - items[index].offsetWidth else items[index].offsetLeft
						left = if index is 0 then items[index].offsetLeft else width
						scrollbar.scrollTo left, 0, 750, ->
							scope.isState = on
							scope.currentState = params.slug
							$state.go 'app.page', params
							return
					return
				scope.$on 'collection_change', (evt, data)->
					$timeout ->
						index = parseInt data.index
						width = if items[index].offsetWidth isnt scrollbar.getSize().container.width then items[index].offsetLeft - items[index].offsetWidth else items[index].offsetLeft
						left = if index is 0 then items[index].offsetLeft else width
						left = if left > scrollbar.limit.x then scrollbar.limit.x else left
						scrollbar.scrollTo left, 0, 0
						return
					return
				
				scope.key = (e)->
					move on if e.keyCode is 40 or e.keyCode is 39
					move off if e.keyCode is 38 or e.keyCode is 37
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