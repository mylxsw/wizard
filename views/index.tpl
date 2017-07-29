{{ template "layout/header.tpl" }}
    <div class="container container-small">
      {{ template "layout/navbar.tpl" }}

      <div class="row marketing">
        <div class="col-lg-12">
            {{ range .projects }}
            <div class="col-lg-3">
                <a class="wz-box" href="/{{ .ID }}" title="{{ .Description }}">
                    <p class="wz-title">{{ .Title }}</p>
                </a>
            </div>
            {{ end }}
        </div>
      </div>
      {{ template "layout/copyright.tpl" }}

    </div>
{{ template "layout/footer.tpl" }}
