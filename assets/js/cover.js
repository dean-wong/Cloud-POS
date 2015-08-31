/**
 * Created by dean on 15/8/25.
 */
$(function () {
    // Tabs
    $('#tabMenuCategory, #tabMenuCategory-1, #tabMenuCategory-2,#tabMenuCategory-3, #tabMenuCategory-4, ' +
    '#tabMenuCategory-5, #tabMenuCategory-6, #tabMenuCategory-7, #tabMenuCategory-8, #tabMenuCategory-9,' +
    '#tabMenuGroup-1,#tabMenuGroup-2,#tabMenuGroup-3,#tabMenuGroup-4,#tabMenuGroup-5,#tabMenuGroup-6,' +
    '#tabMenuGroup-7,#tabMenuGroup-8,#tabMenuGroup-9,#tabMenuGroup-10,#tabMenuGroup-11,#tabMenuGroup-12,' +
    '#discount-tabs').tabs();

    // customize all inputs (will search for checkboxes and radio buttons)
    /*$('input').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_minimal',
        increaseArea: '20%'
    });*/

    // Buttonset
    //$('#discount-group').buttonset();
    $('#payment-group').buttonset();


});


function addMenu(menu_id, menu_name, menu_price) {

    /*
     * 更新界面元素
     */
    // 在菜单列表中添加一项
    var menu_list = $("#menu-list");
    var item_colos = ['list-group-item-success', 'list-group-item-info', 'list-group-item-warning', 'list-group-item-danger'];
    var count = Number($("#menu_count").val()) + 1;

    var text = '<div class="list-group-item ' + item_colos[menu_id % 4] + '" value="' + menu_id + '">' +
        '<span class="badge">1</span>' +
        '<h4 class="list-group-item-heading">' + menu_name + '</h4>' +
        '<p class="list-group-item-text">￥' + menu_price + '</p>' +
        '</div>';

    menu_list.prepend(text);

    // 动态计算总价
    var price = Number($("#total_price").val()) + Number(menu_price);
    var discount = Number($("#discount").val());
    // <h4>菜单 <small>总金额：0.00 优惠金额：0.00</small></h4>
    text = '<h4>菜单 <small>总单价：<strong>' + price.toFixed(2) + '</strong> 优惠金额：' + discount.toFixed(2) + '</small></h4>';
    $("#menu-label").html(text);

    // 保存当前总价格，便于下次计算
    $("#total_price").val(price);


    /*
     * 保存提交表单的数据
     */
    // 保存当前的点菜数量记数
    $("#menu_count").val(count);
    // 保存Menu ID到变量数组当中，给表单提交之用
    text = '<input class="sr-only" type="input" name="menu_items[]" value="' + menu_id + '"/>';
    $("#menu_count").after(text);

    // <button class="btn btn-success btn-block" id="order-btn" disabled>下单</button>
    $("#order-btn").removeAttr("disabled");


}

