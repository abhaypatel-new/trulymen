<?php echo e(Form::open(array('url' => 'project-task-new-stage'))); ?>

<div class="modal-body">

    <div class="row">
        <div class="form-group col-12">
            <?php echo e(Form::label('name', __('Project Task Stage Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group col-12">
            <?php echo e(Form::label('color', __('Color'),['class'=>'form-label'])); ?>

            <input class="jscolor form-control" value="FFFFFF" name="color" id="color" required>
            <small class="small"><?php echo e(__('For chart representation')); ?></small>
        </div>

    </div>
</div>
<div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/task_stage/create.blade.php ENDPATH**/ ?>