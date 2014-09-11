/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

(function () {
    'use strict';

    function getTagId(tab) {
        for (var i = 0; i < tab.length; i++) {
            if (tab[i] === 'tag') {
                return tab[i + 1];
            }
        }

        return -1;
    }

    function getPage(tab) {
        var page = 1;

        for (var i = 0; i < tab.length; i++) {
            if (tab[i] === 'page') {
                if (typeof(tab[i + 1]) !== 'undefined') {
                    page = tab[i + 1];
                }
                break;
            }
        }

        return page;
    }

    $('#workspace-list-div').on('click', '.pagination > ul > li > a', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var element = event.currentTarget;
        var url = $(element).attr('href');
        var route;

        if (url !== '#') {
            var urlTab = url.split('/');
            var tagId = getTagId(urlTab);
            var page = getPage(urlTab);

            if (tagId === -1) {
                route = Routing.generate(
                    'claro_all_workspaces_list_with_self_reg_pager',
                    {'page': page}
                );
            }
            else {
                route = Routing.generate(
                    'claro_workspace_list_with_self_reg_pager',
                    {'workspaceTagId': tagId, 'page': page}
                );
            }
            $.ajax({
                url: route,
                success: function (result) {
                    var source = $(element).parent().parent().parent().parent();
                    $(source).children().remove();
                    $(source).append(result);
                },
                type: 'GET'
            });
        }
    });

    var twigUserId = document.getElementById('twig-self-registration-user-id').getAttribute('data-user-id');
    var workspaceId;
    var registrationValidation;
    var registerButtonClass;

    $('body').on('click', '.register-user-to-workspace', function (e) {
        e.preventDefault();
        workspaceId = $(this).attr('data-workspace-id');
        registerButtonClass = '.register-button-' + workspaceId;
        var workspaceName = $(this).attr('data-workspace-name');
        var workspaceCode = $(this).attr('data-workspace-code');
        registrationValidation = $(this).attr('data-workspace-validation');
        $('#registration-confirm-message').html(workspaceName + ' [' + workspaceCode + ']');
        $('#confirm-registration-validation-box').modal('show');
    });

    $('#registration-confirm-ok').click(function () {
        var resultText;
        var registrationRoute;
        
        if (registrationValidation === 'validation') {
            registrationRoute = Routing.generate(
                'claro_workspace_add_user_queue',
                {'workspace': workspaceId, 'user': twigUserId}
            );
            resultText = Translator.get('platform' + ':' + 'pending');
        } else {
            registrationRoute = Routing.generate(
                'claro_workspace_add_user',
                {'workspace': workspaceId, 'user': twigUserId}
            );
            resultText = Translator.get('platform' + ':' + 'registered');
        }

        $.ajax({
            url: registrationRoute,
            type: 'POST',
            success: function () {
                $(registerButtonClass).each(function () {
                    $(this).empty();
                    $(this).html('<span><i class="fa fa-share-square-o"></i> ' + resultText + '</span>');
                    $(this).attr('class', 'pull-right label label-success');
                });
                $('#confirm-registration-validation-box').modal('hide');
                $('#registration-confirm-message').empty();
            }
        });
    });
})();