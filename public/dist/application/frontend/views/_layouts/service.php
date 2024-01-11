<!DOCTYPE html>

<html dir="rtl">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

  <title>{title}</title>

  <!-- External stylesheets -->
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,800,300&subset=latin" rel="stylesheet" type="text/css">
  <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css">

  <link rel="icon" href="<?php echo base_url('assets/img/fav/favicon.png'); ?>" type="image/x-icon" />


  <link type="text/css" href="<?php echo base_url('assets/uikit/css/uikit-rtl.min.css'); ?>" rel="stylesheet" />
  <script type="text/javascript" src="<?php echo base_url('assets/uikit/js/uikit.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/uikit/js/uikit-icons.min.js'); ?>"></script>

  <!-- Core stylesheets -->
  <link href="<?php echo base_url('assets/css/bootstrap.rtl.css'); ?>?v=<?php echo $AssetVersion; ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets/css/pixeladmin.rtl.css'); ?>?v=<?php echo $AssetVersion; ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets/css/widgets.rtl.css'); ?>" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome5.8.1/all.min.css'); ?>" type="text/css" /><!-- Font Awesome -->
  <link href="<?php echo base_url('assets/admin/css/multiple-select.css'); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets/css/animate.min.css'); ?>" rel="stylesheet" type="text/css">
  <!-- Theme -->
  <link href="<?php echo base_url('assets/css/themes/' . $Theme); ?>" rel="stylesheet" type="text/css">
  <script src="<?php echo base_url('assets/js/jquery-3.1.1.min.js'); ?>"></script>

  <link href="<?php echo base_url('assets/css/jquery.bootgrid.min.css'); ?>" rel="stylesheet" />

  <script type="text/javascript" src="<?php echo base_url('assets/js/numeral.min.js'); ?>"></script>
  <link type="text/css" href="<?php echo base_url('assets/css/js-persian-cal.css'); ?>" rel="stylesheet" />
  <script type="text/javascript" src="<?php echo base_url('assets/js/js-persian-cal.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.form.js'); ?>"></script>


  <link type="text/css" href="<?php echo base_url('assets/css/style.css'); ?>?v=<?php echo $AssetVersion; ?>" rel="stylesheet" />
  <script type="text/javascript" src="<?php echo base_url('assets/js/scripts.js'); ?>?v=<?php echo $AssetVersion; ?>" ></script>

  <script type="text/javascript" src="<?php echo base_url('assets/js/JsBarcode.all.min.js'); ?>"></script>

  <link href="https://static.neshan.org/sdk/leaflet/1.4.0/leaflet.css" rel="stylesheet" type="text/css">
  <script src="https://static.neshan.org/sdk/leaflet/1.4.0/leaflet.js" type="text/javascript"></script>

  <link type="text/css" href="<?php echo base_url('assets/datePicker/jquery.Bootstrap-PersianDateTimePicker.css'); ?>" />

  <link type="text/css" href="<?php echo base_url('assets/css/apexcharts.css'); ?>" rel="stylesheet" />

    <?php
      // Grocery CRUD scripts
      if ( !empty($crud_data) )
      {
        foreach ($crud_data->css_files as $file)
          echo link_tag($file).PHP_EOL;

        foreach ($crud_data->js_files as $file)
//            if (strpos($file, 'jquery-1.10.2.min.js') === FALSE)
                echo "<script src='$file'></script>".PHP_EOL;
      }
    ?>

  <link type="text/css" href="<?php echo base_url('assets/css/jquery-confirm.min.css'); ?>" rel="stylesheet" />
  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-confirm.min.js'); ?>"></script>
    

    <script type="text/javascript">
      var Products = null;
      var CurrentKey = null;
      var CurrentProduct = null;
      var OldCode = '';
      var AdvisorBasket = [];

      var SiteUrl = '<?php echo site_url(''); ?>';
      var siteURL = '<?php echo site_url(''); ?>'.replace('index.php', '') + '/filemanager/index.html';
      var BaseUrl = '<?php echo base_url(''); ?>';
      var ShopUrl = '<?php echo $ShopUrl; ?>';
      var Unit = '<?php echo $Unit; ?>';
      var EditCustomer = <?php echo (hasEdit('customers') || hasAdd('customers')) ? 'true' : 'false';  ?>;
      var Tax = <?php echo intval($Config->Taxes); ?>;
      var SelectLocation = <?php echo $User['SelectCustomerLocation'] ? 'true' : 'false'; ?>;
      var AccessMali = <?php if (hasView('mali')) echo 'true'; else echo 'false'; ?>;
      var UID = <?php echo intval($User['ID']); ?>;
      var RoleID = <?php echo intval($User['RoleID']); ?>;
    </script>
</head>
<body>

<div class="" style="padding: 15px;">
{body}
</div>

<div class="view-map" id="view-map">

</div>

<div id="modal-person" uk-modal>
  <div class="uk-modal-dialog uk-modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h4 class="modal-title">فرد مرتبط</h4>
          </div>
          <div class="modal-body">
            <form id="fPerson" method="post">
            <div uk-grid>
              <div class="uk-width-1-2@s">
                
                <div class="uk-margin">
                    <label class="uk-form-label" for="pSemat">سِمت</label>
                    <div class="uk-form-controls">
                        <select id="pSemat" class="uk-select form-control form-check" role="required" role-name="سِمت">
                          <option value="">----- سِمت را انتخاب نمایید -----</option>
                          <?php foreach ($Semats as $s) { ?>
                            <option value="<?php echo $s->ID; ?>"><?php echo $s->Name; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                </div>            

              </div>
            </div>

            <div uk-grid class="uk-grid childs-margin-10">
              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label" for="pFName">نام</label>
                    <div class="uk-form-controls">
                      <input autocomplete="off" type="text" id="pFName" maxlength="100" role-name="نام" role="required" class="form-control form-check" placeholder="نام را وارد نمایید..." />
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label" for="pLName">نام خانوادگی</label>
                    <div class="uk-form-controls">
                      <input autocomplete="off" type="text" id="pLName" maxlength="100" role-name="نام خانوادگی" role="required" class="form-control form-check" placeholder="نام خانوادگی را وارد نمایید..." />
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label" for="pTel">تلفن</label>
                    <div class="uk-form-controls">
                      <input autocomplete="off" type="phone" id="pTel" maxlength="100" role-name="تلفن" role="tel" class="form-control form-check" placeholder="تلفن را وارد نمایید..." />
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label" for="pMobile">تلفن همراه</label>
                    <div class="uk-form-controls">
                      <input autocomplete="off" type="phone" id="pMobile" maxlength="100" role-name="تلفن همراه" role="phone" class="form-control form-check" placeholder="تلفن همراه را وارد نمایید..." />
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label" for="pEmail">پست الکترونیک</label>
                    <div class="uk-form-controls">
                      <input autocomplete="off" type="email" id="pEmail" maxlength="100" role-name="پست الکترونیک" role="email" class="form-control form-check" placeholder="پست الکترونیک را وارد نمایید..." />
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label" for="pTelegram">آدرس تلگرام</label>
                    <div class="uk-form-controls">
                      <input autocomplete="off" type="text" id="pTelegram" maxlength="100" role-name="آدرس تلگرام" class="form-control form-check" placeholder="آدرس تلگرام را وارد نمایید..." />
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-1">
                <div class="uk-margin">
                    <label class="uk-form-label" for="pAddress">آدرس</label>
                    <div class="uk-form-controls">
                      <textarea class="form-control form-check" role-name="آدرس" maxlength="100" id="pAddress" placeholder="آدرس را وارد نمایید..."></textarea>
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-1">
                <div class="uk-margin">
                    <label class="uk-form-label" for="pTozihat">توضیحات</label>
                    <div class="uk-form-controls">
                      <textarea class="form-control form-check" role-name="توضیحات" maxlength="100" id="pTozihat" placeholder="توضیحات را وارد نمایید..."></textarea>
                    </div>
                </div>            
              </div>

            </div>

            <div class="uk-grid" uk-grid>
                <div class="uk-width-1-1 uk-text-left">
                  <button class="btn btn-success fixed-width" type="submit">ثبت اطلاعات</button>&nbsp;&nbsp;
                  <button class="btn btn-danger fixed-width" onclick="UIkit.modal('#modal-person').hide();">بازگشت</button>
                </div>
            </div>

            </form>

          </div>
        </div>
  </div>
</div>

<div id="modal-view-person" uk-modal>
  <div class="uk-modal-dialog uk-modal-body">
        <div class="modal-content">
          <div class="modal-header">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h4 class="modal-title">فرد مرتبط</h4>
          </div>
          <div class="modal-body">
            <div uk-grid>
              <div class="uk-width-1-2@s">
                
                <div class="uk-margin">
                    <label class="uk-form-label">سِمت</label>
                    <div class="uk-form-controls form-info" id="pvSemat">
                    </div>
                </div>            

              </div>
            </div>

            <div uk-grid class="uk-grid childs-margin-10">
              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label">نام</label>
                    <div class="uk-form-controls form-info" id="pvFName">
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label">نام خانوادگی</label>
                    <div class="uk-form-controls form-info" id="pvLName">
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label">تلفن</label>
                    <div class="uk-form-controls form-info" id="pvTel">
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label" >تلفن همراه</label>
                    <div class="uk-form-controls form-info" id="pvMobile">
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label">پست الکترونیک</label>
                    <div class="uk-form-controls form-info" id="pvEmail">
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-2@s">
                <div class="uk-margin">
                    <label class="uk-form-label">آدرس تلگرام</label>
                    <div class="uk-form-controls form-info" id="pvTelegram">
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-1">
                <div class="uk-margin">
                    <label class="uk-form-label">آدرس</label>
                    <div class="uk-form-controls form-info" id="pvAddress">
                    </div>
                </div>            
              </div>

              <div class="uk-width-1-1">
                <div class="uk-margin">
                    <label class="uk-form-label">توضیحات</label>
                    <div class="uk-form-controls form-info" id="pvTozihat">
                    </div>
                </div>            
              </div>

            </div>

            <div class="uk-grid" uk-grid>
                <div class="uk-width-1-1 uk-text-left">
                  <button class="btn btn-danger fixed-width" onclick="UIkit.modal('#modal-view-person').hide();">بازگشت</button>
                </div>
            </div>

          </div>
        </div>
  </div>
</div>

<div id="modal-view-map" uk-modal >
  <div class="uk-modal-dialog uk-modal-body">
    <div class="modal-content">
      <div class="modal-header">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h4 class="modal-title">نقشه</h4>
      </div>
      <div class="modal-body">
        <div id="map">

        </div>
      </div>
    </div>
  </div>
</div>

  <!-- ==============================================================================
  |
  |  SCRIPTS
  |
  =============================================================================== -->

  <!-- Load jQuery -->

  <!-- Core scripts -->
  <script src="<?php echo base_url('assets/js/bootstrap.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/pixeladmin.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.isloading.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/apexcharts.js'); ?>?v=<?php echo $AssetVersion; ?>" ></script>

  <script type="text/javascript" src="<?php echo base_url('assets/datePicker/calendar.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/datePicker/jquery.Bootstrap-PersianDateTimePicker.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/admin/js/multiple-select.js'); ?>"></script>    
  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.bootgrid.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/exif.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/BarcodeScanner.min.js'); ?>"></script>

  <script type="text/javascript">
    // -------------------------------------------------------------------------
    // Initialize DEMO
    function replaceAll(find, replace, str) {
      try {
        return str.replace(new RegExp(find, 'g'), replace);
      }
      catch (err) {}

      return str;
    }

    function ShowError(err) {
      toastr["error"](err, "پیغام خطا");
    }

    function ShowSuccess(err) {
      toastr["success"](err, "پیغام");
    }

    function ShowLoading() {
    $.isLoading({ text: "لطفا صبر کنید" });    
}

function HideLoading() {
    $.isLoading( "hide" );
}

function confirm(title, text, onconfirm) {
  $.alert({
    title: title,
    content: text,
    rtl: true,
    closeIcon: true,
    type: 'orange',
    typeAnimated: true,
    buttons: {
        confirm: {
            text: 'تایید',
            btnClass: 'btn-blue',
            action: onconfirm
        },
        cancel: {
            text: 'انصراف',
            action: function () {
            }
        }
    }
});  
}

function Post(url, d, ok, custom, always, error) {
  if (custom === undefined)
    custom = false;
  
  if (custom == false)
    ShowLoading();
  $.post(url, 
    d, 
    function(data) {
      if (data.Result) {
        ok(data);
      }
      else {
        ShowError(data.Message);
      }
    }, 'json').always(function() {
      if (custom == false)
        HideLoading();

    if (always)
      always();
  }).fail(function() {

    if (error)
      error();
    else
      ShowError('اتصال به سرور برقرار نشد.');
  })
}

    $(function() {

      try {
        $('input').attr('autocomplete','off');
      }
      catch (err) {}

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

      var file = String(document.location).split('/').pop();

      // Remove unnecessary file parts
      file = file.replace(/(\.html).*/i, '$1');

      if (!/.html$/i.test(file)) {
        file = 'index.html';
      }

      // Activate current nav item
      $('body > .px-nav')
        .find('.px-nav-item > a[href="' + file + '"]')
        .parent()
        .addClass('active');

      $('body > .px-nav').pxNav();
      $('body > .px-footer').pxFooter();

      $('#navbar-notifications').perfectScrollbar();
      $('#navbar-messages').perfectScrollbar();
    });
  </script>
</body>
</html>