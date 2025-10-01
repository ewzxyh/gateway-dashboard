@props([
    'label',
    'info',
    'icon',
    'color' => 'success'
    ])

<div class="mb-4 col-xxl-3 col-xl-4 col-md-6">
    <div class="adobe-glass-card">
        <div class="adobe-card-body px-4 py-4">
            <div class="mb-2 d-flex justify-content-between align-items-center">
                <div class="me-2">
                    <div class="display-5 text-{{ $color }} adobe-text fw-bold">{{ $info }}</div>
                    <div class="adobe-text-muted">{{ $label }}</div>
                </div>
                <div class="text-white icon-circle bg-{{ $color }} card-color"><i class="fa-solid {{ $icon }}"></i></div>
            </div>
        </div>
    </div>
</div>
