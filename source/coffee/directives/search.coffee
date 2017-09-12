module.exports = ->
	search =
		scope : true
		controller : ["$rootScope", "$scope", "$q", "$attrs", "$timeout", "WPAPI", ($rootScope, $scope, $q, $attrs, $timeout, WPAPI)->
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
			$scope.collection = (id)->
				$rootScope.$broadcast 'collection_changed', {id : id} if id isnt off			
				return

			$scope.change = (s, i)->
				#e.stopPropagation();
				$scope.search[s] = i.id
				$scope.select[s] = i.name
				$scope.isSelect[s] = false
				$rootScope.$broadcast 'scrollBarUpdate'
				return
			
			wp
				.collections()
				.lang lang
				.then (res)->
					$scope.collections = res
					return

			wp
				.positions()
				.lang lang
				.then (res)->
					$scope.positions = res
					return
					
			wp
				.sources()
				.lang lang
				.then (res)->
					$scope.sources = res
					return
			$scope.image = (item)->
				img = item._embedded['wp:featuredmedia'][0]
				url = if img.media_details.sizes.large then img.media_details.sizes.large.source_url else img.media_details.sizes.full.source_url
				alt = img.alt_text
				array =
					url : url
					alt : alt
			getAll = (request)->
				request.then (response)->
					if not response._paging or not response._paging.next
						return response
					array = [response, getAll response._paging.next ]
					$q.all array
						.then (responses)->
							flattened = responses.reduce (a, b)->
								b.concat a
			$rootScope.isSearch = off
			$rootScope.startSearch = ->
				return if $rootScope.isSearch
				$rootScope.isSearch = on
				if per_page > 100
					getAll wp.products().perPage per_page 
						.then (results)->
							$scope.items = results
							$rootScope.$broadcast 'scrollBarUpdate'
							return
				else
					wp
						.products()
						.embed()
						.perPage per_page
						.then (results)->
							$timeout ->
								$scope.items = results
								$rootScope.$broadcast 'scrollBarUpdate'
								return
							, 0
							return
				return
			## MODAL
			$rootScope.modal = (id)->
				$rootScope.oldMenu = $rootScope.isMenu
				$rootScope.isMenu = off
				$rootScope.isPopup = !$rootScope.isPopup
				$rootScope.modalId = id
				return
			return
		]