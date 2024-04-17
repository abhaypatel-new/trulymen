<?php echo e(Form::model($lead, array('route' => array('leads.products.update', $lead->id), 'method' => 'PUT'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group">
            <?php echo e(Form::label('products', __('Products'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('products[]', $products,false, array('class' => 'form-control select2','id'=>'choices-multiple3','multiple'=>'','required'=>'required'))); ?>

        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Add')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?>



<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/leads/products.blade.php ENDPATH**/ ?>