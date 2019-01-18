<?php 

/**
 * 
 */
class HtmlHelper
{
	/**
	 * @name 创建页面编辑框
	 * @param array $config 配置信息
	 * @param array $data 编辑框内的数据
	 * @return html code
	 */
	public static function create_edit_box($config, $data)
	{
		$html = '';
		foreach ($config as $key => $value) {
			$input_value = !empty($data[$key]) ? $data[$key] : '';
			if (!empty($value['box_class'])) {
				if ('block' != $value['box_type']) {
					$html .= '<div class="pre-check '. $value['box_class'] .' "';
				} else {
					$html .= '<div class="fitem pre-check '. $value['box_class'] .'"';
				}
			} else {
				$html .= '<div  class="fitem"';
			}

			$html .= '>';
			if (!empty($value['label'])) {
				$html .= '<label ';
				if (!empty($value['label_class'])) {
					$html .= 'class="'. $value['label_class'] .'"';
				}
				$html .= '>'. $value['label'] .'</label>';
			}
			//输入框
			if ('text' == $value['type']) {
				if (!empty($value['class']) && preg_match('/easyui/', $value['class'])) {
					$html .= '<input name="'. $key .'" value="'. $input_value .'"';
				} else {
					$html .= '<input type="text" name="'. $key .'" value="'. $input_value .'"';
				}
				//是否只读
				if ('readonly' == $value['readonly']) {
					$html .= ' readonly="readonly"';
				}
				//是否有class
				if (!empty($value['class'])) {
					$html .= ' class="'. $value['class'] .'"';
				}
				//是否需要加ID
				if (!empty($value['id'])) {
					$html .= ' id="'. $value['id'] .'"';
				}
				//是否需要加载单独样式
				if (!empty($value['style'])) {
					$html .= ' style="'. $value['style'].'"';
				}
				$html .= '>';
			}
			//下拉框
			if ('select' == $value['type']) {
				$html .= '<select name="'. $key .'" value="" ';
				if (!empty($value['other'])) {
					$html .= $value['other'];
				}
				//是否需要加ID
				if (!empty($value['id'])) {
					$html .= ' id="'. $value['id'] .'"';
				}
				$html .= '>';
				//option
				foreach ($value['option'] as $item) {
					if ($input_value == $item['value']) {
						$select_flag = 'selected=selected date-bak=' . $input_value ;
					} else {
						$select_flag = '';
					}
					$html .= '<option value="'. $item['value'] .'" '. $select_flag .'>' . $item['text'] . '</option>';
				}
				$html .= '</select>';
			}
			if ('file' == $value['type']) {
				if (!empty($value['class']) && preg_match('/easyui/', $value['class'])) {
					$html .= '<input name="'. $key .'"';
				} else {
					$html .= '<input type="file" name="'. $key . '"';
				}
				//是否有class
				if (!empty($value['class'])) {
					$html .= ' class="'. $value['class'] .'"';
				}
				//是否需要加ID
				if (!empty($value['id'])) {
					$html .= ' id="'. $value['id'] .'"';
				}
				//是否需要加载单独样式
				if (!empty($value['style'])) {
					$html .= ' style="'. $value['style'].'"';
				}
				$html .= '>';
			}
			if ('' == $value['type']) {
				if (!empty($value['class']) && preg_match('/easyui/', $value['class'])) {
					$html .= '<input name="'. $key .'" value="'. $input_value .'"';
				} else {
					$html .= '<input name="'. $key .'" value="'. $input_value .'"';
				}
				//是否有class
				if (!empty($value['class'])) {
					$html .= ' class="'. $value['class'] .'"';
				}
				//是否需要加ID
				if (!empty($value['id'])) {
					$html .= ' id="'. $value['id'] .'"';
				}
				//是否需要加载单独样式
				if (!empty($value['style'])) {
					$html .= ' style="'. $value['style'].'"';
				}
				$html .= '>';
			}
			$html .= '</div>';
		}
		return $html;
	}

}