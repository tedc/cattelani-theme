module.exports = ($stateProvider, $locationProvider)->
	$locationProvider.html5Mode
		enabled: on
		rewriteLinks: off
		requireBase: off

	$stateProvider
		.state 'app',
			abstract : on
			url : '/{lang:(?:it|en)}?search'
			params: 
				lang : 
					squash : true
					value: 'it'
				search :
					dynamic : true
			template: '<ui-view class="view" />'
			controller: ["$rootScope", "$stateParams", ($rootScope, $stateParams)->
				$rootScope.$broadcast 'collection_changed', {id : $stateParams.search } if  $stateParams.search
				return
			]
		.state 'app.root',
			url: '/'
			templateUrl : "#{vars.main.assets}tpl/post.tpl.html"
			resolve: 
				data : ["$stateParams", "$q", "WPAPI", ($stateParams, $q, WPAPI)->
					deferred = $q.defer()
					wp = WPAPI
					wp
						.pages()
						.slug "#{vars.main.home}"
						.param 'lang', $stateParams.lang
						.then (res)->
							deferred.resolve res[0]
							return
					deferred.promise
				]
				PreviousState: ["$state", "$rootScope", ($state, $rootScope)->
					$rootScope.PreviousState =
						Name: $state.current.name,
						Params: $state.params,
						URL: $state.href $state.current.name, $state.params
					return
				]
				ScrollBefore : ["$q", "$timeout", "$rootScope", require './resolveScroll.coffee']
			controller: ["$rootScope", "$scope", "data", require './single.coffee' ]
		.state 'app.page',
			url: '/:slug'
			templateUrl: "#{vars.main.assets}tpl/post.tpl.html"
			resolve : 
				data : ["$stateParams", "$q", "WPAPI", ($stateParams, $q, WPAPI)->
					wp = WPAPI
					wp.multiple = wp.registerRoute 'wp/v2', 'multiple-post-type/',
						params : ['type', 'lang', 'collezioni']
					deferred = $q.defer()
					wp.multiple()
						.type(['post', 'page', 'lampade', 'progetti', 'installazioni'])
						.slug($stateParams.slug)
						.lang($stateParams.lang)
						.then (res)->
							deferred.resolve res[0]
							return
					deferred.promise
				]	
				PreviousState: ["$state", "$rootScope", ($state, $rootScope)->
					$rootScope.PreviousState =
						Name: $state.current.name,
						Params: $state.params,
						URL: $state.href $state.current.name, $state.params
					return
				]		
				ScrollBefore : ["$q", "$timeout", "$rootScope", require './resolveScroll.coffee']
			controller: ["$rootScope", "$scope", "data", require './single.coffee' ]
		.state 'app.collection',
			url : '/{collection:(?:collection|collezioni)}/:name'
			params : 
				collection :
					value : 'collezioni'
			templateUrl: "#{vars.main.assets}tpl/post.tpl.html"
			resolve : 
				data : ["$stateParams", "$q", "WPAPI", ($stateParams, $q, WPAPI)->
					wp = WPAPI
					wp.collections = wp.registerRoute 'wp/v2', 'collezioni/',
						params : ['lang']
					deferred = $q.defer()
					wp
						.collections()
						.slug $stateParams.name
						.lang $stateParams.lang
						.then (res)->
							deferred.resolve res[0]
							return
					deferred.promise
				]					
				PreviousState: ["$state", "$rootScope", ($state, $rootScope)->
					$rootScope.PreviousState =
						Name: $state.current.name,
						Params: $state.params,
						URL: $state.href $state.current.name, $state.params
					return
				]
				ScrollBefore : ["$q", "$timeout", "$rootScope", require './resolveScroll.coffee']
			controller: ["$rootScope", "data", "$scope", require './term.coffee' ]
		.state 'app.glossary',
			url : "/#{vars.main.glossary}/:name"
			templateUrl: "#{vars.main.assets}tpl/post.tpl.html"
			resolve : 
				data : ["$stateParams", "$q", "WPAPI", ($stateParams, $q, WPAPI)->
					wp = WPAPI
					wp.glossary =  wp.registerRoute 'wp/v2', "#{vars.main.glossary}/",
						params : ['lang']
					deferred = $q.defer()
					wp
						.glossary()
						.slug $stateParams.name
						.lang $stateParams.lang
						.then (res)->
							deferred.resolve res[0]
							return
					deferred.promise
				]	
				PreviousState: ["$state", "$rootScope", ($state, $rootScope)->
					$rootScope.PreviousState =
						Name: $state.current.name,
						Params: $state.params,
						URL: $state.href $state.current.name, $state.params
					return
				]
				ScrollBefore : ["$q", "$timeout", "$rootScope", require './resolveScroll.coffee']
			controller: ["$rootScope", "data", "$scope", require './term.coffee' ]
	return