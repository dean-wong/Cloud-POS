<!-- Begin page content -->
<div class="container">
    <div class="row">

        <div class="btn-toolbar">
            <div class="btn-group">
                <a href="#" class="button button-rounded button-glow button-caution">
                    <i class="fa fa-shopping-cart"></i> 加菜</a>
            </div>
            <!--            <a href="#ChangeTableModal" class="button button-rounded button-glow button-royal"-->
            <!--               data-toggle="modal" data-whatever="@fat">-->
            <!--                <i class="fa fa-table"></i> 转台</a>-->
            <!-- Single button -->
            <div class="btn-group">
                <button type="button" class="button button-rounded button-glow button-royal dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-table"></i> 转台<span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
<!--                    <li role="separator" class="divider"></li>-->

                    <?php foreach($tables_closed as $table) : ?>
                        <?php $table_url = site_url('/pos/change_table') . '/' . $ticket['id'] . '/' . $table['id']; ?>

                        <li><a href="<?= $table_url ?>"><?= $table['name'] ?></a></li>
                    <?php endforeach; ?>
<!--                    <li role="separator" class="divider"></li>-->
                </ul>
            </div>
            <div class="btn-group">

                <a href="#" class="button button-glow button-rounded button-highlight">
                    <i class="fa fa-money"></i> 结账</a>
            </div>
        </div>


        <div class="span12">

            <h3><strong><?= $ticket['table_name'] ?></strong> 台单</h3>

            <p>
                <strong>开台时间：</strong><?= $ticket['create_time'] ?>
            </p>

            <p>
                <strong>顾客人数：</strong><?= $ticket['number_of_guests'] ?>
            </p>

            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th> 编号</th>
                    <th> 菜品</th>
                    <th> 价格</th>
                    <th> 数量</th>
                    <th> 单位</th>
                    <th> 金额</th>
                    <th> 状态</th>
                </tr>
                </thead>

                <tbody>
                <tr class="success">
                    <td> 1</td>
                    <td> 酱汁萝卜皮</td>
                    <td> 8.0</td>
                    <td> 2</td>
                    <td> 份</td>
                    <td> 16.00</td>
                    <td> 已上</td>
                </tr>
                <tr class="warning">
                    <td> 2</td>
                    <td> 冻柠乐</td>
                    <td> 6.0</td>
                    <td> 1</td>
                    <td> 份</td>
                    <td> 6.00</td>
                    <td> 等待过久</td>
                </tr>
                <tr class="danger" style="text-decoration: line-through;">
                    <td> 3</td>
                    <td> 米饭</td>
                    <td> 1.0</td>
                    <td> 3</td>
                    <td> 份</td>
                    <td> 3.00</td>
                    <td> 退单</td>
                </tr>
                <tr class="info">
                    <td> 4</td>
                    <td> 烤羊肉</td>
                    <td> 18.0</td>
                    <td> 1</td>
                    <td> 份</td>
                    <td> 18.00</td>
                    <td> 正常</td>
                </tr>
                <tr>
                    <td>合计</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td> ￥<?= number_format($ticket['total_price'], 2) ?> </td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
