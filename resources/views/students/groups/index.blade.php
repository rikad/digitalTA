@extends('layouts.app')

@section('content')
<div class="container">
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Konfirmasi</h4>
        </div>
        <div class="modal-body">
          <p></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


  <div class="row">
    <div class="col-md-12">

      <ul class="breadcrumb">
        <li><span class="glyphicon glyphicon-home"></span> &nbsp;<a href="{{ url('/home') }}">Dashboard</a></li>
        <li>Kelompok Tugas Akhir</li>
      </ul>

      <!-- Panel groups-->
      <div class="panel panel-primary">
        <div class="panel-heading"><h2 class="panel-title"><span class="glyphicon glyphicon-user"></span> <span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;&nbsp;Pilih Kelompok Tugas Akhir</h2></div>
        <!-- Content -->
        <div class="panel-body">
          @if(method_exists($html,'table'))
            <div align="right"><button class="btn btn-primary btn-sm" onclick="showModal('','POST')">TA Tanpa Teman</button></div>
            <hr>

            {!! $html->table(['class'=>'table-striped']) !!}
          @else
            <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @if(isset($html['konfirmasi']) && $html['konfirmasi'] == true)
                <b>TIPS:</b> Setelah di konfirmasi, anda tidak akan bisa merubah teman TA lagi
                @else
                <b>TIPS:</b> Tunggu Konfirmasi dari teman anda atau tekan batalkan untuk memilih teman lain
                @endif
            </div>

            <table class="table table-bordered">
              <tbody>
                  @if(isset($html['konfirmasi']) && $html['konfirmasi'] == true)

                  @foreach ($html['group'] as $group)
                  <tr>
                  <td><b>{{ $group->teman_name }}</b> Meminta anda untuk menjadi teman TA </td>
                  <td><button class="btn btn-info btn-sm" onclick="konfirmasiGroup({{ $group->group_id }})">Konfirmasi</button> <button class="btn btn-danger btn-sm" onclick="deleteGroup({{ $group->group_id }})"><span class="glyphicon glyphicon-remove"></span> Batalkan</button></td>
                  @endforeach

                  </tr>
                  @else

                  <td>Menunggu Konfirmasi Dari <b>{{ $html['group'][0]->teman_name }}</b></td>
                  <td><button class="btn btn-danger btn-sm" onclick="deleteGroup({{ $html['group'][0]->group_id }})"><span class="glyphicon glyphicon-remove"></span> Batalkan</button></td>

                  </tr>
                  @endif
              </tbody>
            </table>
          @endif
        </div>
        <!-- End Content -->

      </div>
      <!-- End panel groups -->

    </div>
  </div>
</div>
@endsection

@section('css')
<link href="/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/css/selectize.css" rel="stylesheet">

<style type="text/css">
  .menu-icon span {
    font-size: 50px
  }
  .panel-body {
    text-align: center;
  }

</style>
@endsection


@section('scripts')
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap.min.js"></script>
<script src="/js/selectize.min.js"></script>

@if(method_exists($html,'scripts'))
{!! $html->scripts() !!}
@endisset

<script>
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

  function showModal(id,action) {
    $('#myModal').modal();

    var form = '<form method="'+action+'" action="/student/groups"> {{ csrf_field() }} ';
    form += '<input type="hidden" value="'+id+'" name="id"><p>';

    if (action == 'POST') {
      form += id == '' ? 'Konfirmasi TA Tanpa Teman, Tekan Konfirmasi.' : 'Konfirmasi Penambahan Teman TA, Tekan Konfirmasi.';
    } else {
      form += 'Konfirmasi Pembatalan Teman TA, Tekan Konfirmasi.';
    }

    form += '</p><div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Konfirmasi"/></div></form>';

    var content = $('#myModal').find('p')
    content[0].innerHTML = form;

  }

  function deleteGroup(id) {
      $.ajax({
          url: '/student/groups/'+id,
          type: 'DELETE',
          data: { '_token': window.Laravel.csrfToken },
          dataType: 'json',
          error: function() {
            location.reload(); 
          },
          success: function() {
            location.reload(); 
          }
    });
  }

  function konfirmasiGroup(id) {
      $.ajax({
          url: '/student/groups/'+id,
          type: 'PUT',
          data: { '_token': window.Laravel.csrfToken },
          dataType: 'json',
          error: function() {
            location.reload(); 
          },
          success: function() {
            location.reload(); 
          }
    });
  }
</script>
@endsection
