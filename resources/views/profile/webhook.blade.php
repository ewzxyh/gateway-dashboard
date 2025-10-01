<x-app-layout :route="'Webhook'">


  <div class="main-content app-content">
    <div class="container-fluid">
      <div class="flex-wrap gap-2 my-4 d-flex align-items-center justify-content-between page-header-breadcrumb">
        <div class="mb-2 md-mb-0 col col-12 col-lg-8 text-start">
          <h1 class="display-5">Webhooks</h1>
        </div>
      </div>

      <div class="mb-3 row">
        <div class="col-12">
          <div class="adobe-glass-card">
            <div class="adobe-card-body">
              <form method="POST" action="{{ route('webhook.update') }}">
                @csrf
                <div class="row align-items-end">
                  <div class="col-md-5">
                    <div class="mb-3 form-group">
                      <label for="webhook_url" class="form-label">Webhook URL</label>
                      <input type="text" id="webhook_url" name="webhook_url" value="{{ auth()->user()->webhook_url }}" class="form-control input-shadow" placeholder="Digite a URL do webhook" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="mb-3 form-group">
                        <small class="text-sm">Eventos</small>
                      <div class="custom-select" id="multiSelect">
                        <div class="select-display">Selecione os eventos</div>
                        <div class="select-options">
                          <label>
                            <input type="checkbox" value="gerado"> Pix Gerado
                          </label>
                          <label>
                            <input type="checkbox" value="pago"> Pix Pago
                          </label>
                        </div>
                      </div>
                      <input type="hidden" name="webhook_endpoint" id="webhook_endpoint" value="">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="mb-3 form-group">
                      <label class="form-label d-block">&nbsp;</label>
                      <button type="submit" class="webhook-button w-100 btn-primary">Salvar Webhook</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>

  {{-- Passa os eventos selecionados para o JavaScript --}}
  <script>
    const initialSelected = @json(auth()->user()->webhook_endpoint ?? []);
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const select = document.getElementById('multiSelect');
      const display = select.querySelector('.select-display');
      const options = select.querySelectorAll('.select-options input[type="checkbox"]');
      const hiddenInput = document.getElementById('webhook_endpoint');

      function updateDisplayAndInput() {
        const selected = Array.from(options)
          .filter(opt => opt.checked)
          .map(opt => opt.value);

        display.innerHTML = selected.length
          ? selected.map(val => `<span class="tags">${val}</span>`).join('')
          : 'Selecione os eventos';

        hiddenInput.value = JSON.stringify(selected);
      }

      // Inicializar estado baseado no backend (Laravel)
      options.forEach(opt => {
        if (initialSelected.includes(opt.value)) {
          opt.checked = true;
        }
      });

      updateDisplayAndInput(); // Exibe visual inicial e atualiza hidden input

      // Toggle dropdown
      display.addEventListener('click', () => {
        select.classList.toggle('open');
      });

      // Atualiza estado ao marcar/desmarcar
      options.forEach(option => {
        option.addEventListener('change', updateDisplayAndInput);
      });

      // Fecha se clicar fora
      document.addEventListener('click', (e) => {
        if (!select.contains(e.target)) {
          select.classList.remove('open');
        }
      });
    });
  </script>
</x-app-layout>
