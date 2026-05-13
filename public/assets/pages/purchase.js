$(document).mouseup(function (e) {
    var container = $('#productList');
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
        $('#searchProduct').val('');
    }
});
$('#searchProduct').on("keyup", function () {
    var value = $('#searchProduct').val();
    if (value == '') {
        $('#productList').hide();
        $('#productList').html('');
    }
    $.ajax({
        url: "/product/search",
        type: 'GET',
        data: {
            search: value
        },
        dataType: 'json',
        success: function (response) {
            if (response.data.products.length) {
                $('#productList').show()
                let html = '';
                $.each(response.data.products, function (index, item) {
                    html += `<div class='product-item p-2' onclick='selecteItem("${item.id}")'>${item.name}</div>`
                });
                $('#productList').html(html)

            }
        }
    });
});
// function selecteItem(id) {
//     $('#productList').hide()
//     $.ajax({
//         url: "/product/details",
//         type: 'GET',
//         data: {
//             id: id
//         },
//         dataType: 'json',
//         success: function (response) {
//             const product = response.data.product;
//             if (product) {
//                 var purchaseProduct = $(`#productPurchaseRow_${product.id}`);
//                 if (purchaseProduct.length) {
//                     var qty = Number($(`#productQty_${product.id}`).val());
//                     $(`#productQty_${product.id}`).val(qty + 1)
//                     countQty()
//                     calculateTotal();
//                 } else {
//                     var name = product.name;
//                     if (name.length > 16) {
//                         var name = name.substr(0, 16) + ' ...'
//                     }
//                     let batch = ` <td>
//                                     <input type="text" class="form-control" name="products[${product.id}][batch]" disabled>
//                                 </td>
//                                 <td>
//                                     <input type="text" class="form-control" name="products[${product.id}][expire_date]" disabled>
//                                 </td>`;
//                     if (product.batch) {
//                         batch = ` <td>
//                                         <input type="text" class="form-control" name="products[${product.id}][batch]">
//                                     </td>
//                                     <td>
//                                         <input type="date" class="form-control" name="products[${product.id}][expire_date]">
//                                     </td>`;
//                     }
//                     $('#searchProduct').val('');
//                     $('#purchaseProduct').append(`<tr class="productPurchaseRow" id="productPurchaseRow_${product.id}" data-id="${product.id}">
//                         <input type="hidden" name="products[${product.id}][id]" value="${product.id}">
//                         <td>${name}</td>
//                         <td>${product.code}</td>
//                         <td>
//                             <input type="number" class="form-control qty" name="products[${product.id}][qty]"  id="productQty_${product.id}" onchange="countQty()" value="1">
//                         </td>
//                        ${batch}
//                         <td class="net_unit_cost">${product.cost}</td>
//                         <input type="hidden" name="products[${product.id}][netUnitCost]" value="${product.cost}">
//                         <td class="tax">${product.cost_tax}</td>
//                         <input type="hidden" name="products[${product.id}][tax]" value="${product.cost_tax}">

//                         <td class="sub-total">${product.costSubtotal}</td>
//                         <input type="hidden" name="products[${product.id}][subTotal]" class="subTotal" value="">
//                         <td class="d-flex">
//                             <button style="font-size:20px" type="button" class="btn text-danger" onclick='deleteRow("${product.id}")'><i class="fa fa-times"></i></button>
//                         </td>
//                     </tr>`);
//                     countQty();
//                     calculateTotal();
//                 }
//             }

//         }
//     });
// }
function deleteRow(id) {
    $('#productPurchaseRow_' + id).remove();
    countQty();
}
countQty = function () {
    let totalElement = document.getElementsByClassName('productPurchaseRow')
    var totalQty = 0
    for (var i = 0; i < totalElement.length; i++) {
        var totalQty = (Number(totalQty) + Number(totalElement[i].getElementsByClassName('qty')[0].value));
        var netPriceNumber = totalElement[i].getElementsByClassName('net_unit_cost')[0].innerHTML;
        var netPrice = parseFloat(netPriceNumber.replace('$', '').trim());

        var qty = parseInt(totalElement[i].getElementsByClassName('qty')[0].value);

        var taxNumber = totalElement[i].getElementsByClassName('tax')[0].innerHTML;
        var tax = parseFloat(taxNumber.replace('$', '').trim());
        var sub_total = qty * (Number(netPrice) + Number(tax));

        totalElement[i].getElementsByClassName('sub-total')[0].innerText = sub_total.toFixed(2);
        totalElement[i].getElementsByClassName('subTotal')[0].value = sub_total.toFixed(2);
    }
    $('#totalProduct').html(totalElement.length);
    $('input[name="item"]').val(totalElement.length);
    $('#totalQtyProduct').html('(' + totalQty + ')');
    $('input[name="total_qty"]').val(totalQty);
    calculateTotal();
}
function calculateTotal() {
    //Sum of quantity
    var total_qty = 0;
    $(".qty").each(function () {

        if ($(this).val() == '') {
            total_qty += 0;
        } else {
            total_qty += parseFloat($(this).val());
        }
    });
    $("#total-qty").text(total_qty);
    $('input[name="total_qty"]').val(total_qty);
    //Sum of tax
    var total_tax = 0;
    $(".tax").each(function () {
        total_tax += parseFloat($(this).text().replace(/[^0-9.-]/g, ''));
    });

    $("#total-tax").text(total_tax.toFixed(2));
    $('input[name="total_tax"]').val(total_tax.toFixed(2));
    //Sum of subtotal
    var total = 0;
    $(".sub-total").each(function () {
        total += parseFloat($(this).text());
    });
    $("#subTotal").text(total.toFixed(2));
    $('input[name="total_cost"]').val(total.toFixed(2));
    var subtotal = parseFloat($('#subTotal').text());
    var order_tax = parseFloat($('select[name="order_tax_rate"]').val());
    var order_discount = parseFloat($('input[name="order_discount"]').val());
    var shipping_cost = parseFloat($('input[name="shipping_cost"]').val());
    if (!order_tax)
        order_tax = 0.00;
    if (!order_discount)
        order_discount = 0.00;
    if (!shipping_cost)
        shipping_cost = 0.00;
    order_tax = (subtotal - order_discount) * (order_tax / 100);

    var grand_total = Number(subtotal + order_tax + shipping_cost) - order_discount;

    $('#subtotal').text(subtotal.toFixed(2));
    $('#order_tax').text(order_tax.toFixed(2));
    $('input[name="order_tax"]').val(order_tax.toFixed(2));
    $('#order_discount').text(order_discount.toFixed(2));
    $('#shipping_cost').text(shipping_cost.toFixed(2));
    $('#grand_total').text(grand_total.toFixed(2));
    $('input[name="grand_total"]').val(grand_total.toFixed(2));
}
$('input[name="order_discount"]').on("input", function () {
    calculateTotal();
});
$('input[name="shipping_cost"]').on("input", function () {
    calculateTotal();
});
$('select[name="order_tax_rate"]').on("change", function () {
    const id = $('option:selected', this).attr('dataId');
    $('input[name="tax_id"]').val(id);
    calculateTotal();
});
$('select[name="payment_method"]').on('change', function () {
    var value = $(this).val();
    let input = document.getElementById('account-list')
    if (value == 'Bank') {
        input.style.display = 'block';
    } else {
        input.style.display = 'none';
    }
});
