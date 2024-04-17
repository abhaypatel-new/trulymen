<?php echo e(Form::open(array('url'=>'promotion','method'=>'post'))); ?>

<div class="modal-body">
    
    <?php
        $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['promotion'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('employee_id', __('Employee'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('employee_id', $employees,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('designation_id',__('Designation'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('designation_id',$designations,null,array('class'=>'form-control select'))); ?>

        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('promotion_title',__('Promotion Title'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('promotion_title',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Promotion Title')))); ?>

        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('promotion_date',__('Promotion Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('promotion_date',null,array('class'=>'form-control '))); ?>

        </div>
        <div class="form-group col-lg-12">
            <?php echo e(Form::label('description',__('Description'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('description',null,array('class'=>'form-control','placeholder'=>__('Enter Description')))); ?>

        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?>

<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/promotion/create.blade.php ENDPATH**/ ?>