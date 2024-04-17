<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Expense Detail')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).on('click', '#shipping', function () {
            var url = $(this).data('url');
            var is_display = $("#shipping").is(":checked");
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    'is_display': is_display,
                },
                success: function (data) {
                    // console.log(data);
                }
            });
        })



    </script>
<?php $__env->stopPush(); ?>
<?php
    $settings = Utility::settings();
?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('expense.index')); ?>"><?php echo e(__('Expense')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(Auth::user()->expenseNumberFormat($expense->bill_id)); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                    <h4><?php echo e(__('Expense')); ?></h4>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                                    <h4 class="invoice-number"><?php echo e(Auth::user()->expenseNumberFormat($expense->bill_id)); ?></h4>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>




                            <div class="row">

                                <?php if($expense->user_type == 'employee'): ?>
                                    <div class="col-5">
                                        <small class="font-style">
                                            <strong><?php echo e(__('Employee Detail')); ?> :</strong><br>
                                            <?php if(!empty($user->name)): ?>
                                                <?php echo e(!empty($user->name)?$user->name:''); ?><br>
                                                <?php echo e(!empty($user->email)?$user->email:''); ?><br>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </small>
                                    </div>

                                <?php elseif($expense->user_type == 'customer'): ?>
                                    <div class="col-5">
                                        <small class="font-style">
                                            <strong><?php echo e(__('Billed To')); ?> :</strong><br>
                                            <?php if(!empty($user->billing_name)): ?>
                                                <?php echo e(!empty($user->billing_name)?$user->billing_name:''); ?><br>
                                                <?php echo e(!empty($user->billing_address)?$user->billing_address:''); ?><br>
                                                <?php echo e(!empty($user->billing_city)?$user->billing_city:'' .', '); ?><br>
                                                <?php echo e(!empty($user->billing_state)?$user->billing_state:'',', '); ?>,
                                                <?php echo e(!empty($user->billing_zip)?$user->billing_zip:''); ?><br>
                                                <?php echo e(!empty($user->billing_country)?$user->billing_country:''); ?><br>
                                                <?php echo e(!empty($user->billing_phone)?$user->billing_phone:''); ?><br>
                                                <?php if($settings['vat_gst_number_switch'] == 'on'): ?>
                                                    <strong><?php echo e(__('Tax Number ')); ?> : </strong><?php echo e(!empty($user->tax_number)?$user->tax_number:''); ?>

                                                <?php endif; ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <?php if(App\Models\Utility::getValByName('shipping_display')=='on'): ?>
                                        <div class="col-4">
                                            <small>
                                                <strong><?php echo e(__('Shipped To')); ?> :</strong><br>
                                                <?php if(!empty($user->shipping_name)): ?>
                                                    <?php echo e(!empty($user->shipping_name)?$user->shipping_name:''); ?><br>
                                                    <?php echo e(!empty($user->shipping_address)?$user->shipping_address:''); ?><br>
                                                    <?php echo e(!empty($user->shipping_city)?$user->shipping_city:'' . ', '); ?><br>
                                                    <?php echo e(!empty($user->shipping_state)?$user->shipping_state:'' .', '); ?>,
                                                    <?php echo e(!empty($user->shipping_zip)?$user->shipping_zip:''); ?><br>
                                                    <?php echo e(!empty($user->shipping_country)?$user->shipping_country:''); ?><br>
                                                    <?php echo e(!empty($user->shipping_phone)?$user->shipping_phone:''); ?><br>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <div class="col-5">
                                        <small class="font-style">
                                            <strong><?php echo e(__('Billed To')); ?> :</strong><br>
                                            <?php if(!empty($user->billing_name)): ?>
                                                <?php echo e(!empty($user->billing_name)?$user->billing_name:''); ?><br>
                                                <?php echo e(!empty($user->billing_address)?$user->billing_address:''); ?><br>
                                                <?php echo e(!empty($user->billing_city)?$user->billing_city:'' .', '); ?><br>
                                                <?php echo e(!empty($user->billing_state)?$user->billing_state:'',', '); ?>,
                                                <?php echo e(!empty($user->billing_zip)?$user->billing_zip:''); ?><br>
                                                <?php echo e(!empty($user->billing_country)?$user->billing_country:''); ?><br>
                                                <?php echo e(!empty($user->billing_phone)?$user->billing_phone:''); ?><br>
                                                <?php if($settings['vat_gst_number_switch'] == 'on'): ?>
                                                    <strong><?php echo e(__('Tax Number')); ?> : </strong><?php echo e(!empty($user->tax_number)?$user->tax_number:''); ?>

                                                <?php endif; ?>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <?php if(App\Models\Utility::getValByName('shipping_display')=='on'): ?>
                                        <div class="col-4">
                                            <small>
                                                <strong><?php echo e(__('Shipped To')); ?> :</strong><br>
                                                <?php if(!empty($user->shipping_name)): ?>
                                                    <?php echo e(!empty($user->shipping_name)?$user->shipping_name:''); ?><br>
                                                    <?php echo e(!empty($user->shipping_address)?$user->shipping_address:''); ?><br>
                                                    <?php echo e(!empty($user->shipping_city)?$user->shipping_city:'' . ', '); ?><br>
                                                    <?php echo e(!empty($user->shipping_state)?$user->shipping_state:'' .', '); ?>,
                                                    <?php echo e(!empty($user->shipping_zip)?$user->shipping_zip:''); ?><br>
                                                    <?php echo e(!empty($user->shipping_country)?$user->shipping_country:''); ?><br>
                                                    <?php echo e(!empty($user->shipping_phone)?$user->shipping_phone:''); ?><br>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>



                                <div class="col">
                                    <small>
                                        <strong><?php echo e(__('Payment Date')); ?> :</strong><br>
                                        <?php echo e(\Auth::user()->dateFormat($expense->bill_date)); ?><br><br>
                                    </small>

                                </div>

                            </div>
                            <div class="row">

                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong><?php echo e(__('Status')); ?> : </strong><br>
                                            <span class="badge bg-primary p-2 px-3 rounded"><?php echo e(__(\App\Models\Bill::$statues[$expense->status])); ?></span>

                                    </small>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-bold mb-2"><?php echo e(__('Product Summary')); ?></div>
                                    <small class="mb-2"><?php echo e(__('All items here cannot be deleted.')); ?></small>
                                    <div class="table-responsive mt-3">
                                        <table class="table mb-0 table-striped">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                <th class="text-dark"><?php echo e(__('Product')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Quantity')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Rate')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Discount')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Tax')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Chart Of Account')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Account Amount')); ?></th>
                                                <th class="text-dark"><?php echo e(__('Description')); ?></th>
                                                <th class="text-end text-dark" width="12%"><?php echo e(__('Price')); ?><br>
                                                    <small class="text-danger font-weight-bold"><?php echo e(__('after tax & discount')); ?></small>
                                                </th>
                                                <th></th>
                                            </tr>
                                            <?php
                                                $totalQuantity=0;
                                               $totalRate=0;
                                               $totalTaxPrice=0;
                                               $totalDiscount=0;
                                               $taxesData=[];
                                            ?>



                                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <?php if(!empty($item->product_id)): ?>
                                                        <tr>
                                                            <td><?php echo e($key+1); ?></td>
                                                            <?php
                                                                $productName = $item->product;
                                                                $totalQuantity += $item->quantity;
                                                                $totalRate += $item->price;
                                                                $totalDiscount += $item->discount;   
                                                            ?>
                                                            <td><?php echo e(!empty($productName)?$productName->name:'-'); ?></td>
                                                            <td><?php echo e($item->quantity . ' (' . $productName->unit->name . ')'); ?></td>
                                                            <td><?php echo e(\Auth::user()->priceFormat($item->price)); ?></td>
                                                            <td><?php echo e(\Auth::user()->priceFormat($item->discount)); ?></td>
                                                            <td>
                                                                <?php if(!empty($item->tax)): ?>
                                                                    <table>
                                                                        <?php
                                                                            $itemTaxes = [];
                                                                            $getTaxData = Utility::getTaxData();
        
                                                                            if (!empty($item->tax)) {
                                                                                foreach (explode(',', $item->tax) as $tax) {
                                                                                    $taxPrice = \Utility::taxRate($getTaxData[$tax]['rate'], $item->price, $item->quantity);
                                                                                    $totalTaxPrice += $taxPrice;
                                                                                    $itemTax['name'] = $getTaxData[$tax]['name'];
                                                                                    $itemTax['rate'] = $getTaxData[$tax]['rate'] . '%';
                                                                                    $itemTax['price'] = \Auth::user()->priceFormat($taxPrice);
        
                                                                                    $itemTaxes[] = $itemTax;
                                                                                    if (array_key_exists($getTaxData[$tax]['name'], $taxesData)) {
                                                                                        $taxesData[$getTaxData[$tax]['name']] = $taxesData[$getTaxData[$tax]['name']] + $taxPrice;
                                                                                    } else {
                                                                                        $taxesData[$getTaxData[$tax]['name']] = $taxPrice;
                                                                                    }
                                                                                }
                                                                                $item->itemTax = $itemTaxes;
                                                                            } else {
                                                                                $item->itemTax = [];
                                                                            }
                                                                        ?>
                                                                        <?php $__currentLoopData = $item->itemTax; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
                                                                                <tr>
                                                                                    <td><?php echo e($tax['name'] .' ('.$tax['rate'] .'%)'); ?></td>
                                                                                    <td><?php echo e($tax['price']); ?></td>
                                                                                </tr>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </table>
                                                                <?php else: ?>
                                                                    -
                                                                <?php endif; ?>
                                                            </td>

                                                            <?php
                                                                $chartAccount = \App\Models\ChartOfAccount::find($item->chart_account_id);
                                                            ?>

                                                            <td><?php echo e(!empty($chartAccount) ? $chartAccount->name : '-'); ?></td>
                                                            <td><?php echo e(\Auth::user()->priceFormat($item->amount)); ?></td>

                                                            <td><?php echo e(!empty($item->description)?$item->description:'-'); ?></td>

                                                            <td class="text-end"><?php echo e(\Auth::user()->priceFormat(($item->price * $item->quantity - $item->discount) + $totalTaxPrice)); ?></td>
                                                            <td></td>
                                                        </tr>
                                                    <?php else: ?>
                                                    <tr>
                                                        <td><?php echo e($key+1); ?></td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <?php
                                                            $chartAccount = \App\Models\ChartOfAccount::find($item['chart_account_id']);
                                                        ?>
                                                        <td><?php echo e(!empty($chartAccount) ? $chartAccount->name : '-'); ?></td>
                                                        <td><?php echo e(\Auth::user()->priceFormat($item['amount'])); ?></td>
                                                        <td>-</td>
                                                        <td class="text-end"><?php echo e(\Auth::user()->priceFormat($item['amount'])); ?></td>
                                                        <td></td>


                                                    </tr>

                                                <?php endif; ?>


                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td><b><?php echo e(__('Total')); ?></b></td>
                                                <td><b><?php echo e($totalQuantity); ?></b></td>
                                                <td><b><?php echo e(\Auth::user()->priceFormat($totalRate)); ?></b></td>
                                                <td><b><?php echo e(\Auth::user()->priceFormat($totalDiscount)); ?></b></td>
                                                <td><b><?php echo e(\Auth::user()->priceFormat($totalTaxPrice)); ?></b></td>
                                                <td></td>
                                                <td><b><?php echo e(\Auth::user()->priceFormat($expense->getAccountTotal())); ?></b></td>

                                            </tr>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-end"><b><?php echo e(__('Sub Total')); ?></b></td>
                                                <td class="text-end"><?php echo e(\Auth::user()->priceFormat($expense->getSubTotal())); ?></td>
                                            </tr>

                                                <tr>
                                                    <td colspan="8"></td>
                                                    <td class="text-end"><b><?php echo e(__('Discount')); ?></b></td>
                                                    <td class="text-end"><?php echo e(\Auth::user()->priceFormat($expense->getTotalDiscount())); ?></td>
                                                </tr>

                                            <?php if(!empty($taxesData)): ?>
                                                <?php $__currentLoopData = $taxesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taxName => $taxPrice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td colspan="8"></td>
                                                        <td class="text-end"><b><?php echo e($taxName); ?></b></td>
                                                        <td class="text-end"><?php echo e(\Auth::user()->priceFormat($taxPrice)); ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="blue-text text-end"><b><?php echo e(__('Total')); ?></b></td>
                                                <td class="blue-text text-end"><?php echo e(\Auth::user()->priceFormat($expense->getTotal())); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-end"><b><?php echo e(__('Paid')); ?></b></td>
                                                <td class="text-end"><?php echo e(\Auth::user()->priceFormat(($expense->getTotal()-$expense->getDue())-($expense->billTotalDebitNote()))); ?></td>
                                            </tr>

                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/expense/view.blade.php ENDPATH**/ ?>