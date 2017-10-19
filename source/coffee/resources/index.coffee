WPAPI = require 'wpapi'
wp = new WPAPI
	endpoint :
		"#{vars.main.base}/wp-json/"

catellani = angular.module 'catellani'
catellani
	.factory 'WPAPI', ->
		wp
	.factory 'wpApi', ['$http', ($http)->
		deafults =
			name : 'wp'
			ver : 'v2'
			endpoint : ''
			params : {}
		(options)->
			options = angular.extend {}, deafults, options
			$http
				.get "#{vars.main.base}/options.name/options.ver/options.endpoint",
					data : options.params
	]
	.factory 'transformRequestAsFormPost', ->
		serializeData = (data)->
			if not require('angular').isObject data
				string = if not data? then "" else data.toString()
				return string
			buffer = []
			for k, v of data
				if angular.isObject v
					values = []
					for key, value of v
						values.push "#{encodeURIComponent( if not value? then '' else value )}"
						v = values.join( ", " ).replace( /%20/g, " " )
				if not data.hasOwnProperty k
					continue
				buffer.push "#{encodeURIComponent( k )}=#{encodeURIComponent( if not v? then '' else v )}"
			source = buffer.join( "&" ).replace( /%20/g, "+" )
			return source
		transformRequest = (data, getHeaders)->
			headers = getHeaders()
			headers[ "Content-type" ] = "application/x-www-form-urlencoded; charset=utf-8";
			serializeData data
	.filter 'resize', ->
		resize = (item)->
			return if not item._embedded['wp:featuredmedia']? or typeof item._embedded['wp:featuredmedia'] is 'undefined'
			return if item._embedded['wp:featuredmedia'][0].code
			img = item._embedded['wp:featuredmedia'][0]
			url = if img.media_details.sizes.magazine then img.media_details.sizes.large.source_url else img.media_details.sizes.full.source_url
			alt = img.alt_text
			array =
				url : url
				alt : alt
	.filter 'highlight', ["$sce", ($sce)->
		(input, search)->
			return '' if typeof input is 'function'
			if search
				words = search.trim()
				words = "(#{search.split(' ').join('|')})"
				exp = new RegExp words, 'gi'
				if words.length
					input = input.replace exp, "<strong>$1</strong>"
			$sce.trustAsHtml input
	]
	# .filter 'taxSearch', ->
	# 	(items, search)->
	# 		if angular.equals {}, search
	# 			return items
	# 		filtered = items.filter (arrayItem)->
	# 			match = off
	# 			for k, v of search
	# 				if not arrayItem.hasOwnProperty(k) or k is '$$hashKey'
	# 					continue
	# 				if search.hasOwnProperty(k)
	# 					regex = new RegExp "\\b(#{v.replace(/,/g, '|')})\\b", 'g'
	# 					if regex.test( arrayItem[k] )
	# 						match = on
	# 						break
	# 			return match
	# 		return filtered
	.filter 'taxSearch', ->
		(items, search)->
			if angular.equals {}, search
				return items
			filtered = items.filter (arrayItem)->
				regex = {}
				angular.forEach search, (v, k)->
					regex[k] = new RegExp "\\b(#{v.replace(/,/g, '|')})\\b", 'g'
					return
				match = off
				if search.hasOwnProperty('collezioni') and search.hasOwnProperty('posizioni') and search.hasOwnProperty('fonti')
					cond1 = regex['collezioni'].test( arrayItem['collezioni'] )
					cond2 = regex['posizioni'].test( arrayItem['posizioni'] )
					cond3 = regex['fonti'].test( arrayItem['fonti'] )
					match = cond1 and cond2 and cond3
				else if search.hasOwnProperty('collezioni') and search.hasOwnProperty('posizioni')
					cond1 = regex['collezioni'].test( arrayItem['collezioni'] )
					cond2 = regex['posizioni'].test( arrayItem['posizioni'] )
					match = cond1 and cond2
				else if search.hasOwnProperty('posizioni') and search.hasOwnProperty('fonti')
					cond2 = regex['posizioni'].test( arrayItem['posizioni'] )
					cond3 = regex['fonti'].test( arrayItem['fonti'] )
					match = cond2 and cond3
				else if search.hasOwnProperty('collezioni') and search.hasOwnProperty('fonti')
					cond1 = regex['collezioni'].test( arrayItem['collezioni'] )
					cond3 = regex['fonti'].test( arrayItem['fonti'] )
					match = cond1 and cond3	
				else
					angular.forEach search, (v, k)->
						cond = regex[k].test( arrayItem[k] )
						match = cond
						return
				return match
			return filtered
	.filter 'filterMultiple', ['$filter', ($filter)->
		(items, keyObj)->
			if angular.equals {}, keyObj
				return items
			filterObj =
				data : items
				filteredData : []
				compare : (actual, expected)->
					regex = new RegExp "\\b(#{expected.replace(/,/g, '|')})\\b", 'g'
					cond = regex.test actual
					return cond
				applyFilter : (obj,key)->
					fData = []
					@filteredData = @data if @filteredData.length is 0
					if obj
						fObj = {}
						fObj[key] = obj
						#regex = new RegExp "\\b(#{obj.replace(',', '|')})\\b", 'g'
						#fObj[key] = obj
						fData = fData.concat($filter('filter')(@filteredData, fObj, @compare))
						# for el in @filteredData
						# 	console.log regex.test( el[key] )
						# 	fData.push( el ) if regex.test( el[key] )
						# fData = fData.concat fData
						# if not angular.isArray obj
						# 	fObj[key] = obj
						# 	fData = fData.concat($filter('filter')(@filteredData,fObj))
						# else if angular.isArray(obj)
						# 	if obj.length > 0
						# 		for i in obj
						# 			if angular.isDefined i
						# 				fObj[key] = i
						# 				fData = fData.concat($filter('filter')(@filteredData,fObj))
						@filteredData = fData if fData.length > 0
					return
			if keyObj
				angular.forEach keyObj, (obj,key)->
					filterObj.applyFilter(obj,key)
					return
			return filterObj.filteredData
	]