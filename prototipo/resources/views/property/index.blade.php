@extends('property.master')

@section('content')
    <div class="container my-3">
        <h3 class="text-muted">Listagem de Imóveis</h3>

        <?php
        echo "<table class='table table-striped table-hover text-center'>";

        echo "<thead class='bg-primary text-white'>
                <td>Título</td>
                <td>Valor de locação</td>
                <td>Valor de compra</td>
                <td>Ações</td>
              </thead>";

        if (!empty($properties)) {
            foreach ($properties as $property) {
                $linkReadMode = url('/imoveis/' . $property->name);
                $linkEditItem = url('/imoveis/editar/' . $property->name);
                $linkRemoveItem = url('/imoveis/remover/' . $property->name);
                echo "<tr>
                <td>{$property->title}</td>
                <td>" . number_format($property->rental_price, "2", ",", ".") . "</td>
                <td>" . number_format($property->sale_price, "2", ",", ".") . "</td>
                <td>
                    <a href='{$linkReadMode}'>Ver Mais</a> |
                    <a href='$linkEditItem'>Editar</a> |
                    <a href='$linkRemoveItem'>Remover</a> |
                </td>
              </tr>";
            }
            echo "</table>";
        }
        ?>
    </div>
@endsection
