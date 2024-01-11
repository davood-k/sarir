<!DOCTYPE html>

<html dir="rtl">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

  <title>{title}</title>

  <!-- External stylesheets -->
  <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,800,300&subset=latin" rel="stylesheet" type="text/css">
  <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css">-->

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

<!--  <link href="https://static.neshan.org/sdk/leaflet/1.4.0/leaflet.css" rel="stylesheet" type="text/css">
  <script src="https://static.neshan.org/sdk/leaflet/1.4.0/leaflet.js" type="text/javascript"></script>-->

  <link type="text/css" href="<?php echo base_url('assets/datePicker/jquery.Bootstrap-PersianDateTimePicker.css'); ?>" />

  <link type="text/css" href="<?php echo base_url('assets/css/apexcharts.css'); ?>" rel="stylesheet" />

  <style type="text/css">
    .swal2-container {
      z-index: 100000 !important;
    }

    .swal2-confirm,.swal2-cancel,.swal2-deny {
      font-size: medium !important;
    }
  </style>
  
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
      var SiteUrl = '<?php echo site_url(''); ?>';
      var siteURL = '<?php echo site_url(''); ?>'.replace('index.php', '') + '/filemanager/index.html';
      var BaseUrl = '<?php echo base_url(''); ?>';
      var ShopUrl = '<?php echo $ShopUrl; ?>';
    </script>
</head>
<body>
  <!-- Nav -->
  <nav class="px-nav px-nav-left">
  
    <button type="button" class="px-nav-toggle" data-toggle="px-nav">
      <span class="px-nav-toggle-arrow"></span>
      <span class="navbar-toggle-icon"></span>
      <span class="px-nav-toggle-label font-size-11"></span>
      <?php $t = $TicketsCountPanel + $TicketsCount; if (intval($t)) { ?><span class="badge badge-primary hidden-lg hidden-md hidden-sm" style="margin-top: -20px; margin-right: 0px; float: right; z-index: 999999999;"><?php echo intval($t); ?></span><?php } ?>
    </button>

    <ul class="px-nav-content">
      <li class="px-nav-box p-a-3 b-b-1" id="demo-px-nav-box">
        <img src="<?php if ($User['Pic'] == '') echo base_url('assets/img/user.jpg'); else echo base_url('files/' . $User['ID'] . '/' . $User['Pic']); ?>" alt="" class="pull-xs-left m-r-2 border-round" id="imgProfileRight" style="width: 54px; height: 54px; cursor: pointer;" data-toggle="modal" data-target="#modal-change-photo">
        <div class="font-size-16"><span class="font-weight-light"><strong><?php if (!empty($User['FName']) || !empty($User['LName'])) echo $User['FName'] . ' ' . $User['LName']; else echo $User['Username']; ?></strong><br> خوش آمدید! </span></div>
      </li>

      <li class="px-nav-item <?php if ($Class == 'main' && $Action == 'index') echo 'active'; ?>">
        <a href="<?php echo site_url('main/index'); ?>"><i class="px-nav-icon ion-ios-home "></i><span class="px-nav-label">لیست افراد</span></a>
      </li>

      <li class="px-nav-item <?php if ($Class == 'main' && $Action == 'get_file') echo 'active'; ?>">
        <a href="<?php echo site_url('main/get_'); ?>"><i class="px-nav-icon ion-ios-home "></i><span class="px-nav-label">دریافت فایل</span></a>
      </li>

      <li class="px-nav-item <?php if ($Class == 'main' && $Action == 'calc_string') echo 'active'; ?>">
        <a href="<?php echo site_url('main/calc_string'); ?>"><i class="px-nav-icon ion-ios-home "></i><span class="px-nav-label">درج توضیحات</span></a>
      </li>

    </ul>


  </nav>

  <!-- Navbar -->
  <nav class="navbar px-navbar">

    <!-- Header -->
    <div class="navbar-header">
      <a class="navbar-brand px-demo-brand" href="<?php echo site_url('main'); ?>"><span class="px-demo-logo uk-hidden"> <img src="<?php echo base_url('assets/img/logo-white.png'); ?>" alt="Logo" style="height: 32px;" /> </span> &nbsp;&nbsp;سامانه  - {title} </a>
    </div>

    <!-- Navbar togglers -->
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#px-demo-navbar-collapse" aria-expanded="false"><i class="navbar-toggle-icon uk-hidden"></i><i class="fas fa-user fa-2x" style="color: darkgray;"></i>
    <span class="label label-danger <?php if ($MessagesNotReaded == 0) echo 'hidden'; ?>"  style="position: absolute; left: 5px; top: 15px;" id="notifs1"><?php echo $MessagesNotReaded; ?></span>
  </button>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="px-demo-navbar-collapse">
      <ul class="nav navbar-nav">

      </ul>

      <ul class="nav navbar-nav navbar-right">

        <li class="dropdown hidden">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
            <i class="px-navbar-icon fa fa-envelope font-size-14"></i>
            <span class="px-navbar-icon-label">پیام های دریافتی</span>
            <span class="px-navbar-label label label-danger <?php if ($MessagesNotReaded == 0) echo 'hidden'; ?>" id="notifs"><?php echo $MessagesNotReaded; ?></span>
          </a>
          <div class="dropdown-menu p-a-0">
            <div id="navbar-messages" style="height: 280px; position: relative;" class="ps-container ps-theme-default" data-ps-id="2b58566a-76e1-c6ef-3e81-ba43e26c63a8">

            <?php foreach ($Messages as $item) { ?>
              <div class="widget-messages-alt-item <?php if ($item->Readed == 0) echo 'notread'; else echo 'readed'; ?>" onclick="ShowNotification(<?php echo $item->ID; ?>);" style="cursor: pointer;">
                <img src="<?php if ($item->Pic == '') echo base_url('assets/img/user.jpg'); else echo base_url('files/' . $item->UID . '/' . $item->Pic); ?>" alt="" class="widget-messages-alt-avatar m-r-2 border-round">
                <span class="widget-messages-alt-subject text-truncate" id="mtitle<?php echo $item->ID; ?>"><?php echo $item->Title; ?></span>
                <span href="#" class="widget-messages-alt-subject text-truncate" style="color: #dfdfdf;" id="mcontent<?php echo $item->ID; ?>"><?php echo $item->Content; ?></span>
                <span class="hidden" id="mdate<?php echo $item->ID; ?>"><?php echo $item->ShamsiDate . ' ' . $item->ShamsiTime; ?></span>
                <div class="widget-messages-alt-description hidden">از طرف <a href="#" id="muser<?php echo $item->ID; ?>"><?php echo $item->Name; ?></a></div>
                <div class="widget-messages-alt-date" style="float: left;"><?php echo $item->ShamsiTime . ' ' . $item->ShamsiDate; ?></div>
              </div>
            <?php } ?>
            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
            <?php if (count($Messages) >= 10) { ?>
            <a href="#" class="widget-more-link">لیست کامل پیام ها</a>
            <?php } ?>
          </div> <!-- / .dropdown-menu -->
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <img src="<?php if ($User['Pic'] == '') echo base_url('assets/img/user.jpg'); else echo base_url('files/' . $User['ID'] . '/' . $User['Pic']); ?>" alt="" id="imgProfileTop" class="px-navbar-image">
            <span class="hidden-md"><?php if (!empty($User['FName']) || !empty($User['LName'])) echo $User['FName'] . ' ' . $User['LName']; else echo $User['Username']; ?></span>
          </a>
          <ul class="dropdown-menu">
            <li style="display: none;"><a href=""><span class="label label-warning pull-xs-right"><i class="fa fa-asterisk"></i></span>ویرایش مشخصات</a></li>
            <li><a href="javascript:" onclick="ChangePassword();">تغییر رمز عبور</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo site_url('main/logout'); ?>"><i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;خروج</a></li>
          </ul>
        </li>


      </ul>
    </div>
    

  </nav>

  <!-- Content -->
  <div class="px-content">{body}</div>

  <!-- Footer -->
  <footer class="px-footer px-footer-bottom hidden">
    
    <div class="box m-a-0 bg-transparent">
    </div>

    <hr class="page-wide-block">

    <span class="text-muted hidden">Copyright © 2017 LLC. All rights reserved.</span>

  </footer>






<script type="text/javascript">
  var OrderStoreData = {};
  function ShowOrderStores(order_id, item_id) {
    OrderStoreData.OrderID = order_id;
    OrderStoreData.ItemID = item_id;

    UIkit.modal('#modal-view-order-stores').show();

    GetOrderStores(order_id, item_id);
  }

  function GetCountOfOrderItemInItems(current, store, reseller) {
    for (let i = 0; i < current.length; i++) {
      let item = current[i];

      if (item.StoreID == store && item.ResellerID == reseller)
        return Math.abs(parseInt(item.Count));
    }

    return 0;
  }

  function CalcTotalOrderItemsCount() {
    let sum = 0;

    $('.order_item_count').each(function(index, obj) {
      let val = parseInt($(obj).val());

      if (!isNaN(val))
        sum += val;
    });

    $('#tTotalOrderItems').html(sum);

    return sum;
  }

  function ChangeOrderItemsCounts() {
    if (Math.abs(CalcTotalOrderItemsCount()) != Math.abs(OrderStoreData.Total)) {
      ShowError('مجموع تعداد باید ' + Math.abs(OrderStoreData.Total) + ' باشد.');
    }
    else {
      confirm('', 'آیا مطمئن هستید؟', function(res) {
        if (res) {
          let items = [];

          $('.order_item_count').each(function(index, obj) {
            let count = parseInt($(obj).val());

            if (!isNaN(count)) {
              items[items.length] = {
                store_id: $(obj).attr('data-store-id'),
                reseller_id: $(obj).attr('data-reseller-id'),
                count: count
              }
            }
          });

          ShowLoading();
          $.post('<?php echo site_url('store/change_order_item_counts'); ?>',
                { oid: OrderStoreData.OrderID, id: OrderStoreData.ItemID, data: JSON.stringify(items) },
                function(data) {
                  if (data.Result) {
                    GetOrderStores(OrderStoreData.OrderID, OrderStoreData.ItemID);
                    $('#grid').bootgrid('reload');
                  }
                  else {
                    ShowError(data.Message);
                  }
                }, 'json').fail(function() {
                  ShowError('اتصال به سرور برقرار نشد.');
                }).always(function(){
                  HideLoading();
                })
        }
      })
    }
  }

  function EditOrderStores() {
    $('#view-order-stores-body').html('<div class="uk-text-center"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" /></div>');

    $.post('<?php echo site_url('store/get_order_store_to_edit'); ?>',
          { oid: OrderStoreData.OrderID, id: OrderStoreData.ItemID},
          function(data) {
            if (data.Result) {
              OrderStoreData.Data = data.Data;

              let sum = 0;
              for (let i = 0; i < data.Data.Current.length; i++) {
                sum += parseInt(data.Data.Current[i].Count);
              }

              OrderStoreData.Total = sum;

              let html = '';

              html += '<div class=""><button class="btn btn-info" onclick="GetOrderStores(' + OrderStoreData.OrderID + ', ' + OrderStoreData.ItemID + ');">بازگشت</button></div>';

              html += '<hr>';

              html += '<table width="100%" class="table table-condensed table-hover table-striped">';
              html += '<tr><th class="td-grid">ردیف</th><th class="td-grid">انبار</th><th class="td-grid">موجودی</th><th class="td-grid">تعداد</th></tr>';

              html += '<tr>';

              html += '<td class="td-grid">' + 1 + '</td>';
              html += '<td class="td-grid">' + data.Data.Store.Name;
              html += '</td>';
              html += '<td class="td-grid">' + data.Data.Store.Count + '</td>';
              html += '<td class="td-grid"><input type="text" class="order_item_count" data-store-id="' + data.Data.Store.ID + '" data-reseller-id="0" value="' + GetCountOfOrderItemInItems(data.Data.Current, data.Data.Store.ID, 0) +  '" data-max-value="' + data.Data.Store.Count + '" />';

              html += '</tr>';
              
              for (let i = 0; i < data.Data.Resellers.length; i++) {
                let item = data.Data.Resellers[i];

                html += '<tr>';

                html += '<td class="td-grid">' + (i + 2) + '</td>';
                html += '<td class="td-grid">' + item.Store;
                html += ' - ' + item.FName + ' ' + item.LName;

                html += '</td>';
                html += '<td class="td-grid">' + item.Count + '</td>';
                html += '<td class="td-grid"><input type="text" class="order_item_count" data-store-id="' + item.ResellerStoreID + '" data-reseller-id="' + item.ID + '" value="' + GetCountOfOrderItemInItems(data.Data.Current, item.ResellerStoreID, item.ID) +  '" data-max-value="' + item.Count + '" />';

                html += '</tr>';
              }

              html += '<tr><td colspan="3"></td>';
              html += '<td class="td-grid" style="border-top: solid 1px black;" id="tTotalOrderItems"></td></tr>'
              
              html += '</table>';

              html += '<hr><div class="uk-text-center"><button class="btn btn-success" onclick="ChangeOrderItemsCounts();">ویرایش تعداد ها</button></div>';

              $('#view-order-stores-body').html(html);

              CalcTotalOrderItemsCount();

              $('.order_item_count').keyup(function(event) {
                let obj = event.target;

                let count = parseInt($(obj).val());
                let total = parseInt($(obj).attr('data-max-value'));

                if (!isNaN(count)) {
                  if (count > total)
                    $(obj).val(total);
                }

                CalcTotalOrderItemsCount();
              });
            }
            else {
              $('#view-order-stores-body').html('<div class="uk-text-center"><div class="alert alert-danger">' + data.Message + '</div><br><hr><button class="btn btn-danger" onclick="EditOrderStores();">تلاش مجدد</button>&nbsp;<button class="btn btn-info" onclick="GetOrderStores(' + OrderStoreData.OrderID + ', ' + OrderStoreData.ItemID + ');">بازگشت</button></div>');
              
            }
          }, 'json').fail(function() {
              $('#view-order-stores-body').html('<div class="uk-text-center"><div class="alert alert-danger">اتصال به سرور برقرار نشد.</div><br><hr><button class="btn btn-danger" onclick="EditOrderStores();">تلاش مجدد</button>&nbsp;<button class="btn btn-info" onclick="GetOrderStores(' + OrderStoreData.OrderID + ', ' + OrderStoreData.ItemID + ');">بازگشت</button></div>');
          }).always(function() {

          });

  }

  function GetOrderStores(order_id, item_id) {
    $('#view-order-stores-body').html('<div class="uk-text-center"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" /></div>');

    $.post('<?php echo site_url('store/get_order_stores'); ?>',
          { oid: order_id, id: item_id},
          function(data) {
            if (data.Result) {
              let html = '';

              html += '<div class=""><button class="btn btn-info" onclick="EditOrderStores();">ویرایش</button></div>';

              html += '<hr>';

              html += '<table width="100%" class="table table-condensed table-hover table-striped">';
              html += '<tr><th class="td-grid">ردیف</th><th class="td-grid">انبار</th><th class="td-grid">تعداد</th></tr>';

              for (let i = 0; i < data.Data.length; i++) {
                let item = data.Data[i];

                html += '<tr>';

                html += '<td class="td-grid">' + (i + 1) + '</td>';
                html += '<td class="td-grid">' + item.Name;
                if (item.ResellerID != '0')
                  html += ' - ' + item.Reseller;

                html += '</td>';
                html += '<td class="td-grid">' + item.Count + '</td>';

                html += '</tr>';
              }
              
              html += '</table>';

              $('#view-order-stores-body').html(html);
            }
            else {
              $('#view-order-stores-body').html('<div class="uk-text-center"><div class="alert alert-danger">' + data.Message + '</div><br><hr><button class="btn btn-danger" onclick="GetOrderStores(' + order_id + ', ' + item_id + ');">تلاش مجدد</button></div>');
              
            }
          }, 'json').fail(function() {
              $('#view-order-stores-body').html('<div class="uk-text-center"><div class="alert alert-danger">اتصال به سرور برقرار نشد.</div><br><hr><button class="btn btn-danger" onclick="GetOrderStores(' + order_id + ', ' + item_id + ');">تلاش مجدد</button></div>');
          }).always(function() {

          });

  }
</script>


<script type="text/javascript">
    function ChangePassword() {
      $('#currentPassword').val('');
      $('#newPassword').val('');
      $('#renewPassword').val('');
      $('#modal-change-password').modal('show');
    }

    $(document).ready(function() {
      $('#fChangePassword').submit(function() {
        try {
          var pass = $('#currentPassword').val();
          var newpass = $('#newPassword').val();
          var renewpass = $('#renewPassword').val();

          if (pass == '' || newpass == '' || renewpass == '') {
            ShowError('لطفا اطلاعات را به صورت کامل وارد نمایید');
            return false;
          }

          if (newpass != renewpass) {
            ShowError('رمز عبور و تکرار آن مطابقت ندارند.');
            return false;
          }

          $.isLoading({ text: "لطفا صبر کنید" });
          $.post('<?php echo site_url('main/change_password'); ?>',
                  { p: pass, np: newpass},
                  function(data) {
                    if (data.Result) {
                      $('#modal-change-password').modal('hide');
                      ShowSuccess('تغییر رمز با موفقیت انجام شد.');
                    }
                    else {
                      ShowError(data.Message);
                    }
                  }, 'json').fail(function() {
                    ShowError('اتصال به سرور برقرار نشد.');
                  }).always(function() {
                    $.isLoading( "hide" );
                  });

        }
        catch (err) {
          console.log(err);
        }

        return false;
      });
    });
  </script>

  <!-- ==============================================================================
  |
  |  SCRIPTS
  |
  =============================================================================== -->

  <!-- Load jQuery -->
  <link href="<?php echo base_url('assets/css/bootstrap.rtl.css'); ?>?v=<?php echo $AssetVersion; ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets/css/pixeladmin.rtl.css'); ?>?v=<?php echo $AssetVersion; ?>" rel="stylesheet" type="text/css">
  <link type="text/css" href="<?php echo base_url('assets/css/style.css'); ?>?v=<?php echo $AssetVersion; ?>" rel="stylesheet" />

  <!-- Core scripts -->
  <script src="<?php echo base_url('assets/js/bootstrap.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/pixeladmin.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.isloading.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/apexcharts.js'); ?>?v=<?php echo $AssetVersion; ?>" ></script>

  <script type="text/javascript" src="<?php echo base_url('assets/datePicker/calendar.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/datePicker/jquery.Bootstrap-PersianDateTimePicker.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/admin/js/multiple-select.js'); ?>"></script>    
  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.bootgrid.min.js'); ?>?v=<?php echo $AssetVersion; ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/exif.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/BarcodeScanner.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/autoNumeric.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/sweetalert2.js'); ?>"></script>

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