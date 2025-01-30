$(function () {
 
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  
//Tabel tamu
    var table = $('.table-keluar').DataTable({
        "lengthMenu": [
            [ 25, 50, 100, 1000, -1 ],
            [ '25', '50', '100', '1000', 'All' ]
        ],
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        retrieve: true,
        ajax: "",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'barang', name: 'barang'},
            {data: 'jumlah', name: 'jumlah'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

//CREATE barang-keluar
    $('#create').click(function () {
        $('#saveBtn').val("create-barang-keluar");
        $('#id').val('');
        $('#formCreate').trigger("reset");
        $('#modelHeading').html("Tambah Periode");
        $('#modalCreate').modal('show');
        $('#modalCreate').appendTo('body');
        $('#formCreate').find('.help-block').remove();
        $('#formCreate').find('.col-sm-9').removeClass('.has-error');
    });

//EDIT barang-keluar
    $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
        $.get("barang-keluar" +'/' + id +'/edit', function (data) {
            $('#modelHeading').html("Edit Barang");
                $('#saveBtn').val("edit-barang-keluar");
                $('#modalCreate').modal('show');
                $('#modalCreate').appendTo('body');
                $('#formCreate').find('.help-block').remove();
                $('#formCreate').find('.col-sm-9').removeClass('.has-error');
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#harga_beli').val(data.harga_beli);
                $('#harga_jual').val(data.harga_jual);
        })
    });


//SAVE & UPDATE barang-keluar
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $('#formCreate').find('.help-block').remove();
        $('#formCreate').find('.col-sm-9').removeClass('.has-error');
        $(this).html('Menyimpan..');
        $.ajax({
            data: $('#formCreate').serialize(),
            url: "barang-keluar",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                console.log(data.error)
                    if($.isEmptyObject(data.error)){
                        $('#formCreate').trigger("reset");
                        $('#modalCreate').modal('hide');
                        $('#saveBtn').html('<i class="metismenu-icon pe-7s-paper-plane"></i> Simpan');
                        table.draw();
                        toastr.success('Berhasil Menyimpan barang-keluar', 'Success !'),(data.success);
                    }else{
                        printErrorMsg(data.error);
                    }
                },
            error: function (data) {
                console.log('Error:', data);
                $('#saveBtn').html('Simpan');
            }
        });
    });

//DELETE barang-keluar
    $('body').on('click', '.delete', function (){
        var id = $(this).data("id");
        var result = Swal.fire({
            title: 'Peringatan!', 
            text: 'Apakah anda yakin?', 
            icon: 'warning',
            showCancelButton: true,
        }).then((result) =>{
                if (result.isConfirmed){
                    $.ajax({
                    type: "GET",
                    url: "hapus-barang-keluar"+'/'+id,
                    success: function (data) {
                        table.draw();
                        toastr.success('Berhasil Menghapus barang-keluar', 'Success !'),(data.success);
                        $('#formCreate').find('.help-block').remove();
                        $('#formCreate').find('.col-sm-9').removeClass('.has-error');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        })
    });
});

function printErrorMsg (msg) {
    $.each( msg, function( key, value ) {
    console.log(key);
      $('#' +key)
      .closest('.col-sm-9')
      .addClass('has-error')
      .append('<span class="help-block text-danger">'+ value +'</span>');
    });
    $('#saveBtn').html('<i class="metismenu-icon pe-7s-paper-plane"></i> Simpan')
}