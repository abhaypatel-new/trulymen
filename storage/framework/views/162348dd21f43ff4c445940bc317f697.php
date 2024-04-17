<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Verify Email')); ?>

<?php $__env->stopSection(); ?>
<?php
    //  $logo=asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo');
    $company_logo = Utility::getValByName('company_logo');
    $lang = \App::getLocale('lang');

?>

<?php
    $languages = App\Models\Utility::languages();
?>
<?php $__env->startSection('language-bar'); ?>
    <div class="lang-dropdown-only-desk">
        <li class="dropdown dash-h-item drp-language">
            <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="drp-text"> <?php echo e($languages[$lang]); ?>

                </span>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('verification.notice', $code)); ?>" tabindex="0" class="dropdown-item ">
                        <span><?php echo e(Str::upper($language)); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </li>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card-body">
        <?php if(session('status') == 'verification-link-sent'): ?>
            <div class="mb-4 font-medium text-sm text-green-600 text-primary">
                <?php echo e(__('A new verification link has been sent to the email address you provided during registration.')); ?>

            </div>
        <?php endif; ?>
        <div class="mb-4 text-sm text-gray-600">
            <?php echo e(__('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.')); ?>

        </div>
        <div class="mt-4 flex items-center justify-between">
            <div class="row">
                <div class="col-auto">
                    <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary btn-sm"> <?php echo e(__('Resend Verification Email')); ?>

                        </button>
                    </form>
                </div>
                <div class="col-auto">
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-danger btn-sm"><?php echo e(__('Logout')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u217475692/domains/truelymatch.com/public_html/trumen/resources/views/auth/verify.blade.php ENDPATH**/ ?>