
    <?php if (!isset($NoLayout) || $NoLayout == false) { ?>
    <div class="row">
    <?php } ?>

    <?php  foreach ($Filters as $key => $item) { if ($item['Enabled'] == false && isset($item['Enabled'])) continue; ?>

    <?php if ($item['Type'] == 'select') { ?>
    <?php if (!isset($NoLayout) || $NoLayout == false) { ?>
    <div class="col-md-4 padding-10">
    <?php } ?>
    فیلتر <?php echo $item['Title']; ?>:<br>
    <div class="uk-inline uk-width-1-1">
        <button class="uk-button uk-button-default uk-width-1-1" style="max-height: 40px; overflow: hidden;"  type="button" id="btnRFilter_<?php echo $key; ?>"><?php echo $item['ListTitle']; ?> <span class="selected-report-<?php echo $key; ?>"></span><i class="fas fa-times filter-cancel" onclick="RemoveReportFilter_<?php echo $key; ?>();"></i></button>
        <div uk-dropdown="mode: click" id="dRDropdown_<?php echo $key; ?>" style="padding: 5px !important;">
        <div>
            <div class="uk-form-controls">
            <div class="uk-inline uk-width-1-1">
                <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: search"></span>
                <input autocomplete="off" class="uk-input uk-width-1-1" type="text" id="txtRSearchFilter_<?php echo $key; ?>" onkeyup="SearchRFilter_<?php echo $key; ?>();">
            </div>
            </div>
        </div>
        <div class="padding-5 max-height-300 filter-report-items" data-multiple="<?php if (isset($item['Multiple']) && $item['Multiple'] == false) echo '0'; else echo '1'; ?>" data-name="<?php echo $key; ?>" id="dRFilter_<?php echo $key; ?>" data-loaded="<?php if (is_array($item['Options'])) echo '1'; else echo '0'; ?>">
            <?php if (is_array($item['Options'])) { ?>
                <?php foreach ($item['Options'] as $k => $v) { ?>
                    <div data-id="<?php echo $k; ?>" class="filter-item" onclick="ChangeRFilter_<?php echo $key; ?>(this);">
                    <?php echo $v; ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>

        </div>
    </div>

    <?php if (!isset($NoLayout) || $NoLayout == false) { ?>
    </div>
    <?php } ?>
    <?php } else if ($item['Type'] == 'shamsi_date') { ?>
        <?php if (!isset($NoLayout) || $NoLayout == false) { ?>
        <div class="col-md-4 padding-10">
        <?php } ?>
            <label for="txt_filter_<?php echo $key; ?>"><?php echo $item['Title']; ?></label>
            <input autocomplete="off" type="text" id="txt_filter_<?php echo $key; ?>" value="<?php echo !isset($item['Value']) ? $Today : $item['Value']; ?>" class="form-control tarikh-input"/>
        <?php if (!isset($NoLayout) || $NoLayout == false) { ?>
        </div>
        <?php } ?>
    <?php } else if ($item['Type'] == 'text') { ?>
        <?php if (!isset($NoLayout) || $NoLayout == false) { ?>
        <div class="col-md-4 padding-10">
        <?php } ?>
            <label for="txt_filter_<?php echo $key; ?>"><?php echo $item['Title']; ?></label>
            <input autocomplete="off" type="text" id="txt_filter_<?php echo $key; ?>" class="form-control"/>
        <?php if (!isset($NoLayout) || $NoLayout == false) { ?>
        </div>
        <?php } ?>

    <?php }  ?>

    <?php } ?>

    <script type="text/javascript">
        $(document).ready(function() {
        <?php foreach ($Filters as $key => $item) { if ($item['Enabled'] == false && isset($item['Enabled'])) continue; ?>
            <?php if ($item['Type'] == 'select') { ?>
            $('#dRDropdown_<?php echo $key; ?>').on('show', function() {
            
                if ($('#dRFilter_<?php echo $key; ?>').attr('data-loaded') == 0) {
                    LoadFilterOptionsMain('<?php echo $key; ?>'<?php if (!empty($item['Relation'])) echo ", '{$item['Relation']}'" ?>);
                }
            }); 
            <?php } else if ($item['Type'] == 'shamsi_date') { ?>
                new AMIB.persianCalendar('txt_filter_<?php echo $key; ?>', { extraInputID: 'txt_filter_<?php echo $key; ?>', extraInputFormat: 'yyyy/mm/dd' });
            <?php } ?>

        <?php } ?>    
        });

        <?php foreach ($Filters as $key => $item) { if ($item['Enabled'] == false && isset($item['Enabled'])) continue; ?>
        <?php if ($item['Type'] != 'select') continue; ?>
        
        function ChangeRFilter_<?php echo $key; ?>(obj) {
            var name = $(obj).text();

            var multi = $('#dRFilter_<?php echo $key; ?>').attr('data-multiple') == '1';

            if (multi == false) 
                $('#dRFilter_<?php echo $key; ?> .filter-item').removeClass('active'); 

            if ($(obj).hasClass('active')) {
                $(obj).removeClass('active');
                $('.selected-report-<?php echo $key; ?>').html('');
                $('#btnRFilter_<?php echo $key; ?>').removeClass('filtered');
            }
            else {
                $(obj).addClass('active');
                $('.selected-report-<?php echo $key; ?>').html(' (' + name + ')');
                $('#btnRFilter_<?php echo $key; ?>').addClass('filtered');
            }

            <?php if (!empty($item['InRelation'])) { ?>
                <?php foreach ($item['InRelation'] as $rel) { ?>
                RemoveReportFilter_<?php echo $rel; ?>();
                $('#dRFilter_<?php echo $rel; ?>').attr('data-loaded', '0');
                <?php } ?>
            <?php } ?>

            <?php if (!empty($item['OnChange'])) { ?>
                eval('<?php echo $item['OnChange']; ?>');
            <?php } ?>

            <?php if (!empty($OnChange)) { ?>
                <?php echo $OnChange; ?>;
            <?php } ?>

        }

        function RemoveReportFilter_<?php echo $key; ?>() {

            $('#txtRSearchFilter_<?php echo $key; ?>').val('');

            $('#dRFilter_<?php echo $key; ?> .filter-item.active').removeClass('active');
            $('.selected-report-<?php echo $key; ?>').html('');
            $('#btnRFilter_<?php echo $key; ?>').removeClass('filtered');

            <?php if (!empty($item['InRelation'])) { ?>
                <?php foreach ($item['InRelation'] as $rel) { ?>
                RemoveReportFilter_<?php echo $rel; ?>();
                $('#dRFilter_<?php echo $rel; ?>').attr('data-loaded', '0');
                <?php } ?>
            <?php } ?>
            
            <?php if (!empty($item['OnChange'])) { ?>
                eval('<?php echo $item['OnChange']; ?>');
            <?php } ?>

            <?php if (!empty($OnChange)) { ?>
                <?php echo $OnChange; ?>;
            <?php } ?>

        }

        function SearchRFilter_<?php echo $key; ?>() {
            var key = $('#txtRSearchFilter_<?php echo $key; ?>').val();

            $('#dRFilter_<?php echo $key; ?> .filter-item').each(function(index, obj) {
                var name = $(obj).text();

                if (name.indexOf(key) == -1) 
                    $(obj).addClass('hidden');
                else
                    $(obj).removeClass('hidden');
            });
        }

        <?php } ?>

        function GetFilterValues(key) {
            var values = '';
            $('#dRFilter_' + key + ' .filter-item.active').each(function(index, obj) {
                var id = $(obj).attr('data-id');

                if (values != '')
                    values += ',';

                values += id;
            });

            return values;
        }

        function LoadFilterOptionsMain(key, rel) {
            $('#dRFilter_' + key).attr('data-loaded', '1');
            $('#dRFilter_' + key).html('<div class="uk-text-center"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" /></div>');

            var values = '';
            if (rel !== undefined) {
                $('#dRFilter_' + rel + ' .filter-item.active').each(function(index, obj) {
                    var id = $(obj).attr('data-id');

                    if (values != '')
                        values += ',';

                    values += id;
                });
            }

            $.post('<?php echo site_url('main/get_filter_items'); ?>',
            { key: key, values: values },
            function(data) {
                if (data.Result) {
                    $('#dRFilter_' + key).html(data.Data);
                }
                else {
                    $('#dRFilter_' + key).html('<div class="alert alert-danger">' + data.Message + '</div><div class="uk-text-center uk-padding"><button class="btn btn-danger" onclick="LoadFilterOptionsMain(\'' + key + '\');">تلاش مجدد</button></div>');
                }
            }, 'json').fail(function() {
                $('#dRFilter_' + key).html('<div class="alert alert-danger">اتصال به سرور برقرار نشد.</div><div class="uk-text-center uk-padding"><button class="btn btn-danger" onclick="LoadFilterOptionsMain(\'' + key + '\');">تلاش مجدد</button></div>');
            });
        }

    </script>

<?php if (!isset($NoLayout) || $NoLayout == false) { ?>
</div>
<?php } ?>

