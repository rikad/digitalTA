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
            <div class="panel panel-default" url="/dosen/topics">
              <div class="panel-heading">Kelola Topik Tugas Akhir</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-file"></span>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="panel panel-default" url="/dosen/bimbinganTA">
              <div class="panel-heading">Bimbingan Tugas Akhir</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-book"></span>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="panel panel-default" url="/dosen/proposals">
              <div class="panel-heading">Proposals Tugas Akhir</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-save-file"></span>
              </div>
            </div>
          </div>

        </div>

        </div>
        <!-- end Row 1 -->

        </div>
        <!-- End Content -->

      </div>
      <!-- End panel -->

      <!-- Panel --><!--
      <div class="panel panel-primary">
        <div class="panel-heading"><h2 class="panel-title"><span class="glyphicon glyphicon-cog"></span> Settings</h2></div>
        
        <div class="panel-body">

        
        <div class="row">

          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/profiles/update">
              <div class="panel-heading">Personal Information</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-user"></span>
              </div>
            </div>
          </div>
          

        </div>
        

        
        </div>-->
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
