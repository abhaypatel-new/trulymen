<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('POS Barcode Print')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('pos.barcode')); ?>"><?php echo e(__('POS Product Barcode')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('POS Barcode Print')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/datatable/buttons.dataTables.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-page'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
    <script>

        $(document).ready(function () {
            var b_id = $('#warehouse_id').val();
            getProduct(b_id);
        });
        $(document).on('change', 'select[name=warehouse_id]', function () {

            var warehouse_id = $(this).val();
            getProduct(warehouse_id);
        });

        function getProduct(bid) {

            $.ajax({
                url: '<?php echo e(route('pos.getproduct')); ?>',
                type: 'POST',
                data: {
                    "warehouse_id": bid, "_token": "<?php echo e(csrf_token()); ?>",
                },

                success: function (data) {
                    // console.log(data);
                    $('#product_id').empty();

                    $("#product_div").html('');
                    $('#product_div').append('<label for="product_id" class="form-label"><?php echo e(__('Product')); ?></label>');
                    $('#product_div').append('<select class="form-label" id="product_id" name="product_id[]"  multiple></select>');
                    $('#product_id').append('<option value=""><?php echo e(__('Select Product')); ?></option>');

                    $.each(data, function (key, value) {
                        console.log(key, value);
                        $('#product_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    var multipleCancelButton = new Choices('#product_id', {
                        removeItemButton: true,
                    });

                }

            });
        }


    </script>
    <script>
        function copyToClipboard(element) {
            var copyText = element.id;
            navigator.clipboard.writeText(copyText);
            // document.addEventListener('copy', function (e) {
            //     e.clipboardData.setData('text/plain', copyText);
            //     e.preventDefault();
            // }, true);
            // document.execCommand('copy');
            show_toastr('success', 'Url copied to clipboard', 'success');
        }
    </script>
    <script>
        var filename = $('#filesname').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).save();

        }
    </script>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <a href="<?php echo e(route('pos.barcode')); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Back')); ?>">
            <i class="ti ti-arrow-left text-white"></i>
        </a>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php echo e(Form::open(array('route'=>'pos.receipt','method'=>'post'))); ?>

                        <div class="row" id="printableArea">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php echo e(Form::label('warehouse_id',__('Warehouse'),['class'=>'form-label'])); ?>

                                    <?php echo e(Form::select('warehouse_id', $warehouses,'', array('class' => 'form-control select','id'=>'warehouse_id','required'=>'required'))); ?>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="product_div">
                                    <?php echo e(Form::label('product_id',__('Product'),['class'=>'form-label'])); ?>

                                    <select class="form-control select" name="product_id[]" id="product_id" required >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <?php echo e(Form::label('quantity', __('Quantity'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                                <?php echo e(Form::text('quantity',null, array('class' => 'form-control','required'=>'required'))); ?>

                            </div>
                        </div>
                        <div class="col-md-6 pt-4">
                            <button class="btn btn-sm btn-primary btn-icon" type="submit"><?php echo e(__('Print')); ?></button>
                        </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/pos/print.blade.php ENDPATH**/ ?>