<div class="row">
    <div class="col-12">
        <div class="col-6">مجرد - عدم انتقال به سایر بخش ها</div>

    </div>
</div>

<table id="grid" class="table table-condensed table-hover table-striped">
    <thead>
        <th data-width="15%" data-column-id="code_melli" data-formatter="code_melli" data-header-align="center" data-css-class="td-grid">کد ملی</th>
        <th data-width="15%" data-column-id="fname" data-formatter="name" data-header-align="center" data-css-class="td-grid">نام و نام خانوادگی</th>
        <th data-width="12%"data-column-id="sh_letter" data-formatter="sh_letter" data-header-align="center" data-css-class="td-grid">شماره نامه</th>
        <th data-width="12%" data-column-id="date_letter" data-formatter="date_letter" data-header-align="center" data-css-class="td-grid">تاریخ نامه</th>
        <th data-width="15%" data-column-id="moarefi" data-formatter="moarefi" data-header-align="center" data-css-class="td-grid">معرفی</th>
        <!-- <th data-width="15%" data-column-id="moavenat" data-formatter="moavenat" data-header-align="center" data-css-class="td-grid">معاونت</th> -->
        <?php if ($Admin) { ?>
        <th data-formatter="options" data-header-align="center" data-width="7%" data-css-class="td-grid"></th>
        <?php } ?>
    </thead>
</table>



<div class="modal fade" id="modal-view" tabindex="1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">مشاهده اطلاعات فرد</h4>
            </div>

            <div class="modal-body">
                <div class="row" id="dPersonInfo"></div>
                <hr>
                <label>نامه های ثبت شده</label>
                <table class="table table-hover table-striped">
                    <thead>
                        <th class="td-grid">تاریخ ثبت</th>
                        <th class="td-grid">شماره</th>
                        <th class="td-grid">تاریخ نامه</th>
                        <th class="td-grid">معرفی</th>
                        <th class="td-grid">توضیح</th>
                        <th class="td-grid">معاونت</th>
                        <th class="td-grid" style="width: 50px"></th>
                        <th class="td-grid" style="width: 50px"></th>
                    </thead>
                    <tbody id="dLetters"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit" tabindex="2">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title"> ویرایش نامه </h4>
            </div>

            <div class="modal-body">
                <form id="fLetter" method="POST">
                    <label for="txtEditSh">شماره نامه</label>
                    <input type="text" class="form-control" id="txtEditSh">
                    <label for="txtEditDate">تاریخ نامه</label>
                    <input type="text" class="form-control" id="txtEditDate">
                    <label for="txtEditMoarefi">معرفی از</label>
                    <input type="text" class="form-control" id="txtEditMoarefi">
                    <label for="txtEditTozih">توضیح</label>
                    <textarea rows="3" class="form-control" id="txtEditTozih"></textarea>
                    <label for="txtEditMoavenat">معاونت</label>
                    <textarea rows="3" class="form-control" id="txtEditMoavenat"></textarea>
                    <label for="txtEditMolahezat">ملاحظات</label>
                    <textarea rows="3" class="form-control" id="txtEditMolahezat"></textarea><hr>
                    <div class="text-center">
                        <button class="btn btn-success">ذخیره</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript">
    
    var Info = null;
    var CurrentItem = null;

    function CheckCodeMelli() {
        $('#btnCheck').html('<div class="text-center" ><img src="<?php echo base_url('assets/img/loading.gif'); ?>" style="width: 24px;" /></div>');

        $.post('<?php echo site_url('main/check_code_melli'); ?>', { code: $('#txtCodeMelli').val() }, function(data) {
            if (data.Result == 1) {
                
                $('.wait-for').removeClass('disabled');

                if (data.Data != null) {
                    let item = data.Data;

                    $('#txtCodeMelli').val(item.code_melli);
                    $('#txtFName').val(item.fname);
                    $('#txtLName').val(item.lname);
                    if (item.gender == 2)
                        document.getElementById('rbtnFemale').checked = true;
                    else
                        document.getElementById('rbtnMale').checked = true;
                }
                
                
            }
            else {
                ShowError(data.Message);
            }
        }, 'json').fail(function() {
            ShowError('اتصال به سرور برقرار نشد');
        }).always(function() {
            $('#btnCheck').html('<i class="fa fa-search"></i>');
        });
    }

    function CheckValidCodeMelli() {
        let code = $('#txtCodeMelli').val();

        if ($.isNumeric(code) && code.length == 10)
            CheckCodeMelli();
    }

    function Add() {
        document.getElementById('fPerson').reset();

        $('#modal-add').modal('show');

        $('.wait-for').addClass('disabled');
    }

    function tooltip(title, text) {
        if (text === undefined)
            text = title;

        return '<span data-toggle="tooltip" title="' + text + '">' + title + '</span>';
    } 

    function create_info(title, value, width) {
        if (width === undefined)
            width = 6;

        let res = '';

        res = '<div class="col-md-' + width + '">';
        res += '<label>' + title + '</label>';
        res += '<div class="form-info">&nbsp;' + value + '</div></div>';

        return res;
    }

    function EditLetter(index) {
        let item = Info.letters[index];
        CurrentItem = item;

        $('#txtEditSh').val(item.sh_letter);
        $('#txtEditDate').val(item.date_letter);
        $('#txtEditMoarefi').val(item.moarefi);
        $('#txtEditTozih').val(item.tozih);
        $('#txtEditMoavenat').val(item.moavenat);
        $('#txtEditMolahezat').val(item.molahezat);

        $('#modal-edit').modal('show');
    }

    function DeleteLetter(index) {
        let item = Info.letters[index];
        CurrentItem = item;

        confirm('هشدار', 'آیااز حذف نامه اطمینان دارید؟', function(res) {
            if (res) {
                ShowLoading();

                $.post('<?php echo site_url('main/delete_letter'); ?>', { id: item.id }, function(data) {
                    if (data.Result == 1) {
                        $('#modal-view').modal('hide');
                        setTimeout(() => {
                            ViewInfo(Info.id);    
                        }, 500);
                        
                    }
                    else {
                        ShowError(data.Message);
                    }
                }, 'json').fail(function( ) {
                    ShowError('اتصال به سرور برقرار نشد');
                }).always(function() {
                    HideLoading();
                });
            }
        });
    }

    function SetInfo(info) {
        let html = '';

        html += create_info('کد ملی', info.code_melli);
        html += create_info('نام و نام خانوادگی', info.fname + ' ' + info.lname);
        html += create_info('تاریخ اولین ثبت', info.shamsi_date + ' ' + info.shamsi_time);
        html += create_info('جنسیت', info.gender == 1 ? 'مرد' : 'زن');

        $('#dPersonInfo').html(html);

        html = '';

        for (let i = 0; i < info.letters.length; i++) {
            let item = info.letters[i];

            html += '<tr>';

            html += '<td class="td-grid">' + tooltip(item.shamsi_date + ' ' + item.shamsi_time) + '</td>';
            html += '<td class="td-grid">' + tooltip(item.sh_letter) + '</td>'; 
            html += '<td class="td-grid">' + tooltip(item.date_letter) + '</td>';
            html += '<td class="td-grid">' + tooltip(item.moarefi) + '</td>';
            html += '<td class="td-grid">' + tooltip(item.tozih) + '</td>';
            html += '<td class="td-grid">' + tooltip(item.molahezat) + '</td>';
            html += '<td class="td-grid"><button class="btn btn-info" title="ویرایش" onclick="EditLetter(' + i + ')" data-toggle="tooltip"><i class="fa fa-edit"></i></button></td>';
            html += '<td class="td-grid"><button class="btn btn-danger" title="حذف" onclick="DeleteLetter(' + i + ')" data-toggle="tooltip"><i class="fa fa-trash"></i></button></td>';
                

            html += '</tr>';
        }

        $('#dLetters').html(html);

        $('[data-toggle="tooltip"]').tooltip();


    }

    function ViewInfo(id, showLoad) {
        if (showLoad === undefined)
            showLoad = true;

        if (showLoad)
            ShowLoading();

        $.post('<?php echo site_url('main/get_info'); ?>', { id: id}, function(data) {
            if (data.Result == 1) {
                let html = '';
                let info = data.Data;
                Info = info;

                SetInfo(info);

                $('#modal-view').modal('show');
            }   
            else {
                ShowError(data.Message);
            }
        }, 'json').fail(function() {
            ShowError('اتصال به سرور برقرار نشد');
        }).always(function() {
            if (showLoad)
                HideLoading();
        });
    }

    $(document).ready(function() {
        $('#grid').bootgrid({
            ajax: true,
            columnSelection: true,
            post: function() {
                return {
                   
                };
            },
            url: '<?php echo site_url('main/get_list'); ?>',
            formatters: {
                'code_melli': function(col, row) {
                    return tooltip(row.code_melli);
                },
                'sh_letter': function(col, row) {
                    return tooltip(row.sh_letter);
                },
                'date_letter': function(col, row) {
                    return tooltip(row.date_letter);
                },
                'moarefi': function(col, row) {
                    return tooltip(row.moarefi);
                }
                , 'tozih': function(col, row) {
                    return tooltip(row.tozih);
                },
                'name': function(col, row) {
                    return tooltip(row.fname + ' ' + row.lname);
                },
                'gender': function(col, row) {
                    if (row.gender == 1)
                        return tooltip('<span class="text-success">مرد</span>', 'مرد');
                    else
                        return tooltip('<span class="text-info">زن</span>', 'زن');
                },
                'options': function(col, row) {
                    return '<button class="btn btn-info btn-inline" title="مشاهده اطلاعات" data-toggle="tooltip" onclick="ViewInfo(' + row.id + ')"><i class="fa fa-search"></i></button>';
                }
            }
        }).on('load.rs.jquery.bootgrid', function(e) {
            
        }).on('loaded.rs.jquery.bootgrid', function(e) {
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#fLetter').submit(function() {
            try {
                let sh = $('#txtEditSh').val();
                let date = $('#txtEditDate').val();
                let moarefi = $('#txtEditMoarefi').val();
                let tozih = $('#txtEditTozih').val();
                let molahezat = $('#txtEditMolahezat').val();
                let moavenat = $('#txtEditMoavenat').val();

                ShowLoading();
                $.post('<?php echo site_url('main/edit_letter'); ?>', { id: CurrentItem.id, sh: sh, date: date, moarefi: moarefi, tozih: tozih, molahezat: molahezat, moavenat: moavenat }, function(data) {
                    if (data.Result == 1) {
                        $('#modal-edit').modal('hide');

                        Info = data.Data;

                        SetInfo(Info);

                    }
                    else {
                        ShowError(data.Message);
                    }
                }, 'json').fail(function() {
                    ShowError('اتصال به سرور برقرار نشد');
                }).always(function() {
                    HideLoading();
                });
            }
            catch (err) {
                console.log(err);
            }
            

            return false;
        });

        $('#fPerson').submit(function() {
            try {
                let code = $('#txtCodeMelli').val().trim();
                let gender = document.getElementById('rbtnMale').checked ? 1 :2;
                let fname = $('#txtFName').val().trim();
                let lname = $('#txtLName').val().trim();
                let sh = $('#txtNewSh').val();
                let date = $('#txtNewDate').val();
                let moarefi = $('#txtNewMoarefi').val();
                let tozih = $('#txtNewTozih').val();
                let molahezat = $('#txtNewMolahezat').val();

                if (code == '' || fname == '' || lname == '') {
                    ShowError('اطلاعات فرد را بررسی نمایید.');
                    return false;
                } 

                ShowLoading();
                $.post('<?php echo site_url('main/add_person'); ?>', {
                    code, gender, fname, lname, sh, date, moarefi, tozih, molahezat
                }, function(data) {
                    if (data.Result == 1) {
                        ShowSuccess(data.Message);

                        $('#modal-add').modal('hide');

                        $('#grid').bootgrid('reload');
                    }
                    else {
                        ShowError(data.Message);
                    }
                }, 'json').fail(function() {
                    ShowError('اتصال به سرور برقرار نشد.');
                }).always(function() {
                    HideLoading();
                })
            }
            catch (err) {}

            return false;
        });
    });
</script>