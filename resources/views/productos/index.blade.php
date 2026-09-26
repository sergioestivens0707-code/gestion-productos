@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Gestión de Productos</h2>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalCrear"
        >
            + Nuevo Producto
        </button>

    </div>


    {{-- Mensaje de éxito --}}
    @if (session('exito'))
        <div class="alert alert-success">
            {{ session('exito') }}
        </div>
    @endif


    {{-- Mensaje de error --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    {{-- Tabla de productos --}}
    <div class="table-responsive">

        <table class="table table-striped table-bordered align-middle">

            <thead class="table-dark">

                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($productos as $producto)

                    <tr>

                        <td>
                            {{ $producto->id }}
                        </td>

                        <td>
                            {{ $producto->nombre }}
                        </td>

                        <td>
                            {{ $producto->descripcion ?? 'Sin descripción' }}
                        </td>

                        <td>
                            {{ $producto->cantidad }}
                        </td>

                        <td>
                            ${{ number_format($producto->precio_unitario, 2, ',', '.') }}
                        </td>

                        <td>
                            {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                        </td>

                        <td>

                            {{-- Editar --}}
                            <button
                                type="button"
                                class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditar{{ $producto->id }}"
                            >
                                Editar
                            </button>


                            {{-- Eliminar --}}
                            <form
                                action="{{ route('productos.destroy', $producto) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('¿Eliminar este producto?');"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-danger"
                                >
                                    Eliminar
                                </button>

                            </form>

                        </td>

                    </tr>


                    {{-- Modal editar producto --}}
                    <div
                        class="modal fade"
                        id="modalEditar{{ $producto->id }}"
                        tabindex="-1"
                        aria-hidden="true"
                    >

                        <div class="modal-dialog">

                            <form
                                action="{{ route('productos.update', $producto) }}"
                                method="POST"
                                class="modal-content"
                            >

                                @csrf
                                @method('PUT')


                                <div class="modal-header">

                                    <h5 class="modal-title">
                                        Editar Producto
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Cerrar"
                                    ></button>

                                </div>


                                <div class="modal-body">

                                    {{-- Nombre --}}
                                    <div class="mb-3">

                                        <label class="form-label">
                                            Nombre
                                        </label>

                                        <input
                                            type="text"
                                            name="nombre"
                                            class="form-control"
                                            value="{{ $producto->nombre }}"
                                            maxlength="150"
                                            required
                                        >

                                    </div>


                                    {{-- Descripción --}}
                                    <div class="mb-3">

                                        <label class="form-label">
                                            Descripción
                                        </label>

                                        <textarea
                                            name="descripcion"
                                            class="form-control"
                                            maxlength="500"
                                            rows="3"
                                        >{{ $producto->descripcion }}</textarea>

                                    </div>


                                    {{-- Cantidad --}}
                                    <div class="mb-3">

                                        <label class="form-label">
                                            Cantidad
                                        </label>

                                        <input
                                            type="number"
                                            name="cantidad"
                                            class="form-control"
                                            value="{{ $producto->cantidad }}"
                                            min="0"
                                            required
                                        >

                                    </div>


                                    {{-- Precio --}}
                                    <div class="mb-3">

                                        <label class="form-label">
                                            Precio unitario
                                        </label>

                                        <input
                                            type="number"
                                            name="precio_unitario"
                                            class="form-control"
                                            value="{{ $producto->precio_unitario }}"
                                            min="0"
                                            step="0.01"
                                            required
                                        >

                                    </div>


                                    {{-- Categoría --}}
                                    <div class="mb-3">

                                        <label class="form-label">
                                            Categoría
                                        </label>

                                        <select
                                            name="categoria_id"
                                            class="form-select"
                                            required
                                        >

                                            <option value="">
                                                Seleccione una categoría
                                            </option>

                                            @foreach ($categorias as $categoria)

                                                <option
                                                    value="{{ $categoria->id }}"
                                                    {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}
                                                >
                                                    {{ $categoria->nombre }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                </div>


                                <div class="modal-footer">

                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal"
                                    >
                                        Cancelar
                                    </button>

                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Guardar cambios
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center"
                        >
                            No hay productos registrados.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Paginación --}}
    <div class="d-flex justify-content-center mt-4">

        {{ $productos->links() }}

    </div>

</div>


{{-- ========================================= --}}
{{-- MODAL CREAR PRODUCTO --}}
{{-- ========================================= --}}

<div
    class="modal fade"
    id="modalCrear"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <form
            action="{{ route('productos.store') }}"
            method="POST"
            class="modal-content"
        >

            @csrf


            <div class="modal-header">

                <h5 class="modal-title">
                    Nuevo Producto
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>

            </div>


            <div class="modal-body">

                {{-- Nombre --}}
                <div class="mb-3">

                    <label class="form-label">
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        class="form-control @error('nombre') is-invalid @enderror"
                        value="{{ old('nombre') }}"
                        maxlength="150"
                        required
                    >

                    @error('nombre')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Descripción --}}
                <div class="mb-3">

                    <label class="form-label">
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        class="form-control"
                        maxlength="500"
                        rows="3"
                    >{{ old('descripcion') }}</textarea>

                </div>


                {{-- Cantidad --}}
                <div class="mb-3">

                    <label class="form-label">
                        Cantidad
                    </label>

                    <input
                        type="number"
                        name="cantidad"
                        class="form-control @error('cantidad') is-invalid @enderror"
                        value="{{ old('cantidad', 0) }}"
                        min="0"
                        required
                    >

                    @error('cantidad')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Precio --}}
                <div class="mb-3">

                    <label class="form-label">
                        Precio unitario
                    </label>

                    <input
                        type="number"
                        name="precio_unitario"
                        class="form-control @error('precio_unitario') is-invalid @enderror"
                        value="{{ old('precio_unitario') }}"
                        min="0"
                        step="0.01"
                        required
                    >

                    @error('precio_unitario')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Categoría --}}
                <div class="mb-3">

                    <label class="form-label">
                        Categoría
                    </label>

                    <select
                        name="categoria_id"
                        class="form-select @error('categoria_id') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            Seleccione una categoría
                        </option>

                        @foreach ($categorias as $categoria)

                            <option
                                value="{{ $categoria->id }}"
                                {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}
                            >
                                {{ $categoria->nombre }}
                            </option>

                        @endforeach

                    </select>

                    @error('categoria_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar
                </button>

            </div>

        </form>

    </div>

</div>

@endsection