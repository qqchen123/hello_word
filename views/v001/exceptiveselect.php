<style type="text/css">
	.wersd {
        appearance:none;
		-moz-appearance:none;
		-webkit-appearance:none;
		background: url('/assets/images/web/icons/1.png') right 2px 
		top 1px no-repeat ;
	}

</style>

<select class="wersd" name="{{conditionData['name']}}">
		{% for item in conditionData['options'] %}
			<option value="{{item[1]}}">{{item[0]}}</option>
		{% endfor %}
</select>