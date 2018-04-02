<div class="form-group{{ $errors->has('name') ? ' is-invalid' : ''}}">
    {!! Form::label('name', 'Name: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('name', '<p class="form-text">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('display_name') ? ' is-invalid' : ''}}">
    {!! Form::label('display_name', 'Label: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('display_name', '<p class="form-text">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('display_name') ? ' is-invalid' : ''}}">
    {!! Form::label('display_name', 'Permissions: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('permissions[]', $permissions, isset($role) ? $role->permissions->pluck('name') : [], ['class' => 'form-control', 'multiple' => true]) !!}
        {!! $errors->first('display_name', '<p class="form-text">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
