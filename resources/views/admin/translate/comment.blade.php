<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="{{asset('vendors/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendors/animate/animate.css')}}" rel="stylesheet">
</head>
<body class="">
    <?php if ( $comments->isNotEmpty() ) { ?>
    <?php foreach ( $comments as $comment ) { ?>
    <div class="media">
        <a class="media-left" href="#">
            <!-- <img class="media-object" src="" alt="媒体对象"> -->
        </a>
        <div class="media-body">
            <h4 class="media-heading"> <?php echo $comment->username; ?> </h4>
            <?php echo $comment->comment; ?>
        </div>
    </div>
    <?php } ?>
    <?php } else { ?>

    <?php } ?>

<form role="form" method="post">
    {{ csrf_field() }}
  <div class="form-group">
    <label for="name"> Comments </label>
    <textarea name="comment" class="form-control" rows="3" required></textarea>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default"> Submit </button>
    </div>
  </div>
</form>

<script src="{{asset('vendors/jquery/jquery-2.1.1.js')}}"></script>
<script src="{{asset('vendors/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/metisMenu/jquery.metisMenu.js')}}"></script>
<script src="{{asset('vendors/slimscroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('admin/js/inspinia.js')}}"></script>
</body>
</html>