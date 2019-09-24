@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">

      <ul class="breadcrumb">
        <li><span class="glyphicon glyphicon-home"></span> <a href="{{ url('/home') }}">Dashboard</a></li>
      </ul>

      <!-- Panel groups-->
      <div class="panel panel-primary">
        <div class="panel-heading"><h2 class="panel-title"><span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;&nbsp;Status</h2></div>
        <!-- Content -->
        <div class="panel-body">

          <table class="table table-bordered" style="text-align:left">
            <tbody>
              <tr>
                <td>Teman Kelompok</td>
                <td>@isset($teman->name){{$teman->no_induk}}-{{$teman->name}}@else - @endisset</td>
              </tr>
              @isset($topic)
              <tr>
                <td>Topik</td>
                <td>@isset($topic->title){{$topic->title}}@endisset</td>
              </tr>
              <tr>
                <td>Pembimbing Pertama</td>
                <td>@isset($topic->dosen1){{$topic->dosen1}}@endisset</td>
              </tr>
              <tr>
                <td>Pembimbing Kedua</td>
                <td>@isset($topic->dosen2){{$topic->dosen2}} &nbsp;&nbsp; <button data-toggle="modal" data-target="#dosenModal" class="btn btn-warning btn-sm">Ubah Dosen</button> @else <button data-toggle="modal" data-target="#dosenModal" class="btn btn-warning btn-sm">Pilih Dosen</button> @endisset</td>
              </tr>
              <tr>
                <td>Bobot</td>
                <td id="bobot"></td>
              </tr>
              <tr>
                <td>Waktu</td>
                <td id="waktu"></td>
              </tr>
              <tr>
                <td>Dana</td>
                <td id="dana"></td>
              </tr>
              <tr>
                <td>Description</td>
                <td>@isset($topic->description){{$topic->description}}@endisset</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>@if($topic->is_taken == 1) Di Setujui @else Menunggu Persetujuan Dosen @endisset</td>
              </tr>
              @endisset

            </tbody>
          </table>

          <div align="right">
            @isset($group_id)
              <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal">Batalkan Kelompok</button>
            @endisset</td>
          </div>

        </div>
        <!-- End Content -->

      </div>
      <!-- End panel groups -->

      <!-- Panel -->
      <div class="panel panel-primary">
          <div class="panel-heading"><h2 class="panel-title"><span class="glyphicon glyphicon-th-large"></span>&nbsp;&nbsp;&nbsp; Menu</h2></div>
          <!-- Content -->
          <div class="panel-body panel-big-icon">
  
          <!-- Row 1 -->
          <div class="row">
  
            <div class="col-md-3">
              <div class="panel panel-default" url="/student/groups">
                <div class="panel-heading">Kelompok Tugas Akhir</div>
                <div class="panel-body">
                  <span class="glyphicon glyphicon-user"></span>
                  <span class="glyphicon glyphicon-user"></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="panel panel-default" url="/student/topics">
                <div class="panel-heading">Topik Tugas Akhir</div>
                <div class="panel-body">
                  <span class="glyphicon glyphicon-file"></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="panel panel-default" url="/student/bimbinganTA">
                <div class="panel-heading">Bimbingan Tugas Akhir</div>
                <div class="panel-body">
                  <span class="glyphicon glyphicon-book"></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="panel panel-default" url="/student/proposals">
                <div class="panel-heading">Proposal Tugas Akhir</div>
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

      <!-- Panel -->
      <div class="panel panel-primary">
          <div class="panel-heading"><h2 class="panel-title"><span class="glyphicon glyphicon-th-large"></span>&nbsp;&nbsp;&nbsp; Lainnya</h2></div>
          <!-- Content -->
          <div class="panel-body panel-big-icon">
  
          <!-- Row 1 -->
          <div class="row">
  
            <div class="col-md-3">
              <div class="panel panel-default" url="/ekivalensi">
                <div class="panel-heading">Ekuivalensi</div>
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

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Konfirmasi Hapus Grup</h4>
        </div>
        <div class="modal-body">
          <p>Setelah Di hapus, Semua data terkait Grup akan di hapus permanen, artinya anda akan kembali memilih kelompok, topik, dsb</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteGroup(@isset($group_id){{ $group_id }}@endisset)">Konfirmasi</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  <!-- End Modal -->

  <!-- Modal -->
  <div class="modal fade" id="dosenModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Pilih Pembimbing kedua</h4>
        </div>
        <div class="modal-body">
          <p>
              {!! Form::select('user_id', App\User::join('role_user','role_user.user_id','users.id')->where('role_user.role_id',3)->orderBy('users.name')->pluck('users.name','users.id'), null, ['id'=>'user_id','class' => 'form-control', 'placeholder'=>'Pilih Dosen' ] ) !!}
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="selectDosen(@isset($topic->id){{$topic->id}}@endisset)">Konfirmasi</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  <!-- End Modal -->


    </div>
  </div>
</div>
@endsection

@section('css')
<style type="text/css">
  .panel-big-icon span {
    font-size: 100px
  }
  .panel-body {
    text-align: center;
  }

</style>
@endsection


@section('scripts')
<script type="text/javascript">

  function genStar(n) {
    var out = '';
    for (var i = 0; i < 5; i++) {
      if (i < n) {
        out += '<span class="glyphicon glyphicon-star"></span>';
      } else {
        out += '<span class="glyphicon glyphicon-star-empty"></span>';  
      }
    }

    return out;
  }

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

  function selectDosen(id) {

    idDosen = document.getElementById('user_id').value;
    $.ajax({
        url: '{{ url("/student/topics") }}',
        type: 'POST',
        data: { '_token': window.Laravel.csrfToken, 'id': id,'dosen2_id': idDosen },
        dataType: 'json',
        error: function() {
          location.reload(); 
        },
        success: function() {
          location.reload(); 
        }
    });
  }

  @isset($topic->bobot)
    document.getElementById('bobot').innerHTML = genStar({{ $topic->bobot }});
  @endisset
  @isset($topic->waktu)
    document.getElementById('waktu').innerHTML = genStar({{ $topic->waktu }});
  @endisset
  @isset($topic->dana)
    document.getElementById('dana').innerHTML = genStar({{ $topic->dana }});
  @endisset

</script>
@endsection
