$(document).ready(function() {
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
    });
    $(".select2").select2();
    $(".fullpage-version").click(function(e) {
        e.preventDefault();
        $("body").toggleClass("fullpage");
        $(".fullpage-version span").toggleClass("fa-chevron-right");
    });
    $("form").submit(function() {
        $(".loading").addClass("show");
    });
    $(".nav-link[data-toggle='collapse']").click(function() {
        if ($(this).hasClass("collapsed")) {
            $(".nav-link[data-toggle='collapse']").addClass("collapsed");
            $(".nav-link[data-toggle='collapse']").attr("aria-expanded", false);

            $(this).removeClass("collapsed");
            $(this).attr("aria-expanded", true);

            var target = $(this).data("target");
            $(".nav-collapse.collapse").removeClass("show");
            $(".nav-collapse.collapse" + target).addClass("collapsing");
        }
        /*         $(".nav-collapse").removeClass('show')
        var target = $(this).data('target')
        $(".nav-collapse"+target).toggleClass('show')
 */
    });
    function formatRupiah(angka) {
        var number_string = angka.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }
        return rupiah;
    }

    // $('#tax').val(parseInt($("#total").val()) * 10 /100);

    $(".diskon_tambahan").keyup(function() {
        var diskon_tambahan = parseInt($(this).val());
        var tipe = $(this).data('tipe')
        var tipe_pemesanan = $(this).data('tipe_pemesanan')
        var total = parseInt($("#total").val());
        var diskon = 0;
        var tax = 0;
        if (tipe_pemesanan =='langsung') {
            tax = (total - diskon_tambahan) * 10 / 100;
        }
        if(tipe=='persen'){
            var otherDisc = parseInt($(".diskon_tambahan[data-tipe='rp']").val())
            diskon = (diskon_tambahan * total / 100) + otherDisc;
        }
        else{
            var getOtherDisc = parseInt($(".diskon_tambahan[data-tipe='persen']").val())
            var otherDisc = 0;
            if(getOtherDisc>0){
                otherDisc = getOtherDisc * total / 100;
            }
            diskon = diskon_tambahan + otherDisc
        }
        var grand_total = total - diskon_tambahan + tax;
        $("#tax").val(tax);
            $("#grand_total").val(grand_total);
            $("#subtotal").val(grand_total);
            $("#idrGrandTotal").html(formatRupiah(grand_total));
        if($(this).hasClass('dp')){
        }
        else{
/*             $("#grand_total").val(grand_total + (grand_total * 10 / 100));
            $("#total").val(grand_total + (grand_total * 10 / 100));
            $("#idrGrandTotal").html(formatRupiah(grand_total + (grand_total * 10 / 100)));
 */        }
    });

    $("#bayar").keyup(function() {
        var terbayar = parseInt($(this).val());
        var grand_total = parseInt($("#grand_total").val());
        var kembalian = terbayar - grand_total;
        $("#kembalian").val(kembalian);
    });

    // $('#total').keyup(function () { 
    //     let total = parseInt($(this).val());
    //     let diskon = parseInt($('.diskon_tambahan').val());
    //     let ppn = (total - diskon) * 10 /100;
    //     var grand_total = total - diskon + ppn;
    //     $("#tax").val(ppn);
    //     $("#grand_total").val(grand_total);
    //     $("#subtotal").val(grand_total);
    //     // $("#total").val(grand_total);
    //     $("#idrGrandTotal").html(formatRupiah(grand_total));
    // });

    $('.getGrandTotal').keyup(function (e) { 
        var ppn = parseInt($("#tax").val());
        var total = parseInt($("#total").val())
        var diskon = parseInt($('#diskon_tambahan').val());
        var charge = parseInt($('#charge').val());

        var grandTotal = ppn + total - diskon + charge

        $("#grand_total").val(grandTotal);
        $("#subtotal").val(grandTotal);
        // $("#total").val(grandTotal);
        $("#idrGrandTotal").html(formatRupiah(grandTotal));
    });

    // $("#no_kartu").prop("disabled", true);
//    $("#charge").prop("disabled", true);

    temp_grand_total = parseInt($("#grand_total").val());
    
    function getCharge(thisVal) {
        var ppn = parseInt($("#tax").val());
        var total = parseInt($("#total").val())
        var diskon = parseInt($('#diskon_tambahan').val());

        var grand_total = ppn + total - diskon;
        var charge = 0;
        if (thisVal != "Tunai") {
            $("#no_kartu").prop("disabled", false);
            $("#no_kartu").attr("required", true);

/*             $("#charge").prop("disabled", false);
            $("#charge").attr("required", true);
 */        } else {
            $("#no_kartu").prop("disabled", true);
/*             $("#charge").prop("disabled", true);
            $("#charge").val(0);
 */        }

        if (thisVal == "Debit BCA") {
            charge = 0;
        } else if (thisVal == "Debit BRI") {
            charge = (grand_total * 0.15) / 100;
        } else if (thisVal == "Kredit BCA") {
            charge = (grand_total * 2) / 100;
        } else if (thisVal == "Kredit BRI") {
            charge = (grand_total * 1.5) / 100;
        } else if (thisVal == "Debit Bank Lain") {
            charge = (grand_total * 0.15) / 100;
        } else if (thisVal == "Kredit Bank Lain") {
            charge = (grand_total * 1.5) / 100;
        }
        $("#charge").val(Math.round(charge));
        $("#grand_total").val(grand_total + Math.round(charge));
        $("#idrGrandTotal").html(
            formatRupiah(grand_total + Math.round(charge))
        );
    }

        // $("#charge").keyup(function(){
        //     var change = parseInt($(this).val())
        //     var subtotal = parseInt($("#subtotal").val())
        //     var newGrandTotal = subtotal + change;
        //     $("#grand_total").val(newGrandTotal);
        //     $("#idrGrandTotal").html(
        //         formatRupiah(newGrandTotal)
        //     );

        // })

    $("#jenis_bayar").change(function() {
        var thisVal = $(this).val();
        getCharge(thisVal);
        if ($(this).val() != "Tunai") {
            $('#no_kartu').attr({'disabled': false, 'required' : true});
        }
        else{
            $('#no_kartu').attr({'disabled': true, 'required' : false});
        }
    });

    function addFoto(thisParam) {
        var biggestNo = 0; //setting awal No/Id terbesar
        $(".row-detail").each(function() {
            var currentNo = parseInt($(this).attr("data-no"));
            if (currentNo > biggestNo) {
                biggestNo = currentNo;
            }
        }); //mencari No teresar

        var next = parseInt(biggestNo) + 1; // No selanjutnya ketika ditambah field baru
        var thisNo = thisParam.data("no"); // No pada a href
        var url = $("#urlAddFoto").data("url");
        $.ajax({
            type: "get",
            url: url,
            data: { biggestNo: biggestNo },
            beforeSend: function() {
                $(".loading").addClass("show");
            },
            success: function(response) {
                $(".loading").removeClass("show");
                $(".row-detail[data-no='" + thisNo + "']").after(response);

                $(".addFoto[data-no='" + next + "']").click(function(e) {
                    e.preventDefault();
                    addFoto($(this));
                });

                $(".deleteFoto").click(function(e) {
                    e.preventDefault();
                    deleteFoto($(this));
                });
            }
        });
    }
    $(".addFoto").click(function(e) {
        e.preventDefault();
        addFoto($(this));
    });
    function deleteFoto(thisParam) {
        var delNo = thisParam.data("no");
        var parent = ".row-detail[data-no='" + delNo + "']";
        var idFoto = $(parent + " .idFoto").val();
        if (thisParam.hasClass("addDeleteId") && idFoto != 0) {
            $(".idDelete").append(
                "<input type='hidden' name='id_delete[]' value='" +
                    idFoto +
                    "'>"
            );
        }
        $(parent).remove();
        getTotal();
        getTotalQty(thisParam);
    }
    $(".deleteFoto").click(function(e) {
        e.preventDefault();
        deleteFoto($(this));
    });

    $('#tgl_checkout').change(function (e) {  
        $('#id_kamar')
        .find('option')
        .remove()
        .end()
        .append('<option value="">Pilih Kamar</option>');

        var url = $('#id_kamar').data("url");
        var tgl_checkin = $('#tgl_checkin').val();
        var tgl_checkout = $(this).val();
        $.ajax({
            type: "get",
            url: url,
            data: { tgl_checkin: tgl_checkin, tgl_checkout: tgl_checkout },
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $.each(data, function(i, item) {
                    // alert(item.id);
                    $('#id_kamar').append(
                        "<option value='" +
                        item.id +
                            "'> " + item.no_kamar +" </option>"
                    );
                });
            }
        });
    });
});
