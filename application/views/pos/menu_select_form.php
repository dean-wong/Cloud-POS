<?php
/**
 * Created by IntelliJ IDEA.
 * User: dean
 * Date: 15/9/19
 * Time: 下午3:04
 */
?>

<fieldset>

    <div id="legend" class="">
        <legend class="">台号 <?= $cur_ticket->_table_name ?></legend>
        <span class="help-block">创建时间：<?= $cur_ticket->_create_time ?></span>
    </div>

    <div class="control-group">
        <label class="sr-only"  for="menu_count">点菜</label>
        <input  class="sr-only"  type="text" name="menu_count" id="menu_count" value=""/>

        <input  class="sr-only"  type="text" name="total_price" id="total_price" value="<?= $cur_ticket->_total_price ?>"/>

        <label class="control-label" for="menu-list" id="menu-label"><h4>菜单 <small>总单价：<?= number_format($cur_ticket->_total_price, 2) ?></small></h4></label>
        <div class="controls">
            <div class="list-group" id="menu-list">
                <?php $item_colos = ['list-group-item-success', 'list-group-item-info', 'list-group-item-warning', 'list-group-item-danger']; ?>
                <?php foreach ($cur_ticket->_items as $item) : ?>
                    <div class="list-group-item <?= $item_colos[($item['menu_id']) % 4] ?>" value="<?= $item['menu_id'] ?>">
                        <span class="badge"><?= $item['menu_count'] ?></span>
                        <h4 class="list-group-item-heading"><?= $item['menu_name'] ?></h4>
                        <p class="list-group-item-text">￥<?= number_format($item['total_price'], 2) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <div class="control-group">

        <label class="sr-only control-label">Check In</label>
        <!-- Button -->
        <div class="controls">
            <button class="btn btn-success btn-block" id="order-btn" disabled="disabled">下单</button>
        </div>
    </div>

</fieldset>
