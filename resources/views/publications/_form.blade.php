<div class="form-group{{ $errors->has('program_id') ? ' has-error' : '' }}">
	{!! Form::label('program_id', 'Study Program', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
	{!! Form::text('program_id', null, ['class'=>'form-control']) !!}
	{!! $errors->first('program_id', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('institution_id') ? ' has-error' : '' }}">
	{!! Form::label('institution_id', 'Institution', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
	{!! Form::text('institution_id', null, ['class'=>'form-control']) !!}
	{!! $errors->first('institution_id', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group {{ $errors->has('country_id') ? 'has-error' : ''}}">
	{!! Form::label('country_id', 'Country', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('country_id', [''=>'']+App\Country::pluck('country','id')->all(), null, ['class' => 'js-selectize', 'placeholder'=>'Select Country'] ) !!}
		{!! $errors->first('country_id', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
	{!! Form::label('start_date', 'Start Date', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
    <div class='input-group date' id='date'>
		{!! Form::text('start_date', null, ['class'=>'form-control']) !!}
	    <span class="input-group-addon">
	        <span class="glyphicon glyphicon-calendar"></span>
	    </span>
	</div>
	{!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
	{!! Form::label('end_date', 'End date', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
    <div class='input-group date' id='date'>
		{!! Form::text('end_date', null, ['class'=>'form-control']) !!}
	    <span class="input-group-addon">
	        <span class="glyphicon glyphicon-calendar"></span>
	    </span>
	</div>
	{!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
	{!! Form::submit('Simpan', ['class'=>'btn btn-primary']) !!}
	</div>
</div>
