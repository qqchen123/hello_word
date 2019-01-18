<script>
	export default {
 	 shijians () {
        var date = new Date();
         var nian = "年";
         var yue = "月";
         var ri = "日";
         var shi = " ";
         var fen = ":"
         var year = date.getFullYear();
         var month = date.getMonth() + 1;
         var strDate = date.getDate();
         var hour = date.getHours();
         var mt = date.getMinutes();
         var miao = date.getSeconds()
	     if (month >= 1 && month <= 9) {
	           month = "0" + month;
	       }
	     if (strDate >= 0 && strDate <= 9) {
	          strDate = "0" + strDate;
	      }
	     if (strDate >= 0 && strDate <= 9) {
	         strDate = "0" + strDate;
	      }
	    if (hour >= 0 && hour <= 9) {
	         hour = "0" + hour;
	     }
	    if (mt >= 0 && mt <= 9) {
	         mt = "0" + mt;
	     }
		 let currentdate = year + nian + month + yue + strDate + ri + shi + hour + fen + mt + fen + miao;
		return currentdate;
   }
}
</script>
