<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/libs/summernote/summernote-bs4.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/libs/summernote/summernote-bs4.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php if(isset($call)): ?>
    <?php echo e(Form::model($call, array('route' => array('deals.calls.update', $deal->id, $call->id), 'method' => 'PUT'))); ?>

<?php else: ?>
    <?php echo e(Form::open(array('route' => ['deals.calls.store',$deal->id]))); ?>

<?php endif; ?>
<div class="modal-body">
    
    <?php
        $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['deal'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-6 form-group">
            <?php echo e(Form::label('subject', __('Subject'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('subject', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('call_type', __('Call Type'),['class'=>'form-label'])); ?>

            <select name="call_type" id="choices-multiple1" class="form-control select2" required>
                <option value="outbound" <?php if(isset($call->call_type) && $call->call_type == 'outbound'): ?> selected <?php endif; ?>><?php echo e(__('Outbound')); ?></option>
                <option value="inbound" <?php if(isset($call->call_type) && $call->call_type == 'inbound'): ?> selected <?php endif; ?>><?php echo e(__('Inbound')); ?></option>
            </select>
        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('duration', __('Duration'),['class'=>'form-label'])); ?> <small class="font-weight-bold"><?php echo e(__(' (Format h:m:s i.e 00:35:20 means 35 Minutes and 20 Sec)')); ?></small>
            <?php echo e(Form::time('duration', null, array('class' => 'form-control','placeholder'=>'00:35:20','step' => '2'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('user_id', __('Assignee'),['class'=>'form-label'])); ?>

            <select name="user_id" id="choices-multiple2" class="form-control select2"  required>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->getDealUser->id); ?>" <?php if(isset($call->user_id) && $call->user_id == $user->getDealUser->id): ?> selected <?php endif; ?>><?php echo e($user->getDealUser->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-12 form-group">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('description', null, array('class' => 'form-control'))); ?>

        </div>
        <div class="col-12 form-group">
            <?php echo e(Form::label('call_result', __('Call Result'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('call_result', null, array('class' => 'summernote-simple'))); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <?php if(isset($call)): ?>
        <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
    <?php else: ?>
        <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
    <?php endif; ?>
</div>

<?php echo e(Form::close()); ?>


<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/deals/calls.blade.php ENDPATH**/ ?>