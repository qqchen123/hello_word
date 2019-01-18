<!-- <?php //var_dump($_GET['url']); ?> --> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
  <!-- <meta name="description" content="A jQuery plugin wrapper for Cropper.js."> -->
  <!-- <meta name="author" content="Chen Fengyuan"> -->
  <!-- <title>jquery-cropper</title> -->

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/lib/jquery-cropper-master/docs/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/lib/jquery-cropper-master/docs/css/cropper.css">
  <!-- <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.css"> -->

  <link rel="stylesheet" href="/assets/lib/jquery-cropper-master/docs/css/main.css">
</head>
<body>
  <br>
  <br>
  <br>
  <br>
  <br>
  <!--[if lt IE 9]>
  <div class="alert alert-warning alert-dismissible fade show m-0 rounded-0" role="alert">
    You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <![endif]-->

  <!-- Header -->
<!--   <header class="navbar navbar-light navbar-expand-md">
    <div class="container">
      <a class="navbar-brand" href="./">jquery-cropper</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbar-collapse" role="navigation">
        <nav class="nav navbar-nav">
          <a class="nav-link" href="https://github.com/fengyuanchen/jquery-cropper/blob/master/README.md" data-toggle="tooltip" title="View the documentation">Docs</a>
          <a class="nav-link" href="https://github.com/fengyuanchen/jquery-cropper" data-toggle="tooltip" title="View the GitHub project">GitHub</a>
          <a class="nav-link" href="https://fengyuanchen.github.io/cropperjs" data-toggle="tooltip" title="JavaScript image cropper">Cropper.js</a>
          <a class="nav-link" href="https://fengyuanchen.github.io" data-toggle="tooltip" title="More projects">More</a>
          <a class="nav-link" href="https://chenfengyuan.com" data-toggle="tooltip" title="About the author">About</a>
        </nav>
      </div>
    </div>
  </header> -->

  <!-- Jumbotron -->
  <!-- < div class="jumbotron bg-primary text-white rounded-0">
    <div class="container">
      <div class="row">
        <div class="col-md">
          <h1>jquery-cropper <small class="h6">v1.0.0</small></h1>
          <p class="lead">A jQuery plugin wrapper for Cropper.js.</p>
        </div>
        <div class="col-md">
          <div class="carbonads">
            <script id="_carbonads_js" src="//cdn.carbonads.com/carbon.js?serve=CKYI55Q7&placement=fengyuanchengithubio" async></script>
          </div>
        </div>
      </div>
    </div>
  </div> -->

  <!-- Content -->
  <div class="container">
    <div class="row">
      <div class="col-md-9">
        <!-- <h3>Demo:</h3> -->
        <div class="img-container">
          <img id="image" src="<?= $url?>" alt="Picture">
        </div>
      </div>
      <div class="col-md-3">
        <!-- <h3>Preview:</h3> -->
        <div class="docs-preview clearfix">
          <div class="img-preview preview-lg"></div>
          <div class="img-preview preview-md"></div>
          <div class="img-preview preview-sm"></div>
          <div class="img-preview preview-xs"></div>
        </div>

        <!-- <h3>Data:</h3> -->
        <div class="docs-data">
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataX">X</label>
            </span>
            <input type="text" class="form-control" id="dataX" placeholder="x">
            <span class="input-group-append">
              <span class="input-group-text">px</span>
            </span>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataY">Y</label>
            </span>
            <input type="text" class="form-control" id="dataY" placeholder="y">
            <span class="input-group-append">
              <span class="input-group-text">px</span>
            </span>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataWidth">Width</label>
            </span>
            <input type="text" class="form-control" id="dataWidth" placeholder="width">
            <span class="input-group-append">
              <span class="input-group-text">px</span>
            </span>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataHeight">Height</label>
            </span>
            <input type="text" class="form-control" id="dataHeight" placeholder="height">
            <span class="input-group-append">
              <span class="input-group-text">px</span>
            </span>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataRotate">Rotate</label>
            </span>
            <input type="text" class="form-control" id="dataRotate" placeholder="rotate">
            <span class="input-group-append">
              <span class="input-group-text">deg</span>
            </span>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataScaleX">ScaleX</label>
            </span>
            <input type="text" class="form-control" id="dataScaleX" placeholder="scaleX">
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-prepend">
              <label class="input-group-text" for="dataScaleY">ScaleY</label>
            </span>
            <input type="text" class="form-control" id="dataScaleY" placeholder="scaleY">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-9 docs-buttons">
        <!-- <h3>Toolbar:</h3> -->
        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" title="Move">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="移动图片">
            <!-- $().cropper(&quot;setDragMode&quot;, &quot;move&quot;) -->
              <span class="fa fa-arrows"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Crop">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="剪切框">
            <!-- $().cropper(&quot;setDragMode&quot;, &quot;crop&quot;) -->
              <span class="fa fa-crop"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="放大">
            <!-- $().cropper(&quot;zoom&quot;, 0.1) -->
              <span class="fa fa-search-plus"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="缩小">
            <!-- $().cropper(&quot;zoom&quot;, -0.1) -->
              <span class="fa fa-search-minus"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="左移">
            <!-- $().cropper(&quot;move&quot;, -10, 0) -->
              <span class="fa fa-arrow-left"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="右移">
            <!-- $().cropper(&quot;move&quot;, 10, 0) -->
              <span class="fa fa-arrow-right"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="上移">
            <!-- $().cropper(&quot;move&quot;, 0, -10) -->
              <span class="fa fa-arrow-up"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="下移">
            <!-- $().cropper(&quot;move&quot;, 0, 10) -->
              <span class="fa fa-arrow-down"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="rotate" data-option="-15" title="Rotate Left">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="左转">
            <!-- $().cropper(&quot;rotate&quot;, -45) -->
              <span class="fa fa-rotate-left"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="rotate" data-option="15" title="Rotate Right">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="右转">
            <!-- $().cropper(&quot;rotate&quot;, 45) -->
              <span class="fa fa-rotate-right"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="左右颠倒">
            <!-- $().cropper(&quot;scaleX&quot;, -1) -->
              <span class="fa fa-arrows-h"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="上下颠倒">
            <!-- $().cropper(&quot;scaleY&quot;, -1) -->
              <span class="fa fa-arrows-v"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="crop" title="Crop">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="显示剪切框">
            <!-- $().cropper(&quot;crop&quot;) -->
              <span class="fa fa-check"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="clear" title="Clear">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="隐藏剪切框">
            <!-- $().cropper(&quot;clear&quot;) -->
              <span class="fa fa-remove"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="disable" title="Disable">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="锁定图片">
            <!-- $().cropper(&quot;disable&quot;) -->
              <span class="fa fa-lock"></span>
            </span>
          </button>
          <button type="button" class="btn btn-primary" data-method="enable" title="Enable">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="解锁图片">
            <!-- $().cropper(&quot;enable&quot;) -->
              <span class="fa fa-unlock"></span>
            </span>
          </button>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="刷新">
            <!-- $().cropper(&quot;reset&quot;) -->
              <span class="fa fa-refresh"></span>
            </span>
          </button>
          <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
            <input type="file" class="sr-only" id="inputImage" name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="选择本地图片">
              <span class="fa fa-upload"></span>
            </span>
          </label>
          <!-- <button type="button" class="btn btn-primary" data-method="destroy" title="Destroy">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;destroy&quot;)">
              <span class="fa fa-power-off"></span>
            </span>
          </button> -->
        </div>

        <div class="btn-group btn-group-crop">
          <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096 }">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="原图">保存原图
            <!-- $().cropper(&quot;getCroppedCanvas&quot;, { maxWidth: 4096, maxHeight: 4096 }) -->
              <!-- Get Cropped Canvas -->
            </span>
          </button>
          <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 160, &quot;height&quot;: 90 }">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="160:90">
            <!-- $().cropper(&quot;getCroppedCanvas&quot;, { width: 160, height: 90 }) -->
              保存为160&times;90
            </span>
          </button>
          <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 320, &quot;height&quot;: 180 }">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="320:180">
            <!-- $().cropper(&quot;getCroppedCanvas&quot;, { width: 320, height: 180 }) -->
              保存为320&times;180
            </span>
          </button>
          <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 640, &quot;height&quot;: 360 }">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="640:320">
            <!-- $().cropper(&quot;getCroppedCanvas&quot;, { width: 640, height: 320 }) -->
              保存为640&times;320
            </span>
          </button>
        </div>

        <!-- Show the cropped image in modal -->
        <div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="getCroppedCanvasTitle">保存图片</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body"></div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <a class="btn btn-primary" id="download" href="javascript:void(0);" download="tmp.jpg">下载</a>
                <a class="btn btn-primary" id="download" href="javascript:void(0);" download="tmp.jpg" onclick="test(this);">保存</a>
              </div>
            </div>
          </div>
        </div><!-- /.modal -->

 <!--        <button type="button" class="btn btn-secondary" data-method="getData" data-option data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getData&quot;)">
            Get Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="setData" data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;setData&quot;, data)">
            Set Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="getContainerData" data-option data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getContainerData&quot;)">
            Get Container Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="getImageData" data-option data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getImageData&quot;)">
            Get Image Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="getCanvasData" data-option data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getCanvasData&quot;)">
            Get Canvas Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="setCanvasData" data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;setCanvasData&quot;, data)">
            Set Canvas Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="getCropBoxData" data-option data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;getCropBoxData&quot;)">
            Get Crop Box Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="setCropBoxData" data-target="#putData">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;setCropBoxData&quot;, data)">
            Set Crop Box Data
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="moveTo" data-option="0">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="cropper.moveTo(0)">
            Move to [0,0]
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="zoomTo" data-option="1">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="cropper.zoomTo(1)">
            Zoom to 100%
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="rotateTo" data-option="180">
          <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="cropper.rotateTo(180)">
            Rotate 180°
          </span>
        </button>
        <button type="button" class="btn btn-secondary" data-method="scale" data-option="-2" data-second-option="-1">
          <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scale(-2, -1)">
            Scale (-2, -1)
          </span>
        </button>
        <textarea type="text" class="form-control" id="putData" rows="1" placeholder="Get data to here or set data with this value"></textarea> -->

      </div><!-- /.docs-buttons -->

      <div class="col-md-3 docs-toggles">
        <h5>编辑框大小:</h5>
        <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">
          <label class="btn btn-primary active">
            <input type="radio" class="sr-only" id="aspectRatio0" name="aspectRatio" value="1.7777777777777777">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="aspectRatio: 16 / 9">
              16:9
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.3333333333333333">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="aspectRatio: 4 / 3">
              4:3
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="aspectRatio: 1 / 1">
              1:1
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="0.6666666666666666">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="aspectRatio: 2 / 3">
              2:3
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="NaN">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="aspectRatio: 自由">自由
            </span>
          </label>
        </div>

        <!-- <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">
          <label class="btn btn-primary active">
            <input type="radio" class="sr-only" id="viewMode0" name="viewMode" value="0" checked>
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="View Mode 0">
              VM0
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="viewMode1" name="viewMode" value="1">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="View Mode 1">
              VM1
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="viewMode2" name="viewMode" value="2">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="View Mode 2">
              VM2
            </span>
          </label>
          <label class="btn btn-primary">
            <input type="radio" class="sr-only" id="viewMode3" name="viewMode" value="3">
            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="View Mode 3">
              VM3
            </span>
          </label>
        </div> -->

        <!-- <div class="dropdown dropup docs-options">
          <button type="button" class="btn btn-primary btn-block dropdown-toggle" id="toggleOptions" data-toggle="dropdown" aria-expanded="true">
            Toggle Options
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" aria-labelledby="toggleOptions" role="menu">
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="responsive" type="checkbox" name="responsive" checked>
                <label class="form-check-label" for="responsive">responsive</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="restore" type="checkbox" name="restore" checked>
                <label class="form-check-label" for="restore">restore</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="checkCrossOrigin" type="checkbox" name="checkCrossOrigin" checked>
                <label class="form-check-label" for="checkCrossOrigin">checkCrossOrigin</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="checkOrientation" type="checkbox" name="checkOrientation" checked>
                <label class="form-check-label" for="checkOrientation">checkOrientation</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="modal" type="checkbox" name="modal" checked>
                <label class="form-check-label" for="modal">modal</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="guides" type="checkbox" name="guides" checked>
                <label class="form-check-label" for="guides">guides</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="center" type="checkbox" name="center" checked>
                <label class="form-check-label" for="center">center</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="highlight" type="checkbox" name="highlight" checked>
                <label class="form-check-label" for="highlight">highlight</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="background" type="checkbox" name="background" checked>
                <label class="form-check-label" for="background">background</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="autoCrop" type="checkbox" name="autoCrop" checked>
                <label class="form-check-label" for="autoCrop">autoCrop</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="movable" type="checkbox" name="movable" checked>
                <label class="form-check-label" for="movable">movable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="rotatable" type="checkbox" name="rotatable" checked>
                <label class="form-check-label" for="rotatable">rotatable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="scalable" type="checkbox" name="scalable" checked>
                <label class="form-check-label" for="scalable">scalable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="zoomable" type="checkbox" name="zoomable" checked>
                <label class="form-check-label" for="zoomable">zoomable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="zoomOnTouch" type="checkbox" name="zoomOnTouch" checked>
                <label class="form-check-label" for="zoomOnTouch">zoomOnTouch</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="zoomOnWheel" type="checkbox" name="zoomOnWheel" checked>
                <label class="form-check-label" for="zoomOnWheel">zoomOnWheel</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="cropBoxMovable" type="checkbox" name="cropBoxMovable" checked>
                <label class="form-check-label" for="cropBoxMovable">cropBoxMovable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="cropBoxResizable" type="checkbox" name="cropBoxResizable" checked>
                <label class="form-check-label" for="cropBoxResizable">cropBoxResizable</label>
              </div>
            </li>
            <li class="dropdown-item">
              <div class="form-check">
                <input class="form-check-input" id="toggleDragModeOnDblclick" type="checkbox" name="toggleDragModeOnDblclick" checked>
                <label class="form-check-label" for="toggleDragModeOnDblclick">toggleDragModeOnDblclick</label>
              </div>
            </li>
          </ul>
        </div> --><!-- /.dropdown -->

        <!-- <a class="btn btn-success btn-block" data-toggle="tooltip" data-animation="false" href="https://fengyuanchen.github.io/cropperjs" title="JavaScript image cropper">Cropper.js</a> -->

      </div><!-- /.docs-toggles -->
    </div>
  </div>

  <!-- Footer -->
  <!-- <footer class="footer">
    <div class="container">
      <p class="heart"></p>
      <nav class="nav flex-wrap justify-content-center mb-3">
        <a class="nav-link" href="https://github.com/fengyuanchen/jquery-cropper">GitHub</a>
        <a class="nav-link" href="https://github.com/fengyuanchen/jquery-cropper/blob/master/LICENSE">License</a>
        <a class="nav-link" href="https://chenfengyuan.com">About</a>
      </nav>
    </div>
  </footer> -->

  <!-- Scripts -->
  <script src="/assets/lib/jquery-cropper-master/docs/js/jquery-3.3.1.min.js"></script>
  <script src="/assets/lib/jquery-cropper-master/docs/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="js/common.js"></script> -->
  <script src="/assets/lib/jquery-cropper-master/docs/js/cropper.js"></script>
  <script src="/assets/lib/jquery-cropper-master/docs/js/jquery-cropper.js"></script>
  <script src="/assets/lib/jquery-cropper-master/docs/js/main.js"></script>
  <!-- <script type="text/javascript" src="/assets/lib/jquery-easyui/jquery.min.js"></script> -->

  <script type="text/javascript">
    // var pre_order_id = '<?//= $_GET['pre_order_id']?>';
    // var type = '<?//= $_GET['type']?>';
    // var key = '<?//= $_GET['key']?>';
    var file_name = '<?= $file_name?>';
    var dir = '<?= $dir?>';

    function test(o){
      var fileImg = $(o).prev().attr('href');
      // submitForm(fileImg);
      $("#registerForm").attr("enctype","multipart/form-data");
      // console.log(fileImg);
      var formData = new FormData($("#registerForm")[0]);
      formData.append("imgBase64",encodeURIComponent(fileImg));
      formData.append("fileFileName","photo.jpg");

      // formData.append("file_name",file_name);
      submitForm(fileImg);
    }

    function submitForm(fileImg){
      $("#registerForm").attr("enctype","multipart/form-data");
      var formData = new FormData($("#registerForm")[0]);
      formData.append("img_base64",encodeURIComponent(fileImg));
      formData.append("file_name",file_name);
      formData.append("dir",dir);
      // formData.append("pre_order_id",pre_order_id);
      // formData.append("type",type);
      // formData.append("key",key);

      $.ajax({  
        url: "/fms/index.php/PreOrder/edit_jpg",
        dataType:'json', 
        type: 'POST',  
        data: formData,  
        timeout : 10000, //超时时间设置，单位毫秒
        async: true,  
        cache: false,  
        contentType: false,  
        processData: false, 
        success: function (result) { 
          // console.log(result);
          if(result.ret){
            localStorage.setItem('resrc', 1);
            window.close();
          }else{
            alert(result.info);
          }
        },  
        error: function (returndata) {

        }
      });
    }
  </script>
</body>
</html>
