<x-filament::page>
    <!-- Contenedor principal de la página -->
    <form wire:submit.prevent="CreateSale">
        <!-- Componente de formulario que se enviará al método Livewire 'createSale' -->

        <!-- Campo de texto para el nombre del producto -->
        <x-filament::input
                type="text"
                label="Nombre del producto"
                wire:model.defer="sale.product_name"
                required
        />

        <!-- Campo numérico para la cantidad del producto -->
        <x-filament::input
                type="number"
                label="Cantidad"
                wire:model.defer="sale.quantity"
                required
        />

        <!-- Campo numérico para el precio del producto -->
        <x-filament::input
                type="number"
                label="Precio"
                wire:model.defer="sale.price"
                required
        />

        <!-- Botón de envío -->
        <x-filament::button type="submit">
            Crear Venta
        </x-filament::button>
    </form>
</x-filament::page>
