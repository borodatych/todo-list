// Class definition
const TDAuth = function () {
    // Base elements
    let isAdmin;
    let formEl;
    let validator;

    // Private functions
    let initValidation = function() {
        validator = formEl.validate({
            ignore: ":hidden",
            rules: {
                login: {
                    required: true
                },
                password: {
                    required: true
                },
            },
            messages: {
                login: {
                    required: "Введите логин"
                },
                password: {
                    required: "Введите пароль"
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
                let url = '/user/login';
                let _type, _title, _content;
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: formEl.serialize(),
                }).done(function(data) {
                    console.log('| .:: LOGIN REQ ::. |', url, data);
                    if ( data.status ) {
                        _type = 'success';
                        _content = 'Упешно';
                    } else {
                        _type = 'error';
                        _content = 'Неверный логин и\\или пароль';
                    }
                }).fail(function(error) {
                    console.log('| .:: LOGIN ERR ::. |', error);
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

    let logout = () => {
        Swal.fire({
            title: 'Are you sure?',
            /// text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Log Out',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                /// https://learn.javascript.ru/fetch
                return fetch('/user/logout',{
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'}
                    ///body: JSON.stringify({id:id,note:data}),
                }).then(response => {
                    if( !response.ok ){
                        throw new Error(response.statusText)
                    }
                    return response.json();
                }).catch(error => {
                    console.log(error);
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
            formEl = $('#kt_form_auth');

            initValidation();
            initSubmit();
        }
        , logout: logout
    };
}();

jQuery(document).ready(function() {
    TDAuth.init();
});
