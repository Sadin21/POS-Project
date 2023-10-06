@php
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title') | Kasir</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

        @yield('style')
        <style>
            body.swal2-height-auto { height: 100vh !important; }
        </style>
    </head>

    <body class="d-flex flex-column">
        <nav class="flex-shrink-0 navbar navbar-light bg-white py-3 px-4 border-bottom shadow-sm d-flex justify-content-between align-items-center">
            <img src="{{ asset('assets/imgs/logo.png') }}" height="40" alt="FaaFoo Logo" loading="lazy" />

            <div class="dropdown">
                <div class="p-2 user-info d-flex align-items-center gap-2 cursor-pointer rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('assets/icons/person.svg') }}" alt="Profile" width="20px" height="20px" class="rounded-circle p-1">
                    {{-- <div class="text-truncate f14 user-select-none">{{ $user->name }}</div> --}}
                </div>
    
                <div class="dropdown-menu dropdown-menu-end p-2 border shadow bg-white" id="user-menu" style="width:300px">
                    <div class="d-flex w-100 align-items-center gap-4 px-2 pb-2 mb-2 border-bottom">
                        <div class="flex-grow-1">
                            {{-- <div class="text-truncate user-select-none fw-medium f20">{{ $user->name }}</div> --}}
                            {{-- <div class="d-flex gap-2 align-items-center mt-1">
                                @if ($user->email_verified_at)
                                    <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
                                    <div class="text-success fw-medium f12">Verified</div>
                                @else
                                    <ion-icon name="close-circle" class="text-danger"></ion-icon>
                                    <div class="text-danger fw-medium f12">Unverified</div>
                                @endif
                            </div> --}}
                        </div>
                        <img src="{{ asset('assets/icons/person.svg') }}" alt="Profile" width="50px" height="50px" class="rounded-circle p-1 flex-shrink-0">
                    </div>

                    <a class="w-100 dropdown-item d-flex align-items-center gap-2 p-2 mb-1 rounded cursor-pointer" href="">
                        <ion-icon name="settings" class="f20 flex-shrink-0"></ion-icon>
                        <div class="user-select-none fw-medium flex-grow-1">Ubah kata sandi</div>
                    </a>

                    <a class="w-100 dropdown-item d-flex align-items-center gap-2 p-2 rounded cursor-pointer" href="{{ route('auth.logout') }}">
                        <ion-icon name="log-out" class="f20 flex-shrink-0 text-danger"></ion-icon>
                        <div class="user-select-none fw-medium flex-grow-1 text-danger">Keluar</div>
                    </a>
                </div>
            </div>
        </nav>

        <div class="flex-grow-1 d-flex">
            <div class="sidebar bg-white overflow-auto flex-shrink-0 h-100 border-end py-2">
                @include('partials.sidebar')
            </div>

            <main class="flex-grow-1 p-4 overflow-auto">
                @yield('content')
            </main>
        </div>

        @include('partials.toast')

        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

        <script>
            const headers = { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'content-type': 'application/json' };
            
            function showAlert(icon, title, text) { Swal.fire({ icon, title, text }); }
            
            function toggleBtn(btn, isDisabled = true) { btn.classList.toggle('btn-loading'); btn.disabled = isDisabled; }
            
            function callApi({ url, next, error, method = 'GET', body = null }) {
                return fetch(url, { method, headers, body }).then(async (res) => {
                    const r = await res.json();

                    if (!res?.ok) throw r;
                    return r;
                }).then(next).catch(({ message }) => {
                    showAlert('error', 'Terjadi Kesalahan', message);
                    if (error) error();
                });
            }
            
            const forms = document.body.querySelector('form');
            if (forms) {
                forms.onsubmit = function (ev) {
                    ev.preventDefault();

                    if (!this.checkValidity()) ev.stopPropagation();
                    
                    ev.submitter.disabled = true;
                    ev.submitter.classList.toggle('btn-loading');
                    
                    this.submit();
                    this.classList.add('was-validated');
                };
            }

            document.getElementsByClassName('dropdown')[0].addEventListener('mouseenter', ({ target }) => target.children[0].click());
            document.getElementsByClassName('dropdown')[0].addEventListener('mouseleave', ({ target }) => target.children[0].click());
        </script>
    
        @yield('script')
    </body>
</html>
