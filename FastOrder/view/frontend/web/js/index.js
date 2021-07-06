define(['jquery', 'uiComponent', 'ko','mage/url','mage/storage','Magento_Customer/js/customer-data'], function ($, Component, ko ,urlBuilder, storage,customerData) {
    'use strict';
    function resultView(){
        var self = this;
        self.search = ko.observable(),
        self.resultSearch = ko.observableArray([]),
        self.productCart = ko.observableArray([]),
        self.productLine = ko.observableArray([]),
        self.isSelected = ko.observable(false),
        self.eventSearch = function()
        {
            var self = this;
            var url = urlBuilder.build('fastorder/index/search');
            
            storage.post(
                url,
                JSON.stringify(self.search()),
                false
            ).done(
                function (respone) {
                    var result = $.map(respone, function (item) {
                        item['isCheck'] = ko.observable(self.checkExist(item));
                        self.checkProduct = function(item)
                        {
                            item.qty = ko.observable(1);
                            item.qtyUp = function(){
                                item.qty(item.qty() + 1);
                            }
                            item.qtyDown = function(){
                                if(item.qty() > 1)
                                {
                                    item.qty(item.qty() - 1);
                                }
                                
                            }
                            item.total = ko.pureComputed(function() {
                                return item.price * item.qty();
                            });
                            var exist = false;
                            var idProducSearch = item.entity_id;
                            if(self.productCart().length)
                            {
                                ko.utils.arrayFilter(self.productCart(), function (product) {
                                    if (product.entity_id == idProducSearch) {
                                        exist = true;
                                    }
                                });
                            }
                            if(item.isCheck() && !exist)
                            {
                                self.productCart.push(item);
                            }
                            else
                            {
                                self.productCart.remove(item)
                            }
                        }
                        return item;
                        
                    })
                
                self.resultSearch(result);
                
                }

            ).fail(
            );
        }

        self.checkExist = function(item) {
            var exist = false;
            var idProducSearch = item.entity_id;
            if(self.productCart().length)
            {
                ko.utils.arrayFilter(self.productCart(), function (product) {
                    if (product.entity_id == idProducSearch) {
                        exist = true;
                    }
                });
            }
            return exist;

        }
        self.subTotal = ko.computed(function () {
            var total = 0;
            ko.utils.arrayFilter(self.productCart(), function (product) {
                total += product.total();
            });
            return total;
        })
        self.totalQty = ko.computed(function () {
            var qty = 0;
            ko.utils.arrayFilter(self.productCart(), function (product) {
                qty += product.qty();
            });
            return qty;
        })
        self.totalFill = ko.computed(function () {
            return self.productCart().length;
        })
        self.removeLine = function(item)
        { 
            self.productCart.remove(item) 
            item.isCheck(false);
        
        };
        self.addCart = function()
        {
            var self = this;
            var url = urlBuilder.build('fastorder/index/addCart');
            var data =[];
            ko.utils.arrayFilter(self.productCart(), function (product) {
                data.push({
                    'product': product.entity_id,
                    'qty': product.qty()
                })
            });
            storage.post(
                url,
                JSON.stringify(data),
                false
            ).done(
                function (respone) {
                    alert('ok')
                    customerData.reload(['cart'], true);
                    self.productCart.removeAll();
                    self.resultSearch([]);
                    self.search('');
                }

            ).fail(
            );
        }

    };
    return Component.extend(new resultView());
});
