<x-app-layout :route="'Token API PIX'">

  <div class="main-content app-content">
    <div class="container-fluid">
      <div class="row">

        <div class="mb-3 md-mb-3 row justify-content-between align-items-">
            <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                <h1 class="mb-0 display-5 adobe-title">Chaves API</h1>
            </div>
        </div>

        <div class="col-lg-8 col-md-12 mb-4">
          <div class="adobe-glass-card h-100">
            <div class="adobe-card-body">
              <h4 class="mb-4 d-block adobe-subtitle">Recursos do Gateway {{ env('APP_NAME') }}:</h4>
              <p class="mb-3 adobe-text-muted">
                Nossa API foi projetada com tecnologia de última geração para garantir alto desempenho, segurança robusta e escalabilidade real. Com uma arquitetura moderna e otimizada, possibilita o processamento de transações de forma rápida e confiável, assegurando a melhor experiência tanto para lojistas quanto para clientes finais.
              </p>
              <p class="mb-3 adobe-text-muted">
                Disponibilizamos um painel de controle completo e personalizável, que oferece análises detalhadas de vendas e ferramentas avançadas para gestão financeira, facilitando a tomada de decisões estratégicas.
            </p>
            <p class="mb-3 adobe-text-muted">
                A integração com as principais plataformas de e-commerce é simples e direta, proporcionando uma jornada fluida e sem barreiras. Além disso, a conexão nativa com as adquirentes otimiza o fluxo de pagamentos, reduzindo etapas intermediárias e aumentando a eficiência operacional.
            </p>
            <p class="mb-0 adobe-text-muted">
                Seja para expandir sua operação ou modernizar sua infraestrutura de pagamentos, nossa API representa a solução definitiva para quem busca inovação, segurança e controle total.
            </p>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-12 mb-4">
          <div class="adobe-glass-card" style="height: 100%;">
            <div class="adobe-card-body" style="height: 100%;">
              <h4 class="d-block adobe-subtitle mb-4">Integração com o Gateway</h4>
              
              <div class="api-key-section">
                <div class="mb-3">
                  <p class="mb-2 adobe-text font-weight-bold">
                    <button id="btn-show-key-token" class="api-button btn-sm me-2" onclick="mostrarToken()"><i class="fa-solid fa-eye"></i></button>
                    <button class="api-button btn-sm me-2" onclick="copiarToken()"><i class="fa-solid fa-copy"></i></button>
                    Token: <span id="token" class="adobe-text">***********************</span>
                  </p>
                </div>
                
                <div class="mb-3">
                  <p class="mb-2 adobe-text font-weight-bold">
                    <button id="btn-show-key-secret" class="api-button btn-sm me-2" onclick="mostrarSecret()"><i class="fa-solid fa-eye"></i></button>
                    <button class="api-button btn-sm me-2" onclick="copiarSecret()"><i class="fa-solid fa-copy"></i></button>
                    Secret: <span id="secret" class="adobe-text">***********************</span>
                  </p>
                </div>
              </div>
              
              <input id="chave-secret" value="{{ $secret }}" style="display: none;" />
              <input id="chave-token" value="{{ $token }}" style="display: none;" />

              <div class="api-key-section">
                <p class="form-label adobe-text mb-2">API Endpoint</p>
                <div class="input-group">
                  <input type="text" id="endpoint" name="endpoint" value="{{ env('APP_URL').'/api/' }}" class="form-control" readonly>
                  <button class="api-button" type="button" onclick="copyToClipboard()"><i class="fa-solid fa-copy"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function copyToClipboard() {
      var copyText = document.getElementById("endpoint");
      copyText.select();
      copyText.setSelectionRange(0, 99999); // Para compatibilidade com dispositivos móveis
      document.execCommand("copy");

      // Exibir um alerta ou feedback ao usuário
      showToast('success', 'Endpoint copiado com sucesso.')
    }
  </script>
  <script>
    function copiarSecret() {
      var input = document.getElementById("chave-secret");

      // Garante que o valor do input será copiado
      navigator.clipboard.writeText(input.value)
        .then(() => {
          showToast('success', "Chave 'Secret' copiada!")
          //alert("Chave Pix copiada!");
        })
        .catch(err => {
          console.error("Erro ao copiar", err);
        });
    }

    function copiarToken() {
      var input = document.getElementById("chave-token");

      // Garante que o valor do input será copiado
      navigator.clipboard.writeText(input.value)
        .then(() => {
          showToast('success', "Chave 'Token' copiada!")
          //alert("Chave Pix copiada!");
        })
        .catch(err => {
          console.error("Erro ao copiar", err);
        });
    }
  </script>


  <script>
    function mostrarToken() {
      var token = document.getElementById("token");
      var btnCode = document.getElementById('btn-show-key-token');

      if (token.innerText === "***********************") {
        token.innerText = '{{ $token }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
      } else {
        token.innerText = '***********************';
        btnCode.innerHTML = ` <i class="fa-solid fa-eye"></i>`;
      }
    }

    function mostrarSecret() {
      var token = document.getElementById("secret");
      var btnCode = document.getElementById('btn-show-key-secret');

      if (token.innerText === "***********************") {
        token.innerText = '{{ $secret }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
      } else {
        token.innerText = '***********************';
        btnCode.innerHTML = ` <i class="fa-solid fa-eye"></i>`;
      }
    }

   /*  function mostrarCodigo() {
      var token = document.getElementById("token");
      var secret = REDACTED_SECRET("secret");
      var btnCode = document.getElementById('btn-show-key');

      if (token.innerText === "***********************") {
        token.innerText = '{{ $token }}';
        secret.innerText = '{{ $secret }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i> Ocultar Chaves`;
      } else {
        token.innerText = '***********************';
        secret.innerText = '**********************';
        btnCode.innerHTML = ` <i class="fa-solid fa-eye"></i> Mostrar Chaves`;
      }
    } */
  </script>
</x-app-layout>
