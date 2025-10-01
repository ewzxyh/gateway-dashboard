<x-app-layout :route="'Documentação API PIX'">
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.css" rel="stylesheet" />



    <div class="main-content app-content">
      <div class="px-3 container-fluid px-md-5">
        <div class="mb-5">
          <h1 class="display-5 adobe-title"><i class="icon-doc fa-solid fa-file"></i>&nbsp;Documentação da API PIX</h1>
          <p class="adobe-text-muted">Endpoints para Depósito e Saque via PIX com Webhooks.</p>
        </div>
        </div>

        <!-- PIX IN -->
        <div class="adobe-glass-card">
          <div class="adobe-card-body">
            <div class="adobe-title"><i class="icon-doc fa-solid fa-download"></i> Depósito (PIX IN) <span class="method">POST</span></div>
            <p class="adobe-text-muted">Gera um pagamento via QrCode para depósito.</p>
            <p class="adobe-text"><strong>Endpoint:</strong> <code class="endpoint-url">{{ env('APP_URL') }}/api/wallet/deposit/payment</code></p>

            <div class="endpoint-section">
              <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-lock"></i>&nbsp; Cabeçalhos (Headers)</h4>
            <pre class="line-numbers"><code class="language-json">{
    "Content-Type": "application/json",
    "Accept": "application/json"
  }</code></pre>
          </div>

            <div class="endpoint-section">
              <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-upload"></i>&nbsp; Corpo da Requisição</h4>
            <pre class="line-numbers"><code class="language-json">{
    "token": "seu_token",
    "secret": "seu_secret",
    "postback": "rota_callback",
    "amount": 100.00,
    "debtor_name": "Nome",
    "email": "redacted@example.invalid",
    "debtor_document_number": "CPF",
    "phone": "Telefone",
    "method_pay": "pix",
    "split_email": "redacted@example.invalid",
    "split_percentage": 10.00
  }</code></pre>
          </div>

            <div class="endpoint-section">
              <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-share-nodes"></i>&nbsp; Parâmetros de Split (Opcionais)</h4>
              <div class="adobe-text-muted mb-3">
                <p><strong>split_email:</strong> Email do destinatário que receberá o split do pagamento</p>
                <p><strong>split_percentage:</strong> Porcentagem do valor total que será dividida (ex: 10.00 = 10%)</p>
                <p><strong>Exemplo:</strong> Se o depósito for de R$ 100,00 com split_percentage de 10.00, o destinatário receberá R$ 10,00</p>
              </div>
            </div>

            <div class="endpoint-section">
              <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-download"></i>&nbsp; Resposta</h4>
            <pre class="line-numbers"><code class="language-json">{
    "idTransaction": "TX123",
    "qrcode": "código copia e cola",
    "qr_code_image_url": "url da imagem"
  }</code></pre>
            </div>
          </div>
        </div>

        <!-- Webhook PIX IN -->
        <div class="adobe-glass-card">
          <div class="adobe-card-body">
            <div class="adobe-title"><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Webhook PIX IN</div>
            <p class="adobe-text-muted">Retorno automático na rota <code>postback</code> informada na criação do depósito.</p>

            <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Exemplo de retorno</h4>
          <pre class="line-numbers"><code class="language-json">{
    "idTransaction": "TX123",
    "status": "paid",
    "typeTransaction": "PIX",
    "amount": 100.00,
    "debtor_name": "Nome",
    "email": "redacted@example.invalid",
    "debtor_document_number": "12345678901",
    "phone": "11999999999",
    "created_at": "2025-09-10T17:00:00.000Z",
    "paid_at": "2025-09-10T17:05:00.000Z",
    "split_processed": true,
    "split_amount": 10.00,
    "split_recipient": "redacted@example.invalid"
  }</code></pre>
        </div>

        <!-- PIX OUT -->
        <div class="adobe-glass-card">
          <div class="adobe-card-body">
            <div class="adobe-title"><i class="icon-doc fa-solid fa-upload"></i>&nbsp; Saque (PIX OUT) <span class="method">POST</span></div>
            <p class="adobe-text-muted">Realiza um saque para uma chave PIX.</p>
            <p class="adobe-text"><strong>Endpoint:</strong> <code class="endpoint-url">{{ env('APP_URL') }}/api/pixout</code></p>

            <div class="endpoint-section">
              <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-lock"></i>&nbsp; Cabeçalhos (Headers)</h4>
              <pre class="line-numbers"><code class="language-json">{
    "Content-Type": "application/json",
    "Accept": "application/json"
  }</code></pre>
            </div>

            <div class="endpoint-section">
              <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-upload"></i>&nbsp; Corpo da Requisição</h4>
              <p class="adobe-text"><strong>pixKeyType:</strong> 'cpf' | 'cnpj' | 'email' | 'phone' | 'random'</p>
              <pre class="line-numbers"><code class="language-json">{
    "token": "seu_token",
    "secret": "seu_secret",
    "baasPostbackUrl": "url_callback",
    "amount": 100.00,
    "pixKey": "chave_pix",
    "pixKeyType": "cpf"
  }</code></pre>
            </div>

            <div class="endpoint-section">
              <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-download"></i>&nbsp; Resposta</h4>
              <pre class="line-numbers"><code class="language-json">{
    "id": "b522a295-e404...",
    "amount": 100,
    "pixKey": "chave",
    "pixKeyType": "cpf",
    "withdrawStatusId": "PendingProcessing",
    "createdAt": "2025-04-19T20:04:53.166Z",
    "updatedAt": "2025-04-19T20:04:53.166Z"
  }</code></pre>
            </div>
          </div>
        </div>

        <!-- Sistema de Splits -->
        <div class="adobe-glass-card">
          <div class="adobe-card-body">
            <div class="adobe-title"><i class="icon-doc fa-solid fa-share-nodes"></i>&nbsp; Sistema de Splits</div>
            <p class="adobe-text-muted">Divida automaticamente os pagamentos entre múltiplos destinatários.</p>

            <div class="endpoint-section">
              <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-info-circle"></i>&nbsp; Como Funciona</h4>
              <div class="adobe-text-muted">
                <p>1. <strong>Configuração:</strong> Adicione <code>split_email</code> e <code>split_percentage</code> na requisição de depósito</p>
                <p>2. <strong>Processamento:</strong> Quando o pagamento for confirmado, o sistema calcula automaticamente o valor do split</p>
                <p>3. <strong>Distribuição:</strong> O valor é creditado automaticamente para o destinatário do split</p>
                <p>4. <strong>Notificação:</strong> O webhook inclui informações sobre o split processado</p>
              </div>
            </div>

            <div class="endpoint-section">
              <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-calculator"></i>&nbsp; Exemplo de Cálculo</h4>
              <div class="adobe-text-muted">
                <p><strong>Depósito:</strong> R$ 100,00</p>
                <p><strong>Split:</strong> 10% para redacted@example.invalid</p>
                <p><strong>Resultado:</strong> Parceiro recebe R$ 10,00 automaticamente</p>
              </div>
            </div>

            <div class="endpoint-section">
              <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-cogs"></i>&nbsp; Tipos de Split Suportados</h4>
              <div class="adobe-text-muted">
                <p><strong>Percentage:</strong> Porcentagem do valor total (padrão)</p>
                <p><strong>Fixed:</strong> Valor fixo em reais</p>
                <p><strong>Partner:</strong> Para parceiros comerciais</p>
                <p><strong>Affiliate:</strong> Para afiliados</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Webhook PIX OUT -->
        <div class="adobe-glass-card">
          <div class="adobe-card-body">
            <div class="adobe-title"><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Webhook PIX OUT</div>
            <p class="adobe-text-muted">Retorno automático na rota <code>baasPostbackUrl</code> informada na requisição de saque.</p>

            <h4 class="adobe-subtitle"><i class="icon-doc fa-solid fa-bell"></i>&nbsp; Exemplo de retorno</h4>
            <pre class="line-numbers"><code class="language-json">{
    "status": "paid",
    "idTransaction": "TX123",
    "typeTransaction": "PAYMENT"
  }</code></pre>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.js"></script>
  </x-app-layout>
