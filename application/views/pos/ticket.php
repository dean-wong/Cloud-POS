<!-- Begin page content -->
<div class="container">
    <div class="row">

        <div class="btn-toolbar">
            <div class="btn-group">
                <a href="#" class="button button-glow button-rounded button-capitalize">
                    <i class="fa fa-print"></i> 打印台单</a>
                <?php if (!$ticket->_paid) : ?>
                    <a href="<?= site_url('/pos/cashier/') . '/' . $ticket->_id ?>"
                       class="button button-glow button-rounded button-highlight">
                        <i class="fa fa-money"></i> 结账</a>
                <?php else: ?>
                    <a href="<?= site_url('/pos/clearance/') . '/' . $ticket->_id ?>"
                       class="button button-glow button-rounded button-primary">
                        <i class="fa fa-glass"></i> 清台</a>
                <?php endif; ?>

            </div>

            <?php if (!$ticket->_paid) : ?>

            <div class="btn-group">
                <a href="<?= site_url('/pos/order/') . '/' . $ticket->_id ?>"
                   class="button button-rounded button-glow button-caution">
                    <i class="fa fa-shopping-cart"></i> 加菜</a>
            </div>

            <!-- Single button -->
            <div class="btn-group">
                <button type="button" class="button button-rounded button-glow button-royal dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-table"></i> 转台<span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach ($tables_closed as $table) : ?>
                        <?php $table_url = site_url('/pos/change_table') . '/' . $ticket->_id . '/' . $table['id']; ?>

                        <li><a href="<?= $table_url ?>"><?= $table['name'] ?></a></li>
                    <?php endforeach; ?>
                    <!-- <li role="separator" class="divider"></li>-->
                </ul>
            </div>
            <?php endif; ?>

        </div>


        <div class="span12">

            <h3><strong><?= $ticket->_table_name ?></strong> 台单</h3>

            <p>
                <strong>开台时间：</strong><?= $ticket->_create_time ?>
            </p>

            <p>
                <strong>顾客人数：</strong><?= $ticket->_number_of_guests ?>
            </p>

            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th> 编号</th>
                    <th> 菜品</th>
                    <th> 价格</th>
                    <th> 数量</th>
                    <th> 金额</th>
                    <th> 状态</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($ticket->_items as $menu) : ?>
                    <?php
                    $is_refunded = !empty($menu['refunded']);   // 退单
                    $is_settled = !empty($menu['settled']);     // 已经解决
                    $status = '正常';
                    $class_type = '';
                    if ($is_settled) {
                        $class_type = 'class="success"';
                        $status = '已上菜';
                    }
                    if ($is_refunded) {
                        $class_type = 'class="danger" style="text-decoration: line-through;"';
                        $status = '退单';
                    }
                    ?>
                    <tr <?= $class_type ?>>
                        <td><?= $menu['id'] ?></td>
                        <td><?= $menu['menu_name'] ?></td>
                        <td><?= number_format($menu['unit_price'], 2) ?></td>
                        <td><?= $menu['menu_count'] ?></td>
                        <td><?= number_format($menu['total_price'], 2) ?></td>
                        <td><?= $status ?></td>
                    </tr>
                <?php endforeach; ?>

                <!--
                <tr class="warning">
                    <td> 2</td>
                    <td> 冻柠乐</td>
                    <td> 6.0</td>
                    <td> 1</td>
                    <td> 6.00</td>
                    <td> 等待过久</td>
                </tr>
                <tr class="danger" style="text-decoration: line-through;">
                    <td> 3</td>
                    <td> 米饭</td>
                    <td> 1.0</td>
                    <td> 3</td>
                    <td> 3.00</td>
                    <td> 退单</td>
                </tr>
                <tr class="info">
                    <td> 4</td>
                    <td> 烤羊肉</td>
                    <td> 18.0</td>
                    <td> 1</td>
                    <td> 18.00</td>
                    <td> 正常</td>
                </tr>
                -->
                <tr class="info">
                    <td>合计</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td> ￥<?= number_format($ticket->_total_price, 2) ?> </td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
