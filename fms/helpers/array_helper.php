<?php
/**
 * @desc 数组助手
 */

class ArrayHelper
{
    // 重写一个类似 array_shift 的方法
    // array_shift返回只有key为整形的格式，这里扩充为key值保留
    // $keyValueIsRelated的意思是保留键值对还是分开的一个数组
    public static function shift(&$arr, $keyValueIsRelated = false)
    {
        $key = array_keys($arr)[0];
        $arrayNeeded = $keyValueIsRelated ? [$key => $arr[$key]] : [$key, $arr[$key]];

        unset($arr[$key]);
        return $arrayNeeded;
    }

    public static function object_to_array($object)
    {
        $result = [];

        $array = is_object($object) ? get_object_vars($object) : $object;

        foreach ($array as $key => $value) {
            $value = (is_array($value) || is_object($value)) ? self::object_to_array($value) : $value;
            $result[$key] = $value;
        }

        return $result;
    }

    // 重新封装 array_push方法，如果arr不是数组的时候，先转换成数组
    // needCombine 传入true的时候，arr和 value 进行合并
    public static function push(&$arr, $value, $needCombine = true)
    {
        is_string($arr) && $arr = [$arr];
        empty($arr) && $arr = [];

        if (!empty($value)) {

            if (is_array($value) && $needCombine) {
                $arr = array_merge($arr, $value);
            } else {
                array_push($arr, $value);
            }
        }
    }

    /**
     * 将请求是数组参数url_encode
     *
     * @param array $data
     * @return string
     */
    public static function toHttpQuery($data = [])
    {
        return http_build_query(self::toStringArray($data));
    }

    /**
     * 将数组转化成json
     *
     * @param array $data
     * @return string
     */
    public static function toJson($data = [], $processZH = false)
    {
        if ($processZH) {
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode($data);
        }
    }

    /**
     * 将json_encode的数据decode转化
     * @param array $data
     * @return string
     */
    public static function toDecode($data = [])
    {
        return urldecode(json_encode($data));
    }

    /**
     *  将array转xml
     */
    public static function toXml(array $arr)
    {
        $xml = '';
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
            } else {
                $xml .= '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
            }
        }

        return '<xml>' .$xml . '</xml>';
    }

    /**
     * 签名的时候需要对请求参数 按照字典排序后生成字符串
     * @param array $params
     * @return string
     */
    public static function getSignStr(array $params)
    {
        ksort($params);

        $paramsStr = "";
        foreach($params as $key => $val) {
            if (isset($val) && @$val !== "" && !is_array($val)) {
                $paramsStr .= $key . "=" . $val . "&";
            }
        }

        return rtrim($paramsStr, '&');
    }

    /**
     * 通过 key 值对数组进行过滤
     * @param  [type] $data [需要过滤的数据]
     * @param  [type] $keys [过滤后剩下的key值]
     */
    public static function filterKeys($data, $keys)
    {
        return array_filter($data, function($key) use ($keys){
                return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * 校验数组中全是整形
     */
    public static function checkIsAllInt(array $arr)
    {
        $check = array_filter($arr, function($key) {
            return is_int($key);
        });

        return $check === $arr;
    }

    /**
     * 二维数组按照元素的某个字段排序
     * @param array $dyadicArr 需要排序的二维数组
     * @param string $field 排序依照的字段
     * @param type $sortOrder SORT_ASC或SORT_DESC,默认升序
     * @param type $sortFlag 参照 http://php.net/manual/zh/function.array-multisort.php 的sort_flags
     *              常用SORT_NUMERIC按数字大小比较、SORT_STRING按照字符串比较，默认按数字大小比较
     */
    public static function dyadicSort(array &$dyadicArr, string $field, $sortOrder = SORT_ASC, $sortFlag = SORT_NUMERIC)
    {
        $fieldArr = array_column($dyadicArr, $field);
        array_multisort($fieldArr, $sortOrder, $sortFlag, $dyadicArr);
    }

    /**
     * 把带keys的信息拼接到字符串中
     */
    public static function toStringWithKeys(array $arr, $topOfLine = '', $concat = ' ', $endOfLine = "\n")
    {
        $msg = '';
        // 带有连字符的进行拼接
        foreach ($arr as $key => $value) {
            $msg .= "{$topOfLine}{$key}{$concat}{$value}{$endOfLine}";
        }

        return $msg;
    }

    /**
     * 数字字符串转化为数字数组，如'1,3,4,' => [1, 3, 4]
     * @param type $arr
     */
    public static function stringToNumber($str, $delimiter = ',')
    {
        $arr = explode($delimiter, trim($str, $delimiter));
        foreach ($arr as $key => &$value) {
            $value += 0;
        }
        return $arr;
    }

    // 把array中的变量全部变成string类型
    public static function toStringArray(array $toBeProcessed)
    {
        foreach ($toBeProcessed as &$value) {
            if (is_object($value) && $value instanceof \pythia\atom\interfaces\ToString) {
                $value = $value->toString();
            }
        }

        return $toBeProcessed;
    }
}