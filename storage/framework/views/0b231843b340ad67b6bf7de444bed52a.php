<?php
    $settings = Utility::settings();
?>
    <!DOCTYPE html>
<html lang="en" dir="<?php echo e($settings == 'on'?'rtl':''); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/main.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/style.css')); ?>">

    <!-- font css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/tabler-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/feather.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/fontawesome.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/material.css')); ?>">

    <!-- vendor css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" id="main-style-link">

    <title><?php echo e(env('APP_NAME')); ?> - POS Barcode</title>
    <?php if(isset($settings['SITE_RTL'] ) && $settings['SITE_RTL'] == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-rtl.css')); ?>" id="main-style-link">
    <?php endif; ?>
</head>
<body>
<div id="bot" class="mt-5">
    <div class="row">
        <?php $__currentLoopData = $productServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php for($i=1;$i<=$quantity;$i++): ?>
                <div class="col-auto mb-2">
                    <small class=""><?php echo e($product->name); ?></small>
                    <div data-id="<?php echo e($product->id); ?>" class="product_barcode product_barcode_hight_de product_barcode_<?php echo e($product->id); ?> mt-2" data-skucode="<?php echo e($product->sku); ?>"></div>
                </div>
            <?php endfor; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<script>
    window.print();
    window.onafterprint = back;

    function back() {
        window.close();
        window.history.back();
    }
</script>
<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('public/js/jquery-barcode.min.js')); ?>"></script>
<script src="<?php echo e(asset('public/js/jquery-barcode.js')); ?>"></script>
<script>
    $(document).ready(function() {
        $(".product_barcode").each(function() {
            var id = $(this).data("id");
            var sku = $(this).data('skucode');
            sku = encodeURIComponent(sku);
            generateBarcode(sku, id);
        });
    });
    function generateBarcode(val, id) {
        var value = val;
        var btype = '<?php echo e($barcode['barcodeType']); ?>';
        var renderer = '<?php echo e($barcode['barcodeFormat']); ?>';
        var settings = {
            output: renderer,
            bgColor: '#FFFFFF',
            color: '#000000',
            barWidth: '1',
            barHeight: '50',
            moduleSize: '5',
            posX: '10',
            posY: '20',
            addQuietZone: '1'
        };
        $('.product_barcode_' + id).html("").show().barcode(value, btype, settings);

    }
</script>
</body>
</html>
<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/pos/receipt.blade.php ENDPATH**/ ?>