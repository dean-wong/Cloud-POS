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
    //$('#payment-group').buttonset();


});

function setPayment(the_button, payment_id)
{
    //表示当前的付款方式
    $("#current-payment-id").val(payment_id);

    // 更新界面的激活状态
    var payment_group = $("#payment-group");
    payment_group.find("button").attr("class","list-group-item");
    $(the_button).attr("class","list-group-item active");

    // 改变金额输入中的数据
    $("#input-cash").val($(the_button).val());

}

function calPaidamount()
{
    //
    var payment_group = $("#payment-group");
    var find = payment_group.find("button");
    var amount = 0.0;
    find.each(function(){
        amount += Number($(this).val());
    });

    $("#input-paid-amount").val(amount.toFixed(2));
    var actualPaid = Number($("#input-final-amount").val());
    var change = Number(amount - actualPaid);
    if (change >= 0) {
        $("#btn-cashier").removeAttr("disabled");
        $("#input-change").val(change.toFixed(2));
    }
    else {
        $("#btn-cashier").attr("disabled", "disabled");
        $("#input-change").val("0.00");
    }
}

function inputCalculator(num)
{
    // 更新数字输入框
    // num = 0,1,2 ... 8,9, '.'
    var ctrl_inputNum = $("#input-cash");
    if (num == "C") {
        ctrl_inputNum.val('');
    }
    else if (num == "B"){
        var str = String(ctrl_inputNum.val());
        var subs = str.substr(0, str.length-1);
        ctrl_inputNum.val(subs.toString());
    }else {
        var text = ctrl_inputNum.val() + num;
        ctrl_inputNum.val(text);
    }

    // 更新当前选择的支付方式的金额
    var current_payment_btnid = "#radio_payment-" + $("#current-payment-id").val();
    var current_payment_inputid = "#input_payment-" + $("#current-payment-id").val();
    var strBtn = String($(current_payment_btnid).html());
    var pos = strBtn.indexOf("</span>");  // <span class="badge">0</span>
    var newStr = '<span class="badge"> ￥' + Number(ctrl_inputNum.val()).toFixed(2) + strBtn.substr(pos, strBtn.length);
    //alert(newStr);
    $(current_payment_btnid).html(newStr);
    $(current_payment_btnid).val(ctrl_inputNum.val());
    $(current_payment_inputid).val(ctrl_inputNum.val()); // Button保存不了数据，用一个不可见的Input保存数据

    // 统计支付总额
    calPaidamount();
}

function addMenu(menu_id, menu_name, menu_price)
{

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
    //var discount = Number($("#discount").val());
    // <h4>菜单 <small>总金额：0.00 优惠金额：0.00</small></h4>
    text = '<h4>菜单 <small>总单价：<strong>' + price.toFixed(2) + '</strong></small></h4>';
    $("#menu-label").html(text);

    // 保存当前总价格，便于下次计算
    $("#total_price").val(price);


    /*
     * 保存提交表单的数据
     */
    // 保存当前的点菜数量记数
    $("#menu_count").val(count);
    // 保存Menu ID到变量数组当中，给表单提交之用
    text = '<input class="sr-only" type="text" name="menu_items[]" value="' + menu_id + '"/>';
    $("#menu_count").after(text);

    // <button class="btn btn-success btn-block" id="order-btn" disabled>下单</button>
    $("#order-btn").removeAttr("disabled");


}

