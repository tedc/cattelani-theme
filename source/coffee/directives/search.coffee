module.exports = ->
	search =
		scope : true
		controller : ["$rootScope", "$scope", "$q", "$attrs", "$timeout", "WPAPI", "$animate", "ScrollbarService", "$filter", ($rootScope, $scope, $q, $attrs, $timeout, WPAPI, $animate, ScrollbarService, $filter)->
			wp = WPAPI
			wp.products = wp.registerRoute 'wp/v2', 'lampade/', params : ['collezioni', 'posizioni', 'fonti']
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
					.embed()
					.order 'asc'
					.orderby 'title'
					.page $scope.page
					.then (results)->
						$timeout ->
							$scope.isLoading = off
							$scope.items = $scope.items.concat results
							$scope.page += 1
							$rootScope.$broadcast 'scrollBarUpdate'
							$scope.isSearching = off
							$scope.isSearchEnded = on if $scope.page > parseInt results._paging.totalPages
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
				$scope.isChanging = on
				$scope.search[s] = i.id
				$scope.select[s] = i.name
				$rootScope.$broadcast 'scrollBarUpdate'
				return
			$scope.clear = (s)->
				$scope.isChanging = on
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
				img = item._embedded['wp:featuredmedia'][0]
				url = if typeof img.media_details.sizes['vertical-thumb'] isnt 'undefined' then img.media_details.sizes['vertical-thumb'].source_url else img.media_details.sizes.full.source_url
				alt = img.alt_text
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
				console.log element, phase
				if phase is 'close'
					TweenMax.to {index : 0}, .25
						onCompleteParams : ["{self}"]
						onComplete : ->
							$timeout ->
								$scope.isChanging = off
								return
							return
				return
			$animate.on 'leave', wrapper, close
			$animate.on 'enter', wrapper, close
			$scope.$on 'search_ended', ->
				TweenMax.to {index : 0}, .25
					onCompleteParams : ["{self}"]
					onComplete : ->
						$timeout ->
							$scope.isChanging = off
							return
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