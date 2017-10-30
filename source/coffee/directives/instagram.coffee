# wp = new WPAPI
#     endpoint :
#         "#{vars.main.base}/wp-json/"
# wp.instagram = wp.registerRoute 'api/v1', 'instagram/'
module.exports = ->
    instagram =
        controller : [ '$scope', "$attrs", "$timeout", "wpApi", ($scope, $attrs, $timeout, wpApi)->
            $scope.resize = (url)->
                return url.replace('150x150/', '640x640/')
            wpApi
                endpoint : 'instagram'
                name : 'api'
                ver : 'v1'
            .then (res)->
                res = res.data.data
                return if res.length < 1
                $timeout ->
                    $scope.items = res
                    $scope.username = res[0].user.username
                    return
                return
            # wp.instagram()
            #     .then (res)->
            #         return if res.data.length < 1
            #         $timeout ->
            #             $scope.items = res.data
            #             $scope.username = res.data[0].user.username
            #             return
            #         return
            return       
        ]