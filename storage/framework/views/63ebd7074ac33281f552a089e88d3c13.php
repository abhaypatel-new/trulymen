<?php echo e(Form::open(array('url'=>'complaint','method'=>'post'))); ?>

<div class="modal-body">
    
    <?php
        $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['complaint'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <?php if(\Auth::user()->type !='employee'): ?>
            <div class="form-group col-md-6 col-lg-6 ">
                <?php echo e(Form::label('complaint_from', __('Complaint From'),['class'=>'form-label'])); ?>

                <?php echo e(Form::select('complaint_from', $employees,null, array('class' => 'form-control  select','required'=>'required'))); ?>

            </div>
        <?php endif; ?>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('complaint_against',__('Complaint Against'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('complaint_against',$employees,null,array('class'=>'form-control select'))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('title',__('Title'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('title',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Complaint Title')))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('complaint_date',__('Complaint Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('complaint_date',null,array('class'=>'form-control'))); ?>

        </div>
        <div class="form-group col-md-12">
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

<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/complaint/create.blade.php ENDPATH**/ ?>