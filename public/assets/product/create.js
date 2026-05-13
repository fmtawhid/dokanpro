const feature = $("#feature_barcodes").val();

$(document).mouseup(function (e) {
    var container = $("#productList");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
        $("#searchProduct").val("");
    }
});
$("#searchProduct").on("keyup", function () {
    var value = $("#searchProduct").val();
    if (value == "") {
        $("#productList").hide();
        $("#productList").html("");
    }
    $.ajax({
        url: "/product/search",
        type: "GET",
        data: {
            search: value,
        },
        dataType: "json",
        success: function (response) {
            if (response.data.products.length) {
                $("#productList").show();
                let html = "";
                $.each(response.data.products, function (index, item) {
                    html += `<div class='product-item p-2' onclick='selecteItem("${item.id}")'>${item.name}</div>`;
                });
                $("#productList").html(html);
            }
        },
    });
});
function selecteItem(id) {
    $("#productList").hide();
    $.ajax({
        url: "/product/details",
        type: "GET",
        data: {
            id: id,
        },
        dataType: "json",
        success: function (response) {
            const product = response.data.product;
            if (product) {
                var purchaseProduct = $(`#productPurchaseRow_${product.id}`);
                if (purchaseProduct.length) {
                    var qty = Number($(`#productQty_${product.id}`).val());
                    $(`#productQty_${product.id}`).val(qty + 1);
                    countQty();
                } else {
                    $("#searchProduct").val("");

                    $("#comboProduct")
                        .append(`<tr class="productPurchaseRow" id="productPurchaseRow_${product.id}" data-id="${product.id}" data-total="0">
                                        <input type="hidden" name="product_id[]" value="${product.id}">
                                        <td>${product.name}</td>
                                        <td>${product.code}</td>
                                        <td>
                                            <input type="number" class="form-control qty" name="qty[]" id="productQty_${product.id}" onchange="countQty()" value="1">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control net_unit_cost" onchange="countQty()" name="netUnitCost[]" value="${product.price}"></td>
                                            <td class="sub-total">${product.subtotal}</td>

                                        <td>
                                            <button type="button" class="btn btn-md text-danger" onclick='deleteRow("${product.id}")'><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>`);
                    countQty();
                }
            }
        },
    });
}
function deleteRow(id) {
    $("#productPurchaseRow_" + id).remove();
    countQty();
}
countQty = function () {
    let totalElement = document.getElementsByClassName("productPurchaseRow");
    var totalQty = 0;
    for (var i = 0; i < totalElement.length; i++) {
        var totalQty =
            Number(totalQty) +
            Number(totalElement[i].getElementsByClassName("qty")[0].value);

        var netPrice =
            totalElement[i].getElementsByClassName("net_unit_cost")[0].value;
        var qty = totalElement[i].getElementsByClassName("qty")[0].value;
        var subTotal = qty * netPrice;
        totalElement[i].getElementsByClassName("sub-total")[0].innerText =
            subTotal.toFixed(2);
    }
    $("#totalProduct").html(totalElement.length);
    $("#totalQtyProduct").html("(" + totalQty + ")");
    $("#total-qty").html(totalQty);
    $('input[name="total_qty"]').val(totalQty);
    sumSubtotal();
};
function sumSubtotal() {
    //Sum of subtotal
    var total = 0;
    $(".sub-total").each(function () {
        total += parseFloat($(this).text());
    });
    $('input[name="cost"]').val(total.toFixed(2));
}

$("#serial_imei_number").on("change", function () {
    if ($(this).is(":checked")) {
        $("#batch-option").hide(300);
    } else {
        $("#batch-option").show(300);
    }
});

$("#is-batch").on("change", function () {
    if ($(this).is(":checked")) {
        $("#serial_imei_number_section").hide(300);
    } else {
        $("#serial_imei_number_section").show(300);
    }
});

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$("#genbutton").on("click", function () {
    $.get("/product/gencode", function (data) {
        $("input[name='code']").val(data);
    });
});
$('select[name="type"]').on("change", function () {
    if ($(this).val() == "combo") {
        $("#combo-section").show(300);
        $("#unit-section").hide(300);
        $("#is-variant").prop("checked", false);
        $("#is-diffPrice").prop("checked", false);
        $(
            "#variant-section, #variant-option, #diffPrice-option, #diffPrice-section"
        ).hide(300);
    } else if ($(this).val() == "digital") {
        $("#combo-section").hide(300);
        $("#unit-section").show(300);
        $("#is-variant").prop("checked", false);
        $("#is-diffPrice").prop("checked", false);
        $(
            "#variant-section, #variant-option, #diffPrice-option, #diffPrice-section"
        ).hide(300);
    } else if ($(this).val() == "standard") {
        $("#combo-section").hide(300);
        $("#unit-section").show(300);
        $("#variant-option").show(300);
        $("#diffPrice-option").show(300);
        $("#digital").hide(300);
        $("#combo").hide(300);
    }
});
//Change quantity or unit price
$("#myTable").on("input", ".qty , .unit_price", function () {
    calculate_price();
});
//Delete product
$("table.order-list tbody").on("click", ".ibtnDel", function (event) {
    $(this).closest("tr").remove();
    calculate_price();
});
function hide() {
    $("#cost").hide(300);
    $("#unit").hide(300);
    $("#alert-qty").hide(300);
}
function calculate_price() {
    var price = 0;
    $(".qty").each(function () {
        rowindex = $(this).closest("tr").index();
        quantity = $(this).val();
        unit_price = $(
            "table.order-list tbody tr:nth-child(" +
                (rowindex + 1) +
                ") .unit_price"
        ).val();
        price += quantity * unit_price;
    });
    $('input[name="price"]').val(price|| 0);


}
$("input[name='is_variant']").on("change", function () {
    if ($(this).is(":checked")) {
        $("#variant-section").show(300);
    } else $("#variant-section").hide(300);
});
$("input[name='variant']").on("input", function () {
    if ($("#code").val() == "") {
        $("input[name='variant']").val("");
        alert("Please fillup above information first.");
    } else if ($(this).val().indexOf(",") > -1) {
        var rand = Math.floor(10000000 + Math.random() * 90000000);
        var variant_name = $(this).val().slice(0, -1);
        var item_code = variant_name + "-" + rand;
        var newRow = $("<tr>");
        var cols = "";
        cols +=
            '<td style="cursor:grab"><i class="fa fa-circle" aria-hidden="true"></i></td>';
        cols +=
            '<td><input type="text" class="form-control" name="variant_name[]" value="' +
            variant_name +
            '" /></td>';
        if (feature == 1) {
            cols +=
                '<td><input type="text" class="form-control" name="item_code[]" value="' +
                item_code +
                '" /></td>';
        } else {
            cols +=
                '<input type="hidden" class="form-control" name="item_code[]" value="' +
                item_code +
                '" />';
        }
        cols +=
            '<td><input type="number" class="form-control" name="additional_price[]" value="" step="any" /></td>';
        cols +=
            '<td><button type="button" class="vbtnDel btn btn-sm btn-danger">X</button></td>';

        $("input[name='variant']").val("");
        newRow.append(cols);
        $("table.variant-list tbody").append(newRow);
    }
});
//Delete variant
$("table#variant-table tbody").on("click", ".vbtnDel", function (event) {
    $(this).closest("tr").remove();
});
$("#promotion").on("change", function () {
    if ($(this).is(":checked")) {
        $("#starting_date").val(
            $.datepicker.formatDate("dd-mm-yy", new Date())
        );
        $("#promotion_price").show(300);
        $("#start_date").show(300);
        $("#last_date").show(300);
    } else {
        $("#promotion_price").hide(300);
        $("#start_date").hide(300);
        $("#last_date").hide(300);
    }
});
