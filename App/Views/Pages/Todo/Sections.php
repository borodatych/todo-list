<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Tasks Datatable
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <button type="button" id="toDoListBtn" class="btn btn-outline-brand" data-toggle="modal" data-target="#toDoList" onClick="TDListForm.addTask()">
                    <i class="flaticon2-add-1"></i>
                    New Record
                </button>
                <div class="modal fade" id="toDoList" tabindex="-1" role="dialog" aria-labelledby="toDoListTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="toDoListTitle">New Task</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <form id="kt_form_todo-list" class="kt-form" data-action="">
                                <input id="taskID" type="hidden" name="id" />
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Name:</label>
                                        <input type="text" id="tdName" name="name" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input type="email" id="tdEmail" name="email" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleTextarea">Note:</label>
                                        <textarea id="tdNote" name="note" class="form-control" id="exampleTextarea" rows="3"></textarea>
                                    </div>
                                    <?php if( (int)\App\Helpers\Arr::get($session,'isAuth') ): ?>
                                    <div class="form-group row  form-group-last">
                                        <label class="col-6 col-form-label">Performed:</label>
                                        <div class="col-6">
                                            <span class="kt-switch">
                                                <label>
                                                    <input type="checkbox" id="tdCompleted" name="completed" value="1" />
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-outline-brand" data-ktwizard-type="action-submit">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        <!--begin: Search Form -->
        <div class="kt-form kt-fork--label-right kt-margin-t-20 kt-margin-b-10">
            <div class="row align-items-center">
                <div class="col-xl-8 order-2 order-xl-1">
                    <div class="row align-items-center">
                        <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="Search..." id="generalSearch">
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="la la-search"></i></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                            <div class="kt-form__group kt-form__group--inline">
                                <div class="kt-form__label">
                                    <label>Status:</label>
                                </div>
                                <div class="kt-form__control">
                                    <select class="form-control bootstrap-select" id="kt_form_status">
                                        <option value="">All</option>
                                        <option value="0">Pending</option>
                                        <option value="1">Success</option>
                                        <option value="-1">Canceled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end: Search Form -->
    </div>
    <div class="kt-portlet__body kt-portlet__body--fit">
        <!--begin: Datatable -->
        <div class="kt_datatable" id="child_data_ajax"></div>
        <!--end: Datatable -->
    </div>
</div>