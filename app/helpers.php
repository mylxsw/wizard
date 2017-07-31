<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

/**
 * 生成路由url
 *
 * @param string $name
 * @param array  $parameters
 * @param bool   $absolute
 *
 * @return string
 */
function wzRoute($name, $parameters = [], $absolute = false)
{
    return route($name, $parameters, $absolute);
}