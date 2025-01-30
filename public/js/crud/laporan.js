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
        ]
    });
    $('#create').click(function () {
        $('#saveBtn').val("create-barang-keluar");
        $('#id').val('');
        $('#formCreate').trigger("reset");
        $('#modelHeading').html("Filter Tanggal");
        $('#modalCreate').modal('show');
        $('#modalCreate').appendTo('body');
        $('#formCreate').find('.help-block').remove();
        $('#formCreate').find('.col-sm-9').removeClass('.has-error');
    });
});