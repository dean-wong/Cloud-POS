<!-- Begin page content -->
<div class="container">
    <div class="row">

        <div class="col-xs-3">

            <?php $error_validmsg = validation_errors(); ?>
            <?php if (!empty($error_validmsg)) : ?>
                <div class="alert alert-warning" role="alert"><?= $error_validmsg ?></div>
            <?php endif; ?>
            <?= form_open("pos/order/$cur_ticket->_id") ?>
            <?php require_once(__DIR__ . '/menu_select_form.php'); ?>
            <?= form_close() ?>
        </div>

        <div class="col-xs-9">
            <?php require_once(__DIR__ . '/menu_table_list.php'); ?>
        </div>
    </div>
</div>
