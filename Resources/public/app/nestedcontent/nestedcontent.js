'use strict';

angular.module('OpiferNestedContent', ['ui.sortable'])
    /**
     * Template Service
     */
    .factory('TemplateService', ['$resource', function($resource) {
        return $resource(Routing.generate('opifer_eav_api_template'), {}, {
            index: {method: 'GET', isArray: true, params: {}}
        });
    }])

    /**
     * Render the nested content
     */
    .directive('nestedContent', ['$compile', function($compile) {
        var tpl =
            '<div ui-sortable ng-model="subjects">' +
            '   <div nested-content-form ng-repeat="subject in subjects track by $index"></div>' +
            '</div>' +
            '<select class="form-control select-template" ng-options="template.displayName for (key, template) in templates | orderBy:\'displayName\'" ng-model="selected" ng-change="addSubject()">' +
            '    <option value="">Add template...</option>' +
            '</select>'
        ;

        return {
            restrict: 'E',
            template: tpl,
            scope: {
                ids: '@',
                attribute: '@',
                attributeId: '@'
            },
            link: function(scope, element, attrs) {
                scope.addSubject = function() {
                    // Add the template to the subjects array
                    scope.subjects.push(angular.copy(this.selected));

                    // Clear the select field so we can re-use it for adding more
                    // nested content.
                    this.selected = null;
                };

                scope.removeSubject = function(index) {
                    // Remove the subject from the subjects array
                    scope.subjects.splice(index, 1);
                };
            },
            controller: function($scope, $http, $attrs, TemplateService) {
                // Query all available templates to make them available in the
                // select field.
                $scope.templates = TemplateService.index({attribute: $scope.attributeId });
                $scope.subjects = [];

                // Retrieve all predefined nested content and add them to the
                // subjects array.
                if (typeof $scope.ids !== 'undefined') {

                    var ids = $scope.ids.split(',');
                    for (var i = 0; i < ids.length; i++) {
                        $scope.subjects.push({name: ids[i]});
                    };
                }
            }
        };
    }])

    /**
     * render the nested content form
     *
     * Requests a form type and manually compiles the return html
     */
    .directive('nestedContentForm', ['$compile', '$http', '$sce', function($compile, $http, $sce) {
        return {
            template:
                '<article class="nested-content-item">'+
                '   <header ng-click="toggle()" class="form-group">' +
                '       <div class="col-xs-12 col-sm-9 col-lg-10">'+
                '           <div class="title">'+
                '               <div class="cell-icon cell-columns" ng-if="!subject.data.coverImage"></div>'+
                '               <div style="background-image: url({{ subject.data.coverImage }});" ng-if="subject.data.coverImage" class="content-cover"></div>'+
                '               <h3>{{ subject.data.title }}</h3>'+
                '           </div>'+
                '           <span class="template form-control-static"><span class="label label-info">{{ subject.data.templateDisplayName }}</span></span>'+
                '       </div>'+
                '       <div class="col-xs-12 col-sm-3 col-lg-2">'+
                '           <div class="pull-right">'+
                '               <div class="btn-group"><a class="btn btn-sm btn-danger" ng-click="removeSubject($index)">Delete</a></div>'+
                '               <div class="btn-group"><a class="btn btn-sm btn-drag"></a></div>'+
                '           </div>'+
            '           </div>'+
                '   </header>' +
                '   <div ng-hide="hidden">' +
                '       <div class="form-nested-content" compile="subject.form"></div>'+
                '   </div>' +
                '</article>',
            replace: true,
            link: function (scope, element, attrs) {
                scope.subject.data = {};
                scope.subject.form = '';

                if (angular.isUndefined(scope.$parent.$parent.subject)) {
                    var parent = '';
                } else {
                    var parent = element.parent().parent().parent().find('input')[0].name;
                }

                // Request the form template and compile it
                $http.post(Routing.generate('opifer_eav_form_render', {
                    attribute: scope.attribute,
                    id: scope.subject.name,
                    index: scope.$index,
                    parent: parent,
                    template: scope.subject.id
                }), {}).success(function(data) {
                    scope.subject.form = data.form;
                    scope.subject.data = data.content;
                    scope.subject.name = data.name;

                    // If the subject's name is not an ID, it means it's a new nested content
                    // form, so don't hide it.
                    if (isNaN(parseFloat(scope.subject.name)) || !isFinite(scope.subject.name)) {
                        scope.hidden = false;
                    }
                });

                // Hide/Show the nested content form
                scope.toggle = function() {
                    if (scope.hidden == true) {
                        scope.hidden = false;
                    } else {
                        scope.hidden = true;
                    }
                }
            },
            controller: function($scope) {
                // Initially hide each form, to keep the form view clean.
                $scope.hidden = true;
            }
        };
    }])

    /**
     * Compile the passed html
     */
    .directive('compile', function ($compile) {
        return {
            restrict: 'A',
            replace: true,
            link: function (scope, element, attrs) {
                scope.$watch(attrs.compile, function(html) {
                    element.html(html);
                    $compile(element.contents())(scope);
                });
            }
        };
    })
;
