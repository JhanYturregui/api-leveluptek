<button
    class="btn btn-primary btn-sm"
    onclick="addProduct({{ json_encode(['id' => $id, 'name' => $name, 'quantity' => 1, 'prices' => $sales_prices, 'price' => 2, 'stock' => $stock]) }})">
    <i class="fas fa-plus"></i>
</button>