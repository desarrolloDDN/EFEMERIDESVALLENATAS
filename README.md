# Tema WordPress — Efemérides Vallenatas

Tema editorial oficial del libro *Efemérides Vallenatas*: libro histórico + archivo musical + Caribe colombiano.
Tema clásico (PHP), sin constructores visuales ni dependencias en el servidor. Requiere WordPress 6.4+ y PHP 8.0+.

## Instalación (5 pasos)

1. **Apariencia → Temas → Añadir nuevo → Subir tema** y sube `efemerides-vallenatas.zip`. Actívalo.
2. Pulsa **Configurar el sitio** en el aviso (o **Apariencia → Configurar Efemérides**). Crea:
   - Páginas: Inicio (portada), El libro, Autor, Prensa, Tienda, Edición física, Edición digital, Contacto, Blog y 5 páginas legales.
   - Menús principal, del pie y legal; categorías de efeméride; URLs amigables.
   - Opcional: 36 efemérides, 6 curiosidades y 7 artículos **de ejemplo** (solo textos entre [corchetes]).
3. **Apariencia → Personalizar → Efemérides Vallenatas**: ficha del libro, portada oficial, autor, tienda, preguntas frecuentes, contacto y redes.
4. **Tienda (WooCommerce):**
   - Instala WooCommerce y vuelve a ejecutar *Configurar el sitio* (deja el formato de precio en pesos: `$99.000`).
   - Crea el producto **Edición física** (simple, con peso y envío) y **Edición digital** (simple, *virtual* y *descargable*, con el archivo PDF/EPUB).
   - Escribe sus **ID** en *Personalizar → Efemérides Vallenatas → Tienda y ediciones*.
   - Pasarela de pago para Colombia: instala el plugin oficial de **Wompi**, **Mercado Pago** o **PayU** (o Stripe) y actívalo en *WooCommerce → Ajustes → Pagos*. El sitio nunca maneja datos de tarjeta.
5. Reemplaza el contenido de ejemplo por el real y bórralo.

## Dónde se edita cada cosa

| Contenido | Dónde |
|---|---|
| Efemérides (fecha, año, lugar, protagonistas, fuente, destacada) | **Efemérides** (menú lateral). El *Extracto* es el resumen de la tarjeta. |
| Curiosidades | **Curiosidades** (con *Temas* e imagen destacada) |
| Blog | **Entradas** |
| Testimonios (solo con autorización) | **Testimonios**: título = nombre, contenido = testimonio |
| Prensa | **Prensa (medios)**: título = medio, imagen destacada = logo |
| Biografía y entrevista del autor | Página **Autor** (las preguntas como *Encabezado H2*) |
| Datos del libro, precios sin WooCommerce, envío, FAQ, redes | **Personalizar → Efemérides Vallenatas** |
| Suscriptores del boletín / mensajes de contacto | **Herramientas → Suscriptores / Mensajes** |

Mientras un dato siga entre [corchetes] se muestra como placeholder y **no** se publica en los datos estructurados (Schema.org).

## Flujo de compra

- **Física:** producto → carrito → datos de envío → pago → confirmación (WooCommerce).
- **Digital:** producto → pago (directo al checkout) → confirmación → descarga (correo y *Mi cuenta → Descargas*).
- Sin WooCommerce o sin productos vinculados, los botones muestran «Muy pronto a la venta» y un aviso (solo visible para administradores) explica qué falta.

## SEO

Títulos, meta descripción, Open Graph, X Cards, Schema.org (`Book`, `Product`, `Article`, `BreadcrumbList`, `WebSite`), migas de pan, URLs amigables (`/efemerides/nombre-del-evento/`, `/blog/nombre-del-articulo/`) y sitemap nativo (`/wp-sitemap.xml`).
Si instalas Yoast, Rank Math, AIOSEO o SEOPress, el tema les cede metaetiquetas y migas, y conserva los datos de *Libro* y *Producto*.

## Desarrollo

Los estilos usan Tailwind CSS 4 compilado a `assets/css/main.css` (ya incluido; el servidor no necesita Node).
Si cambias clases en las plantillas, recompila:

```bash
npm install && npm run build:css
```

Estructura: `inc/` (lógica: opciones, tipos de contenido, componentes, tienda, formularios, SEO, asistente), `template-parts/` (secciones), `page-templates/` (plantillas de página), `assets/` (CSS, JS, imágenes), `data/` (contenido de ejemplo).

## Actualizaciones desde WordPress

El tema se actualiza como cualquier otro: cuando hay una versión nueva en GitHub, WordPress muestra
el aviso en **Escritorio → Actualizaciones** y en **Apariencia → Temas** con el botón **Actualizar ahora**.
WordPress consulta GitHub cada pocas horas; para ver una versión recién publicada al instante, pulsa
**Comprobar de nuevo** en *Escritorio → Actualizaciones*.

Contenidos, productos, ajustes del Personalizador y menús se conservan. No edites archivos del tema
desde WordPress: la siguiente actualización los sobrescribiría.

### Publicar una versión

```bash
bin/release.sh 1.2.0 "Qué cambió en esta versión"
```

El script sube el número de versión, recompila el CSS, crea el commit y la etiqueta `v1.2.0`, y publica
la *release* en GitHub con el archivo `efemerides-vallenatas.zip` adjunto (requiere `gh` con sesión de
la cuenta desarrolloDDN). El repositorio debe ser público para que WordPress pueda consultarlo.
