<div class="{{class}}" id="sp{{sample_id}}"></div>
<script type="text/javascript">
	$("#sp{{sample_id}}").append(
        nunjucks.renderString(
            globalData['tpl']['lable_tpl'], 
            {class:{{data['lable_class']}}
        )
    );
</script>