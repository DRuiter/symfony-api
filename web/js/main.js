$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$(function(){

    var host = window.location.protocol+'//'+window.location.hostname+(window.location.port ? ':'+window.location.port + '/' : '/'),
        API  = host + 'api/v1.0/',
        batchMode       = false,
        batchQueue      = [],
        Users           = [],
        ContentPages    = [];

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
        }).done(function(users){
            Users = users;
            callback(users);
        });
    }

    function renderUsers(users){
        var html = '<ul>';

        users.forEach(function(user){
            html += '<li data-id="'+user.id+'">';
            html += '<button class="edit-button" data-type="user" data-id="'+user.id+'">Edit</button>';
            html += '<button class="delete-button" data-type="user" data-id="'+user.id+'">Delete</button>';
            html += user.id+' - '+user.email+' - '+user.first_name;
            html += '</li>';
        });

        html += '</ul>';

        $('#user-entities').html(html);
    }

    function getAllContentPages(callback){
        $.ajax(API + 'contentpages', {
            method: 'GET'
        }).done(function(contentpages){
            ContentPages = contentpages;
            callback(contentpages);
        });
    }

    function renderContentPages(contentpages){
        var html = '<ul>';

        contentpages.forEach(function(contentpage){
            html += '<li>';
            html += '<button class="edit-button" data-type="contentpage" data-id="'+contentpage.id+'">Edit</button>';
            html += '<button class="delete-button" data-type="contentpage" data-id="'+contentpage.id+'">Delete</button>';
            html += contentpage.id+' - '+contentpage.title+' - '+contentpage.body;
            html += '</li>';
        });

        html += '</ul>';

        $('#contentpage-entities').html(html);
    }

    function toggleBatchMode(){
        batchMode = !batchMode;

        if(batchMode){
            $(this).text('Handle Batch');
        } else {
            $.ajax(API + 'batch/', {
                method: 'POST',
                data: JSON.stringify(batchQueue),
                contentType : 'application/json; charset=utf-8'
            })
            .done(function(res){
                getAllUsers(renderUsers);
                getAllContentPages(renderContentPages);
                batchQueue = [];
                renderBatchQueue();
                console.log(res);
            })
            .fail(function(){
                console.error(arguments);
            })
            $(this).text('Enable Batch Mode');
        }
    }

    function renderBatchQueue(){
        var html = '<ul><li>Batch Queue</li>';

        batchQueue.forEach(function(request){
            html += '<li>'+request.method+' - '+request.route+'</li>';
        });

        html += '</ul>';

        $('#batch-queue').html(html);
    }

    function hijackFormSubmit(){
        $('body').on('submit', 'form', function(evt){
            evt.preventDefault();
            evt.returnValue = false;

            var $form   = $(this),
                data    = $form.serialize(),
                target  = $(this).find('button[type=submit]').attr('data-target');

            if(batchMode){
                batchQueue.push({
                    route: '/'+target,
                    method: 'POST',
                    params: data
                });

                renderBatchQueue();

                return false;
            }

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

    function editUser(id){
        showEditModal('user', id);
    }

    function editContentPage(id){
        showEditModal('contentpage', id);
    }

    function showEditModal(type, id){
        var html = '<div class="modal" data-type="'+type+'" data-id="'+id+'">';
        html += '<div class="close-modal">x</div>';
        html += '<div id="modal-form-container"></div>';
        html += '<button class="save-modal">Edit</button>';
        html += '</div>';

        $('body').append(html);

        $.ajax(API + 'forms/'+type + '/'+id+' #body', {
            method: 'GET'
        }).done(function(res){
            $('#modal-form-container').append(res);
        }).fail(function(){
            console.error(arguments);
        });
    }

    function deleteUser(id){
        if(batchMode){
            batchQueue.push({
                method: 'DELETE',
                route: '/api/v1.0/users/'+id
            });

            renderBatchQueue();

            return false;
        }

        $.ajax(API + 'users/'+id, {
            method: 'DELETE'
        })
        .done(function(){
            getAllUsers(renderUsers);
        })
        .fail(function(){
            console.error(arguments);
        });
    }
    function deleteContentPage(id){
        if(batchMode){
            batchQueue.push({
                method: 'DELETE',
                route: '/api/v1.0/contentpages/'+id
            });

            renderBatchQueue();

            return false;
        }

        $.ajax(API + 'contentpages/'+id, {
            method: 'DELETE'
        })
        .done(function(){
            getAllContentPages(renderContentPages);
        })
        .fail(function(){
            console.error(arguments);
        });
    }

    function init(){
        $('#batch-mode').on('click', toggleBatchMode);
        $('body')
            .on('click', 'button.delete-button', function(){
                var $this   = $(this),
                    id      = $this.attr('data-id'),
                    type    = $this.attr('data-type');

                if(type === 'contentpage'){
                    deleteContentPage(id);
                }

                if(type === 'user'){
                    deleteUser(id);
                }
            })
            .on('click', 'button.edit-button', function(){
                var $this   = $(this),
                    id      = $this.attr('data-id'),
                    type    = $this.attr('data-type');

                if(type === 'contentpage'){
                    editContentPage(id);
                }

                if(type === 'user'){
                    editUser(id);
                }
            })
            .on('click', '.close-modal', function(){
                $(this).parent().remove();
            })
            .on('click', '.save-modal', function(){
                var $modal  = $(this).parent(),
                    $form   = $modal.find('form'),
                    data    = $form.serializeArray(),
                    type    = $modal.attr('data-type'),
                    id      = $modal.attr('data-id'),
                    transformedData = {};

                if(!id) throw new Error('No ID set in modal-save');

                data.forEach(function(val){
                    var key     = val.name,
                        value   = val.value;

                    key = key.split('[')[1];
                    key = key.substr(0, key.length-1);

                    transformedData[key] = value;
                });

                if(type === 'user'){
                    if(batchMode){
                        batchQueue.push({
                            route: '/api/v1.0/users/'+id,
                            method: 'PUT',
                            params: transformedData
                        });

                        renderBatchQueue();

                        return false;
                    }

                    $.ajax(API + 'users/' + id, {
                        method: 'PUT',
                        data: data
                    }).done(function(res){
                        getAllUsers(renderUsers);
                    }).fail(function(){
                        console.error(arguments);
                    });
                }

                if(type === 'contentpage'){
                    if(batchMode){
                        batchQueue.push({
                            route: '/api/v1.0/contentpages/'+id,
                            method: 'PUT',
                            params: transformedData
                        });

                        renderBatchQueue();

                        return false;
                    }

                    $.ajax(API + 'contentpages/' + id, {
                        method: 'PUT',
                        data: data
                    }).done(function(res){
                        getAllContentPages(renderContentPages);
                    }).fail(function(){
                        console.error(arguments);
                    });
                }
            });



        hijackFormSubmit();
        getUserForm();
        getContentPageForm();
        getAllUsers(renderUsers);
        getAllContentPages(renderContentPages);
        renderBatchQueue();
    }

    init();
}());
