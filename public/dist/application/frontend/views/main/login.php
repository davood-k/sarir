
<!DOCTYPE html>

<html dir="rtl">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  
  <title> ورود به سامانه  </title>

  <link rel="icon" href="<?php echo base_url('assets/img/fav/favicon.png'); ?>" type="image/x-icon" />

  <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

  <!-- Core stylesheets -->
  <link href="<?php echo base_url('assets/css/bootstrap.rtl.css'); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets/css/pixeladmin.rtl.css'); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets/css/widgets.rtl.css'); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets/css/toastr.min.css'); ?>" rel="stylesheet" type="text/css">


  <!-- Theme -->
  <link href="<?php echo base_url('assets/css/themes/' . $Theme); ?>" rel="stylesheet" type="text/css">

  <style>
    .page-signin-header {
      box-shadow: 0 2px 2px rgba(0,0,0,.05), 0 1px 0 rgba(0,0,0,.05);
    }

    .page-signin-header .btn {
      position: absolute;
      top: 12px;
      right: 15px;
    }

    html[dir="rtl"] .page-signin-header .btn {
      right: auto;
      left: 15px;
    }

    .page-signin-container {
      width: auto;
      margin: 30px 10px;
    }

    .page-signin-container form {
      border: 0;
      box-shadow: 0 2px 2px rgba(0,0,0,.05), 0 1px 0 rgba(0,0,0,.05);
    }

    @media (min-width: 544px) {
      .page-signin-container {
        width: 350px;
        margin: 60px auto;
      }
    }

    .page-signin-social-btn {
      width: 40px;
      padding: 0;
      line-height: 40px;
      text-align: center;
      border: none !important;
    }

    #page-signin-forgot-form { display: none; }
  </style>
</head>
<body>

  <!-- Sign In form -->

  <div class="page-signin-container" id="page-signin-form">
    <h2 class="m-t-0 m-b-4 text-xs-center font-weight-semibold font-size-20"> ورود به سامانه </h2>

    <form id="fLogin" class="panel p-a-4">
      <center>
        <a target="_blank">
          <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="Logo" style="width: 128px;" /> 
        </a>
      </center>

      <fieldset class=" form-group form-group-lg">
        <input autocomplete="off" type="text" id="username" class="form-control" placeholder="نام کاربری">
      </fieldset>

      <fieldset class=" form-group form-group-lg">
        <input type="password" id="password" class="form-control" placeholder="رمز عبور">
      </fieldset>

      <div class="clearfix">
        <label class="custom-control custom-checkbox pull-xs-left hidden">
          <input type="checkbox" class="custom-control-input">
          <span class="custom-control-indicator"></span>
          Remember me
        </label>
        <a href="#" class="font-size-12 text-muted pull-xs-right hidden" id="page-signin-forgot-link">Forgot your password?</a>
      </div>

      <button type="submit" class="btn btn-block btn-lg btn-primary m-t-3">ورود</button>
    </form>

    <h4 class="m-y-3 text-xs-center font-weight-semibold text-muted hidden">or sign in with</h4>

    <div class="text-xs-center hidden">
      <a href="index.html" class="page-signin-social-btn btn btn-success btn-rounded" data-toggle="tooltip" title="Facebook"><i class="fa fa-facebook"></i></a>&nbsp;&nbsp;&nbsp;
      <a href="index.html" class="page-signin-social-btn btn btn-info btn-rounded" data-toggle="tooltip" title="Twitter"><i class="fa fa-twitter"></i></a>&nbsp;&nbsp;&nbsp;
      <a href="index.html" class="page-signin-social-btn btn btn-danger btn-rounded" data-toggle="tooltip" title="Google+"><i class="fa fa-google-plus"></i></a>
    </div>
  </div>

  <!-- / Sign In form -->

  <!-- Reset form -->

  <div class="page-signin-container" id="page-signin-forgot-form">
    <h2 class="m-t-0 m-b-4 text-xs-center font-weight-semibold font-size-20">Password reset</h2>

    <form class="panel p-a-4">
      <fieldset class="form-group form-group-lg">
        <input autocomplete="off" type="email" class="form-control" placeholder="Your Email">
      </fieldset>

      <button type="submit" class="btn btn-block btn-lg btn-primary m-t-3">Send password reset link</button>
      <div class="m-t-2 text-muted">
        <a href="#" id="page-signin-forgot-back">&larr; Back</a>
      </div>
    </form>
  </div>

  <!-- / Reset form -->

  <!-- ==============================================================================
  |
  |  SCRIPTS
  |
  =============================================================================== -->

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

  <script src="<?php echo base_url('assets/js/bootstrap.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/pixeladmin.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/toastr.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.isloading.min.js'); ?>"></script>

  <script>
    var login = false;

    function replaceAll(find, replace, str) {
      return str.replace(new RegExp(find, 'g'), replace);
    }

    function showError(err) {
      toastr["error"](err, "پیغام خطا");
    }

    function showSuccess(err) {
      toastr["success"](err, "پیغام");
    }

    $(function() {

      toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-full-width",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      };    

      $('#fLogin').submit(function() {
        try {

          var username = $('#username').val();
          var password = $('#password').val();

          if (username.trim() == '') {
            showError('نام کاربری را وارد نمایید.');
            return false;
          }

          if (password.trim() == '') {
            showError('رمز عبور را وارد نمایید.');
            return false;
          }

          $.isLoading({ text: "لطفا صبر کنید" });
          $.post('<?php echo site_url('main/login'); ?>',
                { u: username, p: password },
                function(data) {
                  try {
                  if (data.Result) {
                    login = true;
                    window.location = '<?php echo site_url('main/index'); ?>';
                  }
                  else {
                    showError(data.Message);
                  }
                  }
                  catch (err) {
                  }
                }, 'json').fail(function() {
                  showError('اتصال به سرور برقرار نشد.')

                }).always(function() {
                  if (login == false)
                    $.isLoading( "hide" );
                })
        }
        catch (err) {
          $.isLoading( "hide" );
        }

        return false;
      });

  });

  </script>

  <script>
    // -------------------------------------------------------------------------
    // Initialize page components

    /*$(function() {
      pxDemo.initializeBgsDemo('body', 0, '#000', function(isBgSet) {
        $('h2')[isBgSet ? 'addClass' : 'removeClass']('text-white font-weight-bold');

        $('h4')
          .addClass(isBgSet ? 'text-white' : 'text-muted')
          .removeClass(isBgSet ? 'text-muted' : 'text-white');
      });

      $('#page-signin-forgot-link').on('click', function(e) {
        e.preventDefault();

        $('#page-signin-form').css({ display: 'none' });
        $('#page-signin-forgot-form').css({ display: 'block' });

        $(window).trigger('resize');
      });

      $('#page-signin-forgot-back').on('click', function(e) {
        e.preventDefault();

        $('#page-signin-form').css({ display: 'block' });
        $('#page-signin-forgot-form').css({ display: 'none' });

        $(window).trigger('resize');
      });

      $('[data-toggle="tooltip"]').tooltip();
    });*/
  </script>
</body>
</html>
