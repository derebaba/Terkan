<div class="form-group{{ $errors->has('name') ? ' is-invalid' : ''}}">
	{!! Form::label('name', 'Name: ', ['class' => 'col-md-4 control-label']) !!}
	<div class="col-md-6">
		{!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
		{!! $errors->first('name', '<p class="form-text">:message</p>') !!}
	</div>
</div>
<div class="form-group{{ $errors->has('email') ? ' is-invalid' : ''}}">
    {!! Form::label('email', 'Email: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('email', '<p class="form-text">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('roles') ? ' is-invalid' : ''}}">
	{!! Form::label('role', 'Role: ', ['class' => 'col-md-4 control-label']) !!}
	<div class="col-md-6">
		{!! Form::select('roles[]', $roles, isset($user_roles) ? $user_roles : [], ['class' => 'form-control', 'multiple' => true]) !!}
	</div>
</div>
<div class="form-group{{ $errors->has('pic') ? ' is-invalid' : ''}}">
	{!! Form::label('pic', 'pic: ', ['class' => 'col-md-4 control-label']) !!}
	<div class="col-md-6">
			{!! Form::text('pic', null, ['class' => 'form-control']) !!}
			{!! $errors->first('pic', '<p class="form-text">:message</p>') !!}
	</div>
</div>
<div class="form-group{{ $errors->has('verified') ? ' is-invalid' : ''}}">
	{!! Form::label('verified', 'Verified (0 or 1): ', ['class' => 'col-md-4 control-label']) !!}
	<div class="col-md-6">
		{!! Form::number('verified', null, ['class' => 'form-control', 'required' => 'required']) !!}
		{!! $errors->first('verified', '<p class="form-text">:message</p>') !!}
	</div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
