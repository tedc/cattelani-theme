module.exports = ->
	search =
		scope : true
		controller : ["$rootScope", "$scope", "$q", "$attrs", "$timeout", "wpApi", "$animate", "ScrollbarService", "$filter", "$location", ($rootScope, $scope, $q, $attrs, $timeout, wpApi, $animate, ScrollbarService, $filter, $location)->
			$scope.isSearchEnded = off
			$scope.isSelect = {}
			lang = $attrs.lang
			$scope.search = {}
			$scope.select = {
				collezioni : []
				fonti : []
				posizioni : []
			}
			$scope.items = []
			wrapper = angular.element document.querySelector '.search__items'
			$scope.enabled = (cat, id)->
				filter = $filter('filter')($scope.items, {"#{cat}" : id}, on)
				filtered = if filter.length > 0 then on else off
			getSearch = ->
				return if $scope.isSearchEnded
				wpApi
					endpoint : 'collezioni'
					params : 
						per_page : "#{vars.api.count_collections}"
				.then (res)->
					$scope.collections = res.data
					return
				wpApi
					endpoint : 'posizioni'
					params : 
						per_page : "#{vars.api.count_positions}"
				.then (res)->
					$scope.positions = res.data
					return
				wpApi
					endpoint : 'fonti'
					params : 
						per_page : "#{vars.api.count_sources}"
				.then (res)->
					$scope.sources = res.data
					return
				wpApi
					endpoint : 'lampade'
					ver : 'v1'
					name : 'api'
					params :
						lang : lang
				.then (results)->
					$timeout ->
						$scope.items = results.data
						console.log $scope.items
						$rootScope.$broadcast 'scrollBarUpdate'
						$scope.isSearchEnded = on
						return
					return
				return
			$scope.$on 'hash_change', (evt, data)->
				getSearch() if data.hash == 'search'
				return
			$scope.isLoading = off
			$scope.isChanging = off	
			$rootScope.closingModal = off
			closeAnim = (callback)->
				$scope.isChanging = on
				$animate.addClass wrapper, 'search__items--changing' 
					.then ->
						callback()
						TweenMax.to {index : 0}, .25,
							index : 10
							onUpdateParams : ['{self}']
							onComplete : ->
								$timeout ->
									$scope.isChanging = off
									wrapper.removeClass 'search__items--changing'
									return
						return
				return
			$scope.change = (s, i)->
				return if $scope.isChanging
				$scope.isSelect[s] = false
				selectIndex = $scope.select[s].indexOf i.name
				if selectIndex isnt -1
					$scope.select[s].splice selectIndex, 1
				else
					$scope.select[s].push i.name
				val = String(i.id)
				callback = ->
					toArray = if $scope.search[s] then $scope.search[s].split(',') else []
					index = toArray.indexOf val
					if index isnt -1
						toArray.splice index, 1
					else
						toArray.push val
					if toArray.length > 0
						$scope.search[s] = toArray.join()
					else
						delete $scope.search[s]
					$rootScope.$broadcast 'scrollBarUpdate'
					return
				closeAnim callback
				return
			$scope.order = '+title'
			$scope.orderValue = ->
				value = if $scope.order is '+title' then 'A-Z' else 'Z-A'
			$scope.isOrder = off
			$scope.changeOrder = (val)->
				callback = ->
					$timeout ->
						$scope.isOrder = off
						$scope.order = val
						return
					return
				closeAnim callback
				return
			$rootScope.closeModal = ->
				if window.history && window.history.pushState
					history.pushState '', document.title, $location.path()
				else
					$location.hash ''
				$rootScope.closePopup()
				# $rootScope.closingModal = on
				# hash = $location.hash()
				# console.log $location.state()
				# if hash 
				# 	url = $location.url().split('#')[0]
				# 	$location.path(url).replace()
				return
			$rootScope.closePopup = ->
				$rootScope.isPopup = off
				$scope.search = {}
				$scope.select = {
					collezioni : []
					fonti : []
					posizioni : []
				}
				return
			$scope.clear = (name)->
				return if $scope.isChanging
				item = angular.element document.querySelector "[data-select='#{name}']"
				$timeout ->
					item.triggerHandler 'click'
					return
				return
			$scope.selected = (s, name)->
				$scope.select[s].indexOf( name ) isnt -1
			$scope.image = (item)->
				img = $scope.$eval item.image
				url = if typeof img['vertical-thumb'] isnt 'undefined' then img['vertical-thumb'] else img.large
				alt = item.title
				array =
					url : url
					alt : alt
			
			$scope.filtered = ->
				return $filter('taxSearch')($scope.items, $scope.search, true).length <= 0

			# $scope.compareTaxes = (actual, expected)->
			# 	console.log actual, expected
			# 	if not angular.equals {}, $scope.search
			# 		regex = new RegExp "\\b(#{actual.replace(',', '|')})\\b", 'g'
			# 		console.log regex.test( expected )
			# 		return regex.test( expected )
			# 		return true
			# 		# for i in expected
			# 		# 	console.log toArray.indexOf(i) isnt -1, i
			# 		# 	return toArray.indexOf(i) isnt -1
			# 	else
			# 		return angular.equals actual, expected
			
			$scope.$on 'search_ended', ->
				$timeout ->
					$scope.isLoading = on
					return
				return
				
			## MODAL
			$rootScope.modal = (id)->
				return if $rootScope.isRunning
				$rootScope.oldMenu = $rootScope.isMenu
				$rootScope.isMenu = off
				$rootScope.isPopup = on
				$rootScope.modalId = id
				return
			return
		]