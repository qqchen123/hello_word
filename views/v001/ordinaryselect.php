<select name="{{data['input_name']}}" id="{{data['input_id']}}" class="{{data['input_class']}}" data-action="{{data['action']}}">
	{% set select_flag = 1 %}
	{% for item in data['input_options'] %}
		<option value="{{item[1]}}" {% if select_flag %}selected='selected'{% endif %}>{{item[0]}}</option>
		{% set select_flag = 0 %}
	{% endfor %}
</select>
