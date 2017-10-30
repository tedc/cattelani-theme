module.exports = ($stateProvider, $locationProvider)->
	$locationProvider.html5Mode
		enabled: on
		rewriteLinks: off
		requireBase: off

	$stateProvider
		.state 'app',
			abstract : on
			url : "/{lang:(?:#{vars.main.langs})}"
			params: 
				lang : 
					squash : true
					value: 'it'
				search :
					dynamic : true
				'#' :
					dynamic : on
			template: '<ui-view class="view" />'
			controller: ["$rootScope", "$stateParams", ($rootScope, $stateParams)->
				$rootScope.$broadcast 'collection_changed', {id : $stateParams.search } if  $stateParams.search
				return
			]
		.state 'app.root',
			url: '/'
			template : '<div class="main__content" bind-html-compile="content"></div>'
			resolve: 
				data : ["$stateParams", "$q", "wpApi", ($stateParams, $q, wpApi)->
					deferred = $q.defer()
					wpApi({
						endpoint : 'pages'
						params :
							slug : "#{vars.main.home}"
					}).then (res)->
						deferred.resolve res.data[0]
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
				ScrollBefore : ["$q", "$timeout", "$rootScope", "PreviousState", require './resolveScroll.coffee']
			controller: ["$rootScope", "$scope", "data", require './single.coffee' ]
		.state 'app.page',
			url: '/:slug'
			params :
				term : 
					value : null
			template : '<div class="main__content" bind-html-compile="content"></div>'
			resolve : 
				PrevBefore : ["$rootScope", "$timeout", "$q", require('./blocks.coffee').prev]	
				ScrollBefore : ["$q", "$timeout", "$rootScope", "PreviousState", require './resolveScroll.coffee']
				data : ["$stateParams", "$q", 'wpApi', ($stateParams, $q, wpApi)->
					deferred = $q.defer()
					wpApi({
						endpoint : 'multiple-post-type'
						params :
							"type[]" : ['post', 'page', 'lampade', 'progetti', 'installazioni']
							slug : $stateParams.slug
					}).then (res)->
						deferred.resolve res.data[0]	
						return
					deferred.promise
				]	
				PreviousState: ["$state", "$rootScope", "$stateParams", ($state, $rootScope, $stateParams)->
					$rootScope.PreviousState =
						Name: $state.current.name,
						Params: $state.params,
						URL: $state.href $state.current.name, $state.params
						Slug: $stateParams.slug
					return
				]
				BlocksBefore : ["$rootScope", "$stateParams", "$timeout", "$q", "PreviousState", "screenSize", require('./blocks.coffee').single]
			controller: ["$rootScope", "$scope", "data", require './single.coffee' ]
		.state 'app.collection',
			url : '/c/:name'
			template : '<div class="main__content" bind-html-compile="content"></div>'
			resolve : 
				data : ["$stateParams", "$q", "wpApi", ($stateParams, $q, wpApi)->
					deferred = $q.defer()
					wpApi({
						endpoint : 'collezioni'
						params :
							slug : $stateParams.name
					}).then (res)->
						deferred.resolve res.data[0]
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
				BlocksBefore : ["$rootScope", "$stateParams", "$timeout", "$q", "ScrollBefore", "PreviousState",  "screenSize", require('./blocks.coffee').collection]		
				ScrollBefore : ["$q", "$timeout", "$rootScope", "PreviousState", require './resolveScroll.coffee']
			controller: ["$rootScope", "data", "$scope", require './term.coffee' ]
		.state 'app.glossary',
			url : "/#{vars.main.glossary}/:name"
			template : '<div class="main__content" bind-html-compile="content"></div>'
			resolve : 
				data : ["$stateParams", "$q", "wpApi", ($stateParams, $q, wpApi)->
					deferred = $q.defer()
					wpApi({
						endpoint : "#{vars.main.glossary}"
						params :
							slug : $stateParams.name
					}).then (res)->
						deferred.resolve res.data[0]
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
				ScrollBefore : ["$q", "$timeout", "$rootScope", "PreviousState", require './resolveScroll.coffee']
			controller: ["$rootScope", "data", "$scope", require './term.coffee' ]
	return