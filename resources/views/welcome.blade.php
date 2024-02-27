<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>سامانه سریر</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/plugins/datepicker/datepicker3.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="dist/css/daterangepicker.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- Google Font: Source Sans Pro -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <link href="" rel="stylesheet">
    <!-- bootstrap rtl -->
    <link rel="stylesheet" href="/dist/css/bootstrap-rtl.min.css">
    <!-- template rtl version -->
    <link rel="stylesheet" href="/dist/css/custom-style.css">
    <link rel="stylesheet" href="/dist/css/bootstrap-switch-button.min.css">
    <!-- template print version -->
    <script type="text/JavaScript" src="/dist/js/jquery.print.js"></script>


</head>
<style>
    body,
    html {
        height: 100%;
    }

    .bg {
        /* Full height */
        height: 100%;
        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .input-required {
        content: ' *';
        color: red;
    }

    @media screen {
        .printSection {
            display: none;
        }
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .printSection,
        .printSection * {
            visibility: visible;
            line-height: 2.6;
        }

        .printSection {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }
    }

    .inthename {
        font-size: 17px;
    }

    .inthename .imageemam {
        height: 250px;
    }

    .imagehead {
        margin-top: 5px;
        height: 220px;
        width: 70%;
        position: relative;
    }

    .imagefooter {
        height: 80px;
        width: 100%;
        position: relative;
    }

    .hadis {
        margin-top {
            100px;
        }
    }

    .sighning {
        line-height: 2;
    }

    .sighnings {
        line-height: 2.2;
    }
    .sighningses {
        line-height: 2.7;
    }

    .copytexts {
        line-height: 1.8;
    }
</style>

<body class="hold-transition sidebar-mini">

    <div class="wrapper">

        {{-- <!-- Navbar -->
        @include('admin.layouts.navbar')
        <!-- /.navbar --> --}}

        <!-- Main Sidebar Container -->
        @include('admin.layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="padding-right: 255px;">
            <!-- Content Header (Page header) -->

            <div class="row mt-4">
                @yield('mohtava')
            </div>
            <footer class="main-footer">
                <strong>CopyRights &copy; 2024 <a href=""> </a>.</strong>
            </footer>
        </div>
        <!-- /.content-wrapper -->


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->


    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Morris.js charts -->
    <script src=""></script>
    <script src="/plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="/plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src=""></script>
    <script src="/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="/plugins/fastclick/fastclick.js"></script>

    <script src="dist/js/adminlte.js"></script>

    <script type="text/JavaScript" src="/dist/js/jquery.print.js"></script>


    <script src="/plugins/select2/select2.full.js"></script>

    <!-- AdminLTE App -->
    {{-- <script src="{{ asset('js/admin.js') }}"></script> --}}
    <script>
        // $('#search').keyup(function(e){
        //   var searchValue = $(this).val();
        //   if(searchValue.length >= 9)
        //   {
        //       var query = window.location.pathname + '?search=' + searchValue;
        //       window.location.href = query;
        //   }
        //   else if(searchValue.length == 0)
        //   {
        //       var query = window.location.pathname;
        //       window.location.href = query;
        //   }
        // });
    </script>
    @yield('script')
    <script type="text/JavaScript" src="/dist/js/bootstrap-switch-button.min.js"></script>
</body>

</html>
