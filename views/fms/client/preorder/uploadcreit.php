<style type="text/css">
	#upload:hover{
		cursor: pointer;
	}
	#next-page:hover{
		cursor: pointer;
	}
</style>
<div>
	<div>
		<div>征信第一页：</div>
		<div><input type="file" name="mcfirst"></div>
	</div>
	<div>
		<div>征信第二页</div>
		<div><input type="file" name="mcsecond"></div>
	</div>
	<div>
		<div>征信第三页</div>
		<div><input type="file" name="mcthird"></div>
	</div>
	<div>
		这里加一个控件  能够增加上传的文件数量  需要动态生成页面元素
	</div>
	<div>
		<span id="upload">上传</span>
		<span id="next-page">下一步</span>
	</div>
</div>