import RM_cookie from "./cookie.js";

var aKey = RM_cookie.getCookies("aKey");

var headers = new Headers();
// headers.append("aKey", aKey);

const APIURL="http://devapi.rabbitmonth.com";

export default {
  /**
   * post方式请求 json数据
   * @param {String} url 请求地址
   * @param {Object} data 传输数据对象
   * @returns {Promise} Promise对象 返回
   */
  postDataJSON(url, data) {

    var formData=new FormData();
    for(let key in data){
      formData.append(key,data[key]);
    }

    return new Promise((resolve, reject) => {
      var status = null;
      fetch(APIURL+url, {
        method: "POST",
        body: formData,
        headers,
        mode:"cors"
      }).then(response => {
        status = response.status;
        return response.json();
      }).then(response => {
        if (status == 200) {
          resolve(response);
        } else {
          reject({
            status,
            reason: response
          });
        }
      });
    })
  },
  /**
   * post方式请求 文本数据
   * @param {String} url 请求的URL地址
   * @param {Object} data 请求的数据
   * @returns {Promise} Promise对象返回 
   */
  postDataText(url, data) {

    var formData=new FormData();
    for(let key in data){
      formData.append(key,data[key]);
    }

    return new Promise((resolve, reject) => {
      var status = null;
      fetch(APIURL+url, {
        method: "POST",
        body: formData,
        headers,
        mode:"cors"
      }).then(response => {
        status = response.status;
        return response.text();
      }).then(response => {
        if (status == 200) {
          resolve(response);
        } else {
          reject({
            status,
            reason: response
          });
        }
      });
    })
  },
  /**
   * 对象转 url参数
   * @param {Object} params 转换的参数对象
   * @returns {String} 返回 参数字符串
   */
  objectToUrlParams(params) {
    let resStr = "?";
    var paramsLength = Object.keys(params).length;
    var paramsIndex = 0;
    for (let key in params) {
      if (paramsIndex == paramsLength - 1) {
        resStr += `${key}=${params[key]}`;
      } else {
        resStr += `${key}=${params[key]}&`;
      }
      paramsIndex++;
    }
    return resStr;
  },
  /**
   * get方式请求 json数据
   * @param {String} url 请求的URL地址
   * @param {Object} params 请求的参数
   * @returns {Object} 返回的是Promise对象
   */
  getDataJSON(url, params = null) {
    var status = null;

    if (params) {
      url += this.objectToUrlParams(params);
    }

    return new Promise((resolve, reject) => {
      fetch(APIURL+url, {
        headers,
        mode:"cors"
      }).then(response => {
        status = response.status;
        return response.json();
      }).then(response => {
        if (status == 200) {
          resolve(response);
        } else {
          reject({
            status,
            reason: response
          });
        }
      })
    });
  },
  /**
   * get方式请求 纯文本数据
   * @param {String} url 请求的URL地址
   * @param {Object} params 请求的参数
   * @returns {Object} 返回的是Promise对象
   */
  getDataText(url, params = null) {
    var status = null;
    if (params) {
      url += this.objectToUrlParams(params);
    }

    return new Promise((resolve, reject) => {
      fetch(APIURL+url, {
        headers,
        mode:"cors"
      }).then(response => {
        status = response.status;
        return response.text();
      }).then(response => {
        if (status == 200) {
          resolve(response);
        } else {
          reject({
            status,
            reason: response
          });
        }
      })
    });
  },
}