<!-- Begin page content -->
<div class="container">
    <ul class="nav navbar-default nav-tabs">
        <li role="presentation" class="<?= $filter == 'all' ? 'active' : 'inactive' ?>">
            <a href="<?= base_url('index.php/pos/overview/all') ?>">全部餐桌</a>
        </li>
        <li role="presentation" class="<?= $filter == 'opened' ? 'active' : 'inactive' ?>">
            <a href="<?= base_url('index.php/pos/overview/opened') ?>">已开台</a>
        </li>
        <li role="presentation" class="<?= $filter == 'closed' ? 'active' : 'inactive' ?>">
            <a href="<?= base_url('index.php/pos/overview/closed') ?>">未开台</a>
        </li>
    </ul>
    <!-- end of nav-tabs -->

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <?php foreach ($tables as $item): ?>
                    <div class="col-xs-6 col-md-2">
                        <?php
                        $thumb_style = '';
                        $caption = '开台';
                        $table_url = '#';
                        $description = '可容纳 ' . $item['capacity'] . '人';

                        if ($item['ticket'] != null) {
                            $thumb_style = ' style="background: #c1e2b3"';
                            $caption = '￥' . number_format($item['total_price'], 2);//'￥188.00';
                            $table_url = site_url('/pos/ticket') . '/' . $item['ticket'];
                            $description = '开台时间：' . date('H:m:s', strtotime($item['create_time']));
                        }
                        ?>

                        <div class="thumbnail" <?= $thumb_style ?> >
                            <span class="glyphicon glyphicon-th" aria-hidden="true"></span><?= $item['name'] ?>
                            <a href="<?= $table_url ?>"><h3 class="text-center"><?= $caption ?></h3></a>

                            <p><?= $description ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- End of Row -->
        </div>
        <!-- End of Panel Body -->
    </div>
    <!-- End of Panel -->
</div> <!-- End of container-fluid -->
