# wp = new WPAPI
# 	endpoint :
# 		"#{vars.main.base}/wp-json/"
# wp.types = wp.registerRoute 'wp/v2', 'multiple-post-type/',
# 	params : ['collezioni', 'tipologie', 'type', 'lang', 'before', 'after']
# wp.collections = wp.registerRoute 'wp/v2', 'collezioni/',
# 	params : ['lang']
# wp.tipologie = wp.registerRoute 'wp/v2', 'tipologie/',
# 	params : ['lang']
module.exports = ->
	search =
		controller : ["$rootScope", "$scope", "$q", "$attrs", "$timeout", "$stateParams", "wpApi", ($rootScope, $scope, $q, $attrs, $timeout, $stateParams, wpApi)->
			$rootScope.collection = off
			per_page = if vars.api.count_posts > 100 then 100 else vars.api.count_posts
			lang = $attrs.lang
			type = $attrs.postType
			$scope.isSelect = {}
			$scope.projects = {}
			$scope.select = {
				periodi : false
			}
			$scope.page = 1
			$scope.items = []
			$scope.firstLoad = off
			$scope.isLoading = off
			$scope.isNotLoading = on
			$scope.change = (s, i)->
				#e.stopPropagation();
				$scope.projects[s] = i.id
				$scope.select[s] = i.name
				$scope.isSelect[s] = false
				$scope.$broadcast 'projects_changed'
				return
			$scope.beforeAfter = (obj)->
				for k, v of obj
					$scope.projects[k] = v	
				$scope.$broadcast 'projects_changed'
				$scope.isSelect['periodi'] = false
				return
			
			$scope.image = (item)->
				img = item.post_thumbnail
				url = if img.magazine then img.magazine else img.large
				alt = img.alt
				array =
					url : url
					alt : alt
			
			query = ->
				before = if $scope.projects.before then new Date($scope.projects.before) else new Date()
				after = if $scope.projects.after then new Date($scope.projects.after) else new Date(-8000000000)
				kindValue = if $scope.projects.tipologie then $scope.projects.tipologie else 0
				collectionValue = if $scope.projects.collezioni then $scope.projects.collezioni else 0
				collectionPar = if collectionValue is 0 then 'collezioni_exclude' else 'collezioni'
				kindPar = if kindValue is 0 then 'tipologie_exclude' else 'tipologie'
				wpApi
					endpoint : type
					params
						"#{collectionPar}" : "#{collectionValue}"
						"#{kindPar}" : "#{kindValue}"
						before : before
						after : after
						page : $scope.page
				# wp
				# 	.types()
				# 	.type [type]
				# 	#.embed()
				# 	.param(collectionPar, collectionValue)
				# 	.param(kindPar, kindValue)
				# 	.before(before)
				# 	.after(after)
				# 	.page($scope.page)
			
			$scope.$on 'projects_changed', ->
				$scope.isNotLoading = on
				$scope.isLoading = on
				$scope.firstLoad = off
				$scope.items = []
				$scope.page = 1
				query()
					.then (results)->
						headers = results.headers()
						results = results.data			
						$timeout ->
							if not angular.equals results, $scope.items
								$scope.items = results
								$scope.page += 1
							if results.length > 0
								$scope.isNotLoading = if $scope.page > parseInt headers['x-wp-totalpages'] then on else off
							else
								$scope.isNotLoading = on
							$scope.isLoading = off
							$scope.firstLoad = on
							return
						, 0
					return
				return
			$scope.$on 'loadProjects', ->
				return if $scope.isLoading
				$scope.isLoading = on
				query()
					.then (results)->
						headers = results.headers()
						results = results.data
						$timeout ->
							if not angular.equals results, $scope.items
								$scope.items = $scope.items.concat results
								$scope.page += 1
							if results.length > 0
								$scope.isNotLoading = if $scope.page > parseInt headers['x-wp-totalpages'] then on else off
							else
								$scope.isNotLoading = on
							$scope.isLoading = off
							$scope.firstLoad = on
							return
						return
			$scope.$emit 'loadProjects'
			wpApi
				endpoint : 'collezioni'
			.then (res)->
					$scope.collections = res.data
					if $stateParams.term?
						$scope.change('collezioni', $stateParams.term)
					return
			wpApi
				endpoint : 'tipologie'
			.then (res)->
					$scope.collections = res.data
					if $stateParams.term?
						$scope.change('collezioni', $stateParams.term)
					return
			# wp
			# 	.collections()
			# 	.lang lang
			# 	.then (res)->
			# 		$scope.collections = res
			# 		if $stateParams.term?
			# 			$scope.change('collezioni', $stateParams.term)
			# 		return

			# wp
			# 	.tipologie()
			# 	.lang lang
			# 	.then (res)->
			# 		$scope.tipologie = res
			# 		return
			return
		]