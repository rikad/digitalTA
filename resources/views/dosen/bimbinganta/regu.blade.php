@extends('layouts.app')

@section('content')
<div class="container">

<div class="modal fade" id="viewBimbingan" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <form method="POST" action="/dosen/bimbinganTA" enctype="multipart/form-data"> {{ csrf_field() }}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Bimbingan <span id=viewDate></span></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" value="" name="id">
          <div class="row">
          <div class="col-md-12 column"><label for="name">Attachment:</label> <span id=viewAttachemnt><a href=#>blabla.doc</a></span>
          </div>
          </div>
          <div class="row">
          <div class="col-md-6 column" id="sidebar" style="border-right:1px dashed #333;">
            <div class="form-group">
              <input type=hidden name=id value="">
              <label for="name">Isi Bimbingan:</label>
              <textarea class="form-control" rows="10" id="address" name="viewBimbingan" readonly></textarea>

            </div> 
          </div>
          <div class="col-md-6 column" id="sidebar">
            <div class="form-group">
              <label for="name">Catatan Pembimbing:</label>
              <textarea class="form-control" rows="10" id="address" name="viewCatatan"></textarea>
            </div> 
          </div>
      </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-sm" name=submit value=save>Simpan</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">

      <ul class="breadcrumb">
        <li><a href="{{ url('/home') }}">Dashboard</a></li>
        <li><a href="{{ url('/dosen/bimbinganTA') }}">Bimbingan Tugas Akhir</a></li>
        <li class="active">Regu</li>
      </ul>

      <!-- Panel -->
      <div class="panel panel-primary">
        <div class="panel-heading"><h2 class="panel-title"><span class="glyphicon glyphicon-th-large"></span>  Bimbingan Tugas Akhir / {{$info['title']}} / {{$info['name']}} & {{$info['name2']}}</h2></div>
        <!-- Content -->
        <div class="panel-body">

        <!-- Row 1 -->
        <div class="row">
          
          @if(count($result) > 0)
          @foreach($result as $record)
          <div class="col-md-3">
            <div class="panel panel-primary" onClick="show({{$record['id']}})">
              <div class="panel-heading">Lihat Isi Bimbingan</div>
              <div class="panel-body">
                <h1>{{$record['date']}}</h1> <h3>{{$record['year']}}</h3>
              </div>
            </div>
          </div>
          @endforeach
          @endif

                
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


@section('css')

@endsection

@section('scripts')
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css" />

<script type="text/javascript" src="/js/moment.min.js"></script>
<script type="text/javascript" src="/js/bootstrap-datetimepicker.min.js"></script>

<script src="http://www.datejs.com/build/date.js" type="text/javascript"></script>

<script type="text/javascript">
  function show(id){
      $.ajax({
              url: '/dosen/bimbinganTA/'+id,
              type: 'GET',
              data: { '_token': window.Laravel.csrfToken },
              dataType: 'json',
              success: function(result) {
                $('input[name=id]').val(result['id']);
                $('textarea[name=viewBimbingan]').val(result['kegiatan']);
                $('textarea[name=viewCatatan]').val(result['note']);
                
                if(result['attachment']==null){
                  document.getElementById("viewAttachemnt").innerHTML = '-';
                }else{
                  document.getElementById("viewAttachemnt").innerHTML = '<a href=/student/bimbinganTA/download?id='+result['id']+'>Bimbingan_'+result['tanggal_bimbingan']+'.'+result['attachment'].split('.').pop()+'</a>';  
                }

                var mydate = new Date(result['tanggal_bimbingan']);
                var str = mydate.toString("dd MMMM yyyy");
                document.getElementById("viewDate").innerHTML = str;
              }
        });

      $('#viewBimbingan').modal();
  }

  $(document).ready(function(){
    $('#datetimepicker1').datetimepicker({ format: 'YYYY-MM-DD'});

    var panel = $(".panel-body .panel");
    panel.mouseover(function(){
        $(this).removeClass('panel-default');
        $(this).addClass('panel-info');
        $(this).css('cursor','pointer');
    });

    panel.mouseout(function(){
        $(this).removeClass('panel-info');
        $(this).addClass('panel-default');
    });

  });
</script>
@endsection
