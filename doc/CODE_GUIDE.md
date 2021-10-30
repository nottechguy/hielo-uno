# Guía de código

En este documento está señalado como debe de ser la estructura del código de este proyecto.

## Indentación

Se deben aplicar 4 niveles de indentación usando espacios y no tabs.

## Variables y atributos
Los nombres de variables deben ser entendibles y deben mostrar su propósito.

Ejemplo:
```php
function suma($numero1, $numero2) {
    $resultado = $numero1 + $numero2;
}
```

## Nombres de clase

Las clases y los objetos deben tener nombres o frases nominales como Cliente,
Cuenta, etc.
El nombre de una clase no debe ser un verbo.

Ejemplo:

```php
class Cliente {}
```

## Nombre de métodos
Los métodos deben tener nombres de verbos o frases verbales como por ejemplo: eliminarRegistro, agregarUsuario o registrar, etc.
Los descriptores de acceso deben nombrarse por su valor y tener el prefijo get, set, y is.

Ejemplo:

```php
class Cliente {
    public function registrar() {
        // ...
    }
}
```

## CSS

Los nombres de clases en los elementos HTML deben de contener 4 caracteres, deben de incluir letras mayúsculas, minúsculas y números.

Lo anterior es necesario para evitar usar clases repetidas que puedan afectar el estilo de los elementos HTML.

Ejemplo:

*HTML*

```html
<div class="Y8dV">
    <button class="Iw20" type="button">
</div>
```

*CSS*

```css
.Y8dv {}
.Iw20 {}
```