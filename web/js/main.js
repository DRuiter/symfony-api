$(function(){
    var host = window.location.protocol+'//'+window.location.hostname+(window.location.port ? ':'+window.location.port + '/' : '/'),
        API  = host + 'api/v1.0/',
        batchMode = false;

    function getUserForm(){
        $.ajax(host + 'forms/user #body').done(function (res){
            $('#user-form').html(res);
        });
    }

    function getContentPageForm(){
        $.ajax(host + 'forms/contentpage #body').done(function (res){
            $('#contentpage-form').html(res);
        });
    }

    function getAllUsers(callback){
        $.ajax(API + 'users', {
            method: 'GET'
        }).done(callback);
    }

    function renderUsers(users){
        var html = '<ul>';

        users.forEach(function(user){
            html += '<li>'+user.id+' - '+user.email+' - '+user.first_name+'</li>';
        });

        html += '</ul>';

        $('#user-entities').html(html);
    }

    function getAllContentPages(callback){
        $.ajax(API + 'contentpages', {
            method: 'GET'
        }).done(callback);
    }

    function renderContentPages(contentpages){
        var html = '<ul>';

        contentpages.forEach(function(contentpage){
            html += '<li>'+contentpage.id+' - '+contentpage.title+' - '+contentpage.body+'</li>';
        });

        html += '</ul>';

        $('#contentpage-entities').html(html);
    }

    function toggleBatchMode(){
        batchMode = !batchMode;

        if(batchMode){
            $(this).text('Disable Batch Mode');
        } else {
            $(this).text('Enable Batch Mode');
        }
    }

    function hijackFormSubmit(){
        $('body').on('submit', 'form', function(evt){
            evt.preventDefault();
            evt.returnValue = false;

            var data = $(this).serialize(),
                target = $(this).find('button[type=submit]').attr('data-target');

            $.ajax(host + target, {
                method: 'POST',
                data: data
            })
            .done(function(){
                if(target.indexOf('user') !== -1) getAllUsers(renderUsers);
                if(target.indexOf('contentpage') !== -1) getAllContentPages(renderContentPages);
            })
            .fail(function(){
                console.error(arguments);
            });
        });
    }

    function init(){
        $('#batch-mode').on('click', toggleBatchMode);

        hijackFormSubmit();
        getUserForm();
        getContentPageForm();
        getAllUsers(renderUsers);
        getAllContentPages(renderContentPages);
    }

    init();
}());
