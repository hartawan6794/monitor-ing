<center>
	{!! Form::model($model, ['url'=>$form_url, 'method'=>'delete']) !!}
	<div class="btn-group" role="group">
		<a href="{{ $edit_url }}" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
		{!! Form::button('<i class="fas fa-trash-alt"></i>', ['type'=>'submit','class'=>'btn btn-sm btn-danger']) !!}
	</div>
	{!! Form::close() !!}
</center>
