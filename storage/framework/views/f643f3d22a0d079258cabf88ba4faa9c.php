<?php echo e(Form::open(array('url'=>'saturationdeduction','method'=>'post'))); ?>

<div class="modal-body">

    <?php echo e(Form::hidden('employee_id',$employee->id, array())); ?>

    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('deduction_option', __('Deduction Options'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::select('deduction_option',$deduction_options,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('title', __('Title'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('title',null, array('class' => 'form-control ','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('type', __('Type'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('type', $saturationdeduc, null, ['class' => 'form-control select amount_type', 'required' => 'required'])); ?>

        </div>

        <div class="form-group col-md-6">
            <?php echo e(Form::label('amount', __('Amount'),['class'=>'form-label amount_label'])); ?>

            <?php echo e(Form::number('amount',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01'))); ?>

        </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>

    <?php echo e(Form::close()); ?>

<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/saturationdeduction/create.blade.php ENDPATH**/ ?>