<?php if (!empty($PageTitle)) { ?>
	<div class="alert alert-info" style="max-width: 100%;">
		<?php echo $PageTitle; ?>
	</div>
<?php } ?>

<br>

<?php if ( !empty($crud_note) ) echo $crud_note; ?>

<?php if ( !empty($crud_data) ) echo $crud_data->output; ?>

<br><br>

<script type="text/javascript">
</script>