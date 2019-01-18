import Vue from 'vue'
import url from '../../overallS/qjbl'

export default {
	// 报单流程
	declUlogin: params => {
    return Vue.http.post(url.url + '/index.php/api/Front_employee/front_user_login', params)
    },
	subCustomerInfo: params => {
    return Vue.http.post(url.url + '/index.php/api/Front_employee/sub_customer', params, "{headers:{'Content-Type':'multipart/form-data'}}")
    },
    subCinfo: params => {
    return Vue.http.post(url.url + '/index.php/api/Front_employee/find', params)
    },
    selectCinfo: params => {
    return Vue.http.post(url.url + '/index.php/api/Front_employee/building_find', params)
    },
    selectBinfo: params => {
    return Vue.http.post(url.url + '/index.php/api/Front_employee/unit_find', params)
    },
    selectHinfo: params => {
    return Vue.http.post(url.url + '/index.php/api/Front_employee/getprice', params)
    },
    subHouseProperty: params => {
    return Vue.http.post(url.url + '/index.php/api/Front_employee/sub_house_property', params)
    },
    subDeclaration: params => {
    return Vue.http.post(url.url + '/index.php/api/Front_employee/sub_declaration', params)
    },
    getDeclaraList: params => {
    return Vue.http.post(url.url + '/index.php/api/Front_employee/get_orderlist', params)
    }
}
