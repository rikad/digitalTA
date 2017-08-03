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
            <div class="panel panel-default" url="/menu/cv">
              <div class="panel-heading">My Curriculum Vitae</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-file"></span>
              </div>
            </div>
          </div>

        </div>
        <!-- end Row 1 -->

        </div>
        <!-- End Content -->

      </div>
      <!-- End panel -->

      <!-- Panel -->
      <div class="panel panel-primary">
        <div class="panel-heading"><h2 class="panel-title"><span class="glyphicon glyphicon-cog"></span> Settings</h2></div>
        <!-- Content -->
        <div class="panel-body">

        <!-- Row 1 -->
        <div class="row">

          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/profiles/update">
              <div class="panel-heading">Personal Information</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-user"></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/educations/update">
              <div class="panel-heading">Educations</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-education"></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/experiences/update">
              <div class="panel-heading">Experiences</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-list-alt"></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/certifications/update">
              <div class="panel-heading">Certifications & Professional Registrations</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-certificate"></span>
              </div>
            </div>
          </div>

        </div>
        <!-- end Row 1 -->

        <!-- Row 2 -->
        <div class="row">

          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/memberships/update">
              <div class="panel-heading">Membership in Professional Organizations</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-briefcase"></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/awards/update">
              <div class="panel-heading">Honors & Awards</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-star"></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/activities/update">
              <div class="panel-heading">Service Activities</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-random"></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/publications/update">
              <div class="panel-heading">Key Publications & Presentations</div>
              <div class="panel-body">
                <span class="glyphicon glyphicon-duplicate"></span>
              </div>
            </div>
          </div>

        </div>
        <!-- end Row 2 -->

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
