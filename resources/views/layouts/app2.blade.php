<!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')

    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Analytics Dashboard - This is an example dashboard created using build-in elements and components.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
  	<link rel="icon" href="{{asset('logo/logo.png')}}" type="image/png">
      <script src="{{ asset('js/main_tambahan.js') }}"></script>
      <link rel="stylesheet" href="{{ asset('css/main_tambahan.css') }}">
    <link rel="stylesheet" href="{{ asset('Icon-font-7-stroke/assets/Pe-icon-7-stroke.css') }}">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" /> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" integrity="sha512-RXf+QSDCUQs5uwRKaDoXt55jygZZm2V++WUZduaU/Ui/9EGp3f/2KZVahFZBKGH0s774sd3HmrhUy+SgOFQLVQ==" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    @stack('css')
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        @include('layouts.navbar')             
        <div class="app-main">
            @include('layouts.sidebar')   
            <div class="app-main__outer">
            @yield('content') 
            @include('layouts.footer')                      
             </div>
        </div>
    </div>
    @stack('crud')
    <script>
    $(document).ready(function(){
        $('#myTable').dataTable({ 
            "ordering": false,
            "processing": true,
            "lengthMenu": [
            [ 25, 50, 100, 1000, -1 ],
            [ '25', '50', '100', '1000', 'All' ]
        ],
        });
        $('#myTable2').dataTable({ 
            "ordering": false,
            "processing": true,
            "lengthMenu": [
            [ 25, 50, 100, 1000, -1 ],
            [ '25', '50', '100', '1000', 'All' ]
        ],
        });
        $('#myTable3').dataTable({ 
            "ordering": true,
            "processing": true,
            "lengthMenu": [
            [ 25, 50, 100, 1000, -1 ],
            [ '25', '50', '100', '1000', 'All' ]
        ],
        });
        $('#myTable4').dataTable({ 
            "ordering": false,
            "processing": true,
            "lengthMenu": [
            [ 25, 50, 100, 1000, -1 ],
            [ '25', '50', '100', '1000', 'All' ]
        ],
        });
        $('#myTable5').dataTable({ 
            "ordering": true,
            "processing": true,
            "lengthMenu": [
            [ 25, 50, 100, 1000, -1 ],
            [ '25', '50', '100', '1000', 'All' ]
        ],
        });
    });
    @if (Session::has('success'))
        toastr.success('{{ Session::get('success') }}');
    @elseif(Session::has('error'))
        toastr.error('{{ Session::get('error') }}');
    @elseif(Session::has('warning'))
        toastr.warning('{{ Session::get('warning') }}');
    @endif
    </script>
    @if(session('status') && session('status') == 'success')
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ session('message') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif
    @yield('js')
    <style>
        .btn-swal-confirm,
        .btn-swal-cancel {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin: 5px;
        }
        .btn-swal-confirm {
            background-color: #3085d6;
            color: white;
        }
        .btn-swal-cancel {
            background-color: #d33;
            color: white;
        }
    </style>
</body>
</html>