<?php if(!empty($employee)): ?>
    <div class="row">
        <div class="col-md-5">
            <h6><?php echo e(__('Employee Details')); ?></h6>
            <div class="bill-to">
                <?php if(!empty($employee->name)): ?>
                    <small>
                        <span><?php echo e($employee->name); ?></span><br>
                        <span><?php echo e($employee->email); ?></span><br>
                        <span><?php echo e($employee->phone); ?></span><br>
                        <span><?php echo e($employee->address); ?></span><br>

                    </small>
                <?php else: ?>
                    <br> -
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-2">
            <a href="#" id="remove" class="text-sm"><?php echo e(__(' Remove')); ?></a>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/expense/employee_detail.blade.php ENDPATH**/ ?>