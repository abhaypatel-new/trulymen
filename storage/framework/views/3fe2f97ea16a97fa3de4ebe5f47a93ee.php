<?php echo e(Form::open(array('url'=>'meeting','method'=>'post'))); ?>

<div class="modal-body">
    
    <?php
        $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['meeting'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('branch_id',__('Branch'),['class'=>'form-label'])); ?>

                <select class="form-control select" name="branch_id" id="branch_id" placeholder="Select Branch">
                    <option value=""><?php echo e(__('Select Branch')); ?></option>
                    <option value="0"><?php echo e(__('All Branch')); ?></option>
                    <?php $__currentLoopData = $branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group" id="department_div">
                <?php echo e(Form::label('department_id',__('Department'),['class'=>'form-label'])); ?>

                <select class="form-control select" name="department_id[]" id="department_id" placeholder="Select Department" >
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group" id="employee_div">
                <?php echo e(Form::label('employee_id',__('Employee'),['class'=>'form-label'])); ?>

                <select class="form-control select" name="employee_id[]" id="employee_id" placeholder="Select Employee" >
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('title',__('Meeting Title'),['class'=>'form-label'])); ?>

                <?php echo e(Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Meeting Title')))); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('date',__('Meeting Date'),['class'=>'form-label'])); ?>

                <?php echo e(Form::date('date',null,array('class'=>'form-control '))); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('time',__('Meeting Time'),['class'=>'form-label'])); ?>

                <?php echo e(Form::time('time',null,array('class'=>'form-control timepicker'))); ?>

            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('note',__('Meeting Note'),['class'=>'form-label'])); ?>

                <?php echo e(Form::textarea('note',null,array('class'=>'form-control','placeholder'=>__('Enter Meeting Note')))); ?>

            </div>
        </div>

        <?php if(isset($settings['google_calendar_enable']) && $settings['google_calendar_enable'] == 'on'): ?>
            <div class="form-group col-md-6 ">
                <?php echo e(Form::label('synchronize_type',__('Synchronize in Google Calendar ?'),array('class'=>'form-label'))); ?>

                <div class="form-switch">
                    <input type="checkbox" class="form-check-input mt-2" name="synchronize_type" id="switch-shadow" value="google_calender">
                    <label class="form-check-label" for="switch-shadow"></label>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>


<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/meeting/create.blade.php ENDPATH**/ ?>