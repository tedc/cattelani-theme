WPAPI = require 'wpapi'
wp = new WPAPI
	endpoint :
		"#{vars.main.base}/wp-json/"
wp.types = wp.registerRoute 'wp/v2', 'multiple-post-type/',
	params : ['collezioni', 'tipologie', 'type', 'lang', 'before', 'after']
wp.collections = wp.registerRoute 'wp/v2', 'collezioni/',
	params : ['lang']
wp.tipologie = wp.registerRoute 'wp/v2', 'tipologie/',
	params : ['lang']
module.exports = ->
	search =
		scope : true
		controller : ["$rootScope", "$scope", "$q", "$attrs", "$timeout", ($rootScope, $scope, $q, $attrs, $timeout)->
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
			
			wp
				.collections()
				.lang lang
				.then (res)->
					$scope.collections = res
					return

			wp
				.tipologie()
				.lang lang
				.then (res)->
					$scope.tipologie = res
					return
			query = ->
				before = if $scope.projects.before then new Date($scope.projects.before) else new Date()
				after = if $scope.projects.after then new Date($scope.projects.after) else new Date(-8000000000)
				kindValue = if $scope.projects.tipologie then $scope.projects.tipologie else 0
				collectionValue = if $scope.projects.collezioni then $scope.projects.collezioni else 0
				collectionPar = if collectionValue is 0 then 'collezioni_exclude' else 'collezioni'
				kindPar = if kindValue is 0 then 'tipologie_exclude' else 'tipologie'
				wp
					.types()
					.type [type]
					.embed()
					.param(collectionPar, collectionValue)
					.param(kindPar, kindValue)
					.before(before)
					.after(after)
					.page $scope.page
			query()
				.then (results)->
					$timeout ->
						$scope.items = results
						$scope.page += 1
						$scope.isNotLoading = on if $scope.page > parseInt results._paging.totalPages
						return
					, 0
					return
			$scope.$on 'projects_changed', ->
				$scope.page = 1
				query()
					.then (results)->
						return if angular.equals results, $scope.items
						$timeout ->
							$scope.items = results
							return
						, 0
					return
				return
			$scope.$on 'loadPojects', ->
				query()
					.then (results)->
						return if angular.equals results, $scope.items
						$timeout ->
							$scope.items = results
							return
						, 0
					return
			$scope.$broadcast 'loadPojects'
			$scope.image = (item)->
				img = item._embedded['wp:featuredmedia'][0]
				url = if img.media_details.sizes.large then img.media_details.sizes.large.source_url else img.media_details.sizes.full.source_url
				alt = img.alt_text
				array =
					url : url
					alt : alt
			return
		]