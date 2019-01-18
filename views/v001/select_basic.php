<div region="north" data-options="border:false" style="padding: 8px 20px;">
    <table class="table table-bordered" style="margin: 0;padding: 0px">
    	<tbody>
        <tr>
            {% for value in selectConfig %}
                <td class="tlabel">{{value[0]}}</td>
                <td>
                    {% if value.length > 3 %}
                        <select name="{{value[1]}}" id="{{value[2]}}">
                            {% for item in value[3] %}
                                <option value="{{item[0]}}">{{item[1]}}</option>
                            {% endfor %}
                        </select>
                    {% else %}
                        <input class="col-sm-8" type="text" name="{{value[1]}}" id="{{value[2]}}" value="">
                    {% endif %}
                </td>
            {% endfor %}
        </tr>
        {% set cnt = selectConfig.length*2 %}
        <tr>
            <td colspan="{{cnt}}" class="align-center">
                {% if canSelect %}
                    <button type="submit" class="btn btn-success ml2" id="query">查询</button>
                {% endif %}
                {% for value in expendBtn %}
                    {% if value[3] %}
                        <a class="btn btn-success ml2 {% if '' == value[1] %} {{value[2]}} {% endif %}" {% if value[1] %} href="{{value[1]}}" id="{{value[2]}}" {% endif %}>{{value[0]}}</a>
                    {% endif %}
                {% endfor %}
    		</td>
        </tr>
        </tbody>
    </table>
</div>
