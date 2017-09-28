@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">

      <ul class="breadcrumb">
        <li><a href="{{ url('/home') }}"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>
      </ul>

      <!-- Panel -->
      <div class="panel panel-primary">
        <div class="panel-heading"><h2 class="panel-title"><span class="glyphicon glyphicon-th-large"></span>  Menu</h2></div>
        <!-- Content -->
        <div class="panel-body">

        <!-- Row 1 -->
        <div class="row">

          <div class="col-md-3">
            <div class="panel panel-default" url="/tu/kpcorps">
              <div class="panel-heading">Kelola Tempat Kerja Praktek</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-blackboard"></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/tu/courses">
              <div class="panel-heading">Kelola Mata Kuliah</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-book"></span>
              </div>
            </div>

          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/tu/transcripts">
              <div class="panel-heading">Kelola Transkrip Mahasiswa</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-save-file"></span>
              </div>
            </div>

          </div>

        </div>
        <!-- end Row 1 -->

        </div>
        <!-- End Content -->

      </div>
      <!-- End panel -->

    </div>
  </div>
</div>
@endsection

@section('css')
<style type="text/css">
  .panel-body span {
    font-size: 100px
  }
  .panel-body {
    text-align: center;
  }

</style>
@endsection


@section('scripts')
<script type="text/javascript">
  $(document).ready(function(){
    var panel = $(".panel-body .panel");
    panel.mouseover(function(){
        $(this).removeClass('panel-default');
        $(this).addClass('panel-info');
        $(this).css('cursor','pointer');
    });

    panel.click(function(){
      window.location.href = $(this).attr('url');
    });

    panel.mouseout(function(){
        $(this).removeClass('panel-info');
        $(this).addClass('panel-default');
    });

  });
</script>
@endsection
