<?php echo e(Form::open(array('route' => ['leads.emails.store',$lead->id]))); ?>

<div class="modal-body">
    <div class="row">
        <div class="col-6 form-group">
            <?php echo e(Form::label('to', __('Mail To'),['class'=>'form-label'])); ?>

            <?php echo e(Form::email('to', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('subject', __('Subject'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('subject', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="col-12 form-group">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

        <?php echo e(Form::textarea('description', null, array('class' => 'summernote-simple'))); ?>

        </div>
        <script>
            $('#emails-summernote').summernote();
        </script>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>

<?php echo e(Form::close()); ?>

<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/leads/emails.blade.php ENDPATH**/ ?>