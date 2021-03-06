<?php
/**
 * 去除标签及标签内的字符串
 * @param string $text 字符串
 * @param string tags 标签
 * @param bool $invert 反转 false：保留单签标签，其余标签及标签内字符串删除；true：删除当前标记及标签内字符串
 * @return string
 */
function strip_tags_content($text, $tags = '', $invert = FALSE) { 
    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);

    // return $tags;
    $tags = array_unique($tags[1]); 
    if(is_array($tags) AND count($tags) > 0) { 
        if($invert == FALSE) { 
            return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text); 
        } 
        else { 
            return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text); 
        } 
    } elseif($invert == FALSE) { 
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text); 
    } 
    return $text; 
}

// $text = '<b>sample</b> text with <p>tags</p>'; 
// echo strip_tags($text);
// echo strip_tags_content($text, '<b>', true);

/**
 * 实体化html标签
 * @param $text 字符串
 * @param tags 标签
 * @param $invert 反转 false：保留单签标签，其余标签及标签内字符串删除；true：删除当前标记及标签内字符串
 * @return string
 */
function html_special_chars($text, $tags = '', $invert = true) { 
    if($invert) {
        preg_match_all('/<'.$tags.'>.+?<\/'.$tags.'>/si', $text, $match);
    } 
    else {
        preg_match_all('/<(?!(?:'.$tags.'))(\w+)\b.*?>.+?<\/\1>/si', $text, $match);
    }

    $match = array_unique($match[0]);

    if(is_array($match) AND count($match) > 0) {
       return preg_replace('/<'.$tags.'>.+?<\/'.$tags.'>/si', htmlspecialchars($match[0]), $text);
    } else {
        return $text;
    }
}

// 利用函数将标签实体化
function filter_script(&$text, $tag) {
    $tag = strtolower($tag);
    $tags = array('<'.strtolower($tag).'>', '</'.strtolower($tag).'>', '<'.strtoupper($tag).'>', '</'.strtoupper($tag).'>');
    $tmp = [];
    foreach ($tags as $key => $value) {
        $tmp[] = htmlspecialchars($value);
    }
    $text = strtr($text, array_combine($tags, $tmp));
}
$text = '<b>sample</b> text with <p>tags</p> <script>console.log(2)</script>';//
// print_r (html_special_chars($text, 'script'));
filter_script($text, 'a');
echo $text;

$arr = array('b', 'p');
foreach ($arr as $key => $value) {
    filter_script($text, $value);
}
echo $text;

/**
\d  匹配一个数字字符。等价于 [0-9]。

\D  匹配一个非数字字符。等价于 [^0-9]。

\f  匹配一个换页符。等价于 \x0c 和 \cL。

\n  匹配一个换行符。等价于 \x0a 和 \cJ。

\r  匹配一个回车符。等价于 \x0d 和 \cM。

\s  匹配任何空白字符，包括空格、制表符、换页符等等。等价于 [ \f\n\r\t\v]。

\S  匹配任何非空白字符。等价于 [^ \f\n\r\t\v]。

\t  匹配一个制表符。等价于 \x09 和 \cI。

\v  匹配一个垂直制表符。等价于 \x0b 和 \cK。

\w  匹配包括下划线的任何单词字符。等价于'[A-Za-z0-9_]'。

\W  匹配任何非单词字符。等价于 '[^A-Za-z0-9_]'。

\  将下一个字符标记为一个特殊字符、或一个原义字符、或一个 向后引用、或一个八进制转义符。例如，'n' 匹配字符 "n"。'\n' 匹配一个换行符。序列 '\\' 匹配 "\" 而 "\(" 则匹配 "("。

^  匹配输入字符串的开始位置。如果设置了 RegExp 对象的 Multiline 属性，^ 也匹配 '\n' 或 '\r' 之后的位置。

$  匹配输入字符串的结束位置。如果设置了RegExp 对象的 Multiline 属性，$ 也匹配 '\n' 或 '\r' 之前的位置。

*  匹配前面的子表达式零次或多次。例如，zo* 能匹配 "z" 以及 "zoo"。* 等价于{0,}。

+  匹配前面的子表达式一次或多次。例如，'zo+' 能匹配 "zo" 以及 "zoo"，但不能匹配 "z"。+ 等价于 {1,}。

?  匹配前面的子表达式零次或一次。例如，"do(es)?" 可以匹配 "do" 或 "does" 中的"do" 。? 等价于 {0,1}。

{n}  n 是一个非负整数。匹配确定的 n 次。例如，'o{2}' 不能匹配 "Bob" 中的 'o'，但是能匹配 "food" 中的两个 o。

{n,}  n 是一个非负整数。至少匹配n 次。例如，'o{2,}' 不能匹配 "Bob" 中的 'o'，但能匹配 "foooood" 中的所有o。'o{1,}' 等价于 'o+'。'o{0,}' 则等价于 'o*'。

{n,m}  m 和 n 均为非负整数，其中n <= m。最少匹配 n 次且最多匹配 m 次。例如，"o{1,3}" 将匹配 "fooooood" 中的前三个 o。'o{0,1}' 等价于 'o?'。请注意在逗号和两个数之间不能有空格。

?  当该字符紧跟在任何一个其他限制符 (*, +, ?, {n}, {n,}, {n,m}) 后面时，匹配模式是非贪婪的。非贪婪模式尽可能少的匹配所搜索的字符串，而默认的贪婪模式则尽可能多的匹配所搜索的字符串。例如，对于字符串 "oooo"，'o+?' 将匹配单个 "o"，而 'o+' 将匹配所有 'o'。

.  匹配除 "\n" 之外的任何单个字符。要匹配包括 '\n' 在内的任何字符，请使用象 '[.\n]' 的模式。

x|y  匹配 x 或 y。例如，'z|food' 能匹配 "z" 或 "food"。'(z|f)ood' 则匹配 "zood" 或 "food"。
[xyz]  字符集合。匹配所包含的任意一个字符。例如， '[abc]' 可以匹配 "plain" 中的 'a'。
[^xyz]  负值字符集合。匹配未包含的任意字符。例如， '[^abc]' 可以匹配 "plain" 中的'p'。
[a-z]  字符范围。匹配指定范围内的任意字符。例如，'[a-z]' 可以匹配 'a' 到 'z' 范围内的任意小写字母字符。
[^a-z]  负值字符范围。匹配任何不在指定范围内的任意字符。例如，'[^a-z]' 可以匹配任何不在 'a' 到 'z' 范围内的任意字符。

*?  零次或多次，但尽可能少的匹配
+?  一次或多次，但尽可能少的匹配
??  0次或1次，但尽可能少的匹配
{n,}?  至少n次，但尽可能少的匹配
{n,m}?  n到m次 ，但尽可能少的匹配

(pattern)  匹配 pattern 并获取这一匹配。所获取的匹配可以从产生的 Matches 集合得到，在VBScript 中使用 SubMatches 集合，在JScript 中则使用 $0…$9 属性。要匹配圆括号字符，请使用 '\(' 或 '\)'。

(?:pattern)  匹配 pattern 但不获取匹配结果，也就是说这是一个非获取匹配，不进行存储供以后使用。这在使用 "或" 字符 (|) 来组合一个模式的各个部分是很有用。例如， 'industr(?:y|ies) 就是一个比 'industry|industries' 更简略的表达式。

(?=pattern)  正向预查，在任何匹配 pattern 的字符串开始处匹配查找字符串。这是一个非获取匹配，也就是说，该匹配不需要获取供以后使用。例如，'Windows 
(?=95|98|NT|2000)' 能匹配 "Windows 2000" 中的 "Windows" ，但不能匹配 "Windows 3.1" 中的 "Windows"。预查不消耗字符，也就是说，在一个匹配发生后，在最后一次匹配之后立即开始下一次匹配的搜索，而不是从包含预查的字符之后开始。

(?!pattern)  负向预查，在任何不匹配 pattern 的字符串开始处匹配查找字符串。这是一个非获取匹配，也就是说，该匹配不需要获取供以后使用。例如'Windows (?!95|98|NT|2000)' 能匹配 "Windows 3.1" 中的 "Windows"，但不能匹配 "Windows 2000" 中的 "Windows"。预查不消耗字符，也就是说，在一个匹配发生后，在最后一次匹配之后立即开始下一次匹配的搜索，而不是从包含预查的字符之后开始


 */