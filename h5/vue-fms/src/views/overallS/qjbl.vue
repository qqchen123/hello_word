<script>
 const serverSrc = 'www.baidu.com';
 const url = 'http://h5.yuandoujinfu.com/apis/fms';
 const callbackroute = '/index.php/CallBack/get_task_id/';
 const authurl = 'https://api.51datakey.com/h5/importV3/index.html#/';
 const authkey = '?apiKey=dd0171ba1b5f4b03a49375f4194b2b7e&userId=xiaoyougaotou_test&backUrl=';
 const authcallbackurl = 'http://h5.yuandoujinfu.com/#/creditEval?fuserid=';
 const base = 'http://h5.yuandoujinfu.com/#/';
 export default {
 	serverSrc,
 	url,
 	callbackroute,
 	authurl,
 	authkey,
 	authcallbackurl,
 	base,
 	settoken(value) {
     this.token = value
     console.log(value);
 	}
 }
</script>

<script>
 const serverSrc = 'dev';
 const callbackroute = '/index.php/CallBack/get_task_id/';
 const authurl = 'https://api.51datakey.com/h5/importV3/index.html#/';
 const authkey = '?apiKey=dd0171ba1b5f4b03a49375f4194b2b7e&userId=xiaoyougaotou_test&backUrl=';
var url = 'http://h5.yuandoujinfu.com/apis/fms';
var authcallbackurl = 'http://h5.yuandoujinfu.com/#/creditEval?fuserid=';
var base = 'http://h5.yuandoujinfu.com/#/';
if (process.env.NODE_ENV !== 'production') {
	url = 'http://new2fms.net/fms';
	authcallbackurl = 'http://localhost:8080/#/creditEval?fuserid=';
	base = 'http://localhost:8080/#/';
}
if (serverSrc === 'local') {
	url = 'http://new2fms.net/fms';
	authcallbackurl = 'http://h5.fms.dev/index.html/#/creditEval?fuserid=';
	base = 'http://localhost:8080/#/';
}
export default {
	serverSrc,
	url,
	callbackroute,
	authurl,
	authkey,
	authcallbackurl,
	base,
	settoken(value) {
		this.token = value
		console.log(value);
	}
}
</script>

