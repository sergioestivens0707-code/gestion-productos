<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function index(): View
    {
        $productos = Producto::with('categoria')
            ->orderBy('nombre')
            ->paginate(10);

        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.index', compact(
            'productos',
            'categorias'
        ));
    }

    private function reglas(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:150',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:500',
            ],

            'cantidad' => [
                'required',
                'integer',
                'min:0',
            ],

            'precio_unitario' => [
                'required',
                'numeric',
                'min:0',
            ],

            'categoria_id' => [
                'required',
                'exists:categorias,id',
            ],
        ];
    }

    private function mensajes(): array
    {
        return [
            'categoria_id.required' =>
                'Debe seleccionar una categoría.',

            'categoria_id.exists' =>
                'La categoría seleccionada no es válida.',

            'cantidad.min' =>
                'La cantidad no puede ser negativa.',

            'precio_unitario.min' =>
                'El precio no puede ser negativo.',
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate(
            $this->reglas(),
            $this->mensajes()
        );

        Producto::create($datos);

        return redirect()
            ->route('productos.index')
            ->with('exito', 'Producto creado correctamente.');
    }

    public function update(
        Request $request,
        Producto $producto
    ): RedirectResponse {
        $datos = $request->validate(
            $this->reglas(),
            $this->mensajes()
        );

        $producto->update($datos);

        return redirect()
            ->route('productos.index')
            ->with('exito', 'Producto actualizado correctamente.');
    }

    public function destroy(
        Producto $producto
    ): RedirectResponse {
        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('exito', 'Producto eliminado correctamente.');
    }
}