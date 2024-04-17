<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Promotion')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Promotion')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create promotion')): ?>
            <a href="#" data-url="<?php echo e(route('promotion.create')); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Create New Promotion')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>"  class="btn btn-sm btn-primary">
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
                                <?php if(\Spatie\Permission\PermissionServiceProvider::bladeMethodWrapper('hasRole', 'company')): ?>
                                <th><?php echo e(__('Employee Name')); ?></th>
                                <?php endif; ?>
                                <th><?php echo e(__('Designation')); ?></th>
                                <th><?php echo e(__('Promotion Title')); ?></th>
                                <th><?php echo e(__('Promotion Date')); ?></th>
                                <th><?php echo e(__('Description')); ?></th>
                                <?php if(Gate::check('edit promotion') || Gate::check('delete promotion')): ?>
                                    <th width="200px"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            <?php $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php if(\Spatie\Permission\PermissionServiceProvider::bladeMethodWrapper('hasRole', 'company')): ?>
                                    <td><?php echo e(!empty($promotion->employee)?$promotion->employee->name:''); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e(!empty($promotion->designation)?$promotion->designation->name:''); ?></td>
                                    <td><?php echo e($promotion->promotion_title); ?></td>
                                    <td><?php echo e(\Auth::user()->dateFormat($promotion->promotion_date)); ?></td>
                                    <td><?php echo e($promotion->description); ?></td>
                                    <?php if(Gate::check('edit promotion') || Gate::check('delete promotion')): ?>
                                        <td>
                                         
                                           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit promotion')): ?>
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="<?php echo e(URL::to('promotion/'.$promotion->id.'/edit')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Promotion')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" data-original-title="<?php echo e(__('Edit')); ?>">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                                </div>
                                           <?php endif; ?>

                                        

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete promotion')): ?>
                                                <div class="action-btn bg-danger ms-2">
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['promotion.destroy', $promotion->id],'id'=>'delete-form-'.$promotion->id]); ?>

                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($promotion->id); ?>').submit();">
                                                <i class="ti ti-trash text-white"></i>
                                                </a>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/promotion/index.blade.php ENDPATH**/ ?>