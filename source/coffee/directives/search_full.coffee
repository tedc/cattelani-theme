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
			$scope.select = {}
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
				$scope.select[s] = i.name	
				callback = ->
					$scope.search[s] = String(i.id)
					$rootScope.$broadcast 'scrollBarUpdate'
					return
				closeAnim callback
				return
			$scope.isOrder = off
			$scope.changOrder = ->
				callback =(val) ->
					$scope.isOrder = off
					$scope.order = val
					return
				closeAnim callback
				return
			$scope.orderValue = if $scope.order is '+title' then 'A-Z' else 'Z-A'
			$scope.clear = (s)->
				return if $scope.isChanging
				callback = ->
					delete $scope.search[s]
					return
				closeAnim callback
				return
			
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
				return if $filter('filter')($scope.items, $scope.search, $scope.compareTaxes).length <= 0 then on else off

			$scope.compareTaxes = (actual, expected)->
				if not angular.equals {}, $scope.search
					toArray = actual.split ','
					return toArray.indexOf(expected) isnt -1
				else
					return angular.equals actual, expected
			
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