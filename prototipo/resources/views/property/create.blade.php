@extends('property.master')

@section('content')
    <div class="container my-3">
        <h3 class="text-muted">Formulario de cadastro</h3>

        <form action="<?= url('/imoveis/store'); ?>" method="post">

            <?= csrf_field(); ?>

            <div class="form-group">
                <label for="title">Título do imóvel</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>

            <div class="form-group">

                <label for="description">Descrição</label>
                <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="rental_price">Valor de locação</label>
                <input type="text" name="rental_price" id="rental_price" class="form-control">
            </div>

            <div class="form-group">
                <label for="sale_price">Valor de locação</label>
                <input type="text" name="sale_price" id="sale_price" class="form-control">
            </div>

            <button class="btn btn-success" type="submit">Cadastrar Imovel</button>
        </form>
    </div>
@endsection
