
<form method="POST" id="fCodes">
    <label for="txtCodes">کد ملی ها:</label>
    <textarea id="txtCodes" name="codes" class="form-control" rows="5"></textarea>
    <div class="uk-text-left">
        <br>
        <button class="btn btn-success">دریافت فایل</button>
    </div>
</form>

<?php if ($Admin) { ?>
<hr>
<br><br>

<form method="POST">
    <div class="text-center">
        <input type="hidden" value="1" name="all_records" />
        <button class="btn btn-info">دریافت فایل کلی</button>
    </div>
</form>

<?php } ?>