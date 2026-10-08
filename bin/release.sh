#!/usr/bin/env bash
# Publica una versión nueva del tema en GitHub para que WordPress la ofrezca como actualización.
# Uso: bin/release.sh 1.2.0 "Descripción breve de los cambios"
set -euo pipefail
cd "$(dirname "$0")/.."

VERSION="${1:?Indica la versión, p. ej. 1.2.0}"
NOTES="${2:-Versión $VERSION}"

# 1. Versión en style.css y functions.php
sed -i '' -E "s/^Version: .*/Version: $VERSION/" style.css
sed -i '' -E "s/define\( 'EV_VERSION', '[^']+' \);/define( 'EV_VERSION', '$VERSION' );/" functions.php

# 2. CSS compilado (por si cambiaron clases en las plantillas)
[ -d node_modules ] || npm install --silent
npm run -s build:css

# 3. Commit, etiqueta y push
git add -A
git commit -m "Versión $VERSION" || true
git tag "v$VERSION"
git push && git push origin "v$VERSION"

# 4. Zip con la carpeta efemerides-vallenatas/ y release con el zip adjunto
ZIP="$(mktemp -d)/efemerides-vallenatas.zip"
git archive --format=zip --prefix=efemerides-vallenatas/ -o "$ZIP" "v$VERSION"
gh release create "v$VERSION" "$ZIP" --title "Efemérides Vallenatas $VERSION" --notes "$NOTES"
echo "Publicada v$VERSION. En WordPress: Escritorio → Actualizaciones → Comprobar de nuevo."
