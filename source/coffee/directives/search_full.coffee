module.exports = ->
	search =
		scope : true
		controller : ["$rootScope", "$scope", "$q", "$attrs", "$timeout", "WPAPI", "$animate", "ScrollbarService", "$filter", ($rootScope, $scope, $q, $attrs, $timeout, WPAPI, $animate, ScrollbarService, $filter)->
			wp = WPAPI
			wp.products = wp.registerRoute 'api/v1', 'lampade/', params : ['lang']
			wp.collections = wp.registerRoute 'wp/v2', 'collezioni/', params : ['lang']
			wp.positions = wp.registerRoute 'wp/v2', 'posizioni/', params : ['lang']
			wp.sources = wp.registerRoute 'wp/v2', 'fonti/', params : ['lang']
			$rootScope.collection = off
			per_page = if vars.api.count_posts > 100 then 100 else vars.api.count_posts
			lang = $attrs.lang
			$scope.isSelect = {}
			$scope.search = {}
			$scope.select = {}
			$scope.items = []
			$scope.enabled = (cat, id)->
				filter = $filter('filter')($scope.items, {"#{cat}" : id}, on)
				filtered = if filter.length > 0 then on else off
			$scope.page = 1
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
			$scope.collection = (id)->
				$rootScope.$broadcast 'collection_changed', {id : id} if id isnt off			
				return
			$scope.isLoading = off
			$scope.isSearchEnded = off
			$scope.change = (s, i)->
				$scope.isSelect[s] = false
				return if not $scope.enabled s, i.id
				#e.stopPropagation();
				$scope.isLoading = on
				$scope.search[s] = String(i.id)
				$scope.select[s] = i.name
				$rootScope.$broadcast 'scrollBarUpdate'
				return
			$scope.clear = (s)->
				$scope.isLoading = on
				delete $scope.search[s]
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
				# img = item._embedded['wp:featuredmedia'][0]
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
			wrapper = angular.element document.querySelector '.search__items'
			close =  (element, phase)->
				if phase is 'close'
					searchTimeout = $timeout ->
						$timeout.cancel searchTimeout
						$scope.isLoading = off
						return
					, 250
				return
			$animate.on 'leave', wrapper, close
			$animate.on 'enter', wrapper, close
			$scope.$on 'search_ended', ->
				$timeout ->
					$scope.isLoading = off
					return
				, 250
				return
			## MODAL
			$rootScope.modal = (id)->
				return if $rootScope.isRunning
				$rootScope.oldMenu = $rootScope.isMenu
				$rootScope.isMenu = off
				$rootScope.isPopup = !$rootScope.isPopup
				$rootScope.modalId = id
				return
			$scope.$on 'loadMoreSearch', getSearch
			## INFINITI SCROLL
			searchBar = ScrollbarService.getInstance 'search'
			searchBar
				.then (scrollbar)->
					scrollbar.addListener (s)->
						if s.offset.y >= s.limit.y
							$scope.$emit 'loadMoreSearch' if not $scope.isSearchEnded and not $scope.isSearching
						return
					return
			return
		]