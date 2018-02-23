function indexSlider() {
    /*首页轮播*/
    var slideType = $("*[data-mode='lunbo']").find("*[data-type='range']").data("slide");
    var length = $(".banner .bd").find("li").length;

    if (slideType == "roll") {
        slideType = "left";
    }

    if (length > 1) {
        $(".banner").slide({
            titCell: ".hd ul", mainCell: ".bd ul", effect: slideType, interTime: 5000, delayTime: 500, autoPlay: true, autoPage: true, trigger: "click", endFun: function (i, c, s) {
                $(window).resize(function () {
                    var width = $(window).width();
                    if (!$('body').hasClass("festival_home")) {
                        s.find(".bd li").css("width", width);
                    }
                });
            }
        });
    } else {
        $(".banner .hd").hide();
    }

    //楼层二级分类商品切换
    $("*[ectype='floorItem']").slide({ titCell: ".hd-tags li", mainCell: ".floor-tabs-content", titOnClassName: "current" });

    $("*[ectype='floorItem']").slide({ titCell: ".floor-nav li", mainCell: ".floor-tabs-content", titOnClassName: "current" });

    //第五套楼层模板
    $(".floor-fd-slide").slide({ mainCell: ".bd ul", effect: "left", autoPlay: false, autoPage: true, vis: 4, scroll: 1, prevCell: ".ff-prev", nextCell: ".ff-next" });

    //第六套楼层模板
    $(".floor-brand").slide({ mainCell: ".fb-bd ul", effect: "left", pnLoop: true, autoPlay: false, autoPage: true, vis: 3, scroll: 1, prevCell: ".fs_prev", nextCell: ".fs_next" });

    //楼层轮播图广告
    $("*[data-purebox='homeFloor']").each(function (index, element) {
        var f_slide_length = $(this).find(".floor-left-slide .bd li").length;
        if (f_slide_length > 1) {
            $(element).find(".floor-left-slide").slide({ titCell: ".hd ul", mainCell: ".bd ul", effect: "left", interTime: 3500, delayTime: 500, autoPlay: true, autoPage: true });
        } else {
            $(element).find(".floor-left-slide .hd").hide();
        }
    });
    //异步加载出首页个人信息、秒杀活动、品牌信息、首页弹出广告
    $(function () {
        var site_domain = "";
        var brand_id = $('*[ectype="homeBrand"]').find(".brand-list").data("value");
        var seckillid = $('[data-mode="h-seckill"]').find('[data-type="range"]').data('seckillid');
        var temp = "backup_tpl_1";

        var where = '';
        if (!brand_id) {
            brand_id = '';
        }

        seckillid = JSON.stringify(seckillid);

        if (site_domain) {
            $.ajax({
                type: "GET",
                url: "ajax_dialog.php", /*jquery Ajax跨域*/
                data: "act=getUserInfo&is_jsonp=1&brand_id=" + brand_id + "&seckillid=" + seckillid + "&temp=" + temp,
                dataType: "__index__onp",
                jsonp: "__index__oncallback",
                success: homeAjax
            });
        } else {
            Ajax.call('ajax_dialog.php?act=getUserInfo', 'brand_id=' + brand_id + "&seckillid=" + seckillid + "&temp=" + temp, homeAjax, 'POST', 'JSON');
        }

        function ajax_Homeindex_Bonusadv() {
            var cfg_bonus_adv = "0";//是否开启首页弹出广告
            var suffix = "backup_tpl_1";

            if (cfg_bonus_adv == 1 && suffix) {
                Ajax.call('ajax_dialog.php?act=ajax_Homeindex_Bonusadv', 'suffix=' + suffix, function (data) {
                    if (data.error != 1) {
                        $("[ectype='bonusadv_box']").html(data.content);
                    }
                }, 'POST', 'JSON');
            }
        }

        ajax_Homeindex_Bonusadv();

        function homeAjax(data) {
            $("*[ectype='user_info']").html(data.content);
            $("*[ectype='homeBrand']").html(data.brand_list);
            console.log(data.seckill_goods);
            if (data.seckill_goods) {
                $('[data-mode="h-seckill"]').find('[data-type="range"] .box-bd').html(data.seckill_goods);
                var sec_begin_time = $('[data-mode="h-seckill"]').find('[data-type="range"] .box-bd').find('input[name="sec_begin_time"]').val();
                var sec_end_time = $('[data-mode="h-seckill"]').find('[data-type="range"] .box-bd').find('input[name="sec_end_time"]').val();
                if (sec_begin_time) {
                    $('[data-mode="h-seckill"]').find('[data-type="range"] .box-hd [ectype="time"]').attr("data-time", sec_begin_time)
                } else {
                    $('[data-mode="h-seckill"]').find('[data-type="range"] .box-hd [ectype="time"]').attr("data-time", sec_end_time)
                }
                $("*[ectype='time']").each(function () {
                    $(this).yomi();
                });
                //首页秒杀商品滚动
                $(".seckill-channel").slide({ mainCell: ".box-bd ul", effect: "leftLoop", autoPlay: true, autoPage: true, interTime: 5000, delayTime: 500, vis: 5, scroll: 1, trigger: "click" });
            } else {
                console.log(111);
            }

            $.catetopLift();

            $(window).scroll(function () {
                var scrollTop = $(document).scrollTop();
                var navTop = $("*[ectype='dscNav']").offset().top;

                if (scrollTop > navTop) {
                    $("*[ectype='suspColumn']").addClass("show");
                } else {
                    $("*[ectype='suspColumn']").removeClass("show");
                }
            });
        }

        //重新加载商品模块
        $("[data-mode='guessYouLike']").each(function () {
            var _this = $(this);
            var goods_ids = _this.data("goodsid");
            var warehouse_id = $("input[name='warehouse_id']").val();
            var area_id = $("input[name='area_id']").val();
            if (goods_ids) {
                Ajax.call('ajax_dialog.php?act=getguessYouLike', 'goods_ids=' + goods_ids + "&warehouse_id=" + warehouse_id + "&area_id=" + area_id, function (data) {
                    if (data.content) {
                        _this.find(".view .lift-channel ul").html(data.content);
                    }
                }, 'POST', 'JSON');
            }
        });

        $("li[ectype='floor_cat_content'].current").each(function () {
            get_homefloor_cat_content(this);
        });

        $("[ectype='identi_floorgoods'].current").each(function () {
            get_homefloor_cat_content(this);
        });

        function checked_article_cat() {
            var cat_ids = '';

            $('[data-mode="insertVipEdit"] .tit a').each(function () {
                var val = $(this).data('catid');
                if (cat_ids) {
                    cat_ids = cat_ids + "," + val;
                } else {
                    cat_ids = val;
                }
            });

            if (cat_ids) {
                Ajax.call('ajax_dialog.php?act=checked_article_cat', 'cat_ids=' + cat_ids, function (data) {
                    $('[data-mode="insertVipEdit"] .vip_article_cat').html(data.content);

                    //首页信息栏 新闻文章切换 
                    $(".vip-item").slide({ titCell: ".tit a", mainCell: ".con" });
                }, 'POST', 'JSON');
            }
        }
        checked_article_cat();
        //异步新品排行
        $("[ectype='h-phb3']").each(function () {
            var _this = $(this);
            var activitytype = _this.data('activitytype');
            var goodsids = _this.data('goodsids');
            var warehouse_id = $("input[name='warehouse_id']").val();
            var area_id = $("input[name='area_id']").val();

            Ajax.call('ajax_dialog.php?act=checked_home_rank', 'goodsids=' + goodsids + "&activitytype=" + activitytype + "&warehouse_id=" + warehouse_id + "&area_id=" + area_id, function (data) {
                _this.html(data.content);
            }, 'POST', 'JSON');
        });
    });

    //楼层左侧栏悬浮框
    readyLoad();
    //去掉悬浮框 我的购物车
    $(".attached-search-container .shopCart-con a span").text("");

    /*首页可视化 第八套模板 楼层左侧前后轮播 */
    aroundSilder(".floor_silder");
}

//公告和促销的切换
function ans(){
    $('.vip-item .tab_head_item').click(function(){
        var i=$(this).index();
        $(this).addClass('on').siblings().removeClass('on');
        $(this).parents('.vip-item').find('ul').eq(i).show().siblings().hide();
    });
}


$(function(){
    indexSlider();
    ans();//促销，公告
    $('.refresh-btn').click();//第一次自动加载品牌ajax事件
});