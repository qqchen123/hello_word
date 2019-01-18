<?php $a=pathinfo($f);?>
<div class="col-xs-12 proverow" id="<?php echo $a['filename']?>">
    <div class="row provec"><img
            src="<?php echo $f; ?>"/>
    </div>
    <div class="row provec1">
        <a _href="<?php echo site_url('qiye/provedelete/' . $id. '/' . $t . '/' . $a['basename']) ?>">删除</a>
    </div>
</div>