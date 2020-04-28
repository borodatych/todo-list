// Class definition
const TDListForm = function () {
    // Base elements
    let isAdmin;
    let formEl;
    let validator;

    // Private functions
    let initValidation = function() {
        validator = formEl.validate({
            ignore: ":hidden",
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                note: {
                    required: true
                },
            },
            messages: {
                name: {
                    required: "Кто постановщик задач ?"
                },
                email: {
                    required: "Введите почту для обратной связи !"
                },
                note: {
                    required: "Сформулируйте задачу !"
                },
            },

            // Display error
            invalidHandler: function(event, validator) {
                KTUtil.scrollTop();

                swal.fire({
                    "title": "",
                    "text": "There are some errors in your submission. Please correct them.",
                    "type": "error",
                    "confirmButtonClass": "btn btn-secondary m-btn m-btn--wide"
                });
            },

            // Submit valid form
            submitHandler: function (form) {

            }
        });
    };

    let initSubmit = function() {
        let btn = formEl.find('[data-ktwizard-type="action-submit"]');

        btn.on('click', function(e) {
            e.preventDefault();

            if ( validator.form() ) {
                // See: src\js\framework\base\app.js
                KTApp.progress(btn);
                //KTApp.block(formEl);

                // See: https://api.jquery.com/jquery.ajax/#jqXHR
                let action = formEl.data('action');
                let url = '/todo/' + action;
                let _type, _title, _content;
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: formEl.serialize(),
                }).done(function(data) {
                    console.log('| .:: TODO ADD REQ ::. |', url, data);
                    if ( data.status > 0 ) {
                        _type = 'success';
                        _content = 'Задача добавленна';
                    } else {
                        _type = 'error';
                        _content = data.message;
                    }
                }).fail(function(error) {
                    console.log('| .:: TODO ADD ERR ::. |', error);
                    _type = 'error';
                    _content = error.responseText;
                }).always(function() {
                    KTApp.unprogress(btn);
                    //KTApp.unblock(formEl);
                    swal.fire({
                        type: _type || 'success',
                        title: _title || '',
                        html: _content || 'The application has been successfully submitted!',
                        confirmButtonClass: 'btn btn-secondary'
                    }).then(() => {
                        if ( 'success' === _type ) location.reload();
                    });
                });
            }
        });
    };

    let addTask = () => {
        formEl.data('action','add');
        console.log('| .:: FORM ADD TASK OPEN ::. |');
    };
    let editTask = (id) => {
        formEl.data('action','upd');
        console.log('| .:: FORM EDIT TASK OPEN ::. |');
        $('#taskID').val(id);
        ///
        $.ajax({
            url: '/todo/get',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({id:id}),
        }).done(function(data) {
            console.log('| .:: TODO EDIT REQ ::. |', data);
            if ( data.status && data.item ) {
                $('#tdName').val(data.item.name);
                $('#tdEmail').val(data.item.email);
                $('#tdNote').val(data.item.note);
                $('#tdCompleted').prop('checked',( data.item.completed * 1 > 1 ));
                $('#toDoList').modal()
            }
        }).fail(function(error) {
            console.log('| .:: TODO ADD ERR ::. |', error);
        }).always(function() {
            //
        });
    };
    let removeTask = (id) => {
        Swal.fire({
            title: 'Are you sure?',
            /// text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Remove',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                /// https://learn.javascript.ru/fetch
                return fetch('/todo/del',{
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id:id}),
                }).then(response => {
                    if( !response.ok ){
                        throw new Error(response.statusText)
                    }
                    ///console.log(response.text());
                    return response.json();
                }).catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`)
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((body) => {
            console.log(body);
            if( body.value.status ){
                swal.fire('Готово!','Возвращайтеь, всегда рады вас видеть','success').then(() => {
                    location.reload();
                });
            } else {
                swal.fire('Oops...','Что-то пошло не так :(','error');
            }
        });
    };

    return {
        // public functions
        init: function() {
            isAdmin = $('#profileInfo').data('is-admin');
            formEl = $('#kt_form_todo-list');

            initValidation();
            initSubmit();
        }
        , addTask: addTask
        , editTask: editTask
        , removeTask: removeTask
    };
}();

jQuery(document).ready(function() {
    TDListForm.init();
});
