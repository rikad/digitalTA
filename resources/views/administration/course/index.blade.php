@extends('layouts.app')
@section('content')
<div class="container">



  <!--Modal Bulk-->
  <div class="modal fade" id="equiModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Register Equivalencies</h4>
        </div>
        <div class="modal-body">
        	<form method="POST" action="/tu/courses/registerEquivalencies"> {{ csrf_field() }}
        	Copy from excel<br>
        	<textarea rows=10 class="form-control" name=data></textarea><br>
        	<div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Save"/></div></form>
          <p></p>
        </div>
      </div>
      
    </div>
  </div>
  <!---->


  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
      	<form method="POST" action="/tu/courses"> {{ csrf_field() }}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Courses (Peringatan: REX wajib diisi dengan R / E / X)</h4>
        </div>
        <div class="modal-body">
			<input type="hidden" value="" name="id">
        	<div class="row">
    			<div class="col-md-6 column" id="sidebar" style="border-right:1px dashed #333;">
    				<div class="form-group">
    					<label for="name">Kurikulum:</label>
    					<input type="text" class="form-control" id="kurikulum" name="kurikulum" required>
  					</div>
  					<div class="form-group">
    					<label for="name">Kode MK:</label>
    					<input type="text" class="form-control" id="code" name="code" required>
  					</div>
  					<div class="form-group">
  						<label for="comment">Nama Mata Kuliah:</label>
  						<input type="text" class="form-control" id="title" name="title">
					</div> 
					<div class="form-group">
  						<label for="comment">Nama Mata Kuliah (Inggris):</label>
  						<input type="text" class="form-control" id="title_en" name="title_en">
					</div> 
					<div class="form-group">
  						<label for="comment">Semester (1-8):</label>
  						<input type="text" class="form-control" id="semester" name="semester" required>
					</div> 
    			</div>
    			<div class="col-md-6 column" id="sidebar">
    				<div class="form-group">
    					<label for="name">SKS:</label>
    					<input type="text" class="form-control" id="sch" name="sch" required>
  					</div>
  					<div class="form-group">
    					<label for="name">REX:</label>
    					<input type="text" class="form-control" id="rex" name="rex" required>
  					</div>
  					<div class="form-group">
    					<label for="name">MBS:</label>
    					<input type="text" class="form-control" id="mbs" name="mbs" required>
  					</div>
  					<div class="form-group">
    					<label for="name">ET:</label>
    					<input type="text" class="form-control" id="et" name="et" required>
  					</div>
  					<div class="form-group">
    					<label for="name">GE:</label>
    					<input type="text" class="form-control" id="ge" name="ge" required>
  					</div>
    			</div>
			</div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Dashboard</a></li>
				<li class="active">Courses Management</li>
			</ul>

			<div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>TIPS:</b> Pada halaman ini, tata usaha dapat mendaftarkan perusahaan tempat kerja praktek
                </div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Courses Management</h2>
				</div>
				<div class="panel-body">
 				    <div align="right">
                                        <a href="/tu/courses/lookup"><button class="btn btn-primary btn-sm">Lookup</button></a>
                                        <button id="editBtn" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#equiModal">Register Equivalencies</button>
                                    </div><br>
					{!! $html->table(['class'=>'table-striped']) !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap.min.js"></script>
<script src="/js/selectize.min.js"></script>

{!! $html->scripts() !!}

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
			name: {title:'Nama Perusahaan',type:'text'},
			bidang: {title:'Bidang',type:'text'},
			address: {title:'Alamat',type:'text'},
			site: {title:'Website',type:'text'},
			phone1: {title:'Phone 1',type:'text'},
			phone2: {title:'Phone 2',type:'text'},
			mail1: {title:'Email 1',type:'text'},
			mail2: {title:'Email 2',type:'text'},
			description: {title:'Description',type:'text'}
		};

		this.removeBtn = function (id) {
			return '<button class="btn btn-danger btn-xs" onclick="rikad.deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></button>';
		}

		this.getDosen = function() {
			var here = this;
	        $.ajax({
	            url: '/dosen/topics/dosen',
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
			
		}

		/*this.add = function () {
			$('input[name=id]').val('');
			$('input[name=name]').val('');
			$('input[name=bidang]').val('');
			$('textarea[name=address]').val('');
			$('textarea[name=description]').val('');
			$('input[name=site]').val('');
			$('input[name=phone1]').val('');
			$('input[name=phone2]').val('');
			$('input[name=mail1]').val('');
			$('input[name=mail2]').val('');
			$('#myModal').modal();
		}*/
		this.edit = function(row,id) {
			var row = row.parentNode.parentNode.cells;

			$.ajax({
	            url: '/tu/courses/'+id,
	            type: 'GET',
	            data: { '_token': window.Laravel.csrfToken },
	            dataType: 'json',
	            success: function(result) {
	            	$('input[name=id]').val(result['id']);
	            	$('input[name=code]').val(result['code']);
	            	$('input[name=title]').val(result['title']);
	            	$('input[name=title_en]').val(result['title_en']);
	            	$('input[name=semester]').val(result['semester']);
	            	$('input[name=sch]').val(result['sch']);
	            	$('input[name=mbs]').val(result['mbs']);
	            	$('input[name=rex]').val(result['rex']);
	            	$('input[name=ge]').val(result['ge']);
	            	$('input[name=et]').val(result['et']);
	            	$('input[name=kurikulum]').val(result['curriculum']);
	            }
	    	});

			$('#myModal').modal();
		}

		this.delete = function(id) {
	        $.ajax({
	            url: '/tu/kpcorps/'+id,
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

	}

	var rikad = new rikad();
  </script>

@endsection

@section('css')
<link href="/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/css/selectize.css" rel="stylesheet">
@endsection