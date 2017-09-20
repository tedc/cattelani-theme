module.exports = ->
	search =
		scope : true
		controller : ["$rootScope", "$scope", "$q", "$attrs", "$timeout", "WPAPI", "$animate", "ScrollbarService", ($rootScope, $scope, $q, $attrs, $timeout, WPAPI, $animate, ScrollbarService)->
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
			$scope.collection = (id)->
				$rootScope.$broadcast 'collection_changed', {id : id} if id isnt off			
				return

			$scope.isLoading = off
			$scope.isSearchEnded = off
			$scope.change = (s, i)->
				#e.stopPropagation();
				$scope.isLoading = on
				$scope.search[s] = i.id
				$scope.select[s] = i.name
				$scope.isSelect[s] = false
				$rootScope.$broadcast 'scrollBarUpdate'
				return
			$scope.clear = (s)->
				$scope.isLoading = on
				$scope.search[s] = ''
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
			# getAll = (request)->
			# 	request.then (response)->
			# 		if not response._paging or not response._paging.next
			# 			return response
			# 		array = [response, getAll response._paging.next ]
			# 		$q.all array
			# 			.then (responses)->
			# 				flattened = responses.reduce (a, b)->
			# 					b.concat a
			$scope.page = 1
			getSearch = ->
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
							$scope.isSearchEnded = on if $scope.page > parseInt res._paging.totalPages
							return
						return
				return
			$rootScope.isSearch = off
			$rootScope.startSearch = ->
				return if $rootScope.isSearch
				$rootScope.isSearch = on
				#$scope.isLoading = on
				#getSearch()
				# if per_page > 100
				# 	getAll wp.products().perPage per_page 
				# 		.embed()
				# 		.order 'asc'
				# 		.orderby 'title'
				# 		.then (results)->
				# 			$scope.items = results
				# 			$rootScope.$broadcast 'scrollBarUpdate'
				# 			return
				# else
				# 	wp
				# 		.products()
				# 		.embed()
				# 		.order 'asc'
				# 		.orderby 'title'
				# 		.perPage per_page
				# 		.then (results)->
				# 			$timeout ->
				# 				$scope.items = results
				# 				$rootScope.$broadcast 'scrollBarUpdate'
				# 				return
				# 			, 0
				# 			return
				return
			getSearch()
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
			$scope.$on 'loadMoreSearch', 
			## INFINITI SCROLL
			searchBar = ScrollbarService.getInstance 'search'
			searchBar
				.then (scrollbar)->
					scrollbar.addListener (s)->
						if s.offeset.y >= s.limit.y
							scope.$emit 'loadMoreSearch' if not $scope.isSearchEnded
						return
					return
			return
		]