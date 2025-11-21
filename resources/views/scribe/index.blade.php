<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>GRP API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://itse-app.test";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.5.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.5.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-tipo-edificacion">
                                <a href="#endpoints-GETapi-v1-tipo-edificacion">Retorna un JSON con la lista de tipos de edificación.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-licencias-expediente--lic_expnum-">
                                <a href="#endpoints-GETapi-v1-licencias-expediente--lic_expnum-">Buscar por número de expediente.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-licencias-numero--lic_numlic-">
                                <a href="#endpoints-GETapi-v1-licencias-numero--lic_numlic-">Buscar por número de licencia.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-">
                                <a href="#endpoints-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-">Buscar por número de licencia y número de expediente.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-licencias-id--lic_id-">
                                <a href="#endpoints-GETapi-v1-licencias-id--lic_id-">Buscar por ID de licencia.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-persona-solicitante--per_idsolicitante-">
                                <a href="#endpoints-GETapi-v1-persona-solicitante--per_idsolicitante-">Obtiene la información de un solicitante por su ID.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-itse-certificados-buscar-ubicacion">
                                <a href="#endpoints-GETapi-v1-itse-certificados-buscar-ubicacion">Endpoint para autocompletar ubicaciones.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-">
                                <a href="#endpoints-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-">Genera y muestra el PDF de un certificado.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-PUTapi-v1-itse-certificados-eliminar--certificadoId-">
                                <a href="#endpoints-PUTapi-v1-itse-certificados-eliminar--certificadoId-">Marca un certificado como eliminado (eliminación lógica).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-certificado-lic-func-datos--expnum-">
                                <a href="#endpoints-GETapi-v1-certificado-lic-func-datos--expnum-">GET api/v1/certificado-lic-func/datos/{expnum}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-certificado-lic-func-datos-codcat--codcat-">
                                <a href="#endpoints-GETapi-v1-certificado-lic-func-datos-codcat--codcat-">GET api/v1/certificado-lic-func/datos-codcat/{codcat}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa">
                                <a href="#endpoints-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa">GET api/v1/certificado-lic-func/lista-procedimientos-tupa</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-">
                                <a href="#endpoints-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-">GET api/v1/certificado-lic-func/nivel-riesgo/{expnum}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-certificado-lic-func-datos-completos--expnum-">
                                <a href="#endpoints-GETapi-v1-certificado-lic-func-datos-completos--expnum-">GET api/v1/certificado-lic-func/datos-completos/{expnum}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-tipo-licencia">
                                <a href="#endpoints-GETapi-v1-tipo-licencia">GET api/v1/tipo-licencia</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-giros-buscar--search-">
                                <a href="#endpoints-GETapi-v1-giros-buscar--search-">GET api/v1/giros/buscar/{search}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-giros-listar">
                                <a href="#endpoints-GETapi-v1-giros-listar">GET api/v1/giros/listar</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: November 21, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://itse-app.test</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-v1-tipo-edificacion">Retorna un JSON con la lista de tipos de edificación.</h2>

<p>
</p>

<p>Ideal para poblar selects o catálogos en el frontend. El formato
depende de la implementación del servicio (array simple, key-value, etc.).</p>

<span id="example-requests-GETapi-v1-tipo-edificacion">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/tipo-edificacion" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/tipo-edificacion"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-tipo-edificacion">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;tie_id&quot;: 5,
        &quot;tie_descripcion&quot;: &quot;RIESGO BAJO&quot;
    },
    {
        &quot;tie_id&quot;: 6,
        &quot;tie_descripcion&quot;: &quot;RIESGO MEDIO&quot;
    },
    {
        &quot;tie_id&quot;: 7,
        &quot;tie_descripcion&quot;: &quot;RIESGO ALTO&quot;
    },
    {
        &quot;tie_id&quot;: 8,
        &quot;tie_descripcion&quot;: &quot;RIESGO MUY ALTO&quot;
    },
    {
        &quot;tie_id&quot;: 1,
        &quot;tie_descripcion&quot;: &quot;EX POST&quot;
    },
    {
        &quot;tie_id&quot;: 2,
        &quot;tie_descripcion&quot;: &quot;EX ANTE&quot;
    },
    {
        &quot;tie_id&quot;: 3,
        &quot;tie_descripcion&quot;: &quot;DE PARTE&quot;
    },
    {
        &quot;tie_id&quot;: 4,
        &quot;tie_descripcion&quot;: &quot;DE DETALLE&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-tipo-edificacion" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-tipo-edificacion"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-tipo-edificacion"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-tipo-edificacion" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-tipo-edificacion">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-tipo-edificacion" data-method="GET"
      data-path="api/v1/tipo-edificacion"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-tipo-edificacion', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-tipo-edificacion"
                    onclick="tryItOut('GETapi-v1-tipo-edificacion');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-tipo-edificacion"
                    onclick="cancelTryOut('GETapi-v1-tipo-edificacion');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-tipo-edificacion"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/tipo-edificacion</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-tipo-edificacion"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-tipo-edificacion"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-licencias-expediente--lic_expnum-">Buscar por número de expediente.</h2>

<p>
</p>

<p>Llama a <code>LicenciaService::obtenerPorNumeroExpediente</code> y devuelve el resultado tal cual
en formato JSON. El servicio encapsula las comprobaciones de duplicados y la normalización
de datos.</p>
<p>Formato de respuesta esperado (ejemplos):</p>
<ul>
<li>{&quot;status&quot;:&quot;ok&quot;,&quot;data&quot;:{...}}        -&gt; coincidencia única</li>
<li>{&quot;status&quot;:&quot;duplicado&quot;,&quot;data&quot;:[...]} -&gt; varias coincidencias</li>
<li>{&quot;status&quot;:&quot;no_encontrado&quot;}            -&gt; sin resultados</li>
<li>{&quot;status&quot;:&quot;error&quot;,&quot;message&quot;:&quot;...&quot;} -&gt; fallo en la consulta</li>
</ul>

<span id="example-requests-GETapi-v1-licencias-expediente--lic_expnum-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/licencias/expediente/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/licencias/expediente/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-licencias-expediente--lic_expnum-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;no_encontrado&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-licencias-expediente--lic_expnum-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-licencias-expediente--lic_expnum-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-licencias-expediente--lic_expnum-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-licencias-expediente--lic_expnum-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-licencias-expediente--lic_expnum-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-licencias-expediente--lic_expnum-" data-method="GET"
      data-path="api/v1/licencias/expediente/{lic_expnum}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-licencias-expediente--lic_expnum-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-licencias-expediente--lic_expnum-"
                    onclick="tryItOut('GETapi-v1-licencias-expediente--lic_expnum-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-licencias-expediente--lic_expnum-"
                    onclick="cancelTryOut('GETapi-v1-licencias-expediente--lic_expnum-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-licencias-expediente--lic_expnum-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/licencias/expediente/{lic_expnum}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-licencias-expediente--lic_expnum-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-licencias-expediente--lic_expnum-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>lic_expnum</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="lic_expnum"                data-endpoint="GETapi-v1-licencias-expediente--lic_expnum-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-licencias-numero--lic_numlic-">Buscar por número de licencia.</h2>

<p>
</p>

<p>Útil cuando se conoce el número de licencia y se desea recuperar los
datos asociados desde la base histórica.</p>

<span id="example-requests-GETapi-v1-licencias-numero--lic_numlic-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/licencias/numero/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/licencias/numero/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-licencias-numero--lic_numlic-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;no_encontrado&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-licencias-numero--lic_numlic-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-licencias-numero--lic_numlic-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-licencias-numero--lic_numlic-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-licencias-numero--lic_numlic-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-licencias-numero--lic_numlic-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-licencias-numero--lic_numlic-" data-method="GET"
      data-path="api/v1/licencias/numero/{lic_numlic}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-licencias-numero--lic_numlic-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-licencias-numero--lic_numlic-"
                    onclick="tryItOut('GETapi-v1-licencias-numero--lic_numlic-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-licencias-numero--lic_numlic-"
                    onclick="cancelTryOut('GETapi-v1-licencias-numero--lic_numlic-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-licencias-numero--lic_numlic-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/licencias/numero/{lic_numlic}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-licencias-numero--lic_numlic-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-licencias-numero--lic_numlic-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>lic_numlic</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="lic_numlic"                data-endpoint="GETapi-v1-licencias-numero--lic_numlic-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-">Buscar por número de licencia y número de expediente.</h2>

<p>
</p>

<p>En la base de datos antigua pueden existir registros duplicados o
inconsistentes; proporcionar ambos valores ayuda a desambiguar la búsqueda.</p>

<span id="example-requests-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/licencias/licencia-expediente/architecto/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/licencias/licencia-expediente/architecto/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;no_encontrado&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-" data-method="GET"
      data-path="api/v1/licencias/licencia-expediente/{lic_numlic}/{lic_expnum}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-"
                    onclick="tryItOut('GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-"
                    onclick="cancelTryOut('GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/licencias/licencia-expediente/{lic_numlic}/{lic_expnum}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>lic_numlic</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="lic_numlic"                data-endpoint="GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>lic_expnum</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="lic_expnum"                data-endpoint="GETapi-v1-licencias-licencia-expediente--lic_numlic---lic_expnum-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-licencias-id--lic_id-">Buscar por ID de licencia.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-licencias-id--lic_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/licencias/id/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/licencias/id/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-licencias-id--lic_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  la sintaxis de entrada no es v&aacute;lida para tipo integer: &laquo;architecto&raquo;\nCONTEXT:  portal sin nombre, par&aacute;metro 1 = &#039;...&#039; (Connection: pgsql_licencias, SQL: select * from \&quot;licencia\&quot;.\&quot;licencia\&quot; where (\&quot;lic_id\&quot; = architecto) and \&quot;lic_filaeliminada\&quot; = 0)&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-licencias-id--lic_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-licencias-id--lic_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-licencias-id--lic_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-licencias-id--lic_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-licencias-id--lic_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-licencias-id--lic_id-" data-method="GET"
      data-path="api/v1/licencias/id/{lic_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-licencias-id--lic_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-licencias-id--lic_id-"
                    onclick="tryItOut('GETapi-v1-licencias-id--lic_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-licencias-id--lic_id-"
                    onclick="cancelTryOut('GETapi-v1-licencias-id--lic_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-licencias-id--lic_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/licencias/id/{lic_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-licencias-id--lic_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-licencias-id--lic_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>lic_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="lic_id"                data-endpoint="GETapi-v1-licencias-id--lic_id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the lic. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-persona-solicitante--per_idsolicitante-">Obtiene la información de un solicitante por su ID.</h2>

<p>
</p>

<p>Devuelve un JSON con la estructura que provee el servicio. Se espera que
el servicio retorne un arreglo o modelo serializable.</p>

<span id="example-requests-GETapi-v1-persona-solicitante--per_idsolicitante-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/persona-solicitante/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/persona-solicitante/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-persona-solicitante--per_idsolicitante-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;data&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-persona-solicitante--per_idsolicitante-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-persona-solicitante--per_idsolicitante-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-persona-solicitante--per_idsolicitante-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-persona-solicitante--per_idsolicitante-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-persona-solicitante--per_idsolicitante-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-persona-solicitante--per_idsolicitante-" data-method="GET"
      data-path="api/v1/persona-solicitante/{per_idsolicitante}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-persona-solicitante--per_idsolicitante-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-persona-solicitante--per_idsolicitante-"
                    onclick="tryItOut('GETapi-v1-persona-solicitante--per_idsolicitante-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-persona-solicitante--per_idsolicitante-"
                    onclick="cancelTryOut('GETapi-v1-persona-solicitante--per_idsolicitante-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-persona-solicitante--per_idsolicitante-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/persona-solicitante/{per_idsolicitante}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-persona-solicitante--per_idsolicitante-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-persona-solicitante--per_idsolicitante-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_idsolicitante</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="per_idsolicitante"                data-endpoint="GETapi-v1-persona-solicitante--per_idsolicitante-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-itse-certificados-buscar-ubicacion">Endpoint para autocompletar ubicaciones.</h2>

<p>
</p>

<p>Recibe query string <code>q</code> y delega al servicio para obtener coincidencias.
Devuelve un JSON con un array asociativo [key =&gt; label] donde ambas claves
se convierten a string para evitar problemas de tipado en el frontend.</p>

<span id="example-requests-GETapi-v1-itse-certificados-buscar-ubicacion">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/itse/certificados/buscar-ubicacion" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/itse/certificados/buscar-ubicacion"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-itse-certificados-buscar-ubicacion">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-itse-certificados-buscar-ubicacion" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-itse-certificados-buscar-ubicacion"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-itse-certificados-buscar-ubicacion"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-itse-certificados-buscar-ubicacion" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-itse-certificados-buscar-ubicacion">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-itse-certificados-buscar-ubicacion" data-method="GET"
      data-path="api/v1/itse/certificados/buscar-ubicacion"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-itse-certificados-buscar-ubicacion', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-itse-certificados-buscar-ubicacion"
                    onclick="tryItOut('GETapi-v1-itse-certificados-buscar-ubicacion');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-itse-certificados-buscar-ubicacion"
                    onclick="cancelTryOut('GETapi-v1-itse-certificados-buscar-ubicacion');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-itse-certificados-buscar-ubicacion"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/itse/certificados/buscar-ubicacion</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-itse-certificados-buscar-ubicacion"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-itse-certificados-buscar-ubicacion"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-">Genera y muestra el PDF de un certificado.</h2>

<p>
</p>

<p>Valida el tipo de edificación; si no está permitido puede redirigir a
una URL externa configurada en <code>certificado.redirect_url</code>. Si el tipo es
permitido, renderiza la vista Blade <code>certificados.pdf</code> y la convierte a PDF
usando Dompdf. Devuelve la respuesta con Content-Type <code>application/pdf</code>.</p>

<span id="example-requests-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/itse/certificados/exportar-pdf/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/itse/certificados/exportar-pdf/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-" data-method="GET"
      data-path="api/v1/itse/certificados/exportar-pdf/{certificadoId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-itse-certificados-exportar-pdf--certificadoId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-"
                    onclick="tryItOut('GETapi-v1-itse-certificados-exportar-pdf--certificadoId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-"
                    onclick="cancelTryOut('GETapi-v1-itse-certificados-exportar-pdf--certificadoId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-itse-certificados-exportar-pdf--certificadoId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/itse/certificados/exportar-pdf/{certificadoId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-itse-certificados-exportar-pdf--certificadoId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-itse-certificados-exportar-pdf--certificadoId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>certificadoId</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="certificadoId"                data-endpoint="GETapi-v1-itse-certificados-exportar-pdf--certificadoId-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-PUTapi-v1-itse-certificados-eliminar--certificadoId-">Marca un certificado como eliminado (eliminación lógica).</h2>

<p>
</p>



<span id="example-requests-PUTapi-v1-itse-certificados-eliminar--certificadoId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://itse-app.test/api/v1/itse/certificados/eliminar/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/itse/certificados/eliminar/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-itse-certificados-eliminar--certificadoId-">
</span>
<span id="execution-results-PUTapi-v1-itse-certificados-eliminar--certificadoId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-itse-certificados-eliminar--certificadoId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-itse-certificados-eliminar--certificadoId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-itse-certificados-eliminar--certificadoId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-itse-certificados-eliminar--certificadoId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-itse-certificados-eliminar--certificadoId-" data-method="PUT"
      data-path="api/v1/itse/certificados/eliminar/{certificadoId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-itse-certificados-eliminar--certificadoId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-itse-certificados-eliminar--certificadoId-"
                    onclick="tryItOut('PUTapi-v1-itse-certificados-eliminar--certificadoId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-itse-certificados-eliminar--certificadoId-"
                    onclick="cancelTryOut('PUTapi-v1-itse-certificados-eliminar--certificadoId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-itse-certificados-eliminar--certificadoId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/itse/certificados/eliminar/{certificadoId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-itse-certificados-eliminar--certificadoId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-itse-certificados-eliminar--certificadoId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>certificadoId</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="certificadoId"                data-endpoint="PUTapi-v1-itse-certificados-eliminar--certificadoId-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-certificado-lic-func-datos--expnum-">GET api/v1/certificado-lic-func/datos/{expnum}</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-certificado-lic-func-datos--expnum-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/certificado-lic-func/datos/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/certificado-lic-func/datos/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-certificado-lic-func-datos--expnum-">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-certificado-lic-func-datos--expnum-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-certificado-lic-func-datos--expnum-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-certificado-lic-func-datos--expnum-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-certificado-lic-func-datos--expnum-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-certificado-lic-func-datos--expnum-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-certificado-lic-func-datos--expnum-" data-method="GET"
      data-path="api/v1/certificado-lic-func/datos/{expnum}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-certificado-lic-func-datos--expnum-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-certificado-lic-func-datos--expnum-"
                    onclick="tryItOut('GETapi-v1-certificado-lic-func-datos--expnum-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-certificado-lic-func-datos--expnum-"
                    onclick="cancelTryOut('GETapi-v1-certificado-lic-func-datos--expnum-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-certificado-lic-func-datos--expnum-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/certificado-lic-func/datos/{expnum}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-certificado-lic-func-datos--expnum-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-certificado-lic-func-datos--expnum-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>expnum</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expnum"                data-endpoint="GETapi-v1-certificado-lic-func-datos--expnum-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-certificado-lic-func-datos-codcat--codcat-">GET api/v1/certificado-lic-func/datos-codcat/{codcat}</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-certificado-lic-func-datos-codcat--codcat-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/certificado-lic-func/datos-codcat/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/certificado-lic-func/datos-codcat/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-certificado-lic-func-datos-codcat--codcat-">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-certificado-lic-func-datos-codcat--codcat-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-certificado-lic-func-datos-codcat--codcat-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-certificado-lic-func-datos-codcat--codcat-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-certificado-lic-func-datos-codcat--codcat-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-certificado-lic-func-datos-codcat--codcat-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-certificado-lic-func-datos-codcat--codcat-" data-method="GET"
      data-path="api/v1/certificado-lic-func/datos-codcat/{codcat}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-certificado-lic-func-datos-codcat--codcat-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-certificado-lic-func-datos-codcat--codcat-"
                    onclick="tryItOut('GETapi-v1-certificado-lic-func-datos-codcat--codcat-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-certificado-lic-func-datos-codcat--codcat-"
                    onclick="cancelTryOut('GETapi-v1-certificado-lic-func-datos-codcat--codcat-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-certificado-lic-func-datos-codcat--codcat-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/certificado-lic-func/datos-codcat/{codcat}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-certificado-lic-func-datos-codcat--codcat-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-certificado-lic-func-datos-codcat--codcat-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>codcat</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="codcat"                data-endpoint="GETapi-v1-certificado-lic-func-datos-codcat--codcat-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa">GET api/v1/certificado-lic-func/lista-procedimientos-tupa</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/certificado-lic-func/lista-procedimientos-tupa" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/certificado-lic-func/lista-procedimientos-tupa"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa" data-method="GET"
      data-path="api/v1/certificado-lic-func/lista-procedimientos-tupa"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-certificado-lic-func-lista-procedimientos-tupa', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa"
                    onclick="tryItOut('GETapi-v1-certificado-lic-func-lista-procedimientos-tupa');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa"
                    onclick="cancelTryOut('GETapi-v1-certificado-lic-func-lista-procedimientos-tupa');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-certificado-lic-func-lista-procedimientos-tupa"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/certificado-lic-func/lista-procedimientos-tupa</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-certificado-lic-func-lista-procedimientos-tupa"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-certificado-lic-func-lista-procedimientos-tupa"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-">GET api/v1/certificado-lic-func/nivel-riesgo/{expnum}</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/certificado-lic-func/nivel-riesgo/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/certificado-lic-func/nivel-riesgo/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-" data-method="GET"
      data-path="api/v1/certificado-lic-func/nivel-riesgo/{expnum}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-"
                    onclick="tryItOut('GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-"
                    onclick="cancelTryOut('GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/certificado-lic-func/nivel-riesgo/{expnum}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>expnum</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expnum"                data-endpoint="GETapi-v1-certificado-lic-func-nivel-riesgo--expnum-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-certificado-lic-func-datos-completos--expnum-">GET api/v1/certificado-lic-func/datos-completos/{expnum}</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-certificado-lic-func-datos-completos--expnum-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/certificado-lic-func/datos-completos/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/certificado-lic-func/datos-completos/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-certificado-lic-func-datos-completos--expnum-">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-certificado-lic-func-datos-completos--expnum-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-certificado-lic-func-datos-completos--expnum-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-certificado-lic-func-datos-completos--expnum-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-certificado-lic-func-datos-completos--expnum-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-certificado-lic-func-datos-completos--expnum-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-certificado-lic-func-datos-completos--expnum-" data-method="GET"
      data-path="api/v1/certificado-lic-func/datos-completos/{expnum}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-certificado-lic-func-datos-completos--expnum-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-certificado-lic-func-datos-completos--expnum-"
                    onclick="tryItOut('GETapi-v1-certificado-lic-func-datos-completos--expnum-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-certificado-lic-func-datos-completos--expnum-"
                    onclick="cancelTryOut('GETapi-v1-certificado-lic-func-datos-completos--expnum-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-certificado-lic-func-datos-completos--expnum-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/certificado-lic-func/datos-completos/{expnum}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-certificado-lic-func-datos-completos--expnum-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-certificado-lic-func-datos-completos--expnum-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>expnum</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expnum"                data-endpoint="GETapi-v1-certificado-lic-func-datos-completos--expnum-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-tipo-licencia">GET api/v1/tipo-licencia</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-tipo-licencia">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/tipo-licencia" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/tipo-licencia"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-tipo-licencia">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-tipo-licencia" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-tipo-licencia"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-tipo-licencia"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-tipo-licencia" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-tipo-licencia">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-tipo-licencia" data-method="GET"
      data-path="api/v1/tipo-licencia"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-tipo-licencia', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-tipo-licencia"
                    onclick="tryItOut('GETapi-v1-tipo-licencia');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-tipo-licencia"
                    onclick="cancelTryOut('GETapi-v1-tipo-licencia');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-tipo-licencia"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/tipo-licencia</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-tipo-licencia"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-tipo-licencia"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-giros-buscar--search-">GET api/v1/giros/buscar/{search}</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-giros-buscar--search-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/giros/buscar/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/giros/buscar/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-giros-buscar--search-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-giros-buscar--search-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-giros-buscar--search-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-giros-buscar--search-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-giros-buscar--search-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-giros-buscar--search-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-giros-buscar--search-" data-method="GET"
      data-path="api/v1/giros/buscar/{search}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-giros-buscar--search-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-giros-buscar--search-"
                    onclick="tryItOut('GETapi-v1-giros-buscar--search-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-giros-buscar--search-"
                    onclick="cancelTryOut('GETapi-v1-giros-buscar--search-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-giros-buscar--search-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/giros/buscar/{search}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-giros-buscar--search-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-giros-buscar--search-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-giros-buscar--search-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-giros-listar">GET api/v1/giros/listar</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-giros-listar">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://itse-app.test/api/v1/giros/listar" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://itse-app.test/api/v1/giros/listar"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-giros-listar">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;gir_id&quot;: 1000,
        &quot;gir_descripcion&quot;: &quot;GIRO DE PRUEBA&quot;
    },
    {
        &quot;gir_id&quot;: 1015,
        &quot;gir_descripcion&quot;: &quot;ODONTOL&Oacute;GICO&quot;
    },
    {
        &quot;gir_id&quot;: 1018,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA Y PRODUCTOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1019,
        &quot;gir_descripcion&quot;: &quot;OPTICA&quot;
    },
    {
        &quot;gir_id&quot;: 1023,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS CORRESPONDIENTE A LA EMPRESA NAVIERA&quot;
    },
    {
        &quot;gir_id&quot;: 1024,
        &quot;gir_descripcion&quot;: &quot;VENTA DE JUGUETES&quot;
    },
    {
        &quot;gir_id&quot;: 1025,
        &quot;gir_descripcion&quot;: &quot;TELECOMERCIO DE COMERCIALIZACION DE MEDICINA Y PRODUCTOS DE DROGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1026,
        &quot;gir_descripcion&quot;: &quot;CABINA DE INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 1036,
        &quot;gir_descripcion&quot;: &quot;AGENCIA BANCARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1037,
        &quot;gir_descripcion&quot;: &quot;PRESTACION DE ASESORAMIENTO EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 1038,
        &quot;gir_descripcion&quot;: &quot;ZAPATER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 1040,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROYECTOS DE INGENIERIA Y ARQUITECTURA&quot;
    },
    {
        &quot;gir_id&quot;: 1041,
        &quot;gir_descripcion&quot;: &quot;FERRETERIA Y SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1043,
        &quot;gir_descripcion&quot;: &quot;OFICINA PROFESIONAL INDEPENDIENTE&quot;
    },
    {
        &quot;gir_id&quot;: 1045,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO INICIAL Y GUARDERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1046,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACIONES, EXPORTACIONES, REPRESENTACIONES Y DISTRIBUCION  DE ARTICULOS DE JARDINERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1047,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COMIDA CRIOLLA&quot;
    },
    {
        &quot;gir_id&quot;: 1048,
        &quot;gir_descripcion&quot;: &quot;HELADERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1049,
        &quot;gir_descripcion&quot;: &quot;TIPEOS&quot;
    },
    {
        &quot;gir_id&quot;: 1050,
        &quot;gir_descripcion&quot;: &quot;UTILES DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 1052,
        &quot;gir_descripcion&quot;: &quot;PASTELERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1042,
        &quot;gir_descripcion&quot;: &quot;FERRETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1056,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PASTELES&quot;
    },
    {
        &quot;gir_id&quot;: 1060,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 1062,
        &quot;gir_descripcion&quot;: &quot;VENTA DE JOYAS&quot;
    },
    {
        &quot;gir_id&quot;: 1064,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE JARDINERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1067,
        &quot;gir_descripcion&quot;: &quot;IMPRESIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1069,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE REHABILITACION Y OTRAS TERAPIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1070,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COMBUSTIBLE Y GRIFOS&quot;
    },
    {
        &quot;gir_id&quot;: 1074,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRENDAS DE VESTIR Y ACCESORIOS PARA NI&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 1075,
        &quot;gir_descripcion&quot;: &quot;PELUQUERIA,EST&Eacute;TICA DE MANOS Y PIES VENTA DE PRODUCTO&quot;
    },
    {
        &quot;gir_id&quot;: 1076,
        &quot;gir_descripcion&quot;: &quot;PELUQUER&Iacute;A,EST&Eacute;TICA DE MANOS Y PIES VENTA DE PRODUCTOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1078,
        &quot;gir_descripcion&quot;: &quot;BODEGA,FUENTES DE SODA&quot;
    },
    {
        &quot;gir_id&quot;: 1081,
        &quot;gir_descripcion&quot;: &quot;OFICINA COMERCIAL-GESTION COMERCIAL DE MAQUINARIA HERRAMIENTAS Y EQUIPOS PARA LA INDUSTRIA METAL MECANICA&quot;
    },
    {
        &quot;gir_id&quot;: 1083,
        &quot;gir_descripcion&quot;: &quot;ESCUELA DE FOTOGR&Aacute;FICO,ACCESORIOS,FOTOGRAFICOS,FOTOCOPIAS.TIPEOS,INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 1097,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ZAPATOS,ROPA Y BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 1098,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA, PELUQUERIA Y VENTA DE ARTICULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 1099,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE ESTETICA NATURISTA&quot;
    },
    {
        &quot;gir_id&quot;: 1101,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE BELLEZA NATURALES&quot;
    },
    {
        &quot;gir_id&quot;: 1103,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA DE BEBES Y NI&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 1105,
        &quot;gir_descripcion&quot;: &quot;SELLOS&quot;
    },
    {
        &quot;gir_id&quot;: 1108,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE LENTES&quot;
    },
    {
        &quot;gir_id&quot;: 1109,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE LENTES&quot;
    },
    {
        &quot;gir_id&quot;: 1110,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMPRA,VENTA Y ALQUILER DE BIENES PROPIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1112,
        &quot;gir_descripcion&quot;: &quot;BODEGA (CON VENTA DE LICOR PARA LLEVAR), LIBRER&Iacute;A,BAZAR,VERDULER&Iacute;A Y FRUTER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1113,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO DENTAL&quot;
    },
    {
        &quot;gir_id&quot;: 1116,
        &quot;gir_descripcion&quot;: &quot;PELUQUER&Iacute;A, GIMNASIO, FISICOCULTURISMO (SPA)&quot;
    },
    {
        &quot;gir_id&quot;: 1119,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS GENERALES Y CONTRATISTAS GENERALES&quot;
    },
    {
        &quot;gir_id&quot;: 1120,
        &quot;gir_descripcion&quot;: &quot;PIZZERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1121,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COMIDAS AL PASO, POLLO FRITO, PIZZAS, DELIVERY&quot;
    },
    {
        &quot;gir_id&quot;: 1123,
        &quot;gir_descripcion&quot;: &quot;TELECOMERCIO DE VENTA DE PRODUCTOS DE ALPACA Y SERVICIOS TURISTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1124,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE DECORACION DE INTERIORES&quot;
    },
    {
        &quot;gir_id&quot;: 1122,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICOR COMO COMPLEMENTO DE COMIDA&quot;
    },
    {
        &quot;gir_id&quot;: 1126,
        &quot;gir_descripcion&quot;: &quot;VENTA DE FRUTAS Y VERDURAS&quot;
    },
    {
        &quot;gir_id&quot;: 1127,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE REPOSTERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3100,
        &quot;gir_descripcion&quot;: &quot;MODISTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1129,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACION DE FIBRA DE ALGODON&quot;
    },
    {
        &quot;gir_id&quot;: 1130,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE MATERIALES DIDACTICOS Y EDUCATIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 1131,
        &quot;gir_descripcion&quot;: &quot;FRUTER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1133,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS Y SERVICIOS POR CATALOGO Y TELECOMUNICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1134,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE BELLEZA Y AFINES&quot;
    },
    {
        &quot;gir_id&quot;: 1135,
        &quot;gir_descripcion&quot;: &quot;VENTA DE OTROS PRODUCTOS NO ALIMENTICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1141,
        &quot;gir_descripcion&quot;: &quot;PICARONERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1142,
        &quot;gir_descripcion&quot;: &quot;VENTA DE GASEOSAS Y GOLOSINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1143,
        &quot;gir_descripcion&quot;: &quot;BODEGA CON VENTA DE VERDURAS, FRUTAS, AVES BENEFICIADAS&quot;
    },
    {
        &quot;gir_id&quot;: 1144,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 1146,
        &quot;gir_descripcion&quot;: &quot;CAFE - BAR&quot;
    },
    {
        &quot;gir_id&quot;: 1147,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE EMPLEOS&quot;
    },
    {
        &quot;gir_id&quot;: 1148,
        &quot;gir_descripcion&quot;: &quot;CORRETAJE&quot;
    },
    {
        &quot;gir_id&quot;: 1149,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UNIFORMES DE PERSONAL DOMESTICO&quot;
    },
    {
        &quot;gir_id&quot;: 1156,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ARQUITECTURA E INGENIERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1157,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LOZAS&quot;
    },
    {
        &quot;gir_id&quot;: 1159,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS Y ACCESORIOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1160,
        &quot;gir_descripcion&quot;: &quot;PELUQUERIA DE CABALLEROS&quot;
    },
    {
        &quot;gir_id&quot;: 1053,
        &quot;gir_descripcion&quot;: &quot;COMUNICACIONES TELEF&Oacute;NICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1020,
        &quot;gir_descripcion&quot;: &quot;PELUQUER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 1002,
        &quot;gir_descripcion&quot;: &quot;CENTRO M&Eacute;DICO&quot;
    },
    {
        &quot;gir_id&quot;: 1072,
        &quot;gir_descripcion&quot;: &quot;CASAS DE CAMBIO&quot;
    },
    {
        &quot;gir_id&quot;: 1138,
        &quot;gir_descripcion&quot;: &quot;BAZAR Y REGALOS&quot;
    },
    {
        &quot;gir_id&quot;: 1106,
        &quot;gir_descripcion&quot;: &quot;BOTICA - PERFUMER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1061,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE&quot;
    },
    {
        &quot;gir_id&quot;: 1021,
        &quot;gir_descripcion&quot;: &quot;COMIDAS R&Aacute;PIDAS&quot;
    },
    {
        &quot;gir_id&quot;: 1132,
        &quot;gir_descripcion&quot;: &quot;VERDULER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1102,
        &quot;gir_descripcion&quot;: &quot;JUGUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1051,
        &quot;gir_descripcion&quot;: &quot;PANADER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1003,
        &quot;gir_descripcion&quot;: &quot;ALQUILER DE INMUEBLES COMERCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 1016,
        &quot;gir_descripcion&quot;: &quot;REHABILITACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1017,
        &quot;gir_descripcion&quot;: &quot;PODOLOG&Iacute;A TERAP&Eacute;UTICA&quot;
    },
    {
        &quot;gir_id&quot;: 1022,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE DELIVERY&quot;
    },
    {
        &quot;gir_id&quot;: 1027,
        &quot;gir_descripcion&quot;: &quot;PLOTEO&quot;
    },
    {
        &quot;gir_id&quot;: 1028,
        &quot;gir_descripcion&quot;: &quot;SCANNER&quot;
    },
    {
        &quot;gir_id&quot;: 1029,
        &quot;gir_descripcion&quot;: &quot;VIDEO JUEGOS&quot;
    },
    {
        &quot;gir_id&quot;: 1030,
        &quot;gir_descripcion&quot;: &quot;FOTOCOPIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1054,
        &quot;gir_descripcion&quot;: &quot;FLORER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1057,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE VENTA Y EXHIBICION DE EQUIPOS PARA PISCINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1058,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PARA PISCINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1063,
        &quot;gir_descripcion&quot;: &quot;VENTA DE RELOJES&quot;
    },
    {
        &quot;gir_id&quot;: 1065,
        &quot;gir_descripcion&quot;: &quot;PELUQUERIA UNISEX&quot;
    },
    {
        &quot;gir_id&quot;: 1068,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 1071,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE ARQUITECTURA Y AGRIMISURA&quot;
    },
    {
        &quot;gir_id&quot;: 1073,
        &quot;gir_descripcion&quot;: &quot;SALON DE BILLAR&quot;
    },
    {
        &quot;gir_id&quot;: 1077,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS PARA ANIMALES, VENTA DE MASCOTAS, ASESOR&Iacute;A T&Eacute;CNICA EN CRIANZA Y CONSULTORIA VETERINARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1079,
        &quot;gir_descripcion&quot;: &quot;BODEGA,FUENTE DE SODA&quot;
    },
    {
        &quot;gir_id&quot;: 1082,
        &quot;gir_descripcion&quot;: &quot;INTERNET,LOCUTORIO&quot;
    },
    {
        &quot;gir_id&quot;: 1084,
        &quot;gir_descripcion&quot;: &quot;ESTUDIO FOTOGRAFICO,ACCESORIOS FOTOGRAFICOS,FOTOCOPIAS,TIPEOS.INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 1111,
        &quot;gir_descripcion&quot;: &quot;VENTAS DE PLANTAS Y FLORES&quot;
    },
    {
        &quot;gir_id&quot;: 1115,
        &quot;gir_descripcion&quot;: &quot;FUENTE DE SODA , SANDWICHERIA,JUGUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1117,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEH&Iacute;CULOS USADOS Y/O NUEVOS (VENTA DE VEH&Iacute;CULOS , AUTOMOTORES,RESPUESTOS, MANTENIMIENTO Y SERVICIO AUTOMOTRIZ&quot;
    },
    {
        &quot;gir_id&quot;: 1125,
        &quot;gir_descripcion&quot;: &quot;ARREGLO DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 1128,
        &quot;gir_descripcion&quot;: &quot;ASESORIA Y REPRESENTACION PARA EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 1136,
        &quot;gir_descripcion&quot;: &quot;MONTURA DE LENTES&quot;
    },
    {
        &quot;gir_id&quot;: 1139,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PERFUMES Y COSMETICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1150,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS MEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1153,
        &quot;gir_descripcion&quot;: &quot;ARREGLO MENOR DE PRENDAS&quot;
    },
    {
        &quot;gir_id&quot;: 1154,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE LIBRERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1158,
        &quot;gir_descripcion&quot;: &quot;CORTINAS PARA BA&Ntilde;O, MANTELES, RELOJES DE PARED&quot;
    },
    {
        &quot;gir_id&quot;: 1161,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS Y ACCESORIOS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 1162,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESAS DE SERVICIOS DE MANTENIMIENTO DE MAQUINARIAS Y EQUIPOS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 1698,
        &quot;gir_descripcion&quot;: &quot;COMPRA Y VENTA DE AUTOMOVILES&quot;
    },
    {
        &quot;gir_id&quot;: 1716,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS Y ARTICULOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1717,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ALQUILER DE MAQUINARIA PESADA&quot;
    },
    {
        &quot;gir_id&quot;: 1718,
        &quot;gir_descripcion&quot;: &quot;ETC&quot;
    },
    {
        &quot;gir_id&quot;: 1720,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CORTINAS Y TAPICES&quot;
    },
    {
        &quot;gir_id&quot;: 1721,
        &quot;gir_descripcion&quot;: &quot;VENTA DE AUTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1722,
        &quot;gir_descripcion&quot;: &quot;MANTENIMIENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1723,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO DE PRIMARIA Y SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1724,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACION Y EXPORTACION DE MATERIAL RECICLABLE&quot;
    },
    {
        &quot;gir_id&quot;: 1725,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMPRA Y VENTA&quot;
    },
    {
        &quot;gir_id&quot;: 1727,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE FOTOGRAFIA&quot;
    },
    {
        &quot;gir_id&quot;: 1728,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ARQUITECTURA Y CONSTRUCCION&quot;
    },
    {
        &quot;gir_id&quot;: 1729,
        &quot;gir_descripcion&quot;: &quot;ACADEMIAS DE ARTES Y OFICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1730,
        &quot;gir_descripcion&quot;: &quot;LOCUTORIO CON VENTA DE EQUIPOS DE INFORMATICA&quot;
    },
    {
        &quot;gir_id&quot;: 1731,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE ASESORAMIENTO Y SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1733,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VIDRIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1735,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE AGENCIA DE CORREDORES DE SEGURO&quot;
    },
    {
        &quot;gir_id&quot;: 1736,
        &quot;gir_descripcion&quot;: &quot;ALMACENAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1737,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA PARA BEBES&quot;
    },
    {
        &quot;gir_id&quot;: 1738,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE EXPLORACION GEOLOGICA&quot;
    },
    {
        &quot;gir_id&quot;: 1739,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE PUBLICIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1740,
        &quot;gir_descripcion&quot;: &quot;COMERCIO EXTERIOR&quot;
    },
    {
        &quot;gir_id&quot;: 1741,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COMIDA AL PASO&quot;
    },
    {
        &quot;gir_id&quot;: 1742,
        &quot;gir_descripcion&quot;: &quot;FISICOCULTURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 1743,
        &quot;gir_descripcion&quot;: &quot;ARREGLO DE PRENDAS MENORES&quot;
    },
    {
        &quot;gir_id&quot;: 1744,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE VIAJE Y TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 1745,
        &quot;gir_descripcion&quot;: &quot;ENTIDAD FINANCIERA&quot;
    },
    {
        &quot;gir_id&quot;: 1746,
        &quot;gir_descripcion&quot;: &quot;TRANSPORTE DE CARGA POR CARRETERA&quot;
    },
    {
        &quot;gir_id&quot;: 1747,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE FABRICACION DE MOBILIARIO EDUCATIVO Y DECORACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1748,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTEFACTOS DE ILUMINACION Y BRONCE&quot;
    },
    {
        &quot;gir_id&quot;: 1749,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE TURISTICO CON SHOW EN VIVO&quot;
    },
    {
        &quot;gir_id&quot;: 1750,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 1751,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TELECOMERCIO CON COMERCIALIZACION DE PRODUCTOS A BASE DE PESCADO, LEGUMBRES Y LACTEOS&quot;
    },
    {
        &quot;gir_id&quot;: 1752,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DIGITACION DE REVISTAS Y FOLLETOS&quot;
    },
    {
        &quot;gir_id&quot;: 1753,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS NATURALES Y SUPLEMENTOS ALIMENTICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1754,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA PRE UNIVERSITARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1755,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE ARTES Y OFICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1756,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACION DE PRODUCTOS AUTOMOTRICES&quot;
    },
    {
        &quot;gir_id&quot;: 1004,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA&quot;
    },
    {
        &quot;gir_id&quot;: 1031,
        &quot;gir_descripcion&quot;: &quot;CABINAS DE INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 1055,
        &quot;gir_descripcion&quot;: &quot;CALZADO&quot;
    },
    {
        &quot;gir_id&quot;: 1059,
        &quot;gir_descripcion&quot;: &quot;BODEGA&quot;
    },
    {
        &quot;gir_id&quot;: 1066,
        &quot;gir_descripcion&quot;: &quot;TRATAMIENTO CORPORAL EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 1085,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE YOGA Y MEDITACION&quot;
    },
    {
        &quot;gir_id&quot;: 1151,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CALZADO&quot;
    },
    {
        &quot;gir_id&quot;: 1163,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SEGURIDAD Y VIGILANCIA PRIVADA&quot;
    },
    {
        &quot;gir_id&quot;: 1719,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DIVERSOS PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 1104,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE DECORACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1137,
        &quot;gir_descripcion&quot;: &quot;EDUCACI&Oacute;N INICIAL&quot;
    },
    {
        &quot;gir_id&quot;: 1165,
        &quot;gir_descripcion&quot;: &quot;INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 1166,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 1167,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE CAMBIO DE MONEDA EXTRANJERA&quot;
    },
    {
        &quot;gir_id&quot;: 1169,
        &quot;gir_descripcion&quot;: &quot;COMPRA - VENTA DE INMUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 1170,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PERFUMES&quot;
    },
    {
        &quot;gir_id&quot;: 1171,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES PERSONALES - ABOGADA&quot;
    },
    {
        &quot;gir_id&quot;: 1172,
        &quot;gir_descripcion&quot;: &quot;DISE&Ntilde;O Y DECORACION DE INTERIORES&quot;
    },
    {
        &quot;gir_id&quot;: 1175,
        &quot;gir_descripcion&quot;: &quot;JUGUETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1179,
        &quot;gir_descripcion&quot;: &quot;ARTESAN&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1225,
        &quot;gir_descripcion&quot;: &quot;RELOJES DE PARED&quot;
    },
    {
        &quot;gir_id&quot;: 1703,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO M&Eacute;DICO VETERINARIO&quot;
    },
    {
        &quot;gir_id&quot;: 1181,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE REPARACION Y ARREGLO MENOR DE PRENDAS DE VESTIR Y COSTURA&quot;
    },
    {
        &quot;gir_id&quot;: 1183,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MALETINES EDUCATIVOS Y DIDACTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1184,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1185,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO MEDICO GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 1188,
        &quot;gir_descripcion&quot;: &quot;TRATAMIENTO CORPORAL ANTICELULITIS Y TRATAMIENTO FACIAL&quot;
    },
    {
        &quot;gir_id&quot;: 1191,
        &quot;gir_descripcion&quot;: &quot;CABINAS TELEF&Oacute;NICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1192,
        &quot;gir_descripcion&quot;: &quot;FOTOCOPIAS E IMPRESIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1193,
        &quot;gir_descripcion&quot;: &quot;VENTA DE TARJETAS TELEFONICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1194,
        &quot;gir_descripcion&quot;: &quot;ARREGLO DE MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 1195,
        &quot;gir_descripcion&quot;: &quot;LAVANDERIA Y SERVICIOS DE LAVANDERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1196,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ABARROTES&quot;
    },
    {
        &quot;gir_id&quot;: 1197,
        &quot;gir_descripcion&quot;: &quot;FRUTAS SECAS&quot;
    },
    {
        &quot;gir_id&quot;: 1198,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DESCARTABLES&quot;
    },
    {
        &quot;gir_id&quot;: 1199,
        &quot;gir_descripcion&quot;: &quot;COMIDAS PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 1200,
        &quot;gir_descripcion&quot;: &quot;CLASES DE REPOSTERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1201,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COMIDAS AL PASO&quot;
    },
    {
        &quot;gir_id&quot;: 1202,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS GERIATRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1208,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE CON VENTA DE HAMBURGUESAS&quot;
    },
    {
        &quot;gir_id&quot;: 1209,
        &quot;gir_descripcion&quot;: &quot;JUEGOS RECREATIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 1211,
        &quot;gir_descripcion&quot;: &quot;PELUCHES&quot;
    },
    {
        &quot;gir_id&quot;: 1212,
        &quot;gir_descripcion&quot;: &quot;MOCHILAS&quot;
    },
    {
        &quot;gir_id&quot;: 1213,
        &quot;gir_descripcion&quot;: &quot;COPIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1215,
        &quot;gir_descripcion&quot;: &quot;TRANSFERENCIA DE DINERO&quot;
    },
    {
        &quot;gir_id&quot;: 1216,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CD&acute;S&quot;
    },
    {
        &quot;gir_id&quot;: 1217,
        &quot;gir_descripcion&quot;: &quot;MUSICA Y VIDEOS ORIGINALES&quot;
    },
    {
        &quot;gir_id&quot;: 1218,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE CON VENTA DE POLLOS FRITOS&quot;
    },
    {
        &quot;gir_id&quot;: 1221,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CARTERAS&quot;
    },
    {
        &quot;gir_id&quot;: 1222,
        &quot;gir_descripcion&quot;: &quot;MANTELES&quot;
    },
    {
        &quot;gir_id&quot;: 1223,
        &quot;gir_descripcion&quot;: &quot;VASOS&quot;
    },
    {
        &quot;gir_id&quot;: 1224,
        &quot;gir_descripcion&quot;: &quot;PLATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1229,
        &quot;gir_descripcion&quot;: &quot;PLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1230,
        &quot;gir_descripcion&quot;: &quot;ESCUELA PRIMARIA Y SECUNDARIA PARA ADULTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1231,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS ORTOP&Eacute;DICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1234,
        &quot;gir_descripcion&quot;: &quot;PASAMANERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1235,
        &quot;gir_descripcion&quot;: &quot;COTILLON&quot;
    },
    {
        &quot;gir_id&quot;: 1236,
        &quot;gir_descripcion&quot;: &quot;REGALOS Y DISFRACES&quot;
    },
    {
        &quot;gir_id&quot;: 1237,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA  DE SERVICIOS DE ASESORAMIENTO Y SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1238,
        &quot;gir_descripcion&quot;: &quot;POLLOS A LA BRASA&quot;
    },
    {
        &quot;gir_id&quot;: 1239,
        &quot;gir_descripcion&quot;: &quot;GUARDERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1241,
        &quot;gir_descripcion&quot;: &quot;ORGANIZACION DE EVENTOS Y VENTA POR MENOR DE PRODUCTOS DE FIESTA&quot;
    },
    {
        &quot;gir_id&quot;: 1242,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE AVISOS PUBLICITARIOS Y ACTIVIDADES INMOBILIARIAS DE VENTA&quot;
    },
    {
        &quot;gir_id&quot;: 1244,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS POSTALES INTERNACIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 1246,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE VIDRIO Y LIBRERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1247,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTESANIA E INSUMO&quot;
    },
    {
        &quot;gir_id&quot;: 1248,
        &quot;gir_descripcion&quot;: &quot;VENTA DE GAS&quot;
    },
    {
        &quot;gir_id&quot;: 1249,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE CONTABILIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1250,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CORRETAJE DE INMUEBLE&quot;
    },
    {
        &quot;gir_id&quot;: 1252,
        &quot;gir_descripcion&quot;: &quot;TERAPIA FISICA, REHABILITACION Y MASAJES TERAPEUTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1258,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE VENTA Y EXHIBICION DE EQUIPOS HIDRAULICOS PARA PISCINA&quot;
    },
    {
        &quot;gir_id&quot;: 1259,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICORES SIN CONSUMO&quot;
    },
    {
        &quot;gir_id&quot;: 1260,
        &quot;gir_descripcion&quot;: &quot;COURRIER&quot;
    },
    {
        &quot;gir_id&quot;: 1261,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALIMENTOS Y ACCESORIOS PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 1262,
        &quot;gir_descripcion&quot;: &quot;TELECOMERCIO DE VENTA, ALQUILER Y REPARACION DE EQUIPOS MEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1263,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BEBIDAS NO ALCOHOLICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1264,
        &quot;gir_descripcion&quot;: &quot;TIENDA DE MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1265,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1266,
        &quot;gir_descripcion&quot;: &quot;ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1267,
        &quot;gir_descripcion&quot;: &quot;ALIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1268,
        &quot;gir_descripcion&quot;: &quot;MEDICINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1269,
        &quot;gir_descripcion&quot;: &quot;VETERINARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1271,
        &quot;gir_descripcion&quot;: &quot;NOTARIOS Y ESCRIBANOS&quot;
    },
    {
        &quot;gir_id&quot;: 1272,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BEBIDAS DIETETICAS Y GASEOSAS&quot;
    },
    {
        &quot;gir_id&quot;: 1273,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACION DE UTILES ESCOLARES - OFICINA - JUGUETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1274,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PLANTAS Y FLORES&quot;
    },
    {
        &quot;gir_id&quot;: 1275,
        &quot;gir_descripcion&quot;: &quot;CHOCOLATER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1276,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1277,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 1278,
        &quot;gir_descripcion&quot;: &quot;OPTICA Y MEDICION DE LA VISTA&quot;
    },
    {
        &quot;gir_id&quot;: 1279,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE INFORMATICA&quot;
    },
    {
        &quot;gir_id&quot;: 1281,
        &quot;gir_descripcion&quot;: &quot;GALLETAS&quot;
    },
    {
        &quot;gir_id&quot;: 1282,
        &quot;gir_descripcion&quot;: &quot;ALQUILER Y VENTA DE VIDEOS&quot;
    },
    {
        &quot;gir_id&quot;: 1284,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE REGALO&quot;
    },
    {
        &quot;gir_id&quot;: 1285,
        &quot;gir_descripcion&quot;: &quot;VENTA AL POR MENOR DE BEBIDAS GASEOSAS Y GOLOSINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1286,
        &quot;gir_descripcion&quot;: &quot;CASSETTES&quot;
    },
    {
        &quot;gir_id&quot;: 1287,
        &quot;gir_descripcion&quot;: &quot;PILAS&quot;
    },
    {
        &quot;gir_id&quot;: 1288,
        &quot;gir_descripcion&quot;: &quot;COSMETICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1289,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIERIA ELECTRONICA&quot;
    },
    {
        &quot;gir_id&quot;: 1290,
        &quot;gir_descripcion&quot;: &quot;DECORACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1291,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEHICULOS AUTOMOTORES&quot;
    },
    {
        &quot;gir_id&quot;: 1293,
        &quot;gir_descripcion&quot;: &quot;DECORACIONES PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 1005,
        &quot;gir_descripcion&quot;: &quot;LIBRERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1220,
        &quot;gir_descripcion&quot;: &quot;ESTUDIO FOTOGR&Aacute;FICO&quot;
    },
    {
        &quot;gir_id&quot;: 1164,
        &quot;gir_descripcion&quot;: &quot;SASTRER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1233,
        &quot;gir_descripcion&quot;: &quot;PI&Ntilde;ATER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1189,
        &quot;gir_descripcion&quot;: &quot;CASAS NATURISTAS (11)&quot;
    },
    {
        &quot;gir_id&quot;: 1032,
        &quot;gir_descripcion&quot;: &quot;ACCESORIOS DE COMPUTO&quot;
    },
    {
        &quot;gir_id&quot;: 1033,
        &quot;gir_descripcion&quot;: &quot;GASEOSAS&quot;
    },
    {
        &quot;gir_id&quot;: 1034,
        &quot;gir_descripcion&quot;: &quot;GOLOSINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1035,
        &quot;gir_descripcion&quot;: &quot;REGALOS&quot;
    },
    {
        &quot;gir_id&quot;: 1086,
        &quot;gir_descripcion&quot;: &quot;BAZAR,VENTA DE PRODUCTOS DIVERSOS PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 1152,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS MEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1173,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACION DE PRODUCTOS DE DECORACION Y MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 1176,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACIONES PARA VENTA DE MAQUINARIA, MATERIA  PRIMA Y ASISTENCIA TECNICA&quot;
    },
    {
        &quot;gir_id&quot;: 1182,
        &quot;gir_descripcion&quot;: &quot;CARTERAS&quot;
    },
    {
        &quot;gir_id&quot;: 1190,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS NATURALES&quot;
    },
    {
        &quot;gir_id&quot;: 1203,
        &quot;gir_descripcion&quot;: &quot;PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 1204,
        &quot;gir_descripcion&quot;: &quot;JUGUETES&quot;
    },
    {
        &quot;gir_id&quot;: 1210,
        &quot;gir_descripcion&quot;: &quot;REPARTO - DELIVERY&quot;
    },
    {
        &quot;gir_id&quot;: 1214,
        &quot;gir_descripcion&quot;: &quot;DULCES - GASEOSAS&quot;
    },
    {
        &quot;gir_id&quot;: 1219,
        &quot;gir_descripcion&quot;: &quot;REPARTO A DOMICILIO&quot;
    },
    {
        &quot;gir_id&quot;: 1186,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO DE NUTRICI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1226,
        &quot;gir_descripcion&quot;: &quot;ALBUNES&quot;
    },
    {
        &quot;gir_id&quot;: 1232,
        &quot;gir_descripcion&quot;: &quot;MATERIAL E INSTRUMENTO MEDICO&quot;
    },
    {
        &quot;gir_id&quot;: 1240,
        &quot;gir_descripcion&quot;: &quot;SPA&quot;
    },
    {
        &quot;gir_id&quot;: 1243,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1245,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS POSTALES NACIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 1251,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACION Y COMERCIALIZACION DE PRODUCTOS Y EQUIPOS MEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1253,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ORTOPEDICOS Y AYUDA BIOMECANIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1270,
        &quot;gir_descripcion&quot;: &quot;ADIESTRAMIENTO Y BA&Ntilde;O SIN HOSPEDAJE&quot;
    },
    {
        &quot;gir_id&quot;: 1280,
        &quot;gir_descripcion&quot;: &quot;SUMINISTROS, ACCESORIOS DE COMPUTO&quot;
    },
    {
        &quot;gir_id&quot;: 1294,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE NATACION&quot;
    },
    {
        &quot;gir_id&quot;: 1704,
        &quot;gir_descripcion&quot;: &quot;VENTA DE DISCOS&quot;
    },
    {
        &quot;gir_id&quot;: 1705,
        &quot;gir_descripcion&quot;: &quot;ROLLOS&quot;
    },
    {
        &quot;gir_id&quot;: 1706,
        &quot;gir_descripcion&quot;: &quot;VHS&quot;
    },
    {
        &quot;gir_id&quot;: 1726,
        &quot;gir_descripcion&quot;: &quot;ALQUILER DE BIENES INMUEBLES PROPIOS O ALQUILADOS, BIENES RAICES Y CORRETAJE&quot;
    },
    {
        &quot;gir_id&quot;: 1732,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE EMPLEO Y SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1006,
        &quot;gir_descripcion&quot;: &quot;CONFITERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1087,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DEPORTIVOS (EQUITACION)&quot;
    },
    {
        &quot;gir_id&quot;: 1091,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VIDRIOS,ARTICULOS DE VIDRIO Y VIDRERIA (ACUARIOS Y OTROS)-VENTA DE PRODUCTOS VETERINAROS (PECES,COMIDA Y ALIMENTOS DE MASCOTAS)&quot;
    },
    {
        &quot;gir_id&quot;: 1155,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISE&Ntilde;O DE PROYECTOS ACTIVIDADES DE ARQUITECTURA E INGENIERIA, CONSTRUCCION EN GENERAL Y VENTA DE MATERIALES DE CONSTRUCCION&quot;
    },
    {
        &quot;gir_id&quot;: 1174,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS VETERINARIOS Y VENTA DE MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1177,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONTABILIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1187,
        &quot;gir_descripcion&quot;: &quot;PARRILLADAS&quot;
    },
    {
        &quot;gir_id&quot;: 1205,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CAMARAS FOTOGRAFICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1206,
        &quot;gir_descripcion&quot;: &quot;ACCESORIOS FOTOGRAFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1207,
        &quot;gir_descripcion&quot;: &quot;CINTAS DE VIDEO&quot;
    },
    {
        &quot;gir_id&quot;: 1227,
        &quot;gir_descripcion&quot;: &quot;BAZAR CON VENTA DE ARTICULOS DE CUERO Y ARTESANIA&quot;
    },
    {
        &quot;gir_id&quot;: 1254,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UNIFORMES PARA EMPLEADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1295,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PIEZAS Y ACCESORIOS PARA VEHICULOS AUTOMOTRICES&quot;
    },
    {
        &quot;gir_id&quot;: 1297,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEHICULOS USADOS Y/O NUEVOS&quot;
    },
    {
        &quot;gir_id&quot;: 1299,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ANTIGUEDADES Y ARTESANIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1305,
        &quot;gir_descripcion&quot;: &quot;VENTA DE TELAS DE CORTINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1306,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE FOTOCOPIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1307,
        &quot;gir_descripcion&quot;: &quot;RECEPCION Y ENTREGA DE DINERO&quot;
    },
    {
        &quot;gir_id&quot;: 1308,
        &quot;gir_descripcion&quot;: &quot;ESTUDIO CONTABLE&quot;
    },
    {
        &quot;gir_id&quot;: 1309,
        &quot;gir_descripcion&quot;: &quot;VENTA DE SUMINISTROS DE COMPUTO&quot;
    },
    {
        &quot;gir_id&quot;: 1310,
        &quot;gir_descripcion&quot;: &quot;CARNES&quot;
    },
    {
        &quot;gir_id&quot;: 1313,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE HIGIENE PERSONAL&quot;
    },
    {
        &quot;gir_id&quot;: 1315,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS OPTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1316,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ARQUITECTURA, INGENIERIA Y AGRIMENSURA&quot;
    },
    {
        &quot;gir_id&quot;: 1317,
        &quot;gir_descripcion&quot;: &quot;BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 1318,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE FOTOCOPIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1321,
        &quot;gir_descripcion&quot;: &quot;VENTA Y DISE&Ntilde;O DE MUEBLES EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 1325,
        &quot;gir_descripcion&quot;: &quot;VENTA DE AUDIO-VIDEO&quot;
    },
    {
        &quot;gir_id&quot;: 1328,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS DEPORTIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 1329,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA&quot;
    },
    {
        &quot;gir_id&quot;: 1330,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTORIA MUNICIPAL, REPRESENTACION Y DISTRIBUCION DE PRODUCTOS DIVERSOS&quot;
    },
    {
        &quot;gir_id&quot;: 1331,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA Y ALMACEN DE MEDICAMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1332,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INTERMEDIACION FINANCIERA&quot;
    },
    {
        &quot;gir_id&quot;: 1333,
        &quot;gir_descripcion&quot;: &quot;VENTA Y ENSE&Ntilde;ANZA DE MANUALIDADES&quot;
    },
    {
        &quot;gir_id&quot;: 1334,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE ARTESANIA&quot;
    },
    {
        &quot;gir_id&quot;: 1339,
        &quot;gir_descripcion&quot;: &quot;ASESORIA A NIVEL SUPERIOR&quot;
    },
    {
        &quot;gir_id&quot;: 1340,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE COMPUTACION&quot;
    },
    {
        &quot;gir_id&quot;: 1341,
        &quot;gir_descripcion&quot;: &quot;CLINICA VETERINARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1342,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS FOTOGRAFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1343,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE VIDRIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1344,
        &quot;gir_descripcion&quot;: &quot;MARCOS&quot;
    },
    {
        &quot;gir_id&quot;: 1345,
        &quot;gir_descripcion&quot;: &quot;ESPECTACULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1346,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE COMPUTO&quot;
    },
    {
        &quot;gir_id&quot;: 1347,
        &quot;gir_descripcion&quot;: &quot;CONFECCION Y ARREGLO DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 1348,
        &quot;gir_descripcion&quot;: &quot;MODAS&quot;
    },
    {
        &quot;gir_id&quot;: 1349,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE MATERIALES LABORATORIO&quot;
    },
    {
        &quot;gir_id&quot;: 1350,
        &quot;gir_descripcion&quot;: &quot;PROMOTORAS Y PRODUCTORAS DE ESPECTACULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1351,
        &quot;gir_descripcion&quot;: &quot;VENTA DE JOYER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1352,
        &quot;gir_descripcion&quot;: &quot;VENTA DE DECORACION DE INTERIORES&quot;
    },
    {
        &quot;gir_id&quot;: 1353,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PRESTADOS DIRECTAMENTE O POR INTERMEDIO DE TERMINALES&quot;
    },
    {
        &quot;gir_id&quot;: 1354,
        &quot;gir_descripcion&quot;: &quot;PLAYA DE ESTACIONAMIENTO&quot;
    },
    {
        &quot;gir_id&quot;: 1355,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTORIA Y AUDITORIA&quot;
    },
    {
        &quot;gir_id&quot;: 1356,
        &quot;gir_descripcion&quot;: &quot;SERVICIO Y ARREGLO DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 1357,
        &quot;gir_descripcion&quot;: &quot;LICOR ENVASADO PARA LLEVAR&quot;
    },
    {
        &quot;gir_id&quot;: 1359,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO INICIAL Y PRIMARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1322,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1360,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE ESTETICA&quot;
    },
    {
        &quot;gir_id&quot;: 1361,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE AUDIFONOS&quot;
    },
    {
        &quot;gir_id&quot;: 1362,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRENDAS PARA DAMAS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1363,
        &quot;gir_descripcion&quot;: &quot;DECORACION PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 1364,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICOR ENVASADO PARA LLEVAR&quot;
    },
    {
        &quot;gir_id&quot;: 1365,
        &quot;gir_descripcion&quot;: &quot;EDUCACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 1366,
        &quot;gir_descripcion&quot;: &quot;ZAPATILLAS&quot;
    },
    {
        &quot;gir_id&quot;: 1367,
        &quot;gir_descripcion&quot;: &quot;PELOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1368,
        &quot;gir_descripcion&quot;: &quot;RAQUETAS&quot;
    },
    {
        &quot;gir_id&quot;: 1372,
        &quot;gir_descripcion&quot;: &quot;LICORES&quot;
    },
    {
        &quot;gir_id&quot;: 1376,
        &quot;gir_descripcion&quot;: &quot;CRISTALERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1377,
        &quot;gir_descripcion&quot;: &quot;SOMBREROS&quot;
    },
    {
        &quot;gir_id&quot;: 1378,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UNIFORMES PARA EMPLEADAS DOMESTICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1379,
        &quot;gir_descripcion&quot;: &quot;TOALLAS&quot;
    },
    {
        &quot;gir_id&quot;: 1381,
        &quot;gir_descripcion&quot;: &quot;BODEGA CON VENTA DE ABARROTES&quot;
    },
    {
        &quot;gir_id&quot;: 1382,
        &quot;gir_descripcion&quot;: &quot;VERDURAS Y FRUTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1383,
        &quot;gir_descripcion&quot;: &quot;INSTITUTO DE IDIOMAS&quot;
    },
    {
        &quot;gir_id&quot;: 1384,
        &quot;gir_descripcion&quot;: &quot;POLLERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1385,
        &quot;gir_descripcion&quot;: &quot;AVES BENEFICIADAS&quot;
    },
    {
        &quot;gir_id&quot;: 1389,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS&quot;
    },
    {
        &quot;gir_id&quot;: 1391,
        &quot;gir_descripcion&quot;: &quot;BODEGA CON VENTA DE FRUTA&quot;
    },
    {
        &quot;gir_id&quot;: 1392,
        &quot;gir_descripcion&quot;: &quot;OFICINA BANCARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1298,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPORTACI&Oacute;N DE FLORES&quot;
    },
    {
        &quot;gir_id&quot;: 1393,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS COMPLEMENTARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1394,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1395,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE CORREDORES DE SEGURO&quot;
    },
    {
        &quot;gir_id&quot;: 1397,
        &quot;gir_descripcion&quot;: &quot;ACCESO DE COMPUTO&quot;
    },
    {
        &quot;gir_id&quot;: 1398,
        &quot;gir_descripcion&quot;: &quot;EMPASTADO&quot;
    },
    {
        &quot;gir_id&quot;: 1399,
        &quot;gir_descripcion&quot;: &quot;LAVADO&quot;
    },
    {
        &quot;gir_id&quot;: 1400,
        &quot;gir_descripcion&quot;: &quot;ENGRASE&quot;
    },
    {
        &quot;gir_id&quot;: 1404,
        &quot;gir_descripcion&quot;: &quot;RECEPCION Y ENTREGA DE PRENDAS&quot;
    },
    {
        &quot;gir_id&quot;: 1405,
        &quot;gir_descripcion&quot;: &quot;LICORES ENVASADOS SIN CONSUMO&quot;
    },
    {
        &quot;gir_id&quot;: 1406,
        &quot;gir_descripcion&quot;: &quot;EMPASTADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1407,
        &quot;gir_descripcion&quot;: &quot;CONFECCIONES DE SELLO&quot;
    },
    {
        &quot;gir_id&quot;: 1408,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE FABRICACION Y DISE&Ntilde;O DE MUEBLES DE TODO TIPO&quot;
    },
    {
        &quot;gir_id&quot;: 1409,
        &quot;gir_descripcion&quot;: &quot;BOUTIQUE DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 1410,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS Y ACCESORIOS PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 1007,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES DIVERSOS&quot;
    },
    {
        &quot;gir_id&quot;: 1093,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VIDRIOS,ARTICULOS DE VIDRIO Y VIDRIERIA (ACUARIOS Y OTROS)-VENTA DE PRODUCTOS VETERINARIOS(PECES,COMIDA Y ALIMENTOS DE MASCOTAS)&quot;
    },
    {
        &quot;gir_id&quot;: 1178,
        &quot;gir_descripcion&quot;: &quot;DISE&Ntilde;O GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 1228,
        &quot;gir_descripcion&quot;: &quot;SALON DE TE&quot;
    },
    {
        &quot;gir_id&quot;: 1255,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS INMOBILIARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1256,
        &quot;gir_descripcion&quot;: &quot;PUBLICIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1257,
        &quot;gir_descripcion&quot;: &quot;TELECOMUNICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1296,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UTILES DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 1300,
        &quot;gir_descripcion&quot;: &quot;DECORACION DEL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 1311,
        &quot;gir_descripcion&quot;: &quot;TALLER DE BAILE DE FOLKLORE PERUANO&quot;
    },
    {
        &quot;gir_id&quot;: 1314,
        &quot;gir_descripcion&quot;: &quot;MERCERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1319,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE VETERINARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1323,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA CONSTRUCTORA Y ALQUILER DE EQUIPOS PARA CONSTRUCCION&quot;
    },
    {
        &quot;gir_id&quot;: 1326,
        &quot;gir_descripcion&quot;: &quot;ARTEFACTOS ELECTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1327,
        &quot;gir_descripcion&quot;: &quot;ELECTRONICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1335,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES CULTURALES&quot;
    },
    {
        &quot;gir_id&quot;: 1370,
        &quot;gir_descripcion&quot;: &quot;RELOJERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1373,
        &quot;gir_descripcion&quot;: &quot;AVES&quot;
    },
    {
        &quot;gir_id&quot;: 1386,
        &quot;gir_descripcion&quot;: &quot;LICORES ENVASADO SIN CONSUMO SOLO PARA LLEVAR&quot;
    },
    {
        &quot;gir_id&quot;: 1390,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS Y ARTICULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 1396,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE ENTRETENIMIENTO PARA NI&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 1402,
        &quot;gir_descripcion&quot;: &quot;VENTA DE INSTRUMENTOS MUSICALES&quot;
    },
    {
        &quot;gir_id&quot;: 1411,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PARTICULAR REINA DEL MUNDO&quot;
    },
    {
        &quot;gir_id&quot;: 1419,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA LA COMERCIALIZACION Y EXPORTACION DE PRODUCTOS HIDROBIOLOGICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1422,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISTRIBUCION DE AGREGADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1008,
        &quot;gir_descripcion&quot;: &quot;ESTACION DE SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1089,
        &quot;gir_descripcion&quot;: &quot;RESTAURANT,POLLO A LA BRASA,PARRILLADAS,VENTA DE LICOR COMO COMPLEMENTO&quot;
    },
    {
        &quot;gir_id&quot;: 1095,
        &quot;gir_descripcion&quot;: &quot;LIBRER&Iacute;A,BAZAR,BODEGA&quot;
    },
    {
        &quot;gir_id&quot;: 1301,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ADORNOS, CUADROS, ETC. EN GENERAL PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 1312,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE PUBLICIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1320,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS Y ALIMENTOS PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 1336,
        &quot;gir_descripcion&quot;: &quot;ENSE&Ntilde;ANZA DEL IDIOMA INGLES&quot;
    },
    {
        &quot;gir_id&quot;: 1371,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE BAZAR Y CALZADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1374,
        &quot;gir_descripcion&quot;: &quot;BEBIDAS ALCOHOLICAS SIN CONSUMO&quot;
    },
    {
        &quot;gir_id&quot;: 1387,
        &quot;gir_descripcion&quot;: &quot;CENTRO OPTICO&quot;
    },
    {
        &quot;gir_id&quot;: 1412,
        &quot;gir_descripcion&quot;: &quot;C.E.I. KINDERGARDEN&quot;
    },
    {
        &quot;gir_id&quot;: 1413,
        &quot;gir_descripcion&quot;: &quot;SERVICIO VETERINARIO&quot;
    },
    {
        &quot;gir_id&quot;: 1414,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACUACULTURA Y PESCA (CULTIVO DE CONCHAS DE ABANICO)&quot;
    },
    {
        &quot;gir_id&quot;: 1415,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE CONTABILIDAD DE COMERCIALIZACION DE PRODUCTOS HIDROBIOLOGICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1416,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA COMERCIALIZACION DE PRODUCTOS HIDROBIOLOGICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1418,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE ARTES MARCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 1420,
        &quot;gir_descripcion&quot;: &quot;NIDO&quot;
    },
    {
        &quot;gir_id&quot;: 1421,
        &quot;gir_descripcion&quot;: &quot;JARDIN&quot;
    },
    {
        &quot;gir_id&quot;: 1423,
        &quot;gir_descripcion&quot;: &quot;PRESTACION DE SERVICIOS PROFESIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 1424,
        &quot;gir_descripcion&quot;: &quot;CLUB SOCIAL Y DEPORTIVO&quot;
    },
    {
        &quot;gir_id&quot;: 1425,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS PARA BEBES&quot;
    },
    {
        &quot;gir_id&quot;: 1426,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEHICULOS MOTORIZADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1430,
        &quot;gir_descripcion&quot;: &quot;TALLER DE MECANICA AUTOMOTRIZ&quot;
    },
    {
        &quot;gir_id&quot;: 1432,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS AUTOMOTORES&quot;
    },
    {
        &quot;gir_id&quot;: 1433,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE CON LICOR COMO COMPLEMENTO DE COMIDA&quot;
    },
    {
        &quot;gir_id&quot;: 1369,
        &quot;gir_descripcion&quot;: &quot;JOYER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 1094,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA - VENTA DE ART&Iacute;CULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 1324,
        &quot;gir_descripcion&quot;: &quot;EDUCACI&Oacute;N UNIVERSITARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1380,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE EDUCACI&Oacute;N INICIAL&quot;
    },
    {
        &quot;gir_id&quot;: 1434,
        &quot;gir_descripcion&quot;: &quot;BELLEZA INTEGRAL&quot;
    },
    {
        &quot;gir_id&quot;: 1435,
        &quot;gir_descripcion&quot;: &quot;ESTETICA&quot;
    },
    {
        &quot;gir_id&quot;: 1440,
        &quot;gir_descripcion&quot;: &quot;REFRESCOS&quot;
    },
    {
        &quot;gir_id&quot;: 1441,
        &quot;gir_descripcion&quot;: &quot;SANDWICH SIN PREPARACION&quot;
    },
    {
        &quot;gir_id&quot;: 1442,
        &quot;gir_descripcion&quot;: &quot;ENMICADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1443,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE FILMACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1444,
        &quot;gir_descripcion&quot;: &quot;FOTOGRAFIA&quot;
    },
    {
        &quot;gir_id&quot;: 1445,
        &quot;gir_descripcion&quot;: &quot;DISE&Ntilde;O Y DESARROLLO DE PAGINAS WEB&quot;
    },
    {
        &quot;gir_id&quot;: 1447,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS Y ALIMENTOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 3101,
        &quot;gir_descripcion&quot;: &quot;COSTURERAS&quot;
    },
    {
        &quot;gir_id&quot;: 1452,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICOR  COMO COMPLEMENTO DE COMIDA&quot;
    },
    {
        &quot;gir_id&quot;: 1454,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS CON BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1456,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE FANTASIA&quot;
    },
    {
        &quot;gir_id&quot;: 1457,
        &quot;gir_descripcion&quot;: &quot;ARTESANIA&quot;
    },
    {
        &quot;gir_id&quot;: 1459,
        &quot;gir_descripcion&quot;: &quot;PRIMARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1460,
        &quot;gir_descripcion&quot;: &quot;SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1461,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE ARQUITECTURA&quot;
    },
    {
        &quot;gir_id&quot;: 1462,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS PARA NI&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 1463,
        &quot;gir_descripcion&quot;: &quot;VENTA DE GOLOSINAS Y UTILES DE ESCRITORIO&quot;
    },
    {
        &quot;gir_id&quot;: 1464,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRENDAS DE VESTIR PARA BEBES&quot;
    },
    {
        &quot;gir_id&quot;: 1465,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BICICLETAS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1467,
        &quot;gir_descripcion&quot;: &quot;VENTA DE AGUA EN BIDONES&quot;
    },
    {
        &quot;gir_id&quot;: 1468,
        &quot;gir_descripcion&quot;: &quot;VENTA DE TODO TIPO DE MALETAS Y MOCHILAS&quot;
    },
    {
        &quot;gir_id&quot;: 1471,
        &quot;gir_descripcion&quot;: &quot;CONFECCIONES DE DISFRACES&quot;
    },
    {
        &quot;gir_id&quot;: 1472,
        &quot;gir_descripcion&quot;: &quot;COMPOSTURA DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 1473,
        &quot;gir_descripcion&quot;: &quot;CARNICERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1475,
        &quot;gir_descripcion&quot;: &quot;BAZAR CON VENTA DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 1476,
        &quot;gir_descripcion&quot;: &quot;PRODUCTOS DE PERFUMERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1477,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LLANTAS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1516,
        &quot;gir_descripcion&quot;: &quot;PLANCHADO&quot;
    },
    {
        &quot;gir_id&quot;: 1401,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE M&Uacute;SICA&quot;
    },
    {
        &quot;gir_id&quot;: 1485,
        &quot;gir_descripcion&quot;: &quot;FUENTE DE SODA CON VENTA DE MENU&quot;
    },
    {
        &quot;gir_id&quot;: 1486,
        &quot;gir_descripcion&quot;: &quot;ROPA DEPORTIVA&quot;
    },
    {
        &quot;gir_id&quot;: 1487,
        &quot;gir_descripcion&quot;: &quot;CLASES DE MUSICA&quot;
    },
    {
        &quot;gir_id&quot;: 1488,
        &quot;gir_descripcion&quot;: &quot;PEDICURE Y VENTA DE ART&Iacute;CULOS DE TOCADOR Y BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1489,
        &quot;gir_descripcion&quot;: &quot;PEDICURE Y VENTA DE ARTICULOS DE TOCADOR Y BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1490,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTAS DE SERVICIOS CON COMERCIALIZACION DE EQUIPOS DE RASTREO SATELITAL&quot;
    },
    {
        &quot;gir_id&quot;: 1491,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE MARKETING Y PROMOCIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1492,
        &quot;gir_descripcion&quot;: &quot;ASESORIA DE ESTETICA&quot;
    },
    {
        &quot;gir_id&quot;: 1493,
        &quot;gir_descripcion&quot;: &quot;LLAMADAS TELEFONICAS E IMPRESIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1494,
        &quot;gir_descripcion&quot;: &quot;FOTO ESTUDIO&quot;
    },
    {
        &quot;gir_id&quot;: 1496,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA CON VENTA DE ARTICULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 1497,
        &quot;gir_descripcion&quot;: &quot;EQUIPAMIENTO&quot;
    },
    {
        &quot;gir_id&quot;: 1498,
        &quot;gir_descripcion&quot;: &quot;LICOR PARA LLEVAR&quot;
    },
    {
        &quot;gir_id&quot;: 1501,
        &quot;gir_descripcion&quot;: &quot;AVES BENEFICIADAS Y CARNES ROJAS&quot;
    },
    {
        &quot;gir_id&quot;: 1502,
        &quot;gir_descripcion&quot;: &quot;CON VENTA DE LICOR COMO COMPLEMENTO DE COMIDA&quot;
    },
    {
        &quot;gir_id&quot;: 1503,
        &quot;gir_descripcion&quot;: &quot;POLLERIA AL PASO&quot;
    },
    {
        &quot;gir_id&quot;: 1504,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 1505,
        &quot;gir_descripcion&quot;: &quot;CAMA&quot;
    },
    {
        &quot;gir_id&quot;: 1506,
        &quot;gir_descripcion&quot;: &quot;BA&Ntilde;O&quot;
    },
    {
        &quot;gir_id&quot;: 1507,
        &quot;gir_descripcion&quot;: &quot;MESA&quot;
    },
    {
        &quot;gir_id&quot;: 1508,
        &quot;gir_descripcion&quot;: &quot;PARA COMPUTO&quot;
    },
    {
        &quot;gir_id&quot;: 1509,
        &quot;gir_descripcion&quot;: &quot;MINIMARKET CON VENTA DE LICOR ENVASADO PARA LLEVAR&quot;
    },
    {
        &quot;gir_id&quot;: 1510,
        &quot;gir_descripcion&quot;: &quot;SERVICIO Y MANTENIMIENTO&quot;
    },
    {
        &quot;gir_id&quot;: 1512,
        &quot;gir_descripcion&quot;: &quot;AIRE ACONDICIONADO&quot;
    },
    {
        &quot;gir_id&quot;: 1513,
        &quot;gir_descripcion&quot;: &quot;CABALLEROS&quot;
    },
    {
        &quot;gir_id&quot;: 1524,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO DE TERAPIA FISICA Y REHABILITACION&quot;
    },
    {
        &quot;gir_id&quot;: 1525,
        &quot;gir_descripcion&quot;: &quot;ESTACION DE SERVICIOS CON GASOCENTRO DE GLP Y GNV&quot;
    },
    {
        &quot;gir_id&quot;: 1526,
        &quot;gir_descripcion&quot;: &quot;FERRETERIA CON ACABADOS DE CONSTRUCCION&quot;
    },
    {
        &quot;gir_id&quot;: 1529,
        &quot;gir_descripcion&quot;: &quot;ASESORIA ACADEMICA Y EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 1534,
        &quot;gir_descripcion&quot;: &quot;TRAGAMONEDAS&quot;
    },
    {
        &quot;gir_id&quot;: 1536,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICOR COMO COMPLEMENTO DE SERVICIO&quot;
    },
    {
        &quot;gir_id&quot;: 1537,
        &quot;gir_descripcion&quot;: &quot;VENTA DE AUTOMOVILES Y SERVICIO&quot;
    },
    {
        &quot;gir_id&quot;: 1541,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UTILES DE ESCRITORIO Y OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 1542,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE COPIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1543,
        &quot;gir_descripcion&quot;: &quot;ALQUILER DE COMPUTADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 1544,
        &quot;gir_descripcion&quot;: &quot;DECORACION Y VENTA DE CORTINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1545,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CELULARES&quot;
    },
    {
        &quot;gir_id&quot;: 1548,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACION DE COSMETICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1302,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA PARA NI&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 1303,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA DE MATERNIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1337,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE DIAGNOSTICO&quot;
    },
    {
        &quot;gir_id&quot;: 1375,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MUEBLES DE MELAMINE&quot;
    },
    {
        &quot;gir_id&quot;: 1388,
        &quot;gir_descripcion&quot;: &quot;ESTABLECIMIENTO DE ENSE&Ntilde;ANZA&quot;
    },
    {
        &quot;gir_id&quot;: 1417,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS HIDROBIOLOGICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1446,
        &quot;gir_descripcion&quot;: &quot;ESCUELA DE DANZAS Y ARTES MARCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 1448,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRENDAS DE VESTIR PARA DAMAS&quot;
    },
    {
        &quot;gir_id&quot;: 1449,
        &quot;gir_descripcion&quot;: &quot;TEJIDO DE CHOMPAS&quot;
    },
    {
        &quot;gir_id&quot;: 1453,
        &quot;gir_descripcion&quot;: &quot;SPA CON TRATAMIENTO FACIALES CORPORALES&quot;
    },
    {
        &quot;gir_id&quot;: 1455,
        &quot;gir_descripcion&quot;: &quot;SPA CON TRATAMIENTOS FACIALES CORPORALES&quot;
    },
    {
        &quot;gir_id&quot;: 1458,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO INICIAL&quot;
    },
    {
        &quot;gir_id&quot;: 1466,
        &quot;gir_descripcion&quot;: &quot;REPARACION DE BICICLETAS&quot;
    },
    {
        &quot;gir_id&quot;: 1469,
        &quot;gir_descripcion&quot;: &quot;ACCESORIOS DE VIAJE&quot;
    },
    {
        &quot;gir_id&quot;: 1474,
        &quot;gir_descripcion&quot;: &quot;LICORES ENVASADOS PARA LLEVAR&quot;
    },
    {
        &quot;gir_id&quot;: 1478,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE ENLLANTE&quot;
    },
    {
        &quot;gir_id&quot;: 1499,
        &quot;gir_descripcion&quot;: &quot;LICOR PARA LLEVAR SIN CONSUMO&quot;
    },
    {
        &quot;gir_id&quot;: 1511,
        &quot;gir_descripcion&quot;: &quot;REFRIGERACION&quot;
    },
    {
        &quot;gir_id&quot;: 1427,
        &quot;gir_descripcion&quot;: &quot;TALLER DE MEC&Aacute;NICA  AUTOMOTRIZ&quot;
    },
    {
        &quot;gir_id&quot;: 1530,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1495,
        &quot;gir_descripcion&quot;: &quot;ZAPATERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1009,
        &quot;gir_descripcion&quot;: &quot;SANDWICHER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1436,
        &quot;gir_descripcion&quot;: &quot;COSMIATR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1549,
        &quot;gir_descripcion&quot;: &quot;EXHIBICI&Oacute;N Y VENTA DE VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1514,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MANTENIMIENTO DE VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1523,
        &quot;gir_descripcion&quot;: &quot;MUEBLERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1527,
        &quot;gir_descripcion&quot;: &quot;BA&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 1528,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1531,
        &quot;gir_descripcion&quot;: &quot;REPUESTOS Y SERVICIO TECNICO MECANICA&quot;
    },
    {
        &quot;gir_id&quot;: 1535,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DROGUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1546,
        &quot;gir_descripcion&quot;: &quot;ACCESORIOS Y TARJETAS TELEFONICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1547,
        &quot;gir_descripcion&quot;: &quot;BAZAR CON VENTA DE AVES BENEFICIADAS&quot;
    },
    {
        &quot;gir_id&quot;: 1551,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE LUSTRADO DE VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 568,
        &quot;gir_descripcion&quot;: &quot;VIVEROS&quot;
    },
    {
        &quot;gir_id&quot;: 1011,
        &quot;gir_descripcion&quot;: &quot;FRUTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1090,
        &quot;gir_descripcion&quot;: &quot;VENTA DE GOLOSINAS Y GASEOSAS&quot;
    },
    {
        &quot;gir_id&quot;: 1304,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE LIMPIEZA CON PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1338,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA COMO EMPRESA CONSTRUCTORA&quot;
    },
    {
        &quot;gir_id&quot;: 1428,
        &quot;gir_descripcion&quot;: &quot;LAVADO DE VEHICULOS MOTORIZADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1437,
        &quot;gir_descripcion&quot;: &quot;ASESOR&Iacute;A DE EST&Eacute;TICA&quot;
    },
    {
        &quot;gir_id&quot;: 1470,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE REPARACION DE MALETAS Y MOCHILAS (S&Oacute;LO RECEPCI&Oacute;N Y ENTREGA)&quot;
    },
    {
        &quot;gir_id&quot;: 1479,
        &quot;gir_descripcion&quot;: &quot;PARCHADO Y BALANCEO DE RUEDAS&quot;
    },
    {
        &quot;gir_id&quot;: 1500,
        &quot;gir_descripcion&quot;: &quot;UTILES DE ESCRITORIO&quot;
    },
    {
        &quot;gir_id&quot;: 1515,
        &quot;gir_descripcion&quot;: &quot;AUTOMOTRIZ&quot;
    },
    {
        &quot;gir_id&quot;: 1518,
        &quot;gir_descripcion&quot;: &quot;REPUESTOS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1532,
        &quot;gir_descripcion&quot;: &quot;REPUESTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1539,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1012,
        &quot;gir_descripcion&quot;: &quot;VERDURAS&quot;
    },
    {
        &quot;gir_id&quot;: 1013,
        &quot;gir_descripcion&quot;: &quot;BODEGA CON VENTA DE LICOR PARA LLEVAR&quot;
    },
    {
        &quot;gir_id&quot;: 1092,
        &quot;gir_descripcion&quot;: &quot;VENTAS DE VIDRIOS,ARTICULOS DE VIDRIO Y VIDRIERIA (ACUARIOS Y OTROS)-VENTA DE PRODUCTOS VETERINARIOS(PECES,COMIDA Y ALIMENTOS DE MASCOTAS)&quot;
    },
    {
        &quot;gir_id&quot;: 1429,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS AUTOMOTRIZ&quot;
    },
    {
        &quot;gir_id&quot;: 1438,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA SPA&quot;
    },
    {
        &quot;gir_id&quot;: 1480,
        &quot;gir_descripcion&quot;: &quot;PLOTEOS&quot;
    },
    {
        &quot;gir_id&quot;: 1538,
        &quot;gir_descripcion&quot;: &quot;VENTA DE AUTOM&Oacute;VILES Y SERVICIO AUTOMOTRIZ&quot;
    },
    {
        &quot;gir_id&quot;: 1100,
        &quot;gir_descripcion&quot;: &quot;PODOLOG&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1517,
        &quot;gir_descripcion&quot;: &quot;PINTURA&quot;
    },
    {
        &quot;gir_id&quot;: 1522,
        &quot;gir_descripcion&quot;: &quot;ACABADOS DE CONSTRUCCION&quot;
    },
    {
        &quot;gir_id&quot;: 1540,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE RENT A CAR&quot;
    },
    {
        &quot;gir_id&quot;: 1554,
        &quot;gir_descripcion&quot;: &quot;SERVICIO AUTOMOTRIZ&quot;
    },
    {
        &quot;gir_id&quot;: 1096,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRENDAS DE VESTIR Y BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 1431,
        &quot;gir_descripcion&quot;: &quot;LAVADO  DE VEHICULOS MOTORIZADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1439,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA SPA&quot;
    },
    {
        &quot;gir_id&quot;: 1481,
        &quot;gir_descripcion&quot;: &quot;VENTA DE SELLOS&quot;
    },
    {
        &quot;gir_id&quot;: 1519,
        &quot;gir_descripcion&quot;: &quot;COMPRA Y VENTA DE VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1555,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA Y PRODUCTOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 1556,
        &quot;gir_descripcion&quot;: &quot;ENSERES&quot;
    },
    {
        &quot;gir_id&quot;: 1557,
        &quot;gir_descripcion&quot;: &quot;SIN VENTA DE CARNES NI AVES&quot;
    },
    {
        &quot;gir_id&quot;: 1558,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICORES&quot;
    },
    {
        &quot;gir_id&quot;: 1559,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ATICULOS DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 1560,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS ELECTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1562,
        &quot;gir_descripcion&quot;: &quot;MECANICA GENERAL DE VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1563,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA UNIVERSITARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1564,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA CON VENTA DE ARTICULOS PARA BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1565,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1566,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EM TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 1568,
        &quot;gir_descripcion&quot;: &quot;TOMA DE MUESTRAS&quot;
    },
    {
        &quot;gir_id&quot;: 1569,
        &quot;gir_descripcion&quot;: &quot;POLLOS BROASTER&quot;
    },
    {
        &quot;gir_id&quot;: 1570,
        &quot;gir_descripcion&quot;: &quot;ANTICUCHOS&quot;
    },
    {
        &quot;gir_id&quot;: 1578,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS ARTESANALES&quot;
    },
    {
        &quot;gir_id&quot;: 1579,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE REPARACION&quot;
    },
    {
        &quot;gir_id&quot;: 1580,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MATERIALES PARA ARTESANIA&quot;
    },
    {
        &quot;gir_id&quot;: 1581,
        &quot;gir_descripcion&quot;: &quot;SHOWS ARTISTICOS EN VIVO&quot;
    },
    {
        &quot;gir_id&quot;: 1582,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTESANIA Y JOYERIA EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 1583,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA DEPORTIVA&quot;
    },
    {
        &quot;gir_id&quot;: 1584,
        &quot;gir_descripcion&quot;: &quot;CONFECCION Y VENTA DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 1585,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRENDAS DE VESTIR PARA DAMAS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1586,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE ADMINISTRATIVA DE ACTIVIDADES&quot;
    },
    {
        &quot;gir_id&quot;: 1587,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE ARTE Y DECORACION&quot;
    },
    {
        &quot;gir_id&quot;: 1588,
        &quot;gir_descripcion&quot;: &quot;BANCO&quot;
    },
    {
        &quot;gir_id&quot;: 1589,
        &quot;gir_descripcion&quot;: &quot;BAZAR CON VENTA DE VERDURA&quot;
    },
    {
        &quot;gir_id&quot;: 1590,
        &quot;gir_descripcion&quot;: &quot;REPARACION Y MANTENIMIENTO DE EQUIPOS Y ELECTRONICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1592,
        &quot;gir_descripcion&quot;: &quot;VENTA  DE VERDURAS Y FRUTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1593,
        &quot;gir_descripcion&quot;: &quot;EVES BENEFICIADAS&quot;
    },
    {
        &quot;gir_id&quot;: 1594,
        &quot;gir_descripcion&quot;: &quot;COMIDA AL PASO&quot;
    },
    {
        &quot;gir_id&quot;: 1595,
        &quot;gir_descripcion&quot;: &quot;VENTA Y SERVICIOS DE TELEFONIA CELULAR&quot;
    },
    {
        &quot;gir_id&quot;: 1596,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRENDAS DE VESTIR Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1597,
        &quot;gir_descripcion&quot;: &quot;VENTA DE EQUIPOS DE AUDIO Y VIDEO&quot;
    },
    {
        &quot;gir_id&quot;: 1598,
        &quot;gir_descripcion&quot;: &quot;ARTEFACTOS ELECTRODOMESTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1599,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS FARMACEUTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1600,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE TOCADOR Y ASEO PERSONAL&quot;
    },
    {
        &quot;gir_id&quot;: 1601,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACION DE PRODUCTOS FARMACEUTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1602,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACION Y SERVICIOS TECNICOS DE ARTEFACTOS ELECTRONICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1603,
        &quot;gir_descripcion&quot;: &quot;TRATORIA&quot;
    },
    {
        &quot;gir_id&quot;: 1605,
        &quot;gir_descripcion&quot;: &quot;CHURRASQUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1607,
        &quot;gir_descripcion&quot;: &quot;BAZAR Y JUGUETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1608,
        &quot;gir_descripcion&quot;: &quot;MANTENIMIENTO DE EQUIPOS BIOMEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1609,
        &quot;gir_descripcion&quot;: &quot;VENTA DE GOLOSINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1611,
        &quot;gir_descripcion&quot;: &quot;BAGUETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1613,
        &quot;gir_descripcion&quot;: &quot;IMPORTACION&quot;
    },
    {
        &quot;gir_id&quot;: 1616,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS PARA DECORACION&quot;
    },
    {
        &quot;gir_id&quot;: 1617,
        &quot;gir_descripcion&quot;: &quot;MENAJE DE CASA&quot;
    },
    {
        &quot;gir_id&quot;: 1618,
        &quot;gir_descripcion&quot;: &quot;CONFECCION&quot;
    },
    {
        &quot;gir_id&quot;: 1622,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE LAVANDERIA COMO CENTO DE ACOPIO&quot;
    },
    {
        &quot;gir_id&quot;: 1624,
        &quot;gir_descripcion&quot;: &quot;RECEPCION DE ROPA Y COSTURA&quot;
    },
    {
        &quot;gir_id&quot;: 1625,
        &quot;gir_descripcion&quot;: &quot;MARQUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1627,
        &quot;gir_descripcion&quot;: &quot;BAILE&quot;
    },
    {
        &quot;gir_id&quot;: 1628,
        &quot;gir_descripcion&quot;: &quot;VENTA DE RAGALOS&quot;
    },
    {
        &quot;gir_id&quot;: 1450,
        &quot;gir_descripcion&quot;: &quot;TALLER DE MEC&Aacute;NICA - VENTA DE ACCESORIOS PARA VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1629,
        &quot;gir_descripcion&quot;: &quot;VENTA DE SISTEMA DE AGUA&quot;
    },
    {
        &quot;gir_id&quot;: 1630,
        &quot;gir_descripcion&quot;: &quot;MATERIALES AFINES&quot;
    },
    {
        &quot;gir_id&quot;: 1631,
        &quot;gir_descripcion&quot;: &quot;ACCESORIOS E IMPLEMENTACION&quot;
    },
    {
        &quot;gir_id&quot;: 1632,
        &quot;gir_descripcion&quot;: &quot;CARNES ROJAS&quot;
    },
    {
        &quot;gir_id&quot;: 1633,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MASAJES FACIALES&quot;
    },
    {
        &quot;gir_id&quot;: 1636,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CORTINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1637,
        &quot;gir_descripcion&quot;: &quot;SIN ELABORACION&quot;
    },
    {
        &quot;gir_id&quot;: 1639,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BOMBAS DE AGUA&quot;
    },
    {
        &quot;gir_id&quot;: 1642,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE CON VENTA DE LICOR COMO COMPLEMENTO&quot;
    },
    {
        &quot;gir_id&quot;: 1643,
        &quot;gir_descripcion&quot;: &quot;VENTA DE INSTRUMENTOS Y ACCESORIOS MUSICALES&quot;
    },
    {
        &quot;gir_id&quot;: 1645,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE IMPRESION&quot;
    },
    {
        &quot;gir_id&quot;: 1646,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ZAPATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1647,
        &quot;gir_descripcion&quot;: &quot;EXPORTACIONES DE FLORES&quot;
    },
    {
        &quot;gir_id&quot;: 1648,
        &quot;gir_descripcion&quot;: &quot;SASTRERIA CONFECCION&quot;
    },
    {
        &quot;gir_id&quot;: 1649,
        &quot;gir_descripcion&quot;: &quot;DELIVERY&quot;
    },
    {
        &quot;gir_id&quot;: 1650,
        &quot;gir_descripcion&quot;: &quot;ENSE&Ntilde;ANZA DE ARTES MARCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 1652,
        &quot;gir_descripcion&quot;: &quot;TAEKONGO&quot;
    },
    {
        &quot;gir_id&quot;: 1655,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 1657,
        &quot;gir_descripcion&quot;: &quot;TELECOMERCIO DE IMPORTACION Y DISTRIBUCION DE MATERIAL MEDICO&quot;
    },
    {
        &quot;gir_id&quot;: 1659,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS ELECTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1660,
        &quot;gir_descripcion&quot;: &quot;INMOBILIARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1661,
        &quot;gir_descripcion&quot;: &quot;IMPORTACIONES DE SISTEMAS LEVADIZOS&quot;
    },
    {
        &quot;gir_id&quot;: 1662,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO PSICOLOGICO&quot;
    },
    {
        &quot;gir_id&quot;: 1663,
        &quot;gir_descripcion&quot;: &quot;POLLERIA DELIVERY&quot;
    },
    {
        &quot;gir_id&quot;: 1664,
        &quot;gir_descripcion&quot;: &quot;COMIDA AL PALO&quot;
    },
    {
        &quot;gir_id&quot;: 1665,
        &quot;gir_descripcion&quot;: &quot;LICOR COMO COMPLEMENTO&quot;
    },
    {
        &quot;gir_id&quot;: 1666,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PUERTAS Y ACCESORIOS PARA BA&Ntilde;OS Y PERSIANAS&quot;
    },
    {
        &quot;gir_id&quot;: 1667,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS VETERINARIOS JARDINERIA Y PISCINA&quot;
    },
    {
        &quot;gir_id&quot;: 1668,
        &quot;gir_descripcion&quot;: &quot;SIN VENTA DE PRODUCTOS QUIMICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1671,
        &quot;gir_descripcion&quot;: &quot;VENTA DE TERRENOS&quot;
    },
    {
        &quot;gir_id&quot;: 1672,
        &quot;gir_descripcion&quot;: &quot;EDIFICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1673,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE ENSE&Ntilde;ANZA Y REPARACION&quot;
    },
    {
        &quot;gir_id&quot;: 1674,
        &quot;gir_descripcion&quot;: &quot;TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 1675,
        &quot;gir_descripcion&quot;: &quot;BOUTIQUE DE LENCERIA CON VENTA DE ACCESORIOS PARA ADULTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1676,
        &quot;gir_descripcion&quot;: &quot;ZAPATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1677,
        &quot;gir_descripcion&quot;: &quot;ACOPIO&quot;
    },
    {
        &quot;gir_id&quot;: 1678,
        &quot;gir_descripcion&quot;: &quot;PERFUMERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1680,
        &quot;gir_descripcion&quot;: &quot;LICOR COMO COMPLEMNTO DE COMIDA&quot;
    },
    {
        &quot;gir_id&quot;: 1670,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1561,
        &quot;gir_descripcion&quot;: &quot;REPARACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1567,
        &quot;gir_descripcion&quot;: &quot;LABORATORIO CL&Iacute;NICO&quot;
    },
    {
        &quot;gir_id&quot;: 1682,
        &quot;gir_descripcion&quot;: &quot;ODONTOLOGICO&quot;
    },
    {
        &quot;gir_id&quot;: 1683,
        &quot;gir_descripcion&quot;: &quot;AUTO SERVICIO&quot;
    },
    {
        &quot;gir_id&quot;: 1684,
        &quot;gir_descripcion&quot;: &quot;EMBUTIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 1482,
        &quot;gir_descripcion&quot;: &quot;SUMINISTROS Y ACCESORIOS DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 1520,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE TAXI&quot;
    },
    {
        &quot;gir_id&quot;: 1521,
        &quot;gir_descripcion&quot;: &quot;ESTACION&quot;
    },
    {
        &quot;gir_id&quot;: 1571,
        &quot;gir_descripcion&quot;: &quot;BELLEZA Y ESTETICA INTEGRAL&quot;
    },
    {
        &quot;gir_id&quot;: 1575,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS MEDICOS EN FORMA INDIVIDUAL&quot;
    },
    {
        &quot;gir_id&quot;: 1591,
        &quot;gir_descripcion&quot;: &quot;REPARACION Y MANTENIMIENTO DE EQUIPOS ELECTRICOS Y ELECTRONICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1604,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICOR COMO COMPLEMENTO DE COMIDA (VINOS)&quot;
    },
    {
        &quot;gir_id&quot;: 1610,
        &quot;gir_descripcion&quot;: &quot;SANGUCHES&quot;
    },
    {
        &quot;gir_id&quot;: 1612,
        &quot;gir_descripcion&quot;: &quot;HELADERIA Y SALON DE TE&quot;
    },
    {
        &quot;gir_id&quot;: 1614,
        &quot;gir_descripcion&quot;: &quot;DISTRIBUCION Y VENTA DE MATERIALES DE CONSTRUCCION&quot;
    },
    {
        &quot;gir_id&quot;: 1619,
        &quot;gir_descripcion&quot;: &quot;ARREGLOS DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 1620,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA PARA BEBES, NI&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 1623,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE LAVANDERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1626,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE LAVANDERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1634,
        &quot;gir_descripcion&quot;: &quot;ARREGLOS DE MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 1635,
        &quot;gir_descripcion&quot;: &quot;DEPILACION&quot;
    },
    {
        &quot;gir_id&quot;: 1638,
        &quot;gir_descripcion&quot;: &quot;ROPA Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1641,
        &quot;gir_descripcion&quot;: &quot;HIGIENE PERSONAL&quot;
    },
    {
        &quot;gir_id&quot;: 1644,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTEFACTOS ELECTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1651,
        &quot;gir_descripcion&quot;: &quot;KARATE&quot;
    },
    {
        &quot;gir_id&quot;: 1653,
        &quot;gir_descripcion&quot;: &quot;TAEKONDO&quot;
    },
    {
        &quot;gir_id&quot;: 1654,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORIA EN RECURSOS HUMANOS&quot;
    },
    {
        &quot;gir_id&quot;: 1656,
        &quot;gir_descripcion&quot;: &quot;FUENTE DE SODA SIN VENTA DE MENU&quot;
    },
    {
        &quot;gir_id&quot;: 1669,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UNIFORMES DE JARDINERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1679,
        &quot;gir_descripcion&quot;: &quot;VENTA DE HELADOS COMO COMPLEMENTO&quot;
    },
    {
        &quot;gir_id&quot;: 1681,
        &quot;gir_descripcion&quot;: &quot;LICOR COMO COMPLEMENTO DE COMIDA&quot;
    },
    {
        &quot;gir_id&quot;: 12,
        &quot;gir_descripcion&quot;: &quot;CLUB DE TIROS XXX&quot;
    },
    {
        &quot;gir_id&quot;: 1483,
        &quot;gir_descripcion&quot;: &quot;ALQUILER Y VENTA DE COPIADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 1572,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS PARA TRATAMIENTO DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1576,
        &quot;gir_descripcion&quot;: &quot;FUENDE DE SODA SIN VENTA DE MENU&quot;
    },
    {
        &quot;gir_id&quot;: 1615,
        &quot;gir_descripcion&quot;: &quot;ACABADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1621,
        &quot;gir_descripcion&quot;: &quot;FRUTAS Y VERDURAS&quot;
    },
    {
        &quot;gir_id&quot;: 1686,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA PARA DAMAS Y CABALLERO&quot;
    },
    {
        &quot;gir_id&quot;: 1687,
        &quot;gir_descripcion&quot;: &quot;FUENTA DE SODA&quot;
    },
    {
        &quot;gir_id&quot;: 1688,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BIJOUTERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1689,
        &quot;gir_descripcion&quot;: &quot;ABARROTES CON VENTA DE VERDURAS&quot;
    },
    {
        &quot;gir_id&quot;: 1690,
        &quot;gir_descripcion&quot;: &quot;ATICULOS PUBLICITARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1691,
        &quot;gir_descripcion&quot;: &quot;COMPRA Y VENTA DE INMUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 1692,
        &quot;gir_descripcion&quot;: &quot;COMPRA Y VENTA DE PIEZAS&quot;
    },
    {
        &quot;gir_id&quot;: 1693,
        &quot;gir_descripcion&quot;: &quot;EQUIPOS PARA AGUA&quot;
    },
    {
        &quot;gir_id&quot;: 1694,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS PUBLICITARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1695,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE INSTALACION Y REPARACION DE MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 1696,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICORES ENVASADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1699,
        &quot;gir_descripcion&quot;: &quot;HOSPEDAJE&quot;
    },
    {
        &quot;gir_id&quot;: 1700,
        &quot;gir_descripcion&quot;: &quot;SIN VENTA DE MENU NI LICORES&quot;
    },
    {
        &quot;gir_id&quot;: 1701,
        &quot;gir_descripcion&quot;: &quot;COSTURA DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 3102,
        &quot;gir_descripcion&quot;: &quot;ACADEMIAS DE KARATE&quot;
    },
    {
        &quot;gir_id&quot;: 1708,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1658,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 1573,
        &quot;gir_descripcion&quot;: &quot;ARTESAN&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 1710,
        &quot;gir_descripcion&quot;: &quot;BODEGA CON VENTA DE VERDURAS&quot;
    },
    {
        &quot;gir_id&quot;: 1711,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRENDAS&quot;
    },
    {
        &quot;gir_id&quot;: 1712,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE TERAPIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1,
        &quot;gir_descripcion&quot;: &quot;PUBS&quot;
    },
    {
        &quot;gir_id&quot;: 2,
        &quot;gir_descripcion&quot;: &quot;SALAS DE REUNIONES SOCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 3,
        &quot;gir_descripcion&quot;: &quot;CLUB SOCIAL (SEDE INSTITUCIONAL)&quot;
    },
    {
        &quot;gir_id&quot;: 4,
        &quot;gir_descripcion&quot;: &quot;SALA DE CONFERENCIAS&quot;
    },
    {
        &quot;gir_id&quot;: 5,
        &quot;gir_descripcion&quot;: &quot;GALER&Iacute;A DE ARTE&quot;
    },
    {
        &quot;gir_id&quot;: 6,
        &quot;gir_descripcion&quot;: &quot;ZOOLOGICOS&quot;
    },
    {
        &quot;gir_id&quot;: 7,
        &quot;gir_descripcion&quot;: &quot;OTROS SERVICIOS CULTURALES&quot;
    },
    {
        &quot;gir_id&quot;: 8,
        &quot;gir_descripcion&quot;: &quot;BIBLIOTECA&quot;
    },
    {
        &quot;gir_id&quot;: 9,
        &quot;gir_descripcion&quot;: &quot;JUEGOS DE AZAR&quot;
    },
    {
        &quot;gir_id&quot;: 10,
        &quot;gir_descripcion&quot;: &quot;CASINO&quot;
    },
    {
        &quot;gir_id&quot;: 11,
        &quot;gir_descripcion&quot;: &quot;TELEPODROMO&quot;
    },
    {
        &quot;gir_id&quot;: 13,
        &quot;gir_descripcion&quot;: &quot;SALON DE BAILE&quot;
    },
    {
        &quot;gir_id&quot;: 14,
        &quot;gir_descripcion&quot;: &quot;ALQUILER DE JUEGOS RECREATIVOS Y ELECTR&Oacute;NICOS.&quot;
    },
    {
        &quot;gir_id&quot;: 15,
        &quot;gir_descripcion&quot;: &quot;SALAS DE BILLAR&quot;
    },
    {
        &quot;gir_id&quot;: 16,
        &quot;gir_descripcion&quot;: &quot;PARQUE DE ATRACCIONES&quot;
    },
    {
        &quot;gir_id&quot;: 17,
        &quot;gir_descripcion&quot;: &quot;PROSTIBULOS, CASAS DE CITAS&quot;
    },
    {
        &quot;gir_id&quot;: 18,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE FUTBOL&quot;
    },
    {
        &quot;gir_id&quot;: 19,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DEPORTIVAS&quot;
    },
    {
        &quot;gir_id&quot;: 20,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE ESPARCIMIENTO&quot;
    },
    {
        &quot;gir_id&quot;: 21,
        &quot;gir_descripcion&quot;: &quot;PISTA DE PATINAJE&quot;
    },
    {
        &quot;gir_id&quot;: 22,
        &quot;gir_descripcion&quot;: &quot;TAPICERIA&quot;
    },
    {
        &quot;gir_id&quot;: 23,
        &quot;gir_descripcion&quot;: &quot;RENOVADORA DE CALZADO&quot;
    },
    {
        &quot;gir_id&quot;: 24,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS TECNICOS ELECTR&Oacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 25,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS TECNICOS ELECTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 26,
        &quot;gir_descripcion&quot;: &quot;TALLER DE ELECTR&Oacute;NICA&quot;
    },
    {
        &quot;gir_id&quot;: 27,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE REPARACI&Oacute;N DE COMPUTADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 28,
        &quot;gir_descripcion&quot;: &quot;REPARACIONES EL&Eacute;CTRICAS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 29,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE GASFITERIA&quot;
    },
    {
        &quot;gir_id&quot;: 30,
        &quot;gir_descripcion&quot;: &quot;REPARACI&Oacute;N DE MAQUINARIA DIVERSA&quot;
    },
    {
        &quot;gir_id&quot;: 31,
        &quot;gir_descripcion&quot;: &quot;REPARACI&Oacute;N DE AUTOMOVILES Y MOTOCICLETAS&quot;
    },
    {
        &quot;gir_id&quot;: 32,
        &quot;gir_descripcion&quot;: &quot;INSTALACION DE ACCESORIOS DE AUTOMOVILES&quot;
    },
    {
        &quot;gir_id&quot;: 33,
        &quot;gir_descripcion&quot;: &quot;REPARACI&Oacute;N DE RELOJES Y JOYAS&quot;
    },
    {
        &quot;gir_id&quot;: 34,
        &quot;gir_descripcion&quot;: &quot;REPARACI&Oacute;N DE BICICLETAS&quot;
    },
    {
        &quot;gir_id&quot;: 35,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE CERRAJERIA&quot;
    },
    {
        &quot;gir_id&quot;: 36,
        &quot;gir_descripcion&quot;: &quot;RECEPCI&Oacute;N Y ENTREGA DE SERVICIOS DE LAVANDER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 37,
        &quot;gir_descripcion&quot;: &quot;LAVANDER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 38,
        &quot;gir_descripcion&quot;: &quot;LAVANDER&Iacute;A AL PESO&quot;
    },
    {
        &quot;gir_id&quot;: 39,
        &quot;gir_descripcion&quot;: &quot;AUTOSERVICIO DE LAVANDER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 40,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE TOCADOR E HIGIENE PERSONAL&quot;
    },
    {
        &quot;gir_id&quot;: 41,
        &quot;gir_descripcion&quot;: &quot;PELUQUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 42,
        &quot;gir_descripcion&quot;: &quot;PODOLOGOS&quot;
    },
    {
        &quot;gir_id&quot;: 43,
        &quot;gir_descripcion&quot;: &quot;MANICURE&quot;
    },
    {
        &quot;gir_id&quot;: 44,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE LIMPIEZA&quot;
    },
    {
        &quot;gir_id&quot;: 45,
        &quot;gir_descripcion&quot;: &quot;CENTROS DE ESTETICA - TATUAJES&quot;
    },
    {
        &quot;gir_id&quot;: 46,
        &quot;gir_descripcion&quot;: &quot;CENTROS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 47,
        &quot;gir_descripcion&quot;: &quot;SALONES DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 48,
        &quot;gir_descripcion&quot;: &quot;PEDICURA&quot;
    },
    {
        &quot;gir_id&quot;: 49,
        &quot;gir_descripcion&quot;: &quot;SALONES DE MASAJES Y BA&Ntilde;OS TURCOS&quot;
    },
    {
        &quot;gir_id&quot;: 50,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE AER&Oacute;BICOS&quot;
    },
    {
        &quot;gir_id&quot;: 51,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE BALLET&quot;
    },
    {
        &quot;gir_id&quot;: 52,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE GIMNASIA&quot;
    },
    {
        &quot;gir_id&quot;: 53,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE NATACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 54,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE TENIS&quot;
    },
    {
        &quot;gir_id&quot;: 55,
        &quot;gir_descripcion&quot;: &quot;GIMNASIO&quot;
    },
    {
        &quot;gir_id&quot;: 56,
        &quot;gir_descripcion&quot;: &quot;CAMPOS DEPORTIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 57,
        &quot;gir_descripcion&quot;: &quot;ESTUDIOS FOTOGRAFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 58,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS FOTOGRAFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 59,
        &quot;gir_descripcion&quot;: &quot;CREMATORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 60,
        &quot;gir_descripcion&quot;: &quot;CEMENTERIOS&quot;
    },
    {
        &quot;gir_id&quot;: 61,
        &quot;gir_descripcion&quot;: &quot;FUNERARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 62,
        &quot;gir_descripcion&quot;: &quot;LAPIDAS Y SIMILARES&quot;
    },
    {
        &quot;gir_id&quot;: 63,
        &quot;gir_descripcion&quot;: &quot;SALONES DE VELATORIO&quot;
    },
    {
        &quot;gir_id&quot;: 64,
        &quot;gir_descripcion&quot;: &quot;SERVICIO FOTOCOPIADO&quot;
    },
    {
        &quot;gir_id&quot;: 65,
        &quot;gir_descripcion&quot;: &quot;COPIAS XEROX&quot;
    },
    {
        &quot;gir_id&quot;: 66,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE COPIAS DE PLANOS&quot;
    },
    {
        &quot;gir_id&quot;: 67,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS FOTOGR&Aacute;FICOS (LABORATORIO DE REVELADO)&quot;
    },
    {
        &quot;gir_id&quot;: 68,
        &quot;gir_descripcion&quot;: &quot;LABORATORIO TOPOGR&Aacute;FICO.&quot;
    },
    {
        &quot;gir_id&quot;: 69,
        &quot;gir_descripcion&quot;: &quot;ALQUILER DE VIDEO CASSETES&quot;
    },
    {
        &quot;gir_id&quot;: 70,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 71,
        &quot;gir_descripcion&quot;: &quot;ESTAMPADO DE MEDALLAS&quot;
    },
    {
        &quot;gir_id&quot;: 72,
        &quot;gir_descripcion&quot;: &quot;ACU&Ntilde;ACION DE MONEDAS&quot;
    },
    {
        &quot;gir_id&quot;: 73,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE INSTRUMENTOS DE MUSICA TALES COMO: PLANOS E INSTRUMENTOS DE VIENTO Y DE PERCUSI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 74,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE INSTRUMENTOS DE CUERDA&quot;
    },
    {
        &quot;gir_id&quot;: 75,
        &quot;gir_descripcion&quot;: &quot;CONFECCION DE TOLDOS, CARPAS Y SIMILARES&quot;
    },
    {
        &quot;gir_id&quot;: 76,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ARTICULOS DE DEPORTE Y ATLETISMO.&quot;
    },
    {
        &quot;gir_id&quot;: 77,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE PLUMAS ESTILOGR&Aacute;FICAS&quot;
    },
    {
        &quot;gir_id&quot;: 78,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE LAPICES&quot;
    },
    {
        &quot;gir_id&quot;: 79,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE PARAGUAS Y BASTONES&quot;
    },
    {
        &quot;gir_id&quot;: 80,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE LETREROS LUMINOSOS&quot;
    },
    {
        &quot;gir_id&quot;: 81,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE JUGUETES (EXCEPTO LOS HECHOS PRINCIPALMENTE DE CAUCHO, Y POR MOLDES O EXTRACCI&Oacute;N DE MATERIAL PLASTICO).&quot;
    },
    {
        &quot;gir_id&quot;: 82,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE ARTICULOS DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 83,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ARTICULOS PARA ARTISTAS&quot;
    },
    {
        &quot;gir_id&quot;: 84,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE JOYAS DE FANTASIA&quot;
    },
    {
        &quot;gir_id&quot;: 85,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ARTICULOS DE NOVEDAD&quot;
    },
    {
        &quot;gir_id&quot;: 86,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE PLUMAS&quot;
    },
    {
        &quot;gir_id&quot;: 87,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE FLORES ARTIFICIALES&quot;
    },
    {
        &quot;gir_id&quot;: 88,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ESCOBAS Y CEPILLOS&quot;
    },
    {
        &quot;gir_id&quot;: 89,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE PLACAS DE IDENTIFICACION&quot;
    },
    {
        &quot;gir_id&quot;: 90,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ESCARAPELAS&quot;
    },
    {
        &quot;gir_id&quot;: 91,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE EMBLEMAS&quot;
    },
    {
        &quot;gir_id&quot;: 92,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ROTULOS&quot;
    },
    {
        &quot;gir_id&quot;: 93,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE PANTALLAS PARA LAMPARAS&quot;
    },
    {
        &quot;gir_id&quot;: 94,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE PIPAS Y BOQUILLAS&quot;
    },
    {
        &quot;gir_id&quot;: 95,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE LETREROS Y ANUNCIOS DE PROPAGANDA&quot;
    },
    {
        &quot;gir_id&quot;: 96,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE SELLOS DE METAL, CAUCHO Y ESTENCIL&quot;
    },
    {
        &quot;gir_id&quot;: 97,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE REDES PARA PELO, PELUCAS Y ARTICULOS SIMILARES.&quot;
    },
    {
        &quot;gir_id&quot;: 98,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE TRANSPORTE URBANO&quot;
    },
    {
        &quot;gir_id&quot;: 99,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE TRANSPORTE SUBURBANO&quot;
    },
    {
        &quot;gir_id&quot;: 100,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE TRANSPORTE INTERURBANO&quot;
    },
    {
        &quot;gir_id&quot;: 101,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE TRANSPORTE DE PASAJEROS POR CARRETERA&quot;
    },
    {
        &quot;gir_id&quot;: 102,
        &quot;gir_descripcion&quot;: &quot;TERMINALES DE PASAJEROS&quot;
    },
    {
        &quot;gir_id&quot;: 103,
        &quot;gir_descripcion&quot;: &quot;EMBARCADEROS&quot;
    },
    {
        &quot;gir_id&quot;: 104,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE VENTAS DE PASAJES&quot;
    },
    {
        &quot;gir_id&quot;: 105,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE VENTAS DE PASAJES AEREOS&quot;
    },
    {
        &quot;gir_id&quot;: 106,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE VIAJES&quot;
    },
    {
        &quot;gir_id&quot;: 107,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 108,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS VINCULADOS AL TURISMO (SIN ALMACEN, NI TERMINAL).&quot;
    },
    {
        &quot;gir_id&quot;: 109,
        &quot;gir_descripcion&quot;: &quot;TERMINALES PARA CARGA&quot;
    },
    {
        &quot;gir_id&quot;: 110,
        &quot;gir_descripcion&quot;: &quot;EDIFICIOS DE ESTACIONAMIENTO&quot;
    },
    {
        &quot;gir_id&quot;: 111,
        &quot;gir_descripcion&quot;: &quot;PLAYAS DE ESTACIONAMIENTO&quot;
    },
    {
        &quot;gir_id&quot;: 112,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS RELACIONADOS CON EL TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 113,
        &quot;gir_descripcion&quot;: &quot;EMBALAJES DE CARGA&quot;
    },
    {
        &quot;gir_id&quot;: 114,
        &quot;gir_descripcion&quot;: &quot;GESTIONES VINCULADAS CON EL TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 115,
        &quot;gir_descripcion&quot;: &quot;REEXPEDICI&Oacute;N DE CARGA Y/O ENCOMIENDAS&quot;
    },
    {
        &quot;gir_id&quot;: 116,
        &quot;gir_descripcion&quot;: &quot;EMBALAJE DE CARGA&quot;
    },
    {
        &quot;gir_id&quot;: 117,
        &quot;gir_descripcion&quot;: &quot;GESTIONES  VINCULADAS CON EL TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 118,
        &quot;gir_descripcion&quot;: &quot;AGENTES DE TRANSPORTES MARITIMO&quot;
    },
    {
        &quot;gir_id&quot;: 119,
        &quot;gir_descripcion&quot;: &quot;AGENTES DE TRANSPORTES AEREO.&quot;
    },
    {
        &quot;gir_id&quot;: 120,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE ALMACENES REFRIGERADOS&quot;
    },
    {
        &quot;gir_id&quot;: 121,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE ALMACENES SIN REFRIGERAR&quot;
    },
    {
        &quot;gir_id&quot;: 122,
        &quot;gir_descripcion&quot;: &quot;INSTRUCCIONES MONETARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 123,
        &quot;gir_descripcion&quot;: &quot;BANCOS OFICINAS CENTRALES&quot;
    },
    {
        &quot;gir_id&quot;: 124,
        &quot;gir_descripcion&quot;: &quot;BANCOS SUCURSALES&quot;
    },
    {
        &quot;gir_id&quot;: 125,
        &quot;gir_descripcion&quot;: &quot;BANCOS AGENCIAS&quot;
    },
    {
        &quot;gir_id&quot;: 126,
        &quot;gir_descripcion&quot;: &quot;CAJERO AUTOMATICO&quot;
    },
    {
        &quot;gir_id&quot;: 127,
        &quot;gir_descripcion&quot;: &quot;CAJAS DE AHORRO Y BANCOS DE AHORRO&quot;
    },
    {
        &quot;gir_id&quot;: 128,
        &quot;gir_descripcion&quot;: &quot;MUTUALES&quot;
    },
    {
        &quot;gir_id&quot;: 129,
        &quot;gir_descripcion&quot;: &quot;FINANCIERAS DE CREDITO&quot;
    },
    {
        &quot;gir_id&quot;: 130,
        &quot;gir_descripcion&quot;: &quot;INSTITUCIONES DE CREDITO  QUE NO SON BANCOS TALES COMO:&quot;
    },
    {
        &quot;gir_id&quot;: 131,
        &quot;gir_descripcion&quot;: &quot;INSTITUCIONES DE CREDITO AGRICOLA, BANCOS DE FOMENTO INDUSTRIAL,&quot;
    },
    {
        &quot;gir_id&quot;: 132,
        &quot;gir_descripcion&quot;: &quot;INSTITUCIONES DE REDESCUENTO Y FINANCIACI&Oacute;N, INSTITUCIONES DE CREDITO PERSONAL&quot;
    },
    {
        &quot;gir_id&quot;: 133,
        &quot;gir_descripcion&quot;: &quot;Y CORRESPONSALES Y AGENTES DE INVERSIONES, COMISIONISTAS Y AGENTES DE PRESTAMO, COMPA&Ntilde;&Iacute;A FIDUCIARIAS Y&quot;
    },
    {
        &quot;gir_id&quot;: 134,
        &quot;gir_descripcion&quot;: &quot;CONSORCIO DE INVERSIONES, COMISIONISTAS AGENTES Y CASAS DE SUSCRIPCI&Oacute;N DE VALORES&quot;
    },
    {
        &quot;gir_id&quot;: 135,
        &quot;gir_descripcion&quot;: &quot;AGENCIAS DE BOLSA&quot;
    },
    {
        &quot;gir_id&quot;: 136,
        &quot;gir_descripcion&quot;: &quot;AGENCIAS DE CAMBIO&quot;
    },
    {
        &quot;gir_id&quot;: 137,
        &quot;gir_descripcion&quot;: &quot;EMPRESA DE INVESTIGACI&Oacute;N Y ASESORAMIENTO DE INVERSIONES&quot;
    },
    {
        &quot;gir_id&quot;: 138,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE COTIZACIONES DE VALORES Y EL ARRENDAMIENTO, COMPRA Y VENTA DE PATENTES Y LICENCIAS&quot;
    },
    {
        &quot;gir_id&quot;: 139,
        &quot;gir_descripcion&quot;: &quot;OFICINAS CENTRALES DE SEGUROS Y SERVICIOS CONEXOS&quot;
    },
    {
        &quot;gir_id&quot;: 140,
        &quot;gir_descripcion&quot;: &quot;AGENTES CORREDORES DE SEGUROS&quot;
    },
    {
        &quot;gir_id&quot;: 141,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS INMOBILIARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 142,
        &quot;gir_descripcion&quot;: &quot;ASESORIA EN COMERCIO EXTERIOR.&quot;
    },
    {
        &quot;gir_id&quot;: 143,
        &quot;gir_descripcion&quot;: &quot;NOTARIA&quot;
    },
    {
        &quot;gir_id&quot;: 144,
        &quot;gir_descripcion&quot;: &quot;ESTUDIO DE CONTADORES&quot;
    },
    {
        &quot;gir_id&quot;: 145,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE AUDITORIA&quot;
    },
    {
        &quot;gir_id&quot;: 146,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE ELABORACI&Oacute;N DE DATOS Y TABULACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 147,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE ASESORAMIENTO TECNICO.&quot;
    },
    {
        &quot;gir_id&quot;: 148,
        &quot;gir_descripcion&quot;: &quot;ASESORIA PARA LA CONSTRUCCION DE OBRAS DE INGENIERIA CIVIL&quot;
    },
    {
        &quot;gir_id&quot;: 149,
        &quot;gir_descripcion&quot;: &quot;GESTIONARIA DE TECNOLOGIA&quot;
    },
    {
        &quot;gir_id&quot;: 150,
        &quot;gir_descripcion&quot;: &quot;PROYECTOS Y DISE&Ntilde;OS DE MANTENIMIENTO ELECTROMECANICO&quot;
    },
    {
        &quot;gir_id&quot;: 151,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS ESPECIALIZADOS Y LOGISTICA&quot;
    },
    {
        &quot;gir_id&quot;: 152,
        &quot;gir_descripcion&quot;: &quot;ASESORAMIENTO EN PROYECTOS DE INGENIERIA&quot;
    },
    {
        &quot;gir_id&quot;: 153,
        &quot;gir_descripcion&quot;: &quot;ASISTENCIA TECNICA Y EQUIPAMIENTO MUNICIPAL&quot;
    },
    {
        &quot;gir_id&quot;: 154,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS ARQUITECT&Oacute;NICOS.&quot;
    },
    {
        &quot;gir_id&quot;: 155,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIA EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 156,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE PUBLICIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 157,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE PRESTAMOS A LAS EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 158,
        &quot;gir_descripcion&quot;: &quot;ALQUILER Y ARRENDAMIENTO  DE MAQUINA Y EQUIPO&quot;
    },
    {
        &quot;gir_id&quot;: 159,
        &quot;gir_descripcion&quot;: &quot;GOBIERNOS CENTRALES, PROVINCIALES, MUNICIPALES O LOCALES&quot;
    },
    {
        &quot;gir_id&quot;: 160,
        &quot;gir_descripcion&quot;: &quot;COMISARIA Y ESTACI&Oacute;N PIP&quot;
    },
    {
        &quot;gir_id&quot;: 161,
        &quot;gir_descripcion&quot;: &quot;CENTROS DE EDUCACION INICIAL&quot;
    },
    {
        &quot;gir_id&quot;: 162,
        &quot;gir_descripcion&quot;: &quot;CUNA Y JARDIN DE INFANCIA&quot;
    },
    {
        &quot;gir_id&quot;: 163,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE ENSE&Ntilde;ANZA&quot;
    },
    {
        &quot;gir_id&quot;: 164,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO&quot;
    },
    {
        &quot;gir_id&quot;: 165,
        &quot;gir_descripcion&quot;: &quot;UNIVERSIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 166,
        &quot;gir_descripcion&quot;: &quot;INSTITUTO TECNICO&quot;
    },
    {
        &quot;gir_id&quot;: 167,
        &quot;gir_descripcion&quot;: &quot;INSTITUTO SUPERIOR&quot;
    },
    {
        &quot;gir_id&quot;: 168,
        &quot;gir_descripcion&quot;: &quot;ENSE&Ntilde;ANZA Y PREPARACI&Oacute;N PRE - UNIVERSITARIA&quot;
    },
    {
        &quot;gir_id&quot;: 169,
        &quot;gir_descripcion&quot;: &quot;ARTES MARCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 170,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE BAILE&quot;
    },
    {
        &quot;gir_id&quot;: 171,
        &quot;gir_descripcion&quot;: &quot;ESCUELA PARA APRENDER A GUIAR VEHICULOS AUTOMOTORES&quot;
    },
    {
        &quot;gir_id&quot;: 172,
        &quot;gir_descripcion&quot;: &quot;DEPORTES AL AIRE LIBRE&quot;
    },
    {
        &quot;gir_id&quot;: 173,
        &quot;gir_descripcion&quot;: &quot;LABORATORIOS CLINICOS&quot;
    },
    {
        &quot;gir_id&quot;: 174,
        &quot;gir_descripcion&quot;: &quot;INVESTIGACIONES DE SALUD&quot;
    },
    {
        &quot;gir_id&quot;: 175,
        &quot;gir_descripcion&quot;: &quot;CLINICA MEDICA&quot;
    },
    {
        &quot;gir_id&quot;: 176,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO ODONTOL&Oacute;GICO&quot;
    },
    {
        &quot;gir_id&quot;: 177,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO MEDICO&quot;
    },
    {
        &quot;gir_id&quot;: 178,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO PSICOL&Oacute;GICO&quot;
    },
    {
        &quot;gir_id&quot;: 179,
        &quot;gir_descripcion&quot;: &quot;UNIDAD DE EMERGENCIAS MEDICAS&quot;
    },
    {
        &quot;gir_id&quot;: 180,
        &quot;gir_descripcion&quot;: &quot;CASA DE REPOSO&quot;
    },
    {
        &quot;gir_id&quot;: 181,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE REHABILITACI&Oacute;N MEDICA&quot;
    },
    {
        &quot;gir_id&quot;: 182,
        &quot;gir_descripcion&quot;: &quot;ATENCION DE TOPICOS&quot;
    },
    {
        &quot;gir_id&quot;: 183,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS VINCULADOS A LA SALUD&quot;
    },
    {
        &quot;gir_id&quot;: 617,
        &quot;gir_descripcion&quot;: &quot;MASAJES FACIALES&quot;
    },
    {
        &quot;gir_id&quot;: 184,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO VETERINARIO&quot;
    },
    {
        &quot;gir_id&quot;: 185,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 186,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA DE ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 187,
        &quot;gir_descripcion&quot;: &quot;CRUZ ROJA&quot;
    },
    {
        &quot;gir_id&quot;: 188,
        &quot;gir_descripcion&quot;: &quot;ORGANIZACIONES DEDICADAS A LA COLECTA Y DISTRIBUCION DE DONATIVOS PARA FINES BENEFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 189,
        &quot;gir_descripcion&quot;: &quot;COMERCIALES Y  ORGANIZACIONES DE AGRICULTURA&quot;
    },
    {
        &quot;gir_id&quot;: 190,
        &quot;gir_descripcion&quot;: &quot;TALLERES DE ARTESANIAS DE LOZA&quot;
    },
    {
        &quot;gir_id&quot;: 191,
        &quot;gir_descripcion&quot;: &quot;CLASES DE ARTESANIAS EN ARCILLA ROJA SIN VIDRIAR&quot;
    },
    {
        &quot;gir_id&quot;: 192,
        &quot;gir_descripcion&quot;: &quot;CLASES DE ARTESANIAS EN BARRO&quot;
    },
    {
        &quot;gir_id&quot;: 193,
        &quot;gir_descripcion&quot;: &quot;CLASES DE ARTESANIAS EN PIEDRA&quot;
    },
    {
        &quot;gir_id&quot;: 194,
        &quot;gir_descripcion&quot;: &quot;CLASES DE ARTESANIAS EN PORCELANA&quot;
    },
    {
        &quot;gir_id&quot;: 195,
        &quot;gir_descripcion&quot;: &quot;CLASES DE ARTESANIAS EN LOZA&quot;
    },
    {
        &quot;gir_id&quot;: 196,
        &quot;gir_descripcion&quot;: &quot;CLASES ARTESANALES&quot;
    },
    {
        &quot;gir_id&quot;: 197,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE VIDRIO Y PRODUCTOS DE VIDRIO, FIBRAS DE VIDRIO&quot;
    },
    {
        &quot;gir_id&quot;: 198,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS DE ARCILLA PARA CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 199,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE CEMENTO, CAL, Y YESO&quot;
    },
    {
        &quot;gir_id&quot;: 200,
        &quot;gir_descripcion&quot;: &quot;EXTRACCI&Oacute;N MINERA NO MET&Aacute;LICA&quot;
    },
    {
        &quot;gir_id&quot;: 201,
        &quot;gir_descripcion&quot;: &quot;CHANCADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 202,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS HECHOS DE PIZARRA&quot;
    },
    {
        &quot;gir_id&quot;: 203,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE PRODUCTOS DE PIEDRA TALLADA&quot;
    },
    {
        &quot;gir_id&quot;: 204,
        &quot;gir_descripcion&quot;: &quot;PRODUCTOS DE GRAFITO&quot;
    },
    {
        &quot;gir_id&quot;: 205,
        &quot;gir_descripcion&quot;: &quot;INDUSTRIAS B&Aacute;SICAS DE HIERRO Y ACERO&quot;
    },
    {
        &quot;gir_id&quot;: 206,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS PRIMARIOS DE METALES  NO FERROSOS A PARTIR DE LA FUNDICI&Oacute;N, ALEACI&Oacute;N, REFINACI&Oacute;N,&quot;
    },
    {
        &quot;gir_id&quot;: 207,
        &quot;gir_descripcion&quot;: &quot;LAMINACI&Oacute;N Y ESTIRADO DE FABRICA DE HIERRO FUNDIDO Y COLADO&quot;
    },
    {
        &quot;gir_id&quot;: 208,
        &quot;gir_descripcion&quot;: &quot;PRODUCCI&Oacute;N DE ALUMINIOS A BASE DE BAUXITA&quot;
    },
    {
        &quot;gir_id&quot;: 209,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE CUCHILLER&Iacute;A DE TODAS LAS CLASES Y HERRAMIENTAS MANUALES, HACHAS, CINCELES, LIMAS,&quot;
    },
    {
        &quot;gir_id&quot;: 210,
        &quot;gir_descripcion&quot;: &quot;MARTILLO Y OTRAS HERRAMIENTAS PARA EL CAMPO Y JARDIN&quot;
    },
    {
        &quot;gir_id&quot;: 283,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE AERONAVES&quot;
    },
    {
        &quot;gir_id&quot;: 211,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE SIERRAS DE MANO Y HERRAMIENTAS DE PLOMERO, ALBA&Ntilde;IL MEC&Aacute;NICO, ETC.&quot;
    },
    {
        &quot;gir_id&quot;: 212,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ELEMENTOS ESTRUCTURALES DE ACERO Y OTRO METAL PARA PUENTES, DEP&Oacute;SITOS Y CHIMENEAS&quot;
    },
    {
        &quot;gir_id&quot;: 213,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCI&Oacute;N DE M&Aacute;QUINAS Y APARATOS INDUSTRIALES EL&Eacute;CTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 214,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS DE TORNILLER&Iacute;A, CAJAS FUERTES Y C&Aacute;MARAS DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 215,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS DE FERRETER&Iacute;A TALES COMO EQUIPO DE CHIMENEA, SOPORTES, CERRADURAS, LLAVES Y OTROS&quot;
    },
    {
        &quot;gir_id&quot;: 216,
        &quot;gir_descripcion&quot;: &quot;ELEMENTOS DE EDIFICIOS Y MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 217,
        &quot;gir_descripcion&quot;: &quot;PROTECTORES, PINZAS, MALETER&Iacute;A Y HERRAJES DE EMBARCACIONES Y VEHICULOS, SE INCLUYE LAS HERRER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 218,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE MUEBLES Y ACCESORIOS PRINCIPALMENTE METALICOS&quot;
    },
    {
        &quot;gir_id&quot;: 219,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ESCALERAS Y OTROS ELEMENTOS DE METAL&quot;
    },
    {
        &quot;gir_id&quot;: 220,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCION O ENSAMBLADO DE MAQUINAS DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 221,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCION O ENSAMBLADO DE MAQUINAS DE CALCULO&quot;
    },
    {
        &quot;gir_id&quot;: 222,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCION O ENSAMBLADO DE MAQUINAS DE CONTABILIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 223,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE EQUIPO PROFESIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 224,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE EQUIPO CIENTIFICO&quot;
    },
    {
        &quot;gir_id&quot;: 225,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE INSTRUMENTOS DE MEDICI&Oacute;N Y CONTROL&quot;
    },
    {
        &quot;gir_id&quot;: 226,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE APARATOS FOTOGRAFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 227,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE INSTRUMENTOS DE OPTICA&quot;
    },
    {
        &quot;gir_id&quot;: 228,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE INSTRUMENTOS DE RELOJES&quot;
    },
    {
        &quot;gir_id&quot;: 229,
        &quot;gir_descripcion&quot;: &quot;TALLER DE ARTESANIA&quot;
    },
    {
        &quot;gir_id&quot;: 230,
        &quot;gir_descripcion&quot;: &quot;CLASES DE ARTESANIAS&quot;
    },
    {
        &quot;gir_id&quot;: 231,
        &quot;gir_descripcion&quot;: &quot;CARPINTERIA DE ALUMINIO&quot;
    },
    {
        &quot;gir_id&quot;: 232,
        &quot;gir_descripcion&quot;: &quot;CARPINTERIA METALICA&quot;
    },
    {
        &quot;gir_id&quot;: 233,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS MET&Aacute;LICOS TAMBORES COMO ENVASES MET&Aacute;LICOS DE HOJALATA: CONTAINERS MET&Aacute;LICOS, TAMBORES, ETC.&quot;
    },
    {
        &quot;gir_id&quot;: 234,
        &quot;gir_descripcion&quot;: &quot;PRODUCTOS DE CABLE DE ALAMBRE HECHOS CON VARILLAS COMPRADAS EXCEPTO CABLES Y ALAMBRES CON AISLAMIENTO,&quot;
    },
    {
        &quot;gir_id&quot;: 235,
        &quot;gir_descripcion&quot;: &quot;RESORTES DE ACERO&quot;
    },
    {
        &quot;gir_id&quot;: 236,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS SANITARIOS Y DE PLOMERIA DE HIERRO ESMALTADO Y DE LAT&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 237,
        &quot;gir_descripcion&quot;: &quot;HERRAJES DE V&Aacute;LVULAS Y TUBER&Iacute;AS; PRODUCTOS MET&Aacute;LICOS PEQUE&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 238,
        &quot;gir_descripcion&quot;: &quot;INDUSTRIAS QUE SE DEDICAN A ESMALTAR, BARNIZAR, LAQUEAR Y GALVANIZAR, CHAPAR Y PULIR ARTICULOS MET&Aacute;LICOS&quot;
    },
    {
        &quot;gir_id&quot;: 239,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCI&Oacute;N DE MOTORES Y TURBINAS&quot;
    },
    {
        &quot;gir_id&quot;: 240,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCI&Oacute;N DE MAQUINARIAS PARA LA AGRICULTURA&quot;
    },
    {
        &quot;gir_id&quot;: 241,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCI&Oacute;N DE MAQUINARIAS PARA TRABAJAR LOS METALES Y LAS MADERAS&quot;
    },
    {
        &quot;gir_id&quot;: 242,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCI&Oacute;N DE MAQUINARIAS Y EQUIPOS ESPECIALES PARA LAS INDUSTRIAS&quot;
    },
    {
        &quot;gir_id&quot;: 243,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCI&Oacute;N DE MAQUINARIAS PARA LEVANTAR E IZAR ARTICULOS, ARMAS PORT&Aacute;TILES Y ACCESORIOS DE&quot;
    },
    {
        &quot;gir_id&quot;: 244,
        &quot;gir_descripcion&quot;: &quot;ARTILLERIA PESADA LIGERA&quot;
    },
    {
        &quot;gir_id&quot;: 245,
        &quot;gir_descripcion&quot;: &quot;HORNOS PARA PROCESOS INDUSTRIALES&quot;
    },
    {
        &quot;gir_id&quot;: 246,
        &quot;gir_descripcion&quot;: &quot;MAQUINAS AUTOMATICAS DE VENDER PRODUCTOS,&quot;
    },
    {
        &quot;gir_id&quot;: 247,
        &quot;gir_descripcion&quot;: &quot;MAQUINARIAS DE LAVAR, FABRICACI&Oacute;N DE PIEZAS DE MAQUINARIAS PARA USO GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 248,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCI&Oacute;N DE EQUIPOS FERROVIARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 249,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE VEHICULOS AUTOM&Oacute;VILES&quot;
    },
    {
        &quot;gir_id&quot;: 250,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE BOMBAS&quot;
    },
    {
        &quot;gir_id&quot;: 1940,
        &quot;gir_descripcion&quot;: &quot;TALLERES&quot;
    },
    {
        &quot;gir_id&quot;: 251,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE COMPRESORAS DE AIRE&quot;
    },
    {
        &quot;gir_id&quot;: 252,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE COMPRESORAS DE GAS&quot;
    },
    {
        &quot;gir_id&quot;: 253,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE SOPLADORES&quot;
    },
    {
        &quot;gir_id&quot;: 254,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ACONDICIONADORES DE AIRE&quot;
    },
    {
        &quot;gir_id&quot;: 255,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE VENTILADORES&quot;
    },
    {
        &quot;gir_id&quot;: 256,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ROCIADORES CONTRA INCENDIO&quot;
    },
    {
        &quot;gir_id&quot;: 257,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE REFRIGERADORES&quot;
    },
    {
        &quot;gir_id&quot;: 258,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE MAQUINAS DE COSER&quot;
    },
    {
        &quot;gir_id&quot;: 259,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ACUMULADORES&quot;
    },
    {
        &quot;gir_id&quot;: 260,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE RECEPTORES DE RADIO&quot;
    },
    {
        &quot;gir_id&quot;: 261,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE RECEPTORES DE TV.&quot;
    },
    {
        &quot;gir_id&quot;: 262,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE EQUIPOS DE GRABACION&quot;
    },
    {
        &quot;gir_id&quot;: 263,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE REPRODUCTORES DE SONIDO&quot;
    },
    {
        &quot;gir_id&quot;: 264,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE DISCOS GRAMOFONOS&quot;
    },
    {
        &quot;gir_id&quot;: 265,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE EQUIPOS DE TELEFONO&quot;
    },
    {
        &quot;gir_id&quot;: 266,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE EQUIPO DE TELEGRAFOS ALAMBICO &Eacute; INALAMBRICO&quot;
    },
    {
        &quot;gir_id&quot;: 267,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE EQUIPOS Y APARATOS DE TRANSMISI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 268,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE EQUIPOS DE SE&Ntilde;ALIZACI&Oacute;N Y DETENCI&Oacute;N DE RADIO Y TV.&quot;
    },
    {
        &quot;gir_id&quot;: 269,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE EQUIPOS DE INSTALACIONES DE RADAR&quot;
    },
    {
        &quot;gir_id&quot;: 270,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE SEMICONDUCTORES CONEXOS&quot;
    },
    {
        &quot;gir_id&quot;: 271,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE CAPACITADORES Y CONDENSADORES FIJOS Y MOVILES&quot;
    },
    {
        &quot;gir_id&quot;: 272,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE APARATOS Y VALVULAS DE RADIOGRAFIA&quot;
    },
    {
        &quot;gir_id&quot;: 273,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE APARATOS Y VALVULAS DE FLUOROSCOPIA&quot;
    },
    {
        &quot;gir_id&quot;: 274,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE APARATOS Y VALVULAS DE RAYOS X.&quot;
    },
    {
        &quot;gir_id&quot;: 275,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCION DE APARATOS Y ACCESORIOS ELECTRICOS DE USO DOMESTICO&quot;
    },
    {
        &quot;gir_id&quot;: 276,
        &quot;gir_descripcion&quot;: &quot;GRABADO DE CINTAS MAGNETOF&Oacute;NICAS (CASSETTES).&quot;
    },
    {
        &quot;gir_id&quot;: 277,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE APARATOS, ACCESORIOS Y SUMINISTROS;: TALES COMO CABLES Y ALAMBRES CON AISLAMIENTO, Y PILAS ELECTRICAS&quot;
    },
    {
        &quot;gir_id&quot;: 278,
        &quot;gir_descripcion&quot;: &quot;APLIQUES ELECTRICOS Y ENCHUFES DE LAMPARAS; INTERRUPTORES DE RESORTE&quot;
    },
    {
        &quot;gir_id&quot;: 279,
        &quot;gir_descripcion&quot;: &quot;CONECTADORES DE CABLES Y OTROS DISPOSITIVOS ALAMBRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 280,
        &quot;gir_descripcion&quot;: &quot;PORTADORES DE CORRIENTE&quot;
    },
    {
        &quot;gir_id&quot;: 281,
        &quot;gir_descripcion&quot;: &quot;TUBOS AISLANTES Y SUS ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 282,
        &quot;gir_descripcion&quot;: &quot;CALENTADORES Y/O THERMAS&quot;
    },
    {
        &quot;gir_id&quot;: 284,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE MOTOCICLETAS Y BICICLETAS&quot;
    },
    {
        &quot;gir_id&quot;: 285,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCI&Oacute;N DE MATERIALES DE TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 286,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE JOYAS&quot;
    },
    {
        &quot;gir_id&quot;: 287,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE PLATERIA&quot;
    },
    {
        &quot;gir_id&quot;: 288,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ARTICULOS QUE UTILIZA METALES PRECIOSOS, PIEDRAS PRECIOSAS, SEMIPRECIOSAS Y PERLAS&quot;
    },
    {
        &quot;gir_id&quot;: 289,
        &quot;gir_descripcion&quot;: &quot;CORTE DE PIEDRAS PRECIOSAS Y SEMIPRECIOSAS.&quot;
    },
    {
        &quot;gir_id&quot;: 290,
        &quot;gir_descripcion&quot;: &quot;TALLADO DE PIEDRAS PRECIOSAS Y SEMIPRECIOSAS&quot;
    },
    {
        &quot;gir_id&quot;: 291,
        &quot;gir_descripcion&quot;: &quot;PULIDO DE PIEDRAS PRECIOSAS Y SEMIPRECIOSAS&quot;
    },
    {
        &quot;gir_id&quot;: 292,
        &quot;gir_descripcion&quot;: &quot;REPRESENTACIONES DE PRODUCTOS AGROPECUARIOS (CON ATENCI&Oacute;N AL PUBLICO)&quot;
    },
    {
        &quot;gir_id&quot;: 293,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PERFILES DE ACERO&quot;
    },
    {
        &quot;gir_id&quot;: 294,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PERFILES DE ACETILENO&quot;
    },
    {
        &quot;gir_id&quot;: 295,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REACTIVOS QUIMICOS&quot;
    },
    {
        &quot;gir_id&quot;: 296,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MATERIALES DE CONSTRUCCI&Oacute;N PESADO&quot;
    },
    {
        &quot;gir_id&quot;: 297,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MADERA ASERRADA&quot;
    },
    {
        &quot;gir_id&quot;: 298,
        &quot;gir_descripcion&quot;: &quot;RECARGA DE EXTINGUIDORES&quot;
    },
    {
        &quot;gir_id&quot;: 299,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PERFILES DE ALUMINIO&quot;
    },
    {
        &quot;gir_id&quot;: 300,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS Y PLANCHAS DE ACRILICOS&quot;
    },
    {
        &quot;gir_id&quot;: 301,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MATERIALES DE CONSTRUCCI&Oacute;N  - ACABADOS&quot;
    },
    {
        &quot;gir_id&quot;: 302,
        &quot;gir_descripcion&quot;: &quot;ALMACENES DE GASEOSAS (DEPOSITO)&quot;
    },
    {
        &quot;gir_id&quot;: 303,
        &quot;gir_descripcion&quot;: &quot;ALMACENES DE CERVEZA  (DEPOSITO)&quot;
    },
    {
        &quot;gir_id&quot;: 304,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PERFILES  DE ALUMINIO&quot;
    },
    {
        &quot;gir_id&quot;: 305,
        &quot;gir_descripcion&quot;: &quot;VENTA DE TRIPLAY Y ENCHAPES&quot;
    },
    {
        &quot;gir_id&quot;: 306,
        &quot;gir_descripcion&quot;: &quot;REFRIGERACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 307,
        &quot;gir_descripcion&quot;: &quot;AIRE CONDICIONADO&quot;
    },
    {
        &quot;gir_id&quot;: 308,
        &quot;gir_descripcion&quot;: &quot;CUEROS Y SIMILARES&quot;
    },
    {
        &quot;gir_id&quot;: 309,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE RADIO&quot;
    },
    {
        &quot;gir_id&quot;: 310,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS ELECTR&Oacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 311,
        &quot;gir_descripcion&quot;: &quot;TALLER DE ARTICULOS DE DECORACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 312,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N DE SISTEMAS DE AIRE CONDICIONADO (CON VENTA )&quot;
    },
    {
        &quot;gir_id&quot;: 313,
        &quot;gir_descripcion&quot;: &quot;EXTINGUIDORES (VENTA)&quot;
    },
    {
        &quot;gir_id&quot;: 314,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MAQUINARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 315,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS Y MAQUINARIAS PARA LA CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 316,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS Y MAQUINARIAS PARA LA INDUSTRIA&quot;
    },
    {
        &quot;gir_id&quot;: 317,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS AUTOMOTRICES&quot;
    },
    {
        &quot;gir_id&quot;: 318,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE MAQUINARIAS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 319,
        &quot;gir_descripcion&quot;: &quot;VENTA DE AUTOPARTES&quot;
    },
    {
        &quot;gir_id&quot;: 320,
        &quot;gir_descripcion&quot;: &quot;AUTOBOUTIQUE&quot;
    },
    {
        &quot;gir_id&quot;: 321,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS PARA AUTOS&quot;
    },
    {
        &quot;gir_id&quot;: 322,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 323,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS DE MAQUINARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 324,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MAQUINARIA AGRICOLA&quot;
    },
    {
        &quot;gir_id&quot;: 325,
        &quot;gir_descripcion&quot;: &quot;VENTA DE EQUIPOS Y ACCESORIOS PARA LA CONSTRUCCI&Oacute;N COMERCIALIZACI&Oacute;N  DE EQUIPOS DE PISCINAS&quot;
    },
    {
        &quot;gir_id&quot;: 326,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE EQUIPOS PARA PISCINAS&quot;
    },
    {
        &quot;gir_id&quot;: 327,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE EQUIPOS PARA AGUA&quot;
    },
    {
        &quot;gir_id&quot;: 328,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ELECTROBOMBAS&quot;
    },
    {
        &quot;gir_id&quot;: 405,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ABONOS Y/O FERTILIZANTES&quot;
    },
    {
        &quot;gir_id&quot;: 329,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACION DE ARMAS MUNICIONES Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 330,
        &quot;gir_descripcion&quot;: &quot;MERCADOS&quot;
    },
    {
        &quot;gir_id&quot;: 331,
        &quot;gir_descripcion&quot;: &quot;SUPERMERCADOS&quot;
    },
    {
        &quot;gir_id&quot;: 332,
        &quot;gir_descripcion&quot;: &quot;MINIMARKETS&quot;
    },
    {
        &quot;gir_id&quot;: 333,
        &quot;gir_descripcion&quot;: &quot;MINIMERCADO&quot;
    },
    {
        &quot;gir_id&quot;: 334,
        &quot;gir_descripcion&quot;: &quot;MERCADO DE ABASTOS&quot;
    },
    {
        &quot;gir_id&quot;: 335,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE ALIMENTOS (SIN DISTRIBUCI&Oacute;N NI ALMACEN)&quot;
    },
    {
        &quot;gir_id&quot;: 336,
        &quot;gir_descripcion&quot;: &quot;ABARROTES&quot;
    },
    {
        &quot;gir_id&quot;: 338,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BEBIDAS ALCOH&Oacute;LICAS PARA LLEVAR (ESPECIAL)&quot;
    },
    {
        &quot;gir_id&quot;: 339,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BEBIDAS ALCOH&Oacute;LICAS COMO ACOMPA&Ntilde;AMIENTO (ESPECIAL)&quot;
    },
    {
        &quot;gir_id&quot;: 340,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICORES ENVASADOS PARA LLEVAR&quot;
    },
    {
        &quot;gir_id&quot;: 341,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS AVICOLAS&quot;
    },
    {
        &quot;gir_id&quot;: 342,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS LACTEOS&quot;
    },
    {
        &quot;gir_id&quot;: 343,
        &quot;gir_descripcion&quot;: &quot;CARNICER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 344,
        &quot;gir_descripcion&quot;: &quot;VENTA DE EMBUTIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 345,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VERDURAS&quot;
    },
    {
        &quot;gir_id&quot;: 346,
        &quot;gir_descripcion&quot;: &quot;AGENTES DE COMPRA - VENTA DE MERCADER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 347,
        &quot;gir_descripcion&quot;: &quot;INTERMEDIARIOS  MAYORISTAS O REVENDEDORES&quot;
    },
    {
        &quot;gir_id&quot;: 348,
        &quot;gir_descripcion&quot;: &quot;DISTRIBUIDORES  INDUSTRIALES&quot;
    },
    {
        &quot;gir_id&quot;: 349,
        &quot;gir_descripcion&quot;: &quot;DISTRIBUIDORES COMERCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 350,
        &quot;gir_descripcion&quot;: &quot;EXPORTADORES   E  IMPORTADORES&quot;
    },
    {
        &quot;gir_id&quot;: 351,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDAD DE EMPRESARIOS  DE SILOS TERMINALES&quot;
    },
    {
        &quot;gir_id&quot;: 352,
        &quot;gir_descripcion&quot;: &quot;COOPERATIVA DE COMPRAS&quot;
    },
    {
        &quot;gir_id&quot;: 353,
        &quot;gir_descripcion&quot;: &quot;OFICINA  Y SUCURSALES  DE VENTA  DE LAS EMPRESAS MANUFACTURERAS O  MINERALES&quot;
    },
    {
        &quot;gir_id&quot;: 354,
        &quot;gir_descripcion&quot;: &quot;CORREDORES DE MERCADERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 355,
        &quot;gir_descripcion&quot;: &quot;CORREDORES DE PRODUCTOS PRIMARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 356,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N DE JUGUETES&quot;
    },
    {
        &quot;gir_id&quot;: 357,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N  DE EQUIPOS  Y MAQUINARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 358,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N  Y  EXPORTACI&Oacute;N  DE  ARTESANIAS&quot;
    },
    {
        &quot;gir_id&quot;: 359,
        &quot;gir_descripcion&quot;: &quot;DISTRIBUCI&Oacute;N DE  CASSETES&quot;
    },
    {
        &quot;gir_id&quot;: 360,
        &quot;gir_descripcion&quot;: &quot;DISTRIBUCI&Oacute;N  DE  DISCOS COMPACTOS (SENCILLOS Y LASER)&quot;
    },
    {
        &quot;gir_id&quot;: 361,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE CASSETS&quot;
    },
    {
        &quot;gir_id&quot;: 362,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE DISCOS COMPACTOS&quot;
    },
    {
        &quot;gir_id&quot;: 363,
        &quot;gir_descripcion&quot;: &quot;DISTRIAV. JAVIER PRADO ESTE NRO. 5840 - URB. LA FONTANA         BUCI&Oacute;N DE PRODUCTOS DE BELLEZA Y SALUD&quot;
    },
    {
        &quot;gir_id&quot;: 364,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N  DE PRODUCTOS DE BELLEZA Y SALUD&quot;
    },
    {
        &quot;gir_id&quot;: 365,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE ARTICULOS FERRETEROS&quot;
    },
    {
        &quot;gir_id&quot;: 366,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE LIBROS&quot;
    },
    {
        &quot;gir_id&quot;: 367,
        &quot;gir_descripcion&quot;: &quot;REPRESENTACI&Oacute;N DE SERVICIOS  Y PROMOCI&Oacute;N  DE  ACTIVIDADES  MINERALES  Y COMERCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 368,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE PRODUCTOS PARA LA CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 369,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE PRODUCTOS PARA LA MINER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 370,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE PRODUCTOS FERRETEROS&quot;
    },
    {
        &quot;gir_id&quot;: 371,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE PRODUCTOS MADEREROS&quot;
    },
    {
        &quot;gir_id&quot;: 372,
        &quot;gir_descripcion&quot;: &quot;DISTRIBUCI&Oacute;N  DE PRODUCTOS ENLATADOS DE CONSERVA DE PESCADO&quot;
    },
    {
        &quot;gir_id&quot;: 373,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE PRODUCTOS QUIMICOS&quot;
    },
    {
        &quot;gir_id&quot;: 577,
        &quot;gir_descripcion&quot;: &quot;LOCER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 374,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE EQUIPOS Y ACCESORIOS PARA EQUIPOS  DE AGUA&quot;
    },
    {
        &quot;gir_id&quot;: 375,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE PLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 376,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE ALFOMBRAS&quot;
    },
    {
        &quot;gir_id&quot;: 377,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE CONFECCIONES&quot;
    },
    {
        &quot;gir_id&quot;: 378,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N Y/O EXPORTACI&Oacute;N DE ARMAS, MUNICIONES Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 379,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE TABACOS Y AFINES&quot;
    },
    {
        &quot;gir_id&quot;: 380,
        &quot;gir_descripcion&quot;: &quot;COMERCIANTES A COMISI&Oacute;N Y COMISIONISTAS&quot;
    },
    {
        &quot;gir_id&quot;: 381,
        &quot;gir_descripcion&quot;: &quot;ACOPIADORES DE PRODUCTOS AGRICOLAS&quot;
    },
    {
        &quot;gir_id&quot;: 382,
        &quot;gir_descripcion&quot;: &quot;COOPERATIVA DE COMERCIANTES DE PRODUCTOS AGRICOLAS&quot;
    },
    {
        &quot;gir_id&quot;: 383,
        &quot;gir_descripcion&quot;: &quot;COMERCIANTES DE PRODUCTOS AGRICOLAS&quot;
    },
    {
        &quot;gir_id&quot;: 384,
        &quot;gir_descripcion&quot;: &quot;ACOPIO DE MERCADER&Iacute;A EN GRANDES LOTES&quot;
    },
    {
        &quot;gir_id&quot;: 385,
        &quot;gir_descripcion&quot;: &quot;AGRUPACI&Oacute;N DE MERCADER&Iacute;AS EN GRANDES LOTES&quot;
    },
    {
        &quot;gir_id&quot;: 386,
        &quot;gir_descripcion&quot;: &quot;TALLER DE MANUALIDADES&quot;
    },
    {
        &quot;gir_id&quot;: 387,
        &quot;gir_descripcion&quot;: &quot;REEMBALAJE DE PRODUCTOS&quot;
    },
    {
        &quot;gir_id&quot;: 388,
        &quot;gir_descripcion&quot;: &quot;EMBOTELLADO DE PRODUCTOS&quot;
    },
    {
        &quot;gir_id&quot;: 389,
        &quot;gir_descripcion&quot;: &quot;ALMACENAMIENTO DE PRODUCTOS&quot;
    },
    {
        &quot;gir_id&quot;: 390,
        &quot;gir_descripcion&quot;: &quot;REFRIGERACI&Oacute;N DE PRODUCTOS&quot;
    },
    {
        &quot;gir_id&quot;: 391,
        &quot;gir_descripcion&quot;: &quot;DEPOSITO DE MATERIALES DE CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 392,
        &quot;gir_descripcion&quot;: &quot;VENTA  DE MANUALIDADES&quot;
    },
    {
        &quot;gir_id&quot;: 393,
        &quot;gir_descripcion&quot;: &quot;ALMACEN DE MEDICAMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 394,
        &quot;gir_descripcion&quot;: &quot;ALMACENAJE DE PRODUCTOS QUIMICOS&quot;
    },
    {
        &quot;gir_id&quot;: 395,
        &quot;gir_descripcion&quot;: &quot;COMISIONISTAS  DE  DIARIOS O SIMILARES&quot;
    },
    {
        &quot;gir_id&quot;: 396,
        &quot;gir_descripcion&quot;: &quot;COMERCIANTES DE CHATARRA&quot;
    },
    {
        &quot;gir_id&quot;: 397,
        &quot;gir_descripcion&quot;: &quot;ESTABLECIMIENTOS DE CHATARRA&quot;
    },
    {
        &quot;gir_id&quot;: 398,
        &quot;gir_descripcion&quot;: &quot;ESTABLECIMIENTOS DE DESPERDICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 399,
        &quot;gir_descripcion&quot;: &quot;COMERCIANTES DE MATERIALES  DE DESECHO (CARTON Y PAPELES)&quot;
    },
    {
        &quot;gir_id&quot;: 400,
        &quot;gir_descripcion&quot;: &quot;COMERCIANTES DE MATERIALES  DE DESECHO (VIDRIOS)&quot;
    },
    {
        &quot;gir_id&quot;: 401,
        &quot;gir_descripcion&quot;: &quot;COMERCIANTES DE MATERIALES  DE DESECHO (PLASTICOS)&quot;
    },
    {
        &quot;gir_id&quot;: 402,
        &quot;gir_descripcion&quot;: &quot;COMERCIANTES DE MATERIALES  DE DESECHO (ALUMINIO Y METALES)&quot;
    },
    {
        &quot;gir_id&quot;: 403,
        &quot;gir_descripcion&quot;: &quot;COMERCIANTES DE DESECHOS&quot;
    },
    {
        &quot;gir_id&quot;: 404,
        &quot;gir_descripcion&quot;: &quot;PLANTAS ENVASADORAS DE GAS LICUADO  DE PETROLEO&quot;
    },
    {
        &quot;gir_id&quot;: 1713,
        &quot;gir_descripcion&quot;: &quot;F&Iacute;SICA&quot;
    },
    {
        &quot;gir_id&quot;: 406,
        &quot;gir_descripcion&quot;: &quot;VENTA DE SEMILLAS&quot;
    },
    {
        &quot;gir_id&quot;: 407,
        &quot;gir_descripcion&quot;: &quot;VENTA DE IMPLEMENTOS AGRICOLAS&quot;
    },
    {
        &quot;gir_id&quot;: 408,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALIMENTOS AL POR MAYOR&quot;
    },
    {
        &quot;gir_id&quot;: 409,
        &quot;gir_descripcion&quot;: &quot;VENTA DE FRUTAS&quot;
    },
    {
        &quot;gir_id&quot;: 410,
        &quot;gir_descripcion&quot;: &quot;VENTA DE AVES BENEFICIADAS&quot;
    },
    {
        &quot;gir_id&quot;: 411,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CARNES ROJAS&quot;
    },
    {
        &quot;gir_id&quot;: 412,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS ALIMENTICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 414,
        &quot;gir_descripcion&quot;: &quot;PANADERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 415,
        &quot;gir_descripcion&quot;: &quot;LECHERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 417,
        &quot;gir_descripcion&quot;: &quot;BAGUETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 418,
        &quot;gir_descripcion&quot;: &quot;PASTELER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 420,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BEBIDAS REFRESCANTES (ENVASADAS)&quot;
    },
    {
        &quot;gir_id&quot;: 421,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PAN&quot;
    },
    {
        &quot;gir_id&quot;: 422,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PASTAS ALIMENTICIAS&quot;
    },
    {
        &quot;gir_id&quot;: 423,
        &quot;gir_descripcion&quot;: &quot;VIVERO (SIN VENTA DE ABONO Y FERTILIZANTES)&quot;
    },
    {
        &quot;gir_id&quot;: 424,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PLANTAS (SIN VENTA DE ABONO Y FERTILIZANTES)&quot;
    },
    {
        &quot;gir_id&quot;: 425,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS NATURISTAS&quot;
    },
    {
        &quot;gir_id&quot;: 426,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE PLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 427,
        &quot;gir_descripcion&quot;: &quot;VENTA DE HELADOS PARA LLEVAR&quot;
    },
    {
        &quot;gir_id&quot;: 428,
        &quot;gir_descripcion&quot;: &quot;ELABORACION DE TORTAS&quot;
    },
    {
        &quot;gir_id&quot;: 429,
        &quot;gir_descripcion&quot;: &quot;HELADER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 430,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS ELECTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 431,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS ELECTRONICOS AUTOMOTRICES&quot;
    },
    {
        &quot;gir_id&quot;: 432,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS ELECTRONICOS&quot;
    },
    {
        &quot;gir_id&quot;: 433,
        &quot;gir_descripcion&quot;: &quot;FERRETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 434,
        &quot;gir_descripcion&quot;: &quot;VIDRIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 435,
        &quot;gir_descripcion&quot;: &quot;VENTA  DE PINTURA&quot;
    },
    {
        &quot;gir_id&quot;: 436,
        &quot;gir_descripcion&quot;: &quot;MATIZADO DE PINTURA&quot;
    },
    {
        &quot;gir_id&quot;: 437,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PLASTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 438,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COMPUTADORAS Y PERIFERICOS&quot;
    },
    {
        &quot;gir_id&quot;: 439,
        &quot;gir_descripcion&quot;: &quot;DISCOS Y CASSETES&quot;
    },
    {
        &quot;gir_id&quot;: 440,
        &quot;gir_descripcion&quot;: &quot;OPTICAS&quot;
    },
    {
        &quot;gir_id&quot;: 441,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE ENVASES PLASTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 442,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE EQUIPOS E INSTRUMENTAL  MEDICO&quot;
    },
    {
        &quot;gir_id&quot;: 443,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N  DE MATERIALES DE LABORATORIO&quot;
    },
    {
        &quot;gir_id&quot;: 444,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE SUMINISTROS DE COMPUTO&quot;
    },
    {
        &quot;gir_id&quot;: 445,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE REPUESTOS Y ACCESORIOS PARA COMPUTADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 446,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE HERRAMIENTAS&quot;
    },
    {
        &quot;gir_id&quot;: 447,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ESPEJOS Y CUADROS&quot;
    },
    {
        &quot;gir_id&quot;: 448,
        &quot;gir_descripcion&quot;: &quot;VENTA DE EQUIPOS ELECTRONICOS&quot;
    },
    {
        &quot;gir_id&quot;: 449,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE ARTICULOS OPTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 450,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE EQUIPOS DE TELECOMUNICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 451,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE LIMPIEZA&quot;
    },
    {
        &quot;gir_id&quot;: 452,
        &quot;gir_descripcion&quot;: &quot;CONFECCI&Oacute;N DE VITRALES&quot;
    },
    {
        &quot;gir_id&quot;: 453,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VITRALES&quot;
    },
    {
        &quot;gir_id&quot;: 454,
        &quot;gir_descripcion&quot;: &quot;VENTA DE DISCOS COMPACTOS Y LASER&quot;
    },
    {
        &quot;gir_id&quot;: 455,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VIDEO CASSETES&quot;
    },
    {
        &quot;gir_id&quot;: 456,
        &quot;gir_descripcion&quot;: &quot;VENTA DE EQUIPOS TELEF&Oacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 457,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LUBRICANTES ENVASADOS (SIN SERVICIO DE CAMBIO)&quot;
    },
    {
        &quot;gir_id&quot;: 458,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACEITE ENVASADO (SIN SERVICIO DE CAMBIO)&quot;
    },
    {
        &quot;gir_id&quot;: 459,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS ELECTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 460,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS ELECTRICOS AUTOMOTRICES&quot;
    },
    {
        &quot;gir_id&quot;: 461,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 462,
        &quot;gir_descripcion&quot;: &quot;VENTA DE EQUIPOS DE COMUNICACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 463,
        &quot;gir_descripcion&quot;: &quot;VENTA DE OLLAS, MENAJE Y UTENSILIOS INDUSTRIALES Y DEL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 464,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE FIERROS&quot;
    },
    {
        &quot;gir_id&quot;: 465,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE OXIGENO, ACETILENO Y AFINES&quot;
    },
    {
        &quot;gir_id&quot;: 466,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CERRAJERIA&quot;
    },
    {
        &quot;gir_id&quot;: 467,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MUEBLES DE ACERO&quot;
    },
    {
        &quot;gir_id&quot;: 468,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 469,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MUEBLES EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 470,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MUEBLES DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 471,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ELECTRODOMESTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 472,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LINEA BLANCA&quot;
    },
    {
        &quot;gir_id&quot;: 473,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS ELECTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 474,
        &quot;gir_descripcion&quot;: &quot;MUEBLER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 475,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COLCHONES&quot;
    },
    {
        &quot;gir_id&quot;: 476,
        &quot;gir_descripcion&quot;: &quot;ALQUILER DE TOLDOS&quot;
    },
    {
        &quot;gir_id&quot;: 477,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BICICLETAS&quot;
    },
    {
        &quot;gir_id&quot;: 478,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE MUSICA&quot;
    },
    {
        &quot;gir_id&quot;: 479,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS PARA BICICLETAS&quot;
    },
    {
        &quot;gir_id&quot;: 480,
        &quot;gir_descripcion&quot;: &quot;FARMACIAS&quot;
    },
    {
        &quot;gir_id&quot;: 481,
        &quot;gir_descripcion&quot;: &quot;LIBRER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 482,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ANTIG&Uuml;EDADES (SIN TALLER)&quot;
    },
    {
        &quot;gir_id&quot;: 483,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTESANIAS (SIN TALLER)&quot;
    },
    {
        &quot;gir_id&quot;: 484,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE ESCRITORIO&quot;
    },
    {
        &quot;gir_id&quot;: 485,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LIBROS&quot;
    },
    {
        &quot;gir_id&quot;: 486,
        &quot;gir_descripcion&quot;: &quot;ANILLADOS&quot;
    },
    {
        &quot;gir_id&quot;: 487,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE FIERRO FORJADO, ALUMINIO Y/O ACEDO INOXIDABLE&quot;
    },
    {
        &quot;gir_id&quot;: 488,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 489,
        &quot;gir_descripcion&quot;: &quot;BOTICA&quot;
    },
    {
        &quot;gir_id&quot;: 491,
        &quot;gir_descripcion&quot;: &quot;VENTA DE TELAS&quot;
    },
    {
        &quot;gir_id&quot;: 492,
        &quot;gir_descripcion&quot;: &quot;PASAMANER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 493,
        &quot;gir_descripcion&quot;: &quot;ZAPATER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 494,
        &quot;gir_descripcion&quot;: &quot;BOUTIQUES&quot;
    },
    {
        &quot;gir_id&quot;: 495,
        &quot;gir_descripcion&quot;: &quot;TIENDA POR DEPARTAMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 496,
        &quot;gir_descripcion&quot;: &quot;JOYER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 3012,
        &quot;gir_descripcion&quot;: &quot;DULCERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 497,
        &quot;gir_descripcion&quot;: &quot;VENTA Y/O ALQUILER DE VESTIDOS DE NOVIA&quot;
    },
    {
        &quot;gir_id&quot;: 498,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE REMALLADO&quot;
    },
    {
        &quot;gir_id&quot;: 499,
        &quot;gir_descripcion&quot;: &quot;PERFUMER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 500,
        &quot;gir_descripcion&quot;: &quot;RELOJER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 501,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 502,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DEPORTIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 503,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LENCER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 504,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BIJOUTER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 505,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REGALOS&quot;
    },
    {
        &quot;gir_id&quot;: 506,
        &quot;gir_descripcion&quot;: &quot;SASTRES&quot;
    },
    {
        &quot;gir_id&quot;: 507,
        &quot;gir_descripcion&quot;: &quot;ARREGLO DE ROPA&quot;
    },
    {
        &quot;gir_id&quot;: 508,
        &quot;gir_descripcion&quot;: &quot;CRISTALER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 509,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE ALFOMBRAS&quot;
    },
    {
        &quot;gir_id&quot;: 510,
        &quot;gir_descripcion&quot;: &quot;PLATER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 511,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE DECORACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 512,
        &quot;gir_descripcion&quot;: &quot;VENTA DE TAPICES&quot;
    },
    {
        &quot;gir_id&quot;: 513,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALFOMBRAS&quot;
    },
    {
        &quot;gir_id&quot;: 514,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ADORNOS&quot;
    },
    {
        &quot;gir_id&quot;: 515,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LAMPARAS&quot;
    },
    {
        &quot;gir_id&quot;: 516,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS ORTOPEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 517,
        &quot;gir_descripcion&quot;: &quot;VENTA DE GASOLINA&quot;
    },
    {
        &quot;gir_id&quot;: 518,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LUBRICANTES DERIVADOS DEL PETROLEO&quot;
    },
    {
        &quot;gir_id&quot;: 519,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LUBRICANTES ENVASADOS (SIN CAMBIO)&quot;
    },
    {
        &quot;gir_id&quot;: 520,
        &quot;gir_descripcion&quot;: &quot;OLEOCENTRO&quot;
    },
    {
        &quot;gir_id&quot;: 521,
        &quot;gir_descripcion&quot;: &quot;GRIFOS&quot;
    },
    {
        &quot;gir_id&quot;: 522,
        &quot;gir_descripcion&quot;: &quot;ESTACI&Oacute;N DE SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 523,
        &quot;gir_descripcion&quot;: &quot;EXPENDIO DE KEROSENE&quot;
    },
    {
        &quot;gir_id&quot;: 524,
        &quot;gir_descripcion&quot;: &quot;EXPENDIO DE GAS LICUADO&quot;
    },
    {
        &quot;gir_id&quot;: 525,
        &quot;gir_descripcion&quot;: &quot;VENTA DE AGUA ENVASADA (EN BIDONES)&quot;
    },
    {
        &quot;gir_id&quot;: 526,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 527,
        &quot;gir_descripcion&quot;: &quot;COMPRA Y VENTA DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 528,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 529,
        &quot;gir_descripcion&quot;: &quot;REPARACI&Oacute;N GENERAL DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 530,
        &quot;gir_descripcion&quot;: &quot;PLANCHADO Y PINTURA DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 531,
        &quot;gir_descripcion&quot;: &quot;TALLER DE MECANICA EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 532,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS ELECTRICOS AUTOMOTRICES&quot;
    },
    {
        &quot;gir_id&quot;: 533,
        &quot;gir_descripcion&quot;: &quot;AFINAMIENTO DE MOTORES AUTOMOTRICES&quot;
    },
    {
        &quot;gir_id&quot;: 534,
        &quot;gir_descripcion&quot;: &quot;MECANICA LIGERA&quot;
    },
    {
        &quot;gir_id&quot;: 535,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE LLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 536,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 537,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BATER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 538,
        &quot;gir_descripcion&quot;: &quot;ALQUILER DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 539,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE INMOBILIARIA&quot;
    },
    {
        &quot;gir_id&quot;: 540,
        &quot;gir_descripcion&quot;: &quot;ESTUDIO DE INGENIEROS&quot;
    },
    {
        &quot;gir_id&quot;: 541,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE DECORACION DE INTERIORES&quot;
    },
    {
        &quot;gir_id&quot;: 542,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIA EN PROYECTO DE INGENIERIA&quot;
    },
    {
        &quot;gir_id&quot;: 543,
        &quot;gir_descripcion&quot;: &quot;MARKETING, PROMOCION DE VENTAS&quot;
    },
    {
        &quot;gir_id&quot;: 544,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 545,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE EDUCACION SUPERIOR&quot;
    },
    {
        &quot;gir_id&quot;: 546,
        &quot;gir_descripcion&quot;: &quot;ORIENTACION EDUCATIVA&quot;
    },
    {
        &quot;gir_id&quot;: 547,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE DANZAS&quot;
    },
    {
        &quot;gir_id&quot;: 548,
        &quot;gir_descripcion&quot;: &quot;INVESTIGACIONES CIENTIFICAS&quot;
    },
    {
        &quot;gir_id&quot;: 549,
        &quot;gir_descripcion&quot;: &quot;CENTRO QUIRURGICO&quot;
    },
    {
        &quot;gir_id&quot;: 550,
        &quot;gir_descripcion&quot;: &quot;CLINICA ODONTOL&Oacute;GICA&quot;
    },
    {
        &quot;gir_id&quot;: 551,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE REHABILITACI&Oacute;N PSICOL&Oacute;GICA&quot;
    },
    {
        &quot;gir_id&quot;: 552,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 553,
        &quot;gir_descripcion&quot;: &quot;ASOCIACI&Oacute;N DE MERCANTILES TALES COMO C&Aacute;MARA DE COMERCIO, JUNTAS REGULADORAS DE COMERCIO, ASOCIACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 554,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE CINTAS MAGNETOF&Oacute;NICAS SIN GRABAR&quot;
    },
    {
        &quot;gir_id&quot;: 555,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE DISPOSITIVOS SEMICONDUCTORES Y OTROS DISPOSITIVOS SENSIBLES&quot;
    },
    {
        &quot;gir_id&quot;: 556,
        &quot;gir_descripcion&quot;: &quot;SECAS Y H&Uacute;MEDAS, BOMBILLAS Y TUBOS EL&Eacute;CTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 557,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCCIONES NAVALES Y REPARACI&Oacute;N DE BARCOS&quot;
    },
    {
        &quot;gir_id&quot;: 558,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ARTICULOS ENCHAPADOS&quot;
    },
    {
        &quot;gir_id&quot;: 559,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE MEDICAMENTOS  O PRODUCTOS  FARMACEUTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 560,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE PRODUCTOS QUIMICOS&quot;
    },
    {
        &quot;gir_id&quot;: 561,
        &quot;gir_descripcion&quot;: &quot;DISTRIBUCION DE EQUIPOS DE SONIDO&quot;
    },
    {
        &quot;gir_id&quot;: 562,
        &quot;gir_descripcion&quot;: &quot;COMPRADORES  DE PRODUCTOS AGRICOLAS&quot;
    },
    {
        &quot;gir_id&quot;: 563,
        &quot;gir_descripcion&quot;: &quot;CLASIFICACI&Oacute;N DE MERCADER&Iacute;A EN GRANDES LOTES&quot;
    },
    {
        &quot;gir_id&quot;: 564,
        &quot;gir_descripcion&quot;: &quot;ENTREGA E INSTALACI&Oacute;N DE PRODUCTOS&quot;
    },
    {
        &quot;gir_id&quot;: 565,
        &quot;gir_descripcion&quot;: &quot;ALMACENAJE DE PRODUCTOS DE LIMPIEZA&quot;
    },
    {
        &quot;gir_id&quot;: 566,
        &quot;gir_descripcion&quot;: &quot;COMERCIANTES DE MATERIALES  DE DESECHO&quot;
    },
    {
        &quot;gir_id&quot;: 567,
        &quot;gir_descripcion&quot;: &quot;ESTACIONES DE VENTA DE PETROLEO AL POR MAYOR&quot;
    },
    {
        &quot;gir_id&quot;: 569,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UTILES DE ESCRITORIO&quot;
    },
    {
        &quot;gir_id&quot;: 570,
        &quot;gir_descripcion&quot;: &quot;MERCER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 571,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE CUERO&quot;
    },
    {
        &quot;gir_id&quot;: 572,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE FANTAS&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 573,
        &quot;gir_descripcion&quot;: &quot;JUGUETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 574,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS PARA FIESTA&quot;
    },
    {
        &quot;gir_id&quot;: 575,
        &quot;gir_descripcion&quot;: &quot;MODISTA&quot;
    },
    {
        &quot;gir_id&quot;: 576,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UNIFORMES&quot;
    },
    {
        &quot;gir_id&quot;: 578,
        &quot;gir_descripcion&quot;: &quot;TOLDOS&quot;
    },
    {
        &quot;gir_id&quot;: 579,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CORTINAS (SIN CONFECCI&Oacute;N)&quot;
    },
    {
        &quot;gir_id&quot;: 580,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS PARA ARTESANIA&quot;
    },
    {
        &quot;gir_id&quot;: 581,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LUBRICANTES EN TODAS SUS MODALIDADES&quot;
    },
    {
        &quot;gir_id&quot;: 582,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEH&Iacute;CULOS NUEVOS Y/O USADOS&quot;
    },
    {
        &quot;gir_id&quot;: 583,
        &quot;gir_descripcion&quot;: &quot;MECANICA GENERAL DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 584,
        &quot;gir_descripcion&quot;: &quot;MECANICA MENOR&quot;
    },
    {
        &quot;gir_id&quot;: 585,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE CAMBIO DE ACEITE&quot;
    },
    {
        &quot;gir_id&quot;: 586,
        &quot;gir_descripcion&quot;: &quot;ADMINISTRACI&Oacute;N DE EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 587,
        &quot;gir_descripcion&quot;: &quot;AGENCIAS DE EMPLEO&quot;
    },
    {
        &quot;gir_id&quot;: 588,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 589,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE FUMIGACI&Oacute;N Y SANEAMIENTO AMBIENTAL&quot;
    },
    {
        &quot;gir_id&quot;: 590,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DROGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 591,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE MOSTAZAS&quot;
    },
    {
        &quot;gir_id&quot;: 592,
        &quot;gir_descripcion&quot;: &quot;PRODUCTOS DE ACEITE CRUDO, TORTAS Y HARINAS DE SEMILLA OLEAGINOSAS Y NUECES, OBTANDO&quot;
    },
    {
        &quot;gir_id&quot;: 593,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS DE CONFITERIA&quot;
    },
    {
        &quot;gir_id&quot;: 594,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE ALCOHOLES, SALVO EL QUE FIGURA EN EL NUMER. 237&quot;
    },
    {
        &quot;gir_id&quot;: 595,
        &quot;gir_descripcion&quot;: &quot;FIELTROS PREPARADOS DE PRODUCTOS QUE NO SEA TEJIDO&quot;
    },
    {
        &quot;gir_id&quot;: 596,
        &quot;gir_descripcion&quot;: &quot;CORTE DE TELAS Y/O TEJIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 597,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ALFOMBRAS DE ESFERAS DE PAPEL RETORCIDO&quot;
    },
    {
        &quot;gir_id&quot;: 598,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS CONEXOS DE CA&Ntilde;AMO&quot;
    },
    {
        &quot;gir_id&quot;: 599,
        &quot;gir_descripcion&quot;: &quot;INCIENSO Y PRODUCTOS DE ALCANFOR, Y BLANQUEADORES PARA LAVANDER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 600,
        &quot;gir_descripcion&quot;: &quot;LLANTERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 601,
        &quot;gir_descripcion&quot;: &quot;REGENERACI&Oacute;N DE CAUCHO OBTENIDO DE DESPERDICIOS, FRAGMENTOS DE LLANTAS, C&Aacute;MARAS Y DESECHOS&quot;
    },
    {
        &quot;gir_id&quot;: 602,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ARTICULOS DE LOZA DECORATIVA&quot;
    },
    {
        &quot;gir_id&quot;: 603,
        &quot;gir_descripcion&quot;: &quot;TALLERES DE ARTESANIAS DE ARCILLA ROJA SIN VIDRIAR&quot;
    },
    {
        &quot;gir_id&quot;: 604,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALIMENTOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 605,
        &quot;gir_descripcion&quot;: &quot;TELECOMERCIO&quot;
    },
    {
        &quot;gir_id&quot;: 606,
        &quot;gir_descripcion&quot;: &quot;CAMBIO DE MONEDA EXTRANJERA&quot;
    },
    {
        &quot;gir_id&quot;: 607,
        &quot;gir_descripcion&quot;: &quot;CERRAJER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 608,
        &quot;gir_descripcion&quot;: &quot;LUSTRABOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 609,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CANCHITA DE POPCORN&quot;
    },
    {
        &quot;gir_id&quot;: 610,
        &quot;gir_descripcion&quot;: &quot;VENTA DE EMOLIENTE&quot;
    },
    {
        &quot;gir_id&quot;: 611,
        &quot;gir_descripcion&quot;: &quot;VENTA DE FLORES&quot;
    },
    {
        &quot;gir_id&quot;: 612,
        &quot;gir_descripcion&quot;: &quot;VENTA DE FRUTAS&quot;
    },
    {
        &quot;gir_id&quot;: 614,
        &quot;gir_descripcion&quot;: &quot;VENTA DE HELADOS&quot;
    },
    {
        &quot;gir_id&quot;: 615,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PERIODICOS Y REVISTAS&quot;
    },
    {
        &quot;gir_id&quot;: 616,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PLASTICOS Y ESCOBAS&quot;
    },
    {
        &quot;gir_id&quot;: 618,
        &quot;gir_descripcion&quot;: &quot;MASAJES FACIALES&quot;
    },
    {
        &quot;gir_id&quot;: 619,
        &quot;gir_descripcion&quot;: &quot;MASAJES FACIALES&quot;
    },
    {
        &quot;gir_id&quot;: 620,
        &quot;gir_descripcion&quot;: &quot;ORGANIZACIONES PROFESIONALES, SINDICATOS&quot;
    },
    {
        &quot;gir_id&quot;: 621,
        &quot;gir_descripcion&quot;: &quot;COLEGIOS PROFESIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 622,
        &quot;gir_descripcion&quot;: &quot;ORGANIZACIONES LABORALES AN&Aacute;LOGAS&quot;
    },
    {
        &quot;gir_id&quot;: 623,
        &quot;gir_descripcion&quot;: &quot;ESTUDIO DE ABOGADOS&quot;
    },
    {
        &quot;gir_id&quot;: 624,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS Y ASISTENCIA SOCIAL&quot;
    },
    {
        &quot;gir_id&quot;: 625,
        &quot;gir_descripcion&quot;: &quot;ACUPUNTURA&quot;
    },
    {
        &quot;gir_id&quot;: 626,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO ACUPUNTURISTA&quot;
    },
    {
        &quot;gir_id&quot;: 627,
        &quot;gir_descripcion&quot;: &quot;TATUAJES Y/O PERCING&quot;
    },
    {
        &quot;gir_id&quot;: 628,
        &quot;gir_descripcion&quot;: &quot;DISE&Ntilde;O GR&Aacute;FICO&quot;
    },
    {
        &quot;gir_id&quot;: 629,
        &quot;gir_descripcion&quot;: &quot;ARQUITECTOS.&quot;
    },
    {
        &quot;gir_id&quot;: 630,
        &quot;gir_descripcion&quot;: &quot;INGENIEROS&quot;
    },
    {
        &quot;gir_id&quot;: 631,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA CONSTRUCTORA (SIN ALMACEN).&quot;
    },
    {
        &quot;gir_id&quot;: 632,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE CORRETAJES.&quot;
    },
    {
        &quot;gir_id&quot;: 633,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE EMP. INMOBILIARIA&quot;
    },
    {
        &quot;gir_id&quot;: 634,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE DECORACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 635,
        &quot;gir_descripcion&quot;: &quot;CONTADORES&quot;
    },
    {
        &quot;gir_id&quot;: 636,
        &quot;gir_descripcion&quot;: &quot;AUDITORES.&quot;
    },
    {
        &quot;gir_id&quot;: 637,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE ASESORAMIENTO Y DESARROLLO DE SISTEMAS&quot;
    },
    {
        &quot;gir_id&quot;: 638,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE TIPEO&quot;
    },
    {
        &quot;gir_id&quot;: 639,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE TIPEO POR COMPUTADORA&quot;
    },
    {
        &quot;gir_id&quot;: 640,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE INSTALACI&Oacute;N DE SISTEMAS INFORMATICOS&quot;
    },
    {
        &quot;gir_id&quot;: 641,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE INSTALACI&Oacute;N DE SISTEMAS INFORMATICOS&quot;
    },
    {
        &quot;gir_id&quot;: 642,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE INSTALACI&Oacute;N DE SISTEMAS DE TELECOMUNICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 643,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS INFORMATICOS&quot;
    },
    {
        &quot;gir_id&quot;: 644,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS DE OBRAS&quot;
    },
    {
        &quot;gir_id&quot;: 645,
        &quot;gir_descripcion&quot;: &quot;CONSTRUCTORAS&quot;
    },
    {
        &quot;gir_id&quot;: 646,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES&quot;
    },
    {
        &quot;gir_id&quot;: 647,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PUBLICIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 648,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE TRAMITACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 649,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LINEAS TELEF&Oacute;NICAS&quot;
    },
    {
        &quot;gir_id&quot;: 650,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE MENSAJER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 651,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE CORREO POSTAL&quot;
    },
    {
        &quot;gir_id&quot;: 652,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE FAX&quot;
    },
    {
        &quot;gir_id&quot;: 653,
        &quot;gir_descripcion&quot;: &quot;CENTRAL TELEF&Oacute;NICA&quot;
    },
    {
        &quot;gir_id&quot;: 654,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE TAXIS (SIN PARADERO).&quot;
    },
    {
        &quot;gir_id&quot;: 655,
        &quot;gir_descripcion&quot;: &quot;VENTA DE TELEFONOS Y/O CELULARES&quot;
    },
    {
        &quot;gir_id&quot;: 656,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE TELECOMUNICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 657,
        &quot;gir_descripcion&quot;: &quot;MEZQUITAS&quot;
    },
    {
        &quot;gir_id&quot;: 658,
        &quot;gir_descripcion&quot;: &quot;SINAGOGAS&quot;
    },
    {
        &quot;gir_id&quot;: 659,
        &quot;gir_descripcion&quot;: &quot;TEMPLOS.&quot;
    },
    {
        &quot;gir_id&quot;: 660,
        &quot;gir_descripcion&quot;: &quot;IGLESIAS&quot;
    },
    {
        &quot;gir_id&quot;: 661,
        &quot;gir_descripcion&quot;: &quot;ORGANIZACI&Oacute;N CULTURAL&quot;
    },
    {
        &quot;gir_id&quot;: 662,
        &quot;gir_descripcion&quot;: &quot;CENTRO CULTURAL&quot;
    },
    {
        &quot;gir_id&quot;: 663,
        &quot;gir_descripcion&quot;: &quot;PRODUCCI&Oacute;N DE PELICULAS&quot;
    },
    {
        &quot;gir_id&quot;: 664,
        &quot;gir_descripcion&quot;: &quot;DISTRIBUCI&Oacute;N Y EXHIBICI&Oacute;N DE PELICULAS&quot;
    },
    {
        &quot;gir_id&quot;: 665,
        &quot;gir_descripcion&quot;: &quot;ESTACIONES DE RADIO Y TELEVISI&Oacute;N (SIN ANTENA)&quot;
    },
    {
        &quot;gir_id&quot;: 799,
        &quot;gir_descripcion&quot;: &quot;SNACK BAR&quot;
    },
    {
        &quot;gir_id&quot;: 666,
        &quot;gir_descripcion&quot;: &quot;ANTENA DE  TV Y RADIO FM (FRECUENCIA MODULADA&quot;
    },
    {
        &quot;gir_id&quot;: 667,
        &quot;gir_descripcion&quot;: &quot;ANTENA  AM (AMPLITUD  MODULADA)&quot;
    },
    {
        &quot;gir_id&quot;: 668,
        &quot;gir_descripcion&quot;: &quot;CINES&quot;
    },
    {
        &quot;gir_id&quot;: 669,
        &quot;gir_descripcion&quot;: &quot;TEATROS, SALAS DE CONVENCIONES&quot;
    },
    {
        &quot;gir_id&quot;: 670,
        &quot;gir_descripcion&quot;: &quot;PRODUCCIONES TEATRALES&quot;
    },
    {
        &quot;gir_id&quot;: 671,
        &quot;gir_descripcion&quot;: &quot;AGENCIAS DE CONTRATACI&Oacute;N DE ACTORES Y DE OBRAS TEATRALES, ARTISTICAS, CONCIERTOS Y SERVICIOS DE ESCENOGRAF&Iacute;A,&quot;
    },
    {
        &quot;gir_id&quot;: 672,
        &quot;gir_descripcion&quot;: &quot;ILUMINACI&Oacute;N Y DEMAS EQUIPOS&quot;
    },
    {
        &quot;gir_id&quot;: 673,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE VENTA DE BOLETOS DE TEATRO&quot;
    },
    {
        &quot;gir_id&quot;: 674,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE TURISTICO&quot;
    },
    {
        &quot;gir_id&quot;: 675,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE CAMPESTRE.&quot;
    },
    {
        &quot;gir_id&quot;: 676,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE AGASAJOS Y ESPECT&Aacute;CULOS (LOCAL PARA ) - VIDEO PUB&quot;
    },
    {
        &quot;gir_id&quot;: 677,
        &quot;gir_descripcion&quot;: &quot;DISCOTECAS&quot;
    },
    {
        &quot;gir_id&quot;: 678,
        &quot;gir_descripcion&quot;: &quot;KARAOKES&quot;
    },
    {
        &quot;gir_id&quot;: 679,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 680,
        &quot;gir_descripcion&quot;: &quot;GASOCENTRO&quot;
    },
    {
        &quot;gir_id&quot;: 681,
        &quot;gir_descripcion&quot;: &quot;LOCUTORIO&quot;
    },
    {
        &quot;gir_id&quot;: 682,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 683,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE CORRETAJES&quot;
    },
    {
        &quot;gir_id&quot;: 685,
        &quot;gir_descripcion&quot;: &quot;NOTARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 686,
        &quot;gir_descripcion&quot;: &quot;AGENCIAS DE SERVICIO DOM&Eacute;STICO&quot;
    },
    {
        &quot;gir_id&quot;: 687,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE AVISOS PERIOD&Iacute;STICO&quot;
    },
    {
        &quot;gir_id&quot;: 688,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE EMPLEOS DOM&Eacute;STICOS&quot;
    },
    {
        &quot;gir_id&quot;: 689,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE EMPLEOS T&Eacute;CNICOS&quot;
    },
    {
        &quot;gir_id&quot;: 690,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE EMPLEOS ADMINISTRATIVOS.&quot;
    },
    {
        &quot;gir_id&quot;: 691,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMPRA Y VENTA DE VEH&Iacute;CULOS Y REPUESTOS&quot;
    },
    {
        &quot;gir_id&quot;: 692,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS PARA LA MINER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 693,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISTRIBUCI&Oacute;N DE PRODUCTOS PARA LA MINER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 694,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISTRIBUCI&Oacute;N DE PRODUCTOS PARA LA CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 695,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE PRODUCTOS PARA LA MINER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 696,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS PARA LA CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 697,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS ALIMENTICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 698,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE PRODUCTOS QUIMICOS&quot;
    },
    {
        &quot;gir_id&quot;: 699,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE PRODUCTOS QUIMICOS&quot;
    },
    {
        &quot;gir_id&quot;: 700,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE MATERIALES DE LIMPIEZA&quot;
    },
    {
        &quot;gir_id&quot;: 701,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACION DE HERRAMIENTAS&quot;
    },
    {
        &quot;gir_id&quot;: 702,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE EQUIPOS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 703,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE MAQUINARIAS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 704,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INTERMEDIACI&Oacute;N DE ART&Iacute;CULOS PROMOCIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 705,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE CONFECCIONES&quot;
    },
    {
        &quot;gir_id&quot;: 706,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASISTENCIA TECNICA Y EQUIPAMIENTO MUNICIPAL&quot;
    },
    {
        &quot;gir_id&quot;: 707,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTORIA EN PROYECTOS DE INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 708,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORAMIENTO EN PROYECTO DE INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 709,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIERO ELECTRICISTA&quot;
    },
    {
        &quot;gir_id&quot;: 710,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA Y ALQUILER DE TOLDOS&quot;
    },
    {
        &quot;gir_id&quot;: 711,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE REPUESTOS Y ACCESORIOS PARA COMPUTADORA&quot;
    },
    {
        &quot;gir_id&quot;: 712,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE ALIMENTOS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 713,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PINTURA INDUSTRIAL Y NAVAL&quot;
    },
    {
        &quot;gir_id&quot;: 714,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACIONES EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 715,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE LIMPIEZA&quot;
    },
    {
        &quot;gir_id&quot;: 716,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;AS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 717,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONTRATISTAS GENERALES&quot;
    },
    {
        &quot;gir_id&quot;: 718,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE INGENIER&Iacute;A CIVIL&quot;
    },
    {
        &quot;gir_id&quot;: 719,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SISTEMA DE AHORRO COLECTIVO&quot;
    },
    {
        &quot;gir_id&quot;: 720,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE PLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 721,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE PLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 722,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS VINCULADOS A LA INDUSTRIA AGROQUIMICA Y DE SALUD PUBLICA&quot;
    },
    {
        &quot;gir_id&quot;: 723,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTANTES DE ACTIVIDADES COMERCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 864,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE CHOCOLATES&quot;
    },
    {
        &quot;gir_id&quot;: 724,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTANTES DE SERVICIOS Y PROMOCI&Oacute;N DE ACTIVIDADES MINERAS Y COMERCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 725,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTANTES DE PRODUCTOS AGROPECUARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 726,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A EN COMERCIO EXTERIOR&quot;
    },
    {
        &quot;gir_id&quot;: 727,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AGENCIAS DE ADUANAS&quot;
    },
    {
        &quot;gir_id&quot;: 728,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N DE PRODUCTOS OPTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 729,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS FARMACEUTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 730,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE GESTIONARIA DE TECNOLOGIA&quot;
    },
    {
        &quot;gir_id&quot;: 731,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRANSPORTE DE FLUIDOS Y/O LIQUIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 732,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE MANTENIMIENTO DE PARQUES&quot;
    },
    {
        &quot;gir_id&quot;: 733,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRANSPORTES DE CARGAS&quot;
    },
    {
        &quot;gir_id&quot;: 800,
        &quot;gir_descripcion&quot;: &quot;ALIMENTOS AL PASO&quot;
    },
    {
        &quot;gir_id&quot;: 734,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE TAXIS&quot;
    },
    {
        &quot;gir_id&quot;: 735,
        &quot;gir_descripcion&quot;: &quot;TELE COMERCIO (COMERCIO POR TELECOMUNICACI&Oacute;N SIN EXHIBICI&Oacute;N NI ALMACEN EN LOCAL).&quot;
    },
    {
        &quot;gir_id&quot;: 736,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA A PUERTA CERRADA&quot;
    },
    {
        &quot;gir_id&quot;: 737,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AGENCIA DE PERSONAL T&Eacute;CNICO&quot;
    },
    {
        &quot;gir_id&quot;: 738,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AGENCIA DE PERSONAL ADMINISTRATIVO&quot;
    },
    {
        &quot;gir_id&quot;: 739,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AGENCIA DE PERSONAL&quot;
    },
    {
        &quot;gir_id&quot;: 740,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE ALUMINIO&quot;
    },
    {
        &quot;gir_id&quot;: 741,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y/O EXPORTACI&Oacute;N DE ART&Iacute;CULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 742,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y/O EXPORTACI&Oacute;N DE JUGUETES&quot;
    },
    {
        &quot;gir_id&quot;: 743,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE COMERCIALIZACI&Oacute;N DE EQUIPOS Y MAQUINARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 744,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE IMPORTACI&Oacute;N DE EQUIPOS Y MAQUINARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 745,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE ARTESAN&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 746,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE CASSETTE Y/O DISCOS COMPACTOS, LASER&quot;
    },
    {
        &quot;gir_id&quot;: 747,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE COMERCIALIZACI&Oacute;N DE EQUIPOS PARA AGUA&quot;
    },
    {
        &quot;gir_id&quot;: 748,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE SERVICIOS DE MANTENIMIENTO DE EDIFICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 749,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE DISTRIBUCI&Oacute;N Y VENTA DE PRODUCTOS ENLATADOS DE CONSERVA DE PESCADO&quot;
    },
    {
        &quot;gir_id&quot;: 750,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE COMPRA VENTA DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 751,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE COMERCIALIZACI&Oacute;N DE PRODUCTOS FERRETEROS&quot;
    },
    {
        &quot;gir_id&quot;: 752,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACIONES BANCARIAS EXTRANJERAS&quot;
    },
    {
        &quot;gir_id&quot;: 753,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE PRODUCTOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 754,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE MEDICAMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 842,
        &quot;gir_descripcion&quot;: &quot;DESCASCARADO, LIMPIO Y PILLADO DEL ARROZ&quot;
    },
    {
        &quot;gir_id&quot;: 755,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE LIBROS&quot;
    },
    {
        &quot;gir_id&quot;: 756,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE ARTICULOS FERRETEROS&quot;
    },
    {
        &quot;gir_id&quot;: 757,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPLORACI&Oacute;N Y EXPLOTACI&Oacute;N DE HIDROCARBUROS&quot;
    },
    {
        &quot;gir_id&quot;: 758,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE MATERIALES DE LABORATORIO&quot;
    },
    {
        &quot;gir_id&quot;: 759,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISTRIBUCI&Oacute;N DE PRODUCTOS DE BELLEZA Y SALUD&quot;
    },
    {
        &quot;gir_id&quot;: 760,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DOTACI&Oacute;N DE PERSONAL&quot;
    },
    {
        &quot;gir_id&quot;: 761,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSTRUCTORA&quot;
    },
    {
        &quot;gir_id&quot;: 762,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE EQUIPOS Y PIEZAS PARA EQUIPOS DE AGUA&quot;
    },
    {
        &quot;gir_id&quot;: 763,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE ALFOMBRAS&quot;
    },
    {
        &quot;gir_id&quot;: 764,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N DE SISTEMAS DE AIRE ACONDICIONADO&quot;
    },
    {
        &quot;gir_id&quot;: 765,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACI&Oacute;N DE EQUIPOS MEDICOS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 766,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N DE EQUIPOS MEDICOS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 767,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ORGANIZACI&Oacute;N DE EVENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 768,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACIONES DE COMPA&Ntilde;IAS EXTRANJERAS&quot;
    },
    {
        &quot;gir_id&quot;: 769,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE AGASAJOS Y ESPECT&Aacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 770,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ALQUILER DE TOLDOS, TABLADILLOS Y MENAJE PARA FIESTAS.&quot;
    },
    {
        &quot;gir_id&quot;: 771,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA MINERA&quot;
    },
    {
        &quot;gir_id&quot;: 772,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE EXPORTACION E IMPORTACION DE MATERIALES PARA TELECOMUNICACIONES (SIN ATENCION AL PUBLICO)&quot;
    },
    {
        &quot;gir_id&quot;: 773,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTORIA Y ASESORIA ECONOMICA&quot;
    },
    {
        &quot;gir_id&quot;: 3103,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE LAVADO DE VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 774,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ORGANISMOS NO GUBERNAMENTALES (ONG)&quot;
    },
    {
        &quot;gir_id&quot;: 775,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA LANGOSTINERA&quot;
    },
    {
        &quot;gir_id&quot;: 776,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TELECOMUNICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 777,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA VENTA DE ARTICULOS DIVERSOS&quot;
    },
    {
        &quot;gir_id&quot;: 778,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N MATERIALES DECORATIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 779,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA COMERCIALIZACION DE PRODUCTOS ELECTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 780,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE CONSULTORIA&quot;
    },
    {
        &quot;gir_id&quot;: 781,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS VINCULADOS AL TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 782,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SISTEMA PANDERO&quot;
    },
    {
        &quot;gir_id&quot;: 783,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACIONES Y EXPORTACIONES Y DISTRIBUCION DE ARTICULOS DE JARDINERIA&quot;
    },
    {
        &quot;gir_id&quot;: 865,
        &quot;gir_descripcion&quot;: &quot;TOSTADO DE CAF&Eacute;&quot;
    },
    {
        &quot;gir_id&quot;: 784,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCCION, EXPORTACION E IMPORTACION DE FLORES EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 785,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISTRIBUCION DE PRODUCTOS CAPACITACION EDUCATIVA Y OTROS&quot;
    },
    {
        &quot;gir_id&quot;: 786,
        &quot;gir_descripcion&quot;: &quot;PIZZER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 787,
        &quot;gir_descripcion&quot;: &quot;PARRILLADA&quot;
    },
    {
        &quot;gir_id&quot;: 788,
        &quot;gir_descripcion&quot;: &quot;TRATTOR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 789,
        &quot;gir_descripcion&quot;: &quot;CEVICHERIA&quot;
    },
    {
        &quot;gir_id&quot;: 790,
        &quot;gir_descripcion&quot;: &quot;CHIFA&quot;
    },
    {
        &quot;gir_id&quot;: 791,
        &quot;gir_descripcion&quot;: &quot;POLLER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 792,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PASTAS (PARA CONSUMO)&quot;
    },
    {
        &quot;gir_id&quot;: 793,
        &quot;gir_descripcion&quot;: &quot;ANTICUCHER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 795,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 796,
        &quot;gir_descripcion&quot;: &quot;SALONES DE T&Eacute;&quot;
    },
    {
        &quot;gir_id&quot;: 797,
        &quot;gir_descripcion&quot;: &quot;COMIDAS AL PASO&quot;
    },
    {
        &quot;gir_id&quot;: 798,
        &quot;gir_descripcion&quot;: &quot;JUGUER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 801,
        &quot;gir_descripcion&quot;: &quot;SANGUCHER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 802,
        &quot;gir_descripcion&quot;: &quot;DULCER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 803,
        &quot;gir_descripcion&quot;: &quot;CONFITER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 804,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MEN&Uacute;&quot;
    },
    {
        &quot;gir_id&quot;: 805,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BEBIDAS REFRESCANTES PREPARADAS&quot;
    },
    {
        &quot;gir_id&quot;: 806,
        &quot;gir_descripcion&quot;: &quot;FUENTE DE SODA&quot;
    },
    {
        &quot;gir_id&quot;: 807,
        &quot;gir_descripcion&quot;: &quot;BARES&quot;
    },
    {
        &quot;gir_id&quot;: 808,
        &quot;gir_descripcion&quot;: &quot;CANTINAS&quot;
    },
    {
        &quot;gir_id&quot;: 809,
        &quot;gir_descripcion&quot;: &quot;HOTEL&quot;
    },
    {
        &quot;gir_id&quot;: 810,
        &quot;gir_descripcion&quot;: &quot;HOSTAL&quot;
    },
    {
        &quot;gir_id&quot;: 811,
        &quot;gir_descripcion&quot;: &quot;CASA DE HUESPEDES&quot;
    },
    {
        &quot;gir_id&quot;: 812,
        &quot;gir_descripcion&quot;: &quot;PENSI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 813,
        &quot;gir_descripcion&quot;: &quot;ALBERGUE&quot;
    },
    {
        &quot;gir_id&quot;: 814,
        &quot;gir_descripcion&quot;: &quot;ALBERGUE INFANTIL&quot;
    },
    {
        &quot;gir_id&quot;: 815,
        &quot;gir_descripcion&quot;: &quot;CAMPAMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 816,
        &quot;gir_descripcion&quot;: &quot;FRIGORIFICOS:  CONSERVACI&Oacute;N DE CARNE, PREPARACI&Oacute;N, CONSERVACI&Oacute;N Y ENVASADO DE&quot;
    },
    {
        &quot;gir_id&quot;: 817,
        &quot;gir_descripcion&quot;: &quot;CARNES EN RECIPIENTES HERM&Eacute;TICOS&quot;
    },
    {
        &quot;gir_id&quot;: 818,
        &quot;gir_descripcion&quot;: &quot;PREPARACI&Oacute;N DE TRIPAS PARA EMBUTIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 819,
        &quot;gir_descripcion&quot;: &quot;PREPARACI&Oacute;N DE SOPAS ENVASADAS&quot;
    },
    {
        &quot;gir_id&quot;: 820,
        &quot;gir_descripcion&quot;: &quot;PREPARACI&Oacute;N DE BUDINES&quot;
    },
    {
        &quot;gir_id&quot;: 821,
        &quot;gir_descripcion&quot;: &quot;PREPARACI&Oacute;N DE PASTELES DE CARNE&quot;
    },
    {
        &quot;gir_id&quot;: 822,
        &quot;gir_descripcion&quot;: &quot;EXTRACCI&Oacute;N Y REFINACI&Oacute;N DE MANTECA DE CERDO OTRAS RAZAS DE ANIMALES COMESTIBLES&quot;
    },
    {
        &quot;gir_id&quot;: 823,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE EMBUTIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 824,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE CACAO Y CHOCOLATE EN POLVO EN BASE A GRANO DE CACAO&quot;
    },
    {
        &quot;gir_id&quot;: 825,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N Y ELABORACI&Oacute;N DE MANTEQUILLA Y/O QUESOS&quot;
    },
    {
        &quot;gir_id&quot;: 826,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE HELADOS, CHUPETES Y OTROS POSTRES&quot;
    },
    {
        &quot;gir_id&quot;: 827,
        &quot;gir_descripcion&quot;: &quot;ENVASADO DE CONSERVAS DE FRUTAS Y LEGUMBRES&quot;
    },
    {
        &quot;gir_id&quot;: 828,
        &quot;gir_descripcion&quot;: &quot;TRANSFORMACI&Oacute;N DE HOJAS DE T&Eacute; Y OTRAS YERBAS AROM&Aacute;TICAS&quot;
    },
    {
        &quot;gir_id&quot;: 829,
        &quot;gir_descripcion&quot;: &quot;TRATAMIENTO DE HOJAS DE T&Eacute; Y OTRAS YERBAS AROM&Aacute;TICAS&quot;
    },
    {
        &quot;gir_id&quot;: 830,
        &quot;gir_descripcion&quot;: &quot;ENVASADO DE HOJAS DE T&Eacute; Y OTRAS YERBAS AROM&Aacute;TICAS&quot;
    },
    {
        &quot;gir_id&quot;: 831,
        &quot;gir_descripcion&quot;: &quot;SEPARACI&Oacute;N DE LA CLARA Y LA YEMA DEL HUEVO&quot;
    },
    {
        &quot;gir_id&quot;: 832,
        &quot;gir_descripcion&quot;: &quot;DESECACI&Oacute;N DE LA CLARA Y LA YEMA DEL HUEVO&quot;
    },
    {
        &quot;gir_id&quot;: 833,
        &quot;gir_descripcion&quot;: &quot;CONGELACI&Oacute;N DE LA CLARA Y LA YEMA DEL HUEVO&quot;
    },
    {
        &quot;gir_id&quot;: 834,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE PRODUCTOS ALIMENTICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 835,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE BEBIDAS REFRESCANTES&quot;
    },
    {
        &quot;gir_id&quot;: 836,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE GELATINA.&quot;
    },
    {
        &quot;gir_id&quot;: 837,
        &quot;gir_descripcion&quot;: &quot;ELABORACION DE JUGOS Y MERMELADAS&quot;
    },
    {
        &quot;gir_id&quot;: 838,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE LECHE CONDENSADA EN POLVO Y EVAPORADA&quot;
    },
    {
        &quot;gir_id&quot;: 839,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE CREMA FRESCA  Y CONSERVADA&quot;
    },
    {
        &quot;gir_id&quot;: 840,
        &quot;gir_descripcion&quot;: &quot;PRODUCTOS : DE MARGARINA Y GRASAS PARA COCINAR Y ACEITES MEZCLADOS DE MESA Y ESCALADA&quot;
    },
    {
        &quot;gir_id&quot;: 841,
        &quot;gir_descripcion&quot;: &quot;MOLIENDAS, PASTOS Y FORRAJES: GRANOS Y SUS DERIVADOS&quot;
    },
    {
        &quot;gir_id&quot;: 843,
        &quot;gir_descripcion&quot;: &quot;PILLADO, DESCASCARADO, MOLIENDA, CLASIFICACI&Oacute;N O PRENSADO DEL CAF&Eacute; O CACAO&quot;
    },
    {
        &quot;gir_id&quot;: 844,
        &quot;gir_descripcion&quot;: &quot;SAL REFINADA DE MESA&quot;
    },
    {
        &quot;gir_id&quot;: 845,
        &quot;gir_descripcion&quot;: &quot;PROCESAMIENTO DE HIGIENIZACI&Oacute;N DE LECHE FRESCA&quot;
    },
    {
        &quot;gir_id&quot;: 846,
        &quot;gir_descripcion&quot;: &quot;PROCESAMIENTO DE VITAMINACI&Oacute;N DE LECHA FRESCA&quot;
    },
    {
        &quot;gir_id&quot;: 847,
        &quot;gir_descripcion&quot;: &quot;ENVASADO DE LECHE FRESCA&quot;
    },
    {
        &quot;gir_id&quot;: 848,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE LEVADURA Y OTROS FERMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 849,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE HIELO (EXCEPTO HIELO SECO)&quot;
    },
    {
        &quot;gir_id&quot;: 850,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE ALMIDON Y DERIVADOS&quot;
    },
    {
        &quot;gir_id&quot;: 851,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE LEVADURA EN POLVO&quot;
    },
    {
        &quot;gir_id&quot;: 852,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE CONDIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 853,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE VINAGRES&quot;
    },
    {
        &quot;gir_id&quot;: 854,
        &quot;gir_descripcion&quot;: &quot;INDUSTRIA VINICOLA&quot;
    },
    {
        &quot;gir_id&quot;: 855,
        &quot;gir_descripcion&quot;: &quot;PROCESOS PARA LA CONSERVACI&Oacute;N DE PESCADO, CRUST&Aacute;CEOS Y OTROS PRODUCTOS MARINOS&quot;
    },
    {
        &quot;gir_id&quot;: 856,
        &quot;gir_descripcion&quot;: &quot;POR TRITURACI&Oacute;N &Oacute; EXTRACCI&Oacute;N  BEBIDAS MALTEADAS O MALTAS&quot;
    },
    {
        &quot;gir_id&quot;: 857,
        &quot;gir_descripcion&quot;: &quot;EXTRACCI&Oacute;N DE ACEITE DE PESCADO Y OTROS ANIMALES MARINOS Y LA PRODUCCI&Oacute;N DE HARINA DE PESCADO&quot;
    },
    {
        &quot;gir_id&quot;: 858,
        &quot;gir_descripcion&quot;: &quot;CLASIFICACI&Oacute;N DE ACEITES Y GRASAS ANIMALES NO COMESTIBLES&quot;
    },
    {
        &quot;gir_id&quot;: 859,
        &quot;gir_descripcion&quot;: &quot;REFINACI&Oacute;N  E  HIDROGENACI&Oacute;N DE ACEITES Y GRASAS (EXCEPTO LO INCLUIDO EN EL N.UM. 204)&quot;
    },
    {
        &quot;gir_id&quot;: 860,
        &quot;gir_descripcion&quot;: &quot;DESTILACI&Oacute;N DE ALCOHOL ETILICO, EXCEP., RESIDUOS&quot;
    },
    {
        &quot;gir_id&quot;: 861,
        &quot;gir_descripcion&quot;: &quot;CEREALES PREPARADOS PARA EL DESAYUNO, TALES COMO AVENA, ARROZ, COPOS DE MAIZ Y COPOS DE TRIGO&quot;
    },
    {
        &quot;gir_id&quot;: 862,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PAN (FABRICACI&Oacute;N INDUSTRIAL)&quot;
    },
    {
        &quot;gir_id&quot;: 863,
        &quot;gir_descripcion&quot;: &quot;FABRICA Y REFINERIAS DE AZ&Uacute;CAR&quot;
    },
    {
        &quot;gir_id&quot;: 866,
        &quot;gir_descripcion&quot;: &quot;MOLIDO DE CAF&Eacute;&quot;
    },
    {
        &quot;gir_id&quot;: 867,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE ALIMENTOS PREPARADOS PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 868,
        &quot;gir_descripcion&quot;: &quot;DESTILACI&Oacute;N, RECTIFICACI&Oacute;N Y MEZCLA DE BEBIDAS ALCOH&Oacute;LICAS (PISCO, WHISKY, COGNAC, RON, GINEBRA)&quot;
    },
    {
        &quot;gir_id&quot;: 869,
        &quot;gir_descripcion&quot;: &quot;INDUSTRIA DE BEBIDAS NO ALCOH&Oacute;LICAS&quot;
    },
    {
        &quot;gir_id&quot;: 870,
        &quot;gir_descripcion&quot;: &quot;INDUSTRIA DE BEBIDAS GASEOSAS&quot;
    },
    {
        &quot;gir_id&quot;: 871,
        &quot;gir_descripcion&quot;: &quot;INDUSTRIA DE TABACO&quot;
    },
    {
        &quot;gir_id&quot;: 872,
        &quot;gir_descripcion&quot;: &quot;PREPARACI&Oacute;N DE FIBRAS PARA PROCESOS DE HILADURAS&quot;
    },
    {
        &quot;gir_id&quot;: 873,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE LIN&Oacute;LEOS, HULE, CUERO ARTIFICIAL QUE NO SEA TOTALMENTE PLASTICO&quot;
    },
    {
        &quot;gir_id&quot;: 874,
        &quot;gir_descripcion&quot;: &quot;TELAS IMPREGNADAS E IMPERMEABILIZADAS EXCEPTO LAS CAUCHOTADAS&quot;
    },
    {
        &quot;gir_id&quot;: 875,
        &quot;gir_descripcion&quot;: &quot;HILADURAS DE FIBRAS&quot;
    },
    {
        &quot;gir_id&quot;: 876,
        &quot;gir_descripcion&quot;: &quot;MANUFACTURAS DE TEJIDOS DE POCA ANCHURA Y OTROS ARTICULOS TEXTILES MENUDOS&quot;
    },
    {
        &quot;gir_id&quot;: 877,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE TAPICES, TEJIDOS Y TRENZADOS DE CUALQUIER FIBRA&quot;
    },
    {
        &quot;gir_id&quot;: 878,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS CONFECCIONADOS  DE MATERIAS TEXTILES&quot;
    },
    {
        &quot;gir_id&quot;: 879,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 880,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE TEJIDOS DE PUNTO&quot;
    },
    {
        &quot;gir_id&quot;: 881,
        &quot;gir_descripcion&quot;: &quot;ESTAMPADO DE TELAS Y/O TEJIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 882,
        &quot;gir_descripcion&quot;: &quot;CONFECCI&Oacute;N DE CORTINAS&quot;
    },
    {
        &quot;gir_id&quot;: 883,
        &quot;gir_descripcion&quot;: &quot;CONFECCI&Oacute;N DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 884,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ALFOMBRAS DE ESPARTO BARROTE&quot;
    },
    {
        &quot;gir_id&quot;: 885,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ALFOMBRAS DE YUTE&quot;
    },
    {
        &quot;gir_id&quot;: 886,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ALFOMBRAS DE TRAPOS&quot;
    },
    {
        &quot;gir_id&quot;: 887,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS DE CUERO Y SUDENEOS DE CUERO&quot;
    },
    {
        &quot;gir_id&quot;: 888,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE CALZADO (EXCEPTO DE CAUCHO VULCANIZADO MOLDEADO &Oacute; DE PLASTICO)&quot;
    },
    {
        &quot;gir_id&quot;: 889,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS CONEXOS DE MANILA&quot;
    },
    {
        &quot;gir_id&quot;: 890,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS CONEXOS DE HENEQU&Eacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 891,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS CONEXOS DE ALGOD&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 892,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS CONEXOS DE YUTE&quot;
    },
    {
        &quot;gir_id&quot;: 893,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS CONEXOS DE PAPEL&quot;
    },
    {
        &quot;gir_id&quot;: 894,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS CONEXOS DE LINO&quot;
    },
    {
        &quot;gir_id&quot;: 895,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS CONEXOS DE FIBRAS ARTIFICIALES INCLUIDAS LAS DE VIDRIOS Y OTRAS&quot;
    },
    {
        &quot;gir_id&quot;: 896,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE SOGAS, CABLES, CORDELES, BRAMANTES, REDES&quot;
    },
    {
        &quot;gir_id&quot;: 897,
        &quot;gir_descripcion&quot;: &quot;CURTIDURIAS Y TALLERES  DE ACABADOS DE CUERO INDUSTRIAL DE PREPARACI&Oacute;N DE TE&Ntilde;IDOS DE CUERO&quot;
    },
    {
        &quot;gir_id&quot;: 898,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE MADERA  AGLOMERADA, LAMINADA, ENCHAPADA Y TERCIADA, ASERRADEROS Y TRATAMIENTOS,&quot;
    },
    {
        &quot;gir_id&quot;: 899,
        &quot;gir_descripcion&quot;: &quot;CONSERVACI&Oacute;N DE LA MADERA&quot;
    },
    {
        &quot;gir_id&quot;: 900,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PULPA A PARTIR DE MADERA, TRAPOS Y OTRAS FIBRAS Y LA FABRICACI&Oacute;N DE PAPEL CART&Oacute;N Y PAPEL DE FIBRA&quot;
    },
    {
        &quot;gir_id&quot;: 901,
        &quot;gir_descripcion&quot;: &quot;CORTE DE MADERA, VIGAS, TRONCOS, TABLAS, LISTONES&quot;
    },
    {
        &quot;gir_id&quot;: 902,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PIEZAS DE ESTRUCTURA PRE - FABRICADAS, TABAQUERIAS Y OTROS PRODUCTOS PRODUCIDOS SIMILARES&quot;
    },
    {
        &quot;gir_id&quot;: 903,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE MARCOS, VENTANAS, PUERTAS Y OTROS PRODUCTOS SIMILARES DE CARPINTER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 904,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE MUEBLES Y ACCESORIOS (EXCEPTO LOS PRINCIPALMENTE METALICOS)&quot;
    },
    {
        &quot;gir_id&quot;: 905,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE ENVASES DE EMBALAJE HECHOS DE CART&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 906,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE FIBRAS VULCANIZADAS&quot;
    },
    {
        &quot;gir_id&quot;: 907,
        &quot;gir_descripcion&quot;: &quot;CARPINTER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 908,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE ENVASES DE MADERA&quot;
    },
    {
        &quot;gir_id&quot;: 909,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE ENVASES DE CA&Ntilde;A&quot;
    },
    {
        &quot;gir_id&quot;: 910,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE ARTICULOS MENUDOS DE CA&Ntilde;A&quot;
    },
    {
        &quot;gir_id&quot;: 911,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS DE MADERA&quot;
    },
    {
        &quot;gir_id&quot;: 912,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS DE CORCHO&quot;
    },
    {
        &quot;gir_id&quot;: 913,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE CAJAS Y BOLSAS DE PAPEL&quot;
    },
    {
        &quot;gir_id&quot;: 914,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE CAJAS Y BOLSAS DE MATERIALES NO TEXTILES&quot;
    },
    {
        &quot;gir_id&quot;: 915,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE CAJAS Y BOLSAS PLASTICAS&quot;
    },
    {
        &quot;gir_id&quot;: 916,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE SOBRES DE PAPEL NO MEMBRETEADO&quot;
    },
    {
        &quot;gir_id&quot;: 917,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS DE PULPA DE MADERA, PAPEL Y CART&Oacute;N, TALES COMO PAPEL O CART&Oacute;N ENLUCIDO Y SATINADO,&quot;
    },
    {
        &quot;gir_id&quot;: 918,
        &quot;gir_descripcion&quot;: &quot;ENGOMADO Y LAMINADO PLATOS Y UTENSILIOS DE PULPA, TAPONES DE BOTELLAS, PAPEL DE EMPAPELAR, TOALLAS,&quot;
    },
    {
        &quot;gir_id&quot;: 919,
        &quot;gir_descripcion&quot;: &quot;PAPEL HIGI&Eacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 920,
        &quot;gir_descripcion&quot;: &quot;EDICI&Oacute;N DE DIARIOS Y OTRAS PUBLICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 921,
        &quot;gir_descripcion&quot;: &quot;PUBLICACI&Oacute;N DE DIARIOS Y OTRAS PUBLICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 922,
        &quot;gir_descripcion&quot;: &quot;IMPRESI&Oacute;N DE DIARIOS Y OTRAS PUBLICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 923,
        &quot;gir_descripcion&quot;: &quot;EDICI&Oacute;N DE LIBROS&quot;
    },
    {
        &quot;gir_id&quot;: 924,
        &quot;gir_descripcion&quot;: &quot;EDICI&Oacute;N DE PANFLETOS TECNICOS Y CIENTIFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 925,
        &quot;gir_descripcion&quot;: &quot;EDICI&Oacute;N DE PANFLETOS CULTURALES Y DE ENSE&Ntilde;ANZA&quot;
    },
    {
        &quot;gir_id&quot;: 926,
        &quot;gir_descripcion&quot;: &quot;PUBLICACI&Oacute;N DE LIBROS&quot;
    },
    {
        &quot;gir_id&quot;: 927,
        &quot;gir_descripcion&quot;: &quot;PUBLICACI&Oacute;N DE PANFLETOS TECNICOS Y CIENTIFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 928,
        &quot;gir_descripcion&quot;: &quot;PUBLICACI&Oacute;N DE PANFLETOS CULTURALES Y DE ENSE&Ntilde;ANZA&quot;
    },
    {
        &quot;gir_id&quot;: 929,
        &quot;gir_descripcion&quot;: &quot;IMPRESI&Oacute;N DE LIBROS&quot;
    },
    {
        &quot;gir_id&quot;: 930,
        &quot;gir_descripcion&quot;: &quot;IMPRESI&Oacute;N DE PANFLETOS CULTURALES Y DE ENSE&Ntilde;ANZA&quot;
    },
    {
        &quot;gir_id&quot;: 931,
        &quot;gir_descripcion&quot;: &quot;IMPRESI&Oacute;N DE PANFLETOS TECNICOS Y CIENTIFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 932,
        &quot;gir_descripcion&quot;: &quot;EMPASTE DE LIBROS&quot;
    },
    {
        &quot;gir_id&quot;: 933,
        &quot;gir_descripcion&quot;: &quot;EMPASTE DE PANFLETOS  TECNICOS Y CIENTIFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 934,
        &quot;gir_descripcion&quot;: &quot;EMPASTE DE PANFLETOS CULTURALES Y DE ENSE&Ntilde;ANZA&quot;
    },
    {
        &quot;gir_id&quot;: 935,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE CUADERNOS&quot;
    },
    {
        &quot;gir_id&quot;: 936,
        &quot;gir_descripcion&quot;: &quot;TRABAJOS DE IMPRESI&Oacute;N EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 937,
        &quot;gir_descripcion&quot;: &quot;TRABAJOS DE ENCUADERNACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 938,
        &quot;gir_descripcion&quot;: &quot;IMPRENTA&quot;
    },
    {
        &quot;gir_id&quot;: 939,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS GRAFICOS&quot;
    },
    {
        &quot;gir_id&quot;: 940,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS GRAFICOS DE TELEVISI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 941,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE SUSTANCIAS QUIMICAS INDUSTRIALES, BASICAS&quot;
    },
    {
        &quot;gir_id&quot;: 942,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE DESINFECTANTES Y PLAGUICIDAS&quot;
    },
    {
        &quot;gir_id&quot;: 943,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE RESINAS SINT&Eacute;TICAS, MATERIALES PLASTICOS Y FIBRA ARTIFICIALES, EXCEP. VIDRIO&quot;
    },
    {
        &quot;gir_id&quot;: 944,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PINTURAS Y BARNICES; LACAS&quot;
    },
    {
        &quot;gir_id&quot;: 945,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS DIVERSOS, DERIVADOS DEL PETR&Oacute;LEO Y DEL CARB&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 946,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS BIOLOGICOS, TALES COMO VACUNAS BACTERIAS Y VIROIDES, SUEROS Y PLASMAS&quot;
    },
    {
        &quot;gir_id&quot;: 947,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE GLICERINA CRUDA Y REFINADA, PROCEDENTE DE ACEITES Y GRASAS ANIMALES Y VEGETALES&quot;
    },
    {
        &quot;gir_id&quot;: 948,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ADHESIVOS, COLAS, APRESIVOS Y CEMENTOS EXCEP. LOS CONTOL&Oacute;GICOS OBTENIDOS DE SUSTANCIAS ANIMALES,&quot;
    },
    {
        &quot;gir_id&quot;: 949,
        &quot;gir_descripcion&quot;: &quot;VEGETALES O PLASTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 950,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE SUSTANCIAS QUIMICAS&quot;
    },
    {
        &quot;gir_id&quot;: 951,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE SUSTANCIAS DE PROCEDIMIENTOS BOTANICOS (ANTIBIOTICOS, QUININA, OPIOS, DERIVADOS DE ESTRIGNINA,&quot;
    },
    {
        &quot;gir_id&quot;: 952,
        &quot;gir_descripcion&quot;: &quot;CAFEINA Y VITAMINAS)&quot;
    },
    {
        &quot;gir_id&quot;: 953,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N DE PREPARADOS FARMACEUTICOS (PARA USO MEDICO VETERINARIO)&quot;
    },
    {
        &quot;gir_id&quot;: 954,
        &quot;gir_descripcion&quot;: &quot;LABORATORIO FARMACEUTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 955,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE JABONES&quot;
    },
    {
        &quot;gir_id&quot;: 956,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE DETERGENTES SINTETICOS&quot;
    },
    {
        &quot;gir_id&quot;: 957,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE SHAMPUS&quot;
    },
    {
        &quot;gir_id&quot;: 958,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE PASTA DENTRIFICA&quot;
    },
    {
        &quot;gir_id&quot;: 959,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE PREPARADOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 960,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PULIMENTOS DE MUEBLES, MATERIALES, ETC.&quot;
    },
    {
        &quot;gir_id&quot;: 961,
        &quot;gir_descripcion&quot;: &quot;VELAS Y CERAS ABRILLANTADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 962,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE DESINFECTANTES Y DESODORIZANTES, HUMECTADORES, EMULCIONADORES Y PENETRANTES&quot;
    },
    {
        &quot;gir_id&quot;: 963,
        &quot;gir_descripcion&quot;: &quot;SUSTANCIAS QUIMICAS PREPARADASPARA FOTOGRAF&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 964,
        &quot;gir_descripcion&quot;: &quot;PAPEL Y TELAS SENSIBLES&quot;
    },
    {
        &quot;gir_id&quot;: 965,
        &quot;gir_descripcion&quot;: &quot;TINTAS Y NEGRO DE HUMO&quot;
    },
    {
        &quot;gir_id&quot;: 966,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE COMPUESTOS IMPERMEABILIZANTES COMPUESTOS PARA TRATAR METALES, ACEITES Y AGUA&quot;
    },
    {
        &quot;gir_id&quot;: 967,
        &quot;gir_descripcion&quot;: &quot;ABONOS PARA LA AGRICULTURA&quot;
    },
    {
        &quot;gir_id&quot;: 968,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE CAMARAS  Y LLANTAS DE CAUCHO NATURAL Y SINTETICO PARA AUTOMOVILES, CAMIONES, AERONAVES,&quot;
    },
    {
        &quot;gir_id&quot;: 969,
        &quot;gir_descripcion&quot;: &quot;TRACTORES Y OTRO TIPO DE EQUIPOS&quot;
    },
    {
        &quot;gir_id&quot;: 970,
        &quot;gir_descripcion&quot;: &quot;RENCAUCHADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 971,
        &quot;gir_descripcion&quot;: &quot;REPARACI&Oacute;N DE LLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 972,
        &quot;gir_descripcion&quot;: &quot;RECONSTRUCCI&Oacute;N DE LLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 973,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE PRODUCTOS DE  CAUCHO NATURAL O SINT&Eacute;TICO, GUTAPERCHA Y OTROS SIMILARES&quot;
    },
    {
        &quot;gir_id&quot;: 974,
        &quot;gir_descripcion&quot;: &quot;COMO GUANTES, ESTERAS, ESPONJAS Y OTROS PRODUCTOS VULCANIZADOS&quot;
    },
    {
        &quot;gir_id&quot;: 975,
        &quot;gir_descripcion&quot;: &quot;REPELADO, MEZCLA, LAMINACI&Oacute;N, CORTE EN TROZOS Y DEM&Aacute;S PROCESOS RELACIONADOS  CON  LA  ELABORACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 976,
        &quot;gir_descripcion&quot;: &quot;DEL CAUCHO NATURAL&quot;
    },
    {
        &quot;gir_id&quot;: 977,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE CALZADO A BASE DE CAUCHO VULCANIZADO O MOLDEADO&quot;
    },
    {
        &quot;gir_id&quot;: 978,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS DE CAUCHO PARA USOS  INDUSTRIALES, MECANICOS, ARTICULOS ESPECIALES DIVERSOS&quot;
    },
    {
        &quot;gir_id&quot;: 979,
        &quot;gir_descripcion&quot;: &quot;FABRICACI&Oacute;N DE PRODUCTOS PLASTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 980,
        &quot;gir_descripcion&quot;: &quot;FABRICA DE ARTICULOS DE COCINA PARA PREPARAR, SERVIR O ALMACENAR Y BEBIDAS DE LOZA&quot;
    },
    {
        &quot;gir_id&quot;: 981,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ARTICULOS ELECTRICOS DE PORCELANA&quot;
    },
    {
        &quot;gir_id&quot;: 982,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ARTICULOS DE LOZA ARTISTICA&quot;
    },
    {
        &quot;gir_id&quot;: 983,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ARTICULOS DE LOZA INDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 984,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE ARTICULOS DE LABORATORIO&quot;
    },
    {
        &quot;gir_id&quot;: 985,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE OBJETOS DE PIEDRA&quot;
    },
    {
        &quot;gir_id&quot;: 986,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE OBJETOS DE BARRO&quot;
    },
    {
        &quot;gir_id&quot;: 987,
        &quot;gir_descripcion&quot;: &quot;FABRICACION DE FLOREROS DE ARCILLA ROJA SIN VIDRIAR&quot;
    },
    {
        &quot;gir_id&quot;: 988,
        &quot;gir_descripcion&quot;: &quot;TALLERES DE ARTESANIAS DE BARRO&quot;
    },
    {
        &quot;gir_id&quot;: 989,
        &quot;gir_descripcion&quot;: &quot;TALLERES DE ARTESANIAS DE PIEDRA&quot;
    },
    {
        &quot;gir_id&quot;: 990,
        &quot;gir_descripcion&quot;: &quot;TALLERES DE ARTESANIAS DE PORCELANA&quot;
    },
    {
        &quot;gir_id&quot;: 991,
        &quot;gir_descripcion&quot;: &quot;LUSTRADO DE CALZADOS&quot;
    },
    {
        &quot;gir_id&quot;: 992,
        &quot;gir_descripcion&quot;: &quot;GALERIA COMERCIAL&quot;
    },
    {
        &quot;gir_id&quot;: 993,
        &quot;gir_descripcion&quot;: &quot;GASOCENTRO&quot;
    },
    {
        &quot;gir_id&quot;: 994,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIA JURIDICA&quot;
    },
    {
        &quot;gir_id&quot;: 995,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIA JURIDICA&quot;
    },
    {
        &quot;gir_id&quot;: 996,
        &quot;gir_descripcion&quot;: &quot;CORREDORES DE SEGURO&quot;
    },
    {
        &quot;gir_id&quot;: 997,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MALETAS&quot;
    },
    {
        &quot;gir_id&quot;: 998,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE REPARACION DE MALETAS&quot;
    },
    {
        &quot;gir_id&quot;: 999,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRIMARIA Y SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1484,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CORTINAS Y ARTICULOS DE BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 1697,
        &quot;gir_descripcion&quot;: &quot;BODEGA CON VENTA DE ABARROTES FRUTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1702,
        &quot;gir_descripcion&quot;: &quot;SERVICIO TECNICO DE RELOJES&quot;
    },
    {
        &quot;gir_id&quot;: 1709,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE COMERCIALIZACION DE CULTIVOS DE PALTA&quot;
    },
    {
        &quot;gir_id&quot;: 1714,
        &quot;gir_descripcion&quot;: &quot;LENGUAJE&quot;
    },
    {
        &quot;gir_id&quot;: 1715,
        &quot;gir_descripcion&quot;: &quot;PSICOL&Oacute;GICA&quot;
    },
    {
        &quot;gir_id&quot;: 1757,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES PERSONALES EN INGENIER&Iacute;A CIVIL&quot;
    },
    {
        &quot;gir_id&quot;: 1758,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPORTACIONES DE PRODUCTOS AGROINDUSTRIALES&quot;
    },
    {
        &quot;gir_id&quot;: 1759,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPORTADORES DE PRODUCTOS AGROINDUSTRIALES&quot;
    },
    {
        &quot;gir_id&quot;: 1760,
        &quot;gir_descripcion&quot;: &quot;MINIMARKET CON VENTA DE LICOR PARA LLEVAR&quot;
    },
    {
        &quot;gir_id&quot;: 1762,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORIA Y REPRESENTACION DE EMPRESAS CLASIFICACION DE RIESGO&quot;
    },
    {
        &quot;gir_id&quot;: 1763,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORIA Y REPRESENTACION DE EMPRESAS CLASIFICACION  DE RIESGO&quot;
    },
    {
        &quot;gir_id&quot;: 1764,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE CONSTRUCCION&quot;
    },
    {
        &quot;gir_id&quot;: 1765,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE CON VENTA DE LICOR COMO COMPLEMENTO DE COMIDA&quot;
    },
    {
        &quot;gir_id&quot;: 1766,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICOR SIN CONSUMO&quot;
    },
    {
        &quot;gir_id&quot;: 1767,
        &quot;gir_descripcion&quot;: &quot;DROGUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1768,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS PARA NI&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 1769,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1770,
        &quot;gir_descripcion&quot;: &quot;MEDICINA GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 1771,
        &quot;gir_descripcion&quot;: &quot;TERAPIA DE REHABILITACION FISICA&quot;
    },
    {
        &quot;gir_id&quot;: 1772,
        &quot;gir_descripcion&quot;: &quot;COMPRA, VENTA, ALQUILER DE BIENES E INMUEBLES PROPIOS O ALQUILADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1773,
        &quot;gir_descripcion&quot;: &quot;SALA DE EXHIBICION&quot;
    },
    {
        &quot;gir_id&quot;: 1774,
        &quot;gir_descripcion&quot;: &quot;VENTA DE TELAS Y TEJIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 1775,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA PARA CABALLERO&quot;
    },
    {
        &quot;gir_id&quot;: 1776,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA PARA DAMAS&quot;
    },
    {
        &quot;gir_id&quot;: 1777,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE REPARACION Y ARREGLO DE PRENDAS MENORES&quot;
    },
    {
        &quot;gir_id&quot;: 1778,
        &quot;gir_descripcion&quot;: &quot;CARNES Y PARRILLADAS&quot;
    },
    {
        &quot;gir_id&quot;: 1779,
        &quot;gir_descripcion&quot;: &quot;SEGURIDAD DE PERSONAL Y PROPIEDADES&quot;
    },
    {
        &quot;gir_id&quot;: 1780,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE COMIDA RAPIDA&quot;
    },
    {
        &quot;gir_id&quot;: 1781,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALFONBRAS&quot;
    },
    {
        &quot;gir_id&quot;: 1782,
        &quot;gir_descripcion&quot;: &quot;PISOS&quot;
    },
    {
        &quot;gir_id&quot;: 1783,
        &quot;gir_descripcion&quot;: &quot;CORTINAS Y TAPETES&quot;
    },
    {
        &quot;gir_id&quot;: 1784,
        &quot;gir_descripcion&quot;: &quot;DECORACION DE HOGARES&quot;
    },
    {
        &quot;gir_id&quot;: 1785,
        &quot;gir_descripcion&quot;: &quot;SPA TRATAMIENTOS FACIALES Y CORPORALES&quot;
    },
    {
        &quot;gir_id&quot;: 1786,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BEBIDAS&quot;
    },
    {
        &quot;gir_id&quot;: 1787,
        &quot;gir_descripcion&quot;: &quot;GOLOSINAS COMO COMPLEMENTO DE GIRO&quot;
    },
    {
        &quot;gir_id&quot;: 1788,
        &quot;gir_descripcion&quot;: &quot;PRODUCTOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1789,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA Y LENCERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1826,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPORTACIONES DE FRUTAS Y HORTALIZAS&quot;
    },
    {
        &quot;gir_id&quot;: 1761,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE ARTICULOS DE SOFTWARE, PRODUCTOS MEDICOS Y COMERCIALIZACION&quot;
    },
    {
        &quot;gir_id&quot;: 1790,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE PROCESAMIENTO DE DATOS (OFICINA ADMINISTRATIVA DE VENTAS POR MAYOR DE MATERIAS PRIMAS AGROPECUARIAS CON PROCESAMIENTO DE DATOS)&quot;
    },
    {
        &quot;gir_id&quot;: 1791,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES DIVERSOS (SERVICIOS PROFESIONALES, CONTABLES Y TRIBUTARIO)&quot;
    },
    {
        &quot;gir_id&quot;: 1792,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE - CHIFA&quot;
    },
    {
        &quot;gir_id&quot;: 1793,
        &quot;gir_descripcion&quot;: &quot;MINIMARKET&quot;
    },
    {
        &quot;gir_id&quot;: 1795,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE TRANSPORTE, TURISTICO Y PERSONAL)&quot;
    },
    {
        &quot;gir_id&quot;: 1796,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES OFICINAS (OFICINA ADMINISTRATIVA DE EXTRACCION DE PRODUCTOS HIDROBIOLOGICOS)&quot;
    },
    {
        &quot;gir_id&quot;: 1797,
        &quot;gir_descripcion&quot;: &quot;JUGUERIA - SANDWICHERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1798,
        &quot;gir_descripcion&quot;: &quot;BODEGA - VENTA DE PAN Y PRODUCTOS DE PANADERIA (PANADERIA, PASTELERIA)&quot;
    },
    {
        &quot;gir_id&quot;: 1799,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINA ADMINISTRATIVA DE SERVICIOS DE TERCERIZACION Y REPRESENTACION DE EQUIPOS Y PRODUCTOS DE FERRETERIA)&quot;
    },
    {
        &quot;gir_id&quot;: 1800,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE ARQUITECTURA, INGENIERIA Y AGRIMENSURA (DECORACION DE INTERIORES)&quot;
    },
    {
        &quot;gir_id&quot;: 1802,
        &quot;gir_descripcion&quot;: &quot;FUENTE DE SODA - VENTA DE PAN Y PRODUCTOS DE PANADERIA (PANADERIA)&quot;
    },
    {
        &quot;gir_id&quot;: 1803,
        &quot;gir_descripcion&quot;: &quot;JUGUERIA (VENTA DE JUGOS Y GASEOSAS)&quot;
    },
    {
        &quot;gir_id&quot;: 1804,
        &quot;gir_descripcion&quot;: &quot;ARREGLO DE PRENDAS&quot;
    },
    {
        &quot;gir_id&quot;: 1805,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ALQUILER&quot;
    },
    {
        &quot;gir_id&quot;: 1806,
        &quot;gir_descripcion&quot;: &quot;VENTA DE EQUIPOS Y MAQUINARIA DE CONSTRUCCION&quot;
    },
    {
        &quot;gir_id&quot;: 1807,
        &quot;gir_descripcion&quot;: &quot;EXPLOTACION DE MINAS CON PROCESAMIENTO DE DATOS Y DE EQUIPOS EN INGENIERIA CIVIL&quot;
    },
    {
        &quot;gir_id&quot;: 1809,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES COMO ASESOR DE MARKETING&quot;
    },
    {
        &quot;gir_id&quot;: 1810,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE OBRAS DE CONSTRUCCION Y SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1811,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ENTREGA DE TARJETAS DE CREDITO&quot;
    },
    {
        &quot;gir_id&quot;: 1812,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DIVERSOS PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 1813,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE LUMINARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1814,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE KARATE&quot;
    },
    {
        &quot;gir_id&quot;: 1815,
        &quot;gir_descripcion&quot;: &quot;EJERCICIO DE LA PROFESION COMO ABOGADA MAGISTER EN ADMINISTRACION&quot;
    },
    {
        &quot;gir_id&quot;: 1817,
        &quot;gir_descripcion&quot;: &quot;EMBARQUES MARITIMOS Y AEREOS DE OFICINA - OFICINA ADMINISTRATIVA CON PROCESAMIENTOS DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1818,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS VETERINARIOS Y ACCESORIOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1819,
        &quot;gir_descripcion&quot;: &quot;EQUIPOS PARA PISCINA Y THERMAS&quot;
    },
    {
        &quot;gir_id&quot;: 1821,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRESTACION DE SERVICIOS ALIMENTICIOS EN GENERAL, IMPORTACION Y EXPORTACION CON PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1822,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROYECTO DE INGENIERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1823,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PARTICULAR&quot;
    },
    {
        &quot;gir_id&quot;: 1824,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES MINERAS CON PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1825,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PAN Y PRODUCTOS DE PANADERIA (PASTELERIA) Y CAFETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1828,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE TRANSPORTE TERRESTRE&quot;
    },
    {
        &quot;gir_id&quot;: 1829,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE VETERINARIA CON VENTA DE ACCESORIOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1830,
        &quot;gir_descripcion&quot;: &quot;ESCANEOS&quot;
    },
    {
        &quot;gir_id&quot;: 1831,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA Y CLUB DE NATACI&Oacute;N - GIMNASIO - AER&Oacute;BICOS - FUENTE DE SODA (CAFETER&Iacute;A Y REHIDRATANTES) Y VENTA DE ART&Iacute;CULOS DEPORTIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 1833,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE TRANSPORTE DE CARGA POR CARRETERA CON PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1834,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE MASAJES FACIALES&quot;
    },
    {
        &quot;gir_id&quot;: 1836,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE ACTIVIDADES DE CONSTRUCCION&quot;
    },
    {
        &quot;gir_id&quot;: 1837,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA CONSTRUCTORA&quot;
    },
    {
        &quot;gir_id&quot;: 1827,
        &quot;gir_descripcion&quot;: &quot;JUEGOS ELECTR&Oacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1832,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE FERRETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1816,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE DECORACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1838,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1839,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE VIDRIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1840,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE APARTADO POSTAL (RECEPCI&Oacute;N Y ENVI&Oacute; DE GIRO DE DINERO)&quot;
    },
    {
        &quot;gir_id&quot;: 1841,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE PANADER&Iacute;A Y SANDWICHERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1842,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA EN CONSULTOR&Iacute;A DE RECURSOS HUMANOS&quot;
    },
    {
        &quot;gir_id&quot;: 1844,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE MAQUINARIAS, EQUIPOS Y MATERIALES DE INDUSTRIA GRAFICA Y PL&Aacute;STICA&quot;
    },
    {
        &quot;gir_id&quot;: 1845,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA IMPORTADORA DE PERFUMER&Iacute;A Y PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1846,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE DANZAS FOLCL&Oacute;RICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1847,
        &quot;gir_descripcion&quot;: &quot;NIVELACION ESCOLAR Y PREPARACION UNIVERSITARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1848,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORAMIENTO DE EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 1849,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE INGENIERIA Y ARQUITECTURA&quot;
    },
    {
        &quot;gir_id&quot;: 1850,
        &quot;gir_descripcion&quot;: &quot;TELECOMERCIO DE COMERCIALIZACION&quot;
    },
    {
        &quot;gir_id&quot;: 1851,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA EN ASESORIA AMBIENTAL Y FORESTAL&quot;
    },
    {
        &quot;gir_id&quot;: 1852,
        &quot;gir_descripcion&quot;: &quot;VETERINARIA CON VENTA DE ALIMENTOS Y ACCESORIOS PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 1853,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRANSPORTE INTERNACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 1854,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1801,
        &quot;gir_descripcion&quot;: &quot;FARMACIA&quot;
    },
    {
        &quot;gir_id&quot;: 1855,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES INMOBILIARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1856,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A INFORM&Aacute;TICA Y SERVICIOS PUBLICITARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1857,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS Y CONTABILIDAD DE F&Aacute;BRICA DE MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 1858,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRESTACI&Oacute;N DE SERVICIOS DE MANTENIMIENTO DE INSTALACIONES EL&Eacute;CTRICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1859,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ARQUITECTURA&quot;
    },
    {
        &quot;gir_id&quot;: 1860,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE PRODUCTOS DE BOMBAS DE AGUA&quot;
    },
    {
        &quot;gir_id&quot;: 1010,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1861,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE SERVICIOS DE VIGILANCIA PRIVADA&quot;
    },
    {
        &quot;gir_id&quot;: 1794,
        &quot;gir_descripcion&quot;: &quot;FUENTE DE SODA Y CAFETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1863,
        &quot;gir_descripcion&quot;: &quot;CAJERO AUTOM&Aacute;TICO&quot;
    },
    {
        &quot;gir_id&quot;: 1864,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE ASESOR&Iacute;A Y CONSULTOR&Iacute;A CON PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1865,
        &quot;gir_descripcion&quot;: &quot;CAJERO CORRESPONSAL&quot;
    },
    {
        &quot;gir_id&quot;: 1866,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE VIAJE&quot;
    },
    {
        &quot;gir_id&quot;: 1867,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTORIA Y REPRESENTACIONES CON ALMACENAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1868,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AGENCIA DE VIAJES Y TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 1869,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MATERIALES Y EQUIPO DE OFICINA (PUBLICIDAD)&quot;
    },
    {
        &quot;gir_id&quot;: 1870,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES COMO INGENIERO CIVIL&quot;
    },
    {
        &quot;gir_id&quot;: 1873,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N DE PRODUCTOS CON PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1874,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 1875,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRANSPORTE VEHICULAR&quot;
    },
    {
        &quot;gir_id&quot;: 1876,
        &quot;gir_descripcion&quot;: &quot;PEDIATRAS (CONSULTORIO MEDICO - PEDIATRIA)&quot;
    },
    {
        &quot;gir_id&quot;: 1877,
        &quot;gir_descripcion&quot;: &quot;PRODUCTOS DE PANADER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1878,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CARNES A LA PARRILLA CON LICOR COMO COMPLEMENTO DE COMIDA&quot;
    },
    {
        &quot;gir_id&quot;: 1879,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO - NIVEL SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1880,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS POSTALES NACIONALES E INTERNACIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 1533,
        &quot;gir_descripcion&quot;: &quot;FERRETER&Iacute;A SIN VENTA DE MATERIALES DE CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1140,
        &quot;gir_descripcion&quot;: &quot;VERDULER&Iacute;A Y FRUTER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1881,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA Y SERVICIOS DE EQUIPOS EL&Eacute;CTRICOS Y ELECTROMEC&Aacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1882,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A EMPRESARIAL CON PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1883,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA (DE SERVICIOS DE PROCESAMIENTO DE DATOS Y COMERCIALIZACI&Oacute;N DE GRANOS DE ARROZ)&quot;
    },
    {
        &quot;gir_id&quot;: 1884,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS PARA ACTIVIDADES DE CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1885,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACI&Oacute;N DE EMPRESA DE MATERIAL CIENT&Iacute;FICO Y SUMINISTRO PARA INDUSTRIAS&quot;
    },
    {
        &quot;gir_id&quot;: 1886,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS Y COMERCIALIZACI&Oacute;N DE PRODUCTOS DE INSUMOS AGROPECUARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1887,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE EMPLEO&quot;
    },
    {
        &quot;gir_id&quot;: 1888,
        &quot;gir_descripcion&quot;: &quot;DECORACI&Oacute;N DE INTERIORES&quot;
    },
    {
        &quot;gir_id&quot;: 1889,
        &quot;gir_descripcion&quot;: &quot;VENTA Y CONFECCI&Oacute;N DE CORTINAS&quot;
    },
    {
        &quot;gir_id&quot;: 1890,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SEGURIDAD DE VIGILANCIA&quot;
    },
    {
        &quot;gir_id&quot;: 1891,
        &quot;gir_descripcion&quot;: &quot;COMUNICACI&Oacute;N TELEF&Oacute;NICA&quot;
    },
    {
        &quot;gir_id&quot;: 1044,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 1892,
        &quot;gir_descripcion&quot;: &quot;CEVICHER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1893,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES PERSONALES&quot;
    },
    {
        &quot;gir_id&quot;: 1894,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS PROFESIONALES COMO ENTRENADOR Y ACTIVIDADES DEPORTIVAS&quot;
    },
    {
        &quot;gir_id&quot;: 1895,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE TRATAMIENTO DE RESIDUOS S&Oacute;LIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 1896,
        &quot;gir_descripcion&quot;: &quot;BOUTIQUE&quot;
    },
    {
        &quot;gir_id&quot;: 1897,
        &quot;gir_descripcion&quot;: &quot;MONTURAS DE LENTES&quot;
    },
    {
        &quot;gir_id&quot;: 1898,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MONTURAS Y LENTES&quot;
    },
    {
        &quot;gir_id&quot;: 1899,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE SERVICIOS DE TELECOMUNICACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1900,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE GESTI&Oacute;N COMERCIAL DE MAQUINAS, HERRAMIENTAS Y EQUIPOS PARA LA INDUSTRIA METALMEC&Aacute;NICA&quot;
    },
    {
        &quot;gir_id&quot;: 1901,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA PARA NI&Ntilde;OS Y BEBES&quot;
    },
    {
        &quot;gir_id&quot;: 1902,
        &quot;gir_descripcion&quot;: &quot;FERRETERIA SIN VENTA DE MATERIALES DE CONSTRUCCION&quot;
    },
    {
        &quot;gir_id&quot;: 1903,
        &quot;gir_descripcion&quot;: &quot;ALUMINIO&quot;
    },
    {
        &quot;gir_id&quot;: 1904,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINSITRATIVA DE PROCESAMIENTO DE DATOS DE CONSTRUCCI&Oacute;N Y REPARACI&Oacute;N DE BUQUES Y AISLAMIENTO TERMICO&quot;
    },
    {
        &quot;gir_id&quot;: 1905,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTAS DE PARTES, PIEZAS Y ACCESORIOS, MANTENIMIENTO Y REPARACI&Oacute;N DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1906,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS FINANCIEROS&quot;
    },
    {
        &quot;gir_id&quot;: 1907,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE DANZAS Y BAILES&quot;
    },
    {
        &quot;gir_id&quot;: 1908,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE NEGOCIOS INMOBILIARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1909,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE SANEAMIENTO AMBIENTAL Y LIMPIEZA AMBIENTAL&quot;
    },
    {
        &quot;gir_id&quot;: 1910,
        &quot;gir_descripcion&quot;: &quot;MULTISERVICIOS VARIOS (TIPO AUTOSERVICIOS)&quot;
    },
    {
        &quot;gir_id&quot;: 1911,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CAPACITACI&Oacute;N Y ASESORAMIENTO EN PREVENCI&Oacute;N DE RIESGOS DE SEGURIDAD EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 1912,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 1913,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA LA VENTA DE EQUIPOS DE SEGURIDAD Y PROTECCI&Oacute;N PERSONAL&quot;
    },
    {
        &quot;gir_id&quot;: 1914,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE ARQUITECTURA, INGENIER&Iacute;A Y AGRIMENSURA (OFICINA ADMINISTRATIVA DE EMPRESA CONSTRUCTORA)&quot;
    },
    {
        &quot;gir_id&quot;: 1403,
        &quot;gir_descripcion&quot;: &quot;LICORER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1915,
        &quot;gir_descripcion&quot;: &quot;SIN CONSUMO&quot;
    },
    {
        &quot;gir_id&quot;: 1916,
        &quot;gir_descripcion&quot;: &quot;ALQUILER DE JUEGOS ELECTR&Oacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1917,
        &quot;gir_descripcion&quot;: &quot;PLAY STATION&quot;
    },
    {
        &quot;gir_id&quot;: 1918,
        &quot;gir_descripcion&quot;: &quot;TELEVISI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1919,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ARQUITECTURA&quot;
    },
    {
        &quot;gir_id&quot;: 1920,
        &quot;gir_descripcion&quot;: &quot;BIJOUTER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1921,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES OFICINAS (OFICINA ADMINISTRATIVA DE VENTA DE MATERIAL PARA MANTENIMIENTO DE AERONAVES)&quot;
    },
    {
        &quot;gir_id&quot;: 1871,
        &quot;gir_descripcion&quot;: &quot;BANCOS COMERCIALES, CAJEROS AUTOM&Aacute;TICOS (AGENCIA BANCARIA)&quot;
    },
    {
        &quot;gir_id&quot;: 1922,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ARQUITECTURA, INGENIER&Iacute;A Y AGRIMENSURA&quot;
    },
    {
        &quot;gir_id&quot;: 1923,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE HERRAMIENTAS DE PERFORACI&Oacute;N DE ROCAS&quot;
    },
    {
        &quot;gir_id&quot;: 1925,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE ARBITRADORES&quot;
    },
    {
        &quot;gir_id&quot;: 1927,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE CONCILIACI&Oacute;N EXTRAJUDICIAL&quot;
    },
    {
        &quot;gir_id&quot;: 1928,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN CIENCIAS CONTABLES&quot;
    },
    {
        &quot;gir_id&quot;: 1929,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 1930,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIER&Iacute;A Y PROYECTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1931,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINITRATIVA DE IMPORTADORES DE PRODUCTOS NATURALES E IMPORTADORA ENVASES DE CART&Oacute;N CORRUGADO&quot;
    },
    {
        &quot;gir_id&quot;: 1932,
        &quot;gir_descripcion&quot;: &quot;PASTELER&Iacute;A FINA, EXHIBICI&Oacute;N Y VENTA&quot;
    },
    {
        &quot;gir_id&quot;: 1933,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE RECOLECCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1934,
        &quot;gir_descripcion&quot;: &quot;DISTRIBUCI&Oacute;N DE ROPA&quot;
    },
    {
        &quot;gir_id&quot;: 1935,
        &quot;gir_descripcion&quot;: &quot;ACCESORIOS PARA FLORER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1937,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACION DE VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1938,
        &quot;gir_descripcion&quot;: &quot;REPUESTOS ACCESORIOS Y MANETNIMIENTO DE VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1939,
        &quot;gir_descripcion&quot;: &quot;REPARACION Y COMERCIALIZACION DE MAQUINARIAS PARA LA INDUSTRIA&quot;
    },
    {
        &quot;gir_id&quot;: 1941,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE LABORATORIO FARMACEUTICO&quot;
    },
    {
        &quot;gir_id&quot;: 1942,
        &quot;gir_descripcion&quot;: &quot;IMPORTACION Y DISTRIBUCION DE MEDICAMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1943,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTADORA, EXPORTADORA Y COMERCIALIZADORA DE PRODUCTOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1944,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE ILUMINACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1945,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE ELABORACI&Oacute;N DE PROYECTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1946,
        &quot;gir_descripcion&quot;: &quot;COMPRA - VENTA Y CONSIGNACI&Oacute;N DE AUTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1947,
        &quot;gir_descripcion&quot;: &quot;COMPRA Y CONSIGNACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1948,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A, CONSULTOR&Iacute;A Y COMERCIALIZAC&Iacute;ON TEXTIL&quot;
    },
    {
        &quot;gir_id&quot;: 1949,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEH&Iacute;CULOS NUEVOS&quot;
    },
    {
        &quot;gir_id&quot;: 1950,
        &quot;gir_descripcion&quot;: &quot;MANTENIMIENTO DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1951,
        &quot;gir_descripcion&quot;: &quot;VENTA DE V&Iacute;VERES, ROPA Y MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 1952,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MOBILIARIO EN GENERAL DE MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 1953,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROMOCI&Oacute;N Y DIFUSI&Oacute;N DE ACTIVIDADES CULTURALES Y ART&Iacute;STICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1954,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS ODONTOL&Oacute;GICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1955,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EDITORIAL DE REVISTA&quot;
    },
    {
        &quot;gir_id&quot;: 1956,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE ESTRUCTURAS METALICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1957,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AGENCIA DE SEDAPAL&quot;
    },
    {
        &quot;gir_id&quot;: 1958,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE ORGANIZACI&Oacute;N INTEGRAL DE EVENTOS, SOCIALES Y CORPORATIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 1959,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE RECOLECCI&Oacute;N Y DISTRIBUCI&Oacute;N DE ROPA POR LAVANDER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 1960,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE LAVANDER&Iacute;A LOCAL DE RECOLECCI&Oacute;N DE PRENDAS&quot;
    },
    {
        &quot;gir_id&quot;: 1962,
        &quot;gir_descripcion&quot;: &quot;VENTA DE &Uacute;TILES ESCOLARES&quot;
    },
    {
        &quot;gir_id&quot;: 1963,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS Y EQUIPOS CONTRA INCENDIO Y ASESOR&Iacute;A EXTERNA EN SISTEMA DE SEGURIDAD CONTRA INCENDIO&quot;
    },
    {
        &quot;gir_id&quot;: 1964,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES DIVERSOS COMO INGENIERO METAL&Uacute;RGICO&quot;
    },
    {
        &quot;gir_id&quot;: 1965,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE VENTA DE EQUIPOS DE LABORATORIO&quot;
    },
    {
        &quot;gir_id&quot;: 1966,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE VERIFICACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1967,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISTRIBUCI&Oacute;N DE PRODUCTOS ALIMENTICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1968,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE PANADER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1969,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES JUR&Iacute;DICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1970,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORAMIENTO EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 1971,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1972,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA LA INSTALACI&Oacute;N DE EQUIPOS DOMICILIARIOS DE TRATAMIENTO Y FILTRACI&Oacute;N DE AGUA, VENTA DE V&Iacute;VERES Y BEBIDAS NO ALCOH&Oacute;LICAS&quot;
    },
    {
        &quot;gir_id&quot;: 1973,
        &quot;gir_descripcion&quot;: &quot;POLICL&Iacute;NICO&quot;
    },
    {
        &quot;gir_id&quot;: 1974,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE ARQUITECTURA E INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 1975,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE PRODUCTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1976,
        &quot;gir_descripcion&quot;: &quot;VENTA DE LICOR COMO COMPLEMENTO&quot;
    },
    {
        &quot;gir_id&quot;: 1977,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE EMPRESA DE TRANSPORTE TUR&Iacute;STICO&quot;
    },
    {
        &quot;gir_id&quot;: 1978,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COMPUTADORAS Y SOFTWARE&quot;
    },
    {
        &quot;gir_id&quot;: 1979,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE COMPUTADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 1980,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COMPUTADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 1981,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MANTENIMIENTO DE REPUESTOS DE MAQUINAS Y EQUIPOS DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 1982,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE REPARACI&Oacute;N DE COMPUTADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 1983,
        &quot;gir_descripcion&quot;: &quot;COLEGIO BILINGUE DE EDUCACION INICIAL, PRIMARIA Y SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1984,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROCESAMIENTO DE DATOS DE EMPRESA DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1640,
        &quot;gir_descripcion&quot;: &quot;EQUIPOS HIDR&Aacute;ULICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1986,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE CONSULTOR&Iacute;A INMOBILIARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1988,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UNIFORMES ESCOLARES&quot;
    },
    {
        &quot;gir_id&quot;: 1989,
        &quot;gir_descripcion&quot;: &quot;ART&Iacute;CULOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1990,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y COMERCIALIZACI&Oacute;N DE INSUMOS Y EQUIPOS PARA LA MINER&Iacute;A Y INDUSTRIA&quot;
    },
    {
        &quot;gir_id&quot;: 1991,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSTRUCCI&Oacute;N DE EDIFICIOS Y SERVICIOS INMOBILIARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 1992,
        &quot;gir_descripcion&quot;: &quot;ASOCIACIONES CULTURALES (TALLERES CULTURALES)&quot;
    },
    {
        &quot;gir_id&quot;: 1993,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE ASESORIA Y CONSULTORIA BIOLOGICA Y GENETICA&quot;
    },
    {
        &quot;gir_id&quot;: 1994,
        &quot;gir_descripcion&quot;: &quot;ESTUDIOS CONTABLES&quot;
    },
    {
        &quot;gir_id&quot;: 1995,
        &quot;gir_descripcion&quot;: &quot;ASESOR&Iacute;A CONTABLE, TRIBUTARIA, ESTUDIO CONTABLE&quot;
    },
    {
        &quot;gir_id&quot;: 1996,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CASETA DE VENTAS&quot;
    },
    {
        &quot;gir_id&quot;: 1997,
        &quot;gir_descripcion&quot;: &quot;LAVADO DE ROPA&quot;
    },
    {
        &quot;gir_id&quot;: 1961,
        &quot;gir_descripcion&quot;: &quot;LIBRER&Iacute;A Y BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 1985,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS Y EQUIPOS DE USO DOM&Eacute;STICO&quot;
    },
    {
        &quot;gir_id&quot;: 1926,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE CONCILIACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 1998,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE TE&Ntilde;IDO&quot;
    },
    {
        &quot;gir_id&quot;: 1999,
        &quot;gir_descripcion&quot;: &quot;DISTRIBUCI&Oacute;N POR LAS LAVANDER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 2000,
        &quot;gir_descripcion&quot;: &quot;RECEPCI&Oacute;N DE ROPA&quot;
    },
    {
        &quot;gir_id&quot;: 2001,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE RECOLECCI&Oacute;N Y DISTRIBUCI&Oacute;N POR LAS LAVANDER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 2002,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE COSTURA&quot;
    },
    {
        &quot;gir_id&quot;: 2003,
        &quot;gir_descripcion&quot;: &quot;RENOVACI&Oacute;N DE CALZADO&quot;
    },
    {
        &quot;gir_id&quot;: 2004,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MASAJES FACIALES Y CORPORALES&quot;
    },
    {
        &quot;gir_id&quot;: 2005,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE PRCESAMIENTO DE DATOS Y COMERCIALIZACION DE GRANOS DE ARROZ&quot;
    },
    {
        &quot;gir_id&quot;: 2006,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRESENTACIONES CORPORATIVAS&quot;
    },
    {
        &quot;gir_id&quot;: 2007,
        &quot;gir_descripcion&quot;: &quot;UNIVERSIDADES (EDUCACI&Oacute;N SUPERIOR - UNIVERSIDAD)&quot;
    },
    {
        &quot;gir_id&quot;: 2008,
        &quot;gir_descripcion&quot;: &quot;OTRAS ESPECIALIDADES&quot;
    },
    {
        &quot;gir_id&quot;: 2009,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO MEDICO VETERINARIO&quot;
    },
    {
        &quot;gir_id&quot;: 2010,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS Y ALIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2011,
        &quot;gir_descripcion&quot;: &quot;ARREGLOS FLORALES&quot;
    },
    {
        &quot;gir_id&quot;: 2012,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIER&Iacute;A, DISE&Ntilde;O Y CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2013,
        &quot;gir_descripcion&quot;: &quot;DISE&Ntilde;O DE INTERIORES&quot;
    },
    {
        &quot;gir_id&quot;: 2014,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A DE EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 2015,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMPRA VENTA, ALQUILER DE BIENES INMUEBLES PROPIOS O ALQUILADOS&quot;
    },
    {
        &quot;gir_id&quot;: 2016,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A T&Eacute;CNICA PARA EL SECTOR AGROPECUARIO&quot;
    },
    {
        &quot;gir_id&quot;: 2017,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA PRESTADORA DE SERVICIOS DE RESIDUOS SOLIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 2018,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE ADMINISTRATIVA DE IMPORTACION Y EXPORTACION DE MATERIAL RECICLAJE&quot;
    },
    {
        &quot;gir_id&quot;: 2019,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE TRANSPORTE DE CARGA&quot;
    },
    {
        &quot;gir_id&quot;: 2020,
        &quot;gir_descripcion&quot;: &quot;DISE&Ntilde;O DE INTERIORES Y VENTA DE PRODUCTOS DIVERSOS PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 2021,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA COMERCIALIZADORA DE MADERA&quot;
    },
    {
        &quot;gir_id&quot;: 2022,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE INGENIER&Iacute;A Y AGRIMENSURA&quot;
    },
    {
        &quot;gir_id&quot;: 2023,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2024,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTADOR Y EXPORTADOR DE PRODUCTOS INDUSTRIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2025,
        &quot;gir_descripcion&quot;: &quot;VENTA Y EXHIBICI&Oacute;N DE VEH&Iacute;CULOS NUEVOS, MANTENIMIENTO DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 2026,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO DE MEDICINA GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2027,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS NUTRICIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 2028,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE DECORACI&Oacute;N DE INTERIORES, ACTIVIDADES DE ARQUITECTURA&quot;
    },
    {
        &quot;gir_id&quot;: 2029,
        &quot;gir_descripcion&quot;: &quot;INSTALACI&Oacute;N DE CAMARAS DE VIGILANCIA, INSTALACIONES DE INTERCOMUNICADORES DE SISTEMAS DE PUERTAS DE GARAJE&quot;
    },
    {
        &quot;gir_id&quot;: 2030,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS Y ART&Iacute;CULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 2031,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE COMPUTADORAS Y SOFTWARE&quot;
    },
    {
        &quot;gir_id&quot;: 2032,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2033,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN ODONTOL&Oacute;GIA&quot;
    },
    {
        &quot;gir_id&quot;: 1358,
        &quot;gir_descripcion&quot;: &quot;EDUCACI&Oacute;N SUPERIOR UNIVERSITARIA CON SERVICIOS COMPLEMENTARIOS A LA COMUNIDAD (AN&Aacute;LISIS CL&Iacute;NICOS)&quot;
    },
    {
        &quot;gir_id&quot;: 2034,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VENTANAS Y MANPARAS&quot;
    },
    {
        &quot;gir_id&quot;: 2035,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONTRATISTAS GENERALES DE INSTALACIONES EL&Eacute;CTRICAS E INGENIER&Iacute;A EL&Eacute;CTRICA&quot;
    },
    {
        &quot;gir_id&quot;: 2036,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ALQUILER DE JUEGOS INFANTILES&quot;
    },
    {
        &quot;gir_id&quot;: 2037,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA CONTRATA DE MINAS PARA EXPLOTACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2038,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE REPARACI&Oacute;N Y ARREGLO MENOR DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 2039,
        &quot;gir_descripcion&quot;: &quot;VENTA Y EXHIBICI&Oacute;N DE MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2040,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A DE RECURSOS HUMANOS&quot;
    },
    {
        &quot;gir_id&quot;: 2041,
        &quot;gir_descripcion&quot;: &quot;COLEGIO ESPECIALIZADO PARA PRIMARIA Y SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2042,
        &quot;gir_descripcion&quot;: &quot;VENTA DE &Uacute;TILES DE ESCRITORIO&quot;
    },
    {
        &quot;gir_id&quot;: 2044,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTEFACTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2045,
        &quot;gir_descripcion&quot;: &quot;REPUESTOS DE FOTOCOPIADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 2046,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE EXPORTACI&Oacute;N Y FABRICACI&Oacute;N DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 2047,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A DE SOFTWARE&quot;
    },
    {
        &quot;gir_id&quot;: 2048,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS Y PRODUCTOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2049,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AGENCIA MAR&Iacute;TIMA&quot;
    },
    {
        &quot;gir_id&quot;: 2050,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE PRODUCTOS NATURALES&quot;
    },
    {
        &quot;gir_id&quot;: 2051,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TECNOLOG&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2052,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEF&Oacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2053,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE PRODUCTOS FARMAC&Eacute;UTICOS Y OTROS&quot;
    },
    {
        &quot;gir_id&quot;: 2054,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE INGENIER&Iacute;A Y COMERCIALIZACI&Oacute;N DE PRODUCTOS DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2055,
        &quot;gir_descripcion&quot;: &quot;VENTA DE DISFRACES&quot;
    },
    {
        &quot;gir_id&quot;: 2056,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A LEGAL Y EDUCACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2057,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2058,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A LOG&Iacute;STICA&quot;
    },
    {
        &quot;gir_id&quot;: 2059,
        &quot;gir_descripcion&quot;: &quot;COMPRA DE EQUIPOS DE COMUNICACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2060,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VIVERES Y BEBIDAS, LICORER&Iacute;A (SIN CONSUMO), VENTA DE PAN Y PRODUCTOS DE PANADER&Iacute;A, VENTA DE EMBUTIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 2061,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA Y CONTABLE DE EMPRESA EXPORTADORA DE MADERA&quot;
    },
    {
        &quot;gir_id&quot;: 2062,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE HIELO INDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2063,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AGENCIA DE CARGA INTERNACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2064,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2065,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE ART&Iacute;CULOS Y MATERIALES RELIGIOSOS&quot;
    },
    {
        &quot;gir_id&quot;: 2066,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MONTURA DE LENTES&quot;
    },
    {
        &quot;gir_id&quot;: 2067,
        &quot;gir_descripcion&quot;: &quot;UNIFORMES PARA EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 2068,
        &quot;gir_descripcion&quot;: &quot;VENTA Y EXHIBICI&Oacute;N DE VEH&Iacute;CULOS NUEVOS&quot;
    },
    {
        &quot;gir_id&quot;: 2069,
        &quot;gir_descripcion&quot;: &quot;TALLER DE MEC&Aacute;NICA MENOR&quot;
    },
    {
        &quot;gir_id&quot;: 2070,
        &quot;gir_descripcion&quot;: &quot;TELECOMERCIO DE ASESOR&Iacute;A EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2071,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2072,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA COMERCIALIZADORA DE ELEMENTOS DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2073,
        &quot;gir_descripcion&quot;: &quot;VENTA DE JUGUETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2074,
        &quot;gir_descripcion&quot;: &quot;PRODUCTOS DESCARTABLES&quot;
    },
    {
        &quot;gir_id&quot;: 2075,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE GESTI&Oacute;N DE PROYECTOS DE INGENIER&Iacute;A Y COMPRAS&quot;
    },
    {
        &quot;gir_id&quot;: 2076,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ACTIVIDADES DE MINERA&quot;
    },
    {
        &quot;gir_id&quot;: 2077,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A EDUCACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2079,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE SEGURIDAD Y VIGILANCIA&quot;
    },
    {
        &quot;gir_id&quot;: 2080,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ACTIVIDADES DE ARQUITECTURA E INGENIERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2081,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE DISE&Ntilde;OS DE SOFTWARE&quot;
    },
    {
        &quot;gir_id&quot;: 2082,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A DE PERSONAS Y EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 2083,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ASESOR&Iacute;A Y REPRESENTACI&Oacute;N DE EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 2084,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ARQUITECTURA E INGENIER&Iacute;A Y CONSTRUCCI&Oacute;N DE EDIFICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 2085,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA ALQUILER Y VENTA DE INMUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2086,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE TRANSPORTES&quot;
    },
    {
        &quot;gir_id&quot;: 2087,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A Y CONSULTOR&Iacute;A EMPRESARIAL EN MEDIO AMBIENTE, SUPERVISI&Oacute;N DE PROYECTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2088,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO DE MASAJES, TERAPIA F&Iacute;SICA Y REHABILITACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 3013,
        &quot;gir_descripcion&quot;: &quot;TABAQUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2089,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIER&Iacute;A, MINER&Iacute;A, CONSTRUCCI&Oacute;N Y MEDIO AMBIENTE&quot;
    },
    {
        &quot;gir_id&quot;: 2090,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ARQUITECTURA E INGENIER&Iacute;A Y OTRAS ACTIVIDADES EMPRESARIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2091,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ASESOR&Iacute;A Y CONSULTORIA EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2092,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE SERVICIOS DE INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2093,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA ORGANIZADORA DE EVENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2094,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROYECTOS DE INGENIER&Iacute;A Y CONSTRUCCI&Oacute;N CIVIL&quot;
    },
    {
        &quot;gir_id&quot;: 2095,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ALQUILER DE MAQUINARIA INDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2096,
        &quot;gir_descripcion&quot;: &quot;MONITOREO Y MANTENIMIENTO DE REDES DE TELECOMUNICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 2097,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN ARQUITECTURA&quot;
    },
    {
        &quot;gir_id&quot;: 2098,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PUBLICIDAD Y MARKETING&quot;
    },
    {
        &quot;gir_id&quot;: 2099,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE COBRANZA DE SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2100,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN ADMINISTRACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2101,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ACTIVIDADES DE INGENIERIA Y DE MINAS&quot;
    },
    {
        &quot;gir_id&quot;: 2102,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE TRANSPORTE TERRESTRE DE CARGA&quot;
    },
    {
        &quot;gir_id&quot;: 2103,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EVENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2104,
        &quot;gir_descripcion&quot;: &quot;MASOTERAPIA&quot;
    },
    {
        &quot;gir_id&quot;: 2105,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SALA DE VENTAS DE DISCOS DIAMANTADOS Y HERRAMIENTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2106,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE CONSULTOR&Iacute;A, ORGANIZACI&Oacute;N Y SERVICIOS TUR&Iacute;STICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1820,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE ARQUITECTURA, INGENIER&Iacute;A Y AGRIMENSURA&quot;
    },
    {
        &quot;gir_id&quot;: 2107,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSTRUCCI&Oacute;N Y OPERACIONES SUBACUATICAS&quot;
    },
    {
        &quot;gir_id&quot;: 2108,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ASESORAMIENTO EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2109,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE CONTRATISTAS GENERALES&quot;
    },
    {
        &quot;gir_id&quot;: 2111,
        &quot;gir_descripcion&quot;: &quot;TELECOMERCIO DE ALQUILER DE JUEGOS PARA NI&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 2112,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ORGANIZACI&Oacute;N DE EVENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2113,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A Y CAPACITACI&Oacute;N EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2114,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE PUBLICIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2115,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIA Y REPRESENTACI&Oacute;N PARA EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 2116,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE CONFECCIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1283,
        &quot;gir_descripcion&quot;: &quot;RECEPCI&Oacute;N Y ENVI&Oacute; DE DINERO&quot;
    },
    {
        &quot;gir_id&quot;: 2117,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INSTALACI&Oacute;N DE REDES SANITARIAS, AGUA, DESAGUE Y CONTRAINCENDIO&quot;
    },
    {
        &quot;gir_id&quot;: 2118,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ARQUITECTURA Y CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2119,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ARQUITECTURA, INGENIER&Iacute;A Y CONSULTA DE OBRA&quot;
    },
    {
        &quot;gir_id&quot;: 2120,
        &quot;gir_descripcion&quot;: &quot;SAUNA&quot;
    },
    {
        &quot;gir_id&quot;: 2121,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS NUTRITIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 2122,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 2123,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA AGROINDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2124,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE TELECOMUNICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 2125,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE IMPORTACIONES Y EXPORTACIONES DE PRODUCTOS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2126,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UTENSILIOS PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 2127,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UTENSILIOS PARA LA COCINA&quot;
    },
    {
        &quot;gir_id&quot;: 2128,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN ASESOR&Iacute;A DE EMPRESA&quot;
    },
    {
        &quot;gir_id&quot;: 2129,
        &quot;gir_descripcion&quot;: &quot;NOTAR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2130,
        &quot;gir_descripcion&quot;: &quot;ESTUDIO JUR&Iacute;DICO&quot;
    },
    {
        &quot;gir_id&quot;: 2131,
        &quot;gir_descripcion&quot;: &quot;TE&Ntilde;IDO DE ALFOMBRAS&quot;
    },
    {
        &quot;gir_id&quot;: 2132,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS Y ACCESORIOS &Oacute;PTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2133,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE JARDINER&Iacute;A Y FORESTALES&quot;
    },
    {
        &quot;gir_id&quot;: 2134,
        &quot;gir_descripcion&quot;: &quot;ASESORAMIENTO EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2135,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS &Oacute;PTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2136,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE COMERCIALIZACI&Oacute;N DE PRODUCTOS DE PL&Aacute;STICO&quot;
    },
    {
        &quot;gir_id&quot;: 2137,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE GERENCIA DE PROYECTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2138,
        &quot;gir_descripcion&quot;: &quot;VENTA DE UNIFORMES MEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2140,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A, CONSULTORIA EN GENERAL Y COMERCIALIZACI&Oacute;N DE PRODUCTOS DE COMPUTACI&Oacute;N EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2141,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BOLETOS&quot;
    },
    {
        &quot;gir_id&quot;: 2110,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE TODO TIPO POR CAT&Aacute;LOGO (TELECOMERCIO)&quot;
    },
    {
        &quot;gir_id&quot;: 2142,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS REALIZADOS POR M&Aacute;QUINAS EXPENDEDORAS&quot;
    },
    {
        &quot;gir_id&quot;: 2143,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;AS EMPRESARIALES, AUDITORIAS Y CAPACITACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 2144,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA A LA CRIANZA Y TENENCIA DE MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2145,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS PARA TATUAJES&quot;
    },
    {
        &quot;gir_id&quot;: 2146,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMIISTRATIVA DE AVITUALLAMIENTO DE NAVES&quot;
    },
    {
        &quot;gir_id&quot;: 2147,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA CONSULTOR&Iacute;A EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2148,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS Y VENTA DE EQUIPOS PARA LA MINER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2149,
        &quot;gir_descripcion&quot;: &quot;ASESOR&Iacute;A CONTABLE Y EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2150,
        &quot;gir_descripcion&quot;: &quot;ALMAC&Eacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2151,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA A LA VENTA DE PRODUCTOS ALIMENTICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2152,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ACTIVIDADES INFORM&Aacute;TICAS&quot;
    },
    {
        &quot;gir_id&quot;: 2153,
        &quot;gir_descripcion&quot;: &quot;BANCOS COMERCIALES (AGENCIA BANCARIA DE ENTREGA DE TARJETA)&quot;
    },
    {
        &quot;gir_id&quot;: 2154,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE MENSAJER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2155,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE PROYECTOS DE INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2156,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ASESOR&Iacute;A EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2157,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA EXPORTADORA DE PRODUCTOS HIDROBIOL&Oacute;GICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2158,
        &quot;gir_descripcion&quot;: &quot;RECEPCI&Oacute;N Y ENTREGA DE ROPA&quot;
    },
    {
        &quot;gir_id&quot;: 2159,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A Y ASESOR&Iacute;A DE RECURSOS HUMANOS&quot;
    },
    {
        &quot;gir_id&quot;: 2160,
        &quot;gir_descripcion&quot;: &quot;VENTA DE EQUIPOS CELULARES Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2161,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTESAN&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2162,
        &quot;gir_descripcion&quot;: &quot;UNIFORMES&quot;
    },
    {
        &quot;gir_id&quot;: 2163,
        &quot;gir_descripcion&quot;: &quot;ACCESORIOS DEPORTIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 2164,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA EL DESARROLLO DE SOFTWARE&quot;
    },
    {
        &quot;gir_id&quot;: 2165,
        &quot;gir_descripcion&quot;: &quot;VENTA Y EXHIBICI&Oacute;N DE PASTELES&quot;
    },
    {
        &quot;gir_id&quot;: 1550,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE LAVADO DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 1685,
        &quot;gir_descripcion&quot;: &quot;LENCER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2166,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ARQUITECTURA E INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2167,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE IMPORTACIONES DE REPUESTOS Y ACCESORIOS PARA LA INDUSTRIA&quot;
    },
    {
        &quot;gir_id&quot;: 2168,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISE&Ntilde;O Y COMERCIALIZACI&Oacute;N DE PRENDAS DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 2169,
        &quot;gir_descripcion&quot;: &quot;ENSE&Ntilde;ANZA PRE ESCOLAR PRIMARIA Y SECUNDARIA PRIVADA (CENTRO EDUCATIVO PRIVADO PARA ACTIVIDADES EDUCACIONALES PRE ESCOLARES, PRIMARIA Y SECUNDARIA)&quot;
    },
    {
        &quot;gir_id&quot;: 2170,
        &quot;gir_descripcion&quot;: &quot;M&Aacute;QUINAS TRAGAMONEDAS&quot;
    },
    {
        &quot;gir_id&quot;: 2171,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES INMOBILIARIAS Y ORGANIZACI&Oacute;N DE EVENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2172,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA A LA VENTA DE MAQUINARIA DE CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2173,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE VENTA DE ELECTROM&Eacute;STICOS Y C&Oacute;MPUTO&quot;
    },
    {
        &quot;gir_id&quot;: 2174,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A DE EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 2175,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A DE SISTEMAS&quot;
    },
    {
        &quot;gir_id&quot;: 2176,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPORTACI&Oacute;N, IMPORTACI&Oacute;N DE VENTA MINERALES, PRODUCTOS AERON&Aacute;UTICOS Y PRODUCTOS DE ARTESAN&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2177,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE MEDICAMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2178,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A TUR&Iacute;STICA&quot;
    },
    {
        &quot;gir_id&quot;: 2179,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE REPRESENTACI&Oacute;N Y ASESOR&Iacute;A EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2180,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISE&Ntilde;O DE PARRILLAS&quot;
    },
    {
        &quot;gir_id&quot;: 2181,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTAS POR MAYOR DE MATERIAS PRIMAS AGROPECUARIAS CON PROCESAMIENTO DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 1872,
        &quot;gir_descripcion&quot;: &quot;BANCOS COMERCIALES, CAJEROS AUTOM&Aacute;TICOS (CAJERO AUTOM&Aacute;TICO)&quot;
    },
    {
        &quot;gir_id&quot;: 2182,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE MINER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2183,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DISTRIBUIDORA DE CELULARES&quot;
    },
    {
        &quot;gir_id&quot;: 2184,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ARQUITECTURA E INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2185,
        &quot;gir_descripcion&quot;: &quot;LIMPIEZA COSM&Eacute;TICA DEL VEH&Iacute;CULO&quot;
    },
    {
        &quot;gir_id&quot;: 2186,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA IMPORTADORA DE AUTOPARTES&quot;
    },
    {
        &quot;gir_id&quot;: 2187,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE VENTA DE ALIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2188,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA TUR&Iacute;STICA&quot;
    },
    {
        &quot;gir_id&quot;: 2189,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2190,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A EN ADMINISTRACI&Oacute;N DE EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 2191,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A AMBIENTAL Y GESTI&Oacute;N DE RESIDUOS&quot;
    },
    {
        &quot;gir_id&quot;: 1145,
        &quot;gir_descripcion&quot;: &quot;SERVICIO T&Eacute;CNICO&quot;
    },
    {
        &quot;gir_id&quot;: 2192,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2193,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDAD INMOBILIARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2194,
        &quot;gir_descripcion&quot;: &quot;BANCOS COMERCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2195,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE VENTA DE SEGUROS&quot;
    },
    {
        &quot;gir_id&quot;: 2196,
        &quot;gir_descripcion&quot;: &quot;ESTABLECIMIENTO RELIGIOSO&quot;
    },
    {
        &quot;gir_id&quot;: 2197,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE SERVICIO Y MANTENIMIENTO DE AIRE ACONDICIONADO&quot;
    },
    {
        &quot;gir_id&quot;: 2198,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE REPRESENTACI&Oacute;N DE PRODUCTOS MEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2199,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE CATERING Y EVENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1936,
        &quot;gir_descripcion&quot;: &quot;CAJEROS AUTOM&Aacute;TICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2200,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE ADITIVOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2201,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE EXHIBICI&Oacute;N Y VENTA DE ART&Iacute;CULOS PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 2202,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MATERIALES Y EQUIPOS DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 2203,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE SERVICIO T&Eacute;CNICO&quot;
    },
    {
        &quot;gir_id&quot;: 2204,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2205,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEH&Iacute;CULOS AUTOMOTORES DE PASAJEROS&quot;
    },
    {
        &quot;gir_id&quot;: 2206,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE ROPA PARA BEBES&quot;
    },
    {
        &quot;gir_id&quot;: 2207,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE SEGUROS&quot;
    },
    {
        &quot;gir_id&quot;: 2208,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE PRODUCTOS REALIZADOS POR MAQUINAS EXPENDEDORAS&quot;
    },
    {
        &quot;gir_id&quot;: 2209,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ACCESORIOS VEHICULARES&quot;
    },
    {
        &quot;gir_id&quot;: 1088,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA, SPA, VENTA DE ART&Iacute;CULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 2210,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA Y CONTABLES DE EMPRESA DE TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 2211,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DIVERSOS&quot;
    },
    {
        &quot;gir_id&quot;: 2212,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA MINERA Y MADERERA&quot;
    },
    {
        &quot;gir_id&quot;: 2213,
        &quot;gir_descripcion&quot;: &quot;ENSE&Ntilde;ANZA PRE-ESCOLAR - JARDINES O NIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 2214,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE COMERCIO EXTERIOR&quot;
    },
    {
        &quot;gir_id&quot;: 2216,
        &quot;gir_descripcion&quot;: &quot;ENSE&Ntilde;ANZA PRIMARIA PRIVADA&quot;
    },
    {
        &quot;gir_id&quot;: 2217,
        &quot;gir_descripcion&quot;: &quot;ENSE&Ntilde;ANZA SECUNDARIA PRIVADA&quot;
    },
    {
        &quot;gir_id&quot;: 2139,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS M&Eacute;DICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2218,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ASESORAMIENTO EMPRESARIAL, ACTIVIDADES INMOBILIARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2219,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE SEGURIDAD PRIVADA&quot;
    },
    {
        &quot;gir_id&quot;: 2220,
        &quot;gir_descripcion&quot;: &quot;CENTROS DE REHABILITACI&Oacute;N Y OTRAS TERAPIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2078,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;AS, SANDWICHERIAS, HELADER&Iacute;AS, DULCER&Iacute;AS Y JUGUER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 2221,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE CONSULTORIA EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2222,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ARQUITECTURA, INGENIERIA Y DISE&Ntilde;O&quot;
    },
    {
        &quot;gir_id&quot;: 2224,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE LENTES&quot;
    },
    {
        &quot;gir_id&quot;: 2225,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRIVADO DE INICIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2226,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRIVADO DE PRIMARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2227,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRIVADO DE SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2228,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS DE GOLF&quot;
    },
    {
        &quot;gir_id&quot;: 2229,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ACTIVIDADES DEL RUBRO MINERO&quot;
    },
    {
        &quot;gir_id&quot;: 1835,
        &quot;gir_descripcion&quot;: &quot;CASA DE PR&Eacute;STAMOS&quot;
    },
    {
        &quot;gir_id&quot;: 2230,
        &quot;gir_descripcion&quot;: &quot;SALONES DE BELLEZA Y MASOTERAPIA&quot;
    },
    {
        &quot;gir_id&quot;: 2231,
        &quot;gir_descripcion&quot;: &quot;GARAJES&quot;
    },
    {
        &quot;gir_id&quot;: 2232,
        &quot;gir_descripcion&quot;: &quot;PLAYAS DE ESTACIONAMIENTO O GARAJES&quot;
    },
    {
        &quot;gir_id&quot;: 2233,
        &quot;gir_descripcion&quot;: &quot;SERVICIO Y VENTA DE LLANTAS, ACCESORIOS Y REPUESTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2234,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE VENTA DE EQUIPOS DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 1843,
        &quot;gir_descripcion&quot;: &quot;VENTA DE V&Iacute;VERES, ROPA Y MUEBLES (VENTA DE MUEBLES Y ACCESORIOS PARA LA CASA Y OFICINA)&quot;
    },
    {
        &quot;gir_id&quot;: 2235,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE CORRETAJE DE BIENES INMUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2236,
        &quot;gir_descripcion&quot;: &quot;VIDRIER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 2237,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE &Aacute;REAS VERDES&quot;
    },
    {
        &quot;gir_id&quot;: 2238,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRIVADO DE EDUCACI&Oacute;N INICIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2239,
        &quot;gir_descripcion&quot;: &quot;PANADER&Iacute;A Y PASTELERIA; CAFETER&Iacute;A, SANDWICHERIA, HELADER&Iacute;A, DULCER&Iacute;A Y JUGUER&Iacute;A (FUENTE DE SODA Y CAFETER&Iacute;A)&quot;
    },
    {
        &quot;gir_id&quot;: 2240,
        &quot;gir_descripcion&quot;: &quot;SALA DE JUEGOS TRAGAMONEDAS&quot;
    },
    {
        &quot;gir_id&quot;: 2241,
        &quot;gir_descripcion&quot;: &quot;ESTABLECIMIENTO CULTURAL&quot;
    },
    {
        &quot;gir_id&quot;: 2242,
        &quot;gir_descripcion&quot;: &quot;VULCANIZADORA DE LLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2243,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE TELEFONIA&quot;
    },
    {
        &quot;gir_id&quot;: 2244,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2245,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN DERECHO&quot;
    },
    {
        &quot;gir_id&quot;: 2246,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REGISTRO DE LA ASOCIACI&Oacute;N MONTEBELLO&quot;
    },
    {
        &quot;gir_id&quot;: 2247,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIEROS Y ARQUITECTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2248,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2249,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA IMPORTADORA&quot;
    },
    {
        &quot;gir_id&quot;: 2250,
        &quot;gir_descripcion&quot;: &quot;VENTA Y EXHIBICI&Oacute;N DE EQUIPOS HIDR&Aacute;ULICOS PARA PISCINA&quot;
    },
    {
        &quot;gir_id&quot;: 2251,
        &quot;gir_descripcion&quot;: &quot;HOSTALES&quot;
    },
    {
        &quot;gir_id&quot;: 2252,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSTRUCCI&Oacute;N Y DISE&Ntilde;O&quot;
    },
    {
        &quot;gir_id&quot;: 2253,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISE&Ntilde;O Y MODA&quot;
    },
    {
        &quot;gir_id&quot;: 2254,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORAMIENTO NUTRICIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2255,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PREPARACI&Oacute;N Y DISTRIBUCI&Oacute;N DE ALIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 1734,
        &quot;gir_descripcion&quot;: &quot;ART&Iacute;CULOS DE VIDRIO&quot;
    },
    {
        &quot;gir_id&quot;: 2256,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN CONTABILIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2257,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE SERVICIO DE TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 2258,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSTRUCCI&Oacute;N DE EDIFICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2259,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2260,
        &quot;gir_descripcion&quot;: &quot;CENTRO COMERCIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2261,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE TRANSPORTE TUR&Iacute;STICO&quot;
    },
    {
        &quot;gir_id&quot;: 2262,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMPA&Ntilde;&Iacute;A DE TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 2263,
        &quot;gir_descripcion&quot;: &quot;IMPORTACI&Oacute;N Y/O COMERCIALIZACI&Oacute;N DE PRODUCTOS FARMACEUTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2264,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N Y DISTRIBUCI&Oacute;N DE COSM&Eacute;TICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2265,
        &quot;gir_descripcion&quot;: &quot;BODEGA Y PANADERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2266,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2268,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;AS GENERALES&quot;
    },
    {
        &quot;gir_id&quot;: 2269,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 2267,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EQUIPOS DE MEDICI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2270,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRIVADO DE EDUCACI&Oacute;N PRIMARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2271,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRIVADO DE EDUCACI&Oacute;N SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2272,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE PODOLOG&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2274,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS M&Eacute;DICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2275,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS TUR&Iacute;STICOS&quot;
    },
    {
        &quot;gir_id&quot;: 1552,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS PARA VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 2276,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE COMPUTADORAS Y SOFTWARES&quot;
    },
    {
        &quot;gir_id&quot;: 2277,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA EL DESARROLLO DE ACTIVIDADES ACADEMICAS&quot;
    },
    {
        &quot;gir_id&quot;: 2278,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS MEDICINALES&quot;
    },
    {
        &quot;gir_id&quot;: 2279,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS FARMAC&Eacute;UTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2215,
        &quot;gir_descripcion&quot;: &quot;ENSE&Ntilde;ANZA PRE ESCOLAR PRIVADA&quot;
    },
    {
        &quot;gir_id&quot;: 2280,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INVESTIGACI&Oacute;N DE MERCADO&quot;
    },
    {
        &quot;gir_id&quot;: 2281,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ARQUITECTURA, INGENIERIA E INMOBILIARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2282,
        &quot;gir_descripcion&quot;: &quot;SUPLEMENTOS DEPORTIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 2283,
        &quot;gir_descripcion&quot;: &quot;PSICOL&Oacute;GICO&quot;
    },
    {
        &quot;gir_id&quot;: 2284,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;AS, CONSULTOR&Iacute;AS Y LICITACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 2285,
        &quot;gir_descripcion&quot;: &quot;COSTURERA&quot;
    },
    {
        &quot;gir_id&quot;: 1924,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE RECOLECCI&Oacute;N Y DISTRIBUCI&Oacute;N DE ROPA POR LAS LAVANDER&Iacute;AS (SOLO RECEPCI&Oacute;N)&quot;
    },
    {
        &quot;gir_id&quot;: 2286,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ARQUITECTURA, INGENIER&Iacute;A Y ASESORAMIENTO EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2287,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;AS EMPRESARIALES, PROGRAMACI&Oacute;N Y SUMINISTRO DE INFORM&Aacute;TICA&quot;
    },
    {
        &quot;gir_id&quot;: 2288,
        &quot;gir_descripcion&quot;: &quot;PLANES&quot;
    },
    {
        &quot;gir_id&quot;: 2289,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 2290,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE INVESTIGACI&Oacute;N Y SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2291,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE RECOJO Y ENTREGA DE CARTAS Y ENCOMIENDAS&quot;
    },
    {
        &quot;gir_id&quot;: 2292,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A, ASESOR&Iacute;A Y VENTA TECNOLOGICA&quot;
    },
    {
        &quot;gir_id&quot;: 2293,
        &quot;gir_descripcion&quot;: &quot;VENTA Y SERVICIO DE CELULARES&quot;
    },
    {
        &quot;gir_id&quot;: 2294,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA CONSTRUCTORA DE DRYWALL&quot;
    },
    {
        &quot;gir_id&quot;: 2295,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 2296,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DIVERSOS, COTILLON, PI&Ntilde;ATERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2298,
        &quot;gir_descripcion&quot;: &quot;BAZAR Y VENTA DE REGALOS&quot;
    },
    {
        &quot;gir_id&quot;: 2299,
        &quot;gir_descripcion&quot;: &quot;DROGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2300,
        &quot;gir_descripcion&quot;: &quot;DULCERIA, FUENTE DE SODA&quot;
    },
    {
        &quot;gir_id&quot;: 2302,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA Y VENTA DE ARTICULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 3014,
        &quot;gir_descripcion&quot;: &quot;CUNA&quot;
    },
    {
        &quot;gir_id&quot;: 2303,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COLECTORES SOLARES&quot;
    },
    {
        &quot;gir_id&quot;: 2304,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A INFORM&Aacute;TICA Y OTROS&quot;
    },
    {
        &quot;gir_id&quot;: 2305,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE IMPORTACIONES DE PRODUCTOS DE SEGURIDAD INDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2306,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE INFORM&Aacute;TICA&quot;
    },
    {
        &quot;gir_id&quot;: 2307,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE PRODUCTOS DE ALIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2308,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISE&Ntilde;O DE INTERIORES Y DECORACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 2309,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE INSUMOS QU&Iacute;MICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2310,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE SERVICIOS DE COMERCIO EXTERIOR&quot;
    },
    {
        &quot;gir_id&quot;: 2311,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE VENTA DE COMBUSTIBLE&quot;
    },
    {
        &quot;gir_id&quot;: 2312,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA PRESTACI&Oacute;N DE SERVICIOS DE TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 2313,
        &quot;gir_descripcion&quot;: &quot;FINANCIERA&quot;
    },
    {
        &quot;gir_id&quot;: 2314,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA Y PRODUCTOS DE BELLEZA (VENTA DE ROPA)&quot;
    },
    {
        &quot;gir_id&quot;: 2315,
        &quot;gir_descripcion&quot;: &quot;SALON DE VELLEZA, VENTA DE ROPA Y PRODUCTOS DE BELLEZA (VENTA DE ARTICULOS DE TOCADOR)&quot;
    },
    {
        &quot;gir_id&quot;: 2316,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINA ADMINISTRATIVA DE SERVICIOS DE SEGURIDAD Y SERVICIOS GENERALES)&quot;
    },
    {
        &quot;gir_id&quot;: 2317,
        &quot;gir_descripcion&quot;: &quot;BANCOS COMERCIALES Y CAJEROS AUTOMATICOS (CAJERO AUTOMATICO)&quot;
    },
    {
        &quot;gir_id&quot;: 2320,
        &quot;gir_descripcion&quot;: &quot;CAFETERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2321,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE RECOLECCION&quot;
    },
    {
        &quot;gir_id&quot;: 2322,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 2323,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SEGURIDAD ELECTR&Oacute;NICA, TELEFON&Iacute;A, SISTEMAS EL&Eacute;CTRICOS, AUTOMATIZACI&Oacute;N Y CONTROLES INDUSTRIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2324,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE VENTA DE MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2325,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE PRODUCTOS AGRICOLAS&quot;
    },
    {
        &quot;gir_id&quot;: 2326,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA Y SERVICIOS DE SOFTWARE&quot;
    },
    {
        &quot;gir_id&quot;: 2327,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA COMERCIALIZADORA DE EQUIPOS DE LIMPIEZA EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2328,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SISTEMAS INFORM&Aacute;TICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2329,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE VENTA DE ELECTRODOM&Eacute;STICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2331,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE ORIENTAL&quot;
    },
    {
        &quot;gir_id&quot;: 2332,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE TERRENOS&quot;
    },
    {
        &quot;gir_id&quot;: 2333,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIER&Iacute;A Y ARQUITECTURA&quot;
    },
    {
        &quot;gir_id&quot;: 1168,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE REPARACI&Oacute;N Y ARREGLO MENOR DE PRENDAS&quot;
    },
    {
        &quot;gir_id&quot;: 2334,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ACTIVIDADES DE CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2335,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE FUNERARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2336,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES PERSONALES COMO CHEF&quot;
    },
    {
        &quot;gir_id&quot;: 2337,
        &quot;gir_descripcion&quot;: &quot;ALQUILER DE DISFRACES&quot;
    },
    {
        &quot;gir_id&quot;: 2301,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PAN Y PRODUCTOS DE PANADER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2338,
        &quot;gir_descripcion&quot;: &quot;ALMACEN&quot;
    },
    {
        &quot;gir_id&quot;: 2339,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE OPERACIONES LOG&Iacute;STICAS&quot;
    },
    {
        &quot;gir_id&quot;: 2340,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y EXPOTACI&Oacute;N DE ALIMENTOS ENVASADOS  Y NO ENVASADOS DE CONSUMO MASIVO&quot;
    },
    {
        &quot;gir_id&quot;: 2341,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A DE PROYECTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2342,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA CONSTRUCTORA E INMOBILIARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2343,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE LA EMPRESA INMOBILIARIA Y CONSTRUCTORA&quot;
    },
    {
        &quot;gir_id&quot;: 2344,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA E IMPORTACI&Oacute;N DE EQUIPO ELECTROMEC&Aacute;NICO, SUPERVISI&Oacute;N Y PROYECTOS EL&Eacute;CTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2345,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDAD DE DISE&Ntilde;O ARQUITECT&Oacute;NICO&quot;
    },
    {
        &quot;gir_id&quot;: 2346,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES MEDICAS&quot;
    },
    {
        &quot;gir_id&quot;: 2347,
        &quot;gir_descripcion&quot;: &quot;INSTALACI&Oacute;N DE CAMARAS DE VIGILANCIA E INTERCOMUNICADORES&quot;
    },
    {
        &quot;gir_id&quot;: 2348,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORAMIENTO Y APRENDIZAJE&quot;
    },
    {
        &quot;gir_id&quot;: 2349,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ALQUILER DE VOLQUETES Y MAQUINARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2350,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SISTEMAS DE CONTROL DE FLOTAS V&Iacute;A GPS&quot;
    },
    {
        &quot;gir_id&quot;: 2351,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE MANTENIMIENTO E INSTALACI&Oacute;N DE REDES EL&Eacute;CTRICAS&quot;
    },
    {
        &quot;gir_id&quot;: 2319,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2330,
        &quot;gir_descripcion&quot;: &quot;SEGURO DE VIDA Y OTROS (OFICINA ADMINISTRATIVA DE SEGUROS)&quot;
    },
    {
        &quot;gir_id&quot;: 3106,
        &quot;gir_descripcion&quot;: &quot;ENSE&Ntilde;ANZA PRIMARIA Y SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2353,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPORTACI&Oacute;N TEXTIL&quot;
    },
    {
        &quot;gir_id&quot;: 2354,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE OPERACIONES INDUSTRIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2355,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE ALIMENTOS Y BEBIDAS&quot;
    },
    {
        &quot;gir_id&quot;: 2356,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS Y ZAPATERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2358,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS NATURALES&quot;
    },
    {
        &quot;gir_id&quot;: 2359,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS INTEGRALES DE LIMPIEZA&quot;
    },
    {
        &quot;gir_id&quot;: 2360,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE INMUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2361,
        &quot;gir_descripcion&quot;: &quot;AGENCIA INMOBILIARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2362,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N Y COMPRA VENTA DE EQUIPO DE MINER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2363,
        &quot;gir_descripcion&quot;: &quot;VENTA DE INSUMOS DE COMPUTO Y SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2364,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALIMENTOS ENLATADOS Y ENVASADOS - SUPLEMENTO VITAM&Iacute;NICO&quot;
    },
    {
        &quot;gir_id&quot;: 2365,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A, CONSULTOR&Iacute;A Y ELABORACI&Oacute;N DE INFORMES&quot;
    },
    {
        &quot;gir_id&quot;: 2366,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES - &Aacute;REA ADMINISTRATIVA Y LEGAL&quot;
    },
    {
        &quot;gir_id&quot;: 2367,
        &quot;gir_descripcion&quot;: &quot;COMIDA R&Aacute;PIDA&quot;
    },
    {
        &quot;gir_id&quot;: 2368,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE CONSTRUCCI&Oacute;N CIVIL&quot;
    },
    {
        &quot;gir_id&quot;: 2297,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MASAJES FACIALES, ARREGLO DE MANOS Y PIES, PODOLOG&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2369,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE TUBERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2370,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE MANTENIMIENTO Y REPARACI&Oacute;N DE AIRE ACONDICIONADO&quot;
    },
    {
        &quot;gir_id&quot;: 2371,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO M&Eacute;DICO DENTAL&quot;
    },
    {
        &quot;gir_id&quot;: 2372,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA CONTABLE Y EXPORTACI&Oacute;N DE MADERAS&quot;
    },
    {
        &quot;gir_id&quot;: 2439,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A, PROGRAMAS Y SUMINISTROS INFORM&Aacute;TICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2373,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS EMPRESARIALES ASESORIA, ORIENTACI&Oacute;N RELACIONADO CON EL TRANSPORTE DE CARGA&quot;
    },
    {
        &quot;gir_id&quot;: 2374,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA VENTA POR MAYOR Y MENOR DE PRODUCTOS PLAGUICIDAS&quot;
    },
    {
        &quot;gir_id&quot;: 2375,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE ART&Iacute;CULOS DE SEGURIDAD INDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2376,
        &quot;gir_descripcion&quot;: &quot;VENTA DE DISFRACES Y ALQUILER&quot;
    },
    {
        &quot;gir_id&quot;: 2379,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MANTENIMIENTO DE PARQUES Y JARDINES, RELANZAMIENTO, RECOLECCI&Oacute;N, TRANSPORTE Y DISPOSICI&Oacute;N DE RESIDUOS S&Oacute;LIDOS&quot;
    },
    {
        &quot;gir_id&quot;: 2378,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE MANTENIMIENTO DE &Aacute;REAS VERDES, TRANSPORTES DE PASAJEROS Y MERCANC&Iacute;AS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2380,
        &quot;gir_descripcion&quot;: &quot;TELECOMERCIO DE SERVICIOS DE INFORM&Aacute;TICA&quot;
    },
    {
        &quot;gir_id&quot;: 2381,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE ROPA PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 2382,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE SERVICIOS GENERALES OFICINA ADMINISTRATIVA&quot;
    },
    {
        &quot;gir_id&quot;: 2357,
        &quot;gir_descripcion&quot;: &quot;AGENCIAS DE VIAJE, AGENCIAS DE TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 2384,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y COMERCIALIZACI&Oacute;N DE HERRAMIENTAS DE PERFORACI&Oacute;N MINERA&quot;
    },
    {
        &quot;gir_id&quot;: 2385,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE SERVICIOS DE AIRE ACONDICIONADO, VENTILACI&Oacute;N Y CALEFACCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2386,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEHICULOS AUTOMOTORES DE PASAJEROS&quot;
    },
    {
        &quot;gir_id&quot;: 2388,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS PARA VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 2389,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 2390,
        &quot;gir_descripcion&quot;: &quot;SERV. DE MASAJES FACIALES ARREGLO DE MANOS Y PIES PODOLOGIA&quot;
    },
    {
        &quot;gir_id&quot;: 2043,
        &quot;gir_descripcion&quot;: &quot;TATUAJES Y/O APLICACIONES DE ACCESORIO EN LA PIEL&quot;
    },
    {
        &quot;gir_id&quot;: 2393,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A Y CONSULTOR&Iacute;A EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2394,
        &quot;gir_descripcion&quot;: &quot;VENTA DE OTROS PRODUCTOS NO ALIMENTICIOS, MONTURAS DE LENTES&quot;
    },
    {
        &quot;gir_id&quot;: 2395,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULO DE CUERO Y ACCESORIOS DE VIAJE&quot;
    },
    {
        &quot;gir_id&quot;: 2396,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MUEBLES Y REGALOS&quot;
    },
    {
        &quot;gir_id&quot;: 2397,
        &quot;gir_descripcion&quot;: &quot;MERCERIAS Y PASAMANERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2398,
        &quot;gir_descripcion&quot;: &quot;SERV. ADM. RELACIONADOS CON COMERCIO (OFICINAS ADMINISTRATIVAS)&quot;
    },
    {
        &quot;gir_id&quot;: 2399,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VIVERES, ROPA Y MUEBLES (VENTA DE MELAMINE Y MADERA)&quot;
    },
    {
        &quot;gir_id&quot;: 2400,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA LA IMPORTACI&Oacute;N DE COMPONENTES, ACCESORIOS ELECTR&Oacute;NICOS Y SERVICIOS RELACIONADOS&quot;
    },
    {
        &quot;gir_id&quot;: 1039,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO DE M&Eacute;DICOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2273,
        &quot;gir_descripcion&quot;: &quot;SERV. ADM. RELACIONADOS CON COMERCIO (OFICINAS ADMINISTRATIVAS) (8)&quot;
    },
    {
        &quot;gir_id&quot;: 3104,
        &quot;gir_descripcion&quot;: &quot;CAFETERIAS, SANDWICHERIAS, HELADERIAS, DULCERIAS, JUGUERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2403,
        &quot;gir_descripcion&quot;: &quot;SALONES DE BELLEZA Y MASOTERAPIA (6)&quot;
    },
    {
        &quot;gir_id&quot;: 2404,
        &quot;gir_descripcion&quot;: &quot;SERV.DE MASAJES FACIALES ARREGLO DE MANOS Y PIES PODOLOGIA&quot;
    },
    {
        &quot;gir_id&quot;: 2405,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINAS)&quot;
    },
    {
        &quot;gir_id&quot;: 2406,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COSM&Eacute;TICA AUTOMOTRIZ&quot;
    },
    {
        &quot;gir_id&quot;: 2407,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE RECOLECCI&Oacute;N Y DISTRIBUCI&Oacute;N DE ROPA POR LAS LAVANDER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 2408,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE VIDRIOS TEMPLADOS Y LAMINADOS DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2409,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MOTOCICLETAS&quot;
    },
    {
        &quot;gir_id&quot;: 2410,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS PARA MOTOCICLETAS&quot;
    },
    {
        &quot;gir_id&quot;: 2411,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE MASAJES CORPORALES, MANICURE&quot;
    },
    {
        &quot;gir_id&quot;: 2412,
        &quot;gir_descripcion&quot;: &quot;PANADERIAS Y PASTELERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2413,
        &quot;gir_descripcion&quot;: &quot;BANCOS COMERCIALES - CAJEROS AUTOM&Aacute;TICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2414,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACI&Oacute;N DE EMPRESAS DE AVIACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2415,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MONTURAS DE LENTES Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2416,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA EN DISE&Ntilde;O Y FABRICACI&Oacute;N EN MUEBLES DE MALAMINE Y CARPINTERIA GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2417,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA LA COMERCIALIZACI&Oacute;N DE INSUMOS PARA LA INDUSTRIA VETERINARIA Y AGR&Iacute;COLA&quot;
    },
    {
        &quot;gir_id&quot;: 2418,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE LA EMPRESA CORPORACI&Oacute;N SALETH TRAVEL INTERNATIONAL S.A.C.&quot;
    },
    {
        &quot;gir_id&quot;: 2401,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEF&Oacute;NICOS / VENTA DE CELULARES , ACCESORIOS Y REPARACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2392,
        &quot;gir_descripcion&quot;: &quot;ZAPATER&Iacute;AS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2420,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INMOBILIARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2422,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS GANADEROS&quot;
    },
    {
        &quot;gir_id&quot;: 2423,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EVALUACION&quot;
    },
    {
        &quot;gir_id&quot;: 2424,
        &quot;gir_descripcion&quot;: &quot;VIDRIERIA&quot;
    },
    {
        &quot;gir_id&quot;: 1014,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;A, JUGUER&Iacute;A Y DULCER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2425,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COORDINACION DE ACTIVIDADES DE PERSONAL ADMINISTRATIVO Y GERENCIA DE EMPRESA&quot;
    },
    {
        &quot;gir_id&quot;: 2426,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDAD AGR&Iacute;COLA&quot;
    },
    {
        &quot;gir_id&quot;: 2427,
        &quot;gir_descripcion&quot;: &quot;CASA NATURISTA&quot;
    },
    {
        &quot;gir_id&quot;: 2428,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRATAMIENTO TERMICO PARA SOLDADURAS&quot;
    },
    {
        &quot;gir_id&quot;: 2429,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N, IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE PRODUCTOS FARMACEUTICOS, NATURALES Y ALIMEN&quot;
    },
    {
        &quot;gir_id&quot;: 2430,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS ADMINISTRATIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 2431,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MANTENIMIENTO Y REPARACION DE EQUIPO, ACTIVIDADES DE INFORM&Aacute;TICA&quot;
    },
    {
        &quot;gir_id&quot;: 2432,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE CONTABILIDAD Y ASESORAMIENTO EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2433,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPORTACI&Oacute;N Y COMERCIALIZACI&Oacute;N DE EXTINTORES, EQUIPO DE SEGURIDAD E HIGIENE INDUSTRIAL Y SERVICIOS DE SANEAMIENTO AMBIENTAL&quot;
    },
    {
        &quot;gir_id&quot;: 2451,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;A, HELADER&Iacute;A, JUGUER&Iacute;A Y DULCER&Iacute;A (FUENTE DE SODA)&quot;
    },
    {
        &quot;gir_id&quot;: 2435,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA CONSTRUCTORA Y ESTUDIO DE PROYECTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2436,
        &quot;gir_descripcion&quot;: &quot;CENTRO RECREACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2437,
        &quot;gir_descripcion&quot;: &quot;LICORERIA (SIN CONSUMO DE LICOR EN EL LOCAL)&quot;
    },
    {
        &quot;gir_id&quot;: 2438,
        &quot;gir_descripcion&quot;: &quot;ZAPATER&Iacute;A (VENTA POR CAT&Aacute;LOGO DE CALZADO) BAZAR Y VENTA DE ROPA (VENTA DE ROPA POR CAT&Aacute;LOGO)&quot;
    },
    {
        &quot;gir_id&quot;: 2391,
        &quot;gir_descripcion&quot;: &quot;PERFUMERIAS Y DROGUERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2383,
        &quot;gir_descripcion&quot;: &quot;CASAS DE JUEGOS DE AZAR Y APUESTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2440,
        &quot;gir_descripcion&quot;: &quot;BAZAR Y REGALOS, VENTA DE GOLOSINAS Y CONFITER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2441,
        &quot;gir_descripcion&quot;: &quot;VENTA DE GOLOSINAS Y CONFITER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2442,
        &quot;gir_descripcion&quot;: &quot;TALLER DE MEC&Aacute;NICA (TALLER DE MEC&Aacute;NICA AUTOMOTRIZ) SERVICIO DE LAVADO DE VEH&Iacute;CULOS, VENTA DE VEH&Iacute;CULOS AUTOMOTORES DE PASAJEROS,&quot;
    },
    {
        &quot;gir_id&quot;: 2443,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA, SERVICIO DE MASAJES FACIALES, ARREGLO DE MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 2444,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES DIVERSOS (SERVICIOS PROFESIONALES EN PSICOLOG&Iacute;A)&quot;
    },
    {
        &quot;gir_id&quot;: 2445,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SISTEMAS EL&Eacute;CTRICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2446,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PUBLICIDAD DIGITAL&quot;
    },
    {
        &quot;gir_id&quot;: 2447,
        &quot;gir_descripcion&quot;: &quot;BOUTIQUE Y BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2448,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMPRA Y VENTA DE DIVISAS - MONEDA EXTRANJERA&quot;
    },
    {
        &quot;gir_id&quot;: 2449,
        &quot;gir_descripcion&quot;: &quot;BODEGA Y VENTA DE PAN&quot;
    },
    {
        &quot;gir_id&quot;: 2450,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS U EQUIPOS PARA LA INDUSTRIA DEL SECTOR AGR&Iacute;COLA Y ANIMAL&quot;
    },
    {
        &quot;gir_id&quot;: 2452,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA, PELUQUER&Iacute;A, ART&Iacute;CULOS DE TOCADO Y BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2453,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINA ADMINISTRATIVA DE EMPRESA DE VENTA DE ALIMENTOS)&quot;
    },
    {
        &quot;gir_id&quot;: 2454,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA, PELUQUER&Iacute;A, SERVICIO DE MASAJES FACIALES, ARREGLO DE MANOS Y PIES, PODOLOG&Iacute;A, VENTA DE PRODUCTOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 2456,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA Y VENTA DE PRODUCTOS DE BELLEZA (VENTA DE ART&Iacute;CULOS DE TOCADOR)&quot;
    },
    {
        &quot;gir_id&quot;: 2457,
        &quot;gir_descripcion&quot;: &quot;PANADER&Iacute;AS Y PASTELER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 2458,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE IMPORTACI&Oacute;N DE SEGURIDAD INDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2459,
        &quot;gir_descripcion&quot;: &quot;MEC&Aacute;NICA MENOR LIGERA, VENTA DE ACCESORIOS Y REPUESTOS AFINES AL SERVICIO QUE PRESTAN&quot;
    },
    {
        &quot;gir_id&quot;: 2460,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A (COMEDOR DE EMPLEADOS)&quot;
    },
    {
        &quot;gir_id&quot;: 2461,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE BELLEZA Y COSM&Eacute;TICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2471,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A ECON&Oacute;MICA&quot;
    },
    {
        &quot;gir_id&quot;: 2463,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINAS) OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A EN TEMAS DE SISTEMAS DE GESTI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2462,
        &quot;gir_descripcion&quot;: &quot;BOTICA, BAZAR, PERFUMER&Iacute;A Y VENTA DE PRODUCTOS DE BELLEZA (VENTA DE ART&Iacute;CULOS DE TOCADOR)&quot;
    },
    {
        &quot;gir_id&quot;: 2464,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE MUDANZAS INTERNACIONALES Y/O LOCALES ADMINISTRACI&Oacute;N, GESTI&Oacute;N Y TRANSPORTE DE BIENES Y ENSERES&quot;
    },
    {
        &quot;gir_id&quot;: 2465,
        &quot;gir_descripcion&quot;: &quot;VENTA DE FLORES (FLORER&Iacute;A), BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2466,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIER&Iacute;A Y MONTAJE INDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2467,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA AGENTE DE CARGA INTERNACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2468,
        &quot;gir_descripcion&quot;: &quot;COMIDAS AL PASO, FUENTE DE SODA, CAFETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2469,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE BELLEZA (VENTA DE ART&Iacute;CULOS DE PELUQUER&Iacute;A)&quot;
    },
    {
        &quot;gir_id&quot;: 2470,
        &quot;gir_descripcion&quot;: &quot;MINIMARKET, CAFETER&Iacute;A Y CASA NATURISTA&quot;
    },
    {
        &quot;gir_id&quot;: 2472,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA, SERVICIO DE MASAJES FACIALES, ARREGLO DE MANOS Y PIES, PODOLOG&Iacute;A (ARREGLO DE MANOS Y PIES)&quot;
    },
    {
        &quot;gir_id&quot;: 2473,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINA ADMINISTRATIVA DE ARRENDAMIENTO Y SUBARRENDAMIENTO Y ADMINISTRACI&Oacute;N DE BIENES MUEBLES E INMUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2474,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO ODONTOL&Oacute;GICO, SERVICIOS M&Eacute;DICOS EN FORMA INDIVIDUAL&quot;
    },
    {
        &quot;gir_id&quot;: 2475,
        &quot;gir_descripcion&quot;: &quot;HELADER&Iacute;A, JUGUER&Iacute;A, DULCER&Iacute;A Y SANDWICHER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2476,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA LABORES DE CONSULTOR&Iacute;A, ASESOR&Iacute;A, GESTI&Oacute;N, INGENIER&Iacute;A, COMERCIALIZACI&Oacute;N Y VENTA DE EQUIPOS&quot;
    },
    {
        &quot;gir_id&quot;: 2477,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, JUGUER&Iacute;A (FUENTE DE SODA)&quot;
    },
    {
        &quot;gir_id&quot;: 2478,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA CONSTRUCTORA Y EQUIPO PARA CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2479,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE LIMPIEZA&quot;
    },
    {
        &quot;gir_id&quot;: 2480,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPORTACI&Oacute;N DE PRENDAS DE VESTIR Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2481,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE GESTI&Oacute;N DE CALIDAD Y NORMAS ISO Y AFINES, GESTI&Oacute;N DE PROCESOS&quot;
    },
    {
        &quot;gir_id&quot;: 2482,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO DE MEDICINA GENERAL (CONSULTORIO PSICOL&Oacute;GICO)&quot;
    },
    {
        &quot;gir_id&quot;: 2455,
        &quot;gir_descripcion&quot;: &quot;LIBRER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2483,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS INDUSTRIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2484,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE ASESOR&Iacute;A, CONSULTOR&Iacute;A T&Eacute;CNICA EN MANEJO, MANTENIMIENTO Y REPARACI&Oacute;N DE MAQUINARIA PESADA&quot;
    },
    {
        &quot;gir_id&quot;: 2485,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, HELADER&Iacute;A, JUGUER&Iacute;A Y SANDWICHER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2486,
        &quot;gir_descripcion&quot;: &quot;OFOCINA ADMINISTRATIVA DE ESTUDIO DE INGENIEROS&quot;
    },
    {
        &quot;gir_id&quot;: 2487,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS EN INGENIERIA Y AN&Aacute;LISIS T&Eacute;CNICO&quot;
    },
    {
        &quot;gir_id&quot;: 2488,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA PROVEEDORA DE SERVICIOS INFORM&Aacute;TICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2489,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ORGANIZACI&Oacute;N Y PRODUCCI&Oacute;N DE EVENTOS Y ACTIVIDADES DE PUBLICIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2490,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE MATERIA PRIMA PARA USO VETERINARIO, FARMAC&Eacute;UTICO Y DE ALIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2492,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA A LA CONSULTOR&Iacute;A EN INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2494,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE KARATE (ARTES MARCIALES)&quot;
    },
    {
        &quot;gir_id&quot;: 2495,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE SUMINISTROS DE LABORATORIO&quot;
    },
    {
        &quot;gir_id&quot;: 2496,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA A LA COMERCIALIZACI&Oacute;N DE SUMINISTROS DE LABORATORIO&quot;
    },
    {
        &quot;gir_id&quot;: 2497,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONTROL DOCUMENTARIO DE EMPRESA DEDICADA A LA COMERCIALIZACI&Oacute;N DE BEBIDAS&quot;
    },
    {
        &quot;gir_id&quot;: 2498,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA Y MASOTERAPIA&quot;
    },
    {
        &quot;gir_id&quot;: 2499,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A EN INGENIER&Iacute;A, AMOBLAMIENTO Y CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2500,
        &quot;gir_descripcion&quot;: &quot;GUARDER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2501,
        &quot;gir_descripcion&quot;: &quot;BANCOS COMERCIALES Y CAJEROS AUTOM&Aacute;TICOS (CJAERO AUTOM&Aacute;TICO)&quot;
    },
    {
        &quot;gir_id&quot;: 2502,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA Y MASOTERAPIA, ARREGLO DE MANOS Y PIES PODOLOG&Iacute;A, VENTA DE ART&Iacute;CULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 2491,
        &quot;gir_descripcion&quot;: &quot;BOUTIQUE, BAZARES Y REGALOS (BAZAR Y ACCESORIOS)&quot;
    },
    {
        &quot;gir_id&quot;: 2503,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINAS) OFICINA ADMINISTRATIVA EN ACTIVIDAD DE SERVICIO DE TRANSPORTE DE CARGA&quot;
    },
    {
        &quot;gir_id&quot;: 2504,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIOS DE MEDICINA GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2505,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;A, JUGUER&Iacute;A, DULCER&Iacute;A Y HELADER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2506,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEF&Oacute;NICOS (VENTA DE CELULARES, ACCESORIOS Y REPARACI&Oacute;N)&quot;
    },
    {
        &quot;gir_id&quot;: 2507,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE INTERMEDIACI&Oacute;N LABORAL DE LIMPIEZA Y MANTENIMIENTO DE EDIFICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2508,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PLANTAS Y FLORES (FLORER&Iacute;A), BAZAR Y REGALOS&quot;
    },
    {
        &quot;gir_id&quot;: 2509,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INVERSIONES DE MICROFINANZAS&quot;
    },
    {
        &quot;gir_id&quot;: 2510,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;A, HELADER&Iacute;A, JUGUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2511,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA AL MANTENIMIENTO DE AIRE ACONDICIONADO&quot;
    },
    {
        &quot;gir_id&quot;: 2512,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DIVERSOS: COTILL&Oacute;N, PI&Ntilde;ATER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2513,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ARQUITECTURA, INGENIER&Iacute;A, AGRIMENSURA Y ASESOR&Iacute;AS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2514,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA, SERVICIO DE MASAJES FACIALES, ARREGLO DE MANOS Y PIES PODOLOG&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2515,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE EDUCACI&Oacute;N INICIAL, CENTRO DE ENSE&Ntilde;ANZA&quot;
    },
    {
        &quot;gir_id&quot;: 2516,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA A LA IMPORTADORA DE EQUIPOS Y PRODUCTOS M&Eacute;DICOS Y DE LABORATORIO&quot;
    },
    {
        &quot;gir_id&quot;: 2517,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;AS, SANDWICHER&Iacute;AS, HELADER&Iacute;AS, DULCER&Iacute;AS Y JUGUER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 2518,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE GESTI&Oacute;N, ADMINISTRACI&Oacute;N, REPRESENTACI&Oacute;N Y SUPERVISI&Oacute;N DE PERSONAS JUR&Iacute;DICAS SOCIETARIAS Y NO SOCIETARIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2519,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PLANTAS Y FLORES (FLORERIA), BAZAR Y REGALLOS (BAZAR)&quot;
    },
    {
        &quot;gir_id&quot;: 2520,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DECORACI&Oacute;N DE INTERIORES&quot;
    },
    {
        &quot;gir_id&quot;: 2521,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA, ARREGLO DE MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 2522,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MASAJES FACIALES, ARREGLO DE MANOS Y PIES PODOLOG&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2523,
        &quot;gir_descripcion&quot;: &quot;ZAPATER&Iacute;A Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2524,
        &quot;gir_descripcion&quot;: &quot;BOTICA, BAZAR, PERFUMER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2525,
        &quot;gir_descripcion&quot;: &quot;SANDWICHER&Iacute;A, CAFETER&Iacute;A, DULCER&Iacute;A, HELADER&Iacute;A Y JUGUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2526,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DESARROLLO DE PROYECTOS DE INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2527,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO DE MEDICINA GENERAL (CONSULTORIO M&Eacute;DICO GENERAL Y PSIQUI&Aacute;TRICO)&quot;
    },
    {
        &quot;gir_id&quot;: 2528,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMERCIALIZACI&Oacute;N DE PRODUCTOS FAMAC&Eacute;UTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2529,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, VENTA DE PANES, FUENTE DE SODA&quot;
    },
    {
        &quot;gir_id&quot;: 2434,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;A, HELADER&Iacute;A, JUGUER&Iacute;A, DULCER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2530,
        &quot;gir_descripcion&quot;: &quot;BOTICA Y PERFUMER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2531,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE SEGURIDAD PRIVADA&quot;
    },
    {
        &quot;gir_id&quot;: 2532,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DEDICADA A SERVICIOS DE MANTENIMIENTO DE EQUIPOS DE LABORATORIO&quot;
    },
    {
        &quot;gir_id&quot;: 2533,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COMPUTADORAS Y SOFTWARE, VENTA DE MATERIALES Y EQUIPOS DE OFICINA, SERVICIO DE MANTENIMIENTO Y REPARACI&Oacute;N DE EQUIPOS&quot;
    },
    {
        &quot;gir_id&quot;: 2534,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEF&Oacute;NICOS, CELULARES&quot;
    },
    {
        &quot;gir_id&quot;: 2535,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTAS POR MAYOR DE MATERIAS PRIMAS AGROPECUARIAS CON PROCESAMIENTOS DE DATOS&quot;
    },
    {
        &quot;gir_id&quot;: 2536,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA AN&Aacute;LISIS DOCUMENTARIO Y CONTABLE&quot;
    },
    {
        &quot;gir_id&quot;: 2538,
        &quot;gir_descripcion&quot;: &quot;BANCO COMERCIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2539,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA, SERVICIOS DE HADWARE, SOFTWARE&quot;
    },
    {
        &quot;gir_id&quot;: 2540,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA ALQUILER EQUIPOS DE SONIDO Y AUDIOVISUAL&quot;
    },
    {
        &quot;gir_id&quot;: 2541,
        &quot;gir_descripcion&quot;: &quot;TABAQUER&Iacute;A (CIGARRILLOS ELECTR&Oacute;NICOS)&quot;
    },
    {
        &quot;gir_id&quot;: 2542,
        &quot;gir_descripcion&quot;: &quot;FERRETER&Iacute;A (SIN VENTA DE MATERIALES DE CONSTRUCCI&Oacute;N) Y CERRAJER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2543,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROYECTOS DE INGENIER&Iacute;A, AIRE ACONDICIONADO Y VENTILACI&Oacute;N MEC&Aacute;NICA&quot;
    },
    {
        &quot;gir_id&quot;: 2544,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATVIA DE ACTIVIDADES DE ARQUITECTURA, INGENIER&Iacute;A Y AGRIMESURA (OFICINA ADMINISTRATIVA PARA ACTIVIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2545,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINA ADMINISTRATIVA DE EMPRESA DE SEGURIDAD)&quot;
    },
    {
        &quot;gir_id&quot;: 2493,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;A, JUGUER&Iacute;A, HELADER&Iacute;A Y DULCER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2546,
        &quot;gir_descripcion&quot;: &quot;VENTA DE EQUIPOS, ACCESORIOS PARA PISCINAS, PRODUCTOS QU&Iacute;MICOS PARA TRATAMIENTO  DE AGUA PARA PISCINA, FERRETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2547,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA, PELUQUER&Iacute;A, SERVICIO DE MASAJES FACIALES, ARREGLO DE MANOS Y PIES, VENTA DE PRODUCTOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 2548,
        &quot;gir_descripcion&quot;: &quot;MINIMARKET Y CAFETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2549,
        &quot;gir_descripcion&quot;: &quot;OFICINA CONTABLE ADMINISTRATIVA&quot;
    },
    {
        &quot;gir_id&quot;: 2550,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;A, HELADER&Iacute;A Y DULCER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2551,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MASAJES FACIALES, ARREGLO DE MANOS Y PIES, PODOLOG&Iacute;A (ARREGLO DE MANOS Y PIES, PODOLOG&Iacute;A)&quot;
    },
    {
        &quot;gir_id&quot;: 2552,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES DIVERSOS (SERVICIO PROFESIONAL EN INGENIER&Iacute;A)&quot;
    },
    {
        &quot;gir_id&quot;: 2553,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A Y PASTELER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2554,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DIVERSOS, COTILL&Oacute;N, PI&Ntilde;ATER&Iacute;A (PI&Ntilde;ATER&Iacute;A) Y BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2555,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;A, JUGUER&Iacute;A, HELADER&Iacute;A, DULCER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2556,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRANSPORTE DE CARGA&quot;
    },
    {
        &quot;gir_id&quot;: 2557,
        &quot;gir_descripcion&quot;: &quot;CASA NATURISTA (VENTA DE PRODUCTOS NATURALES)&quot;
    },
    {
        &quot;gir_id&quot;: 2558,
        &quot;gir_descripcion&quot;: &quot;LIBRER&Iacute;A, BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2559,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PLANTAS Y FLORES (FLORER&Iacute;A) Y BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2561,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;AS, SANDWICHER&Iacute;AS, HELADER&Iacute;AS, DULCER&Iacute;AS, JUGUER&Iacute;AS (CAFETER&Iacute;A)&quot;
    },
    {
        &quot;gir_id&quot;: 2562,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA PARA BEB&Eacute;S Y VENTA DE ROPA PARA NI&Ntilde;OS&quot;
    },
    {
        &quot;gir_id&quot;: 2563,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA CONTROL DE CALIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2564,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS REALIZADOS POR M&Aacute;QUINAS EXPENDEDORAS (VENTA DE BOLETOS DE LOTER&Iacute;A Y APUESTAS DEPORTIVAS)&quot;
    },
    {
        &quot;gir_id&quot;: 2565,
        &quot;gir_descripcion&quot;: &quot;PANADER&Iacute;A, MINIMARKET Y CAFETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2566,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES MINERAS&quot;
    },
    {
        &quot;gir_id&quot;: 2567,
        &quot;gir_descripcion&quot;: &quot;FUENTE DE SODA, CAFETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2568,
        &quot;gir_descripcion&quot;: &quot;GIMNASIO Y VENTA DE ARTICULOS DEPORTIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 2569,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA BANCARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2570,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA BANCARIA, AGENCIA BANCARIA - CAJERO AUTOM&Aacute;TICO&quot;
    },
    {
        &quot;gir_id&quot;: 2571,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A Y JUGUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2572,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA Y ARREGLO DE MANOS&quot;
    },
    {
        &quot;gir_id&quot;: 2573,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DEDICADA A ACTIVIDADES DE ARQUITECTURA E INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2574,
        &quot;gir_descripcion&quot;: &quot;SAL&Oacute;N DE BELLEZA, MASAJES FACIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2575,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DEDICADA A LA COMERCIALIZACI&Oacute;N DE AIRE ACONDICIONADO&quot;
    },
    {
        &quot;gir_id&quot;: 2576,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEF&Oacute;NICOS, ACCESORIOS Y REPARACI&Oacute;N, OFICINA ADMINISTRATIVA DE AIRE ACONDICIONADO Y SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2577,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEF&Oacute;NICOS / VENTA DE CELULARES, ACCESORIOS Y REPARACI&Oacute;N, OFICINA ADMINISTRATIVA DE AIRE ACONDICIONADO Y SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2578,
        &quot;gir_descripcion&quot;: &quot;BAZAR Y REGALOS, VENTA DE FLORES&quot;
    },
    {
        &quot;gir_id&quot;: 2579,
        &quot;gir_descripcion&quot;: &quot;CASA DE CAMBIO&quot;
    },
    {
        &quot;gir_id&quot;: 2580,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTEFACTOS DE ILUMINACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2581,
        &quot;gir_descripcion&quot;: &quot;ZAPATERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2582,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA OFICINA DE CONSULTOR&Iacute;A Y CONSTRUCCI&Oacute;N CIVIL&quot;
    },
    {
        &quot;gir_id&quot;: 2583,
        &quot;gir_descripcion&quot;: &quot;MINIMARKET, BAZAR, LIBRER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2584,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DIVERSOS, COTILLOS, PI&Ntilde;ATER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2585,
        &quot;gir_descripcion&quot;: &quot;SERV. ADM. RELACIONADOS CON COMERCIO OFICINA ADMINISTRATIVA&quot;
    },
    {
        &quot;gir_id&quot;: 2586,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA SERVICIO DE ASESORIA Y CONSULTORIA EN SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2587,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;A, JUGUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2588,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA COMERCIALIZACI&Oacute;N DE BICICLETA Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2589,
        &quot;gir_descripcion&quot;: &quot;BODEGA (ABARROTES)&quot;
    },
    {
        &quot;gir_id&quot;: 2590,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;A, HELADER&Iacute;A, DULCER&Iacute;A, JUGUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2591,
        &quot;gir_descripcion&quot;: &quot;CERRAJERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2592,
        &quot;gir_descripcion&quot;: &quot;EDUCACI&Oacute;N SUPERIOR&quot;
    },
    {
        &quot;gir_id&quot;: 2593,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE UNA EMPRESA CONSTRUCTORA&quot;
    },
    {
        &quot;gir_id&quot;: 2594,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE EST&Eacute;TICA UNISEX&quot;
    },
    {
        &quot;gir_id&quot;: 2595,
        &quot;gir_descripcion&quot;: &quot;BAZAR Y REGALOS, VENTA DE JUGUETES, BIJOUTER&Iacute;A Y VENTA DE GOLOSINAS Y CONF&Iacute;TERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2596,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEF&Oacute;NICOS, VENTA DE CELULARES&quot;
    },
    {
        &quot;gir_id&quot;: 2597,
        &quot;gir_descripcion&quot;: &quot;SEGURO DE VIDA Y OTROS&quot;
    },
    {
        &quot;gir_id&quot;: 2598,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDAD ARQUITECTURA, INGENIERIA Y TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 2599,
        &quot;gir_descripcion&quot;: &quot;PATINES&quot;
    },
    {
        &quot;gir_id&quot;: 2600,
        &quot;gir_descripcion&quot;: &quot;OFICINA  ADMINISTRATIVA DE MOBILIARIO MEDICO Y OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 2601,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE MONITOREO DE ALARMAS&quot;
    },
    {
        &quot;gir_id&quot;: 2602,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDAD AGR&Iacute;COLA Y AGROINDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2537,
        &quot;gir_descripcion&quot;: &quot;BOTICA, PERFUMER&Iacute;A Y REGALOS&quot;
    },
    {
        &quot;gir_id&quot;: 2603,
        &quot;gir_descripcion&quot;: &quot;SERV. DE MASAJES FACIALES, ARREGLO DE MANOS Y PIES, PODOLOGIA, SALON DE BELLEZA, MASOTERAPIA&quot;
    },
    {
        &quot;gir_id&quot;: 2604,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEF&Oacute;NICOS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2605,
        &quot;gir_descripcion&quot;: &quot;LIBRERIA, BAZAR Y REGALOS, VENTA DE PRODUCTOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 2606,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA VENTA DE EQUIPOS Y PLATAFORMA GPS&quot;
    },
    {
        &quot;gir_id&quot;: 2608,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE ARQUITECTURA E INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2609,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DEDICADA A LA DIAGRAMACION Y EDICION DE FORMATOS&quot;
    },
    {
        &quot;gir_id&quot;: 2610,
        &quot;gir_descripcion&quot;: &quot;FUENTE DE SODA, CAFETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3107,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE ARQUITECTURA&quot;
    },
    {
        &quot;gir_id&quot;: 2612,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INMOBILIARIA Y CONSTRUCTORA&quot;
    },
    {
        &quot;gir_id&quot;: 2613,
        &quot;gir_descripcion&quot;: &quot;JOYERIA, BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2614,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE SOFTWARE&quot;
    },
    {
        &quot;gir_id&quot;: 2615,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DIVERSOS PARA EL HOGAR (VENTA DE ARTICULOS DE DECORACION PARA EL HOGAR)&quot;
    },
    {
        &quot;gir_id&quot;: 2616,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ADMINISTRACION DE CONDOMINIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2617,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE EDUCACI&Oacute;N INICIAL Y BASICA&quot;
    },
    {
        &quot;gir_id&quot;: 2618,
        &quot;gir_descripcion&quot;: &quot;BAZAR PERFUMERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2620,
        &quot;gir_descripcion&quot;: &quot;MINIMARKET, CAFETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2621,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA (VENTA DE UNIFORMES Y ROPA DEPORTIVA), BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2623,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE VIAJES Y TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 2624,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA DISTRIBUCION FARMACEUTICA&quot;
    },
    {
        &quot;gir_id&quot;: 2622,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DIVERSOS PARA EL HOGAR (VENTA DE PRODUCTOS PARA DECORACI&Oacute;N DEL HOGAR)&quot;
    },
    {
        &quot;gir_id&quot;: 2625,
        &quot;gir_descripcion&quot;: &quot;CAFETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2627,
        &quot;gir_descripcion&quot;: &quot;INTERMEDIACI&Oacute;N FINANCIERA / BANCA DE CONSUMO (AGENCIA BANCARIA)&quot;
    },
    {
        &quot;gir_id&quot;: 2626,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ACTIVIDADES DE CONSULTOR&Iacute;A DE GESTI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2629,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORAMIENTO EMPRESARIAL Y AGENCIAMIENTO MARITIMO&quot;
    },
    {
        &quot;gir_id&quot;: 2630,
        &quot;gir_descripcion&quot;: &quot;HELADERIA, DULCERIA, JUGUERIA, CAFETERIA, SANDWICHERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2631,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINA) OFICINA VENTA DE TARJETAS&quot;
    },
    {
        &quot;gir_id&quot;: 2632,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE TODO TIPO POR CATALOGO (VENTA DE PRODUCTOS DE TODO TIPO PARA EL HOGAR POR CATALOGO)&quot;
    },
    {
        &quot;gir_id&quot;: 2633,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE RECOLECCION Y DISTRIBUCION DE ROPA POR LAS LAVANDERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2634,
        &quot;gir_descripcion&quot;: &quot;CAFETERIA, SANDWICHERIA, HELADERIA, DULCERIA, JUGUERIA (FUENTE DE SODA, CAFETERIA)&quot;
    },
    {
        &quot;gir_id&quot;: 2635,
        &quot;gir_descripcion&quot;: &quot;BAZAR, VENTA DE JUGUETES, BIJOUTERIA Y ARTICULOS DE VIDRIO, LIBRERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2636,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE EQUIPO MEDICO&quot;
    },
    {
        &quot;gir_id&quot;: 2637,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA Y DE INFORMES DE EMPRESA DE ALIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2638,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA EN CONSULTORIA EN SISTEMAS DE GESTION&quot;
    },
    {
        &quot;gir_id&quot;: 2639,
        &quot;gir_descripcion&quot;: &quot;JUEGOS ELECTRONICOS (VENTA DE APARATOS ELECTRONICOS), VENTA DE DISCOS, CDS Y DVDS&quot;
    },
    {
        &quot;gir_id&quot;: 2640,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE VENTA DE PRODUCTOS MEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2641,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS Y EQUIPOS DE USO DOMESTICO, SERVICIO DE MANTENIMIENTO Y REPARACION DE EQUIPOS&quot;
    },
    {
        &quot;gir_id&quot;: 2642,
        &quot;gir_descripcion&quot;: &quot;SUPERMERCADO - PANADERIA - COMIDA AL PASO - FERRETERIA - ELECTRODOMESTICOS - VENTA DE AUTOPARTES (SIN SERVICIO) - LICORERIA - FARMACIA&quot;
    },
    {
        &quot;gir_id&quot;: 2643,
        &quot;gir_descripcion&quot;: &quot;SANDWICHERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2644,
        &quot;gir_descripcion&quot;: &quot;SANDWICHERIA, CAFETERIA, JUGUERIA, HELADERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2645,
        &quot;gir_descripcion&quot;: &quot;CAFETERIA, SANDWICHERIA, JUGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2646,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PRESTADOS POR MAQUINAS EXPENDEDORAS (VENTA DE LOTER&Iacute;A)&quot;
    },
    {
        &quot;gir_id&quot;: 2647,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PLANTAS Y FLORES (FLORERIA), BAZAR Y REGALOS (BAZAR)&quot;
    },
    {
        &quot;gir_id&quot;: 2648,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ADMINISTRACION DE DISE&Ntilde;O DE INTERIORES Y MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2649,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AGENCIA BANCARIA&quot;
    },
    {
        &quot;gir_id&quot;: 1606,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE PASTELER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2650,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACION Y COMERCIALIZACION DE UTENSILIOS PARA COCINA Y COMESTIBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2651,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACI&Oacute;N Y COMERCIALIZACI&Oacute;N DE UTENSILIOS PARA COCINA Y COMESTIBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2652,
        &quot;gir_descripcion&quot;: &quot;BAZARES Y REGALOS&quot;
    },
    {
        &quot;gir_id&quot;: 2653,
        &quot;gir_descripcion&quot;: &quot;BAZARES&quot;
    },
    {
        &quot;gir_id&quot;: 2654,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO ODONTOLOGICO&quot;
    },
    {
        &quot;gir_id&quot;: 2656,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PAN Y PRODUCTOS DE PANADERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2657,
        &quot;gir_descripcion&quot;: &quot;LIBRERIA, BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2658,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DEDICADA A LA COMERCIALIZACI&Oacute;N DE GRUPOS ELECTROGENOS, MAQUINARIA PESADA, REPUESTOS Y SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2659,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE RECOLECCION DE ROPA POR LAVANDERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2660,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTORIA, EJECUCI&Oacute;N DE PROYECTOS DE MINER&Iacute;A, CONSTRUCCI&Oacute;N Y AFINES&quot;
    },
    {
        &quot;gir_id&quot;: 2661,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA MADERERA&quot;
    },
    {
        &quot;gir_id&quot;: 2662,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE MONITOREO Y MANTENIMIENTO DE REDES&quot;
    },
    {
        &quot;gir_id&quot;: 2663,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORIA Y CAPACITACI&Oacute;N EMPRESARIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2664,
        &quot;gir_descripcion&quot;: &quot;CAMPO DEPORTIVO DE FUTBOL&quot;
    },
    {
        &quot;gir_id&quot;: 2665,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N, EXPORTACI&Oacute;N, REPRESENTACI&Oacute;N, FABRICACI&Oacute;N, PRODUCCI&Oacute;N, COMERCIALIZACI&Oacute;N Y DISTRIBUCI&Oacute;N, PRODUCCI&Oacute;N DE INSUMOS&quot;
    },
    {
        &quot;gir_id&quot;: 2666,
        &quot;gir_descripcion&quot;: &quot;PRODUCTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2667,
        &quot;gir_descripcion&quot;: &quot;BIENES Y SERVICIOS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2668,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BOLETOS DE LOTER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2669,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROYECTOS DE ILUMINACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2670,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRATAMIENTO SANITARIO&quot;
    },
    {
        &quot;gir_id&quot;: 2671,
        &quot;gir_descripcion&quot;: &quot;LABORATORIO DE ANALISIS CLINICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2672,
        &quot;gir_descripcion&quot;: &quot;CEBICHER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2673,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE TRANSPORTE TURISTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2674,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MATERIALES DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 2675,
        &quot;gir_descripcion&quot;: &quot;ACCESORIOS DE CELULARES&quot;
    },
    {
        &quot;gir_id&quot;: 2676,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SISTEMA DE CONTROL DE FLOTAS VIA GPS&quot;
    },
    {
        &quot;gir_id&quot;: 2677,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE ACOPIO&quot;
    },
    {
        &quot;gir_id&quot;: 2678,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS DE SOSTENIMIENTO PARA MINER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2679,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS ARTESANALES&quot;
    },
    {
        &quot;gir_id&quot;: 2680,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A Y PROYECTOS DE GAS NATURAL&quot;
    },
    {
        &quot;gir_id&quot;: 2681,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE MATERIALES M&Eacute;DICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2682,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CALZADO&quot;
    },
    {
        &quot;gir_id&quot;: 2683,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2684,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS PARA CELULARES O APARATOS TELEF&Oacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2387,
        &quot;gir_descripcion&quot;: &quot;TALLER DE MEC&Aacute;NICA&quot;
    },
    {
        &quot;gir_id&quot;: 2685,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INSUMOS QU&Iacute;MICOS Y FUMIGACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2686,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TELECOMUNICACIONES MONITOREO Y MANTENIMIENTO DE REDES&quot;
    },
    {
        &quot;gir_id&quot;: 2687,
        &quot;gir_descripcion&quot;: &quot;CLUBES CAMPESTRES O RECREACIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 2688,
        &quot;gir_descripcion&quot;: &quot;CLUBES DEPORTIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 2628,
        &quot;gir_descripcion&quot;: &quot;ZAPATER&Iacute;A, BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2761,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS PARA CELULARES&quot;
    },
    {
        &quot;gir_id&quot;: 2619,
        &quot;gir_descripcion&quot;: &quot;CAFETERIA, SANDWICHERIA, HELADERIA, DULCERIA Y JUGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2689,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIER&Iacute;A CIVIL Y CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2690,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE MONITOREO Y MANTENIMIENTO DE REDES DE TELECOMUNICACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 2691,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPUESTOS PARA MAQUINARIA Y EQUIPOS&quot;
    },
    {
        &quot;gir_id&quot;: 2692,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE EQUIPOS DE TELEFONIA, AUDIO VIDEO Y COMUNICACIONES EN GENERAL - JUGUETES&quot;
    },
    {
        &quot;gir_id&quot;: 2693,
        &quot;gir_descripcion&quot;: &quot;VENTA DE BEBIDAS GASEOSAS&quot;
    },
    {
        &quot;gir_id&quot;: 2694,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SISTEMAS Y DISE&Ntilde;O&quot;
    },
    {
        &quot;gir_id&quot;: 2695,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE BIENES Y SERVICIOS GENERALES&quot;
    },
    {
        &quot;gir_id&quot;: 2696,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA DE DEPORTES&quot;
    },
    {
        &quot;gir_id&quot;: 2697,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS Y ROPA DEPORTIVA&quot;
    },
    {
        &quot;gir_id&quot;: 2698,
        &quot;gir_descripcion&quot;: &quot;VENTA DE SUPLEMENTOS VITAM&Iacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2699,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA INSPECCI&Oacute;N Y CERTIFICACI&Oacute;N DE EQUIPOS DE IZAJE&quot;
    },
    {
        &quot;gir_id&quot;: 2700,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EQUIPO Y PLATAFORMA GPS&quot;
    },
    {
        &quot;gir_id&quot;: 2701,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS TURISTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2702,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS Y EQUIPOS DE USO DOMESTICO&quot;
    },
    {
        &quot;gir_id&quot;: 2703,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;AS, SANDWICHER&Iacute;AS, DULCER&Iacute;AS, JUGUER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 2704,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA EVENTOS SOCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2705,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ANTIGUEDADES&quot;
    },
    {
        &quot;gir_id&quot;: 2706,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRANSPORTE DE CARGA POR CARRETERA&quot;
    },
    {
        &quot;gir_id&quot;: 2707,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SEGURIDAD INDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2708,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A Y CONSULTOR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2709,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ARTICULOS DE CAUCHO&quot;
    },
    {
        &quot;gir_id&quot;: 2710,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE CALZADO EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2711,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE PRENDAS DE VESTIR Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2713,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS PL&Aacute;STICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2714,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACI&Oacute;N DE MAQUINARIA AGROPECUARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2715,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN CIENCIAS DE LA COMUNICACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2716,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN PSICOLOG&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2717,
        &quot;gir_descripcion&quot;: &quot;EDUCACI&Oacute;N INICIAL, PRIMARIA Y SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2718,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2719,
        &quot;gir_descripcion&quot;: &quot;LOCAL INSTITUCIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2720,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MASAJES FACIALES ARREGLO DE MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 2721,
        &quot;gir_descripcion&quot;: &quot;BARBER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2722,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A AMBIENTAL, EMPRESARIAL, SEGURIDAD OCUPACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2723,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA A LAS TRANSACCIONES CON MONEDAS EXTRANJERAS&quot;
    },
    {
        &quot;gir_id&quot;: 2724,
        &quot;gir_descripcion&quot;: &quot;LICORER&Iacute;A SIN CONSUMO&quot;
    },
    {
        &quot;gir_id&quot;: 2725,
        &quot;gir_descripcion&quot;: &quot;COMUNICACIONES TELEF&Oacute;NICAS, CABINAS DE INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 2727,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA A LA COMERCIALIZACI&Oacute;N DE ALIMENTOS Y ALIMENTACI&Oacute;N CORPORATIVA&quot;
    },
    {
        &quot;gir_id&quot;: 2728,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRESTACI&Oacute;N DE SERVICIOS DE MANTENIMIENTO PREDICTIVO PARA EL SECTOR INDUSTRIAL Y MINERO&quot;
    },
    {
        &quot;gir_id&quot;: 2729,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS REALIZADO POR M&Aacute;QUINAS EXPENDEDORAS&quot;
    },
    {
        &quot;gir_id&quot;: 2730,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS TASACIONES Y CONSULTOR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2731,
        &quot;gir_descripcion&quot;: &quot;VENTA DE DULCES&quot;
    },
    {
        &quot;gir_id&quot;: 2732,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACI&Oacute;N DE SERVICIOS M&Eacute;DICOS INTERNACIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 2733,
        &quot;gir_descripcion&quot;: &quot;VENTA DE MATERIALES EL&Eacute;CTRICOS E ILUMINACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2734,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A Y CONSULTOR&Iacute;A DE OBRAS CIVILES Y PAVIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2735,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CERAMICOS Y PISOS - GRANDES ALMACENES&quot;
    },
    {
        &quot;gir_id&quot;: 2736,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE ARQUITECTURA, INGENIER&Iacute;A Y ASESOR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2737,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INTERMEDIACI&Oacute;N DE COMPRA Y VENTA DE VEH&Iacute;CULOS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2738,
        &quot;gir_descripcion&quot;: &quot;TALLER DE MANTENIMIENTO Y REPARACI&Oacute;N DE VEH&Iacute;CULOS AUTOMOTORES&quot;
    },
    {
        &quot;gir_id&quot;: 3108,
        &quot;gir_descripcion&quot;: &quot;INGENIERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2740,
        &quot;gir_descripcion&quot;: &quot;TABAQUERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2741,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIOS DE MEDICOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2742,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEH&Iacute;CULOS DE PASAJEROS&quot;
    },
    {
        &quot;gir_id&quot;: 2743,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE FERRETER&Iacute;A Y MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2744,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE TRANSPORTE DE PRODUCTOS DE PELUQUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2745,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE ACTIVIDADES DE INGENIER&Iacute;A, CONSTRUCCI&Oacute;N Y MANTENIMIENTO DE ESTACI&Oacute;N DE SERVICIOS (GRIFOS)&quot;
    },
    {
        &quot;gir_id&quot;: 2746,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE ILUMINACI&Oacute;N Y BRONCE&quot;
    },
    {
        &quot;gir_id&quot;: 2747,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N DE MAQUINARIA Y REPUESTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2748,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS GENERALES E INMOBILIARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2749,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESAS DE BEBIDAS&quot;
    },
    {
        &quot;gir_id&quot;: 2750,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE BIENES Y SERVICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2751,
        &quot;gir_descripcion&quot;: &quot;VENTA DE FLORES Y PLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2752,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ADQUISICI&Oacute;N DE FACTURAS NEGOCIABLES&quot;
    },
    {
        &quot;gir_id&quot;: 2753,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PLAYAS DE ESTACIONAMIENTO (LIMPIEZA)&quot;
    },
    {
        &quot;gir_id&quot;: 2754,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CARGA INTERNACIONAL Y OPERADOR LOGISTICO&quot;
    },
    {
        &quot;gir_id&quot;: 2755,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA FOTOGR&Aacute;FICA&quot;
    },
    {
        &quot;gir_id&quot;: 2756,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRANSPORTE Y TRASLADO DE CARGA INTERNACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2757,
        &quot;gir_descripcion&quot;: &quot;VINOTECA (SIN CONSUMO)&quot;
    },
    {
        &quot;gir_id&quot;: 2758,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA EN ASESOR&Iacute;A DE AIRE ACONDICIONADO&quot;
    },
    {
        &quot;gir_id&quot;: 2759,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2760,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INGENIERIA, CONSULTORIA Y EJECICI&Oacute;N DE PROYECTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2762,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISE&Ntilde;O, CONSTRUCCI&Oacute;N Y EQUIPAMIENTO DE PISCINAS&quot;
    },
    {
        &quot;gir_id&quot;: 2763,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRANSPORTE DE ALIMENTO&quot;
    },
    {
        &quot;gir_id&quot;: 2764,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N, EXPORTACI&Oacute;N, COMERCIALIZACI&Oacute;N Y DISTRIBUCI&Oacute;N DE PRODUCTOS FARMACEUTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2765,
        &quot;gir_descripcion&quot;: &quot;SUPLEMENTOS VITAMINICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2766,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRESTACI&Oacute;N DE SERVICIOS LOG&Iacute;STICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2767,
        &quot;gir_descripcion&quot;: &quot;TARJETAS DE CREDITO&quot;
    },
    {
        &quot;gir_id&quot;: 2768,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SANEAMIENTO AMBIENTAL&quot;
    },
    {
        &quot;gir_id&quot;: 2769,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS POSTALES&quot;
    },
    {
        &quot;gir_id&quot;: 2770,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 2771,
        &quot;gir_descripcion&quot;: &quot;SALAS DE CONVENCIONES Y AUDITORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2772,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA ORGANIZACI&Oacute;N DE EVENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2773,
        &quot;gir_descripcion&quot;: &quot;ROPA DE DAMAS&quot;
    },
    {
        &quot;gir_id&quot;: 2774,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MANTENIMIENTO DE COMPUTADORAS&quot;
    },
    {
        &quot;gir_id&quot;: 2775,
        &quot;gir_descripcion&quot;: &quot;COMIDA RAPIDA&quot;
    },
    {
        &quot;gir_id&quot;: 2776,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA ASESOR&Iacute;A INMOBILIAR&Iacute;A Y FINANCIERA&quot;
    },
    {
        &quot;gir_id&quot;: 2777,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS Y REPUESTOS PARA VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 2778,
        &quot;gir_descripcion&quot;: &quot;TALLER DE MECANICA&quot;
    },
    {
        &quot;gir_id&quot;: 2779,
        &quot;gir_descripcion&quot;: &quot;CLUB SOCIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2780,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA SERVICIOS DE LINEA BLANCA Y AIRE ACONDICIONADO&quot;
    },
    {
        &quot;gir_id&quot;: 2781,
        &quot;gir_descripcion&quot;: &quot;POLLOS&quot;
    },
    {
        &quot;gir_id&quot;: 2782,
        &quot;gir_descripcion&quot;: &quot;PARRILLAS&quot;
    },
    {
        &quot;gir_id&quot;: 2783,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ARTICULOS DE OFICINA&quot;
    },
    {
        &quot;gir_id&quot;: 2784,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PIZZAS Y PASTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2785,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE LAVADO DE AUTOS A DOMICILIO CON PRODUCTOS ECOLOGICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2786,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE RECAUDO&quot;
    },
    {
        &quot;gir_id&quot;: 2787,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA A LA FABRICACI&Oacute;N DE PRODUCTOS FARMACEUTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2788,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE REMODELACIONES Y REFACCIONES DE DIFERENTES LOCALES&quot;
    },
    {
        &quot;gir_id&quot;: 2789,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA LABORES DE CONSULTOR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2790,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE ACCESORIOS CELULARES&quot;
    },
    {
        &quot;gir_id&quot;: 2791,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS ALIMENTICIOS Y DE TRANSPORTE&quot;
    },
    {
        &quot;gir_id&quot;: 2792,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 2793,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DE TRANSPORTE Y COMPRA VENTA DE PRODUCTOS PELUQUER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2794,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMPRA Y VENTA DE CUADROS, ELABORACI&Oacute;N DE CORTINAS, TAPICER&Iacute;A, ARTE Y DECORACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2795,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA VENTA DE BALANZAS&quot;
    },
    {
        &quot;gir_id&quot;: 2796,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS Y ESTUDIOS HIDROGR&Aacute;FICOS DE CONSULTOR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2797,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE PRUEBA DE HERMETECIDAD DE TANQUES&quot;
    },
    {
        &quot;gir_id&quot;: 2798,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO DE NIVEL INICIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2799,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE REPRESENTACI&Oacute;N DE PRODUCTOS INDUSTRIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2800,
        &quot;gir_descripcion&quot;: &quot;ESCUELA SUPERIOR DE TEOLOG&Iacute;A CON INTERNADO&quot;
    },
    {
        &quot;gir_id&quot;: 2801,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA COMPRA Y VENTA DE REPUESTOS INDUSTRIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2802,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TR&Aacute;MITE DOCUMENTARIO&quot;
    },
    {
        &quot;gir_id&quot;: 2803,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA EMPRESA DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2804,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A CONTABLE Y LEGAL&quot;
    },
    {
        &quot;gir_id&quot;: 2805,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA AGROINDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2806,
        &quot;gir_descripcion&quot;: &quot;BAZAR Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2807,
        &quot;gir_descripcion&quot;: &quot;EQUIPOS CELULARES&quot;
    },
    {
        &quot;gir_id&quot;: 2808,
        &quot;gir_descripcion&quot;: &quot;REPARACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 1553,
        &quot;gir_descripcion&quot;: &quot;VENTA Y EXHIBICI&Oacute;N DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 2809,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA SERVICIO DE REFRIGERACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2810,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DEPORTIVOS&quot;
    },
    {
        &quot;gir_id&quot;: 2811,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALIMENTOS ENVASADOS, SUPLEMENTOS VITAM&Iacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2812,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE RECOLECCI&Oacute;N Y TRANSPORTE DE RESIDUOS&quot;
    },
    {
        &quot;gir_id&quot;: 2813,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA CON EXHIBICI&Oacute;N DE EQUIPOS Y ACCESORIOS PARA PISCINA, SAUNA, FUENTES ORNAMENTALES, JACUZZI&quot;
    },
    {
        &quot;gir_id&quot;: 2814,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE ARQUITECTURA E INGENIER&Iacute;A PARA PISCINAS&quot;
    },
    {
        &quot;gir_id&quot;: 2815,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA INMOBILIARIA Y CONSTRUCTORA&quot;
    },
    {
        &quot;gir_id&quot;: 2816,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ALARMAS DE SEGURIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2817,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE IMPORTACI&Oacute;N DE EQUIPOS DE SEGURIDAD INDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2818,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A CONSULTOR&Iacute;A DE ARQUITECTURA E INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2819,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE EXPORTACI&Oacute;N DE CONCHAS DE ABANICO&quot;
    },
    {
        &quot;gir_id&quot;: 2820,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE LOCALES COMERCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2821,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS GENERALES&quot;
    },
    {
        &quot;gir_id&quot;: 2822,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EMPRESA DEDICADA A LA DISTRIBUCI&Oacute;N DE PRODUCTOS FARMAC&Eacute;UTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2823,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMPRA, REPRESENTACI&Oacute;N, IMPORTACI&Oacute;N Y EXPORTACI&Oacute;N DE TODO TIPO DE MATERIALES, MAQUINAR&Iacute;A Y EQUIPO PARA LA CONSTRUCCI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2824,
        &quot;gir_descripcion&quot;: &quot;POLICLINICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2825,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE MANTENIMIENTO GENERAL DE EDIFICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2826,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA AGENCIAMIENTO DE CARGA INTERNACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2827,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE ART&Iacute;CULOS DE LABORATORIO POR CATALOGO&quot;
    },
    {
        &quot;gir_id&quot;: 2828,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSTRUCCI&Oacute;N DE EDIFICIOS, ARQUITECTURA E INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2829,
        &quot;gir_descripcion&quot;: &quot;TATUAJES&quot;
    },
    {
        &quot;gir_id&quot;: 2830,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE ALIMENTOS Y RESTAURACI&Oacute;N A EMPRESAS&quot;
    },
    {
        &quot;gir_id&quot;: 2831,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EDIFICIO COMERCIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2832,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE DISTRIBUCI&Oacute;N EN CONSUMO MASIVO&quot;
    },
    {
        &quot;gir_id&quot;: 2833,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMPRA Y VENTA DE DIVISAS&quot;
    },
    {
        &quot;gir_id&quot;: 2834,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRE - UNIVERSITARIO NIVEL SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2835,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE PRODUCTOS ORGANICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2836,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA CONTRATISTAS GENERALES&quot;
    },
    {
        &quot;gir_id&quot;: 2837,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROMOCI&Oacute;N PARA LA EXPORTACI&Oacute;N DE PRODUCTOS AGR&Iacute;COLAS, LANA DE ALPACA Y PISCO, MEDIANTE PLATAFORMAS EN INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 2838,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DENTALES DE ODONTOLOG&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2839,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA PARA ACTIVIDADES DE GESTI&Oacute;N Y CONTABILIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2840,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPORTACI&Oacute;N DE FRUTAS FRESCAS&quot;
    },
    {
        &quot;gir_id&quot;: 2841,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE INDUSTRIA MADERERA&quot;
    },
    {
        &quot;gir_id&quot;: 2842,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEH&Iacute;CULOS AUTOMOTORES NUEVOS&quot;
    },
    {
        &quot;gir_id&quot;: 2843,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE MANTENIMIENTO, LIMPIEZA, PINTURA Y REMODELACI&Oacute;N DE EDIFICIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2844,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA GERENCIA TELEFON&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2845,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 2846,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE CONCILIACI&Oacute;N EXTRAJUDICIAL, ACTIVIDADES DE ARBITRAJE, ESTUDIO JUR&Iacute;DICO&quot;
    },
    {
        &quot;gir_id&quot;: 2847,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DEDICADA A LA FABRICACI&Oacute;N DE ABONO Y COMP. DE NITR&Oacute;GENO&quot;
    },
    {
        &quot;gir_id&quot;: 2848,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES DE PSICOLOGIA&quot;
    },
    {
        &quot;gir_id&quot;: 2849,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE INFORMATICA&quot;
    },
    {
        &quot;gir_id&quot;: 2850,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRAMITE DOCUMENTARIO&quot;
    },
    {
        &quot;gir_id&quot;: 2851,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA SANEAMIENTO AMBIENTAL&quot;
    },
    {
        &quot;gir_id&quot;: 2852,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE LAVADO Y MANTENIMIENTO DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 2853,
        &quot;gir_descripcion&quot;: &quot;OFICINAS ADMINISTRATIVAS DE GESTI&Oacute;N, ORGANIZACI&Oacute;N, ADMINISTRACI&Oacute;N, DESARROLLO DE PROYECTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2854,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EVENTOS SOCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2855,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PARTES, PIEZAS Y ACCESORIOS PARA VEH&Iacute;CULOS AUTOMOTORES Y OFICINA ADMINISTRATIVA DE PUBLICIDAD&quot;
    },
    {
        &quot;gir_id&quot;: 2856,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TRANSACCIONES FINANCIERAS, COMPRA Y VENTA DE CRYPTO MONEDAS EN MONEDA NACIONAL E INTERNACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2857,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE ALIMENTOS, BEBIDAS Y TABACO&quot;
    },
    {
        &quot;gir_id&quot;: 2858,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS DE FARMACIA Y ART&Iacute;CULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 2859,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AGROEXPORTACI&Oacute;N Y PROYECTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2860,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA AL POR MENOR DE CALZADO Y ARTICULOS DE CUERO&quot;
    },
    {
        &quot;gir_id&quot;: 2861,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE VENTA DE PRODUCTOS DE TODO TIPO POR CAT&Aacute;LOGO&quot;
    },
    {
        &quot;gir_id&quot;: 2862,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO LOG&Iacute;STICO DE TRANSPORTE DE CARGA NACIONAL E INTERNACIONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2863,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A PARA LA ORGANIZACI&Oacute;N DE CURSOS DE CAPACITACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2864,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS PARA EL TRATAMIENTO DEL AGUA Y AIRE, SERVICIOS GENERALES HOGAR, INDUSTRIA&quot;
    },
    {
        &quot;gir_id&quot;: 2865,
        &quot;gir_descripcion&quot;: &quot;CENTROS MEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2866,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA EMPRESA DEDICADA A ACTIVIDAD MINERA&quot;
    },
    {
        &quot;gir_id&quot;: 2867,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE ASESORIA Y CONSULTORIA DE PROYECTOS, DISE&Ntilde;O Y EJECUCI&Oacute;N DE PROYECTOS DE OBRAS&quot;
    },
    {
        &quot;gir_id&quot;: 2868,
        &quot;gir_descripcion&quot;: &quot;AFINAMIENTO&quot;
    },
    {
        &quot;gir_id&quot;: 2869,
        &quot;gir_descripcion&quot;: &quot;MEC&Aacute;NICA MENOR&quot;
    },
    {
        &quot;gir_id&quot;: 2870,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE LLANTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2871,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO PSICOLOGIA&quot;
    },
    {
        &quot;gir_id&quot;: 2872,
        &quot;gir_descripcion&quot;: &quot;ESCUELA DE ARTES MARCIALES&quot;
    },
    {
        &quot;gir_id&quot;: 2873,
        &quot;gir_descripcion&quot;: &quot;ACCESORIOS Y SOFTWARE&quot;
    },
    {
        &quot;gir_id&quot;: 2874,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE COMPRA VENTA DE DIVISAS&quot;
    },
    {
        &quot;gir_id&quot;: 2875,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE BIENES Y SERVICIOS EN GENERAL&quot;
    },
    {
        &quot;gir_id&quot;: 2876,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE AUTOMATIZACI&Oacute;N INDUSTRIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2877,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS PROFESIONALES EN ADMINISTRACI&Oacute;N AEROESPACIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2878,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE TELEMETR&Iacute;A Y GPS PARA VEH&Iacute;CULOS DE CARGA TERRESTRE&quot;
    },
    {
        &quot;gir_id&quot;: 2879,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EQUIPOS MEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2880,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SISTEMA Y DISE&Ntilde;O&quot;
    },
    {
        &quot;gir_id&quot;: 2881,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE TERMINACI&Oacute;N DE EDIFICIOS Y SERVICIOS GENERALES&quot;
    },
    {
        &quot;gir_id&quot;: 2882,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS DE SEGURIDAD ELECTR&Oacute;NICA&quot;
    },
    {
        &quot;gir_id&quot;: 2883,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE GESTI&Oacute;N DE COBRANZA, CAMBIO Y ENVIO&quot;
    },
    {
        &quot;gir_id&quot;: 2884,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE IMPORTACI&Oacute;N Y DISTRIBUCI&Oacute;N DE PRODUCTOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2885,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ALQUILER DE AUTOS&quot;
    },
    {
        &quot;gir_id&quot;: 2886,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA Y COMERCIAL PARA LA VENTA DE PRODUCTOS DE HIGIENE PARA LA INDUSTRIA&quot;
    },
    {
        &quot;gir_id&quot;: 2887,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE INHUMACI&Oacute;N, CREMACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2888,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESORAMIENTO&quot;
    },
    {
        &quot;gir_id&quot;: 2889,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A LEGAL&quot;
    },
    {
        &quot;gir_id&quot;: 2890,
        &quot;gir_descripcion&quot;: &quot;GESTI&Oacute;N DE COBRANZA&quot;
    },
    {
        &quot;gir_id&quot;: 2891,
        &quot;gir_descripcion&quot;: &quot;MANUALIDADES&quot;
    },
    {
        &quot;gir_id&quot;: 2892,
        &quot;gir_descripcion&quot;: &quot;VENTA DE TABACO&quot;
    },
    {
        &quot;gir_id&quot;: 2893,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE NIVELACI&Oacute;N UNIVERSITARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2894,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE REHABILTACI&Oacute;N Y OTRAS TERAPIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2895,
        &quot;gir_descripcion&quot;: &quot;VENTA DE COMIDA R&Aacute;PIDA&quot;
    },
    {
        &quot;gir_id&quot;: 2896,
        &quot;gir_descripcion&quot;: &quot;RELOJERIAS Y JOYERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2897,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS TECNICOS Y ARQUITECTONICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2898,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PRODUCTOS ORG&Aacute;NICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2899,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE SERVICIOS AUTOMOTRICES&quot;
    },
    {
        &quot;gir_id&quot;: 2900,
        &quot;gir_descripcion&quot;: &quot;CONFECCIONES DE VESTIR&quot;
    },
    {
        &quot;gir_id&quot;: 2901,
        &quot;gir_descripcion&quot;: &quot;AEROBICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2902,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE PROYECTOS DE INGENIER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2903,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO M&Eacute;DICO&quot;
    },
    {
        &quot;gir_id&quot;: 2904,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE EXPORTACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2905,
        &quot;gir_descripcion&quot;: &quot;REPARACI&Oacute;N DE AUTOM&Oacute;VILES&quot;
    },
    {
        &quot;gir_id&quot;: 2906,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE DECORACIONES&quot;
    },
    {
        &quot;gir_id&quot;: 2907,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIO DE PSICOLOG&Iacute;A - GUARDER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2908,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE SERVICIOS INFORMATICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2909,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE ARREGLO DE MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 2910,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACCESORIOS ELECTRONICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2911,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ZAPATILLAS&quot;
    },
    {
        &quot;gir_id&quot;: 2912,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE ASESOR&Iacute;A Y CONSULTOR&Iacute;A PARA MARKETING&quot;
    },
    {
        &quot;gir_id&quot;: 2913,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA DE CONSULTOR&Iacute;A Y ASESOR&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2914,
        &quot;gir_descripcion&quot;: &quot;ACADEMIAS DE MUSICA&quot;
    },
    {
        &quot;gir_id&quot;: 2915,
        &quot;gir_descripcion&quot;: &quot;REPRESENTACIONES OBRAS TEATRALES Y MUSICALES (TEATROS)&quot;
    },
    {
        &quot;gir_id&quot;: 2916,
        &quot;gir_descripcion&quot;: &quot;VENTA DE REPUESTOS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2917,
        &quot;gir_descripcion&quot;: &quot;OFICINA DE CONSULTORIA SOCIO AMBIENTAL&quot;
    },
    {
        &quot;gir_id&quot;: 2918,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE TERAPIA FISICA Y REHABILITACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2919,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRIVADO DE EDUCACI&Oacute;N INICIAL Y EDUCACI&Oacute;N PRIMARIA&quot;
    },
    {
        &quot;gir_id&quot;: 2920,
        &quot;gir_descripcion&quot;: &quot;VENTA DE AUDIFONOS&quot;
    },
    {
        &quot;gir_id&quot;: 2921,
        &quot;gir_descripcion&quot;: &quot;VENTA DE CELULARES, ACCESORIOS Y REPARACION&quot;
    },
    {
        &quot;gir_id&quot;: 2922,
        &quot;gir_descripcion&quot;: &quot;DULCES Y GASEOSAS&quot;
    },
    {
        &quot;gir_id&quot;: 2923,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS FERRETEROS PARA MINER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2924,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VIVERES, ROPA Y MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 2925,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS ELECTRODOM&Eacute;STICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2926,
        &quot;gir_descripcion&quot;: &quot;VENTA DE SOUVENIR ARTESANALES&quot;
    },
    {
        &quot;gir_id&quot;: 2927,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALIMENTOS ENLATADOS Y ENVASADOS - SUPLEMENTO VITAMINICO&quot;
    },
    {
        &quot;gir_id&quot;: 2928,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE HIGIENE PERSONAL&quot;
    },
    {
        &quot;gir_id&quot;: 2929,
        &quot;gir_descripcion&quot;: &quot;PELUQUER&Iacute;A DE MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2930,
        &quot;gir_descripcion&quot;: &quot;PLANTA DE SERVICIOS PROFESIONALES&quot;
    },
    {
        &quot;gir_id&quot;: 2931,
        &quot;gir_descripcion&quot;: &quot;PELUQUER&Iacute;A, ACCESORIOS, ART&Iacute;CULOS, MASOTERAPIA, ARREGLO DE MANOS Y PIES, PRODUCTOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 2655,
        &quot;gir_descripcion&quot;: &quot;ZAPATERIA,  VENTA DE ARTICULOS DEPORTIVOS, BOUTIQUE&quot;
    },
    {
        &quot;gir_id&quot;: 2932,
        &quot;gir_descripcion&quot;: &quot;CAFETERIA, SANDWICHERIA, HELADERIA, DULCERIA, JUGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2933,
        &quot;gir_descripcion&quot;: &quot;SERV. ADM. RELACIONADOS CON COMERCIO (OFICINA ADMINISTRATIVA)&quot;
    },
    {
        &quot;gir_id&quot;: 2934,
        &quot;gir_descripcion&quot;: &quot;BAZARES Y REGALOS, VENTA DE PRODUCTOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 2935,
        &quot;gir_descripcion&quot;: &quot;MINIMARKET, CAFETERIA, SANDWICHERIA, HELADERIA, DULCERIA, JUGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2936,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE ENFERMERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2937,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE LAVADO DE ALFOMBRAS Y TAPICES CON CHAMPU&quot;
    },
    {
        &quot;gir_id&quot;: 2938,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE, VENTA DE LICOR COMO COMPLEMENTO DE COMIDA&quot;
    },
    {
        &quot;gir_id&quot;: 2939,
        &quot;gir_descripcion&quot;: &quot;BODEGA, BAZAR, LIBRERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2940,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE RECOLECCI&Oacute;N Y DISTRIBUCI&Oacute;N DE ROPA POR LAS LAVANDERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 2941,
        &quot;gir_descripcion&quot;: &quot;VENTA DE SANDWICHES&quot;
    },
    {
        &quot;gir_id&quot;: 2942,
        &quot;gir_descripcion&quot;: &quot;POLICLINICO&quot;
    },
    {
        &quot;gir_id&quot;: 2943,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE TODO TIPO POR CATALOGO (TELECOMERCIO)&quot;
    },
    {
        &quot;gir_id&quot;: 2944,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE ATENCI&Oacute;N DE PERSONAS ADULTAS MAYORES U OTRO CON CARACTER&Iacute;STICAS Y SERVICIOS SIMILARES (CON ALOJAMIENTO)&quot;
    },
    {
        &quot;gir_id&quot;: 2945,
        &quot;gir_descripcion&quot;: &quot;APLICACIONES DE ACCESORIOS EN LA PIEL&quot;
    },
    {
        &quot;gir_id&quot;: 2946,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA Y MASOTERAPIA&quot;
    },
    {
        &quot;gir_id&quot;: 2947,
        &quot;gir_descripcion&quot;: &quot;LIBRERIA, BAZAR, CABINAS DE INTERNET&quot;
    },
    {
        &quot;gir_id&quot;: 2948,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEFONICOS- VENTA DE CELULARES ACCESORIOS Y REPARACION, VENTA DE COMPUTADORAS Y SOFTWARES&quot;
    },
    {
        &quot;gir_id&quot;: 2949,
        &quot;gir_descripcion&quot;: &quot;ELABORACI&Oacute;N Y VENTA DE ALFAJORES&quot;
    },
    {
        &quot;gir_id&quot;: 2950,
        &quot;gir_descripcion&quot;: &quot;TALLER DE REPARACI&Oacute;N DE MOTOCICLETAS&quot;
    },
    {
        &quot;gir_id&quot;: 2952,
        &quot;gir_descripcion&quot;: &quot;BAZAR Y REGALOS, BOUTIQUE&quot;
    },
    {
        &quot;gir_id&quot;: 2953,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS VETERINARIOS- VENTA DE ACCESORIOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2954,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE AGUA POTABLE Y ALCANTARILLADO DE LIMA&quot;
    },
    {
        &quot;gir_id&quot;: 2955,
        &quot;gir_descripcion&quot;: &quot;SERV&quot;
    },
    {
        &quot;gir_id&quot;: 2956,
        &quot;gir_descripcion&quot;: &quot;SERV. ADM. RELACIONADO CON COMERCIO (OFICINA ADMINISTRATIVA)&quot;
    },
    {
        &quot;gir_id&quot;: 2957,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALIMENTOS ENLATADOS Y ENVASADOS- SUPLEMENTO VITAMINICO&quot;
    },
    {
        &quot;gir_id&quot;: 2958,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE TODO TIPO POR CATALOGO&quot;
    },
    {
        &quot;gir_id&quot;: 2959,
        &quot;gir_descripcion&quot;: &quot;ZAPATERIA Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 2960,
        &quot;gir_descripcion&quot;: &quot;CAFETERIA, SANDWICHERIA, HELADERIA, DULCERIA, JUGUERIA, MINIMARKET&quot;
    },
    {
        &quot;gir_id&quot;: 2961,
        &quot;gir_descripcion&quot;: &quot;ACADEMIAS DE MUSICA, REPRESENTACIONES OBRAS TEATRALES Y MUSICALES (TEATROS)&quot;
    },
    {
        &quot;gir_id&quot;: 2962,
        &quot;gir_descripcion&quot;: &quot;ALMACEN DE AUTOPARTES DE VEHICULOS&quot;
    },
    {
        &quot;gir_id&quot;: 2951,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE PREPARACI&Oacute;N Y DISTRIBUCI&Oacute;N DE ALIMENTOS A DOMICILIO&quot;
    },
    {
        &quot;gir_id&quot;: 2963,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA Y MASOTERAPIA, SERV. DE MASAJES FACIALES ARREGLO DE MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 2964,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA, VENTA DE ROPA Y PRODUCTOS DE BELLEZA (VENTA DE ARTICULOS DE TOCADOR)&quot;
    },
    {
        &quot;gir_id&quot;: 2965,
        &quot;gir_descripcion&quot;: &quot;PELUQUERIA, VENTA DE ARTICULOS DE TOCADOR&quot;
    },
    {
        &quot;gir_id&quot;: 2966,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO VETERINARIO, VENTA DE ACCESORIOS PARA ANIMALES.&quot;
    },
    {
        &quot;gir_id&quot;: 2967,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO VETERINARIO, VENTA DE ACCESORIOS PARA ANIMALES&quot;
    },
    {
        &quot;gir_id&quot;: 2968,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS VETERINARIOS/ VENTA DE ACCESORIOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2969,
        &quot;gir_descripcion&quot;: &quot;LIBRERIA, BAZAR, BODEGA&quot;
    },
    {
        &quot;gir_id&quot;: 2970,
        &quot;gir_descripcion&quot;: &quot;PANADER&Iacute;A, PASTELER&Iacute;A, FUENTE DE SODA, BODEGA&quot;
    },
    {
        &quot;gir_id&quot;: 2971,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, FUENTE DE SODA&quot;
    },
    {
        &quot;gir_id&quot;: 2972,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE CON VENTA DE LICOR COMO COMPLEMENTO DE COMIDA (CEVICHERIA)&quot;
    },
    {
        &quot;gir_id&quot;: 2973,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHERIA, HELADER&Iacute;A, DULCER&Iacute;A, JUGUER&Iacute;A, COMIDA R&Aacute;PIDA&quot;
    },
    {
        &quot;gir_id&quot;: 2974,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO DE M&Eacute;DICO VETERINARIO&quot;
    },
    {
        &quot;gir_id&quot;: 2975,
        &quot;gir_descripcion&quot;: &quot;PIZZAS, PASTAS, VENTAS DE LICOR COMO COMPLEMENTO&quot;
    },
    {
        &quot;gir_id&quot;: 2976,
        &quot;gir_descripcion&quot;: &quot;PLAYA DE ESTACIONAMIENTO (31 VEHICULOS)&quot;
    },
    {
        &quot;gir_id&quot;: 2977,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA, SERV. DE MASAJES FACIALES, ARREGLO DE MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 2978,
        &quot;gir_descripcion&quot;: &quot;CAJA DE AHORRO&quot;
    },
    {
        &quot;gir_id&quot;: 2979,
        &quot;gir_descripcion&quot;: &quot;FUENTE DE SODA, MINIMARKET&quot;
    },
    {
        &quot;gir_id&quot;: 2980,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE PREPARACI&Oacute;N Y DISTRIBUCI&Oacute;N DE ALIMENTOS A DOMICILIO, COMIDA RAPIDA, CAFETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2981,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS DE PREPARACI&Oacute;N Y DISTRIBUCI&Oacute;N DE ALIMENTOS A DOMICILIO, COMIDA R&Aacute;PIDA, CAFETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2982,
        &quot;gir_descripcion&quot;: &quot;BOUTIQUE, BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2983,
        &quot;gir_descripcion&quot;: &quot;COMPRA - VENTA DE AUTOMOVILES Y SERVICIO DE MECANICA AUTOMOTRIZ&quot;
    },
    {
        &quot;gir_id&quot;: 2984,
        &quot;gir_descripcion&quot;: &quot;COMIDA R&Aacute;PIDA, CAFETER&Iacute;A, SANDWICHER&Iacute;A, HELADER&Iacute;A, DULCER&Iacute;A, JUGUER&Iacute;A, SERVICIOS DE PREPARACI&Oacute;N Y DISTRIBUCI&Oacute;N DE ALIMENTOS A DOMICILIO&quot;
    },
    {
        &quot;gir_id&quot;: 2985,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE ESTIMULACI&Oacute;N TEMPRANA Y EDUCACI&Oacute;N INICIAL&quot;
    },
    {
        &quot;gir_id&quot;: 2986,
        &quot;gir_descripcion&quot;: &quot;PLAYA DE ESTACIONAMIENTO O GARAJES&quot;
    },
    {
        &quot;gir_id&quot;: 2988,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PAN Y PRODUCTOS DE PANADER&Iacute;A, CAFETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2989,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEFONICOS&quot;
    },
    {
        &quot;gir_id&quot;: 2990,
        &quot;gir_descripcion&quot;: &quot;VENTA DE APARATOS TELEFONICOS, VENTA DE CELULARES, ACCESORIOS Y REPARACI&Oacute;N&quot;
    },
    {
        &quot;gir_id&quot;: 2991,
        &quot;gir_descripcion&quot;: &quot;FUENTE DE SODA, CAFETER&Iacute;A (ELABORACI&Oacute;N DE ALIMENTOS)&quot;
    },
    {
        &quot;gir_id&quot;: 2992,
        &quot;gir_descripcion&quot;: &quot;COMERCIALIZACI&Oacute;N DE RESPUESTOS Y M&Aacute;QUINAS PERFORADORAS DE ROCA&quot;
    },
    {
        &quot;gir_id&quot;: 2993,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO MEDICO VETERINARIO, VENTA DE PRODUCTOS VETERINARIOS/ VENTA DE ACCESORIOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2994,
        &quot;gir_descripcion&quot;: &quot;BOUTIQUE, JOYERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2995,
        &quot;gir_descripcion&quot;: &quot;PELUQUERIA, SALON DE BELLEZA, VENTA DE PRODUCTOS DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 2996,
        &quot;gir_descripcion&quot;: &quot;TABAQUERIA (VENTA Y RESPUESTOS DE CIGARRILLOS ELECTRONICOS)&quot;
    },
    {
        &quot;gir_id&quot;: 2997,
        &quot;gir_descripcion&quot;: &quot;GIMNASIO, SPA, FISICOCULTURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 2998,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE FERRETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 2999,
        &quot;gir_descripcion&quot;: &quot;SERV. MASAJES FACIALES ARREGLO DE MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 3000,
        &quot;gir_descripcion&quot;: &quot;SERV. DE MASAJES FACIALES ARREGLO DE MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 3001,
        &quot;gir_descripcion&quot;: &quot;CAFETER&Iacute;A, SANDWICHER&Iacute;AS, HELADER&Iacute;AS, DULCER&Iacute;AS, JUGUER&Iacute;AS&quot;
    },
    {
        &quot;gir_id&quot;: 3002,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ART&Iacute;CULOS DE FERRETER&Iacute;A, VENTA DE ARTEFACTOS DE ILUMINACI&Oacute;N Y BRONCE&quot;
    },
    {
        &quot;gir_id&quot;: 3003,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS DE PANADERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3004,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ACEITES Y LUBRICANTES&quot;
    },
    {
        &quot;gir_id&quot;: 3005,
        &quot;gir_descripcion&quot;: &quot;PELUQUER&Iacute;A, SALON DE BELLEZA&quot;
    },
    {
        &quot;gir_id&quot;: 1080,
        &quot;gir_descripcion&quot;: &quot;SASTRERIA&quot;
    },
    {
        &quot;gir_id&quot;: 2987,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE PREPARACI&Oacute;N Y DISTRIBUCI&Oacute;N DE ALIMENTOS&quot;
    },
    {
        &quot;gir_id&quot;: 3006,
        &quot;gir_descripcion&quot;: &quot;CASA NATURISTA (SIN CONSULTORIO)&quot;
    },
    {
        &quot;gir_id&quot;: 3007,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA (COMPRA - VENTA - ALQUILER DE BIENES INMUEBLES PROPIOS Y/O ALQUILADOS))&quot;
    },
    {
        &quot;gir_id&quot;: 3008,
        &quot;gir_descripcion&quot;: &quot;CENTRO MEDICO&quot;
    },
    {
        &quot;gir_id&quot;: 3009,
        &quot;gir_descripcion&quot;: &quot;PELUQUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3010,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO EMPRESARIAL, CONSULTORIO EN PROYECTOS DE INGENIERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3011,
        &quot;gir_descripcion&quot;: &quot;HELADERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 3015,
        &quot;gir_descripcion&quot;: &quot;PLAYA DE ESTACIONAMIENTO (10 VEH&Iacute;CULOS)&quot;
    },
    {
        &quot;gir_id&quot;: 3016,
        &quot;gir_descripcion&quot;: &quot;Y LIMPIEZA COSM&Eacute;TICA DEL VEH&Iacute;CULO&quot;
    },
    {
        &quot;gir_id&quot;: 3017,
        &quot;gir_descripcion&quot;: &quot;PLAYAS DE ESTACIONAMIENTO O GARAJES (PLAYAS DE ESTACIONAMIENTO)&quot;
    },
    {
        &quot;gir_id&quot;: 3018,
        &quot;gir_descripcion&quot;: &quot;DULCERIA Y JUGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3019,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIO M&Eacute;DICOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 3020,
        &quot;gir_descripcion&quot;: &quot;COMPOSTURAS&quot;
    },
    {
        &quot;gir_id&quot;: 3021,
        &quot;gir_descripcion&quot;: &quot;MATERIAL E INSTRUMENTAL M&Eacute;DICO&quot;
    },
    {
        &quot;gir_id&quot;: 3022,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALIMENTOS ENLATADOS Y ENVASADOS&quot;
    },
    {
        &quot;gir_id&quot;: 3023,
        &quot;gir_descripcion&quot;: &quot;SERV. ARREGLO DE PIES Y MANOS&quot;
    },
    {
        &quot;gir_id&quot;: 3024,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEH&Iacute;CULOS AUTOMOTORES DE PASAJEROS (PARA USO PARTICULAR)&quot;
    },
    {
        &quot;gir_id&quot;: 3025,
        &quot;gir_descripcion&quot;: &quot;SANGUCHERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3026,
        &quot;gir_descripcion&quot;: &quot;JUGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3027,
        &quot;gir_descripcion&quot;: &quot;DULCERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3028,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE INGENIERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3029,
        &quot;gir_descripcion&quot;: &quot;OFICINA ADMINISTRATIVA (DE SANEAMIENTO AMBIENTAL Y CONSULTORIA)&quot;
    },
    {
        &quot;gir_id&quot;: 3030,
        &quot;gir_descripcion&quot;: &quot;CENTRO DE ATENCION PARA EL ADULTO MAYOR U OTRO CON CARACTERISTICAS Y SERVICIOS SIMILARES&quot;
    },
    {
        &quot;gir_id&quot;: 3031,
        &quot;gir_descripcion&quot;: &quot;SERVICIOS VETERINARIOS CON VENTA DE PRODUCTOS VETERINARIOS&quot;
    },
    {
        &quot;gir_id&quot;: 3032,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTE - POLLOS A LA BRASA PARRILLADAS - VENTA DE LICOR COMO COMPLEMENTO&quot;
    },
    {
        &quot;gir_id&quot;: 3033,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS ENLATADOS Y ENVASADOS - SUPLEMENTOS VITAMINICOS&quot;
    },
    {
        &quot;gir_id&quot;: 3034,
        &quot;gir_descripcion&quot;: &quot;SALA DE CONVENCIONES&quot;
    },
    {
        &quot;gir_id&quot;: 3035,
        &quot;gir_descripcion&quot;: &quot;REPARACION DE BICICLETAS COMPLEMENTARIO A LA VENTA&quot;
    },
    {
        &quot;gir_id&quot;: 3036,
        &quot;gir_descripcion&quot;: &quot;CABINAS DE INTERNET (LOCUTORIO E INTERNET)&quot;
    },
    {
        &quot;gir_id&quot;: 3037,
        &quot;gir_descripcion&quot;: &quot;PLAYA DE ESTACIONAMIENTO (12 VEHICULOS)&quot;
    },
    {
        &quot;gir_id&quot;: 3038,
        &quot;gir_descripcion&quot;: &quot;PLANTA DE SERVICIOS PROFESIONALES (INSPECCION TECNICA VEHICULAR)&quot;
    },
    {
        &quot;gir_id&quot;: 3039,
        &quot;gir_descripcion&quot;: &quot;GRANDES ALMACENES&quot;
    },
    {
        &quot;gir_id&quot;: 3040,
        &quot;gir_descripcion&quot;: &quot;ARTICULOS DE VIDRIO&quot;
    },
    {
        &quot;gir_id&quot;: 1987,
        &quot;gir_descripcion&quot;: &quot;FARMACIAS Y BOTICAS&quot;
    },
    {
        &quot;gir_id&quot;: 3041,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE CUERO Y ACCESORIOS DE VIAJE&quot;
    },
    {
        &quot;gir_id&quot;: 3042,
        &quot;gir_descripcion&quot;: &quot;UNIVERSIDADES&quot;
    },
    {
        &quot;gir_id&quot;: 3043,
        &quot;gir_descripcion&quot;: &quot;SERV. DE MANTENIM. Y REP. DE EQUIPOS&quot;
    },
    {
        &quot;gir_id&quot;: 3044,
        &quot;gir_descripcion&quot;: &quot;AGENCIA DE VIAJE Y AGENCIA DE TURISMO&quot;
    },
    {
        &quot;gir_id&quot;: 3045,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRIVADO DE EDUCACION INICIAL Y EDUCACION PRIMARIA&quot;
    },
    {
        &quot;gir_id&quot;: 3046,
        &quot;gir_descripcion&quot;: &quot;CENTRO EDUCATIVO PRIVADO DE EDUCACION INICIAL, PRIMARIA Y SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 3047,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEHICULOS AUTOMOTORES DE PASAJEROS (PARA USO PRIVADO)&quot;
    },
    {
        &quot;gir_id&quot;: 3048,
        &quot;gir_descripcion&quot;: &quot;SALA DE CONVENCIONES Y AUDITORIO&quot;
    },
    {
        &quot;gir_id&quot;: 3049,
        &quot;gir_descripcion&quot;: &quot;RELOJERIA Y JOYERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3050,
        &quot;gir_descripcion&quot;: &quot;PRODUCTOS MULTIMEDIA&quot;
    },
    {
        &quot;gir_id&quot;: 2402,
        &quot;gir_descripcion&quot;: &quot;PERFUMERIA Y DROGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3051,
        &quot;gir_descripcion&quot;: &quot;ACADEMIA DE MUSICA&quot;
    },
    {
        &quot;gir_id&quot;: 3052,
        &quot;gir_descripcion&quot;: &quot;VENTA DE SANITARIOS Y MAYOLICAS&quot;
    },
    {
        &quot;gir_id&quot;: 3053,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA Y APARATOS ELECTRODOMESTICOS&quot;
    },
    {
        &quot;gir_id&quot;: 3054,
        &quot;gir_descripcion&quot;: &quot;OCULISTA&quot;
    },
    {
        &quot;gir_id&quot;: 3055,
        &quot;gir_descripcion&quot;: &quot;ASOCIACION CULTURAL&quot;
    },
    {
        &quot;gir_id&quot;: 3056,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DEPORTIVOS Y REGALOS&quot;
    },
    {
        &quot;gir_id&quot;: 3057,
        &quot;gir_descripcion&quot;: &quot;CLINICA&quot;
    },
    {
        &quot;gir_id&quot;: 3058,
        &quot;gir_descripcion&quot;: &quot;PLAYA DE ESTACIONAMIENTO O GARAJE&quot;
    },
    {
        &quot;gir_id&quot;: 3059,
        &quot;gir_descripcion&quot;: &quot;VENTA Y EXHIBICI&Oacute;N DE VEH&Iacute;CULOS NUEVOS Y LIMPIEZA COSM&Eacute;TICA DE VEH&Iacute;CULOS&quot;
    },
    {
        &quot;gir_id&quot;: 3060,
        &quot;gir_descripcion&quot;: &quot;PANADERIA Y PASTELERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3061,
        &quot;gir_descripcion&quot;: &quot;CONTRATISTAS GENERALES (OFICINA)&quot;
    },
    {
        &quot;gir_id&quot;: 3062,
        &quot;gir_descripcion&quot;: &quot;ACTIVIDADES DE FOTOGRAFIA PUBLICITARIA&quot;
    },
    {
        &quot;gir_id&quot;: 3063,
        &quot;gir_descripcion&quot;: &quot;LAVANDERIA (SOLO RECOJO Y DISTRIBUCI&Oacute;N)&quot;
    },
    {
        &quot;gir_id&quot;: 3064,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE FERRETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3065,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE FERRETERIA (SIN VENTA DE MATERIALES DE CONSTRUCCION)&quot;
    },
    {
        &quot;gir_id&quot;: 3066,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEHICULOS, REPUESTOS ELECTRICOS, SERVICIO AUTOMOTRIZ (ELECTRICO MECANICA LIGERA), SIN PLANCHADO Y SIN PINTURA&quot;
    },
    {
        &quot;gir_id&quot;: 3067,
        &quot;gir_descripcion&quot;: &quot;PANADER&Iacute;A, PASTELER&Iacute;A, BODEGA, CAFETER&Iacute;A&quot;
    },
    {
        &quot;gir_id&quot;: 3068,
        &quot;gir_descripcion&quot;: &quot;EXHIBICION DE FILMS Y VIDEO CINTAS EN CINEMATOGRAFOS (CINES), CAFETERIA, FUENTE DE SODA&quot;
    },
    {
        &quot;gir_id&quot;: 3069,
        &quot;gir_descripcion&quot;: &quot;EXHIBICION DE FILMS Y VIDEO CINTAS EN CINEMATOGRAFOS (CINES)&quot;
    },
    {
        &quot;gir_id&quot;: 3070,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA , ZAPATERIAS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 3071,
        &quot;gir_descripcion&quot;: &quot;BODEGAS&quot;
    },
    {
        &quot;gir_id&quot;: 3072,
        &quot;gir_descripcion&quot;: &quot;JUEGOS ELECTR&Oacute;NICOS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 3073,
        &quot;gir_descripcion&quot;: &quot;JOYERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3074,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTES (6)&quot;
    },
    {
        &quot;gir_id&quot;: 3075,
        &quot;gir_descripcion&quot;: &quot;AGENCIAS DE VIAJE (OFICINA ADMINISTRATIVA)&quot;
    },
    {
        &quot;gir_id&quot;: 3076,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA Y PRODUCTOS DE BELLEZA, VENTA DE JUGUETES, BIJOUTERIA, VENTA DE PRODUCTOS DIVERSOS PARA EL HOGAR&quot;
    },
    {
        &quot;gir_id&quot;: 3077,
        &quot;gir_descripcion&quot;: &quot;ZAPATERIAS Y ACCESORIOS, VENTA DE ART&Iacute;CULOS DE CUERO Y ACCESORIOS DE VIAJE&quot;
    },
    {
        &quot;gir_id&quot;: 3078,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEHICULOS AUTOMOTORES DE PASAJEROS (PARA USO PRIVADO) (10), TALLER DE MECANICA (6) (3)&quot;
    },
    {
        &quot;gir_id&quot;: 3079,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIOS DE MEDICOS VETERINARIOS (9)&quot;
    },
    {
        &quot;gir_id&quot;: 3080,
        &quot;gir_descripcion&quot;: &quot;HELADERIA - CAFETERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3081,
        &quot;gir_descripcion&quot;: &quot;MINIMARKET (6)&quot;
    },
    {
        &quot;gir_id&quot;: 3082,
        &quot;gir_descripcion&quot;: &quot;ESTUDIO JURIDICO&quot;
    },
    {
        &quot;gir_id&quot;: 3083,
        &quot;gir_descripcion&quot;: &quot;ZAPATERIAS Y ACCESORIOS&quot;
    },
    {
        &quot;gir_id&quot;: 3084,
        &quot;gir_descripcion&quot;: &quot;VENTA AL POR MENOR DE ART&Iacute;CULOS DE PERFUMER&Iacute;A Y COSM&Eacute;TICOS&quot;
    },
    {
        &quot;gir_id&quot;: 3085,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIOS DE MEDICINA GENERAL (9)&quot;
    },
    {
        &quot;gir_id&quot;: 3086,
        &quot;gir_descripcion&quot;: &quot;BASARES, LIBRERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 3087,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DEPORTIVOS / VENTA DE ROPA&quot;
    },
    {
        &quot;gir_id&quot;: 3088,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRENDAS DE VESTIR PARA CABALLEROS&quot;
    },
    {
        &quot;gir_id&quot;: 3089,
        &quot;gir_descripcion&quot;: &quot;SALON DE BELLEZA, PELUQUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3090,
        &quot;gir_descripcion&quot;: &quot;FUENTES DE SODA&quot;
    },
    {
        &quot;gir_id&quot;: 3091,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PLANTAS Y FLORES (4) (SOLO FLORERIA)&quot;
    },
    {
        &quot;gir_id&quot;: 3092,
        &quot;gir_descripcion&quot;: &quot;COLCHONERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3093,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIOS DE MEDICOS VETERINARIOS / VENTA DE  PRODUCTOS VETERINARIOS / VENTA DE ACCESORIOS PARA MASCOTAS&quot;
    },
    {
        &quot;gir_id&quot;: 2611,
        &quot;gir_descripcion&quot;: &quot;COMIDAS RAPIDAS&quot;
    },
    {
        &quot;gir_id&quot;: 1114,
        &quot;gir_descripcion&quot;: &quot;BODEGA Y BAZAR&quot;
    },
    {
        &quot;gir_id&quot;: 2352,
        &quot;gir_descripcion&quot;: &quot;CASINOS DE JUEGOS (TRAGAMONEDAS Y AFINES)&quot;
    },
    {
        &quot;gir_id&quot;: 2739,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE MASAJES FACIALES EN MANOS Y PIES&quot;
    },
    {
        &quot;gir_id&quot;: 1707,
        &quot;gir_descripcion&quot;: &quot;EDUCACION INICIAL, PRIMARIA, SECUNDARIA&quot;
    },
    {
        &quot;gir_id&quot;: 3094,
        &quot;gir_descripcion&quot;: &quot;CAFETERIA, HELADERIA, SANGUCHERIA, JUGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3095,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS PARA EL HOGAR, BAZAR Y REGALOS, VENTA DE  JUGUETES&quot;
    },
    {
        &quot;gir_id&quot;: 3096,
        &quot;gir_descripcion&quot;: &quot;RESTAURANTES&quot;
    },
    {
        &quot;gir_id&quot;: 3097,
        &quot;gir_descripcion&quot;: &quot;SERVICIO DE PREPARACI&Oacute;N Y DISTRIBUCI&Oacute;N DE ALIMENTOS A DOMICILIO&quot;
    },
    {
        &quot;gir_id&quot;: 3098,
        &quot;gir_descripcion&quot;: &quot;ESTUDIOS JURIDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 3099,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIOS MEDICOS&quot;
    },
    {
        &quot;gir_id&quot;: 3105,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PRODUCTOS REALIZADO POR MAQUINAS EXPENDEDORAS&quot;
    },
    {
        &quot;gir_id&quot;: 2223,
        &quot;gir_descripcion&quot;: &quot;CONSULTORIOS ODONTOLOGICOS (9)&quot;
    },
    {
        &quot;gir_id&quot;: 1451,
        &quot;gir_descripcion&quot;: &quot;LAVANDERIAS (6)&quot;
    },
    {
        &quot;gir_id&quot;: 3109,
        &quot;gir_descripcion&quot;: &quot;VENTA DE VEHICULOS USADOS FINOS Y VENTA DE VEHICULOS AUTOMOTORES ESPECIALES&quot;
    },
    {
        &quot;gir_id&quot;: 3110,
        &quot;gir_descripcion&quot;: &quot;CASA NATURISTA (PRODUCTOS ORGANICOS)&quot;
    },
    {
        &quot;gir_id&quot;: 2607,
        &quot;gir_descripcion&quot;: &quot;CAFETERIA, SANDWICHERIA, HELADERIA, JUGUERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3111,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA,  ELECTRODOMESTICOS, ART. PARA EL HOGAR, ETC - GRANDES  ALMACENES&quot;
    },
    {
        &quot;gir_id&quot;: 3112,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ROPA ZAPATERIA&quot;
    },
    {
        &quot;gir_id&quot;: 3113,
        &quot;gir_descripcion&quot;: &quot;VENTA DE PAN Y PRODUCTOS DE PANADERIA (1)&quot;
    },
    {
        &quot;gir_id&quot;: 3115,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ALIMENTOS ENLATADOS&quot;
    },
    {
        &quot;gir_id&quot;: 3116,
        &quot;gir_descripcion&quot;: &quot;BARBERIAS&quot;
    },
    {
        &quot;gir_id&quot;: 3117,
        &quot;gir_descripcion&quot;: &quot;VENTA DE ARTICULOS DE FERRETERIA Y MUEBLES&quot;
    },
    {
        &quot;gir_id&quot;: 3118,
        &quot;gir_descripcion&quot;: &quot;VENTA DE JUGUETES, BIJOUTERIA Y ARTICULOS DE VIDRIO&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-giros-listar" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-giros-listar"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-giros-listar"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-giros-listar" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-giros-listar">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-giros-listar" data-method="GET"
      data-path="api/v1/giros/listar"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-giros-listar', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-giros-listar"
                    onclick="tryItOut('GETapi-v1-giros-listar');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-giros-listar"
                    onclick="cancelTryOut('GETapi-v1-giros-listar');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-giros-listar"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/giros/listar</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-giros-listar"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-giros-listar"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
