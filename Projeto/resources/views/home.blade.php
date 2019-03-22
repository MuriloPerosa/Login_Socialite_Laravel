@extends('adminlte::default')

@section('content')
<div class="container-fluid">
    <h3 class="box-title "><i class="text-info fa fa-hand-peace-o"></i>  <b>Bem-Vindo</b></h3>
        <hr />
      <div class="panel panel-default">
        <div class="panel-heading "><h4 class="text-bold"><i class="text-info fa fa-twitter"></i> Faça um Tweet, {{ Auth::user()->name }}!</h4></div>
          <div class="panel-body">
            <form>
              <div class="form-group">
              <textarea name="message" class="form-control" rows="10" cols="30" maxlength="140" placeholder="O que está acontecendo?" style="resize: none;"></textarea>
              <hr/>
              <input type="submit" class="btn btn-primary" value="Tweet It!">
            </form>
        </div>
      </div>

      </div>
@endsection
