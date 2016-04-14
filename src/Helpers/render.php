<?php
function btn($title = 'Save', $class = 'btn-primary')
{
    return '<input type="submit" value="' . $title . '" class="btn ' . $class . ' submit-btn">';
}

function OptionsView($collection, $idKey, $valKey, $default = '', $skip = array())
{
    $str = '';
    if (!empty($collection)) {
        foreach ($collection as $coln) {
            /*print_r($default);*/
            if (in_array($coln->$idKey, $skip)) {
                continue;
            }

            $str .= '<option value="' . $coln->$idKey . '" ';
            if (!is_array($default)) {
                if ($coln->$idKey == $default) {
                    $str .= 'selected="selected"';
                }
            } else {
                if (in_array($coln->$idKey, $default)) {
                    $str .= 'selected="selected"';
                }
            }

            $str .= '>';
            if (is_callable($valKey)) {
                $str .= $valKey($coln);
            } else {
                $str .= $coln->$valKey;
            }

            $str .= '</option>';
        }
    }
    return $str;
}


function isSelected($option, $val)
{
    return $option == $val ? 'selected="selected"' : '';
}

function isMultiSelected($option, $arr)
{
    return in_array($option, $arr) ? 'selected="selected"' : '';
}

function isChecked($option, $val)
{
    return $option == $val ? 'checked="checked"' : '';
}

function isMultiChecked($option, $arr)
{
    return in_array($option, $arr) ? 'checked="checked"' : '';
}



function limit_words($string, $word_limit)
{
    $words = explode(" ", $string);
    return implode(" ", array_splice($words, 0, $word_limit));
}

function word_limit($string, $limit)
{
    $words = explode(" ", $string);
    if (count($words) > $limit) {
        return implode(" ", array_splice($words, 0, $limit));
    } else {
        return $string;
    }

}



function ToJs($data){
    echo '<script type="text/javascript">
	    /* <![CDATA[ */';

    foreach ($data as $k => $v) {
        echo 'var ' . $k . '= ' . json_encode($v) . ';';
    }
    echo '/* ]]> */
	    </script>';
}
