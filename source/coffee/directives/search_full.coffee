module.exports = ->
	search =
		scope : true
		controller : ["$rootScope", "$scope", "$q", "$attrs", "$timeout", "WPAPI", "$animate", "ScrollbarService", "$filter", ($rootScope, $scope, $q, $attrs, $timeout, WPAPI, $animate, ScrollbarService, $filter)->
			wp = WPAPI
			wp.products = wp.registerRoute 'api/v1', 'lampade/', params : ['lang']
			wp.collections = wp.registerRoute 'wp/v2', 'collezioni/', params : ['lang']
			wp.positions = wp.registerRoute 'wp/v2', 'posizioni/', params : ['lang']
			wp.sources = wp.registerRoute 'wp/v2', 'fonti/', params : ['lang']
			lang = $attrs.lang
			$scope.isSelect = {}
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
				$scope.isSearching = on
				wp
					.products()
					.lang lang
					.then (results)->
						$timeout ->
							$scope.items = results
							$rootScope.$broadcast 'scrollBarUpdate'
							$scope.isSearching = off
							return
						return
				return
			getSearch()
			$scope.isLoading = off
			$scope.isSearchEnded = off
			$scope.isChanging = off	
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
			$scope.clear = (name)->
				return if $scope.isChanging
				item = angular.element document.querySelector "[data-select='#{name}']"
				$timeout ->
					item.triggerHandler 'click'
					return
				return
			$scope.selected = (s, name)->
				$scope.select[s].indexOf( name ) isnt -1
			wp
				.collections()
				.perPage "#{vars.api.count_collections}"
				.lang lang
				.then (res)->
					$scope.collections = res
					return
			wp
				.positions()
				.perPage "#{vars.api.count_positions}"
				.lang lang
				.then (res)->
					$scope.positions = res
					return
			wp
				.sources()
				.perPage "#{vars.api.count_sources}"
				.lang lang
				.then (res)->
					$scope.sources = res
					return
			$scope.image = (item)->
				img = $scope.$eval item.image
				url = if typeof img['vertical-thumb'] isnt 'undefined' then img['vertical-thumb'] else img.large
				alt = item.title
				array =
					url : url
					alt : alt
			$rootScope.isSearch = off
			$rootScope.startSearch = ->
				return if $rootScope.isSearch
				$rootScope.isSearch = on
				return
			
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
				$rootScope.isPopup = !$rootScope.isPopup
				$rootScope.modalId = id
				return
			return
		]