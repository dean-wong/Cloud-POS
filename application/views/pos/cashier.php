<!-- Begin page content -->
<div class="container">
    <div class="row">
        <div class="col-xs-4">
            <h2> <?= $ticket->_table_name ?> 台单 </h2>
            <p> 开台时间：<?= $ticket->_create_time ?> </p>
            <p> 总单价：￥<?= number_format($ticket->_total_price, 2) ?> </p>
            <div class="controls">
                <div class="list-group" name="menu-list" id="menu-list">
                    <?php $item_count = 0;
                    $item_colos = ['list-group-item-success', 'list-group-item-info', 'list-group-item-warning', 'list-group-item-danger']; ?>
                    <?php foreach ($ticket->_items as $item) : ?>
                        <?php if (++$item_count < 9) : ?>
                            <div class="list-group-item <?= $item_colos[($item['menu_id']) % 4] ?>"
                                 value="<?= $item['menu_id'] ?>">
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
            <legend>优惠方案</legend>
            <div class="container-fluid" id="discount-solution">
                <label class="checkbox" for="check1">
                    <input type="checkbox" value="" id="check1">折扣券
                </label>

                <select class="form-control" id="discount-group">
                    <?php $radioDiscounts = [
                        ['id' => 'discount_no', 'value' => 100, 'label' => '全价'],
                        ['id' => 'discount_90', 'value' => 90, 'label' => '九折'],
                        ['id' => 'discount_80', 'value' => 80, 'label' => '八折'],
                        ['id' => 'discount_70', 'value' => 70, 'label' => '七折'],
                    ];
                    foreach ($radioDiscounts as $item): ?>
                        <!--                        <label class="radio">
                            <input type="radio" name="optionsRadios" id="<? /*= $item['id'] */ ?>"
                                   value="<? /*= $item['value'] */ ?>"> <? /*= $item['label'] */ ?> </label>-->
                        <option><?= $item['label'] ?> </option>
                    <?php endforeach; ?>
                </select>

                <label class="checkbox" for="check2">
                    <input type="checkbox" value="" id="check2">抹零
                </label>

                <label class="checkbox" for="check3">
                    <input type="checkbox" value="" id="check3">手动输入
                    </label>
                <input type="text" />
            </div>

            <hr></hr>
            <legend>付款方式</legend>
            <div class="container-fluid">
                <div class="btn-group" id="payment-group">
                    <?php $btn_icons = ['fa fa-internet-explorer', 'fa fa-money', 'fa fa-credit-card', 'fa fa-group', 'fa fa-magic', 'fa fa-bank']; ?>
                    <?php foreach ($payments as $item) : ?>

                        <input class="form-control btn-block" type="radio" id="radio_payment-<?= $item->id ?>"
                               name="radio_payment"
                            <?= ($item->id == 1) ? 'checked="checked"' : '' ?>/>
                        <label class="btn-block" for="radio_payment-<?= $item->id ?>">
                            <i class="<?= $btn_icons[$item->id % 6] ?>"></i> <?= $item->name ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-xs-4">
            <button class="btn btn-block btn-highlight btn-lg" id="btn-cashier" disabled="disabled">
                <i class="fa fa-check"></i> 结账
            </button>
            <form class="form-horizontal">
                <fieldset>
                    </br>
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
                                    <input type="number" class="form-control" id="<?= $ctrl_item['id'] ?>"
                                           placeholder="<?= $ctrl_item['value'] ?>"
                                           value="<?= $ctrl_item['value'] ?>" readonly>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>


                    <!--<div class="form-group calculator">
                        <label class="sr-only" for="input-cash">Cash</label>
                        <input type="input" class="form-control text-right" id="input-cash" placeholder="0.00"/>
                    </div>-->

                    <div class="form-group">

                        <label class="sr-only" for="input-cash">Cash</label>

                        <div class="input-group">
                            <span class="input-group-addon">￥</span>
                            <input type="number" class="form-control text-right" id="input-cash"
                                   aria-label="Amount (to the nearest dollar)">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i></button>
                        </span>
                        </div>
                        <div class="btn-group btn-group-justified" role="group">
                            <a href="#" class="btn btn-default btn-lg" role="button">7</a>
                            <a href="#" class="btn btn-default btn-lg" role="button">8</a>
                            <a href="#" class="btn btn-default btn-lg" role="button">9</a>
                        </div>

                        <div class="btn-group btn-group-justified" role="group">
                            <a href="#" class="btn btn-default btn-lg" role="button">4</a>
                            <a href="#" class="btn btn-default btn-lg" role="button">5</a>
                            <a href="#" class="btn btn-default btn-lg" role="button">6</a>
                        </div>
                        <div class="btn-group btn-group-justified" role="group">
                            <a href="#" class="btn btn-default btn-lg" role="button">1</a>
                            <a href="#" class="btn btn-default btn-lg" role="button">2</a>
                            <a href="#" class="btn btn-default btn-lg" role="button">3</a>
                        </div>
                        <div class="btn-group btn-group-justified" role="group">
                            <a href="#" class="btn btn-default btn-lg" role="button">0</a>
                            <a href="#" class="btn btn-default btn-lg" role="button">.</a>
                            <a href="#" class="btn btn-danger btn-lg" role="button">C</a>
                        </div>
                    </div>

                </fieldset>
            </form>

        </div>
    </div>
</div>
