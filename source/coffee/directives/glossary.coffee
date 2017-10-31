module.exports = ($rootScope)->
	controller : ["$rootScope", "$state", "wpApi", "$q", "$timeout", "$scope", ($rootScope, $state, wpApi, $q, $timeout, $scope)->
		glossaryTerms = wpApi {endpoint : "#{vars.main.glossary_slug}"}
		glossary = @
		glossary.isLoading = on
		glossary.isSearch = off
		glossary.items = []
		$scope.$on 'search_terms', (event, data)->
			valid = data.valid
			glossary.isLoading = on
			if valid 
				getTerms().then (res)->
					$timeout ->
						glossary.items = res
						glossary.isLoading = off	
						return
					return
			else
				glossary.items = []
				glossary.isLoading = off
			return
		glossary.searchTerms = (valid)->
			$scope.$emit 'search_terms', { valid : valid }
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
			wpApi 
				endpoint : "#{vars.main.glossary_slug}"
				params :
					search : glossary.search
			.then (res)->
				deferred.resolve res.data
				return
			deferred.promise
		return
	]
	controllerAs : 'glossary'