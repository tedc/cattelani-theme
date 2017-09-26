WPAPI = require 'wpapi'
wp = new WPAPI
	endpoint :
		"#{vars.main.base}/wp-json/"

catellani = angular.module 'catellani'
catellani
	.factory 'WPAPI', ->
		wp
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
	.filter 'taxSearch', ->
		(items, search)->
			filtered = []
			angular.forEach items, (item)->
				if not angular.equals({}, search)
					for k, v of search
						if search.hasOwnProperty(k)
							toArray = item[k].split(',')
							if toArray.indexOf v isnt -1
								items.push item
				return
			return if angular.equals({}, search) then items else filtered