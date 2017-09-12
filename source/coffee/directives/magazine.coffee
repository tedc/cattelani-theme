module.exports = ->
	controller : ["$scope", "$timeout", "$attrs", "WPAPI", ($scope, $timeout, $attrs, WPAPI)->
		$scope.posts = []
		$scope.page = 1
		$scope.lang = $attrs.lang
		$scope.isNotLoading = off
		wp = WPAPI
		getPosts = ->
			return if $scope.isNotLoading
			wp
				.posts()
				.param 'lang', $scope.lang
				.embed()
				.page $scope.page
				.then (res)->
					$timeout ->
						$scope.posts = $scope.posts.concat res
						$scope.page += 1
						$scope.isNotLoading = on if $scope.page > parseInt res._paging.totalPages
						return
					return
			return
		$scope.image = (item)->
			return if not item._embedded['wp:featuredmedia']? or typeof item._embedded['wp:featuredmedia'] is 'undefined'
			return if item._embedded['wp:featuredmedia'][0].code
			img = item._embedded['wp:featuredmedia'][0]
			url = if img.media_details.sizes.magazine then img.media_details.sizes.large.source_url else img.media_details.sizes.full.source_url
			alt = img.alt_text
			array =
				url : url
				alt : alt
		$scope.$on 'loadPosts', getPosts
		$scope.$broadcast 'loadPosts'
		return
	]
