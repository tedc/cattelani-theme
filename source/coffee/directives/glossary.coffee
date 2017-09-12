module.exports = ($rootScope)->
	controller : ["$rootScope", "$state", "WPAPI", "$q", "$timeout", ($rootScope, $state, WPAPI, $q, $timeout)->
		wp = WPAPI
		wp.glossaryTerms = wp.registerRoute 'wp/v2', "#{vars.main.glossary_slug}/"
		glossary = @
		glossary.isSearch = off
		glossary.items = []
		$rootScope.$on 'search_terms', ->
			getTerms().then (res)->
				$timeout ->
					glossary.items = res
					return
				return
			return
		glossary.searchTerms = ->
			$rootScope.$broadcast 'search_terms'
			return
		glossary.goToTerm = (term)->
			glossary.isSearch = on
			glossary.search = term.name
			$timeout ->
				$rootScope.isGlossary[term.id] = on
				return
			$state.go('app.glossary', {name : term.cat_slug})
			return
			
		getTerms = ->
			deferred = $q.defer()
			wp
				.glossaryTerms()
				.search glossary.search
				.then (res)->
					deferred.resolve res
					return
			deferred.promise
		return
	]
	controllerAs : 'glossary'