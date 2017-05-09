@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">

      <ul class="breadcrumb">
        <li><a href="{{ url('/home') }}"><span class="glyphicon glyphicon-home"> Dashboard</a></li>
      </ul>

      <!-- Panel -->
      <div class="panel panel-primary">
        <div class="panel-heading"><h2 class="panel-title"><span class="glyphicon glyphicon-th-large"></span>  Menu</h2></div>
        <!-- Content -->
        <div class="panel-body">

        <!-- Row 1 -->
        <div class="row">

          <div class="col-md-3">
            <div class="panel panel-default">
              <div class="panel-heading">Key Publications & Presentations</div>
              <div class="panel-body">
                isi data
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default">
              <div class="panel-heading">Curriculum Vitae</div>
              <div class="panel-body">
                isi data
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
        <div class="progress progress-striped active">
          <div class="progress-bar" style="width: 45%"></div>
        </div>

        <!-- Row 1 -->
        <div class="row">

          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/profiles/update">
              <div class="panel-heading">Personal Information</div>
              <div class="panel-body">
                isi data
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/educations/update">
              <div class="panel-heading">Educations</div>
              <div class="panel-body">
                isi data
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/experiences/update">
              <div class="panel-heading">Experiences</div>
              <div class="panel-body">
                isi data
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/certifications/update">
              <div class="panel-heading">Certifications & Professional Registrations</div>
              <div class="panel-body">
                isi data
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
                isi data
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/awards/update">
              <div class="panel-heading">Honors & Awards</div>
              <div class="panel-body">
                isi data
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default" url="/menu/activities/update">
              <div class="panel-heading">Service Activities</div>
              <div class="panel-body">
                isi data
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default">
              <div class="panel-heading">Professional Development Activities</div>
              <div class="panel-body">
                isi data
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
