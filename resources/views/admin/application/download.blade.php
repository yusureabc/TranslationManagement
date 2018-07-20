<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理后台</title>
  <link href="{{asset('vendors/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendors/animate/animate.css')}}" rel="stylesheet">
  <link href="{{asset('admin/css/style.css')}}" rel="stylesheet">
</head>

<body class="">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="ibox-content">
                <a class="btn btn-success" target="_blank" href="{{url( route( 'application.downloadFile', ['id' => $id, 'method' => 'xml'] ) )}}" role="button"> Android XML </a>
                <a class="btn btn-warning" target="_blank" href="{{url( route( 'application.downloadFile', ['id' => $id, 'method' => 'iOS_strings'] ) )}}" role="button"> iOS strings </a>
                <a class="btn btn-info" target="_blank" href="{{url( route( 'application.downloadFile', ['id' => $id, 'method' => 'iOS_js'] ) )}}" role="button"> iOS js </a>
            </div>
        </div>
    </div>
</body>

</html>