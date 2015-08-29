/**
 * Created by dean on 15/8/25.
 */
$(function () {
    // Tabs
    $('#tabMenuCategory, #tabMenuCategory-1, #tabMenuCategory-2,#tabMenuCategory-3, #tabMenuCategory-4, ' +
    '#tabMenuCategory-5, #tabMenuCategory-6, #tabMenuCategory-7, #tabMenuCategory-8, #tabMenuCategory-9,' +
    '#tabMenuGroup-1,#tabMenuGroup-2,#tabMenuGroup-3,#tabMenuGroup-4,#tabMenuGroup-5,#tabMenuGroup-6,' +
    '#tabMenuGroup-7,#tabMenuGroup-8,#tabMenuGroup-9,#tabMenuGroup-10,#tabMenuGroup-11,#tabMenuGroup-12').tabs();
});


function addMenu(menu_id, menu_name, menu_price) {
    var menu_list = $("#menu-list");
    var item_colos = ['list-group-item-success', 'list-group-item-info', 'list-group-item-warning', 'list-group-item-danger'];
    var count = Number(menu_list.val()) + 1;

    var text = '<div class="list-group-item ' + item_colos[menu_id % 4] + '" value="' + menu_id + '">' +
        '<span class="badge">1</span>' +
        '<h4 class="list-group-item-heading">' + menu_name + '</h4>' +
        '<p class="list-group-item-text">￥' + menu_price + '</p>' +
        '</div>';

    menu_list.append(text);
    //alert(menu_list.val())

    text = '<input class="sr-only" type="input" name="menu_items[]" value="' + menu_id + '"/>';
    $("#menu_count").after(text);
    //
    $("#menu_count").val(count);

}

