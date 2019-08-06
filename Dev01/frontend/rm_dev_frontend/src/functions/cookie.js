export default {
  /**
   * 根据key 获取 cookie值
   * @param {String} cookieKey cookie key
   */
  getCookies(cookieKey = null) {
    let cookieData = document.cookie.split(";");
    let cookies = {};
    cookieData.forEach(element => {
      element = element.split("=");
      cookies[element[0].trim()] = element[1];
    });
    if (cookieKey) {
      if (cookies[cookieKey]) {
        return cookies[cookieKey];
      } else {
        return null;
      }
    } else {
      return cookies;
    }
  },
  /**
   * 设置/修改 cookie
   * @param {Array} cookies 设置的cookie对象数组
   */
  setCookie(cookies) {
    let date = new Date();

    cookies.forEach((cookie) => {
      if (cookie.expires) {
        date.setTime(date.getTime() + (cookie.expires * 24 * 60 * 60 * 1000));
        var expires = "expires=" + date.toUTCString();
        document.cookie=`${cookie.key}=${cookie['value']};expires=${expires}`;
      } else {
        document.cookie = `${cookie.key}=${cookie['value']};`;
      }
    })
  },
  deleteCookies(cookies){
    cookies.forEach(key=>{
      this.setCookie([
        {
          key,
          value:"",
          expires:"Thu, 01 Jan 1970 00:00:01 GMT"
        }
      ]);
    })
  }
}