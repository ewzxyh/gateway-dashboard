<x-app-layout :route="'Relatório de saídas'">

<div class="main-content app-content">
        <div class="container-fluid">
            <!-- Start::page-header -->
            <div class="mb-3 md-mb-0 row">
                <div class="mb-3 md-mb-0 col col-12 col-lg-8 text-start" >
                    <h1 class="display-5">Saídas</h1>
                </div>

                <div class="col col-12 col-lg-4 text-end">
                    <form method="GET" action="{{ route('profile.relatorio.pixsaida') }}" id="filtroForm">
                        <div class="row g-2">
                            <div class="col col-6">
                                <input type="search"
                                       class="form-control"
                                       id="buscar"
                                       name="buscar"
                                       placeholder="Buscar"
                                       value="{{ request('buscar') }}"
                                       autofocus>
                            </div>
                            <div class="col col-6">
                                    <select id="periodoSelect" class="bg-transparent form-select" name="periodo" onchange="submitPeriod()" required>
                                        <option value="hoje" {{ request('periodo') == 'hoje' ? 'selected' : '' }}>Hoje</option>
                                        <option value="ontem" {{ request('periodo') == 'ontem' ? 'selected' : '' }}>Ontem</option>
                                        <option value="7dias" {{ request('periodo') == '7dias' ? 'selected' : '' }}>Último 7 dias</option>
                                        <option value="30dias" {{ request('periodo') == '30dias' ? 'selected' : '' }}>Último 30 dias</option>
                                        <option value="tudo" {{ request('periodo') == 'tudo' ? 'selected' : '' }}>Sempre</option>
                                        <option value="personalizado">Personalizado</option>
                                        {{-- @if(isset(request('periodo')) && str_contains(':', request('periodo')))
                                            <option value="{{ request('periodo') }}">{{ explode(':', request('periodo'))[0] - explode(':', request('periodo'))[1] }}</option>
                                        @endif --}}
                                    </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Start:: row-1 -->
            <div class="row">

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 adobe-text fw-bold">{{ (clone $transactions)->count() }}</div>
                                    <div class="adobe-text-muted">Transações</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="fa-solid fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 adobe-text fw-bold">R$ {{ number_format((clone $transactions)->where('status', 'COMPLETED')->sum('amount'), '2',',','.') }}</div>
                                    <div class="adobe-text-muted">Saidas</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="fa-solid fa-arrow-up-short-wide"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 adobe-text fw-bold">R$ {{ number_format((clone $transactions)->where('status', 'CHARGEBACK')->sum('cash_out_liquido'), '2',',','.') }}</div>
                                    <div class="adobe-text-muted">Chargebacks</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 adobe-text fw-bold">R$ {{ number_format((clone $transactions)->where('status', 'MED')->sum('cash_out_liquido'), '2',',','.') }}</div>
                                    <div class="adobe-text-muted">MED</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="fa-solid fa-ban"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->



            <div class="row">
                <div class="col-xl-12">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="table-responsive">
                                <table id="table-pix-saidas" class="table text-nowrap ">
                                    <thead>
                                        <tr>
                                            @if($settings->relatorio_saidas_mostrar_transacao_id ?? true)
                                            <th scope="col">Transação ID</th>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_valor ?? true)
                                            <th scope="col">Valor</th>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_nome ?? true)
                                            <th scope="col">Nome</th>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_chave_pix ?? true)
                                            <th scope="col">Chave PIX</th>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_tipo_chave ?? true)
                                            <th scope="col">Tipo Chave</th>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_status ?? true)
                                            <th scope="col">Status</th>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_data ?? true)
                                            <th scope="col">Data</th>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_taxa ?? true)
                                            <th scope="col">Taxa</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                        <tr>
                                            @if($settings->relatorio_saidas_mostrar_transacao_id ?? true)
                                            <td>{{ $transaction->idTransaction }}</td>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_valor ?? true)
                                            <td>{{ "R$ ".number_format($transaction->amount, '2',',','.') }}</td>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_nome ?? true)
                                            <td>{{ $transaction->beneficiaryname }}</td>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_chave_pix ?? true)
                                            <td>{{ $transaction->pix }}</td>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_tipo_chave ?? true)
                                            <td>{{ $transaction->pixkey }}</td>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_status ?? true)
                                            <td>
                                                @switch($transaction->status)
                                                @case('COMPLETED')
                                                <span class="badge badge-sm bg-success gateway-badge-success">Aprovado</span>
                                                @break
                                                @case('PAID_OUT')
                                                <span class="badge badge-sm bg-success gateway-badge-success">Aprovado</span>
                                                @break
                                                @case('PENDING')
                                                <span class="badge badge-sm bg-warning gateway-badge-warning">Pendente</span>
                                                @break
                                                @case('CANCELLED')
                                                <span class="badge badge-sm bg-danger gateway-badge-danger">Cancelado</span>
                                                @break
                                                @case('REJECTED')
                                                <span class="badge badge-sm bg-danger gateway-badge-danger">Rejeitado</span>
                                                @break
                                                @default
                                                <span class="badge badge-sm bg-secondary">{{ $transaction->status }}</span>
                                                @endswitch
                                            </td>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_data ?? true)
                                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            @endif
                                            @if($settings->relatorio_saidas_mostrar_taxa ?? true)
                                            <td>
                                                R$ {{ number_format((float)$transaction->taxa_cash_out, '2', ',', '.') }}
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
   const buscar = document.getElementById("buscar");
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

   buscar.addEventListener('input', function () {
    setTimeout(() => {
        form.submit();
    }, 500);
   })

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $("#table-pix-saidas").DataTable({
            responsive: true,
            info:false,
            ordering: false,
            searching: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            dom: '<"top"f>rt<"bottom"p><"clear">',
                initComplete: function() {
                    // Muda o placeholder do input de busca
                    $('#table-produtos_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                }
        });
    });
</script>

</x-app-layout>
