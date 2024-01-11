@extends('welcome')

@section('mohtava')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">فرم ثبت توضیحات</h3>
        </div>

        <div class="row">
            <div style="max-width: 800px; margin:center;" class="col-12 m-5">

                <label for="sTemplate">قالب متن</label>
                <select class="w-75 mr-4 form-control" id="sTemplate" oninput="Calc();">
                    <option value="1">حسب نامه {sh} - {date} معرفی از {note}</option>
                    <option value="2">حسب نامه {sh} - {date} رکود تا {note} (مرخصی بارداری)</option>
                    <option value="3">حسب نامه {sh} - {date} رکود تا {note} (مرخصی پزشکی)</option>
                    <option value="4">حسب نامه {sh} - {date} تشرف مجدد</option>
                    <option value="5">تشرف حسب نامه {sh} - {date}</option>
                    <option value="6">حسب نامه {sh} - {date} خاتمه خدمت</option>
                    <option value="7">حسب نامه {sh} - {date} سابقه خدمت لحاظ شده در سامانه یگان امنیت از تاریخ {note} -
                        پس از مراحل جذب {mahal}</option>
                    <option value="7">حسب نامه {sh} - {date} انتقال از {note}</option>
                </select>
                <div class="row mt-4">
                    <div class="col-md-5 mr-4">
                        <label for="txtSh">شماره نامه</label>
                        <input type="text" id="txtSh" class="form-control" oninput="Calc();" />
                    </div>

                    <div class="col-md-5 mr-4">
                        <label for="txtDate">تاریخ نامه</label>
                        <input type="text" id="txtDate" class="form-control" oninput="Calc();" />
                    </div>

                    <div class="col-md-5 mr-4">
                        <label for="txtNote">سایر</label>
                        <input type="text" id="txtNote" class="form-control" oninput="Calc();" />
                    </div>

                    <div class="col-md-5 mr-4">
                        <label for="txtmahal">محل خدمت</label>
                        <input type="text" id="txtmahal" class="form-control" oninput="Calc();" />
                        </br>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="mr-4 col-md-10 alert alert-info" id="dNote" type="text">
            </div>
            </br>
        </div>
        <div class="row">
            <div class="m-4 align-right col-md-6">
                <button class="btn btn-xs btn-primary" onclick="Reset()"><x-feathericon-refresh-ccw />رفرش کردن</button>
            </div>
        </div>

    </div>

    </form>




    <script type="text/javascript">
        function copy() {
            var copyText = document.querySelector("#dNote");
            copyText.select();
            document.exeCommand("copy");
        }
        document.querySelector("#copy").addEventListener("click".copy);

        function Reset() {
            $('#txtSh').val('');
            $('#txtDate').val('');
            $('#txtNote').val('');
            $('#txtmahal').val('');

            Calc();
        }

        function Calc() {
            let template = $('#sTemplate option:selected').text();

            let sh = $('#txtSh').val();
            let date = $('#txtDate').val();
            let note = $('#txtNote').val();
            let mahal = $('#txtmahal').val();

            let text = template.replace('{sh}', sh);
            text = text.replace('{date}', date);
            text = text.replace('{note}', note);
            text = text.replace('{mahal}', mahal);

            $('#dNote').html(text);
        }

    </script>
@endsection
