layui.use('layer', function () {
    var $ = layui.jquery, layer = layui.layer;
});
$(function () {

    if (!window.base.getLocalStorage('token')) {
        window.location.href = 'login.html';
    }

    var pageIndex = 1,
        moreDataFlag = true;
    getOrders(pageIndex);
    getProducts(pageIndex);
    getCategories(pageIndex);

    /*
    * 获取订单数据分页
    * params:
    * pageIndex - {int} 分页下表  1开始
    */
    function getOrders(pageIndex) {
        var params = {
            url: 'order/paginate',
            data: {page: pageIndex, size: 20},
            tokenFlag: true,
            sCallback: function (res) {
                var str = getOrderHtmlStr(res);
                $('#order-table').append(str);
            }
        };
        window.base.getData(params);
    }

    /*
    * 获取商品数据分页
    * params:
    * pageIndex - {int} 分页下表  1开始
    */
    function getProducts(pageIndex) {
        var params = {
            url: 'product/paginate',
            data: {page: pageIndex, size: 20},
            tokenFlag: true,
            sCallback: function (res) {
                var str = getProductsHtmlStr(res);
                $('#products-table').append(str);
            }
        };
        window.base.getData(params);
    }

    /*
    * 获取分类数据分页
    * params:
    * pageIndex - {int} 分页下表  1开始
    */
    function getCategories(pageIndex) {
        var params = {
            url: 'category/paginate',
            data: {page: pageIndex, size: 20},
            tokenFlag: true,
            sCallback: function (res) {
                var str = getCategorysHtmlStr(res);
                $('#categories-table').append(str);
            }
        };
        window.base.getData(params);
    }

    /*拼接分类html字符串*/
    function getCategorysHtmlStr(res) {
        var data = res.data;
        if (data) {
            var len = data.length,
                str = '', item;
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    item = data[i];
                    str += '<tr>' +
                        '<td>' + item.id + '</td>' +
                        '<td>' + item.name + '</td>' +
                        '<td>' + getCategoriesShow(item.status) + '</td>' +
                        '<td>' + i + '</td>' +
                        '<td data-id="' + item.id + '">' + getCategoriesBtns(item.status) + '</td>' +
                        '<td></td>' +
                        '</tr>';
                }
            }
            else {
                ctrlLoadMoreBtn();
                moreDataFlag = false;
            }
            return str;
        }
        return '';
    }

    /*拼接商品html字符串*/
    function getProductsHtmlStr(res) {
        var data = res.data;
        if (data) {
            var len = data.length,
                str = '', item;
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    item = data[i];
                    str += '<tr>' +
                        '<td>' + item.name + '</td>' +
                        '<td><img src="' + item.main_img_url + '"/></td>' +
                        '<td>￥' + item.price + '</td>' +
                        '<td>' + item.stock + '</td>' +
                        '<td>' + item.category_id + '</td>' +
                        '<td data-id="' + item.id + '">' + getProductBtns(item.status) + '</td>' +
                        '</tr>';
                }
            }
            else {
                ctrlLoadMoreBtn();
                moreDataFlag = false;
            }
            return str;
        }
        return '';
    }

    /*拼接订单html字符串*/
    function getOrderHtmlStr(res) {
        var data = res.data;
        if (data) {
            var len = data.length,
                str = '', item;
            if (len > 0) {
                for (var i = 0; i < len; i++) {
                    item = data[i];
                    str += '<tr>' +
                        '<td>' + item.order_no + '</td>' +
                        '<td>' + item.snap_name + '</td>' +
                        '<td>' + item.total_count + '</td>' +
                        '<td>￥' + item.total_price + '</td>' +
                        '<td>' + getOrderStatus(item.status) + '</td>' +
                        '<td>' + item.create_time + '</td>' +
                        '<td data-id="' + item.id + '">' + getOrderBtns(item.status) + '</td>' +
                        '</tr>';
                }
            }
            else {
                ctrlLoadMoreBtn();
                moreDataFlag = false;
            }
            return str;
        }
        return '';
    }
    /*根据分类状态获得标志*/
    function getCategoriesShow(status) {
        str = '';
        if(status == 0){
            str += '<span class="category-status-txt">显示</span>';
        }else if(status == 1) {
            str += '<span class="category-status-txt">不显示</span>';
        }
        return str;
    }
    /*根据订单状态获得标志*/
    function getOrderStatus(status) {
        var arr = [{
            cName: 'unpay',
            txt: '未付款'
        }, {
            cName: 'payed',
            txt: '已付款'
        }, {
            cName: 'done',
            txt: '已发货'
        }, {
            cName: 'unstock',
            txt: '缺货'
        }];
        return '<span class="order-status-txt ' + arr[status - 1].cName + '">' + arr[status - 1].txt + '</span>';
    }
    /*根据订单状态获得 操作按钮*/
    function getOrderBtns(status) {
        var arr = [{
            cName: 'done',
            txt: '发货'
        }, {
            cName: 'unstock',
            txt: '缺货'
        }];
        if (status == 2 || status == 4) {
            var index = 0;
            if (status == 4) {
                index = 1;
            }
            return '<span class="order-btn ' + arr[index].cName + '">' + arr[index].txt + '</span>';
        } else {
            return '';
        }
    }
    /*分类 操作按钮*/
    function getCategoriesBtns(status) {
        var arr = [{
            cName: 'onShow',
            txt: '客户端显示'
        }, {
            cName: 'offShow',
            txt: '客户端不显示'
        }, {
            cName: 'create',
            txt: '编辑'
        }, {
            cName: 'delete',
            txt: '删除'
        }];
        str ='';
        if (status == 0) {
            index = 1;
            str += '<span class="category-btn ' + arr[index].cName + '">' + arr[index].txt + '</span>';
        } else{
            index = 0;
            str += '<span class="category-btn ' + arr[index].cName + '">' + arr[index].txt + '</span>';
        }
        str += '<span class="category-btn ' + arr[2].cName + '">' + arr[2].txt + '</a></span>' +
            '<span class="category-btn ' + arr[3].cName + '">' + arr[3].txt + '</span>';
        return str;
    }
    /*商品 操作按钮*/
    function getProductBtns(status) {
        var arr = [{
            cName: 'on',
            txt: '上架'
        }, {
            cName: 'off',
            txt: '下架'
        }, {
            cName: 'create',
            txt: '编辑'
        }, {
            cName: 'delete',
            txt: '删除'
        }];
        str ='';
        if (status == 0) {
            index = 1;
            str += '<span class="product-btn ' + arr[index].cName + '">' + arr[index].txt + '</span>';
        } else if(status == 1){
            index = 0;
            str += '<span class="product-btn ' + arr[index].cName + '">' + arr[index].txt + '</span>';
        }
        str += '<span class="product-btn ' + arr[2].cName + '">' + arr[2].txt + '</span>' +
            '<span class="product-btn ' + arr[3].cName + '">' + arr[3].txt + '</span>';
        return str;
    }

    /*控制加载更多按钮的显示*/
    function ctrlLoadMoreBtn() {
        if (moreDataFlag) {
            $('.load-more').hide().next().show();
        }
    }

    /*列表切换*/
    $(document).on('click', '.leftbar-item', function () {
        $.each($(this), function () {
            data = $(this).attr('id');
            // alert(data);
            $('.box').hide();
            $.each('.desktop-main', function () {
                $('.desktop-main .' + data + '').show();
            });
        });
    });
    /*加载更多*/
    $(document).on('click', '.load-more', function () {
        if (moreDataFlag) {
            pageIndex++;
            getOrders(pageIndex);
        }
    });
    /*发货*/
    $(document).on('click', '.order-btn.done', function () {
        var $this = $(this),
            $td = $this.closest('td'),
            $tr = $this.closest('tr'),
            id = $td.attr('data-id'),
            $tips = $('.global-tips'),
            $p = $tips.find('p');
        var params = {
            url: 'order/delivery',
            type: 'put',
            data: {id: id},
            tokenFlag: true,
            sCallback: function (res) {
                if (res.code.toString().indexOf('2') == 0) {
                    $tr.find('.order-status-txt')
                        .removeClass('pay').addClass('done')
                        .text('已发货');
                    $this.remove();
                    $p.text('操作成功');
                } else {
                    $p.text('操作失败');
                }
                $tips.show().delay(1500).hide(0);
            },
            eCallback: function () {
                $p.text('操作失败');
                $tips.show().delay(1500).hide(0);
            }
        };
        window.base.getData(params);
    });

    /*分类操作*/
    $(document).on('click', '.category-btn.offShow', function () {
        var $this = $(this),
            $td = $this.closest('td'),
            $tr = $this.closest('tr'),
            id = $td.attr('data-id'),
            $tips = $('.global-tips'),
            $p = $tips.find('p');
        var params = {
            url: 'category/delivery',
            type: 'put',
            data: {
                id: id,
                status: 1
            },
            tokenFlag: true,
            sCallback: function (res) {
                if (res.code.toString().indexOf('2') == 0) {
                    $tr.find('.category-status-txt').text('不显示');
                    $tr.find('.category-btn.offShow')
                        .removeClass('offShow').addClass('onShow')
                        .text('客户端显示');
                    // $this.remove();
                    $p.text('操作成功');
                } else {
                    $p.text('操作失败');
                }
                $tips.show().delay(1500).hide(0);
            },
            eCallback: function () {
                $p.text('操作失败');
                $tips.show().delay(1500).hide(0);
            }
        };
        window.base.getData(params);
    });
    $(document).on('click', '.category-btn.onShow', function () {
        var $this = $(this),
            $td = $this.closest('td'),
            $tr = $this.closest('tr'),
            id = $td.attr('data-id'),
            $tips = $('.global-tips'),
            $p = $tips.find('p');
        var params = {
            url: 'category/delivery',
            type: 'put',
            data: {
                id: id,
                status: 0
            },
            tokenFlag: true,
            sCallback: function (res) {
                if (res.code.toString().indexOf('2') == 0) {
                    $tr.find('.category-status-txt').text('显示');
                    $tr.find('.category-btn.onShow')
                        .removeClass('onShow').addClass('offShow')
                        .text('客户端不显示');
                    // $this.remove();
                    $p.text('操作成功');
                } else {
                    $p.text('操作失败');
                }
                $tips.show().delay(1500).hide(0);
            },
            eCallback: function () {
                $p.text('操作失败');
                $tips.show().delay(1500).hide(0);
            }
        };
        window.base.getData(params);
    });
    $(document).on('click', '.category-btn.create', function () {
        var $this = $(this),
            $td = $this.closest('td'),
            $tr = $this.closest('tr'),
            id = $td.attr('data-id'),
            name = $tr.children('td').eq(1).text();
        layer.open({
            type: 2,
            title: '修改分类',
            scrollbar: false,
            anim: 1,
            maxmin: false,
            skin: 'layui-layer-molv',
            shadeClose: true, //点击遮罩关闭层
            area: ['360px', '180px'],
            content: '../pages/category/category_edit.html',//弹框显示的url
            btn: ['修改', '取消'],
            success: function(layero, index){
                var body = layer.getChildFrame('body', index);
                var iframeWin = window[layero.find('iframe')[0]['name']];
                body.find('#name').val(name);
            },
            yes: function (index, layero) {//后台提交
                var body = layer.getChildFrame('body', index);
                var iframeWin = window[layero.find('iframe')[0]['name']]; //得到iframe页的窗口对象，执行iframe页的方法：
                name = body.find("#name").val();
                console.log(id);
                console.log(name);
                if (name == '') {
                    layer.msg('请完善内容');
                } else {
                    $.ajax({
                        url: 'http://hmapi.com/api/v1/category/editCategory',
                        type: 'PUT',
                        dataType: 'json',
                        async: true,
                        data: {
                            'id': id,
                            'name': name
                        },
                        beforeSend: function () {
                            layer.load();        //打开一个遮罩层，或者直接禁用你的按钮
                        },
                        complete: function () {
                            layer.close(layer.load());     //取消遮罩层，或者回复按钮
                        },
                        success: function (data) {
                            if (data.code == 201 && data.errorCode == 0) {
                                layer.msg('修改成功啦！', {
                                    icon: 6,
                                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                                }, function () {
                                    parent.location.reload(); // 父页面刷新
                                    parent.layer.close(index);
                                });
                                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            } else {
                                layer.msg(data.msg + ',异常代码:' + data.errorCode, {
                                    icon: 5,
                                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                                }, function () {
                                });
                            }
                        },
                        error: function () {
                            layer.msg('操作失败,请重试', {
                                icon: 5,
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            }, function () {
                            });
                        }
                    });
                    return false;
                }
            },
            btn2: function () {
                layer.closeAll();
            }
        });
    });

    $(document).on('click', '.category-btn.delete', function () {
        var $this = $(this),
            $td = $this.closest('td'),
            $tr = $this.closest('tr'),
            id = $td.attr('data-id'),
            $tips = $('.global-tips'),
            $p = $tips.find('p');
        var params = {
            url: 'category/delivery',
            type: 'put',
            data: {
                id: id,
                status: -1
            },
            tokenFlag: true,
            sCallback: function (res) {
                if (res.code.toString().indexOf('2') == 0) {
                    $tr.remove();
                    $p.text('操作成功');
                } else {
                    $p.text('操作失败');
                }
                $tips.show().delay(1500).hide(0);
            },
            eCallback: function () {
                $p.text('操作失败');
                $tips.show().delay(1500).hide(0);
            }
        };
        window.base.getData(params);
    });


    /*商品操作*/
    $(document).on('click', '.product-btn.off', function () {
        var $this = $(this),
            $td = $this.closest('td'),
            $tr = $this.closest('tr'),
            id = $td.attr('data-id'),
            $tips = $('.global-tips'),
            $p = $tips.find('p');
        var params = {
            url: 'product/delivery',
            type: 'put',
            data: {
                id: id,
                status: 1
            },
            tokenFlag: true,
            sCallback: function (res) {
                if (res.code.toString().indexOf('2') == 0) {
                    $tr.find('.product-btn.off')
                        .removeClass('off').addClass('on')
                        .text('上架');
                    $p.text('操作成功');
                } else {
                    $p.text('操作失败');
                }
                $tips.show().delay(1500).hide(0);
            },
            eCallback: function () {
                $p.text('操作失败');
                $tips.show().delay(1500).hide(0);
            }
        };
        window.base.getData(params);
    });
    $(document).on('click', '.product-btn.on', function () {
        var $this = $(this),
            $td = $this.closest('td'),
            $tr = $this.closest('tr'),
            id = $td.attr('data-id'),
            $tips = $('.global-tips'),
            $p = $tips.find('p');
        var params = {
            url: 'product/delivery',
            type: 'put',
            data: {
                id: id,
                status: 0
            },
            tokenFlag: true,
            sCallback: function (res) {
                if (res.code.toString().indexOf('2') == 0) {
                    $tr.find('.product-btn.on')
                        .removeClass('on').addClass('off')
                        .text('下架');
                    $p.text('操作成功');
                } else {
                    $p.text('操作失败');
                }
                $tips.show().delay(1500).hide(0);
            },
            eCallback: function () {
                $p.text('操作失败');
                $tips.show().delay(1500).hide(0);
            }
        };
        window.base.getData(params);
    });
    $(document).on('click', '.product-btn.create', function () {
        var $this = $(this),
            $td = $this.closest('td'),
            $tr = $this.closest('tr'),
            id = $td.attr('data-id'),
            old_name = $tr.children('td').eq(0).text(),
            old_price = $tr.children('td').eq(2).text(),
            old_stock = $tr.children('td').eq(3).text(),
            old_category_id = $tr.children('td').eq(4).text();
        layer.open({
            type: 2,
            title: '修改商品',
            scrollbar: false,
            anim: 1,
            maxmin: false,
            skin: 'layui-layer-molv',
            shadeClose: true, //点击遮罩关闭层
            area: ['500px', '400px'],
            content: '../pages/product/product_edit.html',//弹框显示的url
            btn: ['修改', '取消'],
            success: function(layero, index){
                var body = layer.getChildFrame('body', index);
                var iframeWin = window[layero.find('iframe')[0]['name']];
                body.find('#name').val(old_name);
                body.find('#stock').val(old_stock);
                body.find('#price').val(old_price);
                body.find('#category_id').val(old_category_id);
            },
            yes: function (index, layero) {//后台提交
                var body = layer.getChildFrame('body', index);
                var iframeWin = window[layero.find('iframe')[0]['name']]; //得到iframe页的窗口对象，执行iframe页的方法：
                name = body.find("#name").val();
                main_img_url = body.find("#main_img_url").val();
                price = body.find("#price").val();
                stock = body.find("#stock").val();
                category_id = body.find("#category_id").val();
                main_img_url = body.find("#main_img_url").val();
                console.log(id);
                console.log(name);
                console.log(price);
                console.log(stock);
                console.log(category_id);
                console.log(main_img_url);
                if (name == '' || price == '' || stock == '' || category_id == '') {
                    layer.msg('请完善内容');
                } else {
                    $.ajax({
                        url: 'http://hmapi.com/api/v1/product/editProduct',
                        type: 'PUT',
                        dataType: 'json',
                        async: true,
                        data: {
                            'id': id,
                            'name': name,
                            'stock': stock,
                            'price': price,
                            'category_id': category_id,
                            'main_img_url': main_img_url
                        },
                        beforeSend: function () {
                            layer.load();        //打开一个遮罩层，或者直接禁用你的按钮
                        },
                        complete: function () {
                            layer.close(layer.load());     //取消遮罩层，或者回复按钮
                        },
                        success: function (data) {
                            if (data.code == 201 && data.errorCode == 0) {
                                layer.msg('修改成功啦！', {
                                    icon: 6,
                                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                                }, function () {
                                    parent.location.reload(); // 父页面刷新
                                    parent.layer.close(index);
                                });
                                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                            } else {
                                layer.msg(data.msg + ',异常代码:' + data.errorCode, {
                                    icon: 5,
                                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                                }, function () {
                                });
                            }
                        },
                        error: function () {
                            layer.msg('操作失败,请重试', {
                                icon: 5,
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            }, function () {
                            });
                        }
                    });
                    return false;
                }
            },
            btn2: function () {
                layer.closeAll();
            }
        });
    });
    $(document).on('click', '.product-btn.delete', function () {
        var $this = $(this),
            $td = $this.closest('td'),
            $tr = $this.closest('tr'),
            id = $td.attr('data-id'),
            $tips = $('.global-tips'),
            $p = $tips.find('p');
        var params = {
            url: 'product/delivery',
            type: 'put',
            data: {
                id: id,
                status: -1
            },
            tokenFlag: true,
            sCallback: function (res) {
                if (res.code.toString().indexOf('2') == 0) {
                    $tr.remove();
                    $p.text('操作成功');
                } else {
                    $p.text('操作失败');
                }
                $tips.show().delay(1500).hide(0);
            },
            eCallback: function () {
                $p.text('操作失败');
                $tips.show().delay(1500).hide(0);
            }
        };
        window.base.getData(params);
    });
    /*退出*/
    $(document).on('click', '#login-out', function () {
        window.base.deleteLocalStorage('token');
        window.location.href = 'login.html';
    });
});