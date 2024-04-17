<?php echo e(Form::open(array('url' => 'contractType'))); ?>

    <div class="modal-body">
        <div class="row">
            <div class="form-group">
                <?php echo e(Form::label('name', __('Name'))); ?>

                <?php echo e(Form::text('name', '', array('class' => 'form-control','required'=>'required'))); ?>

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
    </div>
<?php echo e(Form::close()); ?>




<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/contractType/create.blade.php ENDPATH**/ ?>