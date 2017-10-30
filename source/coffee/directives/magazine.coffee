module.exports = ->
	controller : ["$scope", "$timeout", "$attrs", "wpApi", ($scope, $timeout, $attrs, wpApi)->
		$scope.posts = []
		$scope.page = 1
		$scope.lang = $attrs.lang
		$scope.isNotLoading = off
		$scope.firstLoad = off
		$scope.isLoading = off
		getPosts = ->
			return if $scope.isLoading
			$scope.isLoading = on	
			return if $scope.isNotLoading
			wpApi({
				endpoint : 'posts'
				params : 
					page : $scope.page
			})
				.then (res)->
					headers = res.headers()
					res = res.data
					$timeout ->
						$scope.isLoading = off
						$scope.firstLoad = on
						$scope.posts = $scope.posts.concat res
						$scope.page += 1
						$scope.isNotLoading = on if $scope.page > parseInt headers['x-wp-totalpages']
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
