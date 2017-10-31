module.exports = ($rootScope)->
	controller : ["$rootScope", "$state", "wpApi", "$q", "$timeout", ($rootScope, $state, wpApi, $q, $timeout)->
		glossaryTerms = wpApi {endpoint : "#{vars.main.glossary_slug}"}
		glossary = @
		glossary.isLoading = on
		glossary.isSearch = off
		glossary.items = []
		glossary.$on 'search_terms', ->
			glossary.isLoading = on
			getTerms().then (res)->
				$timeout ->
					glossary.items = res.data
					glossary.isLoading = off
			
					return
				return
			return
		glossary.searchTerms = ->
			glossary.$emit 'search_terms'
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
			glossaryTerms()
				.search glossary.search
				.then (res)->
					deferred.resolve res
					return
			deferred.promise
		return
	]
	controllerAs : 'glossary'