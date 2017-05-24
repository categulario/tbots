#!/bin/bash
curl \
    -X POST \
    -s \
    --data-binary @resources/data/eqx_inline_query.json \
    -H 'Content-Type: application/json' \
    http://tbots.categulario.dev/eqxtoken | jq
    # http://tbots.categulario.dev/eqxtoken > err.html && exo-open err.html
