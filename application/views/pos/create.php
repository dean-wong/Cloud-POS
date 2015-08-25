<div class="container">
    <div class="row">

        <div class="col-xs-4">
            <form>
                <fieldset>
                    <legend>表单项</legend>
                    <label>表签名</label><input type="text"/>
                    <span class="help-block">这里填写帮助信息.</span>
                    <label class="checkbox"><input type="checkbox"/> 勾选同意</label>
                    <button type="submit" class="btn">提交</button>
                </fieldset>
            </form>
        </div>

        <div class="col-xs-8">

            <!-- Tabs -->

                <!--start combinations-->
                <div id="tabs3">
                    <ul>
                        <li><a href="#tabs3-1">First</a></li>
                        <li><a href="#tabs3-2">Second</a></li>
                        <li><a href="#tabs3-3">Third</a></li>
                    </ul>
                    <div id="tabs3-1">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eget diam nec urna hendrerit tempus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum aliquam ligula non nulla cursus volutpat. Aliquam malesuada felis nec turpis auctor interdum. Cras et lobortis dolor. Nam sodales, dolor eu cursus faucibus, justo leo vestibulum turpis, id malesuada erat ipsum et leo. Integer id aliquam augue. Proin quis risus magna.</p>
                        <a href="#" id="sampleButton">Change</a>
                    </div>
                    <div id="tabs3-2">Tab 2</div>
                    <div id="tabs3-3">Tab 3</div>
                </div>
                <!--end combinations-->

                <!-- End tabs -->


            <div id="tabMenuCategory">

            <ul class="nav navbar-default nav-tabs">
<!--                <li class="active"><a href="#">全部</a></li>-->
                <?php foreach ($menu_categories as $category) : ?>
                    <li>
                        <a href="#tabMenuGroup"><?= $category['name'] ?></a>
                    </li>
                <?php endforeach; ?>

                <ul class="nav nav-tabs" id="tabMenuGroup">
                    <li role="presentation" class="active"><a href="#">Home</a></li>
                    <li role="presentation"><a href="#">Profile <span class="badge">42</span></a></li>
                    <li role="presentation"><a href="#">Messages</a></li>
                </ul>
            </ul>
            </div>


            <div class=" panel panel-default">
<!--                <div class="panel-heading">-->
<!--                    <h3 class="panel-title">Panel title</h3>-->
<!--                </div>-->

                <div class="panel-body">

                    <div class="row">
                        <?php for ($i = 0; $i < 10; $i++) : ?>
                            <div class="col-xs-6 col-md-3">
                                <a href="#">
                                    <div class="thumbnail">
                                        <img src="http://docs.ebdoor.com/Image/ProductImage/0/1012/10120688_1.jpg"/>
                                        <h4 class="text-center"> 草鱼
                                            <small>￥78.00</small>
                                        </h4>
                                    </div>
                                </a>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="panel-footer">


                </div>

            </div>
            <nav>
                <ul class="pagination">
                    <li>
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li>
                        <a href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
</div>