WPAPI = require 'wpapi'
wp = new WPAPI
    endpoint :
        "#{vars.main.base}/wp-json/"
wp.instagram = wp.registerRoute 'api/v1', 'instagram/'
module.exports = ->
    instagram =
        controller : [ '$scope', "$attrs", "$timeout", ($scope, $attrs, $timeout)->
            $scope.resize = (url)->
                return url.replace('150x150/', '640x640/')
            wp.instagram()
                .then (res)->
                    return if res.data.length < 1
                    $timeout ->
                        $scope.items = res.data
                        $scope.username = res.data[0].user.username
                        return
                    return
            return       
        ]