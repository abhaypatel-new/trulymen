<?php echo e(Form::model($deal, array('route' => array('deals.update', $deal->id), 'method' => 'PUT'))); ?>

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
            <?php echo e(Form::label('name', __('Deal Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('phone', __('Phone'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('phone', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('price', __('Price'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('price', null, array('class' => 'form-control'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('pipeline_id', __('Pipeline'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control ','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('stage_id', __('Stage'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('stage_id', [''=>__('Select Stage')],null, array('class' => 'form-control ','required'=>'required'))); ?>

        </div>
        <div class="col-12 form-group">
            <?php echo e(Form::label('sources', __('Sources'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('sources[]', $sources,null, array('class' => 'form-control select2','multiple'=>'','id'=>'choices-multiple3','required'=>'required'))); ?>

        </div>
        <div class="col-12 form-group">
            <?php echo e(Form::label('products', __('Products'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('products[]', $products,null, array('class' => 'form-control select2','multiple'=>'','id'=>'choices-multiple4','required'=>'required'))); ?>

        </div>
        <div class="col-12 form-group">
            <?php echo e(Form::label('notes', __('Notes'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('notes',null, array('class' => 'summernote-simple'))); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>




<script>
    var stage_id = '<?php echo e($deal->stage_id); ?>';

    $(document).ready(function () {
        $("#commonModal select[name=pipeline_id]").trigger('change');
    });

    $(document).on("change", "#commonModal select[name=pipeline_id]", function () {
        $.ajax({
            url: '<?php echo e(route('stages.json')); ?>',
            data: {pipeline_id: $(this).val(), _token: $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (data) {
                $('#stage_id').empty();
                $("#stage_id").append('<option value="" selected="selected"><?php echo e(__('Select Stage')); ?></option>');
                $.each(data, function (key, data) {
                    var select = '';
                    if (key == '<?php echo e($deal->stage_id); ?>') {
                        select = 'selected';
                    }
                    $("#stage_id").append('<option value="' + key + '" ' + select + '>' + data + '</option>');
                });
                $("#stage_id").val(stage_id);
                $('#stage_id').select2({
                    placeholder: "<?php echo e(__('Select Stage')); ?>"
                });
            }
        })
    });
</script>
<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/deals/edit.blade.php ENDPATH**/ ?>