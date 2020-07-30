$(document).ready(function() {
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd"
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

    $(".diskon_tambahan").keyup(function() {
        var diskon_tambahan = parseInt($(this).val());
        var tipe = $(this).data('tipe')
        var total = parseInt($("#total").val());
        var diskon = 0;
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
        var grand_total = total - diskon;
        $("#grand_total").val(grand_total);
        $("#idrGrandTotal").html(formatRupiah(grand_total));
    });

    $("#bayar").keyup(function() {
        var terbayar = parseInt($(this).val());
        var grand_total = parseInt($("#grand_total").val());
        var kembalian = terbayar - grand_total;
        $("#kembalian").val(kembalian);
    });

    $("#no_kartu").prop('disabled',true);
    // $("#charge").prop('disabled',true);

    temp_grand_total = parseInt($('#grand_total').val());
    function getCharge(thisVal) {
        var grand_total = temp_grand_total;
        var charge = 0;
        if (thisVal != 'Tunai') {
            $("#no_kartu").prop('disabled',false);
            $("#no_kartu").attr("required", true);

            $("#charge").prop('disabled',false);
            $("#charge").attr("required", true);
        }
        else {
            $("#no_kartu").prop('disabled',true);
            $("#charge").prop('disabled',true);
            $('#charge').val(0);
        }

        if (thisVal == "Debit BCA") {
            charge = grand_total * 1 / 100; 
        }
        else if(thisVal == 'Debit BRI'){
            charge = grand_total * 0.15 / 100; 
        }
        else if(thisVal == 'Kredit BCA'){
            charge = grand_total * 1.80 / 100; 
        }
        else if(thisVal == 'Kredit BRI'){
            charge = grand_total * 1.50 / 100; 
        }
        $('#charge').val(Math.round(charge));
        $('#grand_total').val(grand_total + Math.round(charge));
        $('#idrGrandTotal').html(formatRupiah(grand_total + Math.round(charge)));
    }

    $("#jenis_bayar").change(function() {
        var thisVal = $(this).val();
        getCharge(thisVal);
    });
});
