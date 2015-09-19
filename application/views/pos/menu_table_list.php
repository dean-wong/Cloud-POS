<?php
/**
 * Created by IntelliJ IDEA.
 * User: dean
 * Date: 15/9/19
 * Time: 下午3:08
 */
?>

<div id="tabMenuCategory">
    <ul class="nav navbar-default">
        <?php $id_categories = array(); ?>
        <?php foreach ($menu_categories as $category) : ?>
            <li><a href="#tabMenuCategory-<?= $category['id'] ?>"><?= $category['name'] ?></a></li>
            <?php $id_categories[] = $category['id']; ?>
        <?php endforeach; ?>
    </ul>
    <?php foreach ($id_categories as $id_category) : ?>
        <div id="tabMenuCategory-<?= $id_category ?>">
            <ul class="nav nav-tabs">
                <?php $id_groups = array(); ?>
                <?php foreach ($menu_categories as $category) : ?>
                    <?php if ($category['id'] == $id_category) : ?>
                        <?php foreach ($category['menu_groups'] as $menuGroup) : ?>
                            <li>
                                <a href="#tabMenuGroup-<?= $menuGroup['id'] ?>"><?= $menuGroup['name'] ?></a>
                            </li>
                            <?php $id_groups[] = $menuGroup['id']; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <div class="panel panel-default">
                <div class="panel-body">
                    <?php foreach ($id_groups as $id_group) : ?>
                        <div id="tabMenuGroup-<?= $id_group ?>">
                            <div class="row">
                                <?php foreach ($menu_categories as $category) : ?>
                                    <?php foreach ($category['menu_groups'] as $menuGroup) : ?>
                                        <?php if ($menuGroup['id'] == $id_group) : ?>
                                            <?php foreach ($menuGroup['menus'] as $menu) : ?>

                                                <div class="col-xs-6 col-md-3">
                                                    <a href=javascript:addMenu('<?= $menu['id'] ?>','<?= $menu['name'] ?>','<?= number_format($menu['price'], 2) ?>')>
                                                        <!--                                                                <a href=javascript:addMenu('1','2','3')>-->
                                                        <div class="thumbnail">
                                                            <img
                                                                src="<?= base_url('/uploads/default.png') ?>"/>
                                                            <h4 class="text-center"><?= $menu['name'] ?>
                                                                <small>
                                                                    ￥<?= number_format($menu['price'], 2) ?></small>
                                                            </h4>
                                                        </div>
                                                    </a>
                                                </div>

                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    <?php endforeach; ?>
</div>
