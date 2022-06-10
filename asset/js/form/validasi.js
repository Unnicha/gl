function panggil() {
    console.log('kepanggil')
}

function validProduk() {
    if ($('.produk-row').data('id') == undefined) {
        var msg = '<small class="error-msg text-danger">Produk harus diisi</small>';
        $('#table-produk').after(msg).addClass('has-error');
        return false;
    }
    return true;
}

function validInput() {
    var valid = true;
    $('#myForm .form-control').each(function() {
        var field	= $(this);
        var value	= field.val().trim();
        var target	= field.data('target');
        var min		= field.attr('min');
        var max		= field.attr('max');
        var min_len	= field.attr('min-length');
        var max_len	= field.attr('max-length');
        
        // required
        if (field.attr('required') == 'required' && value.length == 0) {
            var msg = '<small class="error-msg text-danger">Belum diisi.</small>';
            $(target).addClass('text-danger').html(msg);
            field.addClass('has-error');
            valid = false;
        }
        else if (value.length != 0) {
            // min / max pada tipe number
            if (field.attr('type') == 'number') {
                // cek nilai min
                if (!isNaN(min) && min != '') {
                    if(value < min) {
                        var msg = '<small class="error-msg text-danger">Minimal '+ min +'</small>';
                        $(target).addClass('text-danger').html(msg);
                        field.addClass('has-error');
                        valid = false;
                    }
                }
                // cek nilai max
                else if (!isNaN(max) && max != '') {
                    if(value < max) {
                        var msg = '<small class="error-msg text-danger">Maximal '+ max +'</small>';
                        $(target).addClass('text-danger').html(msg);
                        field.addClass('has-error');
                        valid = false;
                    }
                }
            }
            // min / max pada tipe date
            else if (field.hasClass('datepicker')) {
                value = value.split('-')
                value = value[2]+'-'+value[1]+'-'+value[0]
                
                // cek nilai min
                if (min != undefined) {
                    var date = min.split('-')
                    date = date[2]+'-'+date[1]+'-'+date[0]
                    if (value < date) {
                        var msg = '<small class="error-msg text-danger">Minimal '+ min +'</small>';
                        $(target).addClass('text-danger').html(msg);
                        field.addClass('has-error');
                        valid = false;
                    }
                }
                // cek nilai max
                if (max != undefined) {
                    var date = max.split('-')
                    date = date[2]+'-'+date[1]+'-'+date[0]
                    if (value > date) {
                        var msg = '<small class="error-msg text-danger">Maximal '+ max +'</small>';
                        $(target).addClass('text-danger').html(msg);
                        field.addClass('has-error');
                        valid = false;
                    }
                }
            }
            else {
                // cek min length
                if (min_len != undefined) {
                    if (value.length < min_len) {
                        var msg = '<small class="error-msg text-danger">Minimal '+ min_len +' karakter</small>';
                        $(target).addClass('text-danger').html(msg);
                        field.addClass('has-error');
                        valid = false;
                    }
                }
                // cek max length
                if (max_len != undefined) {
                    if (value.length < max_len) {
                        var msg = '<small class="error-msg text-danger">Maximal '+ max_len +' karakter</small>';
                        $(target).addClass('text-danger').html(msg);
                        field.addClass('has-error');
                        valid = false;
                    }
                }
            }
        }
    })
    return valid
}

export { panggil, validInput, validProduk }