/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

/**
 * 导航树
 *
 * @param left_nav
 */
$.wz.navigator_tree = function (left_nav) {
    var icon_close = 'fa fa-folder-o';
    var icon_open = 'fa fa-folder-open-o';

    var childrenShow = function (elementLi) {
        elementLi.children('ul').show();
        elementLi.children('.wz-nav-fold').removeClass(icon_close).addClass(icon_open);

        return elementLi;
    };

    // 先隐藏所有的li下的子元素
    left_nav.find('li.wz-has-child').children('ul').hide();
    // 在包含子元素的li中添加展开图标和链接
    left_nav.find('li.wz-has-child').prepend('<a href="javascript:;" class="wz-nav-fold ' + icon_close + '"></a>');
    // 菜单折叠事件处理
    left_nav.find('li.wz-has-child').find('.wz-nav-fold')
        .on('click', function () {
            if ($(this).hasClass(icon_close)) {
                $(this).removeClass(icon_close).addClass(icon_open);
            } else {
                $(this).removeClass(icon_open).addClass(icon_close);
            }

            $(this).parent().children('ul').slideToggle('fast');
        });

    left_nav.find('.wz-auto-open').children('li.wz-has-child').each(function () {
        var childrenCount = $(this).children('ul').children('li').length;
        // 如果一级菜单的子元素小于7个，则自动展开
        if (childrenCount < 7) {
            $(this).children('a.wz-nav-fold').trigger('click');
        }
    });

    // 当前选中元素的所有父级元素全部自动展开
    left_nav.find('li.active').parents('ul').show();
    left_nav.find('li.active')
        .parents('.nav')
        .find('li:has(.active) >.wz-nav-fold')
        .removeClass(icon_close).addClass(icon_open);

    // 当前选中元素的下级元素自动展开
    childrenShow(left_nav.find('li.active'));

    // 一级元素的子元素自动展示
    childrenShow(left_nav.children('li'));

    left_nav.find('li:not(.wz-has-child)').map(function () {
        var nav_icon = ($(this).data('type') === 'swagger' ? 'fa-code' : ($(this).data('type') === 'markdown' ? 'fa-file-text-o' : 'fa-table'));
        $(this).prepend('<a class="fa ' + nav_icon + ' wz-nav-fold" href="javascript:;"></a>');
    });

};