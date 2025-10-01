<x-app-layout :route="'[ADMIN] Dashboard'">

    <div class="main-content app-content">
        <div class="container-fluid">
            
            <!-- Enhanced Header Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body p-4 text-center">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <div class="icon-circle me-3" style="background: linear-gradient(135deg, rgba(220, 53, 69, 0.3), rgba(220, 53, 69, 0.1)); width: 70px; height: 70px; border: 1px solid rgba(220, 53, 69, 0.4);">
                                    <i class="fas fa-shield-alt" style="color: #dc3545; font-size: 1.8rem;"></i>
                                </div>
                                <div class="text-start">
                                    <h1 class="adobe-title mb-2">Dashboard Administrativo</h1>
                                    <p class="adobe-text-muted mb-0">Painel de controle e monitoramento do sistema</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3 row justify-content-between align-items-center">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <!-- Header moved to decorative card above -->
                </div>
                <form class="mt-3 col-12 col-md-8" method="GET" action="{{ route('admin.dashboard') }}" id="filtroForm">
                    <div class="gap-3 d-flex flex-column flex-md-row">
                        <div class="mb-3 form-outlined-select position-relative w-100 w-md-50 mb-md-0">
                            <select id="periodoSelect" class="form-select" name="periodo" onchange="submitPeriod()" required>
                                <option value="hoje" {{ request('periodo') == 'hoje' ? 'selected' : '' }}>Hoje</option>
                                <option value="ontem" {{ request('periodo') == 'ontem' ? 'selected' : '' }}>Ontem</option>
                                <option value="7dias" {{ request('periodo') == '7dias' ? 'selected' : '' }}>Último 7 dias</option>
                                <option value="30dias" {{ request('periodo') == '30dias' ? 'selected' : '' }}>Último 30 dias</option>
                                <option value="tudo" {{ request('periodo') == 'tudo' ? 'selected' : '' }}>Sempre</option>
                                <option value="personalizado">Personalizado</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">R$ {{ number_format($carteiras, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Saldo em carteiras</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="material-icons">account_balance_wallet</i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">R$ {{ number_format($lucro_liquido ?? 0, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Lucro Liquido</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-dollar-sign"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">{{ (clone $solicitacoes)->where('status', 'PAID_OUT')->count() + (clone $saques)->where('status', 'COMPLETED')->count() }}</div>
                                    <div class="adobe-text-muted">Transações aprovadas</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">{{ "R$ ".number_format($valor_aprovado ?? 0, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Transações aprovadas</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">{{ $cadastros_total }}</div>
                                    <div class="adobe-text-muted">Usuários cadastrados</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-people-group"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-warning adobe-text fw-bold">{{ $cadastros_analise }}</div>
                                    <div class="adobe-text-muted">Usuários em análise</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">R$ {{ number_format((clone $saques)->sum('amount') ?? 0, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Saques</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-arrow-up-short-wide"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">R$ {{ number_format($saques_pendentes->sum('amount') ?? 0, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Saques pendentes</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-arrow-up-short-wide"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->

        </div>
    </div>

     <!-- Modal -->
<div class="modal fade" id="dateRangeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="border-0 modal-content rounded-4">
        <div class="justify-center p-4 pl-5 modal-body align-center">
          <h5 class="mb-4 fw-semibold">Selecione o período</h5>

          <div class="row">
            <div class="mb-3 text-center col-md-6">
              <strong class="mb-2 d-block">Data de Início</strong>
              <div class="d-flex justify-content-center" id="calendarInicio"></div>
            </div>
            <div class="text-center col-md-6">
              <strong class="mb-2 d-block">Data de Fim</strong>
              <div class="d-flex justify-content-center" id="calendarFim"></div>
            </div>
          </div>
        </div>
        <div class="gap-2 mt-4 modal-footer d-flex justify-content-end">
            <button class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button>
            <button class="btn btn-success" id="btnAplicarDatas">Aplicar</button>
        </div>
      </div>
    </div>
  </div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
   const select = document.getElementById("periodoSelect");
   const form = document.getElementById("filtroForm");
   const modalEl = document.getElementById('dateRangeModal');
   const btnAplicar = document.getElementById("btnAplicarDatas");

   let dataInicioSelecionada = null;
   let dataFimSelecionada = null;

   function formatarDataBr(data) {
     const meses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
     const dia = String(data.getDate()).padStart(2, '0');
     const mes = meses[data.getMonth()];
     return `${dia} ${mes}`;
   }

   // Flatpickrs
   flatpickr("#calendarInicio", {
     inline: true,
     locale: "pt",
     dateFormat: "Y-m-d",
     onChange: function (selectedDates) {
       dataInicioSelecionada = selectedDates[0];
     }
   });

   flatpickr("#calendarFim", {
     inline: true,
     locale: "pt",
     dateFormat: "Y-m-d",
     onChange: function (selectedDates) {
       dataFimSelecionada = selectedDates[0];
     }
   });

   // Abrir modal
   select.addEventListener("change", function () {
     if (select.value === "personalizado") {
       new bootstrap.Modal(modalEl).show();
     } else {
       form.submit();
     }
   });

   // Aplicar datas
   btnAplicar.addEventListener("click", function () {
     if (dataInicioSelecionada && dataFimSelecionada) {
       const inicioStr = dataInicioSelecionada.toISOString().split("T")[0];
       const fimStr = dataFimSelecionada.toISOString().split("T")[0];
       const texto = `${formatarDataBr(dataInicioSelecionada)} – ${formatarDataBr(dataFimSelecionada)}`;
       const valor = `${inicioStr}:${fimStr}`;

       // Remover opção personalizada anterior, se existir
       let opExistente = select.querySelector('option[data-personalizado]');
       if (opExistente) select.removeChild(opExistente);

       // Criar nova opção personalizada
       let op = document.createElement("option");
       op.value = valor;
       op.textContent = texto;
       op.setAttribute("data-personalizado", "1");
       select.appendChild(op);
       select.value = valor; // Define como selecionado

       // Fechar modal e submeter
       bootstrap.Modal.getInstance(modalEl).hide();
       form.submit();
     } else {
       alert("Selecione ambas as datas.");
     }
   });

   // Restaurar valor da URL, se existir
   const urlParams = new URLSearchParams(window.location.search);
   const periodo = urlParams.get('periodo');

   if (periodo && select) {
     if (periodo.includes(':')) {
       const [inicioStr, fimStr] = periodo.split(':');
       const inicioDate = new Date(inicioStr);
       const fimDate = new Date(fimStr);
       dataInicioSelecionada = inicioDate;
       dataFimSelecionada = fimDate;

       const texto = `${formatarDataBr(inicioDate)} – ${formatarDataBr(fimDate)}`;
       let op = document.createElement("option");
       op.value = periodo;
       op.textContent = texto;
       op.setAttribute("data-personalizado", "1");

       select.appendChild(op);
       select.value = periodo; // Seleciona corretamente
     } else {
       const optionToSelect = Array.from(select.options).find(opt => opt.value === periodo);
       if (optionToSelect) {
         optionToSelect.selected = true;
       }
     }
   }
 });

   </script>


</x-app-layout>
