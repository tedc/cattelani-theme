module.exports = ->
    form =
        scope: on
        controller: [ "$scope", "$rootScope", "$http", "$timeout", "transformRequestAsFormPost", "$location", "$window", ($scope, $rootScope, $http, $timeout, transformRequestAsFormPost, $location, $window)->
            $scope.formData = {}
            $scope.submit = (isValid, url)->
                frmdata = $scope.formData
                frmdata.location = $location.absUrl().split('#')[0]
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
                            #console.log data
                            $window.dataLayer.push 
                                event: 'formSubmissionSuccess'
                            # tmp = document.createElement 'div'
                            # console.log tmp
                            # tmp.innerHTML = data.data
                            # tmp = tmp.querySelector '#form-alert-message'
                            # html = tmp.innerHTML
                            #console.log data.data
                            html = vars.main.formMsg
                            #console.log html
                            $rootScope.isContactSent = on
                            $scope.alert = html
                            $timeout ->
                                $rootScope.isSubmitted = off
                                $rootScope.isContactSent = off
                            , 5000
                            return
                return]