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
          <h4 class="modal-title">Topics</h4>
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
		        <li class="active">Pemilihan Topik</li>
			</ul>

      <!-- Panel groups-->
      <div class="panel panel-primary">
        <div class="panel-heading"><h2 class="panel-title"><span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;&nbsp;Status</h2></div>
        <!-- Content -->
        <div class="panel-body">

          <div  class="progress">
            <div id="progress-bar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width:50%; height: 30px"></div>
          </div>

		    @if(isset($topic))
			<div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <b>TIPS:</b> Silahkan menunggu atau menghubungi dosen terkait dalam konfirmasi topik, jika telah di setujui silahkan tunggu proses selanjutnya. jika tidak di setujui silahkan klik tombol batalkan di bawah dan memilih lagi topik lain
            </div>
            @endif


          <table class="table table-bordered">
            <thead>
              <tr class="info">
                <th>Progress</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Membuat Kelompok Tugas Akhir</td>
                <td><span class="glyphicon glyphicon-ok"></span>&nbsp;-&nbsp; 
		          @if(isset($partner->siswa1) && $partner->siswa1 != Auth::user()->name)
		          {{ $partner->siswa1 }} ({{ $partner->siswa1no }})
		          @elseif($partner->siswa2)
		          {{ $partner->siswa2 }} ({{ $partner->siswa2no }})
		          @endif
                </td>
              </tr>
              <tr>
                <td rowspan=4>Memilih Topik Tugas Akhir</td>
              </tr>

              @if(isset($topic))
              @foreach ($topic as $i => $t)
    			<tr>
              	<td>
                  Topik #{{$i+1}} - {{$t->title}} ({{$t->dosen1}})
                  <br>
		        	@if($t->status == 0)
					<button id="progress" status="80" class="btn btn-warning btn-sm disabled">Menunggu Konfirmasi Pembimbing</button>
		        	@elseif($t->status == 1)
					<button id="progress" status="100" class="btn btn-success btn-sm disabled"><span class="glyphicon glyphicon-ok"></span> Telah Di Setujui</button>
		        	@elseif($t->status == 2)
					<button id="progress" status="60" class="btn btn-danger btn-sm disabled"><span class="glyphicon glyphicon-remove"></span> Di Tolak</button>
		        	@endif
		        	<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalTopic{{$i+1}}">Lihat Detail</button>
                </td>
              </tr>	
			  @endforeach
			  @endif

			  @if (!(count($topic) >2 || (isset($topic[0]) && $topic[0]->status==1)))
    		  <tr><td>
			  <button type="button" class="btn btn-info btn-sm">
			  Pilih Topik 
			  @if(count($topic)>0) Lainnya @endif
			  (max. 3)
			  </button>
			  </td></tr>
			  @endif
			  
            </tbody>
          </table>

          @if(isset($topic))
          @foreach ($topic as $i => $t)
          <!-- Modal -->
			<div id="modalTopic{{$i+1}}" class="modal fade" role="dialog">
  			<div class="modal-dialog">

    		<!-- Modal content-->
    		<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal">&times;</button>
        			<h4 class="modal-title">Informasi Topik</h4>
      			</div>
      		<div class="modal-body">
          		<table class="table table-bordered">
            		<thead>
              			<tr class="info">
                			<th colspan="2"> </th>
              			</tr>
            		</thead>
            		<tbody>
              			<tr>
                			<td>Nama Topik</td>
                			<td>{{ $t->title }}</td>
              			</tr>
              			<tr>
                			<td>Deskripsi</td>
                			<td>{{ $t->description }}</td>
              			</tr>
              			<tr>
                			<td>Bobot</td>
                			<td>{{ $t->bobot }}</td>
              			</tr>
              			<tr>
                			<td>Waktu</td>
                			<td>{{ $t->waktu }}</td>
              			</tr>
              			<tr>
                			<td>Dana</td>
                			<td>{{ $t->dana }}</td>
              			</tr>
              			<tr>
                			<td>Pembimbing Pertama</td>
                			<td>{{ $t->dosen1 }} / {{ $t->dosen1no }}</td>
              			</tr>
              			<tr>
                			<td>Pembimbing Kedua</td>
                			<td>{{ $t->dosen2 }} / {{ $t->dosen2no }}</td>
              			</tr>
              			<tr>
                			<td>Mahasiswa Pertama</td>
                			<td>{{ $t->siswa1 }} / {{ $t->siswa1no }}</td>
              			</tr>
              			<tr>
                			<td>Mahasiswa Kedua</td>
                			<td>{{ $t->siswa2 }} / {{ $t->siswa2no }}</td>
              			</tr>
              			<tr>
                			<td>Catatan</td>
                			<td>{{ $t->note }}</td>
              			</tr>
            		</tbody>
          		</table>
          		<hr>
        		@if($t->status != 1)
					<div align="right"><button class="btn btn-danger btn-sm" onclick="rikad.delete({{ $t->relasi }})">Batalkan Pengajuan Topik</button></div>
            	@endif
            </div></div>
			</div></div>
          @endforeach
		  @endif


          <br>

        </div>
        <!-- End Content -->

      </div>
      <!-- End panel groups -->
      		
      		@if(!(count($topic) >2 || (isset($topic[0]) && $topic[0]->status==1)))

      		<!--limit-->
			<div class="panel panel-primary"">
				<div class="panel-heading">
					<h2 class="panel-title">Pemilihan Topik (max. 3)</h2>
				</div>
				<div class="panel-body">


			<div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>TIPS:</b> Pada halaman ini, Anda bisa mengajukan topik baru atau memilih topik yang sudah ada
                </div>

				<div align="right"><button id="editBtn" class="btn btn-primary btn-sm" onclick="rikad.add(true)">Ajukan Topik Baru</button></div><br>
					{!! $html->table(['class'=>'table-striped']) !!}
				</div>
			</div>
            @endif


		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap.min.js"></script>
<script src="/js/selectize.min.js"></script>

@if(count($topic) <3)
{!! $html->scripts() !!}
@endif

  <script>

  	function additional() {
		$(document).ready(function () {

			// add selectize to select element
			$('.js-selectize').selectize({
				create:true,
				sortField: 'text'
			});
		});
	}

	function rikad() {

		this.optionData = {};

		this.inputName = {
			dosen1_id: {title:'Dosen',type:'select'},
			title: {title:'Judul',type:'text'},
			bobot: {title:'Bobot',type:'text'},
			waktu: {title:'Waktu',type:'text'},
			dana: {title:'Dana',type:'text'}
		};

		this.removeBtn = function (id) {
			return '<button class="btn btn-danger btn-xs" onclick="rikad.deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></button>';
		}

		this.getDosen = function() {
			var here = this;
	        $.ajax({
	            url: '/student/topics/dosen',
	            type: 'GET',
	            dataType: 'json',
	            error: function() {
	                alert('error fetch data, please refresh this page again');
	            },
	            success: function(res) {
	            	here.optionData = res;
	            }
	    	});
        }

        this.buildOption = function(type,selected) {
        	var data = this.optionData;
        	var input = this.inputName[type];
        	var output = selected == '' ? '<option selected="selected" value="">Select '+input.title+'</option>' : '<option value="">Select '+input.title+'</option>';
        	for(var result in data) {
        		if (selected == data[result]) {
	        		output += '<option value="'+result+'" selected="selected">'+data[result]+'</option>';
        		}
        		else {
	        		output += '<option value="'+result+'">'+data[result]+'</option>';
        		}
        	}

        	return output;
        }

		this.inputConstruct = function (name,type,value) {
			var output;

			switch (type) {
				case 'email': output =   '<input type="email" class="form-control" name="'+ name +'" value="'+ value +'" />';
				break;
				case 'select':
					output = '<select class="js-selectize" name="'+name+'">'+ this.buildOption(name,value) +'</select>';
				break;
				case 'date': 
					output = '<div class="input-group date" id="date"><input type="text" class="form-control" name="'+ name +'" value="'+ value +'" /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>';
				break;
				default: output = '<input type="text" class="form-control" name="'+ name +'" value="'+ value +'" />';
			}
			return output;
		}

		this.showModal = function (data,id) {
			$('#myModal').modal();
			var form = '<form method="POST" action="/student/topics"> {{ csrf_field() }} ';
			form += '<input type="hidden" value="'+id+'" name="id">';
			var i=0;
			for(var input in this.inputName) {
				form += this.inputName[input].title + '<br>'
				if (this.inputName[input].type == 'password') {
					form += this.inputConstruct(input,this.inputName[input].type,'');
					form += '<br>';
					i++;
					continue;
				}

				form += data == null ? this.inputConstruct(input,this.inputName[input].type,'') : this.inputConstruct(input,this.inputName[input].type,data[i]);
				form += '<br>';

				i++;
			}

			form += '<div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Save"></div></form>';


			var content = $('#myModal').find('p')
			content[0].innerHTML = form;

			//add additional function of date
			additional();
		}

		this.add = function () {
			this.showModal(null,null);
		}
		this.edit = function(row,id) {
			var row = row.parentNode.parentNode.cells;

			data = [];
			for (var i = 0, lt = row.length; i < lt-1; i++) {
				data.push(row[i].innerHTML);
			}

			this.showModal(data,id);
		}

		this.delete = function(id) {
	        $.ajax({
	            url: '/student/topics/'+id,
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

		this.pilihTopik = function(id) {
	        $.ajax({
	            url: '/student/topics/'+id,
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

	}

	var rikad = new rikad();
	rikad.getDosen();


	//change progress bar
	var longValue = document.querySelector("#progress");
	var progressBar = document.querySelector("#progress-bar");

	if (longValue != undefined && progressBar != undefined) {
		longValue = longValue.attributes.status.value+'%';
		progressBar.style.width = longValue;
	}
  </script>

@endsection

@section('css')
<link href="/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/css/selectize.css" rel="stylesheet">
@endsection
