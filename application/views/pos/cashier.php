<!-- Begin page content -->
<div class="container">
    <div class="row">
        <div class="col-xs-4">
            <h2> <?= $ticket->_table_name ?> 台单 </h2>

            <p> 开台时间：<?= $ticket->_create_time ?> </p>

            <p> 总单价：<strong>￥<?= number_format($ticket->_total_price, 2) ?></strong></p>

            <div class="controls">
                <div class="list-group" id="menu-list">
                    <?php $item_count = 0;
                    $item_colos = ['list-group-item-success', 'list-group-item-info', 'list-group-item-warning', 'list-group-item-danger']; ?>
                    <?php foreach ($ticket->_items as $item) : ?>
                        <?php if (++$item_count < 9) : ?>
                            <div class="list-group-item <?= $item_colos[($item['menu_id']) % 4] ?>"
                                 id="<?= $item['menu_id'] ?>">
                                <span class="badge"><?= $item['menu_count'] ?></span>
                                <?= $item['menu_name'] ?>
                                <small>￥<?= number_format($item['total_price'], 2) ?></small>
                            </div>
                        <?php else: ?>
                            <a href="<?= site_url("/pos/ticket/$ticket->_id") ?>">
                                <div class="list-group-item">查看更多 »</div>
                            </a>
                            <?php break; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <h2>付款方式</h2>

            <div class="container-fluid">
                <div class="list-group" id="payment-group">
                    <input  class="sr-only"  type="text" id="current-payment-id" value="1"/>

                    <?php $btn_icons = ['fa fa-internet-explorer', 'fa fa-money', 'fa fa-credit-card', 'fa fa-group', 'fa fa-magic', 'fa fa-bank']; ?>
                    <?php foreach ($payments as $item) : ?>
                        <button type="button" class="list-group-item <?= $item->id == 1?'active':'' ?>"
                                id="radio_payment-<?= $item->id ?>" value=""
                                onClick=javascript:setPayment(this,<?= $item->id ?>)>
                            <span class="badge"></span>
                            <i class="<?= $btn_icons[$item->id % 6] ?>"></i> <?= $item->name ?>
                        </button>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <?php $error_validmsg = validation_errors(); ?>
            <?php if (!empty($error_validmsg)) : ?>
                <div class="alert alert-warning" role="alert"><?= $error_validmsg ?></div>
            <?php endif; ?>
            <?= form_open("pos/cashier/$ticket->_id", ['class' => 'form-horizontal']) ?>
<!--            <form class="form-horizontal">-->
                <fieldset>
                    <div class="form-group">
                        <button class="btn btn-block btn-danger btn-lg" id="btn-cashier" disabled="disabled">
                            <i class="fa fa-check"></i> 结账
                        </button>
                        <?php foreach ($payments as $item) : ?>
                        <input class="sr-only" type="text" name="radio_payments[]" id="input_payment-<?= $item->id ?>" value="0"/>
                        <?php endforeach; ?>

                    </div>

                    <div class="form-group">
                        <?php $input_controls = [
                            ['id' => 'input-total-amount', 'label' => '原价：', 'value' => number_format($ticket->_total_price, 2)],
                            ['id' => 'input-final-amount', 'label' => '应收：', 'value' => number_format(($ticket->_total_price - $ticket->_discount), 2)],
                            ['id' => 'input-paid-amount', 'label' => '支付：', 'value' => '0.00'],
                            ['id' => 'input-change', 'label' => '找零：', 'value' => '0.00'],
                        ];
                        foreach ($input_controls as $ctrl_item) : ?>
                            <label for="<?= $ctrl_item['id'] ?>" class="col-sm-3 control-label"
                                   style="text-align: left"><?= $ctrl_item['label'] ?></label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">￥</span>
                                    <input type="text" class="form-control" id="<?= $ctrl_item['id'] ?>" name="<?= $ctrl_item['id'] ?>"
                                           placeholder="<?= $ctrl_item['value'] ?>"
                                           value="<?= $ctrl_item['value'] ?>" readonly>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>


                    <div class="form-group">
                        <!--<label class="sr-only" for="input-cash">Cash</label>
                        <input type="text" class="form-control text-right" id="input-cash" placeholder="0.00"/>-->
                        <label class="sr-only" for="input-cash">Cash</label>

                        <div class="input-group">
                            <span class="input-group-addon">￥</span>
                            <input type="text" class="form-control text-right" name="input-cash" id="input-cash"
                                   aria-label="Amount (to the nearest dollar)" placeholder="0.00"/>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" onclick=javascript:inputCalculator("B")>
                            <i class="fa fa-arrow-left"></i></button>
                    </span>
                        </div>
                        <div class="btn-group btn-group-justified" role="group">
                            <a href=javascript:inputCalculator(7) class="btn btn-default btn-lg" role="button">7</a>
                            <a href=javascript:inputCalculator(8) class="btn btn-default btn-lg" role="button">8</a>
                            <a href=javascript:inputCalculator(9) class="btn btn-default btn-lg" role="button">9</a>
                        </div>

                        <div class="btn-group btn-group-justified" role="group">
                            <a href=javascript:inputCalculator(4) class="btn btn-default btn-lg" role="button">4</a>
                            <a href=javascript:inputCalculator(5) class="btn btn-default btn-lg" role="button">5</a>
                            <a href=javascript:inputCalculator(6) class="btn btn-default btn-lg" role="button">6</a>
                        </div>
                        <div class="btn-group btn-group-justified" role="group">
                            <a href=javascript:inputCalculator(1) class="btn btn-default btn-lg" role="button">1</a>
                            <a href=javascript:inputCalculator(2) class="btn btn-default btn-lg" role="button">2</a>
                            <a href=javascript:inputCalculator(3) class="btn btn-default btn-lg" role="button">3</a>
                        </div>
                        <div class="btn-group btn-group-justified" role="group">
                            <a href=javascript:inputCalculator(0) class="btn btn-default btn-lg" role="button">0</a>
                            <a href=javascript:inputCalculator(".") class="btn btn-default btn-lg" role="button">.</a>
                            <a href=javascript:inputCalculator("C") class="btn btn-danger btn-lg" role="button">C</a>
                        </div>
                    </div>

                </fieldset>
<!--            </form>-->
            <?= form_close() ?>


        </div>
    </div>
</div>
