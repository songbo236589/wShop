import { Base } from '../../utils/base.js';
class Product extends Base {
  constructor() {
    super();
  }
  getGoodsContent(id, callBack) {
    var that = this;
    var params = {
      url: 'getGoodsContent',
      type: 'POST',
      data: { id: id },
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
  postAddCart(goods_id, num, callBack) {
    var that = this;
    var params = {
      url: 'postAddCart',
      type: 'POST',
      data: { goods_id: goods_id, num: num},
      sCallBack: function (res) {
        callBack && callBack(res.data);
      }
    };
    that.request(params);
  };
}

export { Product }