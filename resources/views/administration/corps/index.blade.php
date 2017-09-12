@extends('layouts.app')
@section('content')
<div class="container">

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
      	<form method="POST" action="/tu/kpcorps"> {{ csrf_field() }}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">KP Corps</h4>
        </div>
        <div class="modal-body">
			<input type="hidden" value="" name="id">
        	<div class="row">
    			<div class="col-md-6 column" id="sidebar" style="border-right:1px dashed #333;">
    				<div class="form-group">
    					<label for="name">Nama Perusahaan:</label>
    					<input type="text" class="form-control" id="name" name="name">
  					</div>
  					<div class="form-group">
    					<label for="name">Bidang:</label>
    					<input type="text" class="form-control" id="bidang" name="bidang">
  					</div>
  					<div class="form-group">
  						<label for="comment">Alamat:</label>
  						<textarea class="form-control" rows="2" id="address" name="address"></textarea>
					</div> 
					<div class="form-group">
  						<label for="comment">Deskripsi Lowongan:</label>
  						<textarea class="form-control" rows="3" id="address" name="description"></textarea>
					</div> 
    			</div>
    			<div class="col-md-6 column" id="sidebar">
    				<div class="form-group">
    					<label for="name">Situs:</label>
    					<input type="text" class="form-control" id="name" name="site">
  					</div>
  					<div class="form-group">
    					<label for="name">Phone1:</label>
    					<input type="text" class="form-control" id="name" name="phone1">
  					</div>
  					<div class="form-group">
    					<label for="name">Phone2:</label>
    					<input type="text" class="form-control" id="name" name="phone2">
  					</div>
  					<div class="form-group">
    					<label for="name">Email 1:</label>
    					<input type="text" class="form-control" id="name" name="mail1">
  					</div>
  					<div class="form-group">
    					<label for="name">Email 2:</label>
    					<input type="text" class="form-control" id="name" name="mail2">
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
				<li class="active">Corps Management</li>
			</ul>

			<div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>TIPS:</b> Pada halaman ini, tata usaha dapat mendaftarkan perusahaan tempat kerja praktek
                </div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Corps Management</h2>
				</div>
				<div class="panel-body">
				<div align="right"><button id="editBtn" class="btn btn-primary btn-sm" onclick="rikad.add(true)">Add</button></div><br>
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

		this.add = function () {
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
		}
		this.edit = function(row,id) {
			var row = row.parentNode.parentNode.cells;

			$.ajax({
	            url: '/tu/kpcorps/'+id,
	            type: 'GET',
	            data: { '_token': window.Laravel.csrfToken },
	            dataType: 'json',
	            success: function(result) {
	            	$('input[name=id]').val(result['id']);
	            	$('input[name=name]').val(result['name']);
	            	$('input[name=bidang]').val(result['bidang']);
	            	$('textarea[name=address]').val(result['address']);
	            	$('textarea[name=description]').val(result['description']);
	            	$('input[name=site]').val(result['site']);
	            	$('input[name=phone1]').val(result['phone1']);
	            	$('input[name=phone2]').val(result['phone2']);
	            	$('input[name=mail1]').val(result['mail1']);
	            	$('input[name=mail2]').val(result['mail2']);
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