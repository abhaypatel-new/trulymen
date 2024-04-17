<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Meeting')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Meeting')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create meeting')): ?>
            <a href="<?php echo e(route('meeting.calender')); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Calender View')); ?>" data-original-title="<?php echo e(__('Calendar View')); ?>">
                <i class="ti ti-calendar"></i>
            </a>
            <a href="#" data-url="<?php echo e(route('meeting.create')); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Create New Meeting')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>"  class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Meeting title')); ?></th>
                                <th><?php echo e(__('Meeting Date')); ?></th>
                                <th><?php echo e(__('Meeting Time')); ?></th>
                                <?php if(Gate::check('edit meeting') || Gate::check('delete meeting')): ?>
                                    <th width="200px"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            <?php $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($meeting->title); ?></td>
                                    <td><?php echo e(\Auth::user()->dateFormat($meeting->date)); ?></td>
                                    <td><?php echo e(\Auth::user()->timeFormat($meeting->time)); ?></td>
                                    <?php if(Gate::check('edit meeting') || Gate::check('delete meeting')): ?>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit meeting')): ?>
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#" data-url="<?php echo e(URL::to('meeting/'.$meeting->id.'/edit')); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Edit Meeting')); ?>" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" data-original-title="<?php echo e(__('Edit')); ?>"><i class="ti ti-pencil text-white"></i></a>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete meeting')): ?>
                                            <div class="action-btn bg-danger ms-2">
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['meeting.destroy', $meeting->id],'id'=>'delete-form-'.$meeting->id]); ?>

                                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($meeting->id); ?>').submit();"><i class="ti ti-trash text-white"></i></a>
                                                <?php echo Form::close(); ?>

                                            </div>
                                            <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>

    <script>

        $(document).ready(function () {
            var b_id = $('#branch_id').val();
            getDepartment(b_id);
        });
        $(document).on('change', 'select[name=branch_id]', function () {

            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(bid) {

            $.ajax({
                url: '<?php echo e(route('meeting.getdepartment')); ?>',
                type: 'POST',
                data: {
                    "branch_id": bid,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },

                success: function (data) {
                    console.log(data);
                    $('#department_id').empty();

                    $("#department_div").html('');
                    $('#department_div').append('<select class="form-control" id="department_id" name="department_id[]"  multiple></select>');

                    $('#department_id').append('<option value=""><?php echo e(__('Select Department')); ?></option>');

                    $('#department_id').append('<option value="0"> <?php echo e(__('All Department')); ?> </option>');
                    $.each(data, function (key, value) {
                        console.log(key, value);
                        $('#department_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    var multipleCancelButton = new Choices('#department_id', {
                        removeItemButton: true,
                    });


                }

            });
        }

        $(document).on('change', '#department_id', function () {
            var department_id = $(this).val();
            getEmployee(department_id);
        });

        function getEmployee(did) {

            $.ajax({
                url: '<?php echo e(route('meeting.getemployee')); ?>',
                type: 'POST',
                data: {
                    "department_id": did, "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function (data) {
                    console.log(data);
                    $('#employee_id').empty();

                    $("#employee_div").html('');
                    $('#employee_div').append('<select class="form-control" id="employee_id" name="employee_id[]"  multiple></select>');


                    $('#employee_id').append('<option value=""><?php echo e(__('Select Employee')); ?></option>');
                    $('#employee_id').append('<option value="0"> <?php echo e(__('All Employee')); ?> </option>');

                    $.each(data, function (key, value) {
                        $('#employee_id').append('<option value="' + key + '">' + value + '</option>');
                    });

                    var multipleCancelButton = new Choices('#employee_id', {
                        removeItemButton: true,
                    });
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/meeting/index.blade.php ENDPATH**/ ?>