module.exports = ->
    form =
        scope: on
        controller: [ "$scope", "$rootScope", "$http", "$timeout", "transformRequestAsFormPost", ($scope, $rootScope, $http, $timeout, transformRequestAsFormPost)->
            $scope.formData = {}
            $scope.submit = (isValid, url)->
                frmdata = $scope.formData
                $rootScope.isSubmitted = on
                $scope.formData = {}
                $scope.any = off
                $scope.time = off
                $rootScope.isPrivacyChecked = off
                $scope.contactForm.$setUntouched()
                $scope.contactForm.$setPristine()
                if isValid
                    $http(
                        {
                            method: 'POST'
                            url: url
                            data: frmdata
                            headers :
                                "Content-type" : "application/x-www-form-urlencoded; charset=utf-8"
                            transformRequest: transformRequestAsFormPost
                        }).then (data)->
                            tmp = document.createElement 'div'
                            tmp.innerHTML = data.data
                            tmp = tmp.querySelector '#form-alert-message'
                            html = tmp.innerHTML
                            window.ga 'send', 'event', 'contatti', 'submit form' if window.ga
                            $rootScope.isContactSent = on
                            $scope.alert = html
                            $timeout ->
                                $rootScope.isSubmitted = off
                                $rootScope.isContactSent = off
                            , 5000
                            return
                return]