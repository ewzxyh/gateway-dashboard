@php
use App\Helpers\Helper;
$setting = Helper::getSetting();
@endphp
<x-guest-layout :route="'Cadastrar-me'">
    {{-- Mantivemos apenas os estilos que são EXCLUSIVOS para o formulário de registro --}}
    <style>
        /* Container Principal */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Card Principal */
        .auth-card {
            background-color: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            border-radius: calc(var(--radius) * 2);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            width: 100%;
            max-width: 28rem;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Header */
        .auth-header { text-align: center; margin-bottom: 2rem; }
        .auth-logo { height: 3rem; margin-bottom: 1rem; }
        .auth-title { font-size: 1.5rem; font-weight: 600; color: hsl(var(--foreground)); margin-bottom: 0.5rem; }
        .auth-subtitle { font-size: 0.875rem; color: hsl(var(--muted-foreground)); }

        /* Form Groups */
        .form-group { margin-bottom: 1rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        /* Labels */
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: hsl(var(--foreground)); margin-bottom: 0.5rem; }

        /* Inputs */
        .form-input {
            width: 100%;
            height: 2.25rem;
            padding: 0 0.75rem;
            background-color: hsl(var(--background));
            border: 1px solid hsl(var(--input));
            border-radius: var(--radius);
            font-size: 0.875rem;
            color: hsl(var(--foreground));
            transition: all 0.2s ease;
        }
        .form-input:focus { outline: none; border-color: hsl(var(--ring)); box-shadow: 0 0 0 2px hsl(var(--ring) / 0.2); }
        .form-input::placeholder { color: hsl(var(--muted-foreground)); }

        /* Password Input Container */
        .password-container { position: relative; }
        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: hsl(var(--muted-foreground));
            cursor: pointer;
            padding: 0.25rem;
            border-radius: calc(var(--radius) / 2);
            transition: all 0.2s ease;
        }
        .password-toggle:hover { color: hsl(var(--foreground)); background-color: hsl(var(--accent)); }

        /* Checkbox */
        .checkbox-group { display: flex; align-items: center; gap: 0.5rem; margin: 1.5rem 0; }
        .form-checkbox { width: 1rem; height: 1rem; border: 1px solid hsl(var(--input)); border-radius: calc(var(--radius) / 2); background-color: hsl(var(--background)); cursor: pointer; transition: all 0.2s ease; }
        .form-checkbox:checked { background-color: hsl(var(--primary)); border-color: hsl(var(--primary)); }
        .checkbox-label { font-size: 0.875rem; color: hsl(var(--muted-foreground)); cursor: pointer; }

        /* Button */
        .btn-primary {
            width: 100%;
            height: 2.5rem;
            background-color: hsl(var(--primary));
            color: hsl(var(--primary-foreground));
            border: none;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 1rem;
        }
        .btn-primary:hover { background-color: hsl(var(--primary) / 0.9); }
        .btn-primary:focus { outline: none; box-shadow: 0 0 0 2px hsl(var(--ring) / 0.2); }

        /* Links */
        .auth-link { color: hsl(var(--primary)); text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.2s ease; }
        .auth-link:hover { text-decoration: underline; }

        /* Error Messages */
        .error-message { color: hsl(var(--destructive)); font-size: 0.75rem; margin-top: 0.25rem; }

        /* Password Requirements */
        .password-requirements { margin-top: 0.75rem; padding: 0.75rem; background-color: hsl(var(--muted)); border-radius: var(--radius); font-size: 0.75rem; grid-column: 1 / -1; border: 1px solid hsl(var(--border)); }
        .password-requirements h4 { margin: 0 0 0.5rem 0; font-size: 0.8rem; font-weight: 600; color: hsl(var(--foreground)); }
        .password-requirements ul { margin: 0; padding-left: 0; list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }
        .password-requirements li { margin-bottom: 0; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; line-height: 1.4; }
        .password-requirements li::before { content: "✗"; color: hsl(var(--destructive)); font-weight: bold; width: 1rem; text-align: center; }
        .password-requirements li.valid::before { content: "✓"; color: #10b981; }
        .password-requirements li.valid { color: #10b981; }

        /* Responsive */
        @media (max-width: 640px) {
            .auth-card { padding: 1.5rem; margin: 1rem; }
            .form-row { grid-template-columns: 1fr; }
            .password-requirements ul { grid-template-columns: 1fr; }
        }
    </style>

    <body>
        {{-- O botão de tema e o modal foram REMOVIDOS daqui porque já existem no guest.blade.php --}}

        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <img class="auth-logo" src="{{ asset($setting->gateway_logo) }}" alt="Logo" />
                    <h1 class="auth-title">Criar nova conta</h1>
                    <p class="auth-subtitle">Preencha os dados para ter acesso à plataforma</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="ref" value="{{ request('ref') }}">

                    <div class="form-group">
                        <label class="form-label" for="name">Nome Completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input" placeholder="Digite seu nome completo" required />
                        @if ($errors->has('name'))
                            <div class="error-message">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="telefone">Telefone</label>
                            <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}" class="form-input" placeholder="(11) 99999-9999" maxlength="15" required />
                            @if ($errors->has('telefone'))
                                <div class="error-message">{{ $errors->first('telefone') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-input" placeholder="Digite seu username" required />
                            @if ($errors->has('username'))
                                <div class="error-message">{{ $errors->first('username') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="Digite seu email" autocomplete="username" required />
                        @if ($errors->has('email'))
                            <div class="error-message">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="password">Senha</label>
                            <div class="password-container">
                                <input type="password" id="password" name="password" class="form-input" placeholder="Digite sua senha" autocomplete="new-password" required />
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <div class="password-requirements" id="passwordRequirements" style="display: none;">
                                <h4>Requisitos da senha:</h4>
                                <ul>
                                    <li id="req-length">Pelo menos 8 caracteres</li>
                                    <li id="req-lowercase">Uma letra minúscula (a-z)</li>
                                    <li id="req-uppercase">Uma letra maiúscula (A-Z)</li>
                                    <li id="req-number">Um número (0-9)</li>
                                    <li id="req-special">Um caractere especial (@$!%*?&...)</li>
                                </ul>
                            </div>
                            @if ($errors->has('password'))
                                <div class="error-message">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirmar Senha</label>
                            <div class="password-container">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Confirme sua senha" autocomplete="new-password" required />
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" class="form-checkbox" id="terms" name="terms" value="agree" required>
                        <label class="checkbox-label" for="terms">
                            Eu concordo com os <a href="#" class="auth-link" onclick="openTermsModal(event)">termos e condições</a>
                        </label>
                    </div>

                    <button type="submit" class="btn-primary">
                        Criar Conta
                    </button>

                    <div style="text-align: center;">
                        <a href="{{ route('login') }}" class="auth-link">
                            Já tem uma conta? Faça login aqui
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- As funções de tema e modal foram REMOVIDAS daqui --}}
        <script>
            // Mantivemos apenas os scripts que são EXCLUSIVOS para esta página

            // Password Toggle
            function togglePassword(inputId) {
                const input = document.getElementById(inputId);
                const button = input.nextElementSibling;
                const svg = button.querySelector('svg');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    svg.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><path d="M1 1l22 22"/>`;
                } else {
                    input.type = 'password';
                    svg.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
                }
            }

            // Phone Mask
            document.getElementById('telefone').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4,5})(\d{4})$/, '$1-$2');
                }
                e.target.value = value;
            });

            // Password validation
            function validatePassword(password) {
                return {
                    length: password.length >= 8,
                    lowercase: /[a-z]/.test(password),
                    uppercase: /[A-Z]/.test(password),
                    number: /\d/.test(password),
                    special: /[@$!%*?&+#^~`|\\/:";'<>,.=\-_\[\]{}()]/.test(password)
                };
            }

            function updatePasswordRequirements(password) {
                const requirements = validatePassword(password);
                const requirementsDiv = document.getElementById('passwordRequirements');
                
                requirementsDiv.style.display = password.length > 0 ? 'block' : 'none';
                
                Object.keys(requirements).forEach(key => {
                    const element = document.getElementById(`req-${key}`);
                    if (element) {
                        element.classList.toggle('valid', requirements[key]);
                    }
                });
            }

            // Add event listener to password input
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password');
                if (passwordInput) {
                    passwordInput.addEventListener('input', (e) => updatePasswordRequirements(e.target.value));
                    passwordInput.addEventListener('focus', (e) => updatePasswordRequirements(e.target.value));
                }
            });
        </script>
    </body>
</x-guest-layout>