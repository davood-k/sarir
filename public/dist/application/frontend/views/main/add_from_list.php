
<style>
#b64data{
	width:100%;
}
a { text-decoration: none }
#drop-zone{
	background:#fff;
	position: fixed;
	top: 0px;
	left: 0px;
	width: 100vw;
	height: 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
	opacity: 0;
	z-index: -1;
}
#drop-zone p, #drop-zone svg { pointer-events: none }
#drop-zone svg { margin-right: 5px }
</style>
</head>
<body>
<div class="uk-text-left">
    <button class="btn btn-success" onclick="Add()">افزودن</button>
</div>
<select name="format" style="display: none;" onchange="setfmt()">
<option value="csv"> CSV</option>
<option value="json" selected> JSON</option>
<option value="form"> FORMULAE</option>
<option value="html"> HTML</option>
<option value="xlsx"> XLSX</option>
</select>
فایل مورد نظر را انتخاب نمایید:
<br> 
<br>
<div class="text-center">
    <input type="file" name="xlfile" id="xlf" style="display: inline-block !important;" />
</div>

<textarea id="b64data" style="display: none;">... or paste a base64-encoding here</textarea>
<input type="button" id="dotext" style="display: none;" value="Click here to process the base64 text" onclick="b64it();"/><br />
<div style="display: none;">
<b>Advanced Demo Options:</b>
Use Web Workers when available: <input type="checkbox" name="useworker" checked>
Always use UTF8 for CSV / text: <input type="checkbox" name="useutf8" checked>
</div>
<pre id="out" style="display: none;"></pre>
<div id="htmlout" style="display: none;"></div>
<div id="drop-zone">
	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M17 13h-10v-1h10v1zm0-4h-10v1h10v-1zm0-3h-10v1h10v-1zm-15-1v-5h6v2h-4v3h-2zm8-5v2h4v-2h-4zm6 2h4v3h2v-5h-6v2zm6 5h-2v4h2v-4zm-20 10h2v-4h-2v4zm18-4v.543c0 4.107-6 2.457-6 2.457s1.518 6-2.638 6h-1.362v2h2.189c3.163 0 9.811-7.223 9.811-9.614v-1.386h-2zm-18-2h2v-4h-2v4zm2 11v-3h-2v5h6v-2h-4z"/></svg>
	<p>Drop a spreadsheet file here to see sheet data</p>
</div>
<br />
<script src="<?php echo base_url('assets/dist/shim.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/dist/xlsx.full.min.js'); ?>"></script>

<div class="modal fade" id="modal-add" tabindex="2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title"> افزودن </h4>
            </div>

            <div class="modal-body">
                <form id="fPerson" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="txtCodeMelli">کد ملی</label><br>
                            <input type="text" class="form-control" onkeyup="CheckValidCodeMelli()" id="txtCodeMelli" style="display: inline-block; width: calc(100% - 70px);" />
                            <button type="button" class="btn btn-info" id="btnCheck" title="بررسی" onclick="CheckCodeMelli()" data-toggle="tooltip"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="col-md-6">
                            <label>جنسیت</label>
                            <table class="table wait-for">
                                <tr>
                                    <td class="td-grid">
                                        <input type="radio" name="gender" value="1" checked id="rbtnMale" />
                                        &nbsp;
                                        <label for="rbtnMale">مرد</label>
                                    </td>
                                    <td class="td-grid">
                                        <input type="radio" name="gender" value="2" id="rbtnFemale" />
                                        &nbsp;
                                        <label for="rbtnFemale">زن</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <label for="txtFName">نام</label>
                            <input type="text" class="form-control wait-for" id="txtFName" />
                        </div>
                        <div class="col-md-6">
                            <label for="txtLName">نام خانوادگی</label>
                            <input type="text" class="form-control wait-for" id="txtLName" />
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="txtNewSh">شماره نامه</label>
                            <input type="text" class="form-control wait-for" id="txtNewSh">
                        </div>
                        <div class="col-md-6">
                            <label for="txtNewDate">تاریخ نامه</label>
                            <input type="text" class="form-control wait-for" id="txtNewDate">
                        </div>
                        <div class="col-md-6">
                            <label for="txtNewMoarefi">معرفی از</label>
                            <input type="text" class="form-control wait-for" id="txtNewMoarefi">
                        </div>
                        <div class="col-md-6">
                            <label for="txtNewMoavenat">معاونت</label>
                            <input class="form-control wait-for" id="txtNewMoavenat" />
                        </div>
                        <div class="col-md-6">
                            <label for="txtNewTozih">توضیح</label>
                            <textarea rows="3" class="form-control wait-for" id="txtNewTozih"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="txtNewMolahezat">ملاحظات</label>
                            <textarea rows="3" class="form-control wait-for" id="txtNewMolahezat"></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <button class="btn btn-success">ذخیره</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<script>




/*jshint browser:true */
/* eslint-env browser */
/*global Uint8Array, Uint16Array, ArrayBuffer */
/*global XLSX */
/* eslint no-use-before-define:0 */

var global_wb;

var process_wb = (function() {
	var OUT = document.getElementById('out');
	var HTMLOUT = document.getElementById('htmlout');

	var get_format = (function() {
		var radios = document.getElementsByName( "format" );
		return function() {
			for(var i = 0; i < radios.length; ++i) if(radios[i].checked || radios.length === 1) return radios[i].value;
		};
	})();

	var to_json = function to_json(workbook) {
		var result = {};
		workbook.SheetNames.forEach(function(sheetName) {
			var roa = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], {header:1});
			if(roa.length) result[sheetName] = roa;
		});

        ShowLoading();

		$.post('<?php echo site_url('main/add'); ?>', { data: JSON.stringify(result) }, function(data) {
			if (data.result == 1) {
				alert('تعداد ' + data.success + ' رکورد با موفقیت افزوده شده.');
			}
			else {
				alert('عملیات با خطا متوقف شد.');
			}
		}, 'json').fail(function() {
			alert('عملیات با خطا متوقف شد.');
		}).always(function() {
            HideLoading();
            $('#xlf').val('');
		});

		return JSON.stringify(result, 2, 2);
	};

	var to_csv = function to_csv(workbook) {
		var result = [];
		workbook.SheetNames.forEach(function(sheetName) {
			var csv = XLSX.utils.sheet_to_csv(workbook.Sheets[sheetName]);
			if(csv.length){
				result.push("SHEET: " + sheetName);
				result.push("");
				result.push(csv);
			}
		});
		return result.join("\n");
	};

	var to_fmla = function to_fmla(workbook) {
		var result = [];
		workbook.SheetNames.forEach(function(sheetName) {
			var formulae = XLSX.utils.get_formulae(workbook.Sheets[sheetName]);
			if(formulae.length){
				result.push("SHEET: " + sheetName);
				result.push("");
				result.push(formulae.join("\n"));
			}
		});
		return result.join("\n");
	};

	var to_html = function to_html(workbook) {
		HTMLOUT.innerHTML = "";
		workbook.SheetNames.forEach(function(sheetName) {
			var htmlstr = XLSX.write(workbook, {sheet:sheetName, type:'string', bookType:'html'});
			HTMLOUT.innerHTML += htmlstr;
		});
		return "";
	};

	var to_xlsx = function to_xlsx(workbook) {
		HTMLOUT.innerHTML = "";
		XLSX.writeFile(workbook, "SheetJSTest.xlsx");
		return "";
	};

	return function process_wb(wb) {
		global_wb = wb;
		var output = "";
		switch(get_format()) {
			case "form": output = to_fmla(wb); break;
			case "html": output = to_html(wb); break;
			case "json": output = to_json(wb); break;
			case "xlsx": output = to_xlsx(wb); break;
			default: output = to_csv(wb);
		}
		if(OUT.innerText === undefined) OUT.textContent = output;
		else OUT.innerText = output;
		if(typeof console !== 'undefined') console.log("output", new Date());
	};
})();

var setfmt = window.setfmt = function setfmt() { if(global_wb) process_wb(global_wb); };

var b64it = window.b64it = (function() {
	var tarea = document.getElementById('b64data');
	return function b64it() {
		if(typeof console !== 'undefined') console.log("onload", new Date());
		var wb = XLSX.read(tarea.value, {type:'base64', WTF:false});
		process_wb(wb);
	};
})();

var do_file = (function() {
	var use_worker = typeof Worker !== 'undefined';
	var domwork = document.getElementsByName("useworker")[0];
	if(!use_worker) domwork.disabled = !(domwork.checked = false);

	var use_utf8 = false;

	var xw = function xw(data, cb) {
		var worker = new Worker('<?php echo base_url('assets/dist/xlsxworker.js'); ?>');
		worker.onmessage = function(e) {
			switch(e.data.t) {
				case 'ready': break;
				case 'e': console.error(e.data.d); break;
				case 'xlsx': cb(JSON.parse(e.data.d)); break;
			}
		};
		worker.postMessage({d:data,b:'array',c:use_utf8 ? 65001 : void 0});
	};

	return function do_file(files) {
		use_worker = domwork.checked;
		use_utf8 = document.getElementsByName("useutf8")[0].checked;
		var f = files[0];
		var reader = new FileReader();
		reader.onload = function(e) {
			if(typeof console !== 'undefined') console.log("onload", new Date(), use_worker);
			var data = new Uint8Array(e.target.result);
			if(use_worker) xw(data, process_wb);
			else process_wb(XLSX.read(data, {type: 'array', codepage: use_utf8 ? 65001 : void 0}));
		};
		reader.readAsArrayBuffer(f);
	};
})();

(function() {
	var dropZone = document.getElementById('drop-zone');
	if(!dropZone.addEventListener && !window.addEventListener) return;

	function handleDrop(e) {
		dropZoneDisplay(e, false);
		do_file(e.dataTransfer.files);
	}

	function handleDragover(e) {
		e.stopPropagation();
		e.preventDefault();
		e.dataTransfer.dropEffect = 'copy';
	}

	function dropZoneDisplay(e, show){
		e.stopPropagation();
		e.preventDefault();

		var opacity = show ? '1' : '0';
		var zIndex  = show ? '1' : '-1';

		dropZone.style.opacity = opacity;
		dropZone.style.zIndex = zIndex;
	}

	window.addEventListener('drop' , handleDrop);
	window.addEventListener('dragover' , handleDragover);
	window.addEventListener('dragenter' , function(e){
		dropZoneDisplay(e, true);
	});

	dropZone.addEventListener('dragleave' , function(e){
		dropZoneDisplay(e, false);
	});
})();

(function() {
	var xlf = document.getElementById('xlf');
	if(!xlf.addEventListener) return;
	function handleFile(e) { do_file(e.target.files); }
	xlf.addEventListener('change', handleFile, false);
})();
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-36810333-1']);
	_gaq.push(['_trackPageview']);


</script>

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


    $(document).ready(function() {



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
				let moavenat = $('#txtNewMoavenat').val();

                if (code == '' || fname == '' || lname == '') {
                    ShowError('اطلاعات فرد را بررسی نمایید.');
                    return false;
                } 

                ShowLoading();
                $.post('<?php echo site_url('main/add_person'); ?>', {
                    code, gender, fname, lname, sh, date, moarefi, tozih, molahezat, moavenat
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
</body>
</html>
