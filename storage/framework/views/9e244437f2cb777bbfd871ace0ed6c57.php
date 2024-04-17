<?php $__env->startSection('page-title'); ?>
    <?php echo e($deal->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/summernote/summernote-bs4.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/dropzone.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('css/summernote/summernote-bs4.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/dropzone-amd-module.min.js')); ?>"></script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#deal-sidenav',
            offset: 300
        })
        Dropzone.autoDiscover = false;
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            // maxFilesize: 20,
            parallelUploads: 1,
            filename: false,
            // acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "<?php echo e(route('deals.file.upload',$deal->id)); ?>",
            success: function (file, response) {
                if (response.is_success) {
                    if(response.status==1){
                        show_toastr('success', response.success_msg, 'success');
                    }
                    dropzoneBtn(file, response);
                } else {
                    myDropzone.removeFile(file);
                    show_toastr('error', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    show_toastr('error', response.error, 'error');
                } else {
                    show_toastr('error', response.error, 'error');
                }
            }
        });
        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("deal_id", <?php echo e($deal->id); ?>);
        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "badge bg-info mx-1");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "<?php echo e(__('Download')); ?>");
            download.innerHTML = "<i class='ti ti-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "badge bg-danger mx-1");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "<?php echo e(__('Delete')); ?>");
            del.innerHTML = "<i class='ti ti-trash'></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: {_token: $('meta[name="csrf-token"]').attr('content')},
                        type: 'DELETE',
                        success: function (response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                show_toastr('error', response.error, 'error');
                            }
                        },
                        error: function (response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                show_toastr('error', response.error, 'error');
                            } else {
                                show_toastr('error', response, 'error');
                            }
                        }
                    })
                }
            });

            var html = document.createElement('div');
            html.appendChild(download);
            <?php if(Auth::user()->type != 'client'): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit deal')): ?>
            html.appendChild(del);
            <?php endif; ?>
            <?php endif; ?>

            file.previewTemplate.appendChild(html);
        }

        <?php $__currentLoopData = $deal->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(file_exists(storage_path('deal_files/'.$file->file_path))): ?>
        // Create the mock file:
        var mockFile = {name: "<?php echo e($file->file_name); ?>", size: <?php echo e(\File::size(storage_path('deal_files/'.$file->file_path))); ?>};
        // Call the default addedfile event handler
        myDropzone.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone.emit("thumbnail", mockFile, "<?php echo e(asset(Storage::url('deal_files/'.$file->file_path))); ?>");
        myDropzone.emit("complete", mockFile);

        dropzoneBtn(mockFile, {download: "<?php echo e(route('deals.file.download',[$deal->id,$file->id])); ?>", delete: "<?php echo e(route('deals.file.delete',[$deal->id,$file->id])); ?>"});
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit deal')): ?>
        $('.summernote-simple').on('summernote.blur', function () {

            $.ajax({
                url: "<?php echo e(route('deals.note.store',$deal->id)); ?>",
                data: {_token: $('meta[name="csrf-token"]').attr('content'), notes: $(this).val()},
                type: 'POST',
                success: function (response) {
                    if (response.is_success) {
                        // show_toastr('Success', response.success,'success');
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('error', response.error, 'error');
                    } else {
                        show_toastr('error', response, 'error');
                    }
                }
            })
        });
        <?php else: ?>
        $('.summernote-simple').summernote('disable');
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit task')): ?>
        $(document).on("click", ".task-checkbox", function () {
            var chbox = $(this);
            var lbl = chbox.parent().parent().find('label');

            $.ajax({
                url: chbox.attr('data-url'),
                data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
                type: 'PUT',
                success: function (response) {
                    if (response.is_success) {
                        chbox.val(response.status);
                        if (response.status) {
                            lbl.addClass('strike');
                            lbl.find('.badge').removeClass('badge-warning').addClass('badge-success');
                        } else {
                            lbl.removeClass('strike');
                            lbl.find('.badge').removeClass('badge-success').addClass('badge-warning');
                        }
                        lbl.find('.badge').html(response.status_label);

                        show_toastr('success', response.success);
                    } else {
                        show_toastr('error', response.error);
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('success', response.success);
                    } else {
                        show_toastr('error', response.error);
                    }
                }
            })
        });
        <?php endif; ?>
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('deals.index')); ?>"><?php echo e(__('Deal')); ?></a></li>
    <li class="breadcrumb-item"> <?php echo e($deal->name); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('convert deal to deal')): ?>
            <?php if(!empty($deal)): ?>
                <a href="<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('View Deal')): ?> <?php if($deal->is_active): ?> <?php echo e(route('deals.show',$deal->id)); ?> <?php else: ?> # <?php endif; ?> <?php else: ?> # <?php endif; ?>" data-size="lg" data-bs-toggle="tooltip" title=" <?php echo e(__('Already Converted To Deal')); ?>" class="btn btn-sm btn-primary">
                    <i class="ti ti-exchange"></i>
                </a>
            <?php else: ?>
                <a href="#" data-size="lg" data-url="<?php echo e(URL::to('deals/'.$deal->id.'/show_convert')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Convert ['.$deal->subject.'] To Deal')); ?>" class="btn btn-sm btn-primary">
                    <i class="ti ti-exchange"></i>
                </a>
            <?php endif; ?>
        <?php endif; ?>
        <a href="#" data-url="<?php echo e(URL::to('deals/'.$deal->id.'/labels')); ?>" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="<?php echo e(__('Label')); ?>" class="btn btn-sm btn-primary">
            <i class="ti ti-bookmark"></i>
        </a>
        <a href="#" data-size="lg" data-url="<?php echo e(route('deals.edit',$deal->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" class="btn btn-sm btn-primary">
            <i class="ti ti-pencil"></i>
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="deal-sidenav">

                            <a href="#general" class="list-group-item list-group-item-action border-0"><?php echo e(__('General')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#tasks" class="list-group-item list-group-item-action border-0"><?php echo e(__('Task')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#users_products" class="list-group-item list-group-item-action border-0"><?php echo e(__('Users').' | '.__('Products')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#sources_emails" class="list-group-item list-group-item-action border-0"><?php echo e(__('Sources').' | '.__('Emails')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#discussion_note" class="list-group-item list-group-item-action border-0"><?php echo e(__('Discussion').' | '.__('Notes')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#files" class="list-group-item list-group-item-action border-0"><?php echo e(__('Files')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#calls" class="list-group-item list-group-item-action border-0"><?php echo e(__('Calls')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#activity" class="list-group-item list-group-item-action border-0"><?php echo e(__('Activity')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <?php
                        $tasks = $deal->tasks;
                        $products = $deal->products();
                        $sources = $deal->sources();
                        $calls = $deal->calls;
                        $emails = $deal->emails;
                    ?>
                    <div id="general" class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-test-pipe"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0"><?php echo e(__('Pipeline')); ?></p>
                                            <h5 class="mb-0 text-success"><?php echo e($deal->pipeline->name); ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 my-3 my-sm-0">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-server"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0"><?php echo e(__('Stage')); ?></p>
                                            <h5 class="mb-0 text-primary"><?php echo e($deal->stage->name); ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-warning">
                                            <i class="ti ti-calendar"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0"><?php echo e(__('Created')); ?></p>
                                            <h5 class="mb-0 text-warning"><?php echo e(\Auth::user()->dateFormat($deal->created_at)); ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-info">
                                            <i class="ti ti-report-money"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0"><?php echo e(__('Price')); ?></p>
                                            <h5 class="mb-0 text-info"><?php echo e(\Auth::user()->priceFormat($deal->price)); ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <small class="text-muted"><?php echo e(__('Task')); ?></small>
                                            <h3 class="m-0"><?php echo e(count($tasks)); ?></h3>
                                        </div>
                                        <div class="col-auto">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti ti-subtask"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <small class="text-muted"><?php echo e(__('Product')); ?></small>
                                            <h3 class="m-0"><?php echo e(count($products)); ?></h3>
                                        </div>
                                        <div class="col-auto">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti ti-shopping-cart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <small class="text-muted"><?php echo e(__('Source')); ?></small>
                                            <h3 class="m-0"><?php echo e(count($sources)); ?></h3>
                                        </div>
                                        <div class="col-auto">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-social"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <small class="text-muted"><?php echo e(__('Files')); ?></small>
                                            <h3 class="m-0"><?php echo e(count($deal->files)); ?></h3>
                                        </div>
                                        <div class="col-auto">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti ti-file"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="tasks" class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5><?php echo e(__('Tasks')); ?></h5>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create task')): ?>
                                    <div class="float-end">
                                        <a href="#" data-size="lg" data-url="<?php echo e(route('deals.tasks.create',$deal->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Add Task')); ?>" class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                        <div class="card-body">
                            <?php if(!$tasks->isEmpty()): ?>
                                <ul class="list-group list-group-flush mt-2">
                                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="list-group-item px-0">
                                            <div class="d-block d-sm-flex align-items-start">
                                                <div class="form-check form-switch form-switch-right img-fluid me-3 mb-2 mb-sm-0">
                                                    <input class="form-check-input task-checkbox" type="checkbox" role="switch" id="task_<?php echo e($task->id); ?>" <?php if($task->status): ?> checked="checked" <?php endif; ?> value="<?php echo e($task->status); ?>" data-url="<?php echo e(route('deals.tasks.update_status',[$deal->id,$task->id])); ?>">
                                                    <label class="form-check-label pe-5" for="task_<?php echo e($task->id); ?>"></label>
                                                </div>
                                                <div class="w-100">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="mb-3 mb-sm-0">
                                                            <h5 class="mb-0">
                                                                <?php echo e($task->name); ?>

                                                                <?php if($task->status): ?>
                                                                    <div class="badge bg-primary mb-1"><?php echo e(__(\App\Models\DealTask::$status[$task->status])); ?></div>
                                                                <?php else: ?>
                                                                    <div class="badge bg-warning mb-1"><?php echo e(__(\App\Models\DealTask::$status[$task->status])); ?></div>
                                                                <?php endif; ?>
                                                            </h5>
                                                            <small class="text-sm"><?php echo e(__(\App\Models\DealTask::$priorities[$task->priority])); ?> - <?php echo e(Auth::user()->dateFormat($task->date)); ?> <?php echo e(Auth::user()->timeFormat($task->time)); ?></small>
                                                            <span class="text-muted text-sm">
                                                                <?php if($task->status): ?>
                                                                    <div class="badge badge-pill badge-success mb-1"><?php echo e(__(\App\Models\DealTask::$status[$task->status])); ?></div>
                                                                <?php else: ?>
                                                                    <div class="badge badge-pill badge-warning mb-1"><?php echo e(__(\App\Models\DealTask::$status[$task->status])); ?></div>
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                        <span>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit task')): ?>
                                                                <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="" data-title="<?php echo e(__('Edit Task')); ?>" data-url="<?php echo e(route('deals.tasks.edit',[$deal->id,$task->id])); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>"><i class="ti ti-pencil text-white"></i></a>
                                                            </div>
                                                            <?php endif; ?>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete task')): ?>
                                                                <div class="action-btn bg-danger ms-2">
                                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['deals.tasks.destroy',$deal->id,$task->id]]); ?>

                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>"><i class="ti ti-trash text-white"></i></a>
                                                                <?php echo Form::close(); ?>

                                                                </div>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center">
                                    No Tasks Available.!
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div id="users_products">
                        <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5><?php echo e(__('Users')); ?></h5>

                                            <div class="float-end">
                                                <a data-size="md" data-url="<?php echo e(route('deals.users.edit',$deal->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Add User')); ?>" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                <tr>
                                                    <th><?php echo e(__('Name')); ?></th>
                                                    <th><?php echo e(__('Action')); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $__currentLoopData = $deal->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div>
                                                                    <img <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/uploads/avatar/'.$user->avatar)); ?>" <?php else: ?> src="<?php echo e(asset('/storage/uploads/avatar/avatar.png')); ?>" <?php endif; ?> class="wid-30 rounded-circle me-3" >
                                                                </div>
                                                                <p class="mb-0"><?php echo e($user->name); ?></p>
                                                            </div>
                                                        </td>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit deal')): ?>
                                                            <td>
                                                                <div class="action-btn bg-danger ms-2">
                                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['deals.users.destroy', $deal->id,$user->id],'id'=>'delete-form-'.$deal->id]); ?>

                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>"><i class="ti ti-trash text-white"></i></a>

                                                                    <?php echo Form::close(); ?>

                                                                </div>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5><?php echo e(__('Products')); ?></h5>

                                            <div class="float-end">
                                                <a  data-size="md" data-url="<?php echo e(route('deals.products.edit',$deal->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Add Product')); ?>" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                <tr>
                                                    <th><?php echo e(__('Name')); ?></th>
                                                    <th><?php echo e(__('Price')); ?></th>
                                                    <th><?php echo e(__('Action')); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $__currentLoopData = $deal->products(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo e($product->name); ?>

                                                        </td>
                                                        <td>
                                                            <?php echo e(\Auth::user()->priceFormat($product->sale_price)); ?>

                                                        </td>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit deal')): ?>
                                                            <td>
                                                                <div class="action-btn bg-danger ms-2">
                                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['deals.products.destroy', $deal->id,$product->id]]); ?>

                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>"><i class="ti ti-trash text-white"></i></a>

                                                                    <?php echo Form::close(); ?>

                                                                </div>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="sources_emails">
                        <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5><?php echo e(__('Sources')); ?></h5>

                                            <div class="float-end">
                                                <a  data-size="md" data-url="<?php echo e(route('deals.sources.edit',$deal->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Add Source')); ?>" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                <tr>
                                                    <th><?php echo e(__('Name')); ?></th>
                                                    <th><?php echo e(__('Action')); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($source->name); ?> </td>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit deal')): ?>
                                                            <td>
                                                                <div class="action-btn bg-danger ms-2">
                                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['deals.sources.destroy', $deal->id,$source->id],'id'=>'delete-form-'.$deal->id]); ?>

                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>"><i class="ti ti-trash text-white"></i></a>

                                                                    <?php echo Form::close(); ?>

                                                                </div>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5><?php echo e(__('Emails')); ?></h5>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create deal email')): ?>
                                            <div class="float-end">
                                                <a  data-size="lg" data-url="<?php echo e(route('deals.emails.create',$deal->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create Email')); ?>" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush mt-2">
                                            <?php if(!$emails->isEmpty()): ?>
                                                <?php $__currentLoopData = $emails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $email): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="list-group-item px-0">
                                                        <div class="d-block d-sm-flex align-items-start">
                                                            <img src="<?php echo e(asset('/storage/uploads/avatar/avatar.png')); ?>"
                                                                 class="img-fluid wid-40 me-3 mb-2 mb-sm-0" alt="image">
                                                            <div class="w-100">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="mb-3 mb-sm-0">
                                                                        <h5 class="mb-0"><?php echo e($email->subject); ?></h5>
                                                                        <span class="text-muted text-sm"><?php echo e($email->to); ?></span>
                                                                    </div>
                                                                    <div class="form-check form-switch form-switch-right mb-2">
                                                                        <?php echo e($email->created_at->diffForHumans()); ?>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <li class="text-center">
                                                    <?php echo e(__(' No Emails Available.!')); ?>

                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="discussion_note">
                        <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5><?php echo e(__('Discussion')); ?></h5>

                                            <div class="float-end">
                                                <a data-size="lg" data-url="<?php echo e(route('deals.discussions.create',$deal->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Add Message')); ?>" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush mt-2">
                                            <?php if(!$deal->discussions->isEmpty()): ?>
                                                <?php $__currentLoopData = $deal->discussions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $discussion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="list-group-item px-0">
                                                        <div class="d-block d-sm-flex align-items-start">
                                                            <img src="<?php if($discussion->user->avatar): ?> <?php echo e(asset('/storage/uploads/avatar/'.$discussion->user->avatar)); ?> <?php else: ?> <?php echo e(asset('/storage/uploads/avatar/avatar.png')); ?> <?php endif; ?>"
                                                                 class="img-fluid wid-40 me-3 mb-2 mb-sm-0" alt="image">
                                                            <div class="w-100">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="mb-3 mb-sm-0">
                                                                        <h5 class="mb-0"> <?php echo e($discussion->comment); ?></h5>
                                                                        <span class="text-muted text-sm"><?php echo e($discussion->user->name); ?></span>
                                                                    </div>
                                                                    <div class=" form-switch form-switch-right mb-4">
                                                                        <?php echo e($discussion->created_at->diffForHumans()); ?>

                                                                    </div>



                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <li class="text-center">
                                                    <?php echo e(__(' No Data Available.!')); ?>

                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5><?php echo e(__('Notes')); ?></h5>
                                            <?php
                    $user = \App\Models\User::find(\Auth::user()->creatorId());
                    $plan= \App\Models\Plan::getPlan($user->plan);
                                            ?>
                                            <?php if($plan->chatgpt == 1): ?>
                                            <div class="float-end">
                                                <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm"
                                                   data-ajax-popup-over="true" id="grammarCheck" data-url="<?php echo e(route('grammar',['grammar'])); ?>"
                                                   data-bs-placement="top" data-title="<?php echo e(__('Grammar check with AI')); ?>">
                                                    <i class="ti ti-rotate"></i> <span><?php echo e(__('Grammar check with AI')); ?></span>
                                                </a>
                                                <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm"
                                                   data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['deal'])); ?>"
                                                   data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
                                                    <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <textarea class="summernote-simple grammer_textarea" name="note"><?php echo $deal->notes; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="files" class="card">
                        <div class="card-header ">
                            <h5><?php echo e(__('Files')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12 dropzone top-5-scroll browse-file" id="dropzonewidget"></div>
                        </div>
                    </div>
                    <div id="calls" class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5><?php echo e(__('Calls')); ?></h5>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create deal call')): ?>
                                <div class="float-end">
                                    <a  data-size="lg" data-url="<?php echo e(route('deals.calls.create',$deal->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Add Call')); ?>" class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th width=""><?php echo e(__('Subject')); ?></th>
                                        <th><?php echo e(__('Call Type')); ?></th>
                                        <th><?php echo e(__('Duration')); ?></th>
                                        <th><?php echo e(__('User')); ?></th>
                                        <th><?php echo e(__('Action')); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $calls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $call): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($call->subject); ?></td>
                                            <td><?php echo e(ucfirst($call->call_type)); ?></td>
                                            <td><?php echo e($call->duration); ?></td>
                                            <td><?php echo e(isset($call->getLeadCallUser) ? $call->getLeadCallUser->name : '-'); ?></td>
                                            <td>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit deal call')): ?>
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="<?php echo e(URL::to('deals/'.$deal->id.'/call/'.$call->id.'/edit')); ?>" data-ajax-popup="true" data-size="xl" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" data-title="<?php echo e(__('Edit Call')); ?>">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete deal call')): ?>
                                                    <div class="action-btn bg-danger ms-2">
                                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['deals.calls.destroy', $deal->id,$user->id],'id'=>'delete-form-'.$deal->id]); ?>

                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>"><i class="ti ti-trash text-white"></i></a>
                                                        <?php echo Form::close(); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="activity" class="card">
                        <div class="card-header">
                            <h5><?php echo e(__('Activity')); ?></h5>
                        </div>
                        <div class="card-body ">
                            <div class="row leads-scroll" >
                                <ul class="event-cards list-group list-group-flush mt-3 w-100">
                                    <?php if(!$deal->activities->isEmpty()): ?>
                                        <?php $__currentLoopData = $deal->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="list-group-item card mb-3">
                                                <div class="row align-items-center justify-content-between">
                                                    <div class="col-auto mb-3 mb-sm-0">
                                                        <div class="d-flex align-items-center">
                                                            <div class="theme-avtar bg-primary">
                                                                <i class="ti ti-<?php echo e($activity->logIcon()); ?>"></i>
                                                            </div>
                                                            <div class="ms-3">
                                                                <span class="text-dark text-sm"><?php echo e(__($activity->log_type)); ?></span>
                                                                <h6 class="m-0"><?php echo $activity->getRemark(); ?></h6>
                                                                <small class="text-muted"><?php echo e($activity->created_at->diffForHumans()); ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">

                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        No activity found yet.
                                    <?php endif; ?>
                                </ul>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/deals/show.blade.php ENDPATH**/ ?>