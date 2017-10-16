module.exports = ->
	controller : ["$scope", "$timeout", "$attrs", "WPAPI", ($scope, $timeout, $attrs, WPAPI)->
		$scope.posts = []
		$scope.page = 1
		$scope.lang = $attrs.lang
		$scope.isNotLoading = off
		$scope.firstLoad = off
		$scope.isLoading = off
		wp = WPAPI
		getPosts = ->
			return if $scope.isLoading
			$scope.isLoading = on	
			return if $scope.isNotLoading
			wp
				.posts()
				.param 'lang', $scope.lang
				.embed()
				.page $scope.page
				.then (res)->
					$timeout ->
						$scope.isLoading = off
						$scope.firstLoad = on
						$scope.posts = $scope.posts.concat res
						$scope.page += 1
						$scope.isNotLoading = on if $scope.page > parseInt res._paging.totalPages
						return
					return
			return
		$scope.image = (item)->
			img = item.post_thumbnail
			url = if img.magazine then img.magazine else img.large
			alt = img.alt
			array =
				url : url
				alt : alt
		$scope.$on 'loadPosts', getPosts
		$scope.$broadcast 'loadPosts'
		return
	]
