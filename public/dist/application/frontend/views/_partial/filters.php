<div class="row">

<div class="col-md-4 padding-10">
  فیلتر دسته بندی:<br>
  <div class="uk-inline">
    <button class="uk-button uk-button-default fixed-button" style="width: 180px;" type="button" id="btnCats">دسته بندی <span class="selected-cat"></span> <i class="fas fa-times filter-cancel" onclick="RemoveFilterCats();"></i></button>
    <div uk-dropdown="mode: click" style="padding: 5px !important;">
      <div>
        <div class="uk-form-controls">
          <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: search"></span>
            <input autocomplete="off" class="uk-input uk-width-1-1" type="text" id="txtSearchCats" onkeyup="SearchCats();">
          </div>
        </div>
      </div>
      <div class="padding-5 max-height-300" id="dCats">
        <?php foreach ($Cats as $item) { ?>
          <div data-id="<?php echo $item->ID; ?>" data-value="<?php echo $item->ID; ?>" has-items="<?php if ($item->Items > 0) echo '1'; else echo '0'; ?>" class="cat-item <?php if ($item->Items > 0) echo 'has-items'; ?>" onclick="ChangeCat(this);">
          <?php echo $item->Name; ?>
          </div>
        <?php } ?>

      </div>

    </div>
  </div>
</div>

<!--<div class="col-md-4 padding-10">
  فیلتر برند:<br>
  <div class="uk-inline">
    <button class="uk-button uk-button-default fixed-button" style="width: 180px;" type="button" id="btnBrands">برندها <span class="selected-brand"></span><i class="fas fa-times filter-cancel" onclick="RemoveFilterBrands();"></i></button>
    <div uk-dropdown="mode: click" style="padding: 5px !important;">
      <div>
        <div class="uk-form-controls">
          <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: search"></span>
            <input class="uk-input uk-width-1-1" type="text" id="txtSearchBrands" onkeyup="SearchBrands();">
          </div>
        </div>
      </div>
      <div class="padding-5 max-height-300" id="dBrands">
        <?php foreach ($Brands as $item) { ?>
          <div data-id="<?php echo $item->ID; ?>" class="cat-item" data-en="<?php echo $item->NameEn; ?>" onclick="ChangeBrand(this);">
          <?php echo $item->Name; ?>
          </div>
        <?php } ?>

      </div>

    </div>
  </div>
</div>-->
<?php $this->load->config('filters');

$filters = $this->config->item('filters');


foreach ($filters as $key => $item) { ?>

<div class="col-md-4 padding-10">
  فیلتر <?php echo $item['Title']; ?>:<br>
  <div class="uk-inline">
    <button class="uk-button uk-button-default fixed-button" style="width: 180px;" type="button" id="btnFilter_<?php echo $key; ?>"><?php echo $item['ListTitle']; ?> <span class="selected-<?php echo $key; ?>"></span><i class="fas fa-times filter-cancel" onclick="RemoveFilter_<?php echo $key; ?>();"></i></button>
    <div uk-dropdown="mode: click" id="dDropdown_<?php echo $key; ?>" style="padding: 5px !important;">
      <div>
        <div class="uk-form-controls">
          <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: search"></span>
            <input autocomplete="off" class="uk-input uk-width-1-1" type="text" id="txtSearchFilter_<?php echo $key; ?>" onkeyup="SearchFilter_<?php echo $key; ?>();">
          </div>
        </div>
      </div>
      <div class="padding-5 max-height-filter-items" data-name="<?php echo $key; ?>" id="dFilter_<?php echo $key; ?>" data-loaded="0">
      </div>300 

    </div>
  </div>
</div>

<script type="text/javascript">
</script>

<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
    <?php foreach ($filters as $key => $item) { ?>
        $('#dDropdown_<?php echo $key; ?>').on('show', function() {
        
            if ($('#dFilter_<?php echo $key; ?>').attr('data-loaded') == 0) {
                LoadFilterOptions('<?php echo $key; ?>');
            }
        }); 

    <?php } ?>    
    });

    <?php foreach ($filters as $key => $item) { ?>
    
    
    function ChangeFilter_<?php echo $key; ?>(obj) {
        var name = $(obj).text();


        if ($(obj).hasClass('active')) {
            $(obj).removeClass('active');
            $('.selected-<?php echo $key; ?>').html('');
            $('#btnFilter_<?php echo $key; ?>').removeClass('filtered');
        }
        else {
            $('#dFilter_<?php echo $key; ?> .cat-item').removeClass('active');
            $(obj).addClass('active');
            $('.selected-<?php echo $key; ?>').html(' (' + name + ')');
            $('#btnFilter_<?php echo $key; ?>').addClass('filtered');
        }
        ClearRelationFilters_<?php echo $key; ?>();
        RefreshProducts();
    }

    function RemoveFilter_<?php echo $key; ?>() {
        $('#txtSearchFilter_<?php echo $key; ?>').val('');

        $('#dFilter_<?php echo $key; ?> .cat-item.active').removeClass('active');
        $('.selected-<?php echo $key; ?>').html('');
        $('#btnFilter_<?php echo $key; ?>').removeClass('filtered');

        pOldKey += 'a';

        ClearRelationFilters_<?php echo $key; ?>();
        RefreshProducts();
    }

    function ClearRelationFilters_<?php echo $key; ?>() {
      <?php foreach ($item['InRelation'] as $rel) { ?>
      RemoveFilter_<?php echo $rel; ?>();
      $('#dFilter_<?php echo $rel; ?>').attr('data-loaded', '0');
      <?php } ?>
    }

    function SearchFilter_<?php echo $key; ?>() {
        var key = $('#txtSearchFilter_<?php echo $key; ?>').val();

        $('#dFilter_<?php echo $key; ?> .cat-item').each(function(index, obj) {
            var name = $(obj).text();

            if (name.indexOf(key) == -1) 
                $(obj).addClass('hidden');
            else
                $(obj).removeClass('hidden');
        });
    }

    <?php } ?>

    function LoadFilterOptions(key) {
        $('#dFilter_' + key).attr('data-loaded', '1');
        $('#dFilter_' + key).html('<div class="uk-text-center"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" /></div>');

        let data = {};
        data.key = key;

        let id = '';
        <?php foreach ($item['Relations'] as $k => $rel) { ?>
          id = $('#dFilter_<?php echo $k; ?> .active').attr('data-id');

          if (id === undefined || id == null)
              id = '';

          if (id != '') {
            data.<?php echo $k; ?> = id;
          }

        <?php } ?>

        $.post('<?php echo site_url('products/get_filter_options'); ?>',
        data,
        function(data) {
            if (data.Result) {
                $('#dFilter_' + key).html(data.Data);
            }
            else {
                $('#dFilter_' + key).html('<div class="alert alert-danger">' + data.Message + '</div><div class="uk-text-center uk-padding"><button class="btn btn-danger" onclick="LoadFilterOptions(\'' + key + '\');">تلاش مجدد</button></div>');
            }
        }, 'json').fail(function() {
            $('#dFilter_' + key).html('<div class="alert alert-danger">اتصال به سرور برقرار نشد.</div><div class="uk-text-center uk-padding"><button class="btn btn-danger" onclick="LoadFilterOptions(\'' + key + '\');">تلاش مجدد</button></div>');
        });
    }


</script>

</div>

<hr class="uk-divider-icon">
