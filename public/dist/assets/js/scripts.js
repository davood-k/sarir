var TempID = 0;
var basket = [];
var Tour = null;
var CurrentCustomer = null;
var tours = [];
var data = [];
var CurrentOrder = null;
var CustomerIsOpen = false;

$.expr[':'].textEquals = $.expr.createPseudo(function(arg) {
    return function( elem ) {
        return $(elem).text().match("^" + arg + "$");
    };
});


jQuery.fn.putCursorAtEnd = function() {

    return this.each(function() {
      
      // Cache references
      var $el = $(this),
          el = this;
  
      // Only focus if input isn't already
      if (!$el.is(":focus")) {
       $el.focus();
      }
  
      // If this function exists... (IE 9+)
      if (el.setSelectionRange) {
  
        // Double the length because Opera is inconsistent about whether a carriage return is one character or two.
        var len = $el.val().length * 2;
        
        // Timeout seems to be required for Blink
        setTimeout(function() {
          el.setSelectionRange(len, len);
        }, 1);
      
      } else {
        
        // As a fallback, replace the contents with itself
        // Doesn't work in Chrome, but Chrome supports setSelectionRange
        $el.val($el.val());
        
      }
  
      // Scroll to the bottom, in case we're in a tall textarea
      // (Necessary for Firefox and Chrome)
      this.scrollTop = 999999;
  
    });
  
  };

$(document).on('keyup', function(e) {
    if (e.keyCode == 27)
        HideRightMenu();
    else if (e.keyCode == 113)
        ShowRightMenu();
});

function replaceAll(find, replace, str) {
    return str.replace(new RegExp(find, 'g'), replace);
  }

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
        c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
        }
    }
    return "";
}

  function toPersian(text) {
    text = text.toString();
    text = replaceAll("0", "۰", text);
    text = replaceAll("1", "۱", text);
    text = replaceAll("2", "۲", text);
    text = replaceAll("3", "۳", text);
    text = replaceAll("4", "۴", text);
    text = replaceAll("5", "۵", text);
    text = replaceAll("6", "۶", text);
    text = replaceAll("7", "۷", text);
    text = replaceAll("8", "۸", text);
    text = replaceAll("9", "۹", text);

    return text;
}

function toEnglish(text) {
    text = text.toString();
    text = replaceAll("۰", "0", text);
    text = replaceAll("۱", "1", text);
    text = replaceAll("۲", "2", text);
    text = replaceAll("۳", "3", text);
    text = replaceAll("۴", "4", text);
    text = replaceAll("۵", "5", text);
    text = replaceAll("۶", "6", text);
    text = replaceAll("۷", "7", text);
    text = replaceAll("۸", "8", text);
    text = replaceAll("۹", "9", text);

    return text;
}

  function CheckDay(day)
  {
      day = parseInt(day);
      if (day > 31 || day <= 0)
          return false;
  
      return true;
  }
  
  function CheckMonth(month)
  {
      month = parseInt(month);
      if (month > 12 || month <= 0)
          return false;
  
      return true;
  }
  
  function CheckYear(year) {
      year = parseInt(year);
      if (year < 1200 || year >= 1500)
          return false;
  
      return true;
  }
  
  function CheckHour(hour)
  {
      hour = parseInt(hour);
      if (hour >= 24 || hour < 0)
          return false;
  
      return true;
  }
  
  function CheckMin(min) {
      min = parseInt(min);
      if (min >= 60 || min < 0)
          return false;
  
      return true;
  }
  
  
  function CheckTime(time) {
      try {
          if (time.indexOf(':') == -1) {
              time = time.substring(0, 2) + ':' + time.substring(2, 4);
          }
  
          time = toEnglish(time);
  
          if (time.length != 5) {
              return false;
          }
  
          if (!CheckHour(parseInt(time.substring(0, 2))))
              return false;
  
          if (!CheckMin(parseInt(time.substring(3, 5))))
              return false;
      }
      catch (Err) {
          return false;
      }
  
      return true;
  }
  
  function CheckDate(date, EmptyTrue) {
      try {
          if (date.indexOf('/') == -1) {
              date = date.substring(0, 4) + '/' + date.substring(4, 6) + "/" + date.substring(6, 8);
          }
  
          date = toEnglish(date);
  
          if (date.length != 10) {
              if (date.length == 0)
                  return EmptyTrue;
              return false;
          }
  
  
          if (!CheckYear(parseInt(date.substring(0, 4))))
              return false;
  
          if (!CheckMonth(parseInt(date.substring(5, 7))))
              return false;
  
          if (!CheckDay(parseInt(date.substring(8, 10))))
              return false;
      }
      catch (e) {
          console.log(e);
         if (date.trim() == "")
              return EmptyTrue;
  
          return false;
      }
  
      return true;
  }
  

function toPersian(text) {
    text = text.toString();
    text = replaceAll("0", "۰", text);
    text = replaceAll("1", "۱", text);
    text = replaceAll("2", "۲", text);
    text = replaceAll("3", "۳", text);
    text = replaceAll("4", "۴", text);
    text = replaceAll("5", "۵", text);
    text = replaceAll("6", "۶", text);
    text = replaceAll("7", "۷", text);
    text = replaceAll("8", "۸", text);
    text = replaceAll("9", "۹", text);

    return text;
}

var headerHeight = 0;

function ShowRightMenu() {
    $('#back').css('display', 'block');
    $('#rmenu').css('right', '0px');
}

function HideRightMenu() {
    $('#back').css('display', 'none');
    $('#rmenu').css('right', '-' + $('#rmenu').css('width'));
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function GetPhone(mobile)
{
    try {
        mobile = str_replace("+", "", mobile);
    }
    catch (er){
    }

    mobile = mobile.trim();

    while (true)
    {
        if (mobile[0] == '0')
            mobile = mobile.substring(1);
        else
            break;
    }

    if (mobile.substring(0, 2) == "98")
        mobile = mobile.substring(2);

    while (true)
    {
        if (mobile[0] == '0')
            mobile = mobile.substring(1);
        else
            break;
    }

    return mobile;
}


function CheckPhone(Phone)
{
    try
    {
        Phone = GetPhone(Phone);

        while (Phone[0] == '0' || Phone[0] == '+')
            Phone = Phone.substring(1);
        
        if (isNaN(parseFloat(Phone)))
            return false;

        if (Phone.substring(0, 2) == "98")
        {
            if (Phone.length != 12)
                return false;
        }
        else
        {
            if (Phone[0] != '9')
                return false;

            if (Phone.length != 10)
                return false;
        }

    }
    catch (Exception)
    {
        return false;
    }

    return true;

}

function CheckTel(tel) {
    if (tel.trim() == '')
        return true;

    if ($.isNumeric(tel) == false) 
        return false;

    return true;
}

function LoadStates(obj, id) {
    $('#' + id).attr('disabled', '');

    $.post(SiteUrl + '/main/load_states',
            { id: $(obj).val() },
            function(data) {
                $('#' + id).html(data);
            }).always(function() {
                $('#' + id).removeAttr('disabled');
            });
}

function LoadCities(obj, id) {
    $('#' + id).attr('disabled', '');

    $.post(SiteUrl + '/main/load_cities',
            { id: $(obj).val() },
            function(data) {
                $('#' + id).html(data);
            }).always(function() {
                $('#' + id).removeAttr('disabled');
            });
}

function LoadAreas(obj, id) {
    $('#' + id).attr('disabled', '');

    $.post(SiteUrl + '/main/load_areas',
            { id: $(obj).val() },
            function(data) {
                $('#' + id).html(data);
            }).always(function() {
                $('#' + id).removeAttr('disabled');
            });
}

function LoadBlocks(obj, id) {
    $('#' + id).attr('disabled', '');

    $.post(SiteUrl + '/main/load_blocks',
            { id: $(obj).val() },
            function(data) {
                $('#' + id).html(data);
            }).always(function() {
                $('#' + id).removeAttr('disabled');
            });
}

function random_int(min, max) {
    let r = Math.random();

    r = parseInt(r * max) + min;

    return r;
}

function createRandomColor() {
    let R = random_int(0, 255);
    let G = random_int(0, 255);
    let B = random_int(0, 255);

    R = R.toString(16);
    G = G.toString(16);
    B = B.toString(16);

    if (R.length == 1)
        R = '0' + R;

    if (G.length == 1)
        G = '0' + G;

    if (B.length == 1)
        B = '0' + B;



    return R + G + B;
}

function GetElementByTooltip(el, tooltip, data) {
    return '<' + el + ' data-toggle="tooltip" title="' + tooltip + '">' + data + '</' + el + '>';
}

function ChangeProgress(id, value) {
    value = parseInt(value);
    if (isNaN(value))
        value = 0;
    if (value < 0)
        value = 0;
    if (value > 100)
        value = 100;

    $('#' + id).css('background', 'linear-gradient(90deg, #1B5E20 0%, #1B5E20 ' + value + '%, #ffffff ' + (value + 2) + '%, #ffffff 100%)');
}

function ViewVisitResult(id) {
    ShowLoading();

    $.post(SiteUrl + '/sales_manager/get_visit_details',
           { id: id},
           function(data) {
            if (data.Result) {
                $('#modal-view-content .modal-title').html('مشاهده نتیجه ویزیت');
                $('#modal-content').html(data.Data);
                UIkit.modal('#modal-view-content').show();
            }
            else {
                ShowError(data.Message);
            }
           }, 'json').fail(function() {
            ShowError('اتصال به سرور برقرار نشد.');
           }).always(function() {
            HideLoading();
           });
}

/************************************* Customers  **************************************/

var CID = 0;
var CurrentCustomer = null;
var Persons = [];
var CurrentPerson = null;
var CurrentIndex = 0;

function NewCustomer(onload, extra) {
    CurrentCustomer = null;
    CID = 0;
    Persons = [];
    EditCustomer = true;
    CurrentIndex = 0;
    $('#btnEditCustomer').addClass('hidden');


    get_customer(0, 0, onload, null, extra);
}

function ViewCustomer(index, onloaded, onclosed) {
    CurrentCustomer = data[index];
    CID = CurrentCustomer.ID;
    Persons = [];
    EditCustomer = false;
    TempID = 0;
    CurrentIndex = index;
    if (CID == 0)
        $('#btnEditCustomer').addClass('hidden');
    else
        $('#btnEditCustomer').removeClass('hidden');

    get_customer(CID, 0, onloaded);
}

function ChangeCustomerOrdersTab(obj) {
    let page = $(obj).attr('page');

    $('.customer-orders-tab').removeClass('active');
    $(obj).addClass('active');

    $('.customer-order-page').addClass('uk-hidden');
    $('#' + page).removeClass('uk-hidden');

    if (page == 'dListCustomerOrders') {
        if ($('#gCustomerOrders').attr('data-loaded') == '0') {
            $('#gCustomerOrders').attr('data-loaded', '1');
            
            $("#gCustomerOrders").bootgrid({
                ajax: true,
                columnSelection: false,
                post: function ()
                {
                    /* To accumulate custom parameter with the request object */
                    return {
                        az: $('#txtOrdersAz').val(),
                        ta: $('#txtOrdersTa').val(),
                        uid: CID
                    };
                },
                url: SiteUrl + '/orders/get_list',
                formatters: {
                'Tarikh': function(col, row) {
                    return row.ShamsiDate + ' ' + row.CreateDate.substring(10);
                },
                'Noe': function(col, row) {
                    if (row.IsBuy == 1 && row.IsReturn == 0)
                        return 'خرید';
                    else if (row.IsBuy == 1 && row.IsReturn == 1)
                        return 'برگشت خرید';
                    else if (row.IsBuy == 0 && row.IsReturn == 1)
                        return 'برگشت فروش';
                    else
                        return 'فروش';                    
                },
                'AllPrice': function(col, row) {
                    return numeral(row.AllPrice).format('0,0');
                },
                'Options': function(col, row) {
                    var res = '';

                    res += '<button class="btn btn-default inline-button" title="مشاهده فاکتور" data-toggle="tooltip" onclick="ViewOrder(' + row.ID + ');"><i class="fas fa-search"></i></button>';

                    return res;
                }
                }
            }).on("load.rs.jquery.bootgrid", function (e)
            {
            }).on("loaded.rs.jquery.bootgrid", function(e) {
                $('[data-toggle="tooltip"]').tooltip();
            });   

        }
    }
}

function ViewTempCustomer(id) {
    ShowLoading();

    $.post(SiteUrl + '/customers/get_temp_customer',
          { id: id},
          function(data) {
            if (data.Result) {
                $('#modal-content').html(data.Data);
                $('#modal-view-content .modal-title').html('مشتری موقت');
                UIkit.modal('#modal-view-content').show();
            }
            else {
                ShowError(data.Message);
            }
          }, 'json').fail(function() {
            ShowError('اتصال به سرور برقرار نشد.');
          }).always(function() {
            HideLoading();
          });
}

function ViewCallData(id) {
    ShowLoading();

    $.post(SiteUrl + '/tel_tour/get_call_data',
          { id: id},
          function(data) {
            if (data.Result) {
                $('#modal-content').html(data.Data);
                $('#modal-view-content .modal-title').html('نتیجه تماس');
                UIkit.modal('#modal-view-content').show();
            }
            else {
                ShowError(data.Message);
            }
          }, 'json').fail(function() {
            ShowError('اتصال به سرور برقرار نشد.');
          }).always(function() {
            HideLoading();
          });
}

function ViewCustomerById(id, onclosed) {
    CID = id;

    if (CID == 0)
        $('#btnEditCustomer').addClass('hidden');
    else
        $('#btnEditCustomer').removeClass('hidden');

    get_customer(CID, 0, null, onclosed);
}

function EditCust(index, onloaded, onclosed) {
    CurrentCustomer = data[index];
    CID = CurrentCustomer.ID;
    Persons = [];
    EditCustomer = true;
    TempID = 0;
    CurrentIndex = index;
    $('#btnEditCustomer').addClass('hidden');

    get_customer(CID, 1, onloaded, onclosed);
}

function EditCustById(id, onloaded, onclosed) {
    CID = id;
    Persons = [];
    EditCustomer = true;
    TempID = 0;
    CurrentIndex = -1;
    $('#btnEditCustomer').addClass('hidden');

    get_customer(CID, 1, onloaded, onclosed);
}

function DeleteCustomer(index) {
    var item = data[index];

    confirm('حذف مشتری', 'آیا از حذف مشتری "' + item.ShopName + '" اطمینان دارید؟', function() {
        Post(SiteUrl + '/customers/del_customer', { id: item.ID }, function (data) {
            $('#grid').bootgrid('reload');
            ShowSuccess(data.Message);            
        });
    });
}


/************************************* Customers  **************************************/



function AddInputError(id, message) {
    RemoveInputError(id);

    $('#' + id).addClass('uk-form-danger');

    var html = '<div class="alert alert-danger" style="margin-bottom: 5px;">' + message + '</div>';

    try {
        $('#' + id).parent().append(html);
    }
    catch (err) {
        console.log(err);
    }
}

function RemoveInputError(id) {
    $('#' + id).removeClass('uk-form-danger');

    $('#' + id).parent().find('.alert-danger').remove();
}

function ClearForm(id) {
    $(id + ' .form-check').each(function(index, obj) {
        $(obj).off('change');

        $(obj).attr('changed-set', '0');

        RemoveInputError($(obj).attr('id'));
    });
}

function validURL(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
      '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
      '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
      '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
      '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
      '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    return !!pattern.test(str);
}



function CheckForm(fid) {
    var error = false;

    var data = {};

    $(fid + ' .form-check').each(function(index, obj) {
        var role = $(obj).attr('role');
        var val = toEnglish($(obj).val());
        data[$(obj).attr('name')] = val;

        if ($(obj).prop("tagName") == 'SELECT') {
            data[$(obj).attr('name') + '_text'] = $('#' + $(obj).attr('id') + ' option:selected').text();
        }

        if (role !== undefined && role != '') {
            var roles = role.split('|');
            var name = $(obj).attr('role-name');
            var id = $(obj).attr('id');

            if ($(obj).attr('changed-set') != '1') {
                $(obj).on('change', function() {
                    CheckForm(fid);
                });

                $(obj).on('blur', function() {
                    CheckForm(fid);
                });

                $(obj).attr('changed-set', '1');
            }

            for (var i = 0; i < roles.length; i++) {
                switch (roles[i].trim()) {
                    case 'required':
                        if (val.trim() == '') {
                            error = true;
                            AddInputError(id, name + ' را وارد نمایید.');
                            return;
                        }
                        break;
                    case 'tel':
                        if (CheckTel(val) == false) {
                            error = true;
                            AddInputError(id, name + ' وارد شده نامعتبر است.');
                            return;
                        }
                        break;
                    case 'phone':
                        if (CheckPhone(val) == false && val.trim() != '') {
                            error = true;
                            AddInputError(id, name + ' وارد شده نامعتبر است.');
                            return;
                        }
                        break;
                    case 'email':
                        if (validateEmail(val) == false && val.trim() != '') {
                            error = true;
                            AddInputError(id, name + ' وارد شده نامعتبر است.');
                            return;
                        }
                        break;
                    case 'url':
                        if (validURL(val) == false && val.trim() != '') {
                            error = true;
                            AddInputError(id, name + ' وارد شده نامعتبر است.');
                            return;
                        }
                        break;
                    case 'date':
                        if (CheckDate(val, true) == false) {
                            error = true;
                            AddInputError(id, name + ' وارد شده معتبر نیست.');
                            return;
                        }
                        break;
                }
            }
        }

        RemoveInputError(id);
    });

    if (error)
        return false;
    else
        return data;


}



/***************************************** Map  ******************************** */


var map = null;
var LocIcon = null;
var marker = null;
var map_editable = false;
function SelectMap(hid, id, editable, view_map, just_get_location) {
    if (just_get_location === undefined)
        just_get_location = !SelectLocation;

    if (view_map === undefined)
        view_map = true;

    if (just_get_location)
        editable = false;

    map_editable = editable;
    if (view_map)
        UIkit.modal('#modal-view-map').show();

    if (map == null && view_map) {
        map = new L.Map('map', {
            key: 'web.AgDsRCKODrsiSkXMkgpZ1MZni5obWQyP3YCkCrtB',
            maptype: 'dreamy',
            poi: true,
            traffic: false,
        
        }).setView([36.27673191955526,59.60503578186035], 13);

        /*  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ''
        }).addTo(map);*/

        LocIcon = L.icon({
            iconUrl: BaseUrl + 'assets/img/location.svg',
            iconSize: [48, 48], 
            popupAnchor: [0, 0]
            });
    
        map.on('click', function(e) {
            if (map_editable) {
                var v = e.latlng.lat + ',' + e.latlng.lng;
                addMarker(e.latlng);

                $('#' + hid).val(v);
                $('#' + id).html(v);
            }
        });
    }
    else {

    }

    if (marker != null) {
        map.removeLayer(marker);
    }

    var value = $('#' + hid).val();
    if (value) {
        var v = value.split(',');

        if (map != null) {
            map.setView(L.latLng(v[0], v[1]));

            addMarker(L.latLng(v[0], v[1]));
        }
    }

    if (just_get_location) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var v = position.coords.latitude + ',' + position.coords.longitude;

            $('#' + hid).val(v);
            $('#' + id).html(v);

            if (map != null) {
                addMarker(L.latLng(position.coords.latitude, position.coords.longitude));
                map.setView(L.latLng(position.coords.latitude, position.coords.longitude));
            }

        }, function() {
            ShowError('دسترسی به موقعیت شما مقدور نمی باشد');
        });
    }


}

function addMarker(v) {
    if (marker != null) {
        map.removeLayer(marker);
    }

    marker = L.marker(v).addTo(map);
}

/***************************************** Map  ******************************** */


function BoxDateSelect(obj, script) {
    var id = $(obj).attr('data-id');
    var box = $(obj).attr('data-box');
    var val = $(obj).val();
    console.log(id);

    if (val == '0') {
        $('#' + box).removeClass('disabled');
        $('#' + box).removeClass('hidden');
    }
    else {
        $('#' + box).addClass('disabled');
        $('#' + box).addClass('hidden');
        $('#' + id).val(val);
    }

    eval(script);
}

function RefreshCustomerOrders() {
    CreateChart('آمار تعدادی برند', SiteUrl + '/customers/orders_chart', { id: CID, az: $('#txtOrdersAz').val(), ta: $('#txtOrdersTa').val(), t: 'brand_count' }, 'dCustomerOrdersBrandCounts');
    CreateChart('آمار مبلغی برند', SiteUrl + '/customers/orders_chart', { id: CID, az: $('#txtOrdersAz').val(), ta: $('#txtOrdersTa').val(), t: 'brand_price' }, 'dCustomerOrdersBrandPrice');
    CreateChart('آمار تعدادی کالا', SiteUrl + '/customers/orders_chart', { id: CID, az: $('#txtOrdersAz').val(), ta: $('#txtOrdersTa').val(), t: 'item_count' }, 'dCustomerOrdersItemsCounts');
    CreateChart('آمار مبلغی کالا', SiteUrl + '/customers/orders_chart', { id: CID, az: $('#txtOrdersAz').val(), ta: $('#txtOrdersTa').val(), t: 'item_price' }, 'dCustomerOrdersItemsPrice');
    try {
        if ($('#gCustomerOrders').attr('data-loaded') == '1')
            $('#gCustomerOrders').bootgrid('reload');
    }
    catch (err){}
}

function ToggleOrderChartsSize(obj) {
    if ($(obj).attr('data-wide') == '1') {
        $('.orders-chart').removeClass('uk-width-1-1@m');
        $('.orders-chart').addClass('uk-width-1-2@m');
        $(obj).attr('data-wide', '0');
    }
    else {
        $('.orders-chart').removeClass('uk-width-1-2@m');
        $('.orders-chart').addClass('uk-width-1-1@m');
        $(obj).attr('data-wide', '1');
    }

    RefreshCustomerOrders();
}

function RefreshMoein() {
    /*if (chart_data['dMoein']) {
        var d = chart_data['dMoein'];

        d.Data.az = $('#txtMoeinAz').val();
        d.Data.ta = $('#txtMoeinTa').val();

        RefreshChart('dMoein');
    }
    else {*/
        CreateChart('اعتبار معین مشتری', SiteUrl + '/customers/moein_chart', { id: CID, az: $('#txtMoeinAz').val(), ta: $('#txtMoeinTa').val() }, 'dMoein');

        if ($('#gMoein').attr('data-created') == '0') {
            $('#gMoein').attr('data-created', '1');

            $("#gMoein").bootgrid({
                ajax: true,
                columnSelection: false,
                sorting: false,
                post: function ()
                {
                    /* To accumulate custom parameter with the request object */
                    return {
                        id: CID, 
                        az: $('#txtMoeinAz').val(), 
                        ta: $('#txtMoeinTa').val() 
                    };
                },
                url: SiteUrl + "/customers/get_moein_trans",
                formatters: {
                    "Options": function(columns, row) {
                        var res = '';


                        return res;
                    },
                    'Bed': function(col, row) {
                        if (row.Credit > 0)
                            return 0;
                        else
                            return numeral(row.Credit * -1).format('0,0');
                    },
                    'Bes': function(col, row) {
                        if (row.Credit < 0)
                            return 0;
                        else
                            return numeral(row.Credit).format('0,0');
                    },
                    'Mande': function(col, row) {
                        return numeral(row.Mande).format('0,0');
                    },
                    'Tashkhis': function(col, row) {
                        if (row.Mande > 0) 
                            return '<div class="alert alert-success">بستانکار</div>';
                        else if (row.Mande < 0)
                            return '<div class="alert alert-danger">بدهکار</div>';
                        else 
                            return '<div class="alert alert-info">تسویه</div>';
                    }
                }
            })
        }
        else {
            $('#gMoein').bootgrid('reload');
        }

        $('#dMoein-Kol').html('<div class="uk-text-center"><img src="' + BaseUrl + '/assets/img/charts.gif" /></div>');

        Post(SiteUrl + '/customers/get_moein_kol', { id: CID, az: $('#txtMoeinAz').val(), ta: $('#txtMoeinTa').val() }, function(data) {
            var html = '';

            html += '<div class="uk-grid" uk-grid>';
            html += '<div class="uk-width-1-3">';
            html += 'جمع بدهکار: ';
            html += '<div class="form-info">' + numeral(data.Data.Bed).format('0,0') + '</div>';
            html += '</div>';
            html += '<div class="uk-width-1-3">';
            html += 'جمع بستانکار: ';
            html += '<div class="form-info">' + numeral(data.Data.Bes).format('0,0') + '</div>';
            html += '</div>';
            html += '<div class="uk-width-1-3">';
            html += 'مانده: ';
            html += '<div class="form-info" style="color: ' + (data.Data.Mande < 0 ? 'red' : 'green') + '">' + numeral(data.Data.Mande).format('0,0') + ' ' /*+ (data.Data.Mande == 0 ? '' : (data.Data.Mande < 0 ? '(بد)' : '(بس)'))*/ + '</div>';
            html += '</div>';
            html += '</div>';

            $('#dMoein-Kol').html(html);
        }, true, function() { }, function() { $('#dMoein-Kol').html(''); });
    //}
}




/**********************************    Chart   ********************************************/
function sleep( sleepDuration ){
    var now = new Date().getTime();
    while(new Date().getTime() < now + sleepDuration){ /* do nothing */ } 
}

var chart_data = {};

function CreateChart(title, url, data, box) {
    var d = {
        Url: url,
        Data: [ data ], 
        Box: box,
        Title: title
    };

    chart_data[box] = d;

    RefreshChart(box);
}

var _config;
var ch = null;
function RefreshChart(box) {
    console.log(box);

    var chart = chart_data[box];

    if (chart.Chart != null)
        chart.Chart.destroy();

    $('#' + box).html('<div class="chart-error uk-hidden" id="chart-error-' + box + '"><div class="alert alert-danger">عملیات با خطا متوقف شد.</div><br><br><br><br><div class="uk-text-center"><button class="btn btn-danger" onclick="RefreshChart(\'' + box + '\');">بارگذاری مجدد</button></div></div><div id="' + box + '-loading" class="uk-text-center"><h4 class="uk-text-center">درحال بارگذاری...<br><br><br></h4><img src="' + BaseUrl + 'assets/img/charts.gif' + '" /></div><div id="' + box + '-title" class="chart-titles hidden"></div><div id="' + box + '-canvas"></div><div id="' + box + '-pagination" class="chart-pagination"></div>');


    if (typeof chart.Url === 'function') {
        try {
            CheckRefreshChart(chart, chart.Url(chart.Data[chart.Data.length - 1]));
        }
        catch (err) {
            $('#chart-error-' + box + ' .alert').html('اتصال به سرور برقرار نشد.'); 
            $('#chart-error-' + box).removeClass('uk-hidden'); 
        }

        $('#' + box + '-loading').addClass('hidden'); 
    }
    else {
        $.post(chart.Url, chart.Data[chart.Data.length - 1], function(data) {
            CheckRefreshChart(chart, data);
        }, 'json').always(function() { 
            $('#' + box + '-loading').addClass('hidden'); 
        }).fail(function () { 
            $('#chart-error-' + box + ' .alert').html('اتصال به سرور برقرار نشد.'); 
            $('#chart-error-' + box).removeClass('uk-hidden'); 
        });
    }

}

function GetRandomColor() {
    var color = '#';

    var r = Math.floor(Math.random() * 256).toString(16);
    var g = Math.floor(Math.random() * 256).toString(16);
    var b = Math.floor(Math.random() * 256).toString(16);

    if (r.length == 1)
        r = '0' + r;

    if (g.length == 1)
        g = '0' + g;

    if (b.length == 1)
        b = '0' + b;

    color += r + g + b;
    
    return color;
}

function CheckRefreshChart(chart, data) {
    var box = chart.Box;
    if (data.Result) {
        data = data.Data;
        var type = 'bar';
        type = data.Type ? data.Type : 'bar'

        chart.Data[chart.Data.length - 1].Title = data.Title;

        var labels = [];
        var count = data.Labels.length;

        if (data.CountPerPage && data.CountPerPage < data.Labels.length) {
            count = data.CountPerPage;
            chart.CurrentPage = 1;
            chart.CountPerPage = data.CountPerPage;
            chart.Pages = parseInt(data.Labels.length / count + (data.Labels.length % count == 0 ? 0 : 1 ));
            console.log(count);
        }
        else {
            chart.Pages = 0;
            chart.CurrentPage = 0;
        }

        var series = [];

        if (type == 'pie') {
            series = data.Data;
        }
        else {
            for (var i = 0; i < data.Data.length; i++) {
                var item = data.Data[i];
                series[series.length] = {
                    name: item.name,
                    data: []
                };
            }
        }

        for (var i = 0; i < count; i++) {
            var item = data.Labels[i];

            if (item.length > 10) {
                var a = [];

                var index = 0;
                for (var j = 0; j < item.length; j++) {
                    if (item[j] == ' ') {
                        if (j - index >= 10) {
                            var it = item.substring(index, j).trim();
                            if (it != '')
                                a[a.length] = it;
                            index = j;
                        }
                    }
                }

                if (index < item.length) {
                    var it = item.substring(index, item.length).trim();

                    if (it != '')
                        a[a.length] = it;
                }

                var t = [];
                for (var l = a.length - 1; l >= 0; l--)
                    t[t.length] = a[l];

                labels[labels.length] = t;
            }
            else {
                labels[labels.length] = item;
            }

            if (type != 'pie') {
                for (var j = 0; j < data.Data.length; j++) {       
                    series[j].data[series[j].data.length] = data.Data[j].data[i];
                }
            }
        }

        var hasDetails = false;
        if (data.HasDetails !== undefined)
            hasDetails = data.HasDetails;

        if (hasDetails)
            hasDetails = true;
        else
            hasDetails = false;

        

        var options = {
            series: series,
            chart: {
                type: type,
                height: $('#' + box).innerHeight(),
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        console.log(config);
                        if (chart.ClickData.Enable && chart.ClickData.Type === undefined) {
                            
                            
                            var d = chart.ClickData.Data[config.dataPointIndex];

                            var data = JSON.parse(JSON.stringify(chart.Data[chart.Data.length - 1]));
                            

                            data[chart.ClickData.Name] = d;

                            chart.Data[chart.Data.length] = data;

                            RefreshChart(box);
                        }
                    }
                }
            },
            title: {
                text: data.Title,
                align: 'center'
            },
            dataLabels: {
                enabled: true,
                position: 'top',
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                },            
                formatter: function (val) {
                    if (data.ValueisNumber) {
                        return numeral(val).format('0,0')
                    }
                    else
                        return val;
                }
            },
            plotOptions: {
                bar: {
                horizontal: false,
                columnWidth: '20%',
                dataLabels: {
                    position: 'top'
                }
                /*endingShape: 'rounded'*/
                },
            },
            stroke: {
                show: true,
                width: 2,
                colors: data.Colors
            },
            labels: labels,
            /*xaxis: {
                categories: data.Labels,
            },*/
            yaxis: {
                /*title: {
                text: '$ (thousands)'
                }*/
                labels: {
                    align: 'center',
                    formatter: function (val) {
                        if (data.ValueisNumber) {
                            return numeral(val).format('0,0')
                        }
                        else
                            return val;
                    }
                }
            },
            fill: {
                opacity: 1,
                colors: data.Colors
            },
            xaxis: {
                labels: {
                rotate: 0
                }
            },
            colors: data.Colors,
            markers: {
                colors: data.Colors
            },
            tooltip: {
                y: {
                formatter: function (val) {
                    if (data.ValueisNumber) {
                        return numeral(val).format('0,0')
                    }
                    else
                        return val;
                }
                }
            },
            HasDetails: hasDetails,
            FileName: data.FileName ? data.FileName : undefined,
            PostData: chart.Data[chart.Data.length - 1],
            Url: chart.Url
        };

        if (chart.Chart != null)
            chart.Chart.destroy();

        chart.Chart = null;
        
        chart.Chart = new ApexCharts(document.querySelector("#" + box + '-canvas'), options);

        chart.Series = data;

        chart.Chart.render();

        chart.ClickData = data.Click;

        if (chart.Data.length > 1) {
            var html = '';

            for (var i = 0; i < chart.Data.length; i++) {
                var item = chart.Data[i];

                if (i > 0)
                    html += ' -> ';

                html += '<a ';

                if (i != chart.Data.length - 1) 
                    html += ' href="javascript:" onclick="BackChart(\'' + box + '\', ' + i + ');" ';

                html += '>' + item.Title + '</a>';
            }

            $('#' + box + '-title').removeClass('hidden');
            $('#' + box + '-title').html(html);
        }
        else {
            $('#' + box + '-title').addClass('hidden');
        }

        ChartClickHandle(chart);

        if (chart.Pages && chart.Pages > 1) {
            CreateChartPagination(chart);
        }
        else {
            $('#' + box + '-pagination').addClass('uk-hidden');
        }
    }
    else {
        $('#chart-error-' + box + ' .alert').html(data.Message); 
        $('#chart-error-' + box).removeClass('uk-hidden');     
    }

}

function ChartClickHandle(chart) {
    try {
        if (chart.ClickData.Enable == true) {
            for (var i = 0; i < chart.Series.Labels.length; i++) {
                try {
                    var item = chart.Series.Labels[i];

                    var obj = $('#' + chart.Box + ' text.apexcharts-text tspan:textEquals("' + item + '")').parent();

                    $(obj).addClass('chart-label');
                    $(obj).attr('data-id', chart.ClickData.Data[i]);

                    $(obj).click(function() {
                        var id = $(this).attr('data-id');

                        var data = JSON.parse(JSON.stringify(chart.Data[chart.Data.length - 1]));

                        data[chart.ClickData.Pars] = id;
                        data['type'] = chart.ClickData.Type;

                        chart.Data[chart.Data.length] = data;
                        RefreshChart(chart.Box);
                    });
                }
                catch (err) {console.log(err);}
            }
        }
    }
    catch (err) {}

}

function ChartPage(box, page) {
    var chart = chart_data[box];

    if (page >= 1 && page <= chart.Pages) {
        chart.CurrentPage = page;

        var labels = [];
        var count = chart.Series.Labels.length;

        var series = [];

        for (var i = 0; i < chart.Series.Data.length; i++) {
            var item = chart.Series.Data[i];
            series[series.length] = {
                name: item.name,
                data: []
            };
        }

        for (var i = (page - 1) * chart.CountPerPage; i < page * chart.CountPerPage && i < chart.Series.Labels.length; i++) {
            var item = chart.Series.Labels[i];

            if (item.length > 10) {
                var a = [];

                var index = 0;
                for (var j = 0; j < item.length; j++) {
                    if (item[j] == ' ') {
                        if (j - index >= 10) {
                            var it = item.substring(index, j).trim();
                            if (it != '')
                                a[a.length] = it;
                            index = j;
                        }
                    }
                }

                if (index < item.length) {
                    var it = item.substring(index, item.length).trim();

                    if (it != '')
                        a[a.length] = it;
                }

                var t = [];
                for (var l = a.length - 1; l >= 0; l--)
                    t[t.length] = a[l];

                labels[labels.length] = t;
            }
            else {
                labels[labels.length] = item;
            }

            for (var j = 0; j < chart.Series.Data.length; j++) {       
                series[j].data[series[j].data.length] = chart.Series.Data[j].data[i];
            }
        }

        chart.Chart.updateOptions({
            series: series,
            labels: labels
        });

        CreateChartPagination(chart);

        ChartClickHandle(chart);

    }
}

function CreateChartPagination(chart) {
    var html = '<div class="btn-group" role="group">';

    html += '<button type="button" class="btn ' + (chart.CurrentPage == 1 ? 'btn-warning disabled"' : 'btn-primary" onclick="ChartPage(\'' + chart.Box + '\', 1);"') + '>1</button>';

    var page = 2;
    if (chart.CurrentPage > 3 && chart.Pages > 5) {
        page = chart.CurrentPage - 4;
        if (page <= 1)
            page = 2;
        html += '<button type="button" class="btn btn-primary" onclick="ChartPage(\'' + chart.Box + '\', ' + page + ');">...</button>';
        page++;
    }

    var end = chart.CurrentPage + 3;
    if (end > chart.Pages - 1)
        end = chart.Pages - 1;

    for (; page <= end; page++) {
        html += '<button type="button" class="btn ' + (chart.CurrentPage == page ? 'btn-warning disabled"' : 'btn-primary" onclick="ChartPage(\'' + chart.Box + '\', ' + page + ');"') + '">' + page + '</button>';
    }

    if (page != chart.Pages) {
        html += '<button type="button" class="btn btn-primary" onclick="ChartPage(\'' + chart.Box + '\', ' + page + ');">...</button>';
    }

    html += '<button type="button" class="btn ' + (chart.CurrentPage == chart.Pages ? 'btn-warning disabled"' : 'btn-primary" onclick="ChartPage(\'' + chart.Box + '\', ' + chart.Pages + ');"') + '>' + chart.Pages + '</button>';


    html += '</div>';

    $('#' + chart.Box + '-pagination').html(html);

    $('#' + chart.Box + '-pagination').removeClass('uk-hidden');

}

function BackChart(box, index) {
    var temp = [];
    var chart = chart_data[box];

    for (var i = 0; i <= index; i++) {
        temp[temp.length] = chart.Data[i];
    }

    chart.Data = temp;

    RefreshChart(box);
}

/**********************************    Chart   ********************************************/



/**********************************    Show Products ********************************************/

var Offers = null;
var OrderID = 0;
var BaseFilters = null;
var _isReturn = false;
var _IsBuy = false;
var prices = '';
var pTakhfifs = [];
var customGifts = [];
var new_prices = [];
var _order_tozihat = '';
var order_editable = false;

async function ShowProducts(index, cid, name, order, basket_items, OrderData, isReturn, isBuy) {
    _tax = 0;
    _trial = -1;
    _tpercent = -1;
    _ttype = 0;
    _enabled_gifts = '';
    _basket_gifts = '';
    _visitor = 0;
    _visitor2 = 0;
    _manager = 0;
    pTakhfifs = [];
    customGifts = [];
    new_prices = [];
    Visitors = 0;
    Managers = 0;
    _order_tozihat = '';
    order_editable = true;

    _prices = '';

    if (isReturn === undefined)
        isReturn = false;

    if (isBuy === undefined)
        isBuy = false;

    _isReturn = isReturn;
    _isBuy = isBuy;

    if (BaseFilters == null) {
        BaseFilters = $('#dBaseFilters').html();

        $('#dBaseFilters').remove();
    }
    if (basket_items === undefined || basket_items == null)
        basket_items = [];

    
    if (OrderData !== undefined && OrderData != null) {
        if (OrderData.OrderState >= 2)
            order_editable = false;

        for (let i = 0; i < OrderData.Basket.length; i++) {
            let item = OrderData.Basket[i];

            if (item.TakhfifPercent != 0 && item.TakhfifRial != 0 && item.TakhfifType != 0) {
                pTakhfifs[pTakhfifs.length] = {
                    ID: item.ID,
                    Percent: item.TakhfifPercent,
                    Rial: item.TakhfifRial,
                    Type: item.TakhfifType
                };

                console.log(item);
            }

        }

        _order_tozihat = OrderData.Tozihat;

    }

    console.log(JSON.stringify(pTakhfifs));
    
        
    if (OrderData !== undefined && OrderData != null) {
        CurrentOrder = OrderData;
    
        $('#txtTakhfifPercent').val(OrderData.TakhfifPercent);
        $('#txtTakhfifRial').val(OrderData.TakhfifRial);

        for (let i = 0; i < OrderData.Items.length; i++) {
            let item = OrderData.Items[i];

            if (item.BasePrice != item.OldPrice && item.BasePrice != 0) {
                new_prices[new_prices.length] = {
                    ID: item.PMID,
                    Price: parseInt(item.OldPrice)
                }
            }

            if (item.Type == 2 && item.TakhfifID == -1) {
                customGifts[customGifts.length] = {
                    BasePrice: 0,
                    Count: parseInt(item.Count),
                    Name: item.Name,
                    PID: item.PID,
                    Takhfif: 0,
                    TakhfifPercent: 0,
                    Type: 3,
                    Custom: 1                            
                }
            }
        }
    }
    else
        CurrentOrder = null;
    
    CID = cid;
    OrderID = order;
    if (index >= 0) {
        CurrentCustomer = data[index];
    }
    else {
        CurrentCustomer = {
            ID: CID,
            Type: 0
        };
    }

    if (OrderID === undefined)
        OrderID = 0;

    $('.sCustomerName').html('<a href="javascript:" onclick="ViewCustomerById(' + CID + ');">' + name + '</a>' + (isBuy ? (isReturn ? ' - برگشت از خرید' : ' - خرید') : (isReturn ? ' - <span class="red">سفارش برگشت از فروش</span>' : '')));

    pOldCat = -1;
    $('#modal-catalog').modal({
        backdrop: 'static'
    });
    $('#modal-catalog').modal('show');
    $('#btnSettings').removeClass('btn-warning');

    $('#filters').html(BaseFilters);

    basket = [];
    RefreshBasket();

    /*if (order_editable == false)
        ToggleBasket();*/

    $('#dProducts').html('<div class="uk-text-center"><img src="' + BaseUrl + '/assets/img/loading.gif" /></div>');

    $.post(SiteUrl + '/products/index',
           { id: cid, return: _isReturn ? 1 : 0, buy: _isBuy ? 1 : 0},
           function(data) {
            if (data.Result) {
                $('#dProducts').html(data.Data.Html);

                try {
                    $('#dAllProducts').scroll(function() {
                      if ($('#tProducts').height() - $('#dAllProducts').scrollTop() < 450 && loading_products == false) {
                        loading_products = true;
                        LoadMoreProducts();
                      }
                    });
                }
                catch (err) {}
            
                Offers = data.Data.Offers;

                if (basket_items.length > 0) {
                    basket = basket_items;


                    if (OrderData !== undefined) {
                        for (let i = 0; i < basket.length; i++) {
                            let b = basket[i];


                            for (let j = 0; j < OrderData.Items.length; j++) {
                                let item = OrderData.Items[j];
                                if (item.Type == '1' && b.ID == item.PMID && item.BasePrice != item.OldPrice && item.BasePrice != 0) {
                                    if (item.OldPrice > 0)
                                        b.BasePrice = parseInt(item.OldPrice);
                                    b.OldPrice = parseInt(item.BasePrice);
                                }
                            }

                            basket[i] = b;
                        }

                        try {
                            if (OrderData.BasketGifts)
                                _basket_gifts = OrderData.BasketGifts;
                
                            if (OrderData.EnabledGifts)
                                _enabled_gifts = OrderData.EnabledGifts;
                        }
                        catch (err) {}
                    }
                
                    RefreshBasket();
                    RefreshProducts();

                    if (_ttype == 1)
                        ChangeTakhfifPercent();
                    else
                        ChangeTakhfifRial();
                }
                else
                    RefreshProducts();

                if (data.Data.Alarm != '') {
                    swal.fire({
                        title: 'توضیحات مشتری',
                        html: '<pre class="text-left">' + data.Data.Alarm + '</pre>',
                        icon: 'info',
                        confirmButtonText: '<i class="fa fa-thumbs-up"></i> تایید!'
                    });
                }
            }
            else {
                $('#dProducts').html('<div class="alert alert-danger">' + data.Message + '</div><div class="uk-margin uk-text-center"><button class="btn btn-danger" onclick="ShowProducts(-1, ' + cid + ', \'' + name + '\');">تلاش مجدد</button></div>');
            }
           }, 'json').always(function() {

           }).fail(function() {
            $('#dProducts').html('<div class="alert alert-danger">اتصال به سرور برقرار نشد.</div><div class="uk-margin uk-text-center"><button class="btn btn-danger" onclick="ShowProducts(-1, ' + cid + ', \'' + name + '\');">تلاش مجدد</button></div>');
           });

    $('#dSearch').addClass('uk-hidden');
    $('#btnSearch').removeClass('btn-warning');

    if (!$('#settings').hasClass('hidden'))
        ToggleSettings();

    if (!$('#filters').hasClass('hidden'))
        ToggleFilters();
}

function AddGiftItem(id) {
    let finded = false;
    let item = null;

    for (let i = 0; i < customGifts.length; i++) {
        item = customGifts[i];

        if (item.PID == id && item.Custom == 1) {
            finded = true;

            item.Count++;
            $('#dGifts_' + item.PID).val(item.Count);

            customGifts[i] = item;

            break;
        }
    }

    if (finded == false) {
        customGifts[customGifts.length] = {
            BasePrice: 0,
            Count: 1,
            Name: $('#tName_' + id).html(),
            PID: id,
            Takhfif: 0,
            TakhfifPercent: 0,
            Type: 3,
            Custom: 1
        }

        item = customGifts[customGifts.length - 1];

        $('#dGifts_' + id).val(item.Count);

    }

    UpdateGiftButtons(id, item);
    RefreshBasket();
}

function ChangeGiftCountItem(id) {
    let count = parseInt($('#dGifts_' + id).val());

    if (isNaN(count) || count < 0)
        count = 0;

    if (count == 0)
        DeleteGiftItem(id, false);
    else {
        let finded = false;
        let item = null;
    
        for (let i = 0; i < customGifts.length; i++) {
            item = customGifts[i];
    
            if (item.PID == id && item.Custom == 1) {
                finded = true;
    
                item.Count = count;
    
                customGifts[i] = item;
    
                break;
            }
        }
    
        if (finded == false) {
            customGifts[customGifts.length] = {
                BasePrice: 0,
                Count: count,
                Name: $('#tName_' + id).html(),
                PID: id,
                Takhfif: 0,
                TakhfifPercent: 0,
                Type: 3,
                Custom: 1
            }
    
            item = customGifts[customGifts.length - 1];
    
        }
    
        UpdateGiftButtons(id, item);
        RefreshBasket();
    
    }
}

function DeleteGiftItem(id, update) {
    if (update === undefined)
        update = true;

    let items = [];

    for (let i = 0; i < customGifts.length; i++) {
        let item = customGifts[i];

        if (item.PID != id)
            items[items.length] = item;
    }

    customGifts = items;
    RefreshBasket();

    if (update)
        $('#dGifts_' + id).val(0);
    $('#btnSubGift_' + id).addClass('disabled');
}

function SubGiftItem(id) {
    let item = null;

    for (let i = 0; i < customGifts.length; i++) {
        item = customGifts[i];

        if (item.PID == id && item.Custom == 1) {
            finded = true;

            item.Count--;
            $('#dGifts_' + item.PID).val(item.Count);

            customGifts[i] = item;

            UpdateGiftButtons(id, item);
            if (item.Count == 0)
                DeleteGiftItem(id);
            break;
        }
    }

    RefreshBasket();
}

function UpdateGiftButtons(id, item) {
    if (item === undefined) {
        for (let  i = 0; i < customGifts.length; i++) {
            if (customGifts[i].PID == id) {
                item = customGifts[i];
                break;
            }

        }
    }

    if (item !== undefined) {
        if (item.Count > 0)
            $('#btnSubGift_' + id).removeClass('disabled');
        else
            $('#btnSubGift_' + id).addClass('disabled');
    }
}

function ToggleSearch() {
    if ($('#dSearch').hasClass('uk-hidden')) {
        if (!$('#filters').hasClass('hidden')) 
        ToggleFilters();

        if (!$('#settings').hasClass('hidden')) 
            ToggleSettings();
        
        $('#dSearch').removeClass('uk-hidden');
        $('#btnSearch').addClass('btn-warning');
    }
    else {
        $('#dSearch').addClass('uk-hidden');
        $('#btnSearch').removeClass('btn-warning');
    }
}

function ToggleSettings() {
    if (!$('#filters').hasClass('hidden')) 
        ToggleFilters();

    if ($('#settings').hasClass('hidden')) {
        $('#btnSettings').addClass('btn-warning');
        $('#settings').removeClass('hidden');
        $('.list-products').css('height', 'calc(80vh - ' + $('#settings').outerHeight() + 'px)');
        //$('.sticky th').css('top', ($('#settings').innerHeight()) + 'px');
        //$('#dProducts').addClass('uk-hidden');
    }
    else {
        $('#btnSettings').removeClass('btn-warning');
        $('#settings').addClass('hidden');
        $('.list-products').css('height', 'calc(80vh)');
        //$('.sticky th').css('top', '0');
        //$('#dProducts').removeClass('uk-hidden');
    }
}

function ToggleFilters() {
    if (!$('#settings').hasClass('hidden')) 
        ToggleSettings();

    if ($('#filters').hasClass('hidden')) {
        $('#btnFilters').addClass('btn-warning');
        $('#filters').removeClass('hidden');
        $('.list-products').css('height', 'calc(80vh - ' + $('#filters').outerHeight() + 'px)');
        //$('.sticky th').css('top', ($('#filters').innerHeight()) + 'px');
        //$('#dProducts').addClass('uk-hidden');
    }
    else {
        $('#btnFilters').removeClass('btn-warning');
        $('#filters').addClass('hidden');
        $('.list-products').css('height', 'calc(80vh)');
        //$('.sticky th').css('top', '0');
        //$('#dProducts').removeClass('uk-hidden');
    }
}

function CloseCatalog() {
    if (basket.length > 0) {
        confirm('', 'پس از بستن فرم سبد خرید خالی می شود. آیا ادامه می دهید؟', function() {
            basket = [];
            customGifts = [];
            RefreshBasket();
            $('#modal-catalog').modal('hide');    
        });
    } 
    else {
        basket = [];
        customGifts = [];
        RefreshBasket();
        $('#modal-catalog').modal('hide');
    }
}

function SearchBrands() {
    var key = $('#txtSearchBrands').val();

    $('#dBrands .cat-item').each(function(index, obj) {
        var name = $(obj).text();
        var en = $(obj).attr('data-en');

        if (name.indexOf(key) == -1 && en.indexOf(key) == -1) 
            $(obj).addClass('hidden');
        else
            $(obj).removeClass('hidden');
    });
}

function ChangeSort(obj) {
    var sort = $(obj).attr('data-value');

    $('.sort-item').removeClass('active');
    $(obj).addClass('active');

    $('#btnSort').html($(obj).text());
    $('#btnSort').click();

    RefreshProducts();
}

function ChangeBrand(obj) {
    var name = $(obj).text();


    if ($(obj).hasClass('active')) {
        $(obj).removeClass('active');
        $('.selected-brand').html('');
        $('#btnBrands').removeClass('filtered');
    }
    else {
        $('#dBrands .cat-item').removeClass('active');
        $(obj).addClass('active');
        $('.selected-brand').html(' (' + name + ')');
        $('#btnBrands').addClass('filtered');
    }
    RefreshProducts();
}

function RemoveFilterCats() {
    $('#txtSearchCats').val('');

    LoadCats(0, '');

    $('#btnCats').removeClass('filtered');
    $('.selected-cat').html('');
}

function RemoveFilterBrands() {
    $('#txtSearchBrands').val('');

    $('#dBrands .cat-item.active').removeClass('active');
    $('.selected-brand').html('');
    $('#btnBrands').removeClass('filtered');

    RefreshProducts();
}

function FilterColumnClick(obj) {
    var id = $(obj).attr('data-id');

    if ($(obj).hasClass('active')) {
        $(obj).removeClass('active');
    }
    else {
        $(obj).addClass('active');
    }

    var ids = '';

    $('.col-item').each(function (index, obj) {
        if ($(obj).hasClass('active')) {
            if (ids != '')
                ids += ',';
            
            ids += $(obj).attr('data-id');
        }
    });

    setCookie('Columns', ids, 90);

    FilterColumns();
}

function FilterColumns() {
    var ids = getCookie('Columns');

    if (ids == '') {
        ids = '0,1,2,3,4,5,6,7,8,9,10,11';
    }

    ids = ',' + ids + ','; 

    for (var i = 0; i <= 11; i++) {
        if (ids.indexOf(',' + i + ',') >= 0) {
            $('.col-item-' + i).removeClass('hidden');
            $('.col-filter-' + i).addClass('active');
        }
        else {
            $('.col-item-' + i).addClass('hidden');
            $('.col-filter-' + i).removeClass('active');
//            console.log()
        }
    }
}

function SearchCats() {
    var key = $('#txtSearchCats').val();

    $('#dCats').html('<div class="uk-text-center"><img style="width="90%;" src="' + BaseUrl + '/assets/img/Loading.gif"></div>');

    $.post(SiteUrl + '/products/get_cats',
            { key: key },
            function(data) {
                if (data.Result) {
                    $('#dCats').html(data.Data);

                    RefreshProducts();
                }
                else {
                    $('#dCats').html(old);

                    ShowError(data.Message);    
                }
            }, 'json').always(function() {

            }).fail(function() {
                $('#dCats').html(old);

                ShowError('اتصال به سرور برقرار نشد.');
            });

}

function ChangeCat(obj) {
    var name = $(obj).text();
    $('.selected-cat').html(' (' + name + ')');

    if ($(obj).attr('has-items') == '1') {
        var id = $(obj).attr('data-id');
        var old = $('#dCats').html();
        $('#txtSearchCats').val('');

        //$('#dCats').html('<div class="uk-text-center"><img style="width="90%;" src="' + BaseUrl + '/assets/img/Loading.gif"></div>');
        LoadCats(id, old);
    }
    else {
        $('#dCats .cat-item').removeClass('active');

        $(obj).addClass('active');
        $('#btnCats').addClass('filtered');

        RefreshProducts();
    }
}

function LoadCats(id, old) {
    $.post(SiteUrl + '/products/get_cats',
    { id: id },
    function(data) {
        if (data.Result) {
            $('#dCats').html(data.Data);

            RefreshProducts();
        }
        else {
            $('#dCats').html(old);

            ShowError(data.Message);    
        }

        if ($('#dCats .cat-item.active').length > 0) {
            $('#btnCats').addClass('filtered');
        }
        else {
            $('#btnCats').removeClass('filtered');
            $('.selected-cat').html('');
        }
    }, 'json').always(function() {

    }).fail(function() {
        $('#dCats').html(old);

        ShowError('اتصال به سرور برقرار نشد.');
    });

}

var pReq = null;
var pOldKey = '';
var pOldCat = 0;
var pOldBrand = 0;
var pOldSort = 0;
var pOldMojod = -1;
var $product_inf_scroll = null;
var old_data = "";
var product_loaded = 0;
var product_params = {};
var loading_products = false;
var last_key_changed = 0;
var last_key = '';

function KeyChanged() {
    let d = new Date();
    last_key_changed = d.getTime();

    setTimeout(() => {
        let d = new Date();
        if (d.getTime() - last_key_changed >= 900) {
            if (last_key != $('#txtSearchProducts').val())
                RefreshProducts();
        }
    }, 1000);
}

function RefreshProducts() {
    /*var brand = 0;
    try {
        brand = parseInt($('#dBrands .cat-item.active').attr('data-id'));

        if (isNaN(brand))
            brand = 0;
    }
    catch (err){}*/

    var cat = 0;
    try {
        cat = parseInt($('#dCats .cat-item.active').attr('data-value'));

        if (isNaN(cat))
            cat = 0;
    }
    catch (err){}

    var sort = 0;
    sort = $('.sort-item.active').attr('data-value');

    var mojod = document.getElementById('chbJustMojod').checked ? 1 : 0;

    var key = $('#txtSearchProducts').val();
    last_key = key;

    $('#tProducts').html('<td colspan="12"><div class="uk-text-center"><img style="width="90%;" src="' + BaseUrl + '/assets/img/Loading.gif"></div></td>')

    var tour = 0;
    try {
        tour = Tour.ID;

    }
    catch (err) {}

    var data = { cid: CID, cat: cat, sort: sort, key: key, mojod: mojod, tour: tour, order: OrderID, return: _isReturn ? 1 : 0, buy: _isBuy ? 1 : 0, store: _Store };
    
    var changed = false;

    $('.filter-items').each (function(index, obj) {
        var name = $(obj).attr('data-name');

        var id = $('#dFilter_' + name + ' .active').attr('data-id');

        if (id === undefined || id == null)
            id = '';

        if (id != '') {
            data[name] = id;
            changed = true;
        }
    });

    //console.log(changed);

    if (changed || sort != pOldSort || cat != pOldCat /*|| brand != pOldBrand*/ || key != pOldKey || mojod != pOldMojod) {
        pOldSort = sort;
        pOldCat = cat;
        //pOldBrand = brand;
        pOldKey = key;
        pOldMojod = mojod;
    }
    else    
        return;

    try {
        pReq.abort();
    }
    catch (err){}

    product_params = data;
    product_loaded = 0;

    loading_products = true;
    pReq = $.post(SiteUrl + '/products/search',
           data,
           function(data) {
            if (data.Result) {

                let html = data.Data.Html;

                $('#tProducts tr.load-more').remove();

                if (data.Data.Ended == 0) {
                    html += '<tr class="load-more uk-text-center"><td colspan="15" style="text-align: center;"><button class="uk-button uk-button-default uk-width-1-1" onclick="LoadMoreProducts()">نمایش بیشتر</button></td></tr>'
                }

                product_loaded += data.Data.Count;

                $('#tProducts').html(html);
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover();
                UpdateListBuyCounts();
                UpdateListTakhfifs();

                /*if (!$('#filters').hasClass('hidden'))
                    ToggleFilters();*/

            }
            else {
                ProductsError(data.Message);
            }

            FilterColumns();
        }, 'json').fail(function(err) {
            if (err.statusText != 'abort')
                ProductsError('اتصال به سرور برقرار نشد.');
        }).always(function() {
            loading_products = false;
        });
}

function LoadMoreProducts() {
    try {
        pReq.abort();
    }
    catch (err){}

    if ($('#tProducts tr.load-more').length == 0)
        return;

    $('#tProducts tr.load-more td').html('<img style="width: 64px;" src="' + BaseUrl + '/assets/img/Loading.gif" />');

    product_params.offset = product_loaded;

    loading_products = true;
    pReq = $.post(SiteUrl + '/products/search',
           product_params,
           function(data) {
            if (data.Result) {

                let html = data.Data.Html;

                $('#tProducts tr.load-more').remove();

                if (data.Data.Ended == 0) {
                    html += '<tr class="load-more"><td colspan="15" style="text-align: center;"><button class="uk-button uk-button-default uk-width-1-1" onclick="LoadMoreProducts()">نمایش بیشتر</button></td></tr>'
                }

                product_loaded += data.Data.Count;

                $('#tProducts').append(html);
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover();
                UpdateListBuyCounts();
                UpdateListTakhfifs();

                /*if (!$('#filters').hasClass('hidden'))
                    ToggleFilters();*/

            }
            else {
                ShowError(data.Message);
            }

            FilterColumns();
        }, 'json').fail(function() {
            if (err.statusText != 'abort')
                ShowError('اتصال به سرور برقرار نشد.')
        }).always(function() {
            loading_products = false;
        });

}

var _lastOpened = '';
async function ToggleNewPrice(obj) {
    let id = $(obj).attr('data-id');

    if (_lastOpened == id)
        return CloseNewPrice();

    if (_lastOpened != '')
        $('#btnPrice_' + _lastOpened).popover('hide');

    _lastOpened = id;

    let price = parseInt(replaceAll(',', '', $('#txtCount_' + id).attr('data-baseprice')));

    let nprice = price;
    for (let i = 0; i < new_prices.length; i++) {
        if (new_prices[i].ID == id) {
            nprice = new_prices[i].Price;
            break;
        }
    }

    $(obj).attr('data-content', "<div style='min-width: 170px;'><div class='uk-text-center'>قیمت اصلی: <strong>" + price + "</strong><hr style='margin-top: 5px; margin-bottom: 5px;'></div><form class='' onsubmit='ChangeNewPrice(); return false;'><label for='txtNewPrice'>قیمت جدید:</label><input id='txtNewPrice' autocomplete='off' aria-autocomplete='off' type='text' value='" + nprice + "' class='form-control' placeholder='قیمت جدید' /><br></form><table width='100%'><tr><td class='td-grid'><button class='btn btn-success' type='submit' onclick='ChangeNewPrice();'>تایید</button></td><td class='td-grid'><button class='btn btn-danger' type='button' onclick='CloseNewPrice();'>بستن</button></td></tr></table></div>");

    $(obj).popover('show');

    $('#txtNewPrice').focus();
    $('#txtNewPrice').select();
}

function ChangeNewPrice() {
    let price = parseInt($('#txtNewPrice').val());

    if (isNaN(price)) {
        ShowError('قیمت وارد شده معتبر نمی باشد.');
        return;
    }

    let old_price = parseInt(replaceAll(',', '', $('#txtCount_' + _lastOpened).attr('data-old-price')));

    $('#txtCount_' + _lastOpened).attr('data-fix-price', price);

    ChangeItemTakhfifPercent($('#txtTakhfifPercent_' + _lastOpened));

    ChangePriceInTable(_lastOpened, parseInt($('#txtCount_' + _lastOpened).attr('data-old-price')), price);

    if (price == old_price) {
        let items = [];

        for (let i = 0; i < new_prices.length; i++) {
            let item = new_prices[i];

            if (item.ID != _lastOpened)
                items[items.length] = item;
        }

        new_prices = items;
    }
    else {
        let item = null;

        for (let i = 0; i < new_prices.length; i++) {
            let item = new_prices[i];

            if (item.ID != _lastOpened) {
                item.Price = price;
                new_prices[i] = item;
                break;
            }
        }

        if (item == null) {
            new_prices[new_prices.length] = {
                ID: _lastOpened,
                Price: price
            }
        }
    }

    CloseNewPrice();

    return false;
}

function ChangePriceInTable(id, oldPrice, newPrice) {
    if (oldPrice == newPrice) {
        $('#group_price_' + id).html(numeral(newPrice).format('0,0'));
    }
    else {
        $('#group_price_' + id).html('<span class="old-price">' + numeral(oldPrice).format('0,0') + '</span><span class="new-price">' + numeral(newPrice).format('0,0') + '</span>');
    }
}

function CloseNewPrice() {
    $('#btnPrice_' + _lastOpened).popover('hide');
    _lastOpened = '';
}

function UpdateListTakhfifs() {
    for (let i = 0; i < pTakhfifs.length; i++) {
        let item = pTakhfifs[i];

        $('#txtTakhfifPercent_' + item.ID).val(item.Percent);
        $('#txtTakhfifRial_' + item.ID).val(item.Rial);

        if ($('#txtTakhfifPercent_' + item.ID).length > 0) {
            /*if (item.Type == 1)
                ChangeItemTakhfifPercent($('#txtTakhfifPercent_' + item.ID));
            else
                ChangeItemTakhfifRial($('#txtTakhfifRial_' + item.ID));*/
            if (item.Type == 1) {
                $('#txtTakhfifRial_' + item.ID).attr('readonly', 'readonly');
            }
            else {
                $('#txtTakhfifPercent_' + item.ID).attr('readonly', 'readonly');
            }
        }
    }


    for (let i = 0; i < customGifts.length; i++) {
        let item = customGifts[i];

        $('#dGifts_' + item.PID).val(item.Count);
    }

    for (let i = 0; i < new_prices.length; i++) {
        let item = new_prices[i];

        $('#txtCount_' + item.ID).attr('data-fix-price', item.Price);
        let price = parseInt($('#txtCount_' + item.ID).attr('data-old-price'));

        ChangePriceInTable(item.ID, price, item.Price);
        //ChangeItemTakhfifPercent($('#txtTakhfifPercent_' + item.ID));
    }
}

function UpdateBasketTakhfifs() {
    for (let i = 0; i < pTakhfifs.length; i++) {
        let item = pTakhfifs[i];

        $('#txtTakhfifPercent_b_' + item.ID).val(item.Percent);
        $('#txtTakhfifRial_b_' + item.ID).val(item.Rial);

        if ($('#txtTakhfifPercent_b_' + item.ID).length > 0) {
            /*if (item.Type == 1)
                ChangeItemTakhfifPercent($('#txtTakhfifPercent_' + item.ID));
            else
                ChangeItemTakhfifRial($('#txtTakhfifRial_' + item.ID));*/
            if (item.Type == 1) {
                $('#txtTakhfifRial_b_' + item.ID).attr('readonly', 'readonly');
            }
            else {
                $('#txtTakhfifPercent_b_' + item.ID).attr('readonly', 'readonly');
            }
        }
    }
}

function ChangeItemTakhfifPercent(obj, changing) {
    if (changing === undefined)
        changing = true;

    let id = parseInt($(obj).attr('data-id'));

    let price = parseInt($('#txtCount_' + id).attr('data-fix-price'));

    console.log(price);

    let p = parseFloat($(obj).val());

    if (isNaN(p))
        p = 0;

    let rial = parseInt((price * p) / 100);
    $('#txtTakhfifRial_' + id).val(rial);
    let final = price - rial;


    let finded = false;
    for (let i = 0; i < pTakhfifs.length; i++) {
        let item = pTakhfifs[i];

        if (item.ID == id) {
            finded = true;
            item.Percent = p;
            item.Rial = rial
            item.Type = 1;

            pTakhfifs[i] = item;
            break;
        }
    }

    if (finded == false && p > 0) {

        if (!isNaN(id)) { 
            
            pTakhfifs[pTakhfifs.length] = {
                ID: id,
                Percent: p,
                Rial: rial,
                Type: 1
            };
        }
    }

    $('#tFinalPrice_' + id).html(numeral(final).format('0,0'));
    $('#txtCount_' + id).attr('data-price', final);
    let count = parseInt($('#txtCount_' + id).val());
    if (isNaN(count))
        count = 0;
    if (count > 0 && changing)
        _ChangeCount(id, count);

    if (p > 0) {
        $('#txtTakhfifRial_' + id).attr('readonly', 'readonly');
        $('#txtTakhfifPercent_' + id).removeAttr('readonly');
    }
    else {
        $('#txtTakhfifRial_' + id).removeAttr('readonly');
        $('#txtTakhfifPercent_' + id).removeAttr('readonly');
    }
}

function ChangeItemTakhfifRial(obj, changing) {
    if (changing === undefined)
        changing = true;

    let id = parseInt($(obj).attr('data-id'));

    let price = parseInt($('#txtCount_' + id).attr('data-fix-price'));

    let rial = parseFloat($(obj).val());

    if (isNaN(rial))
        rial = 0;

    let p = parseFloat((100 * rial) / price);
    $('#txtTakhfifPercent_' + id).val(numeral(p).format('0,0.00'));
    let final = price - rial;

    let finded = false;
    for (let i = 0; i < pTakhfifs.length; i++) {
        let item = pTakhfifs[i];

        if (item.ID == id) {
            finded = true;
            item.Percent = p;
            item.Rial = rial;
            item.Type = 2;

            pTakhfifs[i] = item;
            break;
        }
    }

    if (finded == false) {
        pTakhfifs[pTakhfifs.length] = {
            ID: id,
            Percent: p,
            Rial: rial,
            Type: 2
        };
    }

    $('#tFinalPrice_' + id).html(numeral(final).format('0,0'));
    $('#txtCount_' + id).attr('data-price', final);
    let count = parseInt($('#txtCount_' + id).val());
    if (isNaN(count))
        count = 0;
    if (count > 0 && changing)
        _ChangeCount(id, count);

    if (p > 0) {
        $('#txtTakhfifPercent_' + id).attr('readonly', 'readonly');
        $('#txtTakhfifRial_' + id).removeAttr('readonly');
    }
    else {
        $('#txtTakhfifRial_' + id).removeAttr('readonly');
        $('#txtTakhfifPercent_' + id).removeAttr('readonly');
    }
    
}

function ChangeItemTakhfifInBasketPercent(obj) {
    let id = parseInt($(obj).attr('data-id'));

    let price = parseInt($('#txtBCount_' + id).attr('data-fix-price'));

    let p = parseFloat($(obj).val());

    if (isNaN(p))
        p = 0;

    let rial = parseInt((price * p) / 100);
    $('#txtTakhfifRial_b_' + id).val(rial);
    let final = price - rial;


    let finded = false;
    for (let i = 0; i < pTakhfifs.length; i++) {
        let item = pTakhfifs[i];

        if (item.ID == id) {
            finded = true;
            item.Percent = p;
            item.Rial = rial
            item.Type = 1;

            pTakhfifs[i] = item;
            break;
        }
    }

    if (finded == false && p > 0) {

        if (!isNaN(id)) { 
            
            pTakhfifs[pTakhfifs.length] = {
                ID: id,
                Percent: p,
                Rial: rial,
                Type: 1
            };
        }
    }

    $('#tFinalPrice_' + id).html(numeral(final).format('0,0'));
    $('#txtBCount_' + id).attr('data-price', final);
    try {
        $('#txtCount_' + id).attr('data-price', final);
    }
    catch (err) {}
    let count = parseInt($('#txtBCount_' + id).val());
    if (isNaN(count))
        count = 0;
    if (count > 0)
        ChangeBCount(id, false);

    try {
        $('#txtTakhfifRial_' + id).val(rial);
        $('#txtTakhfifPercent_' + id).val(p);    
    }
    catch (err) {

    }


    if (p > 0) {
        $('#txtTakhfifRial_b_' + id).attr('readonly', 'readonly');
        $('#txtTakhfifPercent_b_' + id).removeAttr('readonly');
        try {
            $('#txtTakhfifRial_' + id).attr('readonly', 'readonly');
            $('#txtTakhfifPercent_' + id).removeAttr('readonly');    
        }
        catch (err) {

        }
    }
    else {
        $('#txtTakhfifRial_b_' + id).removeAttr('readonly');
        $('#txtTakhfifPercent_b_' + id).removeAttr('readonly');

        try {
            $('#txtTakhfifRial_' + id).removeAttr('readonly');
            $('#txtTakhfifPercent_' + id).removeAttr('readonly');    
        }
        catch (err) {}
    }

    /*$('#txtTakhfifPercent_b_' + id).focus();
    $('#txtTakhfifPercent_b_' + id).putCursorAtEnd();*/

}

function ChangeItemTakhfifInBasketRial(obj) {
    let id = parseInt($(obj).attr('data-id'));

    let price = parseInt($('#txtBCount_' + id).attr('data-fix-price'));

    let rial = parseFloat($(obj).val());

    if (isNaN(rial))
        rial = 0;

    let p = parseFloat(parseFloat((100 * rial) / price).toPrecision(2));

    let finded = false;
    for (let i = 0; i < pTakhfifs.length; i++) {
        let item = pTakhfifs[i];

        if (item.ID == id) {
            if (item.Rial == rial)
                return;
            finded = true;
            item.Percent = p;
            item.Rial = rial;
            item.Type = 2;

            pTakhfifs[i] = item;
            break;
        }
    }


    $('#txtTakhfifPercent_b_' + id).val(numeral(p).format('0,0'));
    let final = price - rial;


    if (finded == false) {
        pTakhfifs[pTakhfifs.length] = {
            ID: id,
            Percent: p,
            Rial: rial,
            Type: 2
        };
    }

    $('#tFinalPrice_' + id).html(numeral(final).format('0,0'));
    $('#txtBCount_' + id).attr('data-price', final);
    try {
        $('#txtCount_' + id).attr('data-price', final);
    }
    catch (err) {}
    let count = parseInt($('#txtBCount_' + id).val());
    if (isNaN(count))
        count = 0;
    if (count > 0)
        ChangeBCount(id, false);

    try {
        $('#txtTakhfifRial_' + id).val(rial);
        $('#txtTakhfifPercent_' + id).val(p);    
    }
    catch (err) {

    }
    
    if (p > 0) {
        $('#txtTakhfifPercent_b_' + id).attr('readonly', 'readonly');
        $('#txtTakhfifRial_b_' + id).removeAttr('readonly');

        try {
            $('#txtTakhfifPercent_' + id).attr('readonly', 'readonly');
            $('#txtTakhfifRial_' + id).removeAttr('readonly');    
        }
        catch (err) {}
    }
    else {
        $('#txtTakhfifRial_b_' + id).removeAttr('readonly');
        $('#txtTakhfifPercent_b_' + id).removeAttr('readonly');

        try {
            $('#txtTakhfifRial_' + id).removeAttr('readonly');
            $('#txtTakhfifPercent_' + id).removeAttr('readonly');    
        }
        catch (err) {}
    }
    
    /*$('#txtTakhfifRial_b_' + id).focus();
    $('#txtTakhfifRial_b_' + id).putCursorAtEnd();*/

}

function UpdateListBuyCounts() {
    for (var i = 0; i < basket.length; i++) {
        var item = basket[i];

        try {
//            $('.txtCount_' + item.ID).val(item.Count);

            if (_isBuy) {
                try {
                    let price = item.Price;
                    if (price === undefined)
                        price = 0;
                    $('#txtPrice_' + item.ID).val(item.Price);
                }
                catch (err) {}
            }

            _ChangeCount(item.ID, item.Count, true, false, false);
        }
        catch (err){}
    }

    RefreshBasket();
}

function ProductsError(msg) {
    $('#tProducts').html('<td colspan="12"><div class="uk-text-center uk-padding">' + msg + '<br><br><button class="btn btn-danger" onclick="pOldCat=-1; RefreshProducts();">تلاش مجدد</button></div></td>');
}

var PID = 0;
function ViewProduct(id) {
    PID = id;

    $('#modal-view-catalog').modal('show');

    $('#dProduct').html('<div class="uk-text-center"><img src="' + BaseUrl + '/assets/img/loading.gif" /></div>');
    $('.sProductTitle').html('')

    $.post(SiteUrl + '/products/get_product',
          { id: id},
          function(data) {
            if (data.Result) {
                $('.sProductTitle').html(' (' + data.Data.Title + ')')
                $('#dProduct').html(data.Data.Html);

                $('.catalog-role').click(function(obj) {
                    var page = $(this).attr('page');

                    $('.catalog-role').removeClass('active');
                    $(this).addClass('active');

                    $('.catalog-page').addClass('hidden');
                    $('#' + page).removeClass('hidden');
                });
            }
            else {
                $('#dProduct').html('<div class="uk-padding"><div class="alert alert-danger">' + data.Message + '</div></div><div class="uk-text-center uk-margin"><button class="btn btn-danger" onclick="ViewProduct(' + id + ');">تلاش مجدد</button></div>');
            }
          }, 'json').fail(function() {
            $('#dProduct').html('<div class="uk-padding"><div class="alert alert-danger">اتصال به سرور برقرار نشد.</div></div><div class="uk-text-center uk-margin"><button class="btn btn-danger" onclick="ViewProduct(' + id + ');">تلاش مجدد</button></div>');
          }).always(function() {

          });
}

function BuyProduct(id) {
    PID = id;

    $('#modal-buy-product').modal('show');
    $('#dBuyBack').css('display', 'block');

    $('#dProductBuy').html('<div class="uk-text-center"><img src="' + BaseUrl + '/assets/img/loading.gif" /></div>');
    $('.sProductTitle').html('');

    $('#modal-buy-product').on('hidden.bs.modal', function (e) {
        $('#dBuyBack').css('display', 'none');        
    });

    $.post(SiteUrl + '/products/get_product_buy',
          { id: id, cid: CID},
          function(data) {
            if (data.Result) {
                $('.sProductTitle').html(' (' + data.Data.Title + ')')
                $('#dProductBuy').html(data.Data.Html);

                $('.count-container').each(function(index, obj) {
                    var id = $(obj).attr('data-id');

                    for (var i = 0; i < basket.length; i++) {
                        if (basket[i].ID == id) {
                            $('.txtCount_' + id).val(basket[i].Count);
                        }
                    }

                    ChangeCount(id);
                });
            }
            else {
                $('#dProductBuy').html('<div class="uk-padding"><div class="alert alert-danger">' + data.Message + '</div></div><div class="uk-text-center uk-margin"><button class="btn btn-danger" onclick="BuyProduct(' + id + ');">تلاش مجدد</button></div>');
            }
          }, 'json').fail(function() {
            $('#dProductBuy').html('<div class="uk-padding"><div class="alert alert-danger">اتصال به سرور برقرار نشد.</div></div><div class="uk-text-center uk-margin"><button class="btn btn-danger" onclick="BuyProduct(' + id + ');">تلاش مجدد</button></div>');
          }).always(function() {

          });
}

function AddCount(id) {
    var count = parseInt(toEnglish($('.txtCount_' + id).val()));

    count++;

    _ChangeCount(id, count);
}

function SubCount(id) {
    var count = parseInt(toEnglish($('.txtCount_' + id).val()));

    count--;

    _ChangeCount(id, count);
}

function AddBCount(id) {
    var count = parseInt(toEnglish($('#txtBCount_' + id).val()));

    count++;

    _ChangeCount(id, count);
}

function SubBCount(id) {
    var count = parseInt(toEnglish($('#txtBCount_' + id).val()));

    count--;

    _ChangeCount(id, count);
}

function ChangeCount(id) {
    var count = parseInt(toEnglish($('#txtCount_' + id).val()));

    if (isNaN(count))
        return;

    _ChangeCount(id, count);

}

function ChangeBCount(id, focus) {
    if (focus === undefined)
        focus = true;

    var count = parseInt(toEnglish($('#txtBCount_' + id).val()));

    if (isNaN(count))
        return;

    _ChangeCount(id, count);


    if (focus) {
        $('#txtBCount_' + id).focus();
        $('#txtBCount_' + id).putCursorAtEnd();
    }
}

function ChangeBuyPrice(obj) {
    let id = $(obj).attr('data-id');

    ChangeCount(id);
}

function _ChangeCount(id, count, ok, update_price, refresh) {
    if (ok === undefined)
        ok = false;

    if (update_price === undefined)
        update_price = true;

    if (refresh === undefined)
        refresh = true;

    if (count == 0 && ok == false && _isBuy == false) {
        confirm('', 'آیا از حذف این محصول مطمئن هستید؟', function() {
            _ChangeCount(id, count, true);
        });

        return;
    }

    try {
        if (update_price) {
            if (!$('#modal-catalog').hasClass('uk-hidden')) {
                if ($('#txtTakhfifPercent_' + id).length > 0) {
                    if ($('#txtTakhfifPercent_' + id).attr('readonly') == 'readonly' || $('#txtTakhfifRial_' + id).attr('readonly') == 'readonly') {
                        if ($('#txtTakhfifPercent_' + id).attr('readonly') == 'readonly')
                            ChangeItemTakhfifRial($('#txtTakhfifRial_' + id), false);
                        else
                            ChangeItemTakhfifPercent($('#txtTakhfifPercent_' + id), false);
                    }
                }
            }
        }
    }
    catch (err) {}

    var max = parseInt($('.txtCount_' + id).attr('data-max'));

    if (_isReturn || _isBuy)
        max = count + 1;

    if (count < 0) {
        count = 0;
    }
    else if (count > max) {
        count = max;
    }

    if (isNaN(count))
        count = 0;

    $('#txtCount_' + id).val(count);

    $('.btnAdd_' + id).removeClass('disabled');
    $('.btnSub_' + id).removeClass('disabled');

    if (count == 0) {
        $('.btnSub_' + id).addClass('disabled');
    }
    else if (count == max) {
        $('.btnAdd_' + id).addClass('disabled');
    }
    
    var item = null;
    var index = -1;
    for (var i = 0; i < basket.length; i++) {
        if (parseInt(basket[i].ID) == parseInt(id)) {
            item = basket[i];
            index = i;
            break;
        }
    }


    if (count == 0 && item != null && _isBuy == false) {
        var temp = [];

        for (var i = 0; i < basket.length; i++) {
            if (i != index)
                temp[temp.length] = basket[i];
        }

        basket = temp;
    }

    if ((count > 0 || _isBuy) && item == null) {
        item = {
            PID: $('#txtCount_' + id).attr('data-pid'),
            ID: $('#txtCount_' + id).attr('data-id'),
            Name: $('#txtCount_' + id).attr('data-name'),
            Variety: $('#txtCount_' + id).attr('data-variety'),
            Count: count,
            Price: parseInt(_isBuy ? $('#txtPrice_' + id).val() : $('#txtCount_' + id).attr('data-price')),
            BasePrice: parseInt($('#txtCount_' + id).attr('data-baseprice')),
            OldPrice: parseInt($('#txtCount_' + id).attr('data-fix-price')),
            Pic: $('#txtCount_' + id).attr('data-pic'),
            Unit: $('#txtCount_' + id).attr('data-unit'),
            Store: $('#txtCount_' + id).attr('data-store'),
            StoreID: $('#txtCount_' + id).attr('data-store-id'),
            Max: max
        };

        index = basket.length;
        basket[basket.length] = item;
    }

    if (update_price == true) {
        if ($('.txtCount_' + id).length > 0) {
            item.Price = parseInt(_isBuy ? $('#txtPrice_' + id).val() : $('.txtCount_' + id).attr('data-price'));
            item.BasePrice = parseInt($('.txtCount_' + id).attr('data-baseprice'));
            item.OldPrice = parseInt($('.txtCount_' + id).attr('data-fix-price'));
        }
    }

    if (count > 0) {
        for (let i = 0; i < pTakhfifs.length; i++) {
            let it = pTakhfifs[i];
            
            if (it.ID == item.ID) {
                item.TakhfifPercent = it.Percent;
                item.TakhfifRial = it.Rial;
                item.TakhfifType = it.Type;

                basket[index] = item;
                break;
            }
        }
    }

    if (count > 0 || _isBuy) {
        item.Count = count;
        item.Max = max;
        basket[index] = item;

        if (_isBuy) {
            item.Price = parseInt($('#txtPrice_' + id).val());
            if (isNaN(item.Price))
                item.Price = 0;
        }

        $('.total-price-' + id).html('جمع: ' + numeral(item.Price * count).format('0,0') + ' ' + Unit);

        var html = '';

        if (_isBuy) {
            html += 'جمع: ' + numeral(item.Price * count).format('0,0') + ' ' + Unit;
        }
        else {
            html += 'جمع: ' + numeral(item.BasePrice * count).format('0,0') + ' ' + Unit + "<br>";
            html += 'تخفیف: ' + numeral(item.BasePrice * count - item.Price * count).format('0,0') + ' ' + Unit + "<br>";
            html += 'نهایی: ' + numeral(item.Price * count).format('0,0') + ' ' + Unit;
        }

        $('.item-buy-details-' + id).attr('data-original-title', html);
        $('.item-buy-details-' + id).html(numeral(item.Price * count).format('0,0'));
        //$('.item-buy-details-' + id).tooltip('show');
        //$('[data-toggle="tooltip"]').tooltip();        
    }
    else {
        $('.total-price-' + id).html('جمع: 0 ' + Unit);
        $('.item-buy-details-' + id).html('0');
        $('.item-buy-details-' + id).attr('title', '0');
    }

    if (refresh)
        RefreshBasket();

}

function ToggleGifts(obj) {
    var ids = '#' + $(obj).attr('data-ids') + '#';
    console.log(ids);

    if (_basket_gifts.indexOf(ids) == -1) {
        _basket_gifts += ids;
        RefreshBasket();
    }
    else {
        _basket_gifts = _basket_gifts.replace(ids, '');
        RefreshBasket();
    }
}

function SetOrderTozih() {
    _order_tozihat = $('#txtOrderTozih').val();
}

function AddPercentToAllBasket() {
    let p = parseFloat($('#txtTakhfifAll').val());

    if (isNaN(p)) {
        ShowError('درصد اشتباه می باشد.');
        return;
    }

    for (let i = 0; i < basket.length; i++) {
        let item = basket[i];

        if (item.TakhfifType === undefined) {
            item.TakhfifType = 1;
            item.TakhfifPercent = p;
        }
        else {
            item.TakhfifType = 1;
            item.TakhfifPercent += p;
        }

        if (item.TakhfifPercent < 0)
            item.TakhfifPercent = 0;

        item.TakhfifRial = parseInt((parseInt(item.BasePrice) * item.TakhfifPercent) / 100);
        item.Price = item.BasePrice - item.TakhfifRial;

        basket[i] = item;

        let finded = false;

        for (let j = 0; j < pTakhfifs.length; j++) {
            if (pTakhfifs[j].ID == item.ID) {
                finded = true;

                pTakhfifs[j].Type = 1;
                pTakhfifs[j].Rial = item.TakhfifRial;
                pTakhfifs[j].Percent = item.TakhfifPercent;

                break;
            }
        }

        if (finded == false) {
            pTakhfifs[pTakhfifs.length] = {
                ID: item.ID,
                Type: 1,
                Rial: item.TakhfifRial,
                Percent: item.TakhfifPercent
            }
        }

        try {
            $('#txtTakhfifRial_' + item.ID).val(item.TakhfifRial);
            $('#txtTakhfifPercent_' + item.ID).val(item.TakhfifPercent);    
        }
        catch (err) {
    
        }
    
    
        if (item.TakhfifPercent > 0) {
            try {
                $('#txtTakhfifRial_' + item.ID).attr('readonly', 'readonly');
                $('#txtTakhfifPercent_' + item.ID).removeAttr('readonly');    
            }
            catch (err) {
    
            }
        }
        else {    
            try {
                $('#txtTakhfifRial_' + item.ID).removeAttr('readonly');
                $('#txtTakhfifPercent_' + item.ID).removeAttr('readonly');    
            }
            catch (err) {}
        }
    
    }

    RefreshBasket();

    $('#dTakhfifAll').addClass('uk-hidden');
}

var _total = 0, _final = 0, _gifts = 0, _samples = 0, _enabled_gifts = '', _basket_gifts = '', _tax = -1, _trial = -1, _tpercent = -1, _ttype = 0, _visitor = 0, _manager = 0, _visitor2 = 0;
function RefreshBasket() {
    console.log('RefreshBasket');
    if (basket.length == 0 && customGifts.length == 0) {
        _enabled_gifts = '';
        _basket_gifts = '';
        $('.basket-container').addClass('hidden');

        if ($('#dBasketBack').css('display') != 'none') {
            ToggleBasket();
        }
    }
    else {
        $('.basket-container').removeClass('hidden');
        $('.basket-items').html(basket.length);

        var gifts = [];
        var samples = [];
        var final = 0;
        var takhfifOrder = 0;


        var html = '';

        if (CustomTakhfif) {
            html += '<div style="border-bottom: solid 1px darkgray; padding: 8px;"><a href="javascript:" onclick="$(\'#btnTakhfifAll\').addClass(\'uk-hidden\');$(\'#txtTakhfifAll\').val(0);$(\'#dTakhfifAll\').removeClass(\'uk-hidden\');" id="btnTakhfifAll">اعمال تخفیف روی همه اقلام</a><div id="dTakhfifAll" class="uk-hidden"><input type="text" id="txtTakhfifAll" class="form-control" style="width: 200px; display: inline-block;" value="0" />&nbsp;&nbsp;<button class="btn btn-success" onclick="AddPercentToAllBasket();">اعمال درصد</button>&nbsp;&nbsp;<button class="btn btn-danger" onclick="$(\'#btnTakhfifAll\').removeClass(\'uk-hidden\');$(\'#dTakhfifAll\').addClass(\'uk-hidden\');">انصراف</button></div></div>';
        }

        html += '<table width="100%" class="table table-condensed table-hover table-striped">';
        html += '<thead><tr>';
        html += '<th class="td-grid">ردیف</th>'; 
        html += '<th class="td-grid" style="width: 24px;"></th>';
        html += '<th class="td-grid">نام</th>'; 
        html += '<th class="td-grid">انبار</th>';
        html += '<th class="td-grid">تعداد</th>'; 
        if (_isBuy == false) {
            html += '<th class="td-grid" style="width: 100px">تخفیف</th>';
            html += '<th class="td-grid">جمع</th>'; 
            html += '<th class="td-grid">جمع تخفیف</th>'; 
        }
        html += '<th class="td-grid">جمع کل</th>'; 
        html += '<th class="td-grid"></th>'; 
        html += '</tr></thead><tbody>';

        var total = 0;
        for (var i = 0; i < basket.length; i++) {
            var item = basket[i];

            html += '<tr class="basket-item"><td class="td-grid">' + (i + 1) + '</td>';
            html += '<td class="td-grid"><i onclick="ToggleGifts(this);" class="fas fa-gift fa-2x uk-hidden gift-item-' + item.PID + '" data-id="' + item.PID + '"></i></td>';

            final += item.Count * parseInt(item.Price);

            if (_isBuy)
                item.BasePrice = item.Price;

            total += item.Count * parseInt(item.BasePrice);

            html += '<td class="basket-item-title td-grid">';
            html += item.Name;
            if (item.Variety != '') {
                html += '<div class="basket-item-variety">';
                html += item.Variety;
                html += '</div>';    
            }
            html += '</td>';

            html += '<td class="td-grid">' + (item.Store == null ? '' : item.Store) + '</td>';


            html += '<td style="width: 140px;">';
            html += '<div class="btn-group center counter" style="margin-top: 5px;">';
            html += '    <button type="button" class="btn ' + (parseInt(item.Count) >= parseInt(item.Max) ? 'disabled' : '') + ' btn-success btnAdd_' + item.ID + '" onclick="AddBCount(' + item.ID + ');">+</button>';
            html += '    <input autocomplete="off" type="text" style="width: calc(100% - 31px - 31px);" maxlength="4" class="txtCount_' + item.ID + ' count-container btn btn-default" onkeyup="ChangeBCount(' + item.ID + ');" value="' + item.Count + '" id="txtBCount_' + item.ID + '" data-max="' + item.Max + '" data-id="' + item.ID + '" data-price="' + item.Price + '" data-name="' + item.Name + '" data-variety="' + item.Variety + '" data-pid="' + item.PID + '" data-pic="' + item.Pic + '" data-unit="' + item.Unit + '"  data-old-price="' + item.OldPrice + '" data-fix-price="' + item.BasePrice + '"data-baseprice="' + item.BasePrice + '" />';
            html += '    <button type="button" class="btn btn-danger btnSub_' + item.ID + '" onclick="SubBCount(' + item.ID + ');">-</button>';
            html += '</div>';
            html += '</td>';

            if (_isBuy == false) {
                html += '<td class="td-grid">';
                if (CustomTakhfif == false) {
                    if (item.TakhfifPercent > 0 || item.TakhfifRial > 0) {
                        if (item.TakhfifPercent > 0)
                            html += item.TakhfifPercent + ' %';
                        else
                            html += numeral(item.TakhfifRial).format('0,0') + ' ' + Unit;
                    }
                    else {
                        html += '-';
                    }
                }
                else {
                    let takhfif_type = 0;
                    if (item.TakhfifType !== undefined) {
                        if (item.TakhfifPercent > 0 && item.TakhfifRial > 0)
                            takhfif_type = item.TakhfifType;
                    }
                    html += '<div class="input-group">';
                    html += '    <span class="input-group-addon" style="padding: 4px; width: 20px;">%</span>';
                    html += '    <input autocomplete="off" aria-autocomplete="off" style="padding: 0px; z-index: initial;" value="' + (item.TakhfifPercent === undefined ? '0' : item.TakhfifPercent) + '" id="txtTakhfifPercent_b_' + item.ID + '" type="text" class="form-control" data-id="' + item.ID + '" onblur="ChangeItemTakhfifInBasketPercent(this);" ' + (takhfif_type == 2 ? 'readonly' : '') + ' >';
                    html += '</div>';        
                    html += '<div class="input-group">';
                    html += '    <span class="input-group-addon" style="padding: 4px; width: 20px;">$</span>';
                    html += '    <input autocomplete="off" aria-autocomplete="off" style="padding: 0px; z-index: initial;" value="' + (item.TakhfifRial === undefined ? 0 : item.TakhfifRial) + '" id="txtTakhfifRial_b_' + item.ID + '" data-id="' + item.ID + '" type="text" class="form-control" onblur="ChangeItemTakhfifInBasketRial(this);" ' + (takhfif_type == 1 ? 'readonly' : '') + '>';
                    html += '</div>';

                }
                html += '</td>';

                html += '<td class="td-grid">' + numeral(item.Count * parseInt(item.BasePrice)).format('0,0') + ' '  + '</td>';
                html += '<td class="td-grid">' + numeral(item.Count * (parseInt(item.BasePrice) - parseInt(item.Price))).format('0,0') + ' '  + '</td>';
            }

            html += '<td class="td-grid">' + numeral(item.Count * parseInt(item.Price)).format('0,0') + ' ' + '</td>';

            html += '<td class="td-grid"><button class="btn btn-default" onclick="RemoveFromBasket(' + item.ID + ');"><i class="fas fa-trash"></i></button></td>';

            html += '</tr>';
        }

        html += '</tbody><tfoot style="background: #546E7A; color: white;"><tr>';

        html += '<td class="td-grid" colspan="5"></td>';
        if (_isBuy == false) {
            html += '<td class="td-grid">-</td>';
            html += '<td class="td-grid">' + numeral(total).format('0,0') + '</td>';
            html += '<td class="td-grid">' + numeral(total - final).format('0,0') + '</td>';
        }
        html += '<td class="td-grid">' + numeral(final).format('0,0') + '</td>';

        html += '<td></td></tr></tfoot>';

        html += '</table>';

        if (_isReturn == false) {
            if (Offers == null)
                Offers = [];
            for (var i = 0; i < Offers.length; i++) {
                var offer = Offers[i];

                if (offer.Type == 1) {
                    if (final >= parseInt(offer.MinPrice) && final <= parseInt(offer.MaxPrice)) {
                        var t = 0;
                        if (parseInt(offer.Takhfif) > 0)
                            t = parseInt(offer.Takhfif);
                        else
                            t = parseInt((parseInt(offer.TakhfifPercent) * final) / 100);

                        takhfifOrder += t;
                    }
                }
                else if (offer.Type > 2) {
                    var finded = true;

                    if (offer.Items.length == 0)
                        continue;

                    var ids = '';
                    for (var j = 0; j < offer.Items.length; j++) {
                        var item = offer.Items[j];

                        if (ids != '')
                            ids += ',';
                        ids += item.PID;

                        var b = FindItemInBasket(item.PID);

                        if (b == null) {
                            finded = false;
                            break;
                        }
                        else {
                            if (parseInt(item.Count) > b.Count) {
                                finded = false;
                                break;
                            }
                        }
                    }

                    if (finded) {
                        if (offer.Type == 3) {
                            if (_enabled_gifts.indexOf('#' + ids + '#') == -1) 
                                _enabled_gifts += '#' + ids + '#';

                            var str = ids.split(',');
                            for (var k = 0; k < str.length; k++) {
                                var cls="";
                                if (_basket_gifts.indexOf('#' + ids + '#') != -1) 
                                    cls = 'active';
                                html = html.replace('uk-hidden gift-item-' + str[k], cls + ' gift-item');
                                html = html.replace('data-id="' + str[k] + '"', 'data-id="' + str[k] + '" data-ids="' + ids + '"');

                                $('.gift-item-' + str[k]).removeClass('uk-hidden');
                                $('.gift-item-' + str[k]).attr('data-ids', ids);

                                if (cls == 'active')
                                    $('.gift-item-' + str[k]).addClass('active');
                                else
                                    $('.gift-item-' + str[k]).removeClass('active');
                            
                            }
                            
                            if (_basket_gifts.indexOf('#' + ids + '#') != -1) {
                                for (var j = 0; j < offer.Gifts.length; j++) {
                                    gifts[gifts.length] = offer.Gifts[j];
                                }
                            }
                        }
                        else {
                            samples[samples.length] = offer;
                        }
                    }
                    else {
                        if (offer.Type == 3) {
                            _enabled_gifts = _enabled_gifts.replace('#' + ids + '#', '');
                            _basket_gifts = _basket_gifts.replace('#' + ids + '#', '');

                            $('.gift-item[data-ids="' + ids + '"]').addClass('uk-hidden');
                        }
                    }
                }
            }
        }

        if (gifts.length > 0) {
            html += '<div class="basket-item" style="background: #66BB6A; color: white; text-align: center;">';
            html += 'هدیه های پروموشن';
            html += '</div>';

            html += '<table width="100%" class="table table-condensed table-hover table-striped">';

            html += '<tr>';
            html += '<th class="td-grid">ردیف</th>';
            html += '<th class="td-grid">نام</th>';
            html += '<th class="td-grid">تعداد</th>';
            html += '<th class="td-grid">قیمت</th>';
            html += '<th class="td-grid">سود شما</th>';
            html += '</tr>';

            for (var i = 0; i < gifts.length; i++) {
                var item = gifts[i];

                html += '<tr class="basket-item td-grid" style="background: #F1F8E9;"><td class="td-grid">' + (i + 1) + '</td>';

            
                html += '<td class="basket-item-title">' + item.Name + '</td>';
                html += '<td class="td-grid">' + numeral(item.Count).format('0,0') + '</td>';
                html += '<td class="td-grid">' + numeral(item.BasePrice).format('0,0') + ' '  + '</td>';
                html += '<td class="td-grid" style="color: #00E676;">' + numeral(parseInt(item.Count) * parseInt(item.BasePrice)).format('0,0') + ' '  + '</td>';

                html += '</tr>';
            }

            html += '</table>';

        }

        if (customGifts.length > 0) {
            html += '<div class="basket-item" style="background: #66BB6A; color: white; text-align: center;">';
            html += 'هدیه های موردی';
            html += '</div>';

            html += '<table width="100%" class="table table-condensed table-hover table-striped">';

            html += '<tr>';
            html += '<th class="td-grid">ردیف</th>';
            html += '<th class="td-grid">نام</th>';
            html += '<th class="td-grid">تعداد</th>';
            html += '<th class="td-grid">قیمت</th>';
            html += '<th class="td-grid">سود شما</th>';
            html += '<th class="td-grid"></th>';
            html += '</tr>';

            for (var i = 0; i < customGifts.length; i++) {
                var item = customGifts[i];

                html += '<tr class="basket-item td-grid" style="background: #F1F8E9;"><td class="td-grid">' + (i + 1) + '</td>';

            
                html += '<td class="basket-item-title">' + item.Name + '</td>';
                html += '<td class="td-grid">' + numeral(item.Count).format('0,0') + '</td>';
                html += '<td class="td-grid">' + numeral(item.BasePrice).format('0,0') + ' '  + '</td>';
                html += '<td class="td-grid" style="color: #00E676;">' + numeral(parseInt(item.Count) * parseInt(item.BasePrice)).format('0,0') + ' '  + '</td>';
                html += '<td class="td-grid"><button type="button" class="btn btn-default inline-button" onclick="DeleteGiftItem(' + item.PID + ');"><i class="fas fa-trash"></i></button></td>';
                html += '</tr>';
            }

            html += '</table>';

        }

        if (samples.length > 0) {
            html += '<div class="basket-item" style="background: #66BB6A; color: white; text-align: center;">';
            html += 'تخفیف های sample';
            html += '</div>';

            for (var i = 0; i < samples.length; i++) {
                var item = samples[i];

                html += '<div class="basket-item" style="background: #F1F8E9;">';
                html += '<div class="uk-text-center">' + item.Tozihat+ '</div>';
                html += '</div>';
            }

        }

        html += '<div class="basket-item" style="background: #66BB6A; color: white; text-align: center;">';
        html += 'توضیحات سفارش';
        html += '</div>';

        html += '<div><span>توضیحات:</span>';
        html += '<textarea id="txtOrderTozih" maxlength="500" class="form-control" rows="5" onkeyup="SetOrderTozih();" placeholder="توضیحات سفارش را در این قسمت وارد نمایید..."></textarea>';
        html += '</div>';


        $('.basket-body').html(html);

        $('#txtOrderTozih').val(_order_tozihat);


        var html = '<div class="uk-grid" uk-grid >';

        html += '<div class="uk-width-1-5">';
        html += 'جمع کل: ';
        html += '<div class="form-info">';
        html += numeral(total).format('0,0') + ' ' + Unit;
        html += '</div>';

        if (Visitors != 0 && _isBuy == false) {
            let content = '';

            html += "<div class='uk-text-center'>";

            html += '<br><a href="#" style="color: white; " id="btnSelectVisitor" data-toggle="popover" title="ویزیتور و سرپرست فروش" data-content="' + content + '" data-html="true" data-placement="top">انتخاب ویزیتور</a>';
            html += '</div>';
        }

        html += '</div>';

        html += '<div class="uk-width-1-5">';
        html += 'جمع پروموشن: ';
        html += '<div class="form-info" style="color: #76FF03;">';
        if (_isBuy == false) 
            html += numeral(total - final).format('0,0') + ' ' + Unit;
        else
            html += '-';
        html += '</div>';
        html += 'درصد تخفیف:<br>';
        html += '<input autocomplete="off" ' + (_ttype == 2 ? 'readonly' : '') + ' type="text" placeholder="درصد تخفیف" id="txtTakhfifPercent" class="form-control" onkeyup="ChangeTakhfifPercent();" value="' + (_tpercent == -1 ? '' : _tpercent) + '" />';
        html += '</div>';
        
        //_trial = -1;

        html += '<div class="uk-width-1-5">';
        html += 'پس از پروموشن:  ';
        html += '<div class="form-info">';
        if (_isBuy == false)
            html += numeral(final).format('0,0') + ' ' + Unit;
        else
            html += '-';
        html += '</div>';
        html += 'مبلغ تخفیف:<br>';
        html += '<input autocomplete="off" ' + (_ttype == 1 ? 'readonly' : '') + ' type="text" placeholder="مبلغ تخفیف" id="txtTakhfifRial" class="form-control" onkeyup="ChangeTakhfifRial();" value="' + (_trial == -1 ? '' : _trial) + '" />';
        html += '</div>';

        var tax = parseInt((final * Tax) / 100);
        html += '<div class="uk-width-1-5">';
        html += 'پروموشن فاکتور: ';
        html += '<div class="form-info" style="color: #76FF03;">';
        if (_isBuy == false)
            html += numeral(takhfifOrder).format('0,0') + ' ' + Unit;
        else
            html += '-';
        html += '</div>';
        html += 'ارزش افزوده: ';
        html += '<div class="form-info">';
        if (_isBuy == false) {
            _tax = tax;
            html += numeral(tax).format('0,0') + ' ' + Unit;
        }
        else
            html += '<input type="text" id="txtTax" class="form-control" value="' + _tax + '" onkeyup="ChangeTax();" value="' + (_tax == -1 ? '' : _tax) + '" />';

        html += '</div>';
        html += '</div>';

        if (_isBuy) {
            if (_tax == -1)
                tax = 0;
            else
                tax = _tax;
        }

        /*if (_isBuy == false)
            final += tax;*/

        //var trial = parseInt($('#txtTakhfifRial').val());
        trial = _trial;

        if (trial < 0)
            trial = 0;

        html += '<div class="uk-width-1-5">';
        html += 'مبلغ نهایی: ';
        html += '<div class="form-info" id="dFinalPrice" style="color: #ffcdd2;">';
        html += numeral(final + tax - takhfifOrder - trial).format('0,0') + ' ' + Unit;
        html += '</div><br>';
        html += '<button class="btn btn-danger uk-width-1-1" onclick="SavePreOrder();">ذخیره فاکتور</button>';
        html += '</div>';

        html += '</div>';

        

        $('.basket-footer').html(html);
        $('#btnSelectVisitor').popover();

        $('#btnSelectVisitor').on('show.bs.popover', function() {
            let content = 'ویزیتور:';
            content += "<select class='form-control' id='sVisitor' onchange='VisitorChanged();'>";
            content += "<option value='0'>---- ویزیتور ----</option>";
            for (let i = 0; i < Visitors.length; i++) {
                let item = Visitors[i];

                content += "<option value='" + item.ID + "' " + (_visitor == item.ID ? 'selected' : '') + '>' + item.Name + '</option>';
            }
            content += '</select>';

            content += 'ویزیتور کمکی:';
            content += "<select class='form-control' id='sVisitor2' onchange='Visitor2Changed();'>";
            content += "<option value='0'>---- ویزیتور ----</option>";
            for (let i = 0; i < Visitors.length; i++) {
                let item = Visitors[i];

                content += "<option value='" + item.ID + "' " + (_visitor2 == item.ID ? 'selected' : '') + '>' + item.Name + '</option>';
            }
            content += '</select>';

            content += 'سرپرست فروش:';
            content += "<select class='form-control' id='sManager'  onchange='ManagerChanged();'>";
            content += "<option value='0'>---- سرپرست فروش ----</option>";
            for (let i = 0; i < SaleManagers.length; i++) {
                let item = SaleManagers[i];

                content += "<option value='" + item.ID + "' " + (_manager == item.ID ? 'selected' : '') + '>' + item.Name + '</option>';
            }
            content += '</select>';

            content += "<div class='uk-text-center'><br><button class='btn btn-info' type='button' onclick='CloseVisitorSelect();'>بستن</button></div>";

            $('#btnSelectVisitor').attr('data-content', content);
        });

        _total = total;
        _final = final - takhfifOrder;
        _gifts = gifts.length;
        _samples = samples.length;

        if (CurrentOrder != null && _trial == -1 && _tpercent == -1) {
            $('#txtTakhfifPercent').val(CurrentOrder.TakhfifPercent);
            $('#txtTakhfifRial').val(CurrentOrder.TakhfifRial);
            _ttype = parseInt(CurrentOrder.TakhfifType);

        }

        if (_ttype == 1)
            ChangeTakhfifPercent();
        else
            ChangeTakhfifRial();

    }

    //UpdateBasketTakhfifs();
}

function VisitorChanged() {
    _visitor = $('#sVisitor').val();
}

function Visitor2Changed() {
    _visitor2 = $('#sVisitor2').val();
}

function ManagerChanged() {
    _manager = $('#sManager').val();
}

function CloseVisitorSelect() {
    $('#btnSelectVisitor').popover('hide');
}

function ChangeTax() {
    var p = parseInt(toEnglish($('#txtTax').val()));

    if (isNaN(p))
        p = 0;

    _tax = p;

    $('#dFinalPrice').html(numeral(_final + _tax - (_trial == -1 ? 0 : _trial)).format('0,0') + ' ' + Unit);

    CurrentOrder = null;
}

function ChangeTakhfifPercent() {
    var p = parseInt(toEnglish($('#txtTakhfifPercent').val()));

    if (isNaN(p))
        p = 0;

    var rial = parseInt((_final * p) / 100);
    _trial = rial;
    _tpercent = p;

    $('#txtTakhfifRial').val(rial);

    $('#dFinalPrice').html(numeral(_final + _tax - rial).format('0,0') + ' ' + Unit);

    if (p > 0) {
        _ttype = 1;
        $('#txtTakhfifRial').attr('readonly', 'readonly');
    }
    else {
        _ttype = 0;
        $('#txtTakhfifRial').removeAttr('readonly');
        $('#txtTakhfifPercent').removeAttr('readonly');
    }

    CurrentOrder = null;
}

function ChangeTakhfifRial() {
    var rial = parseInt(toEnglish($('#txtTakhfifRial').val()));

    if (isNaN(rial))
        rial = 0;

    var p = parseInt((rial * 100) / (_final));

    _trial = rial;
    _tpercent = p;

    $('#txtTakhfifPercent').val(p);

    $('#dFinalPrice').html(numeral(_final + _tax - rial).format('0,0') + ' ' + Unit);

    if (rial > 0) {
        _ttype = 2;
        $('#txtTakhfifPercent').attr('readonly', 'readonly');
    }
    else {
        _ttype = 0;
        $('#txtTakhfifRial').removeAttr('readonly');
        $('#txtTakhfifPercent').removeAttr('readonly');
    }

    CurrentOrder = null;
}

function RemoveFromBasket(id) {
    _ChangeCount(id, 0);
}

function SavePreOrder() {
    var rial = parseInt($('#txtTakhfifRial').val());
    var percent = parseFloat($('#txtTakhfifPercent').val());

    if (isNaN(rial))
        rial = 0;

    if (_final < rial) {
        ShowError('مبلغ تخفیف بیشتر از مبلغ درخواست می باشد.');
        return;
    }
    
    confirm('', 'از صحت اطلاعات ثبت شده مطمئن هستید؟', function() {

        let visitor = 0;

        if (Visitors != 0)
            visitor = _visitor;

        let manager = 0;

        if (SaleManagers != 0)
            manager = _manager;

        let b = [];
        for (let i = 0; i < basket.length; i++) {
            let it = basket[i];

            let item = {
                a: it.BasePrice,
                b: it.Count,
                c: it.ID,
                d: it.OldPrice,
                e: it.PID,
                f: it.Price
            };

            if (it.Price == 0) {
                ShowError('قیمت کالای "' + it.Name + '" صفر می باشد. لطفا بررسی نمایید.');
                return;
            }

            
            if (it.TakhfifPercent !== undefined)
                item.g = it.TakhfifPercent;

            if (it.TakhfifRial !== undefined)
                item.h = it.TakhfifRial;

            if (it.TakhfifType !== undefined)
                item.i = it.TakhfifType;

            b[b.length] = item;
        }

        ShowLoading();
        $.post(SiteUrl + '/orders/save',
               { gifts: _gifts, cid: CID,  samples: _samples, final: _final + _tax, items: JSON.stringify(b), call_id: CurrentCustomer.Type == 1 ? CurrentCustomer.CallID : 0, order: OrderID, visit_id: CurrentCustomer.Type == 2 ? CurrentCustomer.ID : 0, trial: rial, tpercent: percent, ttype: _ttype, EnabledGifts: _enabled_gifts, BasketGifts: _basket_gifts, return: _isReturn ? 1 : 0, visitor: visitor, manager: manager, buy: _isBuy ? 1 : 0, tax: _tax, customGifts: JSON.stringify(customGifts), tozih: _order_tozihat, visitor2: _visitor2 },
               function(data) {

                if (data.Result) {
                    ShowSuccess(data.Message);
                    $('#modal-catalog').modal('hide');
                    basket = [];
                    customGifts = [];
                    RefreshBasket();
                    try {
                        ChangeList();
                    }
                    catch (err) {}

                    try {
                        LoadTour(TourIndex);
                    }
                    catch (err){}

                    var count = parseInt(data.Data.Count);

                    if (count == 0) {
                        $('.pre-orders-button').addClass('hidden');
                    }
                    else {
                        $('.pre-orders-button').removeClass('hidden');
                        $('.pre-orders').html(count);
                    }
                }
                else {
                    ShowError(data.Message);
                }

               }, 'json').fail(function() {
                    ShowError('اتصال به سرور برقرار نشد.');
               }).always(function() {
                    HideLoading();
                    RefreshBasket();
               });
    });
}

function FindItemInBasket(id) {
    for (var i = 0; i < basket.length; i++) {
        if (parseInt(basket[i].PID) == parseInt(id))
            return basket[i];
    }

    return null;
}

function ToggleBasket() {
    if ($('#dBasketBack').css('display') == 'none') {
        $('#dBasketBack').css('display', 'block');
        $('.basket-container').css('bottom', '0px');
        $('#modal-catalog').addClass('uk-hidden');
    }
    else {
        $('#dBasketBack').css('display', 'none');
        $('.basket-container').css('bottom', 'calc(-1 * (70vh - 38px))');
        $('#modal-catalog').removeClass('uk-hidden');
    }
}
/**********************************    Show Products ********************************************/


/**********************************    PreOrders      ******************************************* */

function OpenModal(id) {
    //$(id + ' .my-modal-body').css('display', 'none');
    $(id + ' .my-modal-body').css('top', '-100vh');
    $(id).removeClass('hidden');
    //$(id + ' .my-modal-body').css('display', 'block');
    $(id + ' .my-modal-body').css('top', '3vh');

}

function CloseModal(id) {
    $(id).addClass('hidden');
}

function OpenPreOrders() {
    OpenModal('#modal-pre-orders');

    $('#gPreOrders').bootgrid('reload');
}

function ClosePreOrders() {
    CloseModal('#modal-pre-orders');
}

/**********************************    PreOrders      ******************************************* */


/**********************************    Orders         ******************************************* */

function ViewOrder(id) {
    Post(SiteUrl + '/orders/view',
        { id: id},
        function(data) {
            CurrentOrder = data.Data;
            $('.order-title').html(' سفارش شماره ' + data.Data.ID + ' (' + data.Data.FName + ' ' + data.Data.LName + ') '  + (CurrentOrder.IsBuy == 1 ? ' - خرید' : '') + (CurrentOrder.IsReturn == 1 ? '<span class="label label-danger" style="font-size: 80%;">درخواست مرجوعی</span>' : '') + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="' + SiteUrl + '/orders/print/' + data.Data.CodeRahgiri + '" target="_blank">پرینت ' + (data.Data.PrintCount > 0 ? '(' + data.Data.PrintCount + ')' : '') + '</a>');

            $('#order-body').html(data.Data.Html);

            OpenModal('#modal-view-order');

            try {
            JsBarcode(".barcode").init();
            }
            catch (err) {}

            try {
                new AMIB.persianCalendar('txtTarikhSanadOrder', { extraInputID: 'txtTarikhSanadOrder', extraInputFormat: 'yyyy/mm/dd' });
            }
            catch (err) {}

            try {
                new AMIB.persianCalendar('txtTarikhSanadStoreOrder', { extraInputID: 'txtTarikhSanadStoreOrder', extraInputFormat: 'yyyy/mm/dd' });
            }
            catch (err) {}

            $('#order-body').scrollTop(0);

            UIkit.lightboxPanel({});

            if (CustomerIsOpen)
                $('#modal-view-order').css('z-index', '1000007');
            else
                $('#modal-view-order').css('z-index', '2500');
        });
}

function EditOrder(id) {
    Post(SiteUrl + '/orders/get_order',
        { id: id, edit: 1},
        function(data) {
            
            //CurrentOrder = data;index, cid, name, order, basket_items, OrderData, isReturn
            ShowProducts(-1, data.Data.Order.UID, data.Data.Order.FName + ' ' + data.Data.Order.LName, id, data.Data.Order.Basket, data.Data.Order, data.Data.Order.IsReturn == '1');

            Visitors = data.Data.Visitors;
            SaleManagers = data.Data.Managers;
            _visitor = data.Data.Order.UserID;
            _visitor2 = data.Data.Order.Visitor2;
            _manager = data.Data.Order.SaleManager;
        }); 
}

function DeleteOrderById(id) {
    confirm('', 'آیا از حذف سفارش شماره ' + id + ' مطمئن هستید؟', function() {
        _DeleteOrder(id);
    });
}

function DeleteOrder(index) {
    let item = data[index];

    confirm('', 'آیا از حذف سفارش شماره ' + item.ID + ' مربوط به مشتری "' + item.Customer + '" مطمئن هستید؟', function() {
        _DeleteOrder(item.ID);
    });
}

function _DeleteOrder(id) {
    ShowLoading();

    $.post(SiteUrl + '/orders/delete_order',
            { id: id },
            function(data) {
                if (data.Result) {
                    ShowSuccess(data.Message);

                    try {
                        $('#grid').bootgrid('reload');
                    }
                    catch (err) {}

                    try {
                        $('#gPreOrders').bootgrid('reload');
                    }
                    catch (err) {}

                    var count = parseInt(data.Data.Count);

                    if (!isNaN(count)) {
                        if (count == 0) {
                            $('.pre-orders-button').addClass('hidden');
                        }
                        else {
                            $('.pre-orders-button').removeClass('hidden');
                            $('.pre-orders').html(count);
                        }
                    }

                }
                else {
                    ShowError(data.Message);
                }
            }, 'json').fail(function() {
                ShowError('اتصال به سرور برقرار نشد.')
            }).always(function() {
                HideLoading();
            });

}

function ShowNewOrder() {
    $('#dOrderDetails').addClass('uk-hidden');
    $('#dOrderCustomer').removeClass('disabled');
    $('#txtSearchCustomer').val('');
    $('#txtSearchCustomer').removeAttr('readonly');
    _CurrentCustomer = null;

    $('#modal-new-order').modal('show');
}

var Visitors = [];
var SaleManagers = [];
var _Store = 0;
function NewOrder(id, store, is_return, is_buy) {
    ShowLoading();
    _Store = store;

    if (_Store === undefined)
        _Store = 0;

    if (is_return === undefined)
        is_return = false;

    if (is_buy === undefined)
        is_buy = false;

    $.post(SiteUrl + '/products/get_customer',
            { id: id},
            function(data) {
                if (data.Result){
                    CurrentCustomer = {
                        Type : 0,
                        ID: id
                    };
                    ShowProducts(-1, id, data.Data.Name, 0, null, null, is_return, is_buy);
                    Visitors = data.Data.Visitors;
                    SaleManagers = data.Data.Managers;
                }
                else {
                    ShowError(data.Message);
                }
            }, 'json').fail(function() {
                ShowError('اتصال به سرور برقرار نشد.')
            }).always(function() {
                HideLoading();
            });
}

function GetPays() {
    var pays = [];
    var error = false;

    $('.pay-item').each(function(index, obj) {
        var type = $(obj).find('.pay-type').attr('data-type');

        try {
            if (type.trim() == '')
                return;
        }
        catch (err) {
            return;
        }

        var mablagh = $(obj).find('.pay-mablagh').attr('data-mablagh');
        var tozih = $(obj).find('.pay-tozih pre').html();
        var id = 0;
        
        if ($(obj).find('.pay-id').length > 0)
            id = $(obj).find('.pay-id').val();


        if (type == '1' || type == '2' || type == '5' || type == '6') {
            var bank = $(obj).find('.pay-bank').attr('data-bank');
            var tarikh = $(obj).find('.pay-tarikh').attr('data-tarikh');
            var saleid = (type == '6' ? '' : $(obj).find('.pay-saleid').attr('data-saleid'));

            pays[pays.length] = {
                ID: id,
                Type: type,
                Mablagh: mablagh,
                Bank: bank,
                Tarikh: tarikh,
                SaleID: saleid,
                Tozih: tozih
            };
    
        } 
        else if (type == '3') {
            var bank = $(obj).find('.pay-bank').attr('data-bank');
            var tarikh = $(obj).find('.pay-tarikh').attr('data-tarikh');
            var cheqName = $(obj).find('.pay-cheq-name').attr('data-cheq-name');
            var cheqSh = $(obj).find('.pay-cheq-sh').attr('data-cheq-sh');
            var cheqHesab = $(obj).find('.pay-cheq-hesab').attr('data-cheq-hesab');
            var cheqSerial = $(obj).find('.pay-cheq-serial').attr('data-cheq-serial');

            pays[pays.length] = {
                ID: id,
                Type: type,
                Mablagh: mablagh,
                Bank: bank,
                Tarikh: tarikh,
                CheqName: cheqName,
                CheqSh: cheqSh,
                CheqHesab: cheqHesab,
                CheqSerial: cheqSerial,
                Tozih: tozih
            };
        }
        else if (type == '4') {
            var bank = $(obj).find('.pay-bank').attr('data-bank');
            var tarikh = $(obj).find('.pay-tarikh').attr('data-tarikh');
            var saleid = $(obj).find('.pay-saleid').attr('data-saleid');
            var shCart = $(obj).find('.pay-sh-cart').attr('data-sh-cart');

            pays[pays.length] = {
                ID: id,
                Type: type,
                Mablagh: mablagh,
                Bank: bank,
                Tarikh: tarikh,
                SaleID: saleid,
                ShCart: shCart,
                Tozih: tozih
            };
        }

        var shSanad = '';
        var tarikhSanad = '';
        if (((CurrentOrder.Status == 3 || CurrentOrder == 8) && AccessMali) || AccessMali) {
            shSanad = $(obj).find('.pay-sh-sanad').attr('data-sh-sanad');
            tarikhSanad = $(obj).find('.pay-tarikh-sanad').attr('data-tarikh-sanad');

            if (shSanad.trim() == '') {
                error = true;
            }

            if (tarikhSanad.trim() == '') {
                error = true;
            }

            pays[pays.length - 1].ShSanad = shSanad;
            pays[pays.length - 1].TarikhSanad = tarikhSanad;
        }


    }); 

    if (error) {
        ShowError('یکی از شماره سند ها یا تاریخ سندها مقدار ندارد.');
        return 'error';
    }

    return JSON.stringify(pays);
}

function VerifyOrder(id, state, check) {
    if (check === undefined)
        check = true;

    
    var pays = null;
    if (CurrentOrder.Status <=5 || CurrentOrder.Status == 8 || state == 20) {
        pays = GetPays();

        if (pays == 'error')
            return;
    }

    var shSanad = '';
    var tarikhSanad = '';

    try {
        shSanad = $('#txtShSanadOrder').val();
        tarikhSanad = $('#txtTarikhSanadOrder').val();
    }
    catch (err) {}

    var shSanadStore = '';
    var tarikhSanadStore = '';

    try {
        shSanadStore = $('#txtShSanadStoreOrder').val();
        tarikhSanadStore = $('#txtTarikhSanadStoreOrder').val();
    }
    catch (err) {}

    if (check) {
        if (CurrentOrder.Status == 3 || CurrentOrder.Status == 8) {
            if (shSanad.trim() == '' || tarikhSanad.trim() == '') {
                ShowError('شماره سند یا تاریخ سند درخواست مقدار ندارد.');
                return false;
            }
        }

        if (CurrentOrder.Status == 7) {
            if (shSanadStore.trim() == '' || tarikhSanadStore.trim() == '') {
                ShowError('شماره رسید و تاریخ رسید انبار را وارد نمایید.');
                return false;
            }
        }
    }   
    
    confirm('', 'مطمئن هستید؟', function() {
        var tozih = $('#txtVerifyOrderTozih').val();


        Post(SiteUrl + '/orders/verify_order',
            { id: id, state: state, tozih: tozih, pays: pays, shSanad: shSanad, tarikhSanad: tarikhSanad, shSanadStore: shSanadStore, tarikhSanadStore: tarikhSanadStore, check: check ? 1 : 0 },
            function(data) {
                ShowSuccess(data.Message);
                try {
                    $('#gPreOrders').bootgrid('reload');
                }
                catch (err) {}
                CloseModal('#modal-view-order');

                try {
                    $('#gOrders').bootgrid('reload');
                }
                catch (err){}

                try {
                    $('#grid').bootgrid('reload');
                }
                catch (err) {}

                if (data.Data !== undefined) {
                    let cnt = parseInt(data.Data);

                    if (!isNaN(cnt)) {
                        $('.pre-orders-button .pre-orders').html(cnt);

                        if (cnt == 0) {
                            $('.pre-orders-button').addClass('uk-hidden');
                        }
                        else {
                            $('.pre-orders-button').removeClass('uk-hidden');
                        }
                    }
                }
            });
    });
}

function NewPay(obj) {
    var html = '';

    if (obj === undefined)
        obj = null;

    var type = $(obj).find('.pay-type').attr('data-type');

    if (type === undefined)
        type = '0';

    html += '<form method="post" id="fNewPay" class="uk-text-right" style="width: 100%;">';

    html += '<label for="sPayType">نوع پرداخت</label>';
    html += '<select class="uk-select" id="sPayType" onchange="PayTypeChange(this);" ' + (type != '0' ? 'disabled' : '') + '>';
    html += '<option value="0" ' + (type == '0' ? 'selected' : '') + '>---- انتخاب کنید ----</option>';
//    html += '<option value="1" ' + (type == '1' ? 'selected' : '') + '> پرداخت آنلاین  </option>';
    html += '<option value="5" ' + (type == '5' ? 'selected' : '') + '>پرداخت pos</option>';
    html += '<option value="6" ' + (type == '6' ? 'selected' : '') + '>پرداخت نقدی</option>';
    html += '<option value="2" ' + (type == '2' ? 'selected' : '') + '>واریز به حساب </option>';
    html += '<option value="3" ' + (type == '3' ? 'selected' : '') + '>چک</option>';
    html += '<option value="4" ' + (type == '4' ? 'selected' : '') + '>کارت به کارت</option>';
    html += '</select>';

    html += '<div id="dPayOptions"></div>';
    html += '<div class="uk-text-center uk-padding-small" id="dPayButtons"><a class="btn btn-danger uk-margin-small" onclick="CancelNewPay();">انصراف</a></div>';

    html += '</form>';

    if (obj != null) {

        var res = PayTypeChange(null, type, obj);

        var bank = $(obj).find('.pay-bank').attr('data-bank');

        $(obj).html(html);

        $('#dPayOptions').html(res);


        $('#sPayBank').val(bank);

        $('#dPayButtons').html( '<button type="submit" class="btn btn-success margin-5">تایید</button><a class="btn btn-danger margin-5" onclick="CancelNewPay();">انصراف</a>');
    
        new AMIB.persianCalendar('txtPayTarikh', { extraInputID: 'txtPayTarikh', extraInputFormat: 'yyyy/mm/dd' });
    
        $(obj).addClass('pay-new');
    }
    else {
        $('.pay-new').html(html);

        $('.pay-new').attr('onclick', '');
    }



    $('#fNewPay').submit(function() {
        var type = $('#sPayType').val();
        var edit = $('#hPayEdit').val();

        if (type == '0') {
            ShowError('نوع پرداخت را انتخاب نمایید.');
            return false;
        }
        else {
            var mablagh = parseInt(replaceAll(',', '', toEnglish($('#txtPayMablagh').val())));

            if (isNaN(mablagh) || mablagh <= 0) {
                ShowError('مبلغ را وارد نمایید.');
                return false;
            }

            var tarikh = toEnglish($('#txtPayTarikh').val()).trim();
            var tozih = $('#txtPayTozih').val().trim();

            var html = '';
            if (((CurrentOrder.Status == 3 || CurrentOrder.Status == 8) && AccessMali) || AccessMali) {
                var shSanad = $('#txtPayShSanad').val().trim();
                var tarikhSanad = $('#txtPayTarikhSanad').val().trim();

                if (shSanad === undefined)
                    shSanad = '';

                if (tarikhSanad === undefined)
                    tarikhSanad = '';

                html += 'شماره سند: ';
                html += '<div class="form-info pay-sh-sanad" data-sh-sanad="' + shSanad + '">' + (shSanad == '' ? '-' : shSanad) + '</div>';

                html += 'تاریخ سند: ';
                html += '<div class="form-info pay-tarikh-sanad" data-tarikh-sanad="' + tarikhSanad + '">' + (tarikhSanad == '' ? '-' : tarikhSanad) + '</div>';

            }

            if (type == "1" || type == '2' || type == '5' || type == '6') {
                var sale = $('#txtPaySaleID').val();
                var bankID = $('#sPayBank').val();
                var bank = $('#sPayBank option:selected').text();

                html += 'نوع پرداخت: ';
                html += '<div class="form-info pay-type" data-type="' + type + '" >';
                if (type == "1")
                    html += 'پرداخت آنلاین';
                else if (type == "2")
                    html += 'واریز به حساب';
                else if (type == '5')
                    html += 'پرداخت pos';
                else
                    html += 'پرداخت نقدی';
                html += '</div>';
                html += 'مبلغ: ';
                html += '<div class="form-info pay-mablagh" data-mablagh="' + mablagh + '">' + numeral(mablagh).format('0,0') + '</div>';
                if (type != 5 && type != 6) {
                    html += 'بانک: ';
                    html += '<div class="form-info pay-bank" data-bank="' + bankID + '">' + bank + '</div>';
                }
                html += 'تاریخ: ';
                html += '<div class="form-info pay-tarikh" data-tarikh="' + tarikh + '">' + tarikh + '</div>';
                if (type != 6) {
                    html += 'شماره پیگیری: ';
                    html += '<div class="form-info pay-saleid" data-saleid="' + sale + '">' + sale + '</div>';
                }
                
            }
            else if (type == '3') {
                var bank = $('#txtPayBank').val();
                var name = $('#txtPayCheqName').val();
                var sh = $('#txtPayShCheq').val();
                var serial = $('#txtPaySerialCheq').val();
                var hesab = $('#txtPayHesabCheq').val();


                html += 'نوع پرداخت: ';
                html += '<div class="form-info pay-type" data-type="' + type + '" >چک</div>';
                html += 'مبلغ: ';
                html += '<div class="form-info pay-mablagh" data-mablagh="' + mablagh + '">' + numeral(mablagh).format('0,0') + '</div>';
                html += 'بانک: ';
                html += '<div class="form-info pay-bank" data-bank="' + bank + '">' + bank + '</div>';
                html += 'تاریخ سررسید: ';
                html += '<div class="form-info pay-tarikh" data-tarikh="' + tarikh + '">' + tarikh + '</div>';
                html += 'صاحب حساب: ';
                html += '<div class="form-info pay-cheq-name" data-cheq-name="' + name + '">' + name + '</div>';
                html += 'شماره حساب جک:';
                html += '<div class="form-info pay-cheq-hesab" data-cheq-hesab="' + hesab + '">' + hesab + '</div>';
                html += 'شماره چک:';
                html += '<div class="form-info pay-cheq-sh" data-cheq-sh="' + sh + '">' + sh + '</div>';
                html += 'شماره سریال:';
                html += '<div class="form-info pay-cheq-serial" data-cheq-serial="' + serial + '">' + serial + '</div>';

            }
            else if (type == '4') {
                var bankID = $('#sPayBank').val();
                var bank = $('#sPayBank option:selected').text();
                var sale = $('#txtPaySaleID').val();
                var sh = $('#txtPayShCart').val();

                html += 'نوع پرداخت: ';
                html += '<div class="form-info pay-type" data-type="' + type + '" >کارت به کارت</div>';
                html += 'مبلغ: ';
                html += '<div class="form-info pay-mablagh" data-mablagh="' + mablagh + '">' + numeral(mablagh).format('0,0') + '</div>';
                html += 'بانک: ';
                html += '<div class="form-info pay-bank" data-bank="' + bankID + '">' + bank + '</div>';
                html += 'تاریخ: ';
                html += '<div class="form-info pay-tarikh" data-tarikh="' + tarikh + '">' + tarikh + '</div>';
                html += 'شماره پیگیری: ';
                html += '<div class="form-info pay-saleid" data-saleid="' + sale + '">' + sale + '</div>';
                html += 'شماره کارت:';
                html += '<div class="form-info pay-sh-cart" data-sh-cart="' + sh + '">' + sh + '</div>';
            }

            if (tozih != '') 
                html += '<div class="form-info pay-tozih" style="text-align: right;"><pre>' + tozih + '</pre></div>';


            if (edit == '0') {
                var res = '<div class="pay-item uk-text-right">';

				if (((CurrentOrder.Status == 0 || CurrentOrder.Status == 4) && CurrentOrder.UserID == UID) || ((CurrentOrder.Status == 2 || CurrentOrder.Status== 5) && RoleID == 2) || ((CurrentOrder.Status == 3 || CurrentOrder.Status == 8) && AccessMali)) {
                    res += '<div class="pay-options"><button class="btn btn-default circle-button" onclick="EditPay(this);"><i class="fas fa-edit"></i></button>&nbsp;&nbsp;<button class="btn btn-default circle-button" onclick="DeletePay(this);"><i class="fas fa-trash-alt"></i></button></div>';
                }
    
    
                res += html + '</div>';
                    
                var el = document.createElement('div');
                $(el).html(res);
                $(el).addClass('col-md-4 col-sm-6');

                $('#dPays').append(el);

                el = $('.pay-new').parent();

                $(el).remove();
                $('#dPays').append(el);
                CancelNewPay();
                //$(el).insertBefore('.pay-new');
            }
            else {
                var res = '';
				if (((CurrentOrder.Status == 0 || CurrentOrder.Status == 4) && CurrentOrder.UserID == UID) || ((CurrentOrder.Status == 2 || CurrentOrder.Status== 5) && RoleID == 2) || ((CurrentOrder.Status == 3 || CurrentOrder.Status == 8) && AccessMali) || AccessMali) {
                    res += '<div class="pay-options"><button class="btn btn-default circle-button" onclick="EditPay(this);"><i class="fas fa-edit"></i></button>&nbsp;&nbsp;<button class="btn btn-default circle-button" onclick="DeletePay(this);"><i class="fas fa-trash-alt"></i></button></div>';
                }

                res += html;

                var id = $('#hPayID').val();
                if (id === undefined)
                    id = 0;
                    
                res += '<input type="hidden" class="pay-id" value="' + id + '" />';

                

                $(this).parent().removeClass('pay-new');
                $(this).parent().html(res);

                $('.pay-new').removeClass('uk-hidden');
            }

            CalcTotalPays();

        }

        return false;
    });
}

function EditPay(obj) {
    $('.pay-new').addClass('uk-hidden');

    var parent = $(obj).parent().parent();

    NewPay(parent);

}

function DeletePay(obj) {
    confirm('', 'آیا مطمئن هستید؟', function() {
        var parent = $(obj).parent().parent().parent();

        parent.remove();

        CalcTotalPays();
    });
}

function PayTypeChange(obj, t, pay) {

    var type = t;
    if (obj != null)
        type = $(obj).val();

    if (pay === undefined)
        pay = null;

    var html = '<hr>';

    if (((CurrentOrder.Status == 3 || CurrentOrder.Status == 8) && AccessMali) || AccessMali) {
        var shSanad = '';
        var tarikhSanad = '';

        try {
            shSanad = $(pay).find('.pay-sh-sanad').attr('data-sh-sanad');
            tarikhSanad = $(pay).find('.pay-tarikh-sanad').attr('data-tarikh-sanad');

            if (shSanad === undefined)
                shSanad = '';

            if (tarikhSanad === undefined)
                tarikhSanad = '';

            html += '<label for="txtPayShSanad">شماره سند:</label>';
            html += '<input autocomplete="off" type="text" value="' + shSanad + '" data-value="' + shSanad + '" class="form-control  pay-input" placeholder="" id="txtPayShSanad" />';
    
            html += '<label for="txtPayTarikhSanad">تاریخ سند:</label>';
            html += '<input autocomplete="off" type="text" value="' + tarikhSanad + '" data-value="' + tarikhSanad + '" class="form-control  pay-input" placeholder="" id="txtPayTarikhSanad" />';
        }
        catch (err) {}
    }

    var bank = '';
    if (type == "1" || type == '5' || type == '6') {
        var mablagh = '';
        var tarikh = '';
        var saleid = '';

        try {
            mablagh = $(pay).find('.pay-mablagh').attr('data-mablagh');
            bank = $(pay).find('.pay-bank').attr('data-bank');
            tarikh = $(pay).find('.pay-tarikh').attr('data-tarikh');
            saleid = $(pay).find('.pay-saleid').attr('data-saleid');

            if (mablagh === undefined)
                mablagh = '';

            if (bank === undefined)
                bank = '';

            if (tarikh === undefined)
                tarikh = '';

            if (saleid === undefined)
                saleid = '';

        }
        catch (err){}

        html += '<label for="txtPayMablagh">مبلغ:</label>';
        html += '<input autocomplete="off" type="text" class="form-control pay-input" value="' + mablagh + '" data-value="' + mablagh + '" placeholder="0" id="txtPayMablagh" onkeyup="comma_delimited(this);" />';
        if (type != 5 && type != 6) {
            html += '<label for="sPayBank">بانک:</label>';
            html += '<select class="uk-select pay-input" id="sPayBank" data-value="' + bank + '">' + $('#dBanks').html() + '</select>';
        }
        html += '<label for="txtPayTarikh">تاریخ:</label><br>';
        html += '<div class="tarikh-container" id="txtPayTarikhCon"><input autocomplete="off" type="text" value="' + tarikh + '" data-body="#txtPayTarikhCon" data-value="' + tarikh + '" class="form-control  pay-input tarikh-input" placeholder="1399/01/01" id="txtPayTarikh" /></div>';
        /*html += '<div class="input-group">';
        html += '<div class="input-group-addon" data-MdDateTimePicker="true" data-trigger="click" data-targetselector="#exampleInput3">';
        html += '    <span class="glyphicon glyphicon-calendar"></span>';
        html += '</div>';
        html += '<input autocomplete="off" type="text" class="form-control" id="txtPayTarikh" placeholder="تاریخ" data-MdDateTimePicker="true" data-placement="bottom" />';
        html += '</div>';*/
        if (type != '6') {
            html += '<label for="txtPaySaleID">شماره پیگیری:</label>';
            html += '<input autocomplete="off" type="text" value="' + saleid + '" data-value="' + saleid + '" class="form-control  pay-input" placeholder="" id="txtPaySaleID" />';
        }
    }
    else if (type == "2") {
        var mablagh = '';
        var tarikh = '';
        var saleid = '';

        try {
            mablagh = $(pay).find('.pay-mablagh').attr('data-mablagh');
            bank = $(pay).find('.pay-bank').attr('data-bank');
            tarikh = $(pay).find('.pay-tarikh').attr('data-tarikh');
            saleid = $(pay).find('.pay-saleid').attr('data-saleid');

            if (mablagh === undefined)
                mablagh = '';

            if (bank === undefined)
                bank = '';

            if (tarikh === undefined)
                tarikh = '';

            if (saleid === undefined)
                saleid = '';

        }
        catch (err){}

        html += '<label for="txtPayMablagh">مبلغ:</label>';
        html += '<input autocomplete="off" type="text" value="' + mablagh + '" data-value="' + mablagh + '" class="form-control pay-input" placeholder="0" id="txtPayMablagh" onkeyup="comma_delimited(this);" />';
        html += '<label for="sPayBank">بانک:</label>';
        html += '<select class="uk-select pay-input" id="sPayBank" data-value="' + bank + '">' + $('#dBanks').html() + '</select>';
        html += '<label for="txtPayTarikh">تاریخ:</label><br>';
        html += '<div class="tarikh-container" id="txtPayTarikhCon"><input autocomplete="off" type="text" value="' + tarikh + '" data-body="#txtPayTarikhCon" data-value="' + tarikh + '" class="form-control  pay-input tarikh-input" placeholder="1399/01/01" id="txtPayTarikh" /></div>';
        html += '<label for="txtPaySaleID">شماره پیگیری:</label>';
        html += '<input autocomplete="off" type="text" value="' + saleid + '" data-value="' + saleid + '" class="form-control pay-input" placeholder="" id="txtPaySaleID" />';

    }
    else if (type == "3") {
        var mablagh = '';
        var tarikh = '';
        var cheqName = '';
        var cheqSh = '';
        var cheqHesab = '';
        var cheqSerial = '';

        try {
            mablagh = $(pay).find('.pay-mablagh').attr('data-mablagh');
            bank = $(pay).find('.pay-bank').attr('data-bank');
            tarikh = $(pay).find('.pay-tarikh').attr('data-tarikh');
            cheqName = $(pay).find('.pay-cheq-name').attr('data-cheq-name');
            cheqSh = $(pay).find('.pay-cheq-sh').attr('data-cheq-sh');
            cheqHesab = $(pay).find('.pay-cheq-hesab').attr('data-cheq-hesab');
            cheqSerial = $(pay).find('.pay-cheq-serial').attr('data-cheq-serial');

            if (mablagh === undefined)
                mablagh = '';

            if (bank === undefined)
                bank = '';

            if (tarikh === undefined)
                tarikh = '';

            if (cheqName === undefined)
                cheqName = '';

            if (cheqSh === undefined)
                cheqSh = '';

            if (cheqHesab === undefined)
                cheqHesab = '';

            if (cheqSerial === undefined)
                cheqSerial = '';
        }
        catch (err){}

        html += '<label for="txtPayMablagh">مبلغ:</label>';
        html += '<input autocomplete="off" type="text" value="' + mablagh + '" data-value="' + mablagh + '" class="form-control pay-input" placeholder="0" id="txtPayMablagh" onkeyup="comma_delimited(this);" />';
        html += '<label for="sPayBank">بانک:</label>';
        html += '<input autocomplete="off" type="text" value="' + bank + '" data-value="' + bank + '" class="form-control pay-input" placeholder="" id="txtPayBank" />';
        html += '<label for="txtPayCheqName">صاحب چک:</label><br>';
        html += '<input autocomplete="off" type="text" value="' + cheqName + '" data-value="' + cheqName + '" class="form-control pay-input" placeholder="" id="txtPayCheqName" />';
        html += '<label for="txtPayTarikh">تاریخ سررسید:</label><br>';
        html += '<div class="tarikh-container" id="txtPayTarikhCon"><input type="text" value="' + tarikh + '" data-body="#txtPayTarikhCon" data-value="' + tarikh + '" class="form-control  pay-input tarikh-input" placeholder="1399/01/01" id="txtPayTarikh" /></div>';
        html += '<label for="txtPayHesabCheq">شماره حساب:</label><br>';
        html += '<input autocomplete="off" type="text" value="' + cheqHesab + '" data-value="' + cheqHesab + '" class="form-control pay-input" placeholder="" id="txtPayHesabCheq" />';
        html += '<label for="txtPayShCheq">شماره چک:</label><br>';
        html += '<input autocomplete="off" type="text" value="' + cheqSh + '" data-value="' + cheqSh + '" class="form-control pay-input" placeholder="" id="txtPayShCheq" />';
        html += '<label for="txtPaySerialCheq">سریال چک:</label><br>';
        html += '<input autocomplete="off" type="text" value="' + cheqSerial + '" data-value="' + cheqSerial + '" class="form-control pay-input" placeholder="" id="txtPaySerialCheq" />';

    }
    else if (type == '4') {
        var mablagh = '';
        var tarikh = '';
        var saleid = '';
        var shcart = '';

        try {
            mablagh = $(pay).find('.pay-mablagh').attr('data-mablagh');
            bank = $(pay).find('.pay-bank').attr('data-bank');
            tarikh = $(pay).find('.pay-tarikh').attr('data-tarikh');
            saleid = $(pay).find('.pay-saleid').attr('data-saleid');
            shcart = $(pay).find('.pay-sh-cart').attr('data-sh-cart');

            if (mablagh === undefined)
                mablagh = '';

            if (bank === undefined)
                bank = '';

            if (tarikh === undefined)
                tarikh = '';

            if (saleid === undefined)
                saleid = '';

            if (shcart === undefined)
                shcart = '';
        }
        catch (err){}

        html += '<label for="txtPayMablagh">مبلغ:</label>';
        html += '<input autocomplete="off" type="text" value="' + mablagh + '" data-value="' + mablagh + '" class="form-control pay-input" placeholder="0" id="txtPayMablagh" onkeyup="comma_delimited(this);" />';
        html += '<label for="sPayBank">بانک:</label>';
        html += '<select class="uk-select pay-input" id="sPayBank" data-value="' + bank + '">' + $('#dBanks').html() + '</select>';
        html += '<label for="txtPayTarikh">تاریخ:</label><br>';
        html += '<div class="tarikh-container" id="txtPayTarikhCon"><input autocomplete="off" type="text" value="' + tarikh + '" data-body="#txtPayTarikhCon" data-value="' + tarikh + '" class="form-control  pay-input tarikh-input" placeholder="1399/01/01" id="txtPayTarikh" /></div>';
        html += '<label for="txtPaySaleID">شماره پیگیری:</label>';
        html += '<input autocomplete="off" type="text" value="' + saleid + '" data-value="' + saleid + '" class="form-control pay-input" placeholder="" id="txtPaySaleID" />';
        html += '<label for="txtPayShCart">شماره کارت:</label>';
        html += '<input autocomplete="off" type="text" value="' + shcart + '" data-value="' + shcart + '" class="form-control pay-input" placeholder="" id="txtPayShCart" />';
    }

    var tozih = '';
    try {
        tozih = $(pay).find('.pay-tozih pre').html();

        if (tozih === undefined)
            tozih = '';
    }
    catch(Err) {}


    
        
    html += '<label for="txtPayTozih">توضیحات:</label>';
    html += '<textarea class="form-control" placeholder="" id="txtPayTozih">' + tozih + '</textarea>';

    if (pay != null) {
        var id = $(pay).find('.pay-id').val();

        if (id === undefined)
            id = '0';

        html += '<input type="hidden" value="' + id + '" class="pay-id" id="hPayID" />';
        html += '<input type="hidden" value="1" id="hPayEdit" />';

        return html;
    }

    html += '<input type="hidden" value="0" id="hPayEdit" />';

    $('#dPayButtons').html( '<button type="submit" class="btn btn-success margin-5">تایید</button><a class="btn btn-danger margin-5" onclick="CancelNewPay();">انصراف</a>');

    $('#dPayOptions').html(html);

    new AMIB.persianCalendar('txtPayTarikh', { extraInputID: 'txtPayTarikh', extraInputFormat: 'yyyy/mm/dd' });
    //$('#txtPayTarikh').MdPersianDateTimePicker({placement: 'left'});

}

function comma_delimited(obj) {
    var price = parseInt(replaceAll(',', '', $(obj).val()));

    if (!isNaN(price)) {
        $(obj).val(numeral(price).format('0,0'));
    }
}

function CancelNewPay() {
    if ($('#hPayEdit').val() == '1') {
        $('.pay-input').each(function(index, obj) {
            $(obj).val($(obj).attr('data-value'));

            $('#fNewPay').submit();
        });
    }
    else {
        $('.pay-new').html('<div class="uk-margin-auto uk-margin-auto-vertical" ><i class="fas fa-plus"></i></div>');

        setTimeout("$('.pay-new').attr('onclick', 'NewPay()');", 500);
    }
}

function CalcTotalPays() {
    var total = 0;

    $('.pay-item').each(function(index, obj) {
    
        var s = parseInt($(obj).find('.pay-mablagh').attr('data-mablagh'));

        if (!isNaN(s))
            total += s;


    });

    $('.pay-kol').html(numeral(total).format('0,0'));

    var all = parseInt($('#hAllPrice').val());

    var mande = total - all;

    $('.pay-mande').removeClass('red');
    $('.pay-mande').removeClass('green');
    if (mande < 0) {
        $('.pay-mande').addClass('red');
    }
    else {
        $('.pay-mande').addClass('green');
    }

    $('.pay-mande').html(numeral(Math.abs(mande)).format('0,0') + (mande == 0 ? '' : (mande < 0 ? ' (بد)' : ' (بس)')));
}

/**********************************    Orders         ******************************************* */


/**********************************    Map             ********************************************/
var vmap = null;
function InitMap(point, zoom) {
    if (point === undefined || point == null)
        point = [36.27673191955526,59.60503578186035];

    if (zoom === undefined || zoom == null)
        zoom = 13;

    if (vmap == null) {
        vmap = new L.Map('view-map', {
            key: 'web.AgDsRCKODrsiSkXMkgpZ1MZni5obWQyP3YCkCrtB',
            maptype: 'dreamy',
            poi: true,
            traffic: false,
            center: point,
            zoom: zoom
        });

        /*L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ''
        }).addTo(vmap);*/

        LocIcon = L.icon({
            iconUrl: BaseUrl + 'assets/img/location.svg',
            iconSize: [48, 48], 
            popupAnchor: [0, 0]
            });    
    }
 
    setTimeout(() => {
        vmap.invalidateSize();
    }, 1000);   
}
/**********************************    Map             ********************************************/

function clear_filter(name) {
    eval('RemoveReportFilter_' + name + '();');
}

function get_filter_values(name) {
    var values = '';
    if (name !== undefined) {
        $('#dRFilter_' + name + ' .filter-item.active').each(function(index, obj) {
            var id = $(obj).attr('data-id');

            if (values != '')
                values += ',';

            values += id;
        });
    }

    return values;
}

function get_filter_value(name) {
    return $('#txt_filter_' + name).val();
}

let AdvisorID = 0;
function AddAdvisorOrder() {
    AdvisorID = 0;
    OldCodes = '';
    AdvisorBasket = [];
    $('#txtSearchProduct').val('');
    SearchBrands('');
    $('#modal-add-advisor-order').modal('show');

    RefreshAdvisorBasket();
    document.getElementById('fAdvisorOrder').reset();
}

function EditAdvisorOrder(id) {
    ShowLoading();

    $.post(SiteUrl + '/advisor/get_order',
            { id: id },
            function(data) {    
                if (data.Result) {
                    AdvisorID = id;
                    OldCodes = '';
                    AdvisorBasket = data.Data.Items;
                    $('#txtSearchProduct').val('');
                    SearchBrands('');
                    $('#modal-add-advisor-order').modal('show');
                
                    RefreshAdvisorBasket();
                    document.getElementById('fAdvisorOrder').reset();
                    $('#txtAdvisorFName').val(data.Data.FName);
                    $('#txtAdvisorLName').val(data.Data.LName);
                    $('#txtAdvisorMobile').val(data.Data.Mobile);
                }
                else {
                    ShowError(data.Message);
                }
            }, 'json').fail(function() {
                ShowError('اتصال به سرور برقرار نشد.')
            }).always(function() {
                HideLoading();
            });

}

function setupLiveReader(resultElement) {
    var closeButton = $(
      '<button class="uk-button uk-button-primary uk-width-1-1" onclick="stopBarcodeReader()">بستن</button>'
    )
    var container = document.createElement('div')
  
    container.style.position = 'absolute'
    container.style.zIndex = '3000'
    container.style.width = '100%'
    container.style.height = '100%'
    container.style.left = '0'
    container.style.top = '0'
    container.style.background = '#474C55'
    container.id = 'barcode-reader'
  
    var canvas = document.createElement('canvas')
    var video = document.createElement('video')
    var context = canvas.getContext('2d')
  
    canvas.style.position = 'absolute'
  
    container.appendChild(closeButton[0])
    container.appendChild(canvas)
  
    document.body.appendChild(container)
  
    const constraints = {
      audio: false,
      video: {
        facingMode: 'environment'
      }
    }
  
    navigator.mediaDevices
      .getUserMedia(constraints)
      .then(function(stream) {
        window.currentStream = stream.getTracks()[0]
        video.width = 320
  
        BarcodeScanner.init()
        BarcodeScanner.streamCallback = function(result) {
          console.log('barcode detected, stream will stop');
          //console.log(result);
          //resultElement.value = result[0].Value
  
          for (let i = 0; i < result.length; i++) 
            BarcodeReaded(result[i].Value);

          //BarcodeScanner.StopStreamDecode()
          //stopBarcodeReader()
        }
  
        video.setAttribute('autoplay', '')
        video.setAttribute('playsinline', '')
        video.setAttribute('style', 'width: 100%')
        video.srcObject = stream
        container.appendChild(video)
        video.onloadedmetadata = function(e) {
          var canvasSetting = {
            x: 50,
            y: 20,
            width: 200,
            height: 30
          }
          var rect = video.getBoundingClientRect()
          canvas.style.height = rect.height + 'px'
          canvas.style.width = rect.width + 'px'
          canvas.style.top = rect.top + 'px'
          canvas.style.left = rect.left + 'px'
          const overlayColor = 'rgba(71, 76, 85, .9)'
          context.fillStyle = overlayColor
          context.fillRect(0, 0, rect.width, rect.height)
          context.clearRect(
            canvasSetting.x,
            canvasSetting.y,
            canvasSetting.width,
            canvasSetting.height
          )
          context.strokeStyle = '#ff671f'
          context.strokeRect(
            canvasSetting.x,
            canvasSetting.y,
            canvasSetting.width,
            canvasSetting.height
          )
          video.play()
          BarcodeScanner.DecodeStream(video)
        }
      })
      .catch(function(err) {
        console.log(err)
      })
  }
  
  function stopBarcodeReader() {
    var barcodeContainer = document.getElementById('barcode-reader')
    document.body.removeChild(barcodeContainer)
  
    window.currentStream.stop()
  }
  
function SendCode(id, send) {
    if (send === undefined)
        send = 0;
    //confirm('', 'آیا مطمئن هستید؟', function() {
        if ($('#dSendCode').hasClass('uk-hidden') || send == 1) {
            ShowLoading();

            $.post(SiteUrl + '/orders/send_code',
                    { id: id, send: send, sms: $('#txtSendCode').val() },
                    function(data) {
                        if (data.Result) {
                            if (send == 1) {
                                $('#dSendCode').addClass('uk-hidden');

                                ShowSuccess(data.Message);

                                ViewOrder(id);

                                try {
                                    $('#grid').bootgrid('reload');
                                }
                                catch (err) {}

                                try {
                                    $('#gPreOrders').bootgrid('reload');
                                }
                                catch (err) {}
                            }
                            else {
                                $('#txtSendCode').val(data.Message);

                                $('#dSendCode').removeClass('uk-hidden');
                            }
                        }
                        else {
                            ShowError(data.Message);
                        }
                    }, 'json').fail(function() {
                        ShowError('اتصال به سرور برقرار نشد.');
                    }).always(function() {
                        HideLoading();
                    });
        }
        else {
            $('#dSendCode').addClass('uk-hidden');
        }
 //   });
}

function SendCodeSms(id) {
    confirm('', 'آیا مطمئن هستید؟', function() {
        SendCode(id, 1);
    });
}

function ReturnOrderById(id) {
    confirm('', 'آیا مطمئن هستید؟', function(res) {
        if (res) {
            Post(SiteUrl + '/orders/get_order',
            { id: id },
            function(data) {
                ShowProducts(-1, data.Data.Order.UID, data.Data.Order.FName + ' ' + data.Data.Order.LName, 0, data.Data.Order.Basket, data.Data.Order, true);

            }); 

        }
    })
}