/**
 *
 * @authors itcast.cn
 * @date    2017-03-04 10:18:03
 * @version 1.0
 */

/*
 * 根据元素的id获得其坐标(x轴和y轴)
 */
function getElementPos(elementId) {
    var ua = navigator.userAgent.toLowerCase();
    var isOpera = (ua.indexOf('opera') != -1);
    var isIE = (ua.indexOf('msie') != -1 && !isOpera); // not opera spoof
    var el = document.getElementById(elementId);
    if(el.parentNode === null || el.style.display == 'none') {
        return false;
    }
    var parent = null;
    var pos = [];
    var box;
    if(el.getBoundingClientRect) {   //IE
        box = el.getBoundingClientRect();
        var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
        var scrollLeft = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
        return {
            x:box.left + scrollLeft,
            y:box.top + scrollTop
        };
    }else if(document.getBoxObjectFor) {   // gecko
        box = document.getBoxObjectFor(el);
        var borderLeft = (el.style.borderLeftWidth)?parseInt(el.style.borderLeftWidth):0;
        var borderTop = (el.style.borderTopWidth)?parseInt(el.style.borderTopWidth):0;
        pos = [box.x - borderLeft, box.y - borderTop];
    }else {   // safari & opera
        pos = [el.offsetLeft, el.offsetTop];
        parent = el.offsetParent;
        if (parent != el) {
            while (parent) {
                pos[0] += parent.offsetLeft;
                pos[1] += parent.offsetTop;
                parent = parent.offsetParent;
            }
        }
        if (ua.indexOf('opera') != -1 || ( ua.indexOf('safari') != -1 && el.style.position == 'absolute' )) {
            pos[0] -= document.body.offsetLeft;
            pos[1] -= document.body.offsetTop;
        }
    }
    if (el.parentNode) {
        parent = el.parentNode;
    } else {
        parent = null;
    }
    while (parent && parent.tagName != 'BODY' && parent.tagName != 'HTML') { // account for any scrolled ancestors
        pos[0] -= parent.scrollLeft;
        pos[1] -= parent.scrollTop;
        if (parent.parentNode) {
            parent = parent.parentNode;
        } else {
            parent = null;
        }
    }
    return {
        x:pos[0],
        y:pos[1]
    };
}

//关闭弹窗的方法
function hideElement(eleID){
    //隐藏
    $('#' + eleID).hide();//等价于$('#cartBox').css('display','none');
}

//修改商品的数量
function change_number(goods_id,flag){
    //获取值
    var _val = $('#gid_' + goods_id).val();
    //判断分支
    if(flag == 'reduce'){
        //判断
        if(_val == '1'){
            alert('至少购买1件商品');return false;
        }else{
            _val--;		//--_val;
        }
    }else if(flag == 'add'){
        _val++;
    }else{
        //一般会写点数据有效性的验证
    }
    //请求ajax
    $.get(editUrl,{goods_id: goods_id,amount: _val},function(data){
        //更新页面上的值
        $('#price_' + goods_id).html(data.goods_total_price);	//更新小计
        $('#total').html(data.price);		//总计更新
        //将更改的值重新放回到input中
        $('#gid_' + goods_id).val(_val);
    },'json');
}

//jQuery代码
$(function(){
    //给add_btn绑定点击事件
    $('#add_btn').click(function(){
        //获取商品id
        var goods_id = $(this).attr('data');
        //获取购买数量
        var amount = $('.amount').val();
        //发送ajax请求
        $.ajax({
            url: addUrl,
            data: {goods_id: goods_id,amount: amount},
            dataType: 'json',
            type: 'POST',
            success: function(data){
                //事件的处理程序
                var pos = getElementPos('add_btn');		//获取添加购物车按钮的坐标
                //改变位置
                $('#cartBox').css('left',pos.x - 80);			//x轴
                $('#cartBox').css('top',pos.y + 38);			//y轴
                //展示购物车中的统计信息
                $('#goods_number').html(data.number);
                $('#goods_totalprice').html(data.price);
                //展示弹出层
                $('#cartBox').show();		//等价于$('#cartBox').css('display','block');
            }
        });
    });

    //删除购物车商品
    $('.del').click(function(){
        //获取id
        var goods_id = $(this).attr('data');
        //赋值this
        var _this = this;
        //发送ajax请求
        $.post(delUrl,{goods_id: goods_id},function(data){
            //事件的处理程序
            if(data.errorCode == '1'){
                alert('删除商品失败');
            }else{
                //更好总价，同时需要节点移出掉
                $('#total').html(data.errorText);
                $(_this).parent().parent().remove();
            }
        },'json')
    });
});